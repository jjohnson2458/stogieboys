<?php

class Message
{
	     // this implements the 'singleton' design pattern.
		function getInstance (){ 
			static $instance;
			if (!isset($instance)) {
				$c = __CLASS__;
				$instance = new $c;
			}
			return $instance;
    	} 
		
		public static function Error($message = ""){
			$obj = self::getInstance();
			if(!session_id()){
				session_start();
			}
			//$this->set_error($message);
			$_SESSION['messages']['errors'][] = $message;
			$this->errors = true;
		}
		
		public static function Display($flush = true){
			$obj = self::getInstance();
			if(self::error){
				foreach((array)$_SESSION['messages']['errors'] as $error){
					PR::l($error);
				}
			}
		}
}