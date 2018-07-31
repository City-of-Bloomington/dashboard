<?php
/**
 * Saves the current metric value for each card.
 *
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *
 * This is probably the script to use for nightly cron jobs.
 * It only does one metric query per card, so it shouldn't take too
 * long to run.
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

    $sr = null;
    try {
        $sr = $card->queryService($date);
        $id = $card->getId();
        $d  = $date->format(DATE_FORMAT);
    }
    catch (\Exception $e) {
        echo $e->getMessage()."\n";
    }

    if ($sr) {
        $card->logResponse($sr, $date);
        $v = json_encode($sr->response);
        echo "Card #$id $v $d\n";
    }
    else {
        echo "Card #$id ERROR $d\n";
    }
}
