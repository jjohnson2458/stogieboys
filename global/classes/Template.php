<?php

class Template 
{

  /**
   *
   * 
   * @package
   * @subpackage
   * @static
   * @access
   * @author
   * @copyright
   * @deprecated
   * @example
   * @ignore
   * @internal
   * @link
   * @see
   * @since
   * @tutorial
   * @version
   *  inline {@internal}}
   *  inline {@inheritdoc}
   *  inline {@link}
   * <code>
   * $page=new Template($templatePath);
   * 
   * </code>
   *
  include_once('config.php');
  * @example
	$name='J.J. Johnson';
	$address='2458 First St';
	$city='Grand Island';
	$state='New York';	
	$html=new Template('templatetest.html');
	echo($html);

   *
   *
   */

  CONST self=__CLASS__;
  var $template;
/**
*
*
*
*
*
*
*
*/ 
   function  Template($templatePath="",$saveFolder="")
   {
		if (file_exists($templatePath) && !is_dir($templatePath)){
			$this->templatePath=$templatePath;
			if (is_dir($saveFolder) && is_writable($saveFolder)){
				$this->saveFolder=rtrim($saveFolder,'/').'/';
			}
		} else {
			return false;
		}
   }
  
/**
*
*
*
*
*
*
*
*/ 
   function  Cache($name,$bool=false)
   {
		$this->cache=($bool)  ? true : false ;
		$this->filename=$this->saveFolder.Html::clean($name).'.html';
   }
  
/**
*
*
*
*
*
*
*
*/ 
   function  __call($name,$value)
   {

   }
    
/**
*
*
*
*
*
*
*
*/ 
   function  _build()
   {
		if(!$this->template){
			$this->template=File::get($this->templatePath);
		}
		$pattern='/\{\$(.+?)\}/';
		preg_match_all($pattern,$this->template,$matches); 
		$vars=$matches[1];
		$this->html=$this->template;
		foreach ((array)$matches[0] as $key=>$var){
			$this->html=str_replace($var,$GLOBALS[$matches[1][$key]],$this->html);
			$this->replacements++;
		}
		#if($this->cache) $this->_save();
		return $this->html;
   }
  
/**
*
*
*
*
*
*
*
*/ 
   function  _save()
   {

   }
  
/**
* @access public
* fills in values based on name /vaule pair of array:
*
*
*
*
*
*/ 
   function  build($array)
   {
		if(!$this->template){
			$this->template=File::get($this->templatePath);
		}
		$pattern='/\{\$(.+?)\}/';
		preg_match_all($pattern,$this->template,$matches); 
		$vars=$matches[1];
		$html=$this->template;
			foreach((array)$vars as $key=>$var){
				if(isset($array[$var])){
					$html=str_replace('{$'.$var.'}',$array[$var],$html);
				} else{
					$html=str_replace('{$'.$var.'}','',$html);
				}
			}
		return $html;
   }
  
/**
*
*
*
*
*
*
*
*/ 
   function  show($arrayName='params',$code=true)
   {
		$this->template=File::get($this->templatePath);
		$pattern='/\{\$(.+?)\}/';
		preg_match_all($pattern,$this->template,$matches); 
		$vars=$matches[1];
		// get comments if any =======================================
		$commentMatches=array();
		$commentPattern='/\<\!\-\-BUFFNEWS NOTES:(.+?)\-\-\>/s';
		preg_match_all($commentPattern,$this->template,$commentMatches);
		$commentData = (isset($commentMatches[1][0]))  ? $commentMatches[1][0] : "" ;;
		if($commentData!=""){
			$comments=explode(CR,$commentData);
			foreach ((array)$comments as $comment){
				$parts=explode(':',$comment);
				$tagComments[$parts[0]]=$parts[1];
			}
		}	
		
		// ========================================================
		if($code){
			$vars=array_unique($vars);
			foreach((array)$vars as $var){
				$comment = (isset($tagComments[$var]))  ? $tagComments[$var] : '' ;
				echo '$' . $arrayName . '[\'' . ($var) .'\'] = ' . "''; // " . $comment . BR;
			}
			echo '$html = $tmp->build($' . $arrayName . ');' . BR;			
		} else {
			foreach((array)$vars as $var){
				PR::l($var);
			}
		}
		
   }
  
/**
*
*
*
*
*
*
*
*/ 
   function  bind($name="",$array)
   {
		$pattern = "/\{\#bind[::$".$name."](.+?)\}/s";
		preg_match_all($pattern,$this->template,$matches);
		PR::r($matches);
		if (isset($matches[0][0])) {
			$string = $matches[0][0];
		}
		
		if ($string !=""){
			foreach((array)$array as $name => $value){
				$bindings[] = "{$name}='{$value}'";				
			}
			$binding = AR::join($bindings,' ');
		}
		$this->template=str_replace($string,$binding,$this->template);
   }
  
/**
*
*
*
*
*
*
*
*/ 
   function  __toString()
   {
		return $this->_build();
   }
   
   function  __destruct()
   {

   }
}




