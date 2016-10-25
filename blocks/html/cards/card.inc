<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Card $this->card
 */
$JSON_DATE_FORMAT = 'Y-m-d';

$id          = $this->card->getId();
$name        = parent::escape($this->card->getName());
$description = parent::escape($this->card->getDescription());
$target      = parent::escape($this->card->getTarget());
$uri         = parent::generateUri('cards.view', ['id'=>$this->card->getId()]);
$dataUrl     = parent::escape($this->card->getDataUrl());
$dataLink    = $dataUrl ? "<a href=\"$dataUrl\">{$this->_('dataUrl')}</a>" : '';

$entry       = $this->card->getLatestLogEntry();
$status      = $entry->getStatus();

$data = [];
foreach ($this->card->getLogEntries(10) as $entry) {
    $d        = $entry->getLogDate($JSON_DATE_FORMAT);
    $data[$d] = $entry->getMetricValue();
}
$data = parent::escape(json_encode($data));

$value  = $entry->getMetricValue();
$units  = $entry->getMetricUnits();
$period = $entry->getPeriodRange();

echo "
<article class=\"card $status\" id=\"card_$id\" data-logEntries=\"$data\" data-target=\"$target\">
    <header>
        <h1><a href=\"$uri\">$name</a></h1>
    </header>
    <div class=\"value\"><a href=\"$uri\">$value$units</a></div>
    <div class=\"period\">
        <a href=\"$uri\">
            {$period['start']->format(DATE_FORMAT)} - {$period['end']->format(DATE_FORMAT)}
        </a>
    </div>
    <div class=\"description\"><a href=\"$uri\">$description</a></div>
    <div class=\"target\"><a href=\"$uri\">{$this->_('target')}: $target$units</a></div>
    <div class=\"chart\"></div>
</article>
";
