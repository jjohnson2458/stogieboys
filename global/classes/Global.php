<?php
/**
 * Global.php
 *  
 * @package Jworks
 */

/**
 * works like define when setting constants, accept that we can set constant arrays and objects as well.
 * preferences in the form of arbitrary name/value pairs.
 *
 */
class Global
{
	protected static $vars = array();
	
	public static function exists($key){
		return array_key_exists($key, self::$vars);
	}

	public static function get($key, $default = null){
		if(! self::exists($key)){
			if(is_null($default)){
				return  false;				
			}
			else{
				return $default;
			}
		}
		return self::$vars[$key];
	}
	
	public static function set($key, $value){
		self::$vars[$key] = $value;
	}
	
	public static function debug(){
		return PR::r(self::$vars, 1);
	}
}

