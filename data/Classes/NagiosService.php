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
            'serviceAvailability' => [
                'parameters' => ['hostname'=>'', 'service'=>'', 'username'=>'', 'password'=>''],
                'response'   => ['time_ok' =>'', 'percent'=>''],
                'labels'     => ['percent' =>'%']
            ],
            'hostgroupAvailability' => [
                'parameters' => ['hostgroup'=>'', 'username'=>'', 'password'=>''],
                'response'   => ['percent'=>''],
                'labels'     => ['percent'=>'%']
            ]
        ];
    }

    private function extractScope(array $params)
    {
        $numDays = (int)$params[parent::PERIOD];
        $start   = clone $params[parent::EFFECTIVE_DATE];
        $end     = clone $params[parent::EFFECTIVE_DATE];

        $start->sub(new \DateInterval("P{$numDays}D"));
        return [
            (int)$start->format('U'),
            (int)$end  ->format('U')
        ];
    }

    public function serviceAvailability(array $params)
    {
        list($scopeStart, $scopeEnd) = $this->extractScope($params);

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

            return new ServiceResponse([
                    'time_ok' => $time_ok,
                    'percent' => $percent
                ],
                new \DateTime("@$lastUpdate")
            );
        }
    }

    public function hostgroupAvailability(array $params)
    {
        list($scopeStart, $scopeEnd) = $this->extractScope($params);
        $total_time = $scopeEnd - $scopeStart;
        $sum_total_percent = 0;

        $url = $this->base_url.'/cgi-bin/archivejson.cgi'
             . '?query=availability'
             . '&availabilityobjecttype=hostgroups'
             . '&statetypes=hard'
             . "&hostgroup=$params[hostgroup]"
             . "&starttime=$scopeStart"
             . "&endtime=$scopeEnd";

        $json = parent::jsonQuery($url, [
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD  => "$params[username]:$params[password]"
        ]);

        if (isset(   $json->data->hostgroup->hosts)) {
            foreach ($json->data->hostgroup->hosts as $host) {
                $sum_total_percent += (($host->time_up / $total_time) * 100);
            }

            $numHosts   = count($json->data->hostgroup->hosts);
            $lastUpdate =  (int)$json->result->last_data_update / 100;
            return new ServiceResponse(
                [
                    'percent' => round(($sum_total_percent / $numHosts), 2),
                ],
                new \DateTime("@$lastUpdate")
            );
        }
    }
}
