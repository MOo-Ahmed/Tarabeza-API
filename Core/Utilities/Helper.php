<?php

namespace Core\Utilities;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Helper
{
	public static function generateConfirmationCode($_len = 5)
	{
		// you can increase the digits by changing 10 to desired digit
		$random = substr(number_format(time() * rand(), 0, '', ''), 0, $_len);
		return $random;
	}

	public static function sendMail($to, $subject, $message)
	{
		

		$from = $GLOBALS["mail"]["from"]["address"];
		
		// If the SMTP emails option is enabled in the Admin Panel
		if($GLOBALS["mail"]["default"] == "smtp")
		{
			//Create a new PHPMailer instance
			$mail = new PHPMailer;
			//Tell PHPMailer to use SMTP
			$mail->isSMTP();
			//Enable SMTP debugging
			// 0 = off (for production use)
			// 1 = client messages
			// 2 = client and server messages
			$mail->SMTPDebug = 0;
			//Set the CharSet encoding
			$mail->CharSet = 'UTF-8';
			//Ask for HTML-friendly debug output
			$mail->Debugoutput = 'html';
			//Set the hostname of the mail server
			$mail->Host = $GLOBALS["mail"]["mailers"]["smtp"]['host'];
			//Set the SMTP port number - likely to be 25, 465 or 587
			$mail->Port = 465;
			//Whether to use SMTP authentication
			$mail->SMTPAuth = TRUE;
			//Set the SMTP Secure
			$mail->SMTPSecure = "tls";
			//Username to use for SMTP authentication
			$mail->Username = $GLOBALS["mail"]["mailers"]["smtp"]['username'];
			//Password to use for SMTP authentication
			$mail->Password = $GLOBALS["mail"]["mailers"]["smtp"]['password'];
			//Set who the message is to be sent from
			$mail->setFrom($from, $GLOBALS["mail"]["from"]["name"]);
			//Set an alternative reply-to address
			$mail->addReplyTo($from, $GLOBALS["mail"]["from"]["name"]);

			//Set who the message is to be sent to
			if(is_array($to))
			{
				foreach($to as $address)
				{
					$mail->addAddress($address);
				}
			}
			else
			{
				$mail->addAddress($to);
			}

			//Set the subject line
			$mail->Subject = $subject;
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$mail->msgHTML($message);

			//send the message, check for errors
			if(!$mail->send())
			{
				// Return the error in the Browser's console
				//echo $mail->ErrorInfo;
			}
		}
		else
		{
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: '.$from.'' . "\r\n" . 'Reply-To: '.$from . "\r\n" . 'X-Mailer: PHP/' . phpversion();

			if(is_array($to))
			{
				foreach($to as $address)
				{
					@mail($address, $subject, $message, $headers);
				}
			}
			else
			{
				@mail($to, $subject, $message, $headers);
			}
		}
	}
}