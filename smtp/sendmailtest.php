<?php
require 'class.phpmailer.php';
function Send_Mail($to,$subject,$body,$path = "",$keyword="",$attachment ="false")
{

//echo $attachment;
global $serverRootURL;
$from       = "donotreply@searchtrade.com";
// $from       = "searchcoin-network@scoinz.com";
$mail       = new PHPMailer();
$mail->IsSMTP(true);            // use SMTP
$mail->IsHTML(true);
$mail->SMTPAuth   = true;                  // enable SMTP authentication
// $mail->Host       = "tls://smtp.gmail.com"; // Amazon SES server, note "tls://" protocol
$mail->Host       = "smtp.gmail.com"; // Amazon SES server, note "tls://" protocol
// $mail->Host       = "mail.scoinz.com"; // Amazon SES server, note "tls://" protocol
$mail->Port       =  465;                    // set the SMTP port
//$mail->Port       =  25;                    // set the SMTP port 
// $mail->Port       =  2525;                    // set the SMTP port
$mail->Username   = "donotreply@searchtrade.com";  // SMTP  username
// $mail->Username   = "searchcoin-network@scoinz.com";  // SMTP  username
$mail->Password   = "abccc123";  // SMTP password
$mail->SetFrom($from, 'SearchTrade');
$mail->AddReplyTo($from,'SearchTrade');
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

$to = "testsearchcoin@gmail.com";
						$subject = "Keyword Pre-sale:Funding Your Wallet Notification";
						$message = "You Have successfully funded your wallet with ".$amount_paid." BTC";
						$result = Send_Mail($to,$subject,$message); 
						echo "hi";
						print_r($result); 
?>
