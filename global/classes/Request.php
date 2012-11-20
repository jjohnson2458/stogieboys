<?php


class Request
{
	/**
	*
	*
	*/
	public static function get($var = "", $default = false)
	{
		return self::process($_GET, $var, $default);
	}


	/**
	*
	*
	*/
	public static function post($var = "", $default = false)
	{
		return self::process($_POST, $var, $default);
	}


	/**
	*
	*
	*/
	public static function both($var = "", $default = false)
	{
		return self::process($_REQUEST, $var, $default);
	}


	/**
	*
	*
	*/
	public static function cookie($var = "", $default = false)
	{
		return self::process($_COOKIE, $var, $default);
	}



	/**
	*
	*
	*/
	public static function files($var = "", $default = false)
	{
		
	}


	/**
	*
	*
	*/
	public static function process($array = '', $var = "", $default = false)
	{
		// get var type:		
		$var_type = gettype($array[$var]);				
		switch ($var_type) {
			case 'string':
				$eval = '$result = stripslashes($_' . strtoupper($type) . '[\'' . $var . '\']);';			
				$result = stripslashes($array[$var]);
				
				return ($result != "")  ? $result : $default  ;	
			break;

			case 'array':
				$result = AR::multi_map(array("stripslashes"), $array[$var]) ; 				
				return (is_array($result))  ? $result : $default  ;	
			break;
			default :				
				return '';
			break;
		}

	}









































}