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

$entry       = $this->card->getLatestLogEntry();
$status      = $entry->getStatus();
$value       = $entry->getMetricValue();
$units       = $entry->getMetricUnits();
$display     = $value ? "$value$units" : '';


$period = $entry->getPeriodRange();

$data = [];
foreach ($this->card->getLogEntries(90) as $entry) {
    $d        = $entry->getLogDate($JSON_DATE_FORMAT);
    $data[$d] = $entry->getMetricValue();
}
$data = array_reverse($data);
$data = parent::escape(json_encode($data));



if ($this->card->getGroup_id()) {
    $g = $this->card->getGroup();
    $class = $g->getCSSClass();
    $n  = parent::escape($g->getName());
    $group = "
    <div class=\"group\">
        <span class=\"$class\">$n</span>
    </div>
    ";
}
else { $group = ''; }

echo "
<a href=\"$uri\">
<article class=\"card $status\" id=\"card_$id\" data-logEntries=\"$data\" data-target=\"$target\">
    <header>
        $group
        <h1>$name</h1>
    </header>
    <div class=\"value\">$display</div>
    <div class=\"period\">
        {$period['start']->format(DATE_FORMAT)} - {$period['end']->format(DATE_FORMAT)}
    </div>
    <div class=\"description\">$description</div>
    <div class=\"target\">{$this->_('target')}: $target$units</div>
    <div class=\"chart\"></div>
</article>
</a>
";
