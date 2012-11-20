<?php

/*gateway constants*/ 
define ('ADMIN_EMAIL','jjohnson@stogieboys.com');
define('SLOCKWOOD', 'slockwood@stogieboys.com');
define('TLOCKHART', 'tlockhart@stogieboys.com');
define('PEUSANIO', 'peusanio@stogieboys.com');
define('JJOHNSON', 'jjohnson@stogieboys.com');

define ('DATETIME',date('Y-m-d H:i:s'));
define ('PHP_SELF',$_SERVER['PHP_SELF']);
/*this file sets the contants for any project to determine the root folder location and all sub folders*/
/*echo '<pre>'; 
print_r($_SERVER);
echo '</pre>';*/

define('PROJECT','gateway/');
define('PATH_PREFIX','gateway/');

// cache constants:
define('DEFAULT_CACHE_TIME',600); // 10 minutes

	if ( $_SERVER['HTTP_HOST']!='127.0.0.1') {
		// remote server
		define('ROOT_PREFIX','global/');
		if(!defined('ROOT_FOLDER')){
			define('ROOT_FOLDER','/home/mkephart/public_html/zendev/'.ROOT_PREFIX);
		}
		define('GATEWAY_DIR',ROOT_FOLDER.PROJECT);
		define('GATEWAY_URL','http://'.$_SERVER['HTTP_HOST'].ROOT_PREFIX.'/'.PATH_PREFIX);
		define('SLASH',chr(47));
		define('THIS_DIR',getcwd().SLASH);

		if (!defined('DB_HOST')) define('DB_HOST','localhost'); 
		if (!defined('DB_USER')) define('DB_USER','mkephart_admin25');
		if (!defined('DB_PASS')) define('DB_PASS','dkj32p3m'); 
		if (!defined('DB_NAME')) define('DB_NAME','mkephart_zentest_import');							
	} else {
		// local server
		define('ROOT_PREFIX','/global/'); // change in different enviroment
		define('ROOT_FOLDER','C:/xampp/htdocs/' . ROOT_PREFIX);
		define('SLASH',chr(92));
		define('THIS_DIR',getcwd().SLASH);
		if (!defined('DB_HOST')) define('DB_HOST','localhost'); 
		if (!defined('DB_USER')) define('DB_USER','root');
		if (!defined('DB_PASS')) define('DB_PASS','jj0708ba'); 
		if (!defined('DB_NAME')) define('DB_NAME','stogieboys');		
	}
include_once('vars.php'); // get common variables and constants
define('GLOBAL_DIR',ROOT_FOLDER);
define('GLOBAL_URL',$_SERVER['HTTP_HOST'] . '/'. ROOT_PREFIX);

define('GLOBAL_FUNCTION_DIR', GLOBAL_DIR . 'functions/'); 
define('GLOBAL_FUNCTION_URL', GLOBAL_URL . 'functions/');

define('GLOBAL_CLASSES_DIR', GLOBAL_DIR . 'classes/'); 
define('GLOBAL_CLASSES_URL', GLOBAL_URL . 'classes/');

define('GLOBAL_CONSTANTS_DIR', GLOBAL_DIR . 'constants/'); 
define('GLOBAL_CONSTANTS_URL', GLOBAL_URL . 'constants/');





define('GLOBAL_HELPER_DIR',GLOBAL_DIR.'helpers/');
define('GLOBAL_HELPER_URL',GLOBAL_URL.'helpers/');

define('GLOBAL_CSS_DIR',GLOBAL_HELPER_DIR.'css/');
define('GLOBAL_CSS_URL',GLOBAL_HELPER_URL.'css/');

define('GLOBAL_JAVA_DIR',GLOBAL_HELPER_DIR.'java/');
define('GLOBAL_JAVA_URL',GLOBAL_HELPER_URL.'java/');

define('GLOBAL_JQUERY_DIR',GLOBAL_HELPER_DIR.'jquery/');
define('GLOBAL_JQUERY_URL',GLOBAL_HELPER_URL.'jquery/');

define('GLOBAL_JS_DIR',GLOBAL_HELPER_DIR.'js/');
define('GLOBAL_JS_URL',GLOBAL_HELPER_URL.'js/');

define('GLOBAL_IMAGES_DIR',GLOBAL_DIR.'images/');
define('GLOBAL_IMAGES_URL','http://'. GLOBAL_URL.'images/');


define('GLOBAL_ICON_DIR',GLOBAL_IMAGES_DIR.'icons/');
define('GLOBAL_ICON_URL',GLOBAL_IMAGES_URL.'icons/');

/* ========================================================== */




//define('','');

/**
$constants = get_defined_constants(true);
echo '<pre>';
print_r($constants[user]); 
echo '</pre>';
*/
