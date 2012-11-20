<?php


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
	
	function GetPallete($key){
		
		$pallete['red'] = 'ff0000';
		$pallete['grn'] = '00ff00';
		$pallete['blu'] = '0000ff';
		$pallete['yel'] = 'ffff00';
		$pallete['blk'] = '000000';
		$pallete['brn'] = 'a52a2a';
		$pallete['cyn'] = '00ffff';
		$pallete['org'] = 'ffa500';
 		$pallete['wht'] = 'ffffff';	
		if(array_key_exists($key, $pallete)){				
			return $pallete[$key];
		}
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
        }		
	function size($text = "", $size = 12){
		if($text != ""){
		$size = intval($size);
			return "<span style=\"font-size:{$size}px;\">{$text}</span>";
		}
	}
	
	function color($color = 'red', $text = "", $show = false){	
		if($text != "" ){
			if($show){
				ob_start();
				echo "<span style=\"color:#" . self::GetPallete($color). ";\">";
				PR::r($text);
				echo "</span>";
				$out = ob_get_clean();
				echo $out;
			} else {
				ob_start();
				echo "<span style=\"color:#" . self::GetPallete($color). ";\">";
				PR::r($text);
				echo "</span>";
				$out = ob_get_clean();			
		 		return $out;
			}
		} else {
		 	return self::GetPallete(__FUNCTION__) ;
		}
	}


	function red($text = "", $show = false){	
		return self::color(__FUNCTION__, $text, $show);
	}
	
	
	function grn($text = "", $show = false){	
		return self::color(__FUNCTION__, $text, $show);
	}
		
	
	function blu($text = "", $show = false){	
		return self::color(__FUNCTION__, $text, $show);
	}
		
	function yel($text = "", $show = false){	
		return self::color(__FUNCTION__, $text, $show);
	}		
	
	
	function blk($text = "", $show = false){	
		return self::color(__FUNCTION__, $text, $show);
	}
		
	
	
	function brn($text = "", $show = false){	
		return self::color(__FUNCTION__, $text, $show);
	}		
	
	
	
	function cyn($text = "", $show = false){	
		return self::color(__FUNCTION__, $text, $show);
	}		
		
	
	
	function org($text = "", $show = false){	
		return self::color(__FUNCTION__, $text, $show);
	}		
		
	
	
	function wht($text = "", $show = false){	
		return self::color(__FUNCTION__, $text, $show);
	}		
		
	
	function highlight($text = "", $show = false){			
		if($show){
			PR::r('<span style="BACKGROUND-COLOR: #ffff00">' . $text . '</span>');
		} else {
			return '<span style="BACKGROUND-COLOR: #ffff00">' . $text . '</span>';
		}
	}		
			
}



