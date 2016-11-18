<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Site\Classes;

use Application\Models\ServiceInterface;
use Application\Models\ServiceResponse;
use Blossom\Classes\Url;

class NagiosService extends ServiceInterface
{
    /**
     * Returns a list of all the methods and their parameters
     *
     * @return array
     */
    public function getMethods()
    {
        return [
            'uptimePercentage' => [
                'parameters' => ['hostname'=>'', 'service'=>'', 'username'=>'', 'password'=>''],
                'response'   => ['time_ok'=>'', 'percent'=>''],
                'labels'     => ['percent'=>'%']
            ]
        ];
    }

    public function uptimePercentage(array $params)
    {
        $numDays     = (int)$params[parent::PERIOD];
        $s = clone $params[parent::EFFECTIVE_DATE];
        $e = clone $params[parent::EFFECTIVE_DATE];

        $s->sub(new \DateInterval("P{$numDays}D"));
        $scopeStart = (int)$s->format('U');
        $scopeEnd   = (int)$e->format('U');

        $url = $this->base_url.'/cgi-bin/archivejson.cgi'
             . '?query=availability'
             . '&availabilityobjecttype=services'
             . '&statetypes=hard'
             . "&hostname=$params[hostname]"
             . "&servicedescription=$params[service]"
             . "&starttime=$scopeStart"
             . "&endtime=$scopeEnd";

        $json = parent::jsonQuery($url, [
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD  => "$params[username]:$params[password]"
        ]);

        if (isset($json->data->service->time_ok)) {
            $time_ok    = (int)$json->data->service->time_ok;
            $total      = $scopeEnd - $scopeStart;
            $percent    = round((($time_ok / $total) * 100), 2);
            $lastUpdate = (int)$json->result->last_data_update / 100;

            return new ServiceResponse(
                [
                    'time_ok' => $time_ok,
                    'percent' => $percent
                ],
                new \DateTime("@$lastUpdate")
            );
        }
    }
}
