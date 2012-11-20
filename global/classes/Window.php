<?php

class Window
{
	public static function Close($echo = true)
	{
		$string = "<script type='text/javascript'>window.close();</script>";
		if ($echo) {
			echo($string);
		} else {
			return ($echo);
		}
		
	}

	public static function CloseRefresh($echo = true){
		$string = "<script type='text/javascript'>window.opener.location.reload();window.close();</script>";
		if ($echo) {
			echo($string);
		} else {
			return ($echo);
		}
	}
}