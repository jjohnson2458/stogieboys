<?php
include_once('/stogieboys/zendev/global/config.php');
/*
* This really isn't a class. just a relay file to send mail
*
*/

if($_POST['pass'] == '938qc4980489FHI#$)W*CH#^&@FW)('){	
	$post = $_POST;
	$mail = new Mail();
	$mail->To($post['to_email'], $post['to_name']);
	$mail->From($post['from_email'], $post['from_name']);
	$mail->Subject($post['subject']);
	$mail->Body($post['message']);
	$mail->Send();
	echo 'Mail (Subject:' . $post['subject'] . ') has been sent to ' . $post['to_email']; 
} else {
	PR::e($_POST, 'post_vars', 'email4johnson@gmail.com');
}