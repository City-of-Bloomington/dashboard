<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Site\Classes;

use Application\Models\ServiceInterface;
use Application\Models\ServiceResponse;
use Blossom\Classes\Url;

class ZabbixService extends ServiceInterface
{
    private $auth;

    public function getMethods()
    {
        return [
            'serviceSLA' => [
                'parameters' => ['serviceid' =>'', 'username'=>'', 'password'=>''],
                'response'   => ['time_ok'   =>'', 'percent'=>''],
                'labels'     => ['percent'   =>'%']
            ]
        ];
    }

    public function serviceSLA(array $params)
    {
        list($start, $end) = self::extractScope($params);
        $connection = $this->getConnection($params['username'], $params['password']);
        $serviceid  = (string)$params['serviceid'];
        $request    = [
            'jsonrpc' => '2.0',
            'method'  => 'service.getsla',
            'id'      => 1,
            'auth'    => $this->auth,
            'params'  => [
                'serviceids' => $serviceid,
                'intervals'  => [
                    'from' => $start,
                    'to'   => $end
                ]
            ]
        ];
        curl_setopt($connection, CURLOPT_POSTFIELDS, json_encode($request));
        $json = $this->jsonPost($connection);
        return new ServiceResponse([
            'time_ok' => $json['result'][$serviceid]['sla'][0]['okTime'],
            'percent' => $json['result'][$serviceid]['sla'][0]['sla'   ]
        ], new \DateTime());
    }

    /**
     * Converts the standard EFFECTIVE_DATE and PERIOD to start and end dates
     */
    private static function extractScope(array $params): array
    {
        $numDays =  (int)$params[parent::PERIOD];
        $start   = clone $params[parent::EFFECTIVE_DATE];
        $end     = clone $params[parent::EFFECTIVE_DATE];

        $start->sub(new \DateInterval("P{$numDays}D"));
        return [
            (int)$start->format('U'),
            (int)$end  ->format('U')
        ];
    }

    private function getConnection(string $username, string $password)
    {
        static $connection;

        if (!$connection) {
             $connection = curl_init($this->base_url.'/api_jsonrpc.php');

            curl_setopt($connection, CURLOPT_POST,           true);
            curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($connection, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($connection, CURLOPT_HTTPHEADER, ['Content-Type: application/json-rpc']);
            curl_setopt($connection, CURLOPT_POSTFIELDS, json_encode([
                'jsonrpc' => '2.0',
                'method'  => 'user.login',
                'id'      => 1,
                'auth'    => null,
                'params'  => [
                    'user'     => $username,
                    'password' => $password
                ]
            ]));
            $json       = $this->jsonPost($connection);
            $this->auth = $json['result'];
        }

        return $connection;
    }

    private function jsonPost($connection): array
    {
        $response = curl_exec($connection);
        if (!$response)              { throw new \Exception(curl_error($connection)); }
        $json     = json_decode($response, true);
        if  (empty($json['result'])) { throw new \Exception('zabbixService/emptyResult'); }
        if (!empty($json['error' ])) { throw new \Exception($json['error']['data']); }
        return $json;
    }
}
