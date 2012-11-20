<?php


class Quote
{
	CONST self 			= __CLASS__;

	
	public static function add($text = false){
		return self::_add("'", $text);
	}
	
	public static function adds($text = false){
		return self::_add('"', $text);
	}
	
	public static function _add($quote, $text = ''){ 
		if(!is_array($text)){
			$text 		= self::_remove($quote, $text);				
			$text		= str_replace($quote, '\\'. $quote, $text);
			return $quote . $text . $quote;
		} else {
			$array = array();
			foreach((array)$text as $name => $value){
				$array[$name] = self::_add($quote, $value);
			}
			return $array;
		}
		
		
	}	
	
	public static function join($text = array(), $separator = ', '){
		return self::_join("'", $text, $separator);
	}
	
	public static function joins($text = array(), $separator = ', ' ){
		return self::_join('"', $text, $separator);
	}

	public static function _join($quote, $text, $separator = ', '){
		$array = self::_add($quote, $text);
		$data = array();
		foreach((array)$array as $name => $value){
			$data[] = "{$name} = {$value}";
		}
		return AR::join($data, $separator);
	}	
	
	public static function remove($text = false){
		return self::_remove("'", $text);
	}
	
	public static function removes($text = false){
		return self::_remove('"', $text);	
	}
	
	public static function _remove($quote, $text = false){
			$patterns[] = "/^" . $quote . "/s";
			$patterns[] = "/" . $quote . "$/s";			
			return stripslashes(preg_replace($patterns, '', $text));		
	}	
}


