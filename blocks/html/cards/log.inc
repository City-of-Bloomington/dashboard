<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Card $this->card
 */
use Application\Models\ServiceInterface;
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
            $response       = $entry->getResponse();
            $responseValues = '';
            foreach ($responseDefinition as $key=>$v) {
                $responseValues.= "<td>{$response[$key]}</td>";
            }

            echo "
            <tr><th>{$entry->getLogDate(DATE_FORMAT)}</th>
                $responseValues
                <td>{$entry->getEffectiveDate(DATETIME_FORMAT)}</td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>
