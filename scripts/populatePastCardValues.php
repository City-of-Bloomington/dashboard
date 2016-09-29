<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\Card;
use Application\Models\ServiceInterface;
use Site\Classes\CkanService;
use Blossom\Classes\Database;

include realpath(__DIR__.'/../bootstrap.inc');

$ckan   = new CkanService('https://data.bloomington.in.gov');

$date   = new \DateTime();
$oneDay = new \DateInterval('P1D');

$card = new Card(2);
$params = $card->getParameters();

for ($i=0; $i<30; $i++) {
    $params[ServiceInterface::EFFECTIVE_DATE] = $date;

    $result = $ckan->onTimePercentage($params);
    $card->logValue($result, $date);
    echo "{$date->format(DATE_FORMAT)} {$result->value}\n";

    $date->sub($oneDay);
}
