<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Application\Models;

use Blossom\Classes\TableGateway;

class ServicesTable extends TableGateway
{
    public function __construct() { parent::__construct('services', __namespace__.'\Service'); }
}
