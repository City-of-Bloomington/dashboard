<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Card;

use Blossom\Classes\Block;
use Blossom\Classes\Controller;

class ServicesController extends Controller
{
    public function index(array $params)
    {
        return new \Application\Views\Services\ListView(['services'=>Card::$services]);
    }
}
