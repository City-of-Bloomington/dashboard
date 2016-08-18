<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Card $this->card
 */
$fields = ['id', 'description'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = $this->$get();
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

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(self::generateUri('cards.view', ['id'=>$id]));
            ?>
        </fieldset>
    </form>
</section>
