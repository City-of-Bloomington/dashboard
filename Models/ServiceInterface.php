<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

abstract class ServiceInterface
{
    const VALUE          = 'value';
    const EFFECTIVE_DATE = 'effectiveDate';
    const PERIOD         = 'period';

    protected $base_url;

    public function __construct($url)
    {
        $this->base_url = $url;
    }

    abstract public function getMethods();
}
