<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Views\Cards;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class UpdateView extends Template
{
    public function __construct(array $vars)
    {
        $format   = !empty($_REQUEST['format' ]) ? $_REQUEST['format'] : 'html';
        $filename = !empty($_REQUEST['partial']) ? 'partial'           : 'default';
        parent::__construct($filename, $format, $vars);

		$this->blocks[] = new Block('cards/updateForm.inc', ['card' => $this->card]);
    }
}
