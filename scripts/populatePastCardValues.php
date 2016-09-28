<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\Card;
use Site\Classes\CkanService;
use Blossom\Classes\Database;

include './bootstrap.inc';

$ckan   = new CkanService('https://data.bloomington.in.gov');

$date   = new \DateTime();
$oneDay = new \DateInterval('P1D');

$card = new Card(2);
$params = $card->getParameters();

$sql = 'insert card_log values(?, ?, ?)';
$pdo = Database::getConnection();
$query = $pdo->prepare($sql);

for ($i=0; $i<30; $i++) {
    $params['asOfDate'] = $date->format('Y-m-d');

    $value = $ckan->onTimePercentage($params);
    $query->execute([$card->getId(), $date->format('Y-m-d H:i:s'), $value]);
    echo "{$date->format(DATE_FORMAT)} $value\n";

    $date->sub($oneDay);
}
