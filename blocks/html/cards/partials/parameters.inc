<?php
/**
 * @copyright 2016-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Card $this->card
 */
use Application\Models\Person;

$service = $this->card->getService();
$serviceName = parent::escape($service->getName());
if (Person::isAllowed('services', 'view')) {
    $uri = parent::generateUri('services.view', ['id'=>$service->getId()]);
    $serviceName = "<a href=\"$uri\">$serviceName</a>";
}

$parameters = json_encode($this->card->getParameters());

$fields = ['description', 'method', 'period', 'target', 'comparison', 'responseKey'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->card->$get());
}
$visibility = $this->card->isInternal() ? $this->_('internal') : $this->_('public');
$active     = $this->card->isActive()   ? $this->_('active'  ) : $this->_('disabled');

$groupNames = [];
$groups     = $this->card->getGroups();
foreach ($groups as $g) { $groupNames[] = $g->getName(); }
$groupNames = parent::escape(implode(', ', $groupNames));
?>
<p><?= $description; ?></p>
<table>
    <tr><th><?= $this->_('service'    ); ?></th><td><?= $serviceName; ?></td></tr>
    <tr><th><?= $this->_('method'     ); ?></th><td><?= $method;      ?></td></tr>
    <tr><th><?= $this->_('responseKey'); ?></th><td><?= $responseKey; ?></td></tr>
    <tr><th><?= $this->_('period'     ); ?></th><td><?= $period;      ?></td></tr>
    <tr><th><?= $this->_('target'     ); ?></th><td><?= $target;      ?></td></tr>
    <tr><th><?= $this->_('comparison' ); ?></th><td><?= $comparison;  ?></td></tr>
    <tr><th><?= $this->_('parameters' ); ?></th><td><?= $parameters;  ?></td></tr>
    <tr><th><?= $this->_('visibility' ); ?></th><td><?= $visibility;  ?></td></tr>
    <tr><th><?= $this->_('active'     ); ?></th><td><?= $active;      ?></td></tr>
    <tr><th><?= $this->_(['group', 'groups', count($groups)]);        ?></th><td><?= $groupNames; ?></td></tr>
</table>
