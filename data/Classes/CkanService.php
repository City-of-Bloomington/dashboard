<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Site\Classes;

use Application\Models\ServiceInterface;
use Application\Models\ServiceResponse;
use Blossom\Classes\Url;

class CkanService extends ServiceInterface
{
    const PSQL_DATETIME_FORMAT = 'c';
    const SOLR_DATETIME_FORMAT = 'Y-m-d\TH:i:s\Z';

    /**
     * Returns a list of all the methods and their parameters
     *
     * @return array
     */
    public function getMethods()
    {
        return [
            'onTimePercentage' => [
                'parameters' => ['resource_id'=>'', 'slaDays'=>''],
                'response'   => ['total'=>'', 'ontime'=>'', 'percent'=>''],
                'labels'     => ['percent'=>'%']
            ],
            'rowCount' => [
                'parameters' => ['resource_id' => ''],
                'response'   => ['count' => ''],
                'labels'     => ['count' => '']
            ],
            'datasetCount' => [
                'parameters' => ['' => ''],
                'response'   => ['count' => ''],
                'labels'     => ['count' => '']
            ]
        ];
    }

    /**
     * @param  string    $sql
     * @return stdObject       JSON response
     */
    private function sqlQuery($sql)
    {
        $sql = preg_replace('/\s+/', ' ', $sql);

        $url = new Url($this->base_url.'/api/action/datastore_search_sql');
        $url->sql = $sql;
        $url = $url->__toString();

        return parent::jsonQuery($url);
    }

    public function onTimePercentage(array $params)
    {
        $resource_id = preg_replace('/[^0-9a-f\-]/', '', $params['resource_id']);
        $numDays     = (int)$params[parent::PERIOD];
        $slaDays     = (int)$params['slaDays'];

        $s = clone $params[parent::EFFECTIVE_DATE];
        $e = clone $params[parent::EFFECTIVE_DATE];

        $s->sub(new \DateInterval("P{$numDays}D"));
        $scopeStart = $s->format(self::PSQL_DATETIME_FORMAT);
        $scopeEnd   = $e->format(self::PSQL_DATETIME_FORMAT);

        $scopeFilter = "('$scopeStart'::timestamp <= least(closed_date, current_timestamp) and '$scopeEnd'::timestamp >= requested_datetime)";

        $sql = "select  x.total,
                        x.ontime,
                        case x.total
                            when 0 then null
                            else floor(x.ontime::real / x.total::real * 100)
                        end as percentage,
                        x.effectiveDate
                from  (select
                        (select count(*)              from \"$resource_id\" where $scopeFilter) as total,
                        (select max(updated_datetime) from \"$resource_id\" where $scopeFilter) as effectiveDate,
                        (select count(*)              from \"$resource_id\" where $scopeFilter
                            and least(closed_date, current_timestamp)::date - requested_datetime::date <= $slaDays)  as ontime
                ) x";

        $json = $this->sqlQuery($sql);
        if ($json) {
            $p = !is_null($json->result->records[0]->percentage)
                ?    (int)$json->result->records[0]->percentage
                : null;
            return new ServiceResponse(
                [
                    'total'   => (int)$json->result->records[0]->total,
                    'ontime'  => (int)$json->result->records[0]->ontime,
                    'percent' => $p
                ],
                new \DateTime($json->result->records[0]->effectivedate)
            );
        }
    }

    public function rowCount(array $params)
    {
        $resource_id = preg_replace('/[^0-9a-f\-]/', '', $params['resource_id']);
        $numDays     = (int)$params[parent::PERIOD];

        $s = clone $params[parent::EFFECTIVE_DATE];
        $e = clone $params[parent::EFFECTIVE_DATE];

        $s->sub(new \DateInterval("P{$numDays}D"));
        $scopeStart = $s->format(self::PSQL_DATETIME_FORMAT);
        $scopeEnd   = $e->format(self::PSQL_DATETIME_FORMAT);

        $sql = "select count(*) as count from \"$resource_id\"";
        $json = $this->sqlQuery($sql);
        return $json->success
            ? new ServiceResponse(['count' => (int)$json->result->records[0]->count], new \DateTime())
            : new ServiceResponse(['count' => 0], new \DateTime());
    }

    public function datasetCount(array $params)
    {
        $d = clone($params[parent::EFFECTIVE_DATE]);
        $d->setTimezone(new \DateTimeZone('UTC'));
        $effectiveDate = $d->format(self::SOLR_DATETIME_FORMAT);

        $url = new Url($this->base_url.'/api/3/action/package_search');
        $url->q  = '*:*';
        $url->fq = "metadata_created:[* TO $effectiveDate]";

        $json = parent::jsonQuery($url);
        if (isset($json->result->count)) {
            return new ServiceResponse(
                ['count'=> (int)$json->result->count],
                $params[parent::EFFECTIVE_DATE]
            );
        }
    }
}
