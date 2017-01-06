<?php
/**
 * Class for making calls via Google webservice APIs
 *
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Site\Classes;

use Application\Models\ServiceInterface;
use Application\Models\ServiceResponse;

class GoogleService extends ServiceInterface
{
    // Datetime format for timestamp used in Google Spreadsheets
    const DATETIME_FORMAT = 'n/j/Y G:i:s';

    /**
     * Returns a list of all the methods and their parameters
     *
     * @return array
     */
    public function getMethods()
    {
        return [
            'formsColumnAverage' => [
                'parameters' => ['spreadsheet_id'=>'', 'sheet'=>'', 'column'=>''],
                'response'   => ['average'=>'', 'count'=>''],
                'labels'     => ['average'=>'']
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

    /**
     * Computes the average value for a given column in a spreadsheet
     */
    public function formsColumnAverage(array $params)
    {
        list($scopeStart, $scopeEnd) = $this->extractScope($params);

        $service  = new \Google_Service_Sheets($this->getClient());
        $range    = "$params[sheet]!A2:$params[column]";
        $response = $service->spreadsheets_values->get($params['spreadsheet_id'], $range);
        $values   = $response->getValues();
        $count    = 0;
        $sum      = 0;
        $lastUpdate = null;
        foreach ($values as $row) {
            if (!empty($row[0])) {
                $timestamp = \Datetime::createFromFormat(self::DATETIME_FORMAT, trim($row[0]));
                if (!$lastUpdate) { $lastUpdate = $timestamp; }

                $time = (int)$timestamp->format('U');

                if ($scopeStart <= $time && $time <= $scopeEnd) {
                    // Convert the letter name of the column to 0-based index
                    $column_number = ord(strtoupper($params['column'])) - ord('A');

                    // This relies on PHP's type juggling to parse out a number
                    // http://php.net/manual/en/language.types.string.php#language.types.string.conversion
                    $sum +=  !empty($row[$column_number])
                        ? (int)trim($row[$column_number])
                        : 0;
                    $count++;
                }
            }
        }
        $average = $count ? round(($sum / $count), 2) : null;
        return new ServiceResponse([
                'average' => $average,
                'count'   => $count
            ],
            $lastUpdate
        );
    }

    private function getClient()
    {
        static $client = null;

        if (!$client) {
            $client = new \Google_Client();
            $client->setAuthConfig(GOOGLE_CREDENTIALS_FILE);
            $client->setScopes([\Google_Service_Sheets::SPREADSHEETS_READONLY]);
            $client->setSubject(GOOGLE_USER_EMAIL);
        }
        return $client;
    }
}
