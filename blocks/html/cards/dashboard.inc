<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->cards
 */
$JSON_DATE_FORMAT = 'Y-m-d';

foreach ($this->cards as $c) {
    $id          = $c->getId();
    $description = parent::escape($c->getDescription());
    $target      = parent::escape($c->getTarget());
    $units       = parent::escape($c->getUnits());
    $entry       = $c->getLatestLogEntry();
    $value       = parent::escape($entry['value']);
    $status      = $c->getStatus ($entry['value']);
    $uri         = parent::generateUri('cards.view', ['id'=>$c->getId()]);

    $data = [];
    foreach ($c->getLogEntries(10) as $entry) {
        $d        = $entry['logDate']->format($JSON_DATE_FORMAT);
        $data[$d] = (int)$entry['value'];
    }
    $data = parent::escape(json_encode($data));
    echo "
    <article class=\"card $status\" id=\"card_$id\" data-logEntries=\"$data\">
        <header>
            <h1><a href=\"$uri\">$description</a></h1>
        </header>
        <div class=\"value\">$value$units</div>
        <div class=\"target\">{$this->_('target')}: $target$units</div>
        <div class=\"chart\"></div>
    </article>
    ";
}
