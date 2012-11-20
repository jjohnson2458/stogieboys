<?php

/**
* system constants
*/
define('DS', DIRECTORY_SEPARATOR);
define('PHP_SELF', $_SERVER['PHP_SELF']);
define('THIS_DIR', getcwd() . DS);
define('IP_ADDRESS', $_SERVER['REMOTE_ADDR']);

define('PHP', '.php');
define('CSV', '.csv');
define('XML', '.xml');
define('JS', '.js');
define('HTM', '.htm');
define('HTML', '.html');
define('EXE', '.exe');
define('JPG', '.jpg');
define('JPEG', '.jpeg');
define('GIF', '.gif');
define('PNG', '.png');
define('LOG', '.log');
define('TXT', '.txt');
define('PDF', '.pdf');
define('DOT', '.');
define('SQL', '.sql');

/*
* string constants
*/

define('BR', '<br>');
define('CR', "\n");
define('AT', '@');
define('HR', '<hr>');
define('DEFAULT_EMAIL', 'email4johnson@gmail.com');
define('NBSP', "&nbsp;");
define('COPYRIGHT', chr(64) . date('Y'));
define('QUOTE', chr(39));
define('DQUOTE', chr(34));
define('CM', ',');
define('PIPE', '|');
define('SEPARATOR', NBSP . PIPE . NBSP); 
define('SPACE', "&nbsp;");
define('TAB', chr(9));
define('ASK', '*');



define('SECOND', 1);
define('MINUTE', 60 * SECOND);
define('HOUR', 60 * MINUTE);
define('DAY', 24 * HOUR);
define('WEEK', 7 * DAY);
define('MONTH', 30 * DAY);
define('YEAR', 365 * DAY);

define('DATE', 'Y-m-d');
define('TIME', 'H:i:s');
define('TODAY', date(DATE));
define('NOW', date(TIME));
define('RIGHTNOW', date(DEFAULT_DATE_FORMAT));
define('TIMESTAMP', time());


/**
* legacy constants
*/
define('SLASH', DS);
$dbname = DB_NAME;
$dbhost = DB_HOST;
$dbuser = DB_USER;
$dbpass = DB_PASS;
$dbtype = DB_TYPE;


if(!empty($_SERVER['HTTPS'])){
	define('HTTP', 'https:' . DS .DS) ;	
} else {
	define('HTTP', 'http:' . DS .DS) ;	
}

// setting the root dir===================================================

	if(!defined('__ROOT_DIR') || __ROOT_DIR == ''){
		define('ROOT__DIR', $_SERVER['DOCUMENT_ROOT'] . DS) ;
		#define('ROOT_DIR', '__ROOT_DIR' . DS);
	} else {
		#define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT'] . DS) ;
		define('ROOT__DIR', __ROOT_DIR . DS);
	}


// setting the root url =====================================================

	if(__ROOT_URL != ''){
		define('ROOT__URL', __ROOT_URL . DS);	
	} else {
		define('ROOT__URL', HTTP . $_SERVER['HTTP_HOST'] . DS);	
	}


define('GLOBAL_DIR', realpath(dirname(__FILE__)) . DS);
define('GLOBAL_URL', ROOT__URL . end(explode(DS, realpath(dirname(__FILE__)))) . DS );

define('GLOBAL_FUNCTION_DIR', GLOBAL_DIR . 'functions' . DS); 
define('GLOBAL_FUNCTION_URL', GLOBAL_URL . 'functions' . DS); 

define('GLOBAL_CLASSES_DIR', GLOBAL_DIR . 'classes' . DS); 
define('GLOBAL_CLASSES_URL', GLOBAL_URL . 'classes' . DS);

define('GLOBAL_CONSTANTS_DIR', GLOBAL_DIR . 'constants' . DS); 
define('GLOBAL_CONSTANTS_URL', GLOBAL_URL . 'constants' . DS);

define('GLOBAL_SCRIPTS_DIR', GLOBAL_DIR . 'scripts' . DS); 
define('GLOBAL_SCRIPTS_URL', GLOBAL_URL . 'scripts' . DS);

define('GLOBAL_HELPER_DIR', GLOBAL_DIR . 'helpers' . DS);
define('GLOBAL_HELPER_URL', GLOBAL_URL . 'helpers' . DS);

define('GLOBAL_CSS_DIR', GLOBAL_HELPER_DIR .'css' . DS);
define('GLOBAL_CSS_URL', GLOBAL_HELPER_URL .'css' . DS);

define('GLOBAL_JAVA_DIR', GLOBAL_HELPER_DIR .'java' . DS);
define('GLOBAL_JAVA_URL', GLOBAL_HELPER_URL .'java' . DS);

define('GLOBAL_JQUERY_DIR', GLOBAL_SCRIPTS_DIR .'js' . DS);
define('GLOBAL_JQUERY_URL', GLOBAL_SCRIPTS_URL .'js' . DS);

define('JQUERY_PATH', 
 '<script type="text/javascript" src="' . GLOBAL_JQUERY_URL  . 'global_js_functions.js"></script>
  <script type="text/javascript" src="' . GLOBAL_JQUERY_URL  . 'jquery-ui-1.7.3.custom.min.js"></script>
  <script type="text/javascript" src="' . GLOBAL_JQUERY_URL  . 'jquery-1.3.2.min.js"></script>' 
 );

define('GLOBAL_IMAGES_DIR', GLOBAL_DIR . 'images' . DS);
define('GLOBAL_IMAGES_URL', GLOBAL_URL .'images' . DS);

define('GLOBAL_ICON_DIR', GLOBAL_IMAGES_DIR .'icons' . DS);
define('GLOBAL_ICON_URL', GLOBAL_IMAGES_URL .'icons' . DS);

// ==============================OPEN CART CONSTANTS =======================

define('OC_ADMIN_DIR', ROOT__URL . 'admin' . DS);
define('OC_ADMIN_URL', ROOT__DIR . 'admin' . DS);

define('OC_ADMIN_CONTROLLER_DIR', OC_ADMIN_DIR . 'controller' . DS);
define('OC_ADMIN_CONTROLLER_URL', OC_ADMIN_URL . 'controller' . DS);

	define('OC_ADMIN_INVENTORY_DIR', OC_ADMIN_CONTROLLER_DIR . 'inventory' . DS);
	define('OC_ADMIN_INVENTORY_URL', OC_ADMIN_CONTROLLER_URL . 'inventory' . DS);

define('OC_ADMIN_MODEL_DIR', OC_ADMIN_DIR . 'model' . DS);
define('OC_ADMIN_MODEL_URL', OC_ADMIN_DIR . 'model' . DS);

define('OC_ADMIN_VIEW_DIR', OC_ADMIN_DIR . 'view' . DS);
define('OC_ADMIN_VIEW_URL', OC_ADMIN_DIR . 'view' . DS);

define('OC_ADMIN_CUSTOM_DIR', OC_ADMIN_DIR . 'custom' . DS);
define('OC_ADMIN_CUSTOM_URL', OC_ADMIN_DIR . 'custom' . DS);

	define('OC_ADMIN_CUSTOM_INVENTORY_DIR', OC_ADMIN_CUSTOM_DIR . 'inventory' . DS);
	define('OC_ADMIN_CUSTOM_INVENTORY_URL', OC_ADMIN_CUSTOM_URL . 'inventory' . DS);

// ==========================================================================

define('ZEN_INCLUDES_DIR', ROOT__DIR . 'includes' . DS);
define('ZEN_INCLUDES_URL', ROOT__URL . 'includes' . DS);

define('ZEN_ADMIN_DIR', ROOT__DIR . 'sb_admin' . DS);
define('ZEN_ADMIN_URL', ROOT__URL . 'sb_admin' . DS);

define('ZEN_CLASSES_DIR', ZEN_INCLUDES_DIR . 'classes' . DS);
define('ZEN_CLASSES_URL', ZEN_INCLUDES_URL . 'classes' . DS);

define('ZEN_FUNCTIONS_DIR', ZEN_INCLUDES_DIR . 'functions' . DS);
define('ZEN_FUNCTIONS_URL', ZEN_INCLUDES_URL . 'functions' . DS);

define('ZEN_LANGUAGES_DIR', ZEN_INCLUDES_DIR . 'languages' . DS);
define('ZEN_LANGUAGES_URL', ZEN_INCLUDES_URL . 'languages' . DS);

define('ZEN_TEMPLATES_DIR', ZEN_INCLUDES_DIR . 'templates' . DS);
define('ZEN_TEMPLATES_URL', ZEN_INCLUDES_URL . 'templates' . DS);

define('ZEN_SCRIPTS_DIR', ZEN_INCLUDES_DIR . 'javascript' . DS);
define('ZEN_SCRIPTS_URL', ZEN_INCLUDES_URL . 'javascript' . DS);

define('ZEN_MODULES_DIR', ZEN_INCLUDES_DIR . 'modules' . DS);
define('ZEN_MODULES_URL', ZEN_INCLUDES_URL . 'modules' . DS);

if(defined('IS_ADMIN_FLAG') && IS_ADMIN_FLAG !== false){

	define('ZEN_ADMIN_INCLUDES_DIR', ROOT__DIR . 'sb_admin' . DS);
	define('ZEN_ADMIN_INCLUDES_URL', ROOT__URL . 'sb_admin' . DS);

	define('ZEN_ADMIN_CLASSES_DIR', ZEN_ADMIN_INCLUDES_DIR . 'classes' . DS);
	define('ZEN_ADMIN_CLASSES_URL', ZEN_ADMIN_INCLUDES_URL . 'classes' . DS);

	define('ZEN_ADMIN_FUNCTIONS_DIR', ZEN_ADMIN_INCLUDES_DIR . 'functions' . DS);
	define('ZEN_ADMIN_FUNCTIONS_URL', ZEN_ADMIN_INCLUDES_URL . 'functions' . DS);

	define('ZEN_ADMIN_LANGUAGES_DIR', ZEN_ADMIN_INCLUDES_DIR . 'languages' . DS);
	define('ZEN_ADMIN_LANGUAGES_URL', ZEN_ADMIN_INCLUDES_URL . 'languages' . DS);

	define('ZEN_ADMIN_TEMPLATES_DIR', ZEN_ADMIN_INCLUDES_DIR . 'templates' . DS);
	define('ZEN_ADMIN_TEMPLATES_URL', ZEN_ADMIN_INCLUDES_URL . 'templates' . DS);

	define('ZEN_ADMIN_SCRIPTS_DIR', ZEN_ADMIN_INCLUDES_DIR . 'javascript' . DS);
	define('ZEN_ADMIN_SCRIPTS_URL', ZEN_ADMIN_INCLUDES_URL . 'javascript' . DS);

	define('ZEN_ADMIN_MODULES_DIR', ZEN_ADMIN_INCLUDES_DIR . 'modules' . DS);
	define('ZEN_ADMIN_MODULES_URL', ZEN_ADMIN_INCLUDES_URL . 'modules' . DS);
	
	define('ZEN_ADMIN_BOXES_DIR', ZEN_ADMIN_INCLUDES_DIR . 'boxes' . DS);
	define('ZEN_ADMIN_BOXES_URL', ZEN_ADMIN_INCLUDES_URL . 'boxes' . DS);	

	define('ZEN_ADMIN_GRAPHS_DIR', ZEN_ADMIN_INCLUDES_DIR . 'graphs' . DS);
	define('ZEN_ADMIN_GRAPHS_URL', ZEN_ADMIN_INCLUDES_URL . 'graphs' . DS);	



}

if(LOAD_EXTRA_FUNCTIONS && is_dir(EXTRA_FUNCTIONS_DIR)){
	$extra_function_folder = str_replace('..' . DS, DS, EXTRA_FUNCTIONS_DIR);
	$extra_function_folder = str_replace(DS . DS, DS, $extra_function_folder);
	$extra_function_files = File::scan($extra_function_folder);	
	foreach((array)$extra_function_files as $extra_function_file){		
		if(File::getExt(basename($extra_function_file)) == 'php'){
			try{			
				include_once($extra_function_file);
			} catch (Exception $e){
				PR::red($e->getMessage());	
			}
		}	
	}
}

require_once(GLOBAL_CLASSES_DIR . 'ErrorBacktrace.php');

/*
echo '<pre>'; 
print_r($_SERVER);
echo '</pre>';

$constants = get_defined_constants(true);
echo '<pre>';
print_r($constants[user]); 
echo '</pre>';
die;
*/