<?php

class ZenMail extends Mail{
	
	var $text;
	var $html;
	var $asHtml;
	
	function __construct($asHtml = true){
		$this->type = ($asHtml)  ? 'HTML' : 'TEXT' ;
	}	
	
	function Text($text = ""){
		if($text != ""){
			$this->text = $text;
		}
	}
	
	function Html($html = ""){
		if($html != ""){
			$this->text = $html;
		}		
	}
	
	function set_type($type = ""){
			switch(strtoupper($type)){
				case 'HTML':
					$this->type = 'HTML';
				break;	
				
				case 'TEXT':
					$this->type = 'TEXT';
				break;					
			}
	}
	
	
}