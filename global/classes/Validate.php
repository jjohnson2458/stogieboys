<?php

class Validate 
{

  CONST self=__CLASS__;
  
  /* 
   *
   */
   function  Validate()
   {
		return;
   }
  
  /* 
   *
   */
   function  email($email)
   {
	  # if ($email=="" || !$email) return false;
	  if (strpos($email,'facebook.com')) return false;
	  // First, we check that there's one @ symbol, 
	  // and that the lengths are right.  
	  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
		// Email invalid because wrong number of characters 
		// in one section or wrong number of @ symbols.
		return false;
	  }
	  // Split it into sections to make life easier
	  $email_array = explode("@", $email);
	  $local_array = explode(".", $email_array[0]);
	  for ($i = 0; $i < sizeof($local_array); $i++) {
		if
	(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
	?'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
	$local_array[$i])) {
		  return false;
		}
	  }
	  // Check if domain is IP. If not, 
	  // it should be valid domain name
	  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
		  if
	(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
	?([A-Za-z0-9]+))$",
	$domain_array[$i])) {
			return false;
		  }
		}
	  }
	  return true;
   }
  
  /* 
   *
   */
   function  phone($text,$return=false)
   {
	$number = preg_replace("/[^0-9]/","",$text);
	$bool=false;
		if (strlen($number) > 0){
			switch (strlen($number)) {
				case 7 :
					$formattedPhone= substr($number, 0,3).'-'.substr($number,3,4);
					$bool=true;
				break;
				
				case 8 :
				case 9 :
					$formattedPhone= substr($number, 0,3).'-'.substr($number,3,4). ' ext. '.substr($number,7);
					$bool=true;
				break;
				
				case 10:
					$formattedPhone= '('.substr($number, 0,3).') '.substr($number, 3,3).'-'.substr($number,6,4);
					$bool=true;
				break;
				
				default :
					if (strlen($number) < 7) return $phone;
				$formattedPhone= '('.substr($number, 0,3).') '.substr($number, 3,4).'-'.substr($number,6,4).' ext. '.substr($number,10);
				$bool=true;
		
			}
		}
		return ($return)  ? $formattedPhone : $bool ;
   }
    
  /* 
   *
   */
   function  alpha($text,$return=false)
   {
		$bool=(ctype_alpha($text))  ? true : false ;
		return ($return)  ? $text : $bool ;
   }
  
  /* 
   *
   */
   function  num($text,$return=false)
   {
		$bool=(ctype_digit($text))  ? true : false ;
		return ($return)  ? $text : $bool ;
   }
   
  
  /* 
   *
   */
   function  maxLength($text,$num=0)
   {
		return(strlen($text) > intval($num))  ? true : false ;
   }
   
    
  /* 
   *
   */
   function  wordCount($text)
   {

   }
   
    
  /* 
   *
   */
   function  tags()
   {

   }
   
    
  /* 
   *
   */
   function  numeric($num)
   {
		return (is_numeric($num))  ? true : false ;
   }
   
    
    
  /* 
   *
   */
   function  file($filename="")
   {
		return(file_exists($filename) && !is_dir($filename))  ? true : false ;
   }
   
       
   function  __destruct()
   {

   }
}