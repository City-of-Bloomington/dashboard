<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\CardsTable;

include realpath(__DIR__.'/../bootstrap.inc');

$table = new CardsTable();
$cards = $table->find();
foreach ($cards as $c) {
    $v = $c->queryService();
    $c->logResponse($v);
    echo "{$c->getDescription()}: $v\n";
}
