<?

/**
* 
* @package global
*
*/

class Icon
{
	CONST self = __CLASS__;
	
	public static function src($image, $ext = 'png', $attributes = ""){ 
		$icon_path = GLOBAL_ICON_DIR . $image . '.' . $ext;
		$icon_link = GLOBAL_ICON_URL . $image . '.' . $ext; 
		//PR::r($icon_path, $icon_link);
		return (file_exists($icon_path))  ? Html::Image($icon_link, AR::a($attributes)) : 'x' ;
	}
	
	
	public static function get($image, $url = '', $get_var){
		
	}	
	
	public static function link($id, $image, $url = '', $get_vars = array(), $attributes = ""){		
			$get_vars = array_merge($get_vars, array('id' => $id));
			self::src($image, 'png');
			return Http::glink($url, $get_vars, self::src($image, 'png') , $attributes);
		
	}
	
	public static function show($text = ""){
		$icons = File::scan(GLOBAL_ICON_DIR, false);
		$trs = array();
		foreach($icons as $icon){
			if($text != "" && strpos('.' . $icon, $text)){
				
			} else {
				$cells = array(basename($icon), Html::image(GLOBAL_ICON_URL . $icon));
				$trs[] = Table::tr(Table::cells($cells));
			}
		}
		$html = Table::build(AR::join($trs, ' '));
		echo $html;
	}		
}