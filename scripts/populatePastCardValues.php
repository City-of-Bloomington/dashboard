<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\CardsTable;
use Application\Models\ServiceInterface;
use Blossom\Classes\Database;

include realpath(__DIR__.'/../bootstrap.inc');

$oneDay = new \DateInterval('P1D');

$table = new CardsTable();
$list  = $table->find();
foreach ($list as $card) {
    $date   = new \DateTime();

    for ($i=0; $i<30; $i++) {
        $sr = $card->queryService($date);
        $id = $card->getId();
        $d  = $date->format(DATE_FORMAT);

        if ($sr) {
            $card->logResponse($sr, $date);
            $v = json_encode($sr->response);
            echo "Card #$id $v $d\n";
        }
        else {
            echo "Card #$id ERROR $d\n";
        }

        $date->sub($oneDay);
    }
}
