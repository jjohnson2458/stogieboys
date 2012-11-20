<?php
/**
*
* example:
* $js = new Javascript();
* echo $js->OnClick('getProducts', $vars);
* echo $js->jquery->get('include/select.php', $array, $success);
* echo $js->src($url); // produces js link surrounded by script tags
*
* $js->createFunction($name, $lines, $args); // builds a function one line at a time - no function( or ) needed;
* $js->loadFunction($path); // same as create function, but loads function lines from file
* $js->setFunction($name, $lines, $args);
* echo $js->getFunction($name);
*/


class Javascript
{
	
	CONST self = __CLASS__;
	
	function __construct(){
		
	}
	
	public function __call($event, $args){
		//PR::r($args);
		$function = $args[0]; 
		
		for($i = 1; $i <= count($args) -1 ; $i++){
			$arg = $args[$i];
			$arguments = array();
			if(substr($arg, 0, 4) != 'this'){
				//$arguments[] = Quote::add($arg);
				$arguments[] = $arg;
			} else {
				$arguments[] = $arg;
			}	
		}
		//PR::r($arguments);
		
		$js_string = $event . '="' . ($function . '(' . AR::join($arguments, ', ') . ');"'); 
		return $js_string;
	}	
	
	public static function src($url){
		return Html::tag('script', '', 'type="text/javascript" src="'. $url. '"'); 	
	}

	public static function script($code = ""){
		return Html::tag('script', $code, 'type="text/javascript" '); 	
	}

	public static function jquery(){
		$jq1 = (GLOBAL_URL . 'scripts/js/jquery-ui-1.7.3.custom.min.js');
		$jq2 = (GLOBAL_URL . 'scripts/js/jquery-1.3.2.min.js');
		$jq_css = '<link rel="stylesheet" href="' . GLOBAL_URL . 'scripts/css/ui-lightness/jquery-ui-1.7.3.custom.css" type="text/css" media="all" />'; 
		return self::src($jq2) . CR . self::src($jq1) . CR . $jq_css; 	
	}


	/**
	* creates a jquery $.getJSON function
	* @params string $path
	* @params array $args name/value pair of what is to be sent with the ajax request
	* @params array $sucess_array name/value pair of dom elements to be replaced upon sucess
	*/
	function getJSON($path, $args, $sucess_array, $error_callback = false){
		
	}
	
	
}