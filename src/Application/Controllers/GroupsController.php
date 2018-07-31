<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Application\Controllers;

use Application\Models\Group;
use Application\Models\GroupsTable;

use Blossom\Classes\Controller;

class GroupsController extends Controller
{
    public function index(array $params)
    {
        $table = new GroupsTable();
        $list  = $table->find();

        return new \Application\Views\Groups\ListView(['groups'=>$list]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $group = new Group($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else {
            $group = new Group();
        }

        if (isset($group)) {
            if (isset($_POST['name'])) {
                try {
                    $group->handleUpdate($_POST);
                    $group->save();
                    header('Location: '.parent::generateUrl('groups.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }

            return new \Application\Views\Groups\UpdateView(['group'=>$group]);
        }
        else { return new \Application\Views\NotFoundView(); }
    }
}
