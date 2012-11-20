<?php
/**
*
* @file config.php
* master configuration for the global/static framwork
* @author: J.J. Johnson <email4johnson@gmail.com>
* @package global
*/

/**
* enter site root url document root
* this is configured to default to zen carts constants 
* IF application_top.php has been called first
*/

if(defined(IS_ADMIN_FLAG)){
	define('__ROOT_URL', HTTP_SERVER);
	define('__ROOT_SURL', HTTPS_SERVER);
	define('__ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);
} else {
	define('__ROOT_DIR', ''); // set your root dir here
	define('__ROOT_URL', ''); // set the url path here
}

/**
* database constants
* this is configured to default to zen carts constants 
* IF application_top.php has been called first
*/

if(!defined(IS_ADMIN_FLAG)){
	define('DB_HOST', 'localhost');
	//define('DB_NAME', 'mkephart_zendev2');
	define('DB_NAME', (strpos($_SERVER['SCRIPT_FILENAME'], 'sbocdev'))  ? 'mkephart_sbocdev3' : 'mkephart_sbopencart' );
	define('DB_USER', 'mkephart_ocdev3');
	define('DB_PASS', 'dkj32p3m');
	define('DB_TYPE', 'mysql');
} else {
	// settings defined in /includes/configure.php
	define('DB_HOST', DB_SERVER);
	define('DB_NAME', DB_DATABASE);
	define('DB_USER', DB_SERVER_USERNAME);
	define('DB_PASS', DB_SERVER_PASSWORD);
	define('DB_TYPE', 'mysql');	
}

/**
* email constants
*/
define('EMAIL_DOMAIN', 'stogieboys.com');
define('ADMIN_EMAIL','jjohnson@stogieboys.com');
define('PEUSANIO', 'peusanio@stogieboys.com');
define('JJOHNSON', 'jjohnson@stogieboys.com');
define('SKING', 'sking@stogieboys.com');
define('JMOELK', 'jamesm@stogieboys.com');

/**
* extra functions
* here you can define a full path url of extra functions you wish to load
* first you must tell the system IF you want use load extra functsion
*/

/***********************************************************

* load extra functions
* set to true if you wish to load extra functions
*/

define('LOAD_EXTRA_FUNCTIONS', false);

/**
* set path for extra functions
*/

define('EXTRA_FUNCTIONS_DIR', '');

/************************************************************/

/***********************************************************

* load extra classes
* set to true if you wish to load extra functions
*/

define('LOAD_EXTRA_CLASSES', false);

/**
* set path for extra functions
*/

define('EXTRA_CLASSES_DIR', '');

/************************************************************/


/**
* to turn off all debug printing uncomment the line below
*/

//define('NO_PRINT', true);


define('DEFAULT_TIMEZONE', 'America/New_York');
define('DEFAULT_DATE_FORMAT', 'Y-m-d H:i:s');
define('DEFAULT_CACHE_TIME',600); // 10 minutes

// ===========================================================================
// ============ WOULD SUGGEST NOT EDITING IN THIS SECTION BELOW ==============
// ===========================================================================

require 'functions/functions.php';
require 'global_config.php';
