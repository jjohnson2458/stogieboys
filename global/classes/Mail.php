<?php

class Mail
{
	function __construct($bool = true){
		$PHPMailerFile = ZEN_CLASSES_DIR . 'class.phpmailer.php';

		if( !class_exists('PHPMailer') && file_exists($PHPMailerFile) ){			
			include_once($PHPMailerFile);
		} else {
			//PR::r(get_class_methods('PHPMailer'));
			//die;
		}
		//die($PHPMailerFile);		
		$this->obj = new PHPMailer();
		$this->obj->isHtml($bool);	
	}	
	
	function isHTML($bool = true){
		$this->obj->isHtml($bool);	
	}
	
	function To($email = "", $name = ""){
		if( $email != "" && Validate::email($email) ){
			$this->obj->AddAddress($email, $name);	
		}
	}
	
	function From($email = "", $name = ""){
		if( $email != "" ){
			$this->obj->From 	= ($email);	
			$this->obj->FromName = ($name);
		}			
	}

	
	function Bcc($email = "", $name = ""){
		if( $email != "" && Validate::email($email) ){
			$this->obj->AddBCC($email, $name);	
		}		
	}
	
	function CC($email = "", $name = ""){
		if( $email != "" && Validate::email($email) ){
			$this->obj->AddCC($email, $name);	
		}		
	}
	
	
	function Body($text = ""){
		$this->obj->Body = $text;	
	}
	
	function Message($text = ""){
		$this->obj->Body = $text;	
	}
	
	function Subject($subject, $timestamp = false){
		$this->obj->Subject = ($timestamp)  ? $subject. Date::now() : $subject ;	
	}
	
	function Attach($filename, $name, $encoding = "base64"){
		$this->obj->AddAttachment($filename, $name, $encoding); 	
	}
	
	function Attachment($filename, $name, $encoding = "base64"){
		$this->obj->AddAttachment($filename, $name, $encoding); 	
	}	
	
	
	function Send(){
		$this->obj->Send();			
	}
}