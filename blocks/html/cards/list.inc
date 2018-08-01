<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->cards
 */
use Application\Models\Person;

$this->template->addToAsset('scripts', BASE_URI.'/js/cards/list.js');
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
            $groups = [];
            foreach ($c->getGroups() as $g) { $groups[] = $g->getName(); }
            $groups = parent::escape(implode(', ', $groups));

            $id          = $c->getId();
            $name        = parent::escape($c->getName());
            $service     = parent::escape($c->getService()->getName());
            $uri         = parent::generateUri('cards.view', ['id'=>$id]);
            $class       = $c->isInternal() ? 'class="internal"' : '';

            echo "
            <tr $class>
                <th>$id</th>
                <td><a href=\"$uri\">$name</a></td>
                <td>$service</td>
                <td>{$c->getMethod()}</td>
                <td>$groups</td>
            </tr>
            ";
        }
    ?>
    </table>
</section>
