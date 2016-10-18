<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
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
</table>
