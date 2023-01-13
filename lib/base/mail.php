<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';


function send_email($email, $nama, $subject, $messege)
{

	require "../../config.php";
	$config = array(
		"host_mail" => $host_mail,
		"user_mail" => $user_mail,
		"pass_mail" => $pass_mail,
		"smtp_mail" => $smtp_mail,
		"port_mail" => $port_mail,
		"name_mail" => $name_mail
	);

	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
	try {
		//Server settings
		$mail->SMTPDebug = 0;                                 // Enable verbose debug output
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = $config['host_mail'];  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = $config['user_mail'];                 // SMTP username 'info@developermuslim.com'
		$mail->Password = $config['pass_mail'];                           // SMTP password 
		$mail->SMTPSecure = $config['smtp_mail'];                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = $config['port_mail'];                                    // TCP port to connect to

		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		//Recipients
		$mail->setFrom($config['user_mail'], $config['name_mail']);
		$mail->addAddress($email, $nama);     // Add a recipient
		$mail->addReplyTo($config['user_mail'], 'Information');

		//Attachments
		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		//Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body    = $messege;
		$mail->AltBody = '';

		$mail->send();
		return 1;
	} catch (Exception $e) {
		//echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		return 0;
	}
}
