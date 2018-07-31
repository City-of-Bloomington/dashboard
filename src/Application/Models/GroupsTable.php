<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Application\Models;

use Blossom\Classes\TableGateway;

class GroupsTable extends TableGateway
{
    public function __construct() { parent::__construct('groups', __namespace__.'\Group'); }
}
