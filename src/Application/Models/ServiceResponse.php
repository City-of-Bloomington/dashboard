<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Application\Models;

class ServiceResponse
{
    public $response;
    public $effectiveDate;

    /**
     * Simple constructor for creating responses.
     *
     * In the response, make sure to use null values for no-data situations.
     * Zeros in the response are actual data.  We must not confuse zeros with
     * lack of data.
     *
     * @param array    $response
     * @param DateTime $effectiveDate
     */
    public function __construct(array $response, \DateTime $effectiveDate)
    {
        $this->response      = $response;
        $this->effectiveDate = $effectiveDate;
    }
}
