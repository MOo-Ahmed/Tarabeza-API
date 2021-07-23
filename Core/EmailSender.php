<?php

namespace Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailSender
{
	public static function send($_to, $_subject, $_message)
	{
		//Create an instance; passing `true` enables exceptions
		$mail = new PHPMailer(true);

		try
		{
		    //Server settings

		    //Send using SMTP
		    $mail->isSMTP();                                            
		    //Set the SMTP server to send through
		    $mail->Host       = $GLOBALS["mail"]["mailers"]["smtp"]["host"]; 
		    //Enable SMTP authentication
		    $mail->SMTPAuth   = true;                                   
		    //SMTP username
		    $mail->Username   = $GLOBALS["mail"]["mailers"]["smtp"]["username"];
		     //SMTP password
		    $mail->Password   = $GLOBALS["mail"]["mailers"]["smtp"]["password"];                              
		    //Enable implicit TLS encryption
		    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
		    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
		    $mail->Port       = $GLOBALS["mail"]["mailers"]["smtp"]["port"];                                    

		    //Recipients
		    $mail->setFrom($GLOBALS["mail"]["from"]["address"], $GLOBALS["mail"]["from"]["name"]);
		    $mail->addAddress($_to);
		
		    //Content
		    $mail->isHTML(true);                                  //Set email format to HTML
		    $mail->Subject = $_subject;
		    $mail->Body    = $_message;

		    $mail->send();

			return true;
		} 
		catch (Exception $e)
		{
		    return false;
		}
	}

}

