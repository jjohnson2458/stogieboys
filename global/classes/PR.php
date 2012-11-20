<?

class PR
{
 
 	CONST self =__CLASS__;
    /**
     * HTML Formatting for a print_r.
     */
    public static function r(){
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
    
    
    /**
     * HTML Formatting for a print_r.
     */
    public static function d(){
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
                die(date('Y-m-d H:i:s'));
    }
	
	#function self::r($anything){return pretty_print_r($anything);}
    /**
     * Alerts by javascript the print_r.
     */
     public static function a($anything){
        $string = self::s($anything);
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
     public static function s($anything, $pretty=false){
        ob_start();
        if($pretty===true){ 
            self::r($anything); 
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
	 * NOTE: this version has been modified for the open cart classes
     */	
	  public static function e($anything, $subject = "", $email = 'jjohnson@stogieboys.com', $name = "Steven" ){
		 	
		global $cr;
		global $br;
		global $jj;
		#$message=$_SERVER[REQUEST_URI].$cr.$br;
		ob_start();
			echo $_SERVER['REQUEST_URI'] . "\n\r";
			echo $_SERVER['HTTP_USER_AGENT'] . "\n\r"; 
			self::r($anything);
		$message 	 = ob_get_clean();			
		$subjectline ="$subject " . date('Y-m-d H:i:s');
		$mail = new Mail();
		$mail->setSubject($subjectline);
		$mail->setFrom('debug@stogieboys.com');
		$mail->setSender(basename($_SERVER['SCRIPT_FILENAME']));
		$mail->setTo($email, $name);
		$mail->setText($message);
		$mail->Send();
		return $message;
	 }

public static function jj($anything, $subject = "", $email = 'jjohnson@stogieboys.com', $name = "J.J.")
{
	return self::e($anything, $subject, $email, $name);
}

/* outputs html into a given file in the data/errors folder*/
	 public static function f($anything, $filename = 'errors.txt'){
		ob_start();
		self::r($_SERVER['REQUEST_URI']);
		self::r(date('Y-m-d H:i:s'));
		self::r($anything);
		$message=ob_get_clean();
		$fh = @fopen($filename, 'w+');
		@fwrite($fh,$message);
		fclose($fh);
			
	}	
	
	/**
	 * print_r wrapped by an HTML comment.
	 */
	 public static function c($whatever) {
		print "\n<!--\n " . self::s($whatever) . "\n-->\n";
	}
	
	 public static function x($string){
		echo '<pre>';
		echo xmlentities($string);
		echo '</pre>';
	}
	
	 public static function print__v($name){
		$whatever=$GLOBALS[$name];
		self::r("$name: ".$whatever);
	}
	
    /**
     * Will print the $whatever on either the condition of DEBUG_PRINTING being
     * set to true, or the condition of DEBUG_PRINTING being set to the particular
     * $debug_key string passed into this function.
     */
     public static function debug_print($whatever, $debug_key=''){
        if(DEBUG_PRINTING === true || DEBUG_PRINTING === $debug_key){
            self::r($whatever);
    }   }
	 public static function l($text=""){
		if ($text!="") echo $text.'<br>';
	}	
	
	 public static function server(){
		return self::r($_SERVER);
	}
	
	 public static function request(){
		return self::r($_REQUEST);
	}
	 public static function post(){
		return self::r($_POST);
	}
	 public static function get(){
		return self::r($_GET);
	}	
	 public static function files(){
		return self::r($_FILES);
	}	
	 public static function cookie(){
		return self::r($_COOKIE);
	}		

	 public static function session(){
		return self::r($_SESSION);
	}	
	
	 public static function time(){
		return self::l(date('Y-m-d H:i:s'));
	}
	
	 public static function out($string)
	{
		echo(htmlentities($string,ENT_QUOTES,'UTF-8'));
	}
	
	 public static function red($data){
		$args = func_get_args();
		echo '<font color="ff0000">';
		self::r($args);
		echo '</font>';
	}
	
	public static function constants($type = 'user'){
		$c =get_defined_constants(true);
		self::r($c[$type]);
	}
	
	
	public static function functions($type = 'user'){
		$c = get_defined_functions();
		self::r($c[$type]);		
	}
	
	public static function classes(){
		self::r(get_declared_classes());
	}
	
	public static function methods($class = ""){
		if($class != ""){
			PR::r(get_class_methods($class));
		}
	}
	
	public static function blu($text){
		return Font::blu($text, true);
	}	
	
	public static function grn($text){
		return Font::grn($text, true);
	}		
	
	public static function brn($text){
		return Font::brn($text, true);
	}		
	
	public static function cyn($text){
		return Font::cyn($text, true);
	}		
	
	public static function org($text){
		return Font::org($text, true);
	}		
	
	public static function yel($text){
		return Font::yel($text, true);
	}		
	
	public static function highlight($text){
		return Font::highlight($text, true);
	}		
	
	
	function o($data, $clear = false){
		static $__debug;
		ob_start();
		PR::r($data) . HR;
		$__debug .= ob_get_clean();
		if ($clear) {
			$output = $__debug;
			$__debug = '';
			return $output;
		}
	}	
		
		
	
}
