<?php
/**
 * Generates past log entries for all cards
 *
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 *
 * It is useful to be able to generate what a metric query would have
 * returned at various points in the past.  This is how we can populate
 * a newly added card with past data.
 */
use Application\Models\CardsTable;
use Application\Models\ServiceInterface;
use Blossom\Classes\Database;

include realpath(__DIR__.'/../bootstrap.inc');

$opts = getopt('c::n::');

$numDays = !empty($opts['n']) && is_numeric($opts['n'])
    ? (int)$opts['n']
    : 30;

$oneDay = new \DateInterval('P1D');

$table  = new CardsTable();
$search = !empty($opts['c']) ? ['id'=>(int)$opts['c']] : null;
$list   = $table->find($search);
foreach ($list as $card) {
    $date   = new \DateTime();

    for ($i=0; $i<$numDays; $i++) {
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
