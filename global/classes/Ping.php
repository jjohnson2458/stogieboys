<?php

/**
* @class Ping
*  built solely for testing purposes.
* this file is NOT meant to send any data broswer:
* @package global
*/

class Ping
{
	function __construct($email = "")
	{
		if ($email != "") {
			$this->email = $email;
		} else {
			$email = JJOHNSON;
		}
	}

	function me($subject = 'ping: ' . RIGHTNOW)
	{
		$data = array_reverse(debug_backtrace());
		PR::e($data, $subject, $this->email;
	}
}