<?php
/**
 * @copyright 2012-2013 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Application\Models\Person;

include '../bootstrap.inc';
$person = new Person();
$person->setFirstname('{{dashboard_user_fname}}');
$person->setLastname('{{dashboard_user_lname}}');
$person->setEmail('{{dashboard_user_email}}');

$person->setUsername("{{ ansible_ssh_user }}");
//$person->setPassword();
$person->setAuthenticationMethod('Employee');
$person->setRole('Administrator');

$person->save();
