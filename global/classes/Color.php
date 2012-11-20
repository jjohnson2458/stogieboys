<?php
include('classes/PR.php');

/**
* 
*
*/

class Font
{
	
	CONST self = __CLASS__;
	public static $instance = FALSE;
	public static $pallete;
	
	public static function getInstance(){
		if (!self::$instance){
			self::$instance = new Font();
		}
		
		return self::$instance;
	}
	
	function GetPallete(){
		
		$pallete['red'] = 'ff0000';
		$pallete['grn'] = '00ff00';
		$pallete['blu'] = '0000ff';
		$pallete['yel'] = 'ffff00';
		$pallete['blk'] = '000000';
		$pallete['brn'] = 'a52a2a';
		$pallete['cyn'] = '00ffff';
		$pallete['org'] = 'ffa500';
 		$pallete['wht'] = 'ffffff';	
		return $pallete;
	}
	
	public static function __callStatic($color = "", $array){
		$pallete = self::GetPallete();
		if(array_key_exists($color,$pallete)){
			ob_start();
				if( count($array) > 1 ) {
					PR::r($array);
				} else {
					PR::r($array[0]);
				}
			$text = ob_get_clean();print_r($text);
			return ($text != "" )  ? "<span style=\"color:#{$pallete[$color]};\">$text</span>" : $pallete[$color] ;
		}	
	}
	
	function htmlTag($tag = "", $text = ""){
		if($text != "" && $tag != ""){
			return "<{$tag}>" . $text . "</{$tag}>";
		}
	}
	
	
	function italic($text = ""){
		return self::htmlTag('i',$text);
	}
	
	
	function bold($text = ""){
		return self::htmlTag('b',$text);
	}	
		
	function underline($text = ""){
		return self::htmlTag('u',$text);
	}	
	
	function strike($text = ""){
		return self::htmlTag('strike',$text);
		
	function size($text = "", $size = 12){
		if($text != ""){
		$size = intval($size);
			return "<span style=\"font-size:{$size}px;\">{$text}</span>";
		}
	}
	
}



