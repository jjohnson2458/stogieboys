<?php
class Debug 
{ 
    private static $calls; 

    public static function log($message = null) 
    { 
        if(!is_array(self::$calls)) 
            self::$calls = array(); 

        $call = debug_backtrace(false); 
        $call = (isset($call[1]))?$call[1]:$call[0]; 

        $call['message'] = $message; 
        array_push(self::$calls, $call); 
    } 
	
	public static function getLogs(){
		return $calls;	
	}
} 