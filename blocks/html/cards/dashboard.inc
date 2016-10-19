<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->cards
 */
$JSON_DATE_FORMAT = 'Y-m-d';

foreach ($this->cards as $c) {
    $id          = $c->getId();
    $name        = parent::escape($c->getName());
    $description = parent::escape($c->getDescription());
    $target      = parent::escape($c->getTarget());
    $uri         = parent::generateUri('cards.view', ['id'=>$c->getId()]);
    $dataUrl     = parent::escape($c->getDataUrl());
    $dataLink    = $dataUrl ? "<a href=\"$dataUrl\">{$this->_('dataUrl')}</a>" : '';

    $entry       = $c->getLatestLogEntry();
    $status      = $entry->getStatus();

    $data = [];
    foreach ($c->getLogEntries(10) as $entry) {
        $d        = $entry->getLogDate($JSON_DATE_FORMAT);
        $data[$d] = $entry->getMetricValue();
    }
    $data = parent::escape(json_encode($data));

    echo "
    <article class=\"card $status\" id=\"card_$id\" data-logEntries=\"$data\" data-target=\"$target\">
        <header>
            <h1><a href=\"$uri\">$name</a></h1>
        </header>
        <div class=\"value\">{$entry->getMetricValue()} {$entry->getMetricUnits()}</div>
        <div class=\"chart\"></div>
        <div class=\"description\">$description</div>
        <div class=\"dataUrl\">$dataLink</div>
    </article>
    ";
}
