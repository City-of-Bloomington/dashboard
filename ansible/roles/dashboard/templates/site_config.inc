<?php
define('APPLICATION_NAME','Dashboard');

/**
 * The URL to get to this site
 * Do NOT use a trailing slash
 */
define('BASE_URL','http://localhost{{ dashboard_uri }}');
define('BASE_URI','{{ dashboard_uri }}');

/**
 * Specify the theme directory
 *
  * Remember to create a symbolic link in public/css to the theme CSS
  * that you declare here.
 *
 * A theme can consist of templates, blocks which will override core.
 * The current theme's screen.css will be included in the HTML head.
 */
define('THEME', 'COB');

/**
 * Database Setup
 */
$DATABASES = [
    'default' => [
        'dsn'      => 'mysql:dbname={{ dashboard_db.name }};host=localhost',
        'username' => '{{ dashboard_db.username }}',
        'password' => '{{ dashboard_db.password }}',
        'options'  => []
    ]
];

/**
 * Directory Configuration
 *
 * This supports doing user authentication from multiple external
 * directories, such as LDAP or ADS. This is required since city staff
 * are in a seperate LDAP directory from public user accounts.
 * Classes that implement ExternalIdentity should have an entry here.
 *
 * See: ExternalIdentity
 */
// Example for ADS style authentication
$DIRECTORY_CONFIG = [
//  'Ldap' => [
//      'classname' => 'Site\Classes\Ldap',
//      'DIRECTORY_SERVER'=>'ldaps://example.org:636',
//      'DIRECTORY_BASE_DN'=>'OU=Department,DC=example,DC=org',
//      'DIRECTORY_USERNAME_ATTRIBUTE'=>'CN',
//      'DIRECTORY_USER_BINDING'=>'{username}@example.org',
//      'DIRECTORY_ADMIN_BINDING'=>'admin@example.org',
//      'DIRECTORY_ADMIN_PASS'=>'password'
//  ]
//
// Example for LDAP style authentication
//  'Ldap' => [
//      'classname' => 'Site\Classes\Ldap',
//      'DIRECTORY_SERVER'=>'ldaps://example.org:636');
//      'DIRECTORY_BASE_DN'=>'ou=people,o=ldap.domain.somewhere');
//      'DIRECTORY_USERNAME_ATTRIBUTE'=>'uid');
//      'DIRECTORY_USER_BINDING'=>'uid={username},'.DIRECTORY_BASE_DN);
//      'DIRECTORY_ADMIN_BINDING'=>'uid=admin,'.DIRECTORY_BASE_DN);
//      'DIRECTORY_ADMIN_PASS'=>'password');
//  ]
// Example for Directory webservice
  'Employee' => [
     'classname' => 'Site\Classes\Employee',
     'DIRECTORY_SERVER' => '{{ dashboard_directory_server }}',
  ]
];

/**
 * CAS authentication http://www.jasig.org/cas
 *
 * https://wiki.jasig.org/display/CASC/phpCAS
 *
 * phpCAS is a PHP library for handling the calls to the CAS service
 * It is the official library, part of the Jasig CAS project
 */
define('CAS', APPLICATION_HOME.'/vendor/jasig/phpcas/source');
define('CAS_SERVER','{{ dashboard_cas_server }}');
define('CAS_URI','cas');

define('DATE_FORMAT', 'n/j/Y');
define('TIME_FORMAT', 'g:i a');
define('DATETIME_FORMAT', 'n/j/Y g:i a');
define('LOCALE', 'en_US');