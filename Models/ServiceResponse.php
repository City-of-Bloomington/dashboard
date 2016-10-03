<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

class ServiceResponse
{
    public $response;
    public $effectiveDate;

    public function __construct(array $response, \DateTime $effectiveDate)
    {
        $this->response      = $response;
        $this->effectiveDate = $effectiveDate;
    }
}
