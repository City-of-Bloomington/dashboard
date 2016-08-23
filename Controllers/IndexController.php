<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\CardsTable;
use Blossom\Classes\Controller;

class IndexController extends Controller
{
	public function index(array $params)
	{
        $table = new CardsTable();
        $list  = $table->find();
        return new \Application\Views\Cards\DashboardView(['cards'=>$list]);
	}
}
