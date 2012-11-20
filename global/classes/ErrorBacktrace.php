<?php
//Shamelessly copied from http://www.codingforums.com/showthread.php?p=777595 with some modifications

class ErrorBacktrace {   
	public static function process_error_backtrace($errno, $errstr, $errfile, $errline, $errcontext) {
		if(!(error_reporting() & $errno)) {
			return;
		}
		
		switch($errno) {
			case E_WARNING:
			case E_USER_WARNING:
			case E_STRICT:
			case E_NOTICE:
			case E_USER_NOTICE:
				$type = 'warning';
				$fatal = false;
				break;
			default:
				$type = 'fatal error';
				$fatal = false;                                
				break;
		}
		
		if(php_sapi_name() == 'cli') {
			echo "Backtrace from $type '$errstr' at $errfile $errline:\n";
			foreach(array_reverse(debug_backtrace()) as $item) {
				echo '  ' . (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . " calling {$item['function']}()\n";
			}
		} else {
			self::backtrace_to_errorlog("Backtrace from $type '$errstr' at $errfile $errline:");                     
		}
		
		if($fatal) {
			exit(1);
		}
		
		return false;
	}
	
	public static function backtrace_to_errorlog($top_message) {
		$request_uri = '';
		if (isset($_SERVER)) {
			$request_uri = ' at '.$_SERVER['REQUEST_URI'];
		}
		
                $email_messages = array();
                $email_messages[] = '===================BEGIN BACKTRACE===================';
                
		error_log('===================BEGIN BACKTRACE===================');
		error_log($top_message . $request_uri);
                $email_messages[] = $top_message . $request_uri;
                
		
		foreach(array_reverse(debug_backtrace()) as $item) {
			error_log((isset($item['file']) ? $item['file'] : '<unknown file>') . ' (' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ") calling {$item['function']}()");
                        $email_messages[] = (isset($item['file']) ? $item['file'] : '<unknown file>') . ' (' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ") calling {$item['function']}()";
		}
		
		error_log('====================END BACKTRACE====================');
                $email_messages[] = '====================END BACKTRACE====================';
                
                $data['message'] = join("\n", $email_messages);
                $data['file'] = $top_message;
                $data['type'] = 3;
                $data['line'] = "";
                //self::process_shutdown($data);
	}
	
	public static function error_to_errorlog($error) { 
		$request_uri = '';
		if( isset($_SERVER['REQUEST_URI']) ){
			$request_uri = ' at '.$_SERVER['REQUEST_URI'];
		}
		
		error_log('===================BEGIN BACKTRACE===================');
		error_log("Exception" . $request_uri);
		/*
		if ($error instanceof DatabaseException) {
			error_log('Query: ' . $error->getStatement());
		}
		*/
		/*
		if( isset($error->xdebug_message) ){
			$lines = explode("\n", $error->xdebug_message);
			foreach( $lines as $line ){
				error_log($line);
			}
		}
		else {
			error_log($error->getMessage());
		}
		
		*/
		//if($error isntanceof Execption){
		//	error_log($error->getMessage());
		//}
		error_log("");
		error_log("Error caught by:");
		foreach(array_reverse(debug_backtrace()) as $item) {
			error_log((isset($item['file']) ? $item['file'] : '<unknown file>') . ' (' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ") calling {$item['function']}()");
		}
		
		error_log('====================END BACKTRACE====================');
	}
	
	public static function get_backtrace(){
		$request_uri = '';
		if( isset($_SERVER['REQUEST_URI']) ){
			$request_uri = ' at '.$_SERVER['REQUEST_URI'];
		}
		$bt_lines = array();
		$bt_lines[] = $request_uri;
		$bt_lines[] = '===================BEGIN BACKTRACE===================';
		$bt_lines[] = ("Error caught by:");
		foreach(array_reverse(debug_backtrace()) as $item) {
			$bt_lines[] = ((isset($item['file']) ? $item['file'] : '<unknown file>') . ' (' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ") calling {$item['function']}()");
		}
		
	$bt_lines[] = ('====================END BACKTRACE====================');
	$lines[] = join("\n", $bt_lines);
				
	}
        
        /**
         * function process_shutdown
         * send email to dev team defined by Variable::get('email_team_on_error'')
         * this method operates only after shutdown
         * NOTE: relative path not available for this method
         * see {@link http://us3.php.net/manual/en/function.register-shutdown-function.php}
         * @uses {@link class.phpmailer}
         * @param array $data - to be used to force email to dev team upon direct request (ErrorBacktrace::process_shutdown)
         *  keys for $data(strings): 'type','file','message','line'
         * @param array Variables::get('email_team_on_error')
         * @param bool  Varaibles::get('send_error_mail')
         * @return null
         * 
         */
        public static function process_shutdown($data = false){ 
		$errors_to_ignore = array('Notice', 'Strict', 'Deprecation');
		
           $error = array();
                if( $data && is_array($data) ){
                   $error['type']       = ($data['type'] != "")     ? $data['type']     : 1024 ;
                   $error['file']       = ($data['file'] != "")     ? $data['file']     : 'No file given' ;
                   $error['message']    = ($data['message'] != "")  ? $data['message']  : "" ;
                   $error['line']       = ($data['line'] != "")     ? $data['line']     : "" ;
               } else {
                   $error = error_get_last(); 
               }
               
               if ($error != NULL && php_sapi_name() != 'cli'){
                   // create indexed array for type of error message:
                   // NOTE: most of these will NEVER be used. they are listed here as a reference.
                   $error_types         = array();
                   $error_types[1]      = 'Error (runtime)';
                   $error_types[2]      = 'Warning';
                   $error_types[3]      = 'Backtrace';
                   $error_types[4]      = 'Parse Error';
                   $error_types[8]      = 'Notice';
                   $error_types[16]     = 'PHP Core Error';
                   $error_types[32]     = 'PHP Core Warning';
                   $error_types[64]     = 'PHP Compiled Error';
                   $error_types[128]    = 'PHP Compiled Warning';
                   $error_types[256]    = 'User Error';
                   $error_types[512]    = 'User Warning';
                   $error_types[1024]   = 'User Notice';
                   $error_types[2048]   = 'Strict';
                   $error_types[4096]   = 'Fatal Error';
                   $error_types[8192]   = 'Deprecation';
                   $error_types[16384]  = 'Deprecation(user)';
                   $error_types[30719]  = 'Error(all)';
				   // check for ignored error types ===========================
				   if(in_array($error_types[$error['type']], $errors_to_ignore)){
						exit();   
					}
				   
                   
                   // create message ===========================================
                   $lines  = array();
                   $lines[]= 'Date: ' . date('Y-m-d');
                   $lines[]= 'Error Type: ' . $error_types[$error['type']];
                   $lines[]= 'Error Message: ' . $error['message'] .  ' at line ' . $error['line'];
                   $lines[]= 'File: ' . $error['file'];
                   
                    //provides a unique error message id each day for a given error message
                   $error_message_id = md5( join(',', $lines) );
                   
                   $lines[]= 'This error occured at ' . date('H:i:s'); 
                   $lines[]= 'Error Message Id: ' . $error_message_id;
// =========================== GET BACKTRACE INFO ===============================================
		$request_uri = '';
		if( isset($_SERVER['REQUEST_URI']) ){
			$request_uri = ' at '.$_SERVER['REQUEST_URI'];
		}
		$bt_lines = array();
		$bt_lines[] = $request_uri;
		$bt_lines[] = '===================BEGIN BACKTRACE===================';
		$bt_lines[] = ("Error caught by:");
		foreach(array_reverse(debug_backtrace()) as $item) {
			$bt_lines[] = ((isset($item['file']) ? $item['file'] : '<unknown file>') . ' (' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ") calling {$item['function']}()");
		}
		
	$bt_lines[] = ('====================END BACKTRACE====================');
	$lines[] = join("\n\r", $bt_lines);


// ===============================================================================================				   
				   
				  
                  
                   $max_emails = 10;
                   $emails_sent = self::get_error_mail_status($error_message_id);
                   $lines[]= 'Previous emails sent: ' . $emails_sent;
                   if($emails_sent == $max_emails){                       
                       $lines[]= 'NOTE: This message has reached its send limit and no further notifications of this error will be sent today.';
                   }
                   //===========================================================
                   
                         
                   
                   // send email
                   
                   // $addresses = Global::get('email_team_on_error', array() ) ; 
				   $addresses = array('jjohnson@stogieboys.com') ; 
                   
                       if($addresses && $emails_sent <= $max_emails ) {
                           
                           // NOTE: per Manual:Working directory of the script can change inside the shutdown function under some web servers, e.g. Apache.
                           // So we manually find the include path below
                           // see: http://us3.php.net/manual/en/function.register-shutdown-function.php
                           //$include_path = str_replace('public/htdocs', 'include/phpmailer/',$_SERVER['DOCUMENT_ROOT']);
   							//$include_path = '/home/mkephart/public_html/includes/classes/';
                           //===================================================================
						   $include_path = '/home/mkephart/public_html/includes/classes/';
						   if(!class_exists('PHPMailer')){
						   	require_once($include_path . 'class.phpmailer.php');
							
                           }
                           $mail = new PHPMailer(); 
                           //suppresing output in case an invalid email address is submitted
                           ob_start();
                               foreach((array)$addresses as $address){
                                  $mail->AddAddress($address) ;  
                                }
                           $mail->From = 'error_report@stogieboys.com';
                           $mail->FromName = 'Stogie Errors';
                           $mail->Subject = 'PHP Error on '. $_SERVER['HTTP_HOST'] .': '. $error_types[$error['type']];
                           $mail->Body = join("\n\n",$lines);
                           $mail->Send();
                           $mail_message = ob_end_clean();
                           self::set_error_mail_status($error_message_id);
                       }

               }
                               
            
        }
        
        /**
         * get_error_mail_status (dev)
         * checks to see how many emails message have been sent per email_message_id 
         * and if a maximum mail message notice has been sent
         * @param string $error_message_id
         * @param string $filename - name of the file to the tmp folder; defalt to reflexions_framework_error.txt
         * @param int $max_emails - email limit
         * @return bool true if  maximum mail message notice has been sent
         */
        
        public static function get_error_mail_status($error_message_id = "", $filename = 'stogie_error.txt'){
            if($error_message_id != ""){
                $error_filename = '/tmp/' . $filename  ;
                if(file_exists($error_filename)){
                    $error_messages = array_map('trim',file($error_filename));
                    $email_messages = array_keys($error_messages, $error_message_id);
                    return count($email_messages);
                } else {
                    return 0;                    
                }
            }     
            
            
        }
        
        
        /**
         * set_error_mail_status
         * writes the error message id to a file
         * @param string $error_message_id 
         * @param string $filename - name of the file to the tmp folder; defalt to reflexions_framework_error.txt
         * 
         */
        
        public static function set_error_mail_status($error_message_id = "", $filename = 'stogie_error.txt'){ 
            if($error_message_id != ""){
                $error_filename = '/tmp/' . $filename  ;
                $cmd = "echo {$error_message_id} >> {$error_filename}" ;
                $result = shell_exec($cmd);
            }            
        }
}

//set_error_handler(array('ErrorBacktrace', 'process_error_backtrace'));

// added to send email upon php error
register_shutdown_function(array('ErrorBacktrace', 'process_shutdown'));
