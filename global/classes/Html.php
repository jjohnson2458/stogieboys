<?php

class Html 
{
   function  Html()
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

   /* places html tags of $tag around $string */
   public static function  tag($tag,$string="",$attribute="")
   {
		return "<$tag $attribute>$string</$tag>";
   }

   /* strtolower */
   public static function  low()
   {
		
   }

   /* strtoupper */
   public static function  up()
   {
		
   }

   /*str_replace */
   public static function  replace()
   {
		
   }

   /*removes all but alpha-numeric */
   public static function  clean($text)
   {
		$text=str_replace(chr(32),'^',$text);
		$text= preg_replace("/[^a-zA-z0-9@\.\-\_]/",chr(32), $text);
		$text=str_replace('^',chr(32),$text);	
		return $text;		
   }

     /* *** HTML Anchor public static functions ****************************************** *** */
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
    public static function addGet($old_url, $new_arguments)
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
  

   /*returns true/false if value is a sting */
   public static function  isString($string="")
   {
		return(is_string($string) && strlen(trim($string)) > 0 )  ? true : false ;
   }

   /*comment */
   public static function  redirect($url)
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

   /*returns regex pattern from an unlimited number of inputs */
   public static function  regex()
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
   public static function  match($subject,$start,$end)
   {
		$matches=array();
		$pattern=self::regex($start,$end);
		preg_match_all($pattern,$subject,$matches);
		return $matches[0][1];
   }

   /*returns a hash value of the given array or value  */
   public static function  hash($array)
   {
		return(is_array($array)) ? md5(join(',',$array))  : md5($array)  ;	
   }

   /*comment */
   public static function  Image($image,$attributes=array())
   {
		if (!array_key_exists('border',$attributes)) $attributes['border']=0;
		return "<img src='$image' ".AR::bind($attributes).">";
   }

   /*comment */
   public static function  label($label = "", $value = "", $marker = ":")
   {
		return $label . $marker . ' ' . $value;
   }

   /*comment */
   public static function  method9()
   {
		
   }

   /*comment */
   public static function  method10()
   {
		
   }

   /*comment */
   public static function  method11()
   {
		
   }

   /*comment */
   public static function  method12()
   {
		
   }

   /*comment */
   public static function  method13()
   {
		
   }

   /*comment */
   public static function  method14()
   {
		
   }



   function  __destruct()
   {

   }


}