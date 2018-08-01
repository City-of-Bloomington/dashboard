<?php
/**
 * @copyright 2016-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Application\Controllers;

use Application\Models\CardsTable;
use Application\Models\Group;

use Blossom\Classes\Controller;

class IndexController extends Controller
{
	public function index(array $params)
	{
        $search = [];
        if (!isset($_SESSION['USER'])) { $search['internal'] = false; }

        if (!empty($_GET['group_id'])) {
            try {
                $group = new Group($_GET['group_id']);
                $search['group_id'] = $group->getId();
            }
            catch (\Exception $e) { }
        }

        $table = new CardsTable();
        $list  = $table->find($search);
        return new \Application\Views\Cards\DashboardView(['cards'=>$list]);
	}
}
