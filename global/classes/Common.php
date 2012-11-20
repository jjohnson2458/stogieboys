<?php
/**
 * Common.php
 * @package static
 */

/**
 * A singleton class that provides access to static, global variables
 * preferences in the form of arbitrary name/value pairs.
 *
 * @package static
 */
class Common{
        protected static $prefs = array();
        
        public static function exists($key){
                return array_key_exists($key, self::$prefs);
        }

        public static function get($key, $default = null){
                if(! self::exists($key)){
                        if(is_null($default)){
                               return false;
							   // throw new ArgumentError("Variables::get('$key') Missing variable");
                        }
                        else{
                                return $default;
                        }
                }
                return self::$prefs[$key];
        }
        
        public static function set($key, $value){
                self::$prefs[$key] = $value;
        }
        
        public static function debug(){
                return PR::r(self::$prefs, 1);
        }
}

/**
 * Substitute for IllegalArgumentException in PHP 5.1
 */