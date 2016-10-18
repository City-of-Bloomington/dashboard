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
    $entry       = $c->getLatestLogEntry();
    $status      = $c->getStatus($entry);
    $uri         = parent::generateUri('cards.view', ['id'=>$c->getId()]);
    $dataUrl     = parent::escape($c->getDataUrl());
    $dataLink    = $dataUrl ? "<a href=\"$dataUrl\">{$this->_('dataUrl')}</a>" : '';

    $data = [];
    foreach ($c->getLogEntries(10) as $entry) {
        $d        = $entry['logDate']->format($JSON_DATE_FORMAT);
        $data[$d] = (int)$entry['response'][$c->getResponseKey()];
    }
    $data = parent::escape(json_encode($data));
    echo "
    <article class=\"card $status\" id=\"card_$id\" data-logEntries=\"$data\" data-target=\"$target\">
        <header>
            <h1><a href=\"$uri\">$description</a></h1>
        </header>
        <div class=\"response\">
    ";
        foreach ($entry['response'] as $key=>$value) {
            echo "<div class=\"$key\">$value $key</div>";
        }
    echo "
        </div>
        <div class=\"dataUrl\">$dataLink</div>
        <div class=\"target\">{$this->_('target')}: $target</div>
        <div class=\"chart\"></div>
    </article>
    ";
}
