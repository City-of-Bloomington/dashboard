<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->groups
 */
use Application\Models\Person;
?>
<section>
    <header>
        <h1><?= $this->_(['group', 'groups', count($this->groups)]); ?></h1>
        <div class="tools">
        <?php
            $helper = $this->template->getHelper('buttonLink');
            if (Person::isAllowed('groups', 'add')) {
                echo $helper->buttonLink(
                    parent::generateUri('groups.update'),
                    $this->_('group_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
    <table>
    <?php
        $userCanEdit = Person::isAllowed('groups', 'edit');
        $editButton  = '';

        foreach ($this->groups as $g) {
            if ($userCanEdit) {
                $editButton = $helper->buttonLink(
                    parent::generateUri('groups.update', ['id'=>$g->getId()]),
                    $this->_('group_edit'),
                    'edit'
                );
            }
            $name     = parent::escape($g->getName());
            $cssClass = parent::escape($g->getCSSClass());
            echo "
            <tr><td>$editButton</td>
                <td>$name</td>
                <td>$cssClass</td>
            </tr>
            ";
        }
    ?>
    </table>
</section>
