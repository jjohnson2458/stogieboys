<?php
include_once('../config.php');
include_once('../global/config.php');

$db = new MySQL(DB_DATABASE, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
$action = $argv[1];
if ($action == 'start') {
	$sql = "
	UPDATE
	sales_promotion sp
	SET sp.`status` = 1
	WHERE 1
	AND sp.name LIKE '%nooner%'
	AND sp.date_start = CURRENT_DATE()
	ORDER BY sp.date_start DESC
	LIMIT 1;
	";	
} elseif($action == 'stop') {
	$sql = "
	UPDATE
	sales_promotion sp
	SET sp.`status` = 0
	WHERE 1
	AND sp.name LIKE '%nooner%'
	AND sp.date_start = CURRENT_DATE()
	ORDER BY sp.date_start DESC
	LIMIT 1;
	";	
}

$db->Query($sql);
PR::r($db->Error);

