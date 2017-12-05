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
use Application\Models\Person;
use Application\Models\ServiceInterface;
use Blossom\Classes\Database;

include realpath(__DIR__.'/../bootstrap.inc');

$opts = getopt('c::n::u::', ['help']);
if (isset($opts['help'])) {
    echo <<<EOT
DESCRIPTION
    This script populates past log entries for the cards in the dashboard.
    If no cards are specified, this script will use all cards in the system.
    If the number of days to generate is not specified, this script will
    generate 30 days of past logs.

OPTIONS
    Generic Program Information
        --help Output a usage message and exit

    Selection
        -cNUM
            Specify the card_id for generating past log values

        -nNUM
            Specify the number of days of past log entries to generate

        -uUSER
            Run the command as the specified user in the system.
            The user may be either a username or a numeric user_id.

EOT;
exit();
}


if (!empty($opts['u'])) {
    try { $_SESSION['USER'] = new Person($opts['u']); }
    catch (\Exception $e) {
        echo "Unknown user\n";
        exit();
    }
}

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

        $date->sub($oneDay);
    }
}
if (!count($list)) { echo "No Cards Found\n"; }
