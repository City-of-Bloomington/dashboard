<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Application\Models;

use Blossom\Classes\Url;

abstract class ServiceInterface
{
    const VALUE          = 'value';
    const EFFECTIVE_DATE = 'effectiveDate';
    const PERIOD         = 'period';

    protected $base_url;

    public function __construct($url)
    {
        $this->base_url = $url;
    }

    abstract public function getMethods();

    /**
     * Do a basic GET request and return the response as a JSON object
     *
     * This is mostly a convenience wrapper around CURL functions.
     * @see http://php.net/manual/en/function.curl-setopt.php
     *
     * @param  string    $url
	 * @param  array     $curl_options Additional options to set for the curl request
     * @return stdObject               The JSON object for the response
     */
    public static function jsonQuery($url, array $curl_options=null)
    {
        $response = Url::get($url, $curl_options);
        if ($response) {
            $json = json_decode($response);
            if ($json) {
                return $json;
            }
        }
    }
}
