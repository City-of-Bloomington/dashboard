<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Group $this->group
 */
$id       = $this->group->getId();
$name     = parent::escape($this->group->getName());
$cssClass = parent::escape($this->group->getCSSClass());

$title = $id ? $this->_('group_edit') : $this->_('group_add');
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>
    <form method="post" action="<?= parent::generateUri('groups.update'); ?>">
        <fieldset>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                $helper = $this->template->getHelper('field');
                echo $helper->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label'    => $this->_('name'),
                    'value'    => $name,
                    'required' => true
                ]);

                echo $helper->field([
                    'name'     => 'cssClass',
                    'id'       => 'cssClass',
                    'label'    => $this->_('cssClass'),
                    'value'    => $cssClass,
                    'required' => true
                ]);

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(parent::generateUri('groups.index'));
            ?>
        </fieldset>
    </form>
</section>
