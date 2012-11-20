<?php

if(!defined('DEFAULT_COOKIE_TIME')) {
	define('DEFAULT_COOKIE_TIME',time()+86400);
}
class Cookie 
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
   *
   *
   *
   */

  CONST self = __CLASS__;
  
  /**
   *
   */
   function  Set($name = "", $value = "", $time = "", $global = TRUE, $secure = FALSE, $httpoly = FALSE)
   {
		if($name != "" && $value != ""){
			$domain = ($global)  ? '/' : '' ;
			$cookieTime = ($time != "")  ? $time : DEFAULT_COOKIE_TIME ;
				if(!headers_sent()){
					setcookie($name, $value, $cookieTime, $domain, $secure, $httponly);
				}
		}
   }
  
  /** 
   *
   */
   function  SetAll($array = array())
   {
		foreach((array)$array as $name => $value){
			self::Set($name,$value);
		}
   }
  
  /** 
   *
   */
   function  Kill($name = "")
   {
		if($name != "" && isset($_COOKIE[$name]) ){			
			self::Set($name, '', time()-3600);

		}
   }
    
  /** 
   *
   */
   function  KillAll($cookies)
   {
		foreach((array)$cookies as $cookie){
			if(isset($_COOKIE[$cookie])) self::Kill($cookie);
		}
   }
  
  /** 
   *
   */
   function  isLoggedIn()
   {
		
   }
  
  /** 
   *
   */
   function  __method5()
   {

   }
  
  /** 
   *
   */
   function  __method6()
   {

   }
  
  /** 
   *
   */
   function  __method7()
   {

   }
  
  /** 
   *
   */

   function  __destruct()
   {

   }
}

