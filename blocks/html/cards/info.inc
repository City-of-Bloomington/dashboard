<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Card $this->card
 */
use Application\Models\Person;
use Application\Models\ServiceInterface;

$fields = ['description', 'method', 'target', 'comparison', 'responseKey'];
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
            <thead>
                <tr><th><?= $this->_('logDate'); ?></th>
                    <?php
                        $responseDefinition = $this->card->getMethodDefinition()['response'];
                        foreach ($responseDefinition as $key=>$v) {
                            $key = self::escape($key);
                            echo "<th>$key</th>";
                        }
                    ?>
                    <th><?= $this->_(ServiceInterface::EFFECTIVE_DATE); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
                $log = $this->card->getLogEntries();
                foreach ($log as $entry) {
                    $responseValues = '';
                    foreach ($responseDefinition as $key=>$v) {
                        $responseValues.= "<td>{$entry['response'][$key]}</td>";
                    }

                    echo "
                    <tr><th>{$entry['logDate']->format(DATE_FORMAT)}</th>
                        $responseValues
                        <td>{$entry[ServiceInterface::EFFECTIVE_DATE]->format(DATETIME_FORMAT)}</td>
                    </tr>
                    ";
                }
            ?>
            </tbody>
        </table>
    </header>
</section>
