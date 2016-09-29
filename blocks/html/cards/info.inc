<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Card $this->card
 */
use Application\Models\Person;
use Application\Models\ServiceInterface;

$fields = ['description', 'method', 'target', 'comparison'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->card->$get());
}
$userCanEdit   = Person::isAllowed('cards', 'edit'  );
$userCanDelete = Person::isAllowed('cards', 'delete');
?>
<section>
    <header>
        <h1><?= $description; ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            if ($userCanEdit) {
                echo $helper->buttonLink(
                    self::generateUri('cards.update', ['id'=>$this->card->getId()]),
                    $this->_('card_edit'),
                    'edit'
                );
            }
            if ($userCanDelete) {
                echo $helper->buttonLink(
                    self::generateUri('cards.delete', ['id'=>$this->card->getId()]),
                    $this->_('card_delete'),
                    'delete'
                );
            }
        ?>
        </div>

        <?php
            if ($userCanEdit) { $this->_include('cards/partials/parameters.inc'); }
        ?>

        <table>
        <?php
            $log = $this->card->getLogValues();
            foreach ($log as $entry) {
                echo "
                <tr><th>{$entry['logDate']->format(DATE_FORMAT)}</th>
                    <td>$entry[value]</td>
                    <td>{$entry[ServiceInterface::EFFECTIVE_DATE]->format(DATETIME_FORMAT)}</td>
                </tr>
                ";
            }
        ?>
        </table>
    </header>
</section>
