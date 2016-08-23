<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
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
            $id          = $c->getId();
            $description = self::escape($c->getDescription());
            $uri         = self::generateUri('cards.view', ['id'=>$id]);
            $comparison  = $this->_($c->getComparison());
            $parameters  = json_encode($c->getParameters());
            echo "
            <tr><th>$id</th>
                <td><a href=\"$uri\">$description</a></td>
                <td>{$c->getService()}</td>
                <td>{$c->getMethod()}</td>
                <td>$comparison</td>
                <td>{$c->getTarget()}</td>
                <td>$parameters</td>
            </tr>
            ";
        }
    ?>
    </table>
</section>
