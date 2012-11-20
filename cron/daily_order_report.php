<?php
include_once('../global/index.php');
$start_date = date('Y-m-d', strtotime('- 30 day'));
$end_date = date('Y-m-d');
$daily_folder = '/stogieboys/cron/';

$orders_emails[] = 'jjohnson@stogieboys.com';
$orders_emails[] = 'sking@stogieboys.com';
$orders_emails[] = 'peusanio@stogieboys.com';
//$orders_emails[] = 'jamesm@stogieboys.com';

$dbhost = 'localhost';
$dbname = 'mkephart_opencart';
$dbuser = 'mkephart_ocdev3';
$dbpass = 'dkj32p3m';
$db = new MySQL($dbname, $dbhost, $dbuser, $dbpass);

$sql = "SELECT 
DATE_FORMAT(  `date_added` ,  '%Y-%m-%d' ) AS  `Date`,
COUNT(o.order_id ) AS Orders,
FORMAT(SUM(ot.value), 2) as Gross, 
FORMAT(SUM(o.total ), 2) AS Net
FROM order_total ot, `order` o
WHERE 1
AND ot.order_id = o.order_id
AND (ot.code = 'ot_total' )
AND o.date_added BETWEEN '" . $start_date . "' AND '" . $end_date . "'
GROUP BY `DATE`";


$rows = $db->GetAllRows($sql);
$filename = $daily_folder . 'orders_' . date('Ymd') . '.csv';
$fh = fopen($filename,'w+');
foreach($rows as $key => $row){
	if($key == 0){
		fputcsv($fh,array_keys($row));
	}
	fputcsv($fh,$row);
}
fclose($fh);

$messages 	= array();
$messages[] = '*** THIS IS AN AUTO-GENERATED EMAIL REPORT **';
$messages[] = "Order Report for " . Date::convert(RIGHTNOW, 'F j, Y h:i a');
$messages[] = "Orders From " . Date::convert($start_date, 'F j, Y' ) .' to ' .Date::convert($end_date, 'F j, Y' );

$message = AR::join($messages, "\n\r");

foreach((array)$orders_emails as $orders_email){
	include_once('/home/mkephart/public_html/includes/classes/class.phpmailer.php');
	$mail = new PHPMailer();
	$mail->Subject = 'Order Report for ' .RIGHTNOW;
	$mail->From = ('orders@stogieboys.com');
	$mail->FromName = ('Stogie Order Report');
	$mail->AddAddress($orders_email);
	$mail->Body = $message;
	$mail->AddAttachment($filename, $name = basename($filename), $encoding = "base64"); 
	$mail->Send();
}
