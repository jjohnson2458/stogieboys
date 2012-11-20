<?php

class Input
{

	CONST self =__CLASS__;
	
	public static function makeInput($type,$name,$default="",$attribute="",$size=30){
		return "<input type=\"$type\" name=\"$name\" id=\"$name\" value=\"$default\" $attribute class=\"inputboxsmall\" size=\"$size\">";
	}
	
	public static function Text($name,$default="",$attribute="",$size=30){
		return self::makeInput('text',$name,$default,$attribute,$size);
	}
	
	public static function Money($name = "", $value = "", $attributes = ""){
		return self::Text($name, $value, 'onFocus="this.focus();"  onBlur="checkDecimal(this,this.value)" ' . $attributes, 4);
	}
	
	public static function File($name,$value="",$default="",$attribute="",$size=30){
		$current=($default!="")  ? 'Current File: '.$default : '' ; 
		return $current.$default.' '.self::makeInput('file',$name,'',$attribute,$size);
	}
	
	public static function TextArea($name,$default="",$attribute="",$columns='30',$rows='5'){
		return "<textarea name='$name' cols='$columns' rows='$rows' $attribute >$default</textarea>";
		
	}
	
	public static function makeInputDate($name,$value,$default="",$attribute=""){
		$default=(strtotime($default)!=-1)  ? $default : date('Y-m-d') ;
		ob_start();
		?>
		<link href="<?php echo STYLES_DIR ?>dateSelect.css" rel="stylesheet" type="text/css"/>
		<script type="text/javascript" src="<?php echo SCRIPTS_DIR ?>selectDate.js"></script>
		<?
		echo makeButton(Date,'action',"onClick=\"displayDatePicker('$name');\"",false); 
		echo makeInputText($name,$default,$attribute,14);
		$button=ob_get_clean();
		return $button;	
	}
	
	public static function Date($name, $default = "", $attributes = ""){
		if (!defined('CONFIG_JQUERY_DIR')) define('CONFIG_JQUERY_DIR','http://util.buffalo.com/autoload/helpers/jquery/');
		$default=(strtotime($default))  ? Date::convert($default,'Y-m-d') : Date::convert($default,'Y-m-d') ;		
		ob_start();
		?>

		<?
		echo Input::Text($name,$default,$attributes);
		?>
	<script type="text/javascript">
		$(function() {
			$("#<?php echo $name ?>").datepicker({ dateFormat: 'yy-mm-dd', showOn: 'button', buttonImageOnly: true , buttonImage: '<?php echo GLOBAL_ICON_URL?>calendar.png' });
		});
	</script>	
		<?
		
		$html=ob_get_clean();
		return $html;	
	}
	
	public static function Decimal($name = "", $value = "", $attributes = ""){
		return self::Text($name, $value,  'onFocus="this.focus();"  onBlur="checkDecimal(this,this.value)"'  . $attributes, 4) ;
	}
	
	public static function Integer($name = "", $value = "", $attributes = ""){
		return self::Text($name, $value, 'onFocus="this.focus();"  onBlur="checkInteger(this,this.value)" ' . $attributes, 4);
	}
	
	public static function Hidden($name,$default){
		return self::makeInput('hidden',$name,$default);
	}
	
	public static function Button($value,$action='action',$attribute="",$type=true){
	$buttontype=($type)  ? 'submit' : 'button' ;
	return "<input name=\"$action\" id=\"$action\" type=\"$buttontype\" value=\"$value\" class=\"button_small\" $attribute  style=\"font-weight:bold\"/>";
	}
	
	public static function ImageButton($imageUrl="",$value="",$attribute="",$alt=""){
		if ($imageUrl!=""){
			$html="<input name='$value' type='hidden' value='' id='$value'/>";
			$html.="<input type='image' src='$imageUrl' alt='$alt' title='$alt' $attribute ";
			$html.="onclick=\"document.getElementById('$value').value='$value'; document.form(this).submit()\"/>";
			return $html;
		}
	}
	
	public static function Checkbox($name,$value,$parameter,$default,$attribute=""){
		$checked=($parameter==$default)  ? 'checked' : '' ;
		return "<input name='$name' id='$name' type='checkbox' value='$value' $checked $attribute>";
	}
	
	public static function Radio($name, $value, $parameter, $default = "", $attribute = ""){	
		$checked=($parameter == $default)  ? 'checked' : '' ;
		return "<input type='radio'  name='$name' value='$value' $checked $attribute>";
	}
	/*function makeRadio(){
		$array=func_get_args();
		$name=$array[0];
		$value=$array[1];
		$default=$array[2];
		
		if (count($array)>3){		
			for($i=3; $i<count($array); $i++){
			$checked=($array[$i]==$default)  ? 'checked' : '' ;
				$radio.= "<input type='radio' name='$name' value='$array[$i]' $checked>".$array[$i]."&nbsp;&nbsp;";
	;
				}		
			
		}
		return $radio;
	}*/
	
	public static function Form($name="",$action="",$method="post",$attribute=""){
		$action=($action!="")  ? $action : $_SERVER['PHP_SELF'];
		return  "<form action='$action' name='$name' id='$name' method='$method' $attribute>";
	}
	
	public static function FormClose(){
		return '</form>';
	}
	
	public static function selectYesNo($name,$value,$default='No'){
		return self::Radio($name,$value,$default,'Yes','No');
	}
	
	public static function Select($name,$array,$default,$attribute=""){
		ob_start();
		?>
			<select name="<?php echo $name ?>"   id="<?php echo $name ?>" <?php echo $attribute ?> >
				<?php foreach ($array as $value=>$label) {?>
						<?php $selected=($value==$default)  ?  'selected'  : ''  ;?>			
						<option value="<?=$value?>" <?=$selected?>><?=$label?></option>
				<?php }?>
			</select>
		<?
		$select=ob_get_clean();
		return $select;
	} 
	
	public static function StateSelect($db, $name, $default = "", $attribute = "", $canada = false){
	
	$canada_insert = ($canada)  ? '			UNION
			SELECT
			DISTINCT(provinceCode) as prefix, 
			province as state
			FROM postalcodes' : '' ;
	$sql = "SELECT
			DISTINCT(state_prefix) as prefix,
			state
			FROM 
			zipcodesall
			WHERE state_prefix !=''
			{$canada_insert}
			ORDER BY prefix";	
		$rs = $db->Query($sql);
		$states[] = ($canada)  ? 'Select State or Province' : 'Select State or Territory' ;
		while($row = $db->GetRow($rs)){
			$states[$row['prefix']] = ucwords( strtolower($row['state']) );	
		}
		return self::Select($name, $states, $default, $attribute);	
	}
	
	public static function returnConfirm($text="Are you Sure?"){ 
		return "onClick=\"return confirm('$text');\"";
	} 
	
	public static function Password ($name,$value="",$default="",$attribute="",$size='30'){ 
		return self::makeInput('password',$name,$value,$attribute,$size);
	}
	
	public static function isAction($value='Submit',$name='action',$method='post'){
		$imageName=str_replace(' ','_',$value);
		switch($method){
			case 'post':
				$request=$_POST;
			break;
			
			case 'get':
				$request=$_GET;
			break;
			
			case 'request':
				$request=$_REQUEST;
			break;
							
		}
		return ($request[$name]==$value || $request[$imageName]==$value)  ? true : false ;
	}

}
