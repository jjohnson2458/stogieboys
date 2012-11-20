<?php

$cr="\n";
$np="``x";
$br="<br>";
$sp=chr(32);
$jj="email4johnson@gmail.com";
$hr='<hr>';
$quote=chr(39);
$d_quote=chr(34);
$comma=",";
$cm=',';
$dot=".";
$at="@";
$bold="<b>";
$comma=",";
$cm=',';
$dot=".";
$at="@";
$bold="<b>";
$separator="&nbsp;|&nbsp;";
$current_dir=getcwd();
$Year=date('Y');
$copyright=chr(64)." $Year";

define('SPACE','&nbsp;');

define('SECOND', 1);
define('MINUTE', 60 * SECOND);
define('HOUR', 60 * MINUTE);
define('DAY', 24 * HOUR);
define('WEEK', 7 * DAY);
define('MONTH', 30 * DAY);
define('YEAR', 365 * DAY);

if(!defined('DATETIME')) define('DATETIME','Y-m-d H:i:s');
define('DATE','Y-m-d');
define('TIME','H:i:s');
define('TODAY',date(DATE));
define('NOW',date(TIME));
define('RIGHTNOW',date(DATETIME));


define('BR','<br>');
define('CR',"\n");
define('AT','@');
define('HR','<hr>');
define('DEFAULT_EMAIL','email4johnson@gmail.com');
define('COPYRIGHT',chr(64)." $Year");
define('QUOTE',chr(39));
define('DQUOTE',chr(34));
define('CM',',');




//define('','');
//define('','');