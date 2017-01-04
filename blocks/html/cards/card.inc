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
$visibility  = $this->card->isInternal() ? 'internal' : '';

$entry       = $this->card->getLatestLogEntry();
if ($entry) {
    $status      = $entry->getStatus();
    $value       = $entry->getMetricValue();
    $units       = $entry->getMetricUnits();
    $display     = is_numeric($value) ? "$value$units" : '';

    $period      = $entry->getPeriodRange();
    $periodStart = $period['start']->format(DATE_FORMAT);
    $periodEnd   = $period['end'  ]->format(DATE_FORMAT);
}
else {
    $status      = '';
    $value       = '';
    $units       = '';
    $display     = '';

    $periodStart = '';
    $periodEnd   = '';
}

$data = [];
foreach ($this->card->getLogEntries(90) as $entry) {
    $d        = $entry->getLogDate($JSON_DATE_FORMAT);
    $data[$d] = $entry->getMetricValue();
}
$data = array_reverse($data);
$data = parent::escape(json_encode($data));

$groups = $this->card->getGroups();
if ($groups) {
    $groupHTML = '<div class="group">';
    foreach ($groups as $g) {
        $n = parent::escape($g->getName());
        $groupHTML.= "<span class=\"{$g->getCSSClass()}\">$n</span>";
    }
    $groupHTML.= '</div>';
}
else { $groupHTML = ''; }

echo "
<a href=\"$uri\">
<article class=\"card $status $visibility\" id=\"card_$id\" data-logEntries=\"$data\" data-target=\"$target\">
    <header>
        $groupHTML
        <h1>$name</h1>
    </header>
    <div class=\"value\">$display</div>
    <div class=\"period\">$periodStart - $periodEnd</div>
    <div class=\"description\">$description</div>
    <div class=\"target\">{$this->_('target')}: $target$units</div>
    <div class=\"chart\"></div>
</article>
</a>
";
