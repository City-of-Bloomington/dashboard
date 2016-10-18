<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Card $this->card
 */
use Application\Models\Card;
use Application\Models\ServicesTable;

$this->template->addToAsset('scripts', BASE_URI.'/js/cards/updateForm.js');

$fields = [
    'id', 'description', 'dataUrl', 'service_id', 'method',
    'target', 'period', 'comparison', 'responseKey',
];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = $this->card->$get();
}

$title = $id ? $this->_('card_edit') : $this->_('card_add');
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>

    <form method="post" action="<?= parent::generateUri('cards.update'); ?>">
        <fieldset>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                $helper = $this->template->getHelper('field');
                echo $helper->field([
                    'name'     => 'description',
                    'id'       => 'description',
                    'label'    => $this->_('description'),
                    'value'    => $description,
                    'required' => true
                ]);

                echo $helper->field([
                    'name'  => 'dataUrl',
                    'id'    => 'dataUrl',
                    'label' => $this->_('dataUrl'),
                    'help'  => $this->_('dataUrl_help', 'messages'),
                    'value' => $dataUrl
                ]);

                $options = [['value'=>'']];
                $table   = new ServicesTable();
                $list    = $table->find();
                foreach ($list as $s) { $options[] = ['value'=>$s->getId(), 'label'=>$s->getName()]; }
                echo $helper->field([
                    'name'    => 'service_id',
                    'id'      => 'service_id',
                    'label'   => $this->_('service'),
                    'value'   => $service_id,
                    'type'    => 'select',
                    'options' => $options
                ]);

                $options = [];
                $service = $this->card->getService();
                if ($service) {
                    foreach ($service->getMethods() as $m=>$o) { $options[] = ['value'=>$m]; }
                }
                echo $helper->field([
                    'name'    => 'method',
                    'id'      => 'method',
                    'label'   => $this->_('method'),
                    'value'   => $method,
                    'type'    => 'select',
                    'options' => $options
                ]);

                $options = [];
                $definition = $this->card->getMethodDefinition();
                if ($definition) {
                    foreach ($definition['response'] as $key=>$v) { $options[] = ['value'=>$key]; }
                }
                echo $helper->field([
                    'name'    => 'responseKey',
                    'id'      => 'responseKey',
                    'label'   => $this->_('responseKey'),
                    'help'    => $this->_('responseKey_help', 'messages'),
                    'value'   => $responseKey,
                    'type'    => 'select',
                    'options' => $options
                ]);

                $options = [];
                foreach (Card::$comparisons as $c) { $options[] = ['value'=>$c, 'label'=>parent::escape($this->_($c))]; }
                echo $helper->field([
                    'name'    => 'comparison',
                    'id'      => 'comparison',
                    'label'   => $this->_('comparison'),
                    'value'   => $comparison,
                    'type'    => 'select',
                    'options' => $options
                ]);

                echo $helper->field([
                    'name'  => 'target',
                    'id'    => 'target',
                    'label' => $this->_('target'),
                    'value' => $target,
                    'type'  => 'number'
                ]);
                echo $helper->field([
                    'name'  => 'period',
                    'id'    => 'period',
                    'label' => $this->_('period'),
                    'help'  => $this->_('period_help', 'messages'),
                    'value' => $period,
                    'type'  => 'number'
                ]);

                echo '<div id="parameters">';
                if ($method) {
                    $parameters = $this->card->getParameters();
                    if ($service) {
                        $methods = $service->getMethods();
                        if (!empty(  $methods[$method]['parameters'])) {
                            foreach ($methods[$method]['parameters'] as $k=>$v) {
                                echo $helper->field([
                                    'name'  => "parameters[$k]",
                                    'id'    => "parameters_$k",
                                    'label' => $k,
                                    'value' => !empty($parameters[$k]) ? parent::escape($parameters[$k]) : ''
                                ]);
                            }
                        }
                    }
                }
                echo '</div>';

                $helper = $this->template->getHelper('saveAndCancelButtons');
                $uri = $id
                    ? parent::generateUri('cards.view', ['id'=>$id])
                    : parent::generateUri('cards.index');
                echo $helper->saveAndCancelButtons($uri);
            ?>
        </fieldset>
    </form>
</section>
