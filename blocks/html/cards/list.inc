<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->cards
 */
use Application\Models\Person;
?>
<section>
    <header>
        <h1><?= $this->_(['card', 'cards', count($this->cards)]); ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');

            if (Person::isAllowed('cards', 'add')) {
                echo $helper->buttonLink(
                    self::generateUri('cards.update'),
                    $this->_('card_add'),
                    'add'
                );

            }
        ?>
        </div>
    </header>
    <table>
        <?php
            foreach ($this->cards as $c) {
                $description = self::escape($c->getDescription());
                $uri = self::generateUri('cards.view', ['id'=>$c->getId()]);
                echo "<tr><td><a href=\"$uri\">$description</a></td></tr>";
            }
        ?>
    </table>
</section>
