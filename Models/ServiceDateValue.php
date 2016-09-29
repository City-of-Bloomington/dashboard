<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

class ServiceDateValue
{
    public $value;
    public $effectiveDate;

    public function __construct($value, \DateTime $effectiveDate)
    {
        $this->value         = $value;
        $this->effectiveDate = $effectiveDate;
    }
}
