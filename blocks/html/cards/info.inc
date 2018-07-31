<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Card $this->card
 */
use Application\Models\Person;

$userCanEdit   = Person::isAllowed('cards', 'edit'  );
$userCanDelete = Person::isAllowed('cards', 'delete');
?>
<section class="info">
    <header>
        <h1><?= parent::escape($this->card->getName()); ?></h1>
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
    </header>
    <?php
        if ($userCanEdit) { $this->_include('cards/partials/parameters.inc'); }
        $this->_include('cards/card.inc');

        $url = $this->card->getDataUrl();
        if ($url) {
            echo "
            <dl><dt>{$this->_('dataUrl')}</dt>
                <dd><a href=\"$url\">$url</a></dd>
            </dl>
            ";
        }

        $this->_include('cards/log.inc');
    ?>
</section>
