<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\CardsTable;
use Application\Models\Group;

use Blossom\Classes\Controller;

class IndexController extends Controller
{
	public function index(array $params)
	{
        if (!empty($_GET['group_id'])) {
            try {
                $group = new Group($_GET['group_id']);
            }
            catch (\Exception $e) { }
        }

        $search = isset($group) ? ['group_id' => $group->getId()] : null;

        $table = new CardsTable();
        $list  = $table->find($search);
        return new \Application\Views\Cards\DashboardView(['cards'=>$list]);
	}
}
