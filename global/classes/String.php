<?php

class String
{
	CONST self = __CLASS__;
	
	function IsEmpty($text = "")
	{
		return ( strlen(trim($text)) == 0 )  ? TRUE : FALSE ;
	}
	
	
	function x($string, $quote_style=ENT_QUOTES)
	{
	   static $trans;
	   if (!isset($trans)) {
		   $trans = get_html_translation_table(HTML_ENTITIES, $quote_style);
		   foreach ($trans as $key => $value)
			   $trans[$key] = '&#'.ord($key).';';
		   // dont translate the '&' in case it is part of &xxx;
		   $trans[chr(38)] = '&';
	   }
	   // after the initial translation, _do_ map standalone '&' into '&#38;'
	   return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/","&#38;" , strtr($string, $trans));
				
	}
		
	function Clean($text = "")
	{
		$text=str_replace(chr(32),'^',$text);
		$text= preg_replace("/[^a-zA-Z0-9@\.\-\_]/",chr(32), $text);
		$text=str_replace('^',chr(32),$text);
		return $text;		
	}	
	
	function Alpha($text = "")
	{
		return preg_replace("/[^a-zA-Z]/", '', $text);		
	}	
	
	function Numeric($text = "")
	{
		return preg_replace("/[^0-9]/", '', $text);
	}	
	
	
	function Strip($text = "")
	{
		
	}	

	function Space($num = 1)
	{
		return str_repeat(SPACE, $num);
	}
	
	
}