<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Application\Views\Groups;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class UpdateView extends Template
{
    public function __construct(array $vars)
    {
        parent::__construct('default', 'html', $vars);

		$this->blocks[] = new Block('groups/updateForm.inc', ['group' => $this->group]);
    }
}
