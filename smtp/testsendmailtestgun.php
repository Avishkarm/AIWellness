<?php
include("Send_Mail.php");
/* require 'mailgun-php/vendor/autoload.php';
use Mailgun\Mailgun;

# Instantiate the client.
$mgClient = new Mailgun('key-2b8f2419e616db09b1297ba51d7cc770');
$domain = "sandboxa0d6c39d98864fb1a543e6a0e43272d2.mailgun.org";
$html = '<div style="border:solid thin #ff9933;padding:5px"><div style="width:100%;text-align:left;height:50px;background-color:#d68801"><img src="http://www.searchtrade.com/wp-content/uploads/2015/07/Searchtrade-logo2.png" style="margin-left:21px;margin-top: 10px;width: 86%;max-width: 300px;"></div><p>Hi, <br/> <br/> We need to make sure you are human. Please verify your email and get started using your Website account. <br/> <br/>Click <a href="http://scndemo.com/protected/controller/activation.php?code=123123">here</a> to activate your account.<br>OR<br> Copy The Link Address:-http://scndemo.com/protected/controller/activation.php?code='.$activation.' <br><br>You Have been given 123123 BTC as Registration bonus.</p></div>';
# Make the call to the client.
$result = $mgClient->sendMessage($domain, array(
    'from'    => 'SearchTrade<donotreply@searchtrade.com>',
   // 'to'      => 'Baz <vishal@searchtrade.com>',
	//'to'      => 'Baz <contact@searchtrade.com>',
	//'to'      => 'Baz <mayuri.shroff@ymail.com>',
	//'to'      => 'Baz <malvik@hansinfotech.in>',
	'to'      => '<searchcoin50@gmail.com>',
   
    'subject' => 'attachment txt and pdf test',
    //'text'    => 'Testing some Mailgun awesomness!',
	'html'    => $html
)); */

$to = "searchcoin50@gmail.com";  
$from = "SearchTrade<donotreply@searchtrade.com>";
$subject = "test sendmail";
$message = "mail send sucessfully";
$test = send_Mailnew($to, $from, $subject, $message);
//($test);
?>