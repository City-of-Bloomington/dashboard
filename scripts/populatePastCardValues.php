<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\CardsTable;
use Application\Models\ServiceInterface;
use Blossom\Classes\Database;

include realpath(__DIR__.'/../bootstrap.inc');

$date   = new \DateTime();
$oneDay = new \DateInterval('P1D');


$table = new CardsTable();
$list  = $table->find();
foreach ($list as $card) {
    $params = $card->getParameters();

    for ($i=0; $i<30; $i++) {
        $result = $card->getValue($date);
        $id     = $card->getId();
        $d      = $date->format(DATE_FORMAT);

        if ($result) {
            $card->logValue($result, $date);
            echo "Card #$id {$result->value} $d\n";
        }
        else {
            echo "Card #$id ERROR $d\n";
        }

        $date->sub($oneDay);
    }
}