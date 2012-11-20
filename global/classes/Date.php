<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if(!defined('DEFAULT_DATE_FORMAT')){
    define('DEFAULT_DATE_FORMAT', 'Y-m-d H:i:s');
}



class Date extends DateTime
{

    /**
     *
     * @param type $format - must be a valid date string
     * @return string - formatted date
     */
    public static function now($format = ""){        
        return (strtotime(date($format)) != -1 && $format != "")  ? date($format) : date(DEFAULT_DATE_FORMAT) ;
    }
    
    /**
     * 
     * 
     * @param string $datestring - must be a valid date string
     * @param string $new_format - the new date format (must be a valid date string)
     * @return string - formatted date
     * 
     */
    public static function convert($datestring = "", $new_format = ""){
        if(strtotime($datestring) != -1){
            return (strtotime(date($new_format)) != -1 && $new_format != "")  ? date($new_format,strtotime($datestring)) : date(DEFAULT_DATE_FORMAT,strtotime($datestring)) ;
        }
        
        
    }
	
	public static function unix($datestring = ""){
		return (strtotime($datestring) != -1)  ? strtotime($datestring) : false ;
	}
	
	public static function fromunix($unixtime, $datestring = "Y-m-d H:i:s"){
		return (strtotime(date($datestring, $unixtime)) != -1)  ? date($datestring, $unixtime) : false ;
	}	
}


?>
