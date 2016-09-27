<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Card $this->card
 */
use Application\Models\Person;
?>
<section>
    <header>
        <h1><?= $this->_('card'); ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            if (Person::isAllowed('cards', 'edit')) {
                echo $helper->buttonLink(
                    self::generateUri('cards.update', ['id'=>$this->card->getId()]),
                    $this->_('card_edit'),
                    'edit'
                );
            }
            if (Person::isAllowed('cards', 'delete')) {
                echo $helper->buttonLink(
                    self::generateUri('cards.delete', ['id'=>$this->card->getId()]),
                    $this->_('card_delete'),
                    'delete'
                );
            }
        ?>
        </div>

        <table>
        <?php
            $service = parent::escape($this->card->getService()->getName());
            echo "<tr><th>{$this->_('service')}</th><td>$service</td></tr>";

            $fields = ['description', 'method', 'target', 'comparison'];
            foreach ($fields as $f) {
                $get = 'get'.ucfirst($f);
                $$f  = parent::escape($this->card->$get());
                echo "<tr><th>{$this->_($f)}</th><td>{$$f}</td></tr>";
            }

            $parameters = json_encode($this->card->getParameters());
            echo "<tr><th>{$this->_('parameters')}</th><td>$parameters</td></tr>";
        ?>
        </table>

        <table>
        <?php
            $log = $this->card->getLogValues();
            foreach ($log as $entry) {
                echo "
                <tr><th>{$entry['timestamp']->format(DATETIME_FORMAT)}</th>
                    <td>$entry[value]</td>
                </tr>
                ";
            }
        ?>
        </table>
    </header>
</section>
