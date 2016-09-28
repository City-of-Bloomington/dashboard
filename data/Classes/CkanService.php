<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Site\Classes;

use Application\Models\ServiceInterface;
use Blossom\Classes\Url;

class CkanService extends ServiceInterface
{
    /**
     * Returns a list of all the methods and their parameters
     *
     * @return array
     */
    public function getMethods()
    {
        return [
            'onTimePercentage' => [
                'parameters' => ['resource_id'=>'', 'numDays'=>'', 'slaDays'=>'', 'asOfDate'=>'']
            ]
        ];
    }

    public function onTimePercentage(array $params)
    {
        $resource_id = preg_replace('/[^0-9a-f\-]/', '', $params['resource_id']);
        $numDays     = (int)$params['numDays'];
        $slaDays     = (int)$params['slaDays'];
        $asOfDate    = !empty($params['asOfDate']) ? preg_replace('/[^0-9\-]/', '', $params['asOfDate']) : null;

        $dateRangeFilter = $asOfDate
            ? "'$asOfDate'::date > requested_datetime and requested_datetime > ('$asOfDate'::date - interval '$numDays day')"
            : "requested_datetime > (now() - interval '$numDays day')";

        $sql = "select floor(x.ontime::real / x.total::real * 100) as percentage
                from  (select
                        (select count(*) from \"$resource_id\" where $dateRangeFilter) as total,
                        (select count(*) from \"$resource_id\"
                            where $dateRangeFilter
                            and least(closed_date, current_timestamp)::date - requested_datetime::date <= $slaDays)  as ontime
                ) x";
        $sql = preg_replace('/\s+/', ' ', $sql);

        $url = new Url($this->base_url.'/api/action/datastore_search_sql');
        $url->sql = $sql;
        $url = $url->__toString();

        $response = Url::get($url);
        if ($response) {
            $json = json_decode($response);
            return (int)$json->result->records[0]->percentage;
        }
    }
}