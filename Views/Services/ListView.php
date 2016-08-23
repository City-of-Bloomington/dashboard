<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Views\Services;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class ListView extends Template
{
    public function __construct(array $vars)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format, $vars);

		$this->blocks[] = new Block('services/list.inc', ['services' => $this->services]);
    }
}
