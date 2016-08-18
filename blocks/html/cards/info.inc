<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Card $this->card
 */
use Application\Models\Person;

$fields = ['id', 'description'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = $this->$get();
}
?>
<section>
    <header>
        <h1><?= $this->_('card'); ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            if (Person::isAllowed('cards', 'edit')) {
                echo $helper->buttonLink(
                    self::generateUri('cards.update', ['id'=>$id]),
                    $this->_('card_edit'),
                    'edit'
                );
            }
            if (Person::isAllowed('cards', 'delete')) {
                echo $helper->buttonLink(
                    self::generateUri('cards.delete', ['id'=>$id]),
                    $this->_('card_delete'),
                    'delete'
                );
            }
        ?>
        </div>
        <table>
            <tr><th><?= $this->_('description'); ?></th>
                <td><?= $description; ?></td>
            </tr>
        </table>
    </header>
</section>
