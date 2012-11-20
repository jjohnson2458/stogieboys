<?php

class Define{
	CONST self = __CLASS__;
	
	public static function set($constant = "", $value = ""){
		if($value != ""){
			if( !defined(strtoupper($constant)) ){
				define(strtoupper($constant), $value);	
			}	
		}
	}
	public static function get($constant = ""){
		if(defined($constant)){
			return $$constant;
		}
	}	
	public static function show($constant = ""){
		if($constant != ""){
			PR::r($constant);	
		} else {
			PR::constants();	
		}	
	}
	
	public static function includes($path = ''){
		if($path != ''){
			$path = str_replace('//' , '/', $path);			
			if(file_exists($path)){
				include_once($path);	
			}	
		}	
	}
	public static function requires($path = ''){
		if($path != ''){
			$path = str_replace('//' , '/', $path);			
			if(file_exists($path)){
				require_once($path);	
			}	
		}	
	}	
	
}