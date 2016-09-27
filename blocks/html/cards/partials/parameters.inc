<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Card $this->card
 */
$service    = parent::escape($this->card->getService()->getName());
$parameters = json_encode($this->card->getParameters());

$fields = ['method', 'target', 'comparison', 'units'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->card->$get());
}
?>
<table>
    <tr><th><?= $this->_('service'   ); ?></th><td><?= $service;       ?></td></tr>
    <tr><th><?= $this->_('method'    ); ?></th><td><?= $method;        ?></td></tr>
    <tr><th><?= $this->_('target'    ); ?></th><td><?= $target.$units; ?></td></tr>
    <tr><th><?= $this->_('comparison'); ?></th><td><?= $comparison;    ?></td></tr>
    <tr><th><?= $this->_('parameters'); ?></th><td><?= $parameters;    ?></td></tr>
</table>
