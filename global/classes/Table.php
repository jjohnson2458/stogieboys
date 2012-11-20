<?php

class Table
{

  CONST self=__CLASS__;
  
  /* 
   *
   */

function htmlTag($tag,$text,$attributes=""){
	global $cr;
	return "<$tag {$attributes}> $text{$cr}</$tag>";
}

function tag($tag,$text,$attributes=""){
	global $cr;
	return "<$tag {$attributes}> $text{$cr}</$tag>";
}

function trTag($text,$attributes=""){return self::htmlTag('tr',$text,$attributes);}
function tdTag($text,$attributes=""){
	global $cr;
	return $cr.self::htmlTag('td',$text,$attributes);
}

function cells($array, $attributes = "")
{
	$array = AR::a($array);
	$cells = array();
	foreach((array)$array as $cell){
		$cells[] = self::td($cell, $attributes);
	}
	return (AR::join($cells, CR));

}

function td($text, $attributes = "")
{
	return self::tdTag($text, $attributes);
}

function tr($text, $attributes = "")
{
	return self::trTag($text, $attributes);	
}

function create($text, $attributes = ""){
	return self::htmlTag('table', $text, $attributes);	
}

function build($data, $attributes = array())
{
	$attrs = "";
	if(is_array($attributes)){
		$attrs = AR::bind($attributes);
	}
	return self::htmlTag('table', $data, $attrs);
}

function tableTag($text,$width="100%",$border="0",$cellspacing="2",$cellpading="2",$extra=""){
	return htmlTag('table',$text,"width='$width' border='$border' cellspacing='$cellspacing' cellpadding='$cellpading'", $extra);
}

function formTag($text,$method='post',$name='buffnewsform',$action="",$extra=""){
	$action=($action!="")  ? $action : $_SERVER['PHP_SELF'] ;
	global $cr;
	return htmlTag('form',$text,"method='$method' name='$name' action='$action' $extra");
}

function makeHref($url,$text,$attributes=""){
	return "<a href='$url' $attributes>$text</a>";
}

function joinArray($array){
	global $cr;
	return join($cr,$array);
}

function switchColor($light='ffffff',$dark='eaf3fb',$offset='ff0000',$flag=false){
	static $bgcolor='ffffff';
	$bgcolor =($bgcolor!=$light)  ? $light : $dark ;
	$bgcolor=($flag)  ? $offset : $bgcolor ;
	return "bgcolor='#$bgcolor'";
}


function createHeader($array,$width="100%", $border="0", $cellspacing="1" ,$cellpadding="2",$attribute=""){
$array=array_merge(array(),$array);
ob_start();
?>
<table width="100%" border="<?php echo $border ?>" cellspacing="<?php echo $cellspacing ?>" cellpadding="<?php echo $cellpadding ?>" <?php echo $attribute ?>>
	<tr class="header" >
		<?php foreach ($array as $item){?>
		<td nowrap="nowrap"><?php echo $item ?></td>
		<?php } ?>
	</tr>
<?
$header=ob_get_clean();
return $header;
}
function createTable($header,$sql,$actions){
	global $db;
	
	$rs=$db->query($sql);
	ob_start();
	echo createHeader($header);
		while($row=$db->fetch_row($rs)){
			?>
			<tr <?php echo switchColor() ?>>
			<?
			foreach ($actions as $field=>$value){
				if ($value==""){
				echo "<td>$row[$field]</td>";
				} else {
				$eval='echo '.str_replace($field,'$row[$field]',$value).';';
				echo "<td>";
				eval ($eval);
				echo "</td>";
				}			
			}
		echo "</tr>";		
		}
		echo '</table>';
		$table_rows=ob_get_clean();
		return $table_rows;
}
   function  __destruct()
   {

   }
}