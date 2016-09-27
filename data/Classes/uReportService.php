<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Site\Classes;

use Application\Models\ServiceInterface;
use Blossom\Classes\Url;

class uReportService extends ServiceInterface
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
                'parameters' => ['category_id'=>'', 'numDays'=>'']
            ]
        ];
    }

    public function onTimePercentage(array $params)
    {
        $url = new Url($this->base_url.'/onTimePercentage');
        $url->parameters = $params;
        $url->format     = 'json';
        $url = $url->__toString();

        echo "$url\n";

        $response = Url::get($url);
        if ($response) {
            $json = json_decode($response);
            return (int)$json->value;
        }
    }
}