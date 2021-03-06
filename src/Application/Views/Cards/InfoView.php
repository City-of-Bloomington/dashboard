<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Application\Views\Cards;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class InfoView extends Template
{
    public function __construct(array $vars)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format, $vars);

		$this->blocks[] = new Block('cards/info.inc', ['card' => $this->card]);
    }
}
