<?php
/**
 * @copyright 2012-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
use Application\Models\Person;

include '../bootstrap.inc';
$person = new Person();
$person->setFirstname('{{ dashboard_admin.firstname }}');
$person->setLastname ('{{ dashboard_admin.lastname  }}');
$person->setEmail    ('{{ dashboard_admin.email     }}');
$person->setUsername ('{{ dashboard_admin.username  }}');
//$person->setPassword();
$person->setAuthenticationMethod('Employee');
$person->setRole('Administrator');

$person->save();
