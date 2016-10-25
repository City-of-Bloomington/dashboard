<?php
/**
 * Example CSS script for group icon handling
 *
 * This will look for image files in SITE_HOME/groups that
 * match the cssClass for each group.  For groups that have
 * image files in SITE_HOME/groups you will probably want to render
 * out some custom CSS.
 *
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\GroupsTable;

$table = new GroupsTable();
$list  = $table->find();

echo '<style type="text/css">';
foreach ($list as $g) {
    $icon = $g->getIconUri();
    if ($icon) {
        $cssClass = $g->getCSSClass();

        echo "
        .$cssClass {
            background:url('$icon') top left no-repeat;
            background-size: 24px;
            display:block;
            width:24px; height:24px;
            text-indent:100%; white-space:nowrap; overflow:hidden;
        }
        ";
    }
}
echo '</style>';
