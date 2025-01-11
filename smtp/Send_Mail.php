<?php
require 'class.phpmailer.php';
require 'mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;


function Send_Mailold($to,$subject,$body,$path = "",$keyword="",$attachment ="false"){
	//echo $attachment;
	global $serverRootURL;
	$from       = "searchcoin@scndemo.com";
	// $from       = "searchcoin-network@scoinz.com";
	$mail       = new PHPMailer();
	$mail->IsSMTP(true);            // use SMTP
	$mail->IsHTML(true);
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	//$mail->Host       = "tls://smtp.gmail.com"; // Amazon SES server, note "tls://" protocol
	$mail->Host       = "mail.scndemo.com"; // Amazon SES server, note "tls://" protocol
	// $mail->Host       = "mail.scoinz.com"; // Amazon SES server, note "tls://" protocol
	//$mail->Port       =  465;                    // set the SMTP port
	$mail->Port       =  25;                    // set the SMTP port 
	// $mail->Port       =  2525;                    // set the SMTP port
	$mail->Username   = "searchcoin@scndemo.com";  // SMTP  username
	// $mail->Username   = "searchcoin-network@scoinz.com";  // SMTP  username
	$mail->Password   = "searchcoin123";  // SMTP password
	$mail->SetFrom($from, 'Searchcoin Network');
	$mail->AddReplyTo($from,'Searchcoin Network');
	$mail->Subject    = $subject;
	$mail->MsgHTML($body);
	//Add attachment
	$URL = $serverRootURL."keywordsale/temporary_zip/keywords_ownership.zip"; 
	if($attachment == "true"){  
	$mail->AddAttachment($URL, 'keywords_ownership_for_keyword-'.$keyword.'.zip'); 
	}
	$address = $to;
	$mail->AddAddress($address, $to);
	$result = $mail->Send();  

	return $result;  
} 

function Send_Mail($to,$subject,$body,$path = "",$keyword="",$attachment ="false"){
	# Instantiate the client.
	$mgClient = new Mailgun('key-2b8f2419e616db09b1297ba51d7cc770');
	//$domain = "sandboxa0d6c39d98864fb1a543e6a0e43272d2.mailgun.org";
	$domain = "searchtrade.com";

	 $returnArr = array();

	 # Make the call to the client.
	$result = $mgClient->sendMessage($domain, array(
		'from'    => 'SearchTrade<donotreply@scoinz.com>', 
		'to'      => $to,
		'subject' => $subject,
		'html'    => $body
	)); 
	 
	//print_r($result);
	return $result; 
} 

?>