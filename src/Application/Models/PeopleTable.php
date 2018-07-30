<?php
/**
 * @copyright 2013-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\Paginator;
use Blossom\Classes\TableGateway;

class PeopleTable extends TableGateway
{
    public function __construct() { parent::__construct('people', __namespace__.'\Person'); }

	/**
	 * @param  array $fields       Key value pairs to select on
	 * @param  array $order        The default ordering to use for select
	 * @param  int   $itemsPerPage
	 * @param  int   $currentPage
	 * @return array|Paginator
	 *
	 * @see https://github.com/auraphp/Aura.SqlQuery
	 */
	public function find(array $fields=null, array $order=['lastname'], int $itemsPerPage=null, int $currentPage=null)
	{
        $select = $this->queryFactory->newSelect();
        $select->cols(['p.*'])
               ->from('people as p');
        if ($order) { $select->orderBy($order); }

		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				switch ($key) {
					case 'user_account':
						if ($value) {
							$select->where('username is not null');
						}
						else {
							$select->where('username is null');
						}
					break;

					default:
                        $select->where("$key=?", $value);
				}
			}
		}
		return parent::performSelect($select, $itemsPerPage, $currentPage);
	}
}
