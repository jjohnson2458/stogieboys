<?php

class Http  
{
   function  Http()
   {

    }

   public static function link($url="",$text="",$attribute="",$alt="",$target="")
   {
	if ($url!=""){
		$text=($text!="")  ? $text : $url ;
		$target=($target!="")  ? "target='$target'" : "" ;
		$alt=($alt!="")  ? "alt='{$alt}' title='{$alt}' " : "" ;
		return "<a href='{$url}' $target $attribute $alt >$text</a>"; 
	}
   }
   
   function glink($url="",$getVars,$text="",$attribute="",$alt="",$target="") 
   {
	if ($url!=""){
		$text=($text!="")  ? $text : $url ;
		$target=($target!="")  ? "target='$target'" : "" ;
		$alt=($alt!="")  ? "alt='{$alt}' title='{$alt}' " : "" ;
		return "<a href='".Http::addGet($url,$getVars)."' $target $attribute $alt >$text</a>"; 
	}   		
   }

   /* returns all html from a remote url */
   function  load($url="")
   {
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

   /* strtolower */
   function  low()
   {
		
   }

   /* strtoupper */
   function  up()
   {
		
   }

   /*str_replace */
   function  replace()
   {
		
   }

   /*removes all but alpha-numeric */
   function  clean($text)
   {
		$text=str_replace(chr(32),'^',$text);
		$text= preg_replace("/[^a-zA-z0-9@\.\-\_]/",chr(32), $text);
		$text=str_replace('^',chr(32),$text);	
		return $text;		
   }

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
    function addGet($old_url, $new_arguments)
	{
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
     * Takes a url with get string arguments, and an array of arguments ((argument names)) to
     * delete, removes those arguments and their values from the getstring, and then returns
     * the new get string.
     */
    function removeGet($old_url, $arguments_to_delete){
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
    function stripGet($old_url){
        if(strpos($old_url, '?') !== false){
            $new_url = substr($old_url, 0, strpos($old_url, '?'));
        } else { $new_url = $old_url; }
        return $new_url;
    }
	
	
   /*returns true/false if value is a sting */
   function  isString($string="")
   {
		return(is_string($string) && strlen(trim($string)) > 0 )  ? true : false ;
   }

   /*comment */
   function  redirect($url)
   {
       if(QUICK_REDIRECT !== false){
            @header( "Location: {$url}" );
            print "<script type='text/javascript'>window.location = '{$url}';</script>";
        }
        print "<span style='font:12px verdana'>";
        print "Please click here to continue: <a href='{$url}'>Continue!</a>\n";
		print "</span>";
        die;		
   }
   
   /*redirects parent window to given location */
   function  redirectParent($url)
   {
       if(QUICK_REDIRECT !== false){
            @header( "Location: {$url}" );
            print "<script type='text/javascript'>window.parent.location = '{$url}';</script>";
        }
        print "<span style='font:12px verdana'>";
        print "Please click here to continue: <a href='{$url}'>Continue!</a>\n";
		print "</span>";
        die;		
   }   

   /*returns regex pattern from an unlimited number of inputs */
   function  regex()
   {
		$args=func_get_args();	
		$specialCharacters=str_split('~`!@#$%^&*()_-+=\][{}¦:;"\'?/>.<,');
		foreach($specialCharacters as  $specialCharacter){		
			$regexReplacements[]=chr(92).$specialCharacter;
		}
	
		foreach ((array) $args as $arg){
			$regex[]=str_replace($specialCharacters,$regexReplacements,$arg);
		}
		return "/".join('(.+?)',$regex)."/s";
		## ~`!@#$%^&*()_-+=\][{}¦:;"'?/>.<, 		
   }

   /*returns an array of matches based on regex($start,$end) */
   function  match($subject,$start,$end)
   {
		$matches=array();
		$pattern=self::regex($start,$end);
		preg_match_all($pattern,$subject,$matches);
		return $matches[0][1];
   }

   /*returns a hash value of the given array or value  */
   function  hash($array)
	{		
	   return(is_array($array)) ? md5(join(',',$array))  : md5($array)  ;	
	}

   /*comment */
   function  bind($array,$join="",$useQuotes=false)
   {
		$params=array();
		$quotes=(useQuotes)  ? "'" : '' ;
		foreach ((array)$array as $key=>$value){
			$params[]="$key={$quotes}$value{$quotes}";		
		}
		return ' '.AR::join($params,$join);		
   }

   /*comment */
   function  method8()
   {
		
   }

   /*comment */
   function  method9()
   {
		
   }

   /*comment */
   function  method10()
   {
		
   }

   /*comment */
   function  method11()
   {
		
   }

   /*comment */
   function  method12()
   {
		
   }

   /*comment */
   function  method13()
   {
		
   }

   /*comment */
   function  method14()
   {
		
   }



   function  __destruct()
   {

   }


}