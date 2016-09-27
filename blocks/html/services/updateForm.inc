<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Service $this->service
 */
use Application\Models\Service;

$fields = ['id', 'name', 'class', 'url'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = self::escape($this->service->$get());
}

$title = $id ? $this->_('service_edit') : $this->_('service_add');

$helper = $this->template->getHelper('field');
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>

    <form method="post" action="<?= parent::generateUri('services.update'); ?>">
        <fieldset>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                echo $helper->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label'    => $this->_('name'),
                    'value'    => $name,
                    'required' => true
                ]);

                $options = [];
                foreach (Service::availableClasses() as $c) { $options[] = ['value'=>$c]; }
                echo $helper->field([
                    'name'     => 'class',
                    'id'       => 'class',
                    'label'    => $this->_('class'),
                    'value'    => $class,
                    'type'     => 'select',
                    'options'  => $options,
                    'required' => true
                ]);

                echo $helper->field([
                    'name'     => 'url',
                    'id'       => 'url',
                    'label'    => $this->_('url'),
                    'value'    => $url,
                    'required' => true
                ]);

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(parent::generateUri('services.index'));
            ?>
        </fieldset>
    </form>
</section>