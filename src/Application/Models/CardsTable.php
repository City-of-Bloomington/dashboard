<?php
/**
 * @copyright 2016-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\TableGateway;

class CardsTable extends TableGateway
{
    public function __construct() { parent::__construct('cards', __namespace__.'\Card'); }

	/**
	 * @param  array $fields       Key value pairs to select on
	 * @param  array $order        The default ordering to use for select
	 * @param  int   $itemsPerPage
	 * @param  int   $currentPage
	 * @return array|Paginator
	 *
	 * @see https://github.com/auraphp/Aura.SqlQuery
	 */
	public function find(array $fields=null, array $order=[], int $itemsPerPage=null, int $currentPage=null)
	{
        $select = $this->queryFactory->newSelect();
        $select->cols(['c.*'])
               ->from('cards as c');
        if ($order) { $select->orderBy($order); }

        if (!isset($_SESSION['USER']) || !Person::isAllowed('cards', 'internal')) {
            $select->where('not c.internal');
        }

		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				switch ($key) {
                    case 'group_id':
                        $select->join('inner', 'card_groups as g', 'c.id=g.card_id');
                        $select->where("g.group_id=?", $value);
                    break;

					default:
                        $select->where("$key=?", $value);
				}
			}
		}
		return parent::performSelect($select, $itemsPerPage, $currentPage);
	}
}
