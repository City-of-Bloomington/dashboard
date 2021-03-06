<?php
/**
 * @copyright 2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

$ZEND_ACL = new Acl();
$ZEND_ACL->addRole(new Role('Anonymous'))
		 ->addRole(new Role('Public'), 'Anonymous')
		 ->addRole(new Role('Staff'), 'Public')
		 ->addRole(new Role('Administrator'), 'Staff');

/**
 * Declare all the resources
 */
$ZEND_ACL->addResource(new Resource('index'));
$ZEND_ACL->addResource(new Resource('people'));
$ZEND_ACL->addResource(new Resource('users'));
$ZEND_ACL->addResource(new Resource('login'));

$ZEND_ACL->addResource(new Resource('cards'));
$ZEND_ACL->addResource(new Resource('services'));
$ZEND_ACL->addResource(new Resource('groups'));

/**
 * Assign permissions to the resources
 */
// Permissions for unauthenticated browsing
$ZEND_ACL->allow(null,['login', 'index']);
$ZEND_ACL->allow(null, 'cards', 'view');

// Allow Staff to do stuff
$ZEND_ACL->allow('Staff', ['people', 'services'], ['index','view']);
$ZEND_ACL->allow('Staff', 'cards');

// Administrator is allowed access to everything
$ZEND_ACL->allow('Administrator');
