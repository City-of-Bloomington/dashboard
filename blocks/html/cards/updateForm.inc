<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Card $this->card
 */
use Application\Models\Card;

$this->template->addToAsset('scripts', BASE_URI.'/js/cards/updateForm.js');

$fields = ['id', 'description', 'service', 'method', 'target', 'comparison'];
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

    <form method="post" action="<?= self::generateUri('cards.update'); ?>">
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

                $options = [['value'=>'']];
                foreach (array_keys(Card::$services) as $name) { $options[] = ['value'=>$name]; }
                echo $helper->field([
                    'name'    => 'service',
                    'id'      => 'service',
                    'label'   => $this->_('service'),
                    'value'   => $service,
                    'type'    => 'select',
                    'options' => $options
                ]);

                $options = [];
                if ($service && array_key_exists($service, Card::$services)) {
                    foreach (array_keys(Card::$services[$service]['methods']) as $m) {
                        $options[] = ['value'=>$m];
                    }
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
                foreach (Card::$comparisons as $c) { $options[] = ['value'=>$c, 'label'=>$this->_($c)]; }
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

                echo '<div id="parameters">';
                if ($method) {
                    $parameters = $this->card->getParameters();
                    foreach (Card::$services[$service]['methods'][$method]['parameters'] as $k=>$v) {
                        echo $helper->field([
                            'name'  => "parameters[$k]",
                            'id'    => "parameters_$k",
                            'label' => $k,
                            'value' => !empty($parameters[$k]) ? self::escape($parameters[$k]) : ''
                        ]);
                    }
                }
                echo '</div>';

                $helper = $this->template->getHelper('saveAndCancelButtons');
                $uri = $id
                    ? self::generateUri('cards.view', ['id'=>$id])
                    : self::generateUri('cards.index');
                echo $helper->saveAndCancelButtons($uri);
            ?>
        </fieldset>
    </form>
</section>
