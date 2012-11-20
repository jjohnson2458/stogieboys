<?
error_reporting(1);

    /* super includes: */

	#include_once('messenger.php');
	
	/* *** Debug / PHP Enhancement functions ****************************** *** */
    /**
     * Redirects to the $url by http headers, javascript, and anchor-clicking,
     * in that order, passing on to the next option on failure.  If the debug
     * constant QUICK_REDIRECT is set to false, headers and javascript will be
     * bypassed, leaving only a link to click for debugging purposes
     */
    if (!defined("OFFSET")){
		define("OFFSET",0); ## to put server on Local time
	}
	
	function redirect($url){
        if(QUICK_REDIRECT !== false){
            @header( "Location: {$url}" );
            print "<script type='text/javascript'>window.location = '{$url}';</script>";
        }
        print "<span style='font:12px verdana'>";
        print "Please click here to continue: <a href='{$url}'>Continue!</a>\n";
		print "</span>";
        die;
    }

    function xmlentities($string, $quote_style=ENT_QUOTES)
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

	
	function popupWindow($url,$width=800,$height=600,$name=""){
		ob_start();
		?>
		<script type="text/javascript">
		window.open('<?php echo $url ?>','_<?php echo $name ?>','width=<?php echo $width ?>,height=<?php echo $height ?>');
		</script>
		<?
		$popup=ob_get_clean();
		echo $popup;
		return;
	}
	
    /**
     * print a value with line break at the end
     */	
	
	function println($text=""){
		if ($text!="") echo $text.'<br>';
	}
	
    /**
     * Uses a javascript alert to print whatever string you send it.
     */
    function alert($string){
        if($string === true || $string === false){
            ob_start();
            var_dump($string);
            $string = ob_get_clean();
        }
        $string = addslashes($string);
        $string = str_replace("\n", '\n', $string);
        print "&nbsp;<script type=\"text/javascript\">alert(\"{$string}\");</script>";
    }
	/**
	 * Returns the string " selected" (used for select boxes) if $val matches $sel
	 */	
	function selected($sel, $val) {
		if ($val == $sel) {
			$selected = " selected";
		} else {
			$selected = "";
		}
		return $selected;
	}

	
    /**
     * HTML Formatting for a print_r.
     */
    function print__r(){
		if(defined("NO_PRINT")){
			return;
		} else {
			$args=func_get_args();
			foreach ((array) $args as $anything) {
				if(is_array($anything) || is_object($anything)){
					?><pre><? print_r($anything); ?></pre><?
				} else {
					?><pre><? var_dump($anything); ?></pre><?
				}
			}
		}
    }
	
	#function print__r($anything){return pretty_print_r($anything);}
    /**
     * Alerts by javascript the print_r.
     */
    function alert_print_r($anything){
        $string = string_print_r($anything);
        $string = addslashes($string);
        $string = str_replace("\n", '\n', $string);
        print "&nbsp;<script type=\"text/javascript\">alert(\"{$string}\");</script>";
    }
    /**
     * Returns the print_r of $anything in a string.
     *
     * If $pretty === true, it will return the data as a formatted string like 
     * pretty_print_r would have.
     */
    function string_print_r($anything, $pretty=false){
        ob_start();
        if($pretty===true){ 
            pretty_print_r($anything); 
        } else {
            if(is_array($anything)){
                print_r($anything);
            } else {
                var_dump($anything);
            }
        }
        $string = ob_get_clean();
        return $string;
    }
    /**
     * Emails the print_r of $anything in a string.
     * send to email address specified; defaults to TEST_EMAIL
	 * subject: name of variable $anything
	 * line: line number of the script (use __LINE__);
     */	
	 function print__e($anything,$subject="",$line="",$email=TEST_EMAIL){
	 	global $cr;
		global $br;
		global $jj;
		#$message=$_SERVER[REQUEST_URI].$cr.$br;
		ob_start();
			echo $_SERVER[REQUEST_URI].$cr.$br;
			print__r($anything);
		$message=ob_get_clean();			
		$subjectline="$subject ".getLocalTime();
		mail('email4johnson@gmail.com',$subjectline,$message,mailHeader());
		return $message;
	 }
/* outputs html into a given file in the data/errors folder*/
	function print__f($anything,$filename='errors.txt'){
		ob_start();
		print__r($_SERVER[REQUEST_URI]);
		print__r(date('Y-m-d H:i:s'));
		print__r($anything);
		$message=ob_get_clean();
			/*complie filename*/
		$file=(strpos($filename,'.txt'))  ? str_replace('/','',$filename) : $filename.'.txt' ;
		$fh=@fopen(GATEWAY_DATA_DIR.'errors/'.$file,'w+');
		#print__r(GATEWAY_DATA_DIR.'/errors/'.$file);
		#die;
		@fwrite($fh,$message);
		fclose($fh);
			
	}	
	
	/**
	 * print_r wrapped by an HTML comment.
	 */
	function comment_print_r($whatever) {
		print "\n<!--\n " . string_print_r($whatever) . "\n-->\n";
	}
	
	function print__v($name){
		$whatever=$GLOBALS[$name];
		print__r("$name: ".$whatever);
	}
	
    /**
     * Will print the $whatever on either the condition of DEBUG_PRINTING being
     * set to true, or the condition of DEBUG_PRINTING being set to the particular
     * $debug_key string passed into this function.
     */
    function debug_print($whatever, $debug_key=''){
        if(DEBUG_PRINTING === true || DEBUG_PRINTING === $debug_key){
            print__r($whatever);
    }   }
    /**
     * Takes a string $glue as the first argument, and then any number of strings
     * after that, joining them together with $glue.
     */
    function join_strings($glue=' '){
        $strs = Array();
        for($i = 1; $i < func_num_args(); $i++){ $str[] = func_get_arg($i); }
        return implode($glue, $str);
    }
    /**
     * Emulates the PHP function html_entity_decode because the demo server is
     * totally bum.
     */
    if(!function_exists('html_entity_decode')){
        function html_entity_decode ($string) {
           $trans_tbl = get_html_translation_table(HTML_ENTITIES);
           $trans_tbl = array_flip($trans_tbl);
           return strtr($string, $trans_tbl);
    }   }
    /**
     * Emulates the PHP function ob_get_clean because the demo server is
     * totally bum.
     */
    if(!function_exists('ob_get_clean')){
        function ob_get_clean(){
            $s = ob_get_contents();
            ob_end_clean();
            return $s;
    }   }
    /**
     * Emulates the PHP function mysql_real_escape_string because the demo
     * server is totally bum.
     */
    if(!function_exists('mysql_real_escape_string')){
        function mysql_real_escape_string($string){
            return mysql_escape_string($string);
    }   }
    /**
     * POORLY Emulates the PHP function file_get_contents because the demo
     * server is totally bum.
     */
    if(!function_exists('file_get_contents')){
        function file_get_contents($filename){
            return implode('', file($filename));
        }
    }
    /* *** **************************************************************** *** */
    
    /* *** HTML Anchor functions ****************************************** *** */
    /**
     * Takes a url and adds new getstring arguments to it, overwriting old
     * gs arguments with the same name.  Use any number of new arguments or
     * old ones.
     *
     * $new_arguments can come in this form:
     *     Array( 'arg_name'=>'arg_value', 'arg_2_name'=>'arg_2_value' ... );
     *     -or-
     *     "arg_name=arg_value&arg_2_name=arg_2_value&..."
     *     -or-
     *     "arg_name=arg_value&amp;arg_2_name=arg_2_value&amp;..."
     * and the returned url will be in this form:
     *     addToGetString('test.html', 'arg_name=arg_value&arg_2_name=arg_2_value')
     *         : "test.html?arg_name=arg_value&amp;arg_2_name=arg_2_value"
     *     addToGetString('test.html?arg_x=xxx', 'arg_name=arg_value&arg_2_name=arg_2_value')
     *         : "test.html?arg_x=xxx&amp;arg_name=arg_value&amp;arg_2_name=arg_2_value"
     *     addToGetString('test.html?arg_name=prev_value', 'arg_name=new_value&arg_2_name=arg_2_value')
     *         : "test.html?arg_name=arg_value&amp;arg_2_name=arg_2_value"
     ********************************************************************* *** */
    function addToGetString($old_url, $new_arguments){
        $old_url  = trim($old_url, '&');
        $working_arguments = Array();

        //get the old arguments and prepare the base url
        if(strpos($old_url, '?') !== false){
            $new_url       = substr($old_url, 0, strpos($old_url, '?'));
            $delimiter     = '&';
            $old_arguments = explode($delimiter, substr($old_url, strpos($old_url, '?')+1));

            foreach($old_arguments as $arg){
                $arg = explode('=', $arg);
                if($arg[0] == '' || $arg[1] == ''){ continue; }
                $working_arguments[$arg[0]] = $arg[1];
            }
        } else { $new_url = $old_url; }


        //make the $new_arguments an array in proper format if it's not one now
        if(!is_array($new_arguments)){
            $replace       = Array();
            $delimiter     = '&';
            $new_arguments = explode($delimiter, $new_arguments);
            foreach($new_arguments as $arg){
                $arg = explode('=', $arg);
                $replace[$arg[0]] = $arg[1];
            }
            $new_arguments = $replace;
        }

        /*
         * insert the values of the $new_arguments, and remove any
         * arguments that were set to blank.
         */        
        $working_arguments = array_merge((array) $working_arguments, (array) $new_arguments);
        foreach($working_arguments as $var => $arg){
            if($var == '' || $arg == ''){ 
                unset($working_arguments[$var]);
            }
        }
        
        foreach ($working_arguments as $arg => $value) { $working_arguments[$arg] = urlencode($arg).'='.urlencode($value); }

        return  $new_url.'?'.implode('&',$working_arguments);
    }
    /**
     *  @return string The $string, with only the alphabetical characters returned
     *  in lower case.
     *  
     *  $additional_allowed_chars will also be allowed if specified ((as a regex char-class bit))
     *  $strip_only will strip only the regex char-class bit included
     */
    function stripDown($string, $additional_allowed_chars='', $strip_only=''){
        if($strip_only!=''){
            $string = strtolower(preg_replace("/[{$strip_only}]*/s", '', $string));
        } else {
            $string = strtolower(preg_replace("/[^a-zA-Z{$additional_allowed_chars}]*/s", '', $string));
        }
        return $string;
    }
    /**
     * Takes a url with get string arguments, and an array of arguments ((argument names)) to
     * delete, removes those arguments and their values from the getstring, and then returns
     * the new get string.
     */
    function removeFromGetString($old_url, $arguments_to_delete){
        $old_url  = trim($old_url, '&');

        //get the old arguments and prepare the base url
        if(strpos($old_url, '?') !== false){
            $new_url = substr($old_url, 0, strpos($old_url, '?'));
            $old_arguments = explode('&', substr($old_url, strpos($old_url, '?')+1));

            foreach($old_arguments as $arg){
                $arg = explode('=', $arg);
                if($arg[0] == '' || $arg[1] == ''){ continue; }
                $working_arguments[$arg[0]] = $arg[1];
            }
        } else { $new_url = $old_url; }

        //make the $arguments_to_delete an array in proper format if it's not one now
        if(!is_array($arguments_to_delete)){ $arguments_to_delete = preg_split("/[\W]+/", $arguments_to_delete); }

        //remove them
        foreach($arguments_to_delete as $arg){ unset($working_arguments[$arg]); }

        //insert the values of the $new_arguments
        foreach ($working_arguments as $arg => $value) { $working_arguments[$arg] = $arg.'='.$value; }

        return  $new_url.'?'.implode('&',$working_arguments);
    }
    /**
     * Returns the url with all Get String Variables removed: no query string.
     */
    function stripGetStringVars($old_url){
        if(strpos($old_url, '?') !== false){
            $new_url = substr($old_url, 0, strpos($old_url, '?'));
        } else { $new_url = $old_url; }
        return $new_url;
    }
    /* *** **************************************************************** *** */

	/* *** Formatting and String Manipulation functions ******************* *** */
	function stringMoney($string) { return number_format($string, 2, '.', ','); }
	/**
	 * Returns a string where $string_to_remove$string_to_remove ((at any multiple
	 * of $string_to_remove)) has been reduced to a single $string_to_remove.
	 */
	function stripMultiples($string_to_remove, $string, $strip_if_count_greater_than=1){
		$new_string = $string;
		if($strip_if_count_greater_than > 1){
			$string = urlencode($string);
			$string_to_remove = urlencode($string_to_remove);
			for($i = 0; $i < $strip_if_count_greater_than; $i++){
				$finding_string .= $string_to_remove;
			}
			$new_string = preg_replace("/{$finding_string}({$string_to_remove})*/", $finding_string, $string);
			return urldecode($new_string);
		} else {
			do {
				$old_string = $new_string;
				$new_string = str_replace($string_to_remove.$string_to_remove, $string_to_remove, $old_string);
			} while($new_string != $old_string);
			return $new_string;
	}	}
    if(!function_exists('verify_uid')){
        function verify_uid($uid){
            $x = md5($uid);
            $x = substr($x, 2, 12).substr($x, 1, 5);
            $x = str_replace('0', '3', $x);
            $x = str_replace('1', '3', $x);
            $x = str_replace('2', '3', $x);
            $x = str_replace('3', '5', $x);
            $x = str_replace('4', '6', $x);
            $x = str_replace('5', '7', $x);
            $x = str_replace('6', '3', $x);
            $x = str_replace('7', '4', $x);
            $x = str_replace('8', '8', $x);
            $x = str_replace('9', 'd', $x);
            $x = str_replace('a', 'e', $x);
            $x = str_replace('b', 'f', $x);
            $x = str_replace('c', '9', $x);
            $x = str_replace('d', 'a', $x);
            $x = str_replace('e', 'b', $x);
            $x = str_replace('f', 'c', $x);
            $x = stripMultiples('0', $x);
            $x = stripMultiples('1', $x);
            $x = stripMultiples('2', $x);
            $x = stripMultiples('3', $x);
            $x = stripMultiples('4', $x);
            $x = stripMultiples('5', $x);
            $x = stripMultiples('6', $x);
            $x = stripMultiples('7', $x);
            $x = stripMultiples('8', $x);
            $x = stripMultiples('9', $x);
            $x = stripMultiples('a', $x);
            $x = stripMultiples('b', $x);
            $x = stripMultiples('c', $x);
            $x = stripMultiples('d', $x);
            $x = stripMultiples('e', $x);
            $x = stripMultiples('f', $x);
            
            return $x;
        }
    }
	/* *** **************************************************************** *** */

    /* *** Html Stripping functions *************************************** *** */
    function makeProperHTML ($input){ 
        if(!is_array($input)) {
            return addslashes(htmlentities($input));
        } else {
            array_walk($input, 'makeProperHTML');
        }
    }
    function stripProperHTML ($input){
        // return html_entity_decode(stripslashes($input)); //<-- when demo gets above php 4.3.1
        if(!is_array($input)) {
            $input = stripslashes($input);
            $table = get_html_translation_table(HTML_ENTITIES);
            $table = array_flip($table);
            return strtr($input, $table);
        } else {
            array_walk($input, 'stripProperHTML');
        }
    }
    /* *** **************************************************************** *** */

    /* *** Database prep functions **************************************** *** */
    function db_to($arr) {
        if(is_array($arr)){
            foreach($arr as $k => $v){
                $arr[$k] = db_to($v);
            }
        } else {
            if(!$GLOBALS['db_to_mysql_connection']){
                $GLOBALS['db_to_mysql_connection'] = mysql_connect('localhost', 'sql_user', 'l0ve2code', true);
            }
            if(get_magic_quotes_gpc()){
                $arr = stripslashes($arr);
            }
            $arr = mysql_real_escape_string($arr, $GLOBALS['db_to_mysql_connection']);
        }
        return $arr;
    }
    function db_from($arr) {
        if(is_array($arr)){
            foreach($arr as $k => $v){
                $arr[$k] = db_from($v);
            }
        } else {
            $arr = str_replace('\r\n', "\n", $arr);
            $arr = str_replace('\r',   "\n", $arr);
            $arr = str_replace('\n',   "\n", $arr);
        }
        return $arr;
    }
	if (!function_exists("clean_input")){
		function clean_input(&$_INPUT){
			$_INPUT = db_to($_INPUT);
		}
	}
    /* *** **************************************************************** *** */
    
    /* *** Array functions ************************************************ *** */
    /**
     * Makes an icon link to the app-root based $filename.
     */
    function makeGoLink($filename, $title="Go!"){
        $html .= 
            '<a href="'.$_SERVER['APPLICATION_PREPEND'].$filename.'" '.
            'title="'.$title.'">'.
                '<img src="'.APP_IMAGES_DIR.'bullet_go_arrow.gif" alt="Go!" />'.
            '</a>';
        return $html;
    }
    function makeHtmlList($list_array, $list_type='ul'){
        if(!is_array($list_array)){ return false; }
        
        $l = '';
        switch($list_type){
            case 'dl':
                $l = "<$list_type class=\"generated_list\">\n";
                foreach($list_array as $k => $v){
                    $l .= "\t<dt>$k</dt>\n\t\t<dl>$v</dl>\n";
                }
                $l .= "</$list_type>";
                break;
            default:
                /* case 'ol': */
                /* case 'ul': */
                $l = "<$list_type class=\"generated_list\">\n";
                foreach($list_array as $v){
                    $l .= "<li>$v</li>";
                }
                $l .= "</$list_type>";
                break;
        }
        return $l;
    }
 
	/*
	* 	helpful function for json decoding - makes an array readable
	*/
	 if (!function_exists('setArray')){
		 function setArray($array){
			foreach ((array)$array as $key=>$value){$array[$key]=$value;}
			return $array;
		 }
 	 }
    function array_empty($array, $string_to_consider_empty=''){
        foreach($array as $k => $v){
            if($v && $v!=$string_to_consider_empty){ return false; }
        }
        return true;
    }
    function array_peek($array){ return reset($array); } 
    function array_find_slot($array){
        $i = 0;
        for($i = 0; $i<=count($array); $i++){
            if($array[$i] == ''){
                return $i;
            }
        }
        return $i+1;
    }
    function in_array_multi($needle, $haystack, $strict=false){
        if(is_array($haystack)){
            foreach($haystack as $new_haystack){
                if(in_array_multi($needle, $new_haystack, $strict)){ return true; } 
            }
            return false;
        } else { return ($strict)?$needle===$haystack:$needle==$haystack; } 
    }
	
	/* remove an element from a single-dimension array */
if (!function_exists('array_remove')) {
	function array_remove(&$array=array(), $value='') {
		$array_keys=array_keys($array,$value);
		foreach ((array)$array_keys as $key) {
			unset($array[$key]);
		}		
		return $array;
	}	
}

function array_add(&$array=array(),$value=""){
	if (is_array($value)){
		return array_merge($array,$value);
	}
	if ($value!="") $array[]=$value;
	return array_unique($array);
}

function array_file($array=array(),$filename=""){
	global $cr;
	if (is_file($filename) && is_writable($filename)){
		$fh=@fopen($filename,'w+');
		fwrite($fh,join($cr,$array));
		fclose($fh);
		return true;
	} else {
		return false;
	}
}
	
    /**
     * two-dimensional Array Sub Key SORT (ASKSORT)...i tried to follow php
     * function naming conventions...which basically don't exist, but this is
     * how most of the array sort functions were named.
     * It should sort an array by a subkey: so an array where every element
     * had a key 'x', you could then sort the entire array based on x. It
     * sorts the array by reference, like PHP's sorting functions.
     */
    function asksort(&$array, $key_to_sort, $sort=SORT_REGULAR){
        $order = Array();
        $new_array = Array();
        foreach($array as $key => $sub_array){ 
            $order[$key] = $sub_array[$key_to_sort]; 
        }
        if(asort($order, $sort) === false){ return false; }
        foreach($order as $key => $sorted_value){ 
            $new_array[$key] = $array[$key];
        }
        $array = $new_array;
        return true;
    }
    function in_array_sublevel($sublevelKey, $value, $array){
        foreach($array as $k => $v){
            if(is_array($v)){ if(in_array_sublevel($sublevelKey, $value, $v)){ 
                return true; } } 
            else if($k == $sublevelKey && $value==$v){ 
                return true; }  }
        return false;
    }
    /**
     * Outputs a php array to a javascript equivalent.
     *
     * Handles multi-dimensional associative arrays. 
     * $rray_name is the name the array will have in javascript, the var name.
     * if $var is specified to be true, it will make the array a var.  $tabs
     * should contain as many slash-t ((\ t)) elements as you want for the 
     * array to be tabbed in in js script. $keys will print all the keys
     * within the array as the hash value of array["key"]
     */
    function phpArrayToJsArray($rray, $rray_name, $tabs="\t", $var=false, $keys=false) {
        $js = "{$tabs}".(($var)?"var ":"")."{$rray_name} = new Array();\n"; 
        
        if(is_array($rray) && count($rray)>0){ 
            foreach($rray as $k => $sub_elem){ 
                if($keys) { $js .= "{$tabs}{$rray_name}['key'] = {$k};\n"; }
                if(!is_numeric($k)){ $k = "'{$k}'"; }
                if(is_array($sub_elem)){ 
                    $js .= phpArrayToJsArray($sub_elem, "{$rray_name}[{$k}]", $tabs); 
                } else { 
                    $sub_elem = str_replace("</", "<\\/", addslashes($sub_elem)); 
                    if(($sub_elem=='') || (!is_numeric($sub_elem))){ 
                        $sub_elem = "\"{$sub_elem}\""; 
                    } 
                    $js .= "{$tabs}{$rray_name}[{$k}] = {$sub_elem};\n"; 
                }
            }
        } 
        return $js;
    }
    /**
     * Splices an array, but keeps the keys in $insert_array when inserting.
     *
     * Returns nothing.
     */
    function array_splice_keep_keys (&$array, $position, $length='', $insert_array='') {
        if($insert_array==''){
            $insert_array = Array();
        }
        $first_array = array_splice ($array, 0, $position);
        array_splice ($array, 0, $length);
        $array = array_merge ($first_array, $insert_array, $array);
    }
    /* *** **************************************************************** *** */
    
    /* *** HTML Manipulation Functions ************************************ *** */
    function GetFileIcon($file_name){
        $ext = substr(strrchr($file_name, "."), 1);
        switch($ext){
            case "asp" : $icon="ico_asp.gif";     break;
            case "bmp" : $icon="ico_bmp.gif";     break;
            case "css" : $icon="ico_css.gif";     break;
            case "doc" : $icon="ico_doc.gif";     break;
            case "exe" : $icon="ico_exe.gif";     break;
            case "gif" : $icon="ico_gif.gif";     break;
            case "htm" : $icon="ico_htm.gif";     break;
            case "html": $icon="ico_htm.gif";     break;
            case "jpg" : $icon="ico_jpg.gif";     break;
            case "js"  : $icon="ico_js.gif";      break;
            case "mdb" : $icon="ico_mdb.gif";     break;
            case "mov" : $icon="ico_mov.gif";     break;
            case "mp3" : $icon="ico_mp3.gif";     break;
            case "pdf" : $icon="ico_pdf.gif";     break;
            case "png" : $icon="ico_png.gif";     break;
            case "ppt" : $icon="ico_ppt.gif";     break;
            case "mid" : $icon="ico_sound.gif";   break;
            case "wav" : $icon="ico_sound.gif";   break;
            case "wma" : $icon="ico_sound.gif";   break;
            case "swf" : $icon="ico_swf.gif";     break;
            case "txt" : $icon="ico_txt.gif";     break;
            case "vbs" : $icon="ico_vbs.gif";     break;
            case "avi" : $icon="ico_video.gif";   break;
            case "wmv" : $icon="ico_video.gif";   break;
            case "mpeg": $icon="ico_video.gif";   break;
            case "mpg" : $icon="ico_video.gif";   break;
            case "xls" : $icon="ico_xls.gif";     break;
            case "zip" : $icon="ico_zip.gif";     break;
            default    : $icon="ico_unknown.gif"; break;
        }
        return "<img src=\"".APP_IMAGES_DIR."icons/{$icon}\" />";
    }
	
		function ObjectToArray($data) 
	{
	  if(is_array($data) || is_object($data))
	  {
		$result = array(); 
		foreach($data as $key => $value)
		{ 
		  $result[$key] = ObjectToArray($value); 
		}
		return $result;
	  }
	  return $data;
	}
	
    function CommonImage($file_name, $alt=''){
        $alt = (($alt)?"alt=\"{$alt}\" ":'');
        return "<img src=\"".APP_IMAGES_DIR."{$file_name}\" {$alt}/>";
    }
    /* *** **************************************************************** *** */
	function cleanText($text){
	$text=str_replace(chr(32),'^',$text);
	$text= preg_replace("/[^a-zA-z0-9@\.\-\_]/",chr(32), $text);
	$text=str_replace('^',chr(32),$text);
	return $text;
	} 
	
	function showMoney($value){
	return "$".number_format($value,2);
}
	
	function formatPhone($phone) {
	$number = preg_replace("/[^0-9]/","",$phone);

	switch (strlen($number)) {
		case 7 :
			return substr($number, 0,3).'-'.substr($number,3,4);
		break;
		
		case 8 :
		case 9 :
			return substr($number, 0,3).'-'.substr($number,3,4). ' ext. '.substr($number,7);
		break;
		
		case 10:
			return '('.substr($number, 0,3).') '.substr($number, 3,3).'-'.substr($number,6,4);
		break;
		
		default :
			if (strlen($number) < 7) return $phone;
		return '('.substr($number, 0,3).') '.substr($number, 3,4).'-'.substr($number,6,4).' ext. '.substr($number,10);

	}
}

		function doPagination ($total_rows,$per_page,$PAGES_PER_PAGE,$request_url=""){ ## <-- very cool app :-)
	#function ToHtml(total_rows){
		#$html = CDBList::ToHtml();
		$request_url=($request_url!="")  ? $request_url : $_SERVER['REQUEST_URI'] ;
		$paginationCode = '';
		if ($per_page) {
			$page = (!isset($_GET['page']))? 1 : $_GET['page'];
			$prev = ($page - 1);
			$next = ($page + 1);
			$max_records = $per_page;
			$from = ($page - 1) * $max_records;
			$total_result = $total_rows;
			$total_pages = ceil($total_result / $max_records);
			$ppp = $PAGES_PER_PAGE;
			if ($total_pages > 1 && $ppp && $ppp < $total_pages) {
				if ($page > $ppp) {
					$first_page = $page;
				} else {
					$first_page = 1;
				}
				$last_page = $ppp + $first_page;
				if ($last_page > $total_pages) {
					$last_page = $total_pages;
					$first_page = $last_page - $ppp;
				}
			} else {
				$first_page = 1;
				$last_page = $total_pages;
			}
			$prev_shown = false;
			$next_shown = false;
			$generic_uri = addToGetString($request_url, "page=PAGEREPLACE");
			$extra_data = "";
			if ($pass_key) {
				foreach ($pass_key as $key => $value) {
					if (is_array($value)) {
						foreach ($value as $getValue) {
							$extra_data .= "&" . urlencode($key) . "=" . $getValue;
						}
					} else {
						$generic_uri = addToGetString($generic_uri, "$key=$value");
					}
				}
				$generic_uri .= $extra_data;
			}
			if ($first_page > 1) {
				$prev_pages = $page - $ppp;
				$prev_shown = true;
			}
			if ($last_page != $total_pages) {
				$next_pages = $page + $ppp;
				$next_shown = true;
			}
			if(($total_pages > 1) && ($page > 1)) {
				$uri = str_replace('PAGEREPLACE', $prev, $generic_uri);
				$paginationCode .= '<a class="page-prev" href="'.$uri.'">&laquo; Previous</a>';
			}
			if ($total_pages > 1) {
				for($i = $first_page; $i <= $last_page; $i++) {
					if(($page) == $i) {
						$paginationCode .= ' <span class="page-num">'.$i.'</span> ';
					} else {
						$uri = str_replace('PAGEREPLACE', $i, $generic_uri);
						$paginationCode .= ' <a href="'.$uri.'" class="page-link">'.$i.'</a> ';
					}
				}
			}
			if(($total_pages > 1) && ($page < $total_pages)) {
				$uri = str_replace('PAGEREPLACE', $next, $generic_uri);
				$paginationCode .= '<a class="page-next" href="';
				$paginationCode .= $uri . '">Next &raquo;</a>';
			}
			$paginationCode .= "<br /><br />";
			if ($prev_shown) {
				$uri = str_replace('PAGEREPLACE', '1', $generic_uri);
				$paginationCode .= '<a class="page-first" href="'.$uri.'">First Page&nbsp;|&nbsp;</a>';
				$uri = str_replace('PAGEREPLACE', $prev_pages, $generic_uri);
				$paginationCode .= '<a class="page-prev" href="'.$uri.'">Previous. '.$ppp.' Pages</a>';
			}
			if ($next_shown) {
				if ($prev_shown) $paginationCode .= '&nbsp;|&nbsp;';
				$uri = str_replace('PAGEREPLACE', $next_pages, $generic_uri);
				$paginationCode .= '<a class="page-next" href="'.$uri.'">Next '.$ppp.' Pages&nbsp;|&nbsp;</a> ';
				$uri = str_replace('PAGEREPLACE', "$total_pages", $generic_uri);
				$paginationCode .= '<a class="page-last" href="'.$uri.'">Last Page</a>';
			}
			if ($prev_shown || $next_shown)	$paginationCode .= "<br><br>";
			$min = ($page - 1) * $per_page + 1;
			$max = min($page * $per_page, $total_rows);
			$paginationCode .= "<div class=\"page-showing\">Showing records $min-$max of {$total_rows} ($total_pages pages total).</div>";
		}
		$html .= $paginationCode . "<br />";
		return $html; 
	}
#if (!function_exists('dropdownmenu')){	
	function createDropDown ($SQL,$select_value,$id,$name,$default_value="",$attribute=""){
	global $db;
	
	$rs=$db->Query($SQL);
		if (!$rs) return false;
	$selected='selected';
	$dropdown="<select name=\"$select_value\" $attribute>";
		while($row=$db->GetRow($rs)){
			if (!$row) return false;
		$dropdown.="<option value=\"".$row[$id]."\"";
		$dropdown.=($row[$id]==$default_value)  ? $selected : "" ;
		$dropdown.=">".$row[$name]."</option>";
		}
	$dropdown.="</select>";
	
	return $dropdown;
	
	}	
#}
function checkdata($target){return (isset($target) && $target != "");}

function checkfields($fields_info){
if ($fields_info['Extra']=='auto_increment') return false;
return true;
}

function fieldSelect($table,$fields){ 
	/* returns only $fields that exist in $table*/
	global $db;
	$return_fields=array(); 
	$sql="SHOW COLUMNS FROM {$table}";
	$res=$db->query($sql);
		while ($row=$db->fetch_row($res)){			
			$existing_fields[]=$row['Field'];
		}
	#$existing_fields=$db->GetFields($table);
	#print__r($existing_fields);
		if(!is_array($existing_fields)) return false;
		foreach((array)$fields as $field=>$value){			
			if (in_array($field,$existing_fields)){
				$return_fields[$field]=$value;
			}
		}
	return $return_fields;
}

function insertSQL($table,$data=array(),$whereclause=array(),$inject=true){
global $db;	
	$key=key($whereclause);
	$where=" WHERE $key='$whereclause[$key]'";
	$sql=" SELECT * FROM $table WHERE $key=?";
	/*to do: mutiple fields checks in whereclause*/
	$res=$db->query($sql,$whereclause[$key]);
	$row=$db->fetch_row($res);
#print__r($row);
//echo mysql_error();
$COMMAND  = (empty($row))  ? " INSERT INTO "  : " UPDATE " ;
$CONDITION= (empty($row))  ? ""  : " $where LIMIT 1 " ;
	if (count($data) < 1) return false;
	#print__r($row);
$sql="$COMMAND `$table` SET ".sqlJoin(fieldSelect($table,$data)).$CONDITION;
	if ($inject) {	
		$db->trueQuery($sql);	
	} else {
	#echo $sql.'<br>';
	}
return $sql;
}


function sqlJoin($array,$whereclause=""){
if (!is_array($array)) return false;
	$sql=" ";
	#$array=db_to($array);
		foreach ($array as $field=>$value){
			if ($value==TBN_NULL){			
				$sql.="`$field`=NULL,";
			} else {
				$sql.="`$field`='".db_to($value)."',";
			}
		}
	return rtrim($sql,",")." $whereclause";
}

function aesEncrypt($value){
	return '_AES_ENCRYPT->'.$value;
}

function processDate($date){
if (checkdata($date) && strtotime($date)){return date('M j, Y',strtotime($date));}
}

/*takes default date and returns date in readable 
* default day= current day
* default format (ex): Jan 1, 2008 3:00 pm
* returns orginal data if date is in improper format
*/
function readableDate($date="",$format='M j, Y g:i a'){
 $date=($date!="")  ? $date : date('Y-m-d H:i:s') ;
 return (strtotime($date))  ? date($format,strtotime($date)) : "" ; 
} 

function getLocalTime($format='Y-m-d H:i:s'){
	return (date($format,time()+OFFSET));	
}

function loadCSVFile($filename,$header=true){

if (!file_exists($filename)) {
	return array();
}
$fh=fopen($filename,'r');
	while (($data = fgetcsv($fh, 1024, ",")) !== FALSE) {
		if ($header){
			foreach ($data as $key=>$field){
				$fields[]=$field;
			} 
		$header=false;
		$have_fields=true;
		} else {
			$i=0;
			foreach ($data as $key=>$value){
				($have_fields)  ?  $line[$fields[$i]]=$value  : $line[$i]=$value ;
				$i++;
			}
		$csv[]=$line;		
		}
	}
	fclose($fh);
	return $csv;
}

function insertPrep($array_name,$dbtable,$post=false){
/* comment by J.Johnson: anyone using this function for live use on server should be shot (twice). This function is ONLY to assist writing code for the insertSQL function. 
It returns a code template if the values the user will set up as an array for the insertSQL function.

*/ 
global $db;
	$sql="SHOW COLUMNS FROM {$dbtable}";
	$res=$db->query($sql);
		while ($row=$db->fetch_row($res)){			
			$fields[]=$row[Field];
		}

pretty_print_r($_POST);
	if (isset($fields[datetime])) unset($fields[datetime]);

	foreach ($fields as $key=>$field){
		$POST=($post)  ? '$_POST['.$field.']' : '' ;
		$left=	'$'.$array_name.'['.$field.']=';	
		#echo '$'.$array_name.'['.$field.']='.$POST.';<br>';
		echo str_pad($left,30,"-").$POST.';<br>';
	}

echo '<br>';	
echo '$whereclause[checksum]=md5(join(",",'.$array_name.'))";'.'<br>';
echo '$'.$array_name.'[datetime]=getLocalTime();<br>';
echo '$'.$array_name.'_sql=insertSQL(\''.$dbtable.'\',$'.$array_name.',$whereclause,false);'.'<br>';
echo 'echo ($'.$array_name.'_sql);<br>';
#echo '$db->GetID(false,$'.$array_name.'_sql)' ;
die;
}

if (!function_exists('setFocus')){
	function setFocus($id){
		?>
		<script type="text/javascript">
		document.getElementById("<?=$id?>").focus();
		</script> 
		<?
	}
}

function roundOff($value){
	return number_format($value,2);
}

function sendMessage($message,$statement="",$email='email4johnson@gmail.com',$from='messagedesk@empireliquidators.com'){
	$output[datetime]=getEmpireTime();
	$output[statement]=$statement;
	$output[session]=$_SESSION;
	$output[user]=getUserName();
	$output[script]=$_SERVER['REQUEST_URI'];
	$output[message]=$message;
	
	$to=$email;
	$from=$from;
	$subject='Message Alert: '.getUserName()."-".getEmpireTime();
	ob_start();
	pretty_print_r($output);
	$output_message=ob_get_clean();
		$header= "From: Empire Liquidators <$from>\n";
		$header.= "MIME-Version: 1.0\r\n"; 
		$header.= "Content-type: text/html; charset=iso-8859-1\r\n"; 
		$header.= "X-Sender: <$from>\n";
		$header.= "X-Mailer: PHP\n";
		$header.= "X-Priority: 3\n";
		$header.= "Return-Path: <$from>\n";
	mail($to,$subject,$output_message,$header);
	$filename=$_SERVER['DOCUMENT_ROOT'].'/error_log/'.getUserName()."_".time().'.txt';
	$fh=fopen($filename,'w');
	fwrite($fh,$output_message);
	fclose($fh);
}

function CloseAndRefresh(){
	echo "<script type='text/javascript'>window.opener.location.reload();window.close();</script>";
}

function CloseAndRefreshButton(){
	echo "<input type='button' class='button_small' onClick='window.opener.location.reload();window.close();' value='Close This Window'>";
}

function CloseThisWindow(){
	echo "<script type='text/javascript'>window.close();</script>";
}

function CloseThisWindowButton(){
	echo "<input type='button' onClick='window.close();' value='Close This Window' class='button_small'>";
}
/*for db_functions */

function GetRow($sql,$id=""){
	global $db;
	$result = $db->query($sql,$id);
	$row = $db->fetch_row($result);
	return (!empty($row))  ?  $row :  false ;
}

if (!function_exists("addQuotes")){
	function addQuotes($text){
	return chr(34).trim($text).chr(34);
	}
}

if (!function_exists("addQuote")){
	function addQuote($text){
	return chr(39).trim($text).chr(39);
	}
}

function makeHash($array){
	return(is_array($array)) ? md5(join(',',$array))  : md5($array)  ;	
}

function commentPagination ($total_rows,$per_page,$PAGES_PER_PAGE,$request_url=""){ ## <-- very cool app :-)
	#function ToHtml(total_rows){
		global $page;
		$request_url=($request_url!="")  ? $request_url : $_SERVER['REQUEST_URI'] ;
		$paginationCode = '';
		if ($per_page) {
			#$page = (!isset($_GET['page']))? 1 : $_GET['page'];			
			$prev = ($page - 1);
			$next = ($page + 1);
			$max_records = $per_page;
			$from = ($page - 1) * $max_records;
			$total_result = $total_rows;
			$total_pages = ceil($total_result / $max_records);
			$ppp = $PAGES_PER_PAGE;
			if ($total_pages > 1 && $ppp && $ppp < $total_pages) {
				if ($page > $ppp) {
					$first_page = $page;
				} else {
					$first_page = 1;
				}
				$last_page = $ppp + $first_page;
				if ($last_page > $total_pages) {
					$last_page = $total_pages;
					$first_page = $last_page - $ppp;
				}
			} else {
				$first_page = 1;
				$last_page = $total_pages;
			}
			$prev_shown = false;
			$next_shown = false;
			$generic_uri = addToGetString($request_url, "page=PAGEREPLACE");
			$extra_data = "";
			if ($pass_key) {
				foreach ($pass_key as $key => $value) {
					if (is_array($value)) {
						foreach ($value as $getValue) {
							$extra_data .= "&" . urlencode($key) . "=" . $getValue;
						}
					} else {
						$generic_uri = addToGetString($generic_uri, "$key=$value");
					}
				}
				$generic_uri .= $extra_data;
			}
			if ($first_page > 1) {
				$prev_pages = $page - $ppp;
				$prev_shown = true;
			}
			if ($last_page != $total_pages) {
				$next_pages = $page + $ppp;
				$next_shown = true;
			}
			
			
			$min = ($page - 1) * $per_page + 1;
			$max = min($page * $per_page, $total_rows);
			$paginationCode .= "<div class=\"page-showing\">Showing comments $min to $max of {$total_rows} ($total_pages pages total).</div>";
			
			$paginationJson['showing']['min']=			$min;
			$paginationJson['showing']['max']=			$max;
			$paginationJson['showing']['totalRows']=	$total_rows;
			$paginationJson['showing']['totalPages']=	$total_pages;
			
			if(($total_pages > 1) && ($page > 1)) {
				$uri = str_replace('PAGEREPLACE', $prev, $generic_uri);
				$paginationCode .= '<a class="page-prev" href="'.$uri.'">&laquo; Previous&nbsp;</a>';
				$paginationJson['pages']['prev']=				$uri;
			}
			if ($total_pages > 1) {
				for($i = $first_page; $i <= $last_page; $i++) {
					if(($page) == $i) {
						$paginationCode .= ' <span class="page-num">&nbsp;'.$i.'&nbsp;</span> ';
						$paginationJson['pages'][$i]=$i;
					} else {
						$uri = str_replace('PAGEREPLACE', $i, $generic_uri);
						$paginationCode .= ' <a href="'.$uri.'">'.$i.'</a> ';
						$paginationJson['pages'][$i]=$uri;
					}
				}
			}
			if(($total_pages > 1) && ($page < $total_pages)) {
				$uri = str_replace('PAGEREPLACE', $next, $generic_uri);
				$paginationCode .= '<a class="page-next" href="';
				$paginationCode .= $uri . '">Next &raquo;</a>';
				$paginationJson['pages']['next']=$uri;
			}
//			$paginationCode .= "<br /><br />";
			if ($prev_shown) {
				$uri = str_replace('PAGEREPLACE', '1', $generic_uri);
				$paginationCode .= '<a class="page-first" href="'.$uri.'">First Page</a>';
				$paginationJson['pages']['firstpage']=$uri;
				$uri = str_replace('PAGEREPLACE', $prev_pages, $generic_uri);
				$paginationCode .= '<a class="page-prev" href="'.$uri.'">Previous. '.$ppp.' Pages</a>';
				$paginationJson['pages']['previouspages']['uri']=$uri;
				$paginationJson['pages']['previouspages']['pages']=$ppp;
			}
			if ($next_shown) {
				if ($prev_shown) $paginationCode .= '&nbsp;|&nbsp;';
				$uri = str_replace('PAGEREPLACE', $next_pages, $generic_uri);
				$paginationCode .= '<a class="page-next" href="'.$uri.'">Next '.$ppp.' &raquo;</a> ';
				$paginationJson['pages']['nextpages']['uri']=$uri;
				$paginationJson['pages']['nextpages']['pages']=$ppp;				
				$uri = str_replace('PAGEREPLACE', "$total_pages", $generic_uri);
				$paginationCode .= '<a class="page-last" href="'.$uri.'">Last Page</a>';
				$paginationJson['pages']['lastpage']=$uri;
			}
//			if ($prev_shown || $next_shown)	$paginationCode .= "<br><br>";
		}
		$html .= $paginationCode;
		$json=new Services_JSON;
		$pgJson=$json->encode($paginationJson);
		global $pgJson;
		
		return $html; 
	}
function jsonPagination ($total_rows,$per_page,$PAGES_PER_PAGE,$request_url=""){ 
	#function ToHtml(total_rows){
		global $page;
		$request_url=($request_url!="")  ? $request_url : $_SERVER['REQUEST_URI'] ;
		$paginationCode = '';
		if ($per_page) {
			#$page = (!isset($_GET['page']))? 1 : $_GET['page'];			
			$prev = ($page - 1);
			$next = ($page + 1);
			$max_records = $per_page;
			$from = ($page - 1) * $max_records;
			$total_result = $total_rows;
			$total_pages = ceil($total_result / $max_records);
			$ppp = $PAGES_PER_PAGE;
			if ($total_pages > 1 && $ppp && $ppp < $total_pages) {
				if ($page > $ppp) {
					$first_page = $page;
				} else {
					$first_page = 1;
				}
				$last_page = $ppp + $first_page;
				if ($last_page > $total_pages) {
					$last_page = $total_pages;
					$first_page = $last_page - $ppp;
				}
			} else {
				$first_page = 1;
				$last_page = $total_pages;
			}
			$prev_shown = false;
			$next_shown = false;
			$generic_uri = addToGetString($request_url, "page=PAGEREPLACE");
			$extra_data = "";
			if ($pass_key) {
				foreach ($pass_key as $key => $value) {
					if (is_array($value)) {
						foreach ($value as $getValue) {
							$extra_data .= "&" . urlencode($key) . "=" . $getValue;
						}
					} else {
						$generic_uri = addToGetString($generic_uri, "$key=$value");
					}
				}
				$generic_uri .= $extra_data;
			}
			if ($first_page > 1) {
				$prev_pages = $page - $ppp;
				$prev_shown = true;
			}
			if ($last_page != $total_pages) {
				$next_pages = $page + $ppp;
				$next_shown = true;
			}
			
			
			$min = ($page - 1) * $per_page + 1;
			$max = min($page * $per_page, $total_rows);
			$paginationCode .= "<div class=\"page-showing\">Showing comments $min to $max of {$total_rows} ($total_pages pages total).</div>";
			
			$paginationJson['showing']['min']=			$min;
			$paginationJson['showing']['max']=			$max;
			$paginationJson['showing']['totalRows']=	$total_rows;
			$paginationJson['showing']['totalPages']=	$total_pages;
			
			if(($total_pages > 1) && ($page > 1)) {
				$uri = str_replace('PAGEREPLACE', $prev, $generic_uri);
				$paginationCode .= '<a class="page-prev" href="'.$uri.'">&laquo; Previous&nbsp;</a>';
				$paginationJson['pages']['prev']=				$uri;
			}
			if ($total_pages > 1) {
				for($i = $first_page; $i <= $last_page; $i++) {
					if(($page) == $i) {
						$paginationCode .= ' <span class="page-num">&nbsp;'.$i.'&nbsp;</span> ';
						$paginationJson['pages'][$i]=$i;
					} else {
						$uri = str_replace('PAGEREPLACE', $i, $generic_uri);
						$paginationCode .= ' <a href="'.$uri.'">'.$i.'</a> ';
						$paginationJson['pages'][$i]=$uri;
					}
				}
			}
			if(($total_pages > 1) && ($page < $total_pages)) {
				$uri = str_replace('PAGEREPLACE', $next, $generic_uri);
				$paginationCode .= '<a class="page-next" href="';
				$paginationCode .= $uri . '">Next &raquo;</a>';
				$paginationJson['pages']['next']=$uri;
			}
//			$paginationCode .= "<br /><br />";
			if ($prev_shown) {
				$uri = str_replace('PAGEREPLACE', '1', $generic_uri);
				$paginationCode .= '<a class="page-first" href="'.$uri.'">First Page</a>';
				$paginationJson['pages']['firstpage']=$uri;
				$uri = str_replace('PAGEREPLACE', $prev_pages, $generic_uri);
				$paginationCode .= '<a class="page-prev" href="'.$uri.'">Previous. '.$ppp.' Pages</a>';
				$paginationJson['pages']['previouspages']['uri']=$uri;
				$paginationJson['pages']['previouspages']['pages']=$ppp;
			}
			if ($next_shown) {
				if ($prev_shown) $paginationCode .= '&nbsp;|&nbsp;';
				$uri = str_replace('PAGEREPLACE', $next_pages, $generic_uri);
				$paginationCode .= '<a class="page-next" href="'.$uri.'">Next '.$ppp.' &raquo;</a> ';
				$paginationJson['pages']['nextpages']['uri']=$uri;
				$paginationJson['pages']['nextpages']['pages']=$ppp;				
				$uri = str_replace('PAGEREPLACE', "$total_pages", $generic_uri);
				$paginationCode .= '<a class="page-last" href="'.$uri.'">Last Page</a>';
				$paginationJson['pages']['lastpage']=$uri;
			}
//			if ($prev_shown || $next_shown)	$paginationCode .= "<br><br>";
		}
		$html .= $paginationCode;
		$json=new Services_JSON;
		$pgJson=$json->encode($paginationJson);
		#global $pgJson;
		
		return $pgJson; 
	}
	
if (!function_exists('getHtml')){
	function getHtml($url=""){
		if ($url!=""){
		  $curl = curl_init();
		
		  // Setup headers - I used the same headers from Firefox version 2.0.0.6
		  // below was split up because php.net said the line was too long. :/
		  $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
		  $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
		  $header[] = "Cache-Control: max-age=0";
		  $header[] = "Connection: keep-alive";
		  $header[] = "Keep-Alive: 300";
		  $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		  $header[] = "Accept-Language: en-us,en;q=0.5";
		  $header[] = "Pragma: "; // browsers keep this blank.
		
		  curl_setopt($curl, CURLOPT_URL, $url);
		  curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
		  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		  curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com');
		  curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
		  curl_setopt($curl, CURLOPT_AUTOREFERER, true);
		  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		
		  $html = curl_exec($curl); // execute the curl command
		  curl_close($curl); // close the connection
		
		  return $html; // and finally, return $html		
		}
	}	
}

function showFunctions(){
	$included_files = get_included_files();
	foreach ($included_files as $included_file){
			if ($included_file==__FILE__) continue;		
		$html=file_get_contents($included_file);
		$key=basename($included_file);
		$pattern='/function (.+?)\n/s';
		preg_match_all($pattern,$html,$matches);
		#print__r($matches);
		$functionList[$key]=$matches[1];
		array_unshift($functionList[$key],$included_file);
	}
	return $functionList;
}

function showConstants(){
	$constants=get_defined_constants(true);
	return ($constants['user']);	
}

function underConstruction(){
	$dir=GATEWAY_IMAGES_DIR.'underconstruction/';
	$url=GATEWAY_IMAGES_URL.'underconstruction/';
	$images=array_diff(scandir($dir),array('.','..'));
	$key=array_rand($images,1);
	#print__r($url);
	#print__r($key);
	return "<img src='{$url}{$images[$key]}'><br>As of ".date('D M j,Y').'<br>';
}

function makeLink($url="",$text="",$target="",$attribute="",$alt=""){
	if ($url!=""){
		$text=($text!="")  ? $text : $url ;
		$target=($target!="")  ? "target='$target'" : "" ;
		$alt=($alt!="")  ? "alt='{$alt}' title='{$alt}' " : "" ;
		return "<a href='{$url}' $target $attribute $alt >$text</a>"; 
	}
}


if (!function_exists('__autoload')){
	function __autoload($class="")
	{
		if(file_exists(AUTOLOAD_CLASS_DIR.$class.'.php')){
			require(AUTOLOAD_CLASS_DIR.$class.'.php');
		} 
	}
}

function makeDatePath($date){
	$path=str_replace('-','/',$date);
	$path=rtrim($path,'/');
	return $path.'/';
}

?>