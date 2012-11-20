<?php

/*$time_start = microtime(true);

$time_end = microtime(true);
$time = $time_end - $time_start;
print__r($time);*/

class Stopwatch {
	
	function __construct($decimal=7){
		$this->time_start = microtime(true);
		$this->Timeline=	array();
		$this->decimalplace=($decimal!="")  ? $decimal : 7 ;
	}
	
	function SetDecimalPlace($num){
		$this->decimalplace=$num;
	}
	
	function Check($name=""){
		if ($name!=""){
			$this->time_end = microtime(true);
			$this->timecheck = $this->time_end - $this->time_start;	
			$this->rawtime[$name]=	$this->timecheck; 	
			$this->Timeline[$name]=round($this->timecheck,$this->decimalplace). ' secs; '.round(array_sum($this->rawtime),$this->decimalplace).' secs total. '.number_format(memory_get_usage(),0,',',',').' bytes used.';
			$this->time_start = microtime(true);
		} else {
			$this->time_end = microtime(true);
			$this->timecheck = $this->time_end - $this->time_start;			
			$this->Timeline[]=$this->timecheck;
			$this->time_start = microtime(true);		
		}
	}
	
	function Stop(){
		$this->time_end = microtime(true);
	}
	
	function Display(){
		print__r($this->Timeline);
	}
	
	function SendReport($subject="",$email='email4johnson@gmail.com'){
		ob_start();
		print__r($this->Timeline);
		$message=ob_get_clean();
		mail($email,$subject.date('Y-m-d H:i:s'),$message,mailHeader());
	}
}
