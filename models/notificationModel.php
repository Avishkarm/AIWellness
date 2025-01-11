<?php 
if($activeHeader=="2opinion" || $activeHeader=='knowledge_center' || $activeHeader=="doctorsArea")
  {
    $pathprefix="../../";
    $views =  "../";
    $controllers = "../../controllers/";
  }else if($activeHeader == "index.php"){
    $pathprefix="";
    $views =  "views/";
    $controllers = "controllers/";
  }else {
    $pathprefix="../";
    $views = "";
    $controllers = "../controllers/";
  } 
//session_start();
//prepare for request  
require_once($pathprefix."utilities/config.php");

function sendMail($to, $from, $subject, $message) {
	//$mailSender;
	//echo $mailSender = mailSender;
		//echo $to."<br>".$from."<br>".$subject."<br>".$message."<br>";
		$returnArr = array();
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		// Additional headers
		//$headers .= 'To: ' .$to. "\r\n";
		$headers .= 'From: ' .$from. "\r\n";
	if(mailSender=='php_mail')
	{
		// Mail iterator_apply(iterator, function)
		//printArr(mail($to, $subject, $message, $headers));
		if(mail($to, $subject, $message, $headers)){
			$returnArr["errCode"][-1]=-1;
			$returnArr["errMsg"]="Email Sent";
		} else {
			$returnArr["errCode"][8]=8;
			$returnArr["errMsg"]="Email Send  Error";
		}
	}	
	else if(mailSender=='mailGun')
	{
		$mgClient = new Mailgun('YOUR_API_KEY');
		$domain = "YOUR_DOMAIN_NAME";

		# Make the call to the client.
		$result = $mgClient->sendMessage($domain, array(
		    'from'    => $headers,
		    'to'      => $to,
		    'subject' => $subject,
		    'text'    => $message
		));
		if($result){
			$returnArr["errCode"][-1]=-1;
			$returnArr["errMsg"]="Email Sent";
		} else {
			$returnArr["errCode"][8]=8;
			$returnArr["errMsg"]="Email Send  Error";
		}

}	
//printArr($returnArr);
return $returnArr;
}
function sendNotifications($conn,$bitwise=0, $recipients="", $sender="", $message="", $subject="",$mobile=""){
	$email=1;
	$sms=2;
	$push=4;
	$returnArr=array();
	$arr=setNotification($conn,$recipients, $sender, $message, $subject,$bitwise);

	if(noError($arr)){
		if($email &  $bitwise){
			$msg=sendMail($recipients, $sender, $subject, $message);
			if(noError($msg)){
				$returnArr['errMsg'].="Successfully sent to your Email ";
				$returnArr['errCode'][-1]=-1;
			}else{
				$returnArr['errCode'][1]=1;
				$returnArr['errMsg'].=$msg['errMsg'];
			}
		}
		if($sms &  $bitwise){
			$sms1=sendSMS($message,$mobile);
			if(noError($sms1)){
				$returnArr['errMsg'].="and mobile";
				$returnArr['errCode'][-1]=-1;
			}else{
				$returnArr['errCode'][1]=1;
				$returnArr['errMsg'].=$sms1['errMsg'];
			}
		}
		if($push &  $bitwise){

		}
	}else{
		$returnArr['errCode'][10]=10;
		$returnArr['errMsg']=$arr['errMsg'];
	}
	//printArr($returnArr);
	return $returnArr;
}
function sendFollowupNotification($conn,$bitwise=0, $sender="",$doctor_email="",$doctor_mob="",$patient_email="",$patient_mob="",$doctorMessage="",$doctorSMS="",$subject="",$patientMsg=""){
	$email=1;
	$sms=2;
	$push=4;
	$returnArr=array();
	$arr=setNotification($conn,$doctor_email, $sender, $doctorSMS, $subject,$bitwise);

	if(noError($arr)){
		if($email &  $bitwise){
			$msg=sendMail($doctor_email, $sender, $subject, $doctorSMS);
			if(noError($msg)){
				$msg1=sendMail($patient_email, $sender, $subject, $patientMsg);
				$returnArr['errMsg'].="Successfully sent to your Email ";
				$returnArr['errCode'][-1]=-1;
			}else{
				$returnArr['errCode'][1]=1;
				$returnArr['errMsg'].=$msg['errMsg'];
			}
		}
		if($sms &  $bitwise){
			$sms1=sendSMS($doctorSMS,$doctor_mob);
			if(noError($sms1)){
				$sms2=sendSMS($patientMsg,$patient_mob);
				$returnArr['errMsg'].="and mobile";
				$returnArr['errCode'][-1]=-1;
			}else{
				$returnArr['errCode'][1]=1;
				$returnArr['errMsg'].=$sms1['errMsg'];
			}
		}
		if($push &  $bitwise){

		}
	}else{
		$returnArr['errCode'][10]=10;
		$returnArr['errMsg']=$arr['errMsg'];
	}
	//printArr($returnArr);
	return $returnArr;
}

function setNotification($conn,$recipients, $sender, $message, $subject,$bitwise){
	$returnArr=array();

	$month=(int) date('m');
	$year=date('Y');
	$view=0;
	$tableName="Notification_".$month."_".$year;

	$strQuery=sprintf("INSERT into %s (time_stamp,recipient,sender,message,subject,type,view) values('%s','%s','%s','%s','%s','%s','%s')",$tableName,date('Y-m-d H:i:s'),$recipients, $sender, $message, $subject,$bitwise,$view);
	$query=runQuery($strQuery,$conn);
	if(noError($query)){
		$returnArr['errMsg']="Notification inserted into DB";
		$returnArr['errCode'][-1]=-1;
	}else{
		$returnArr['errMsg']=$query['errMsg'];
		$returnArr['errCode'][1]=1;
	}
	//printArr($returnArr);
	return $returnArr;
}
function sendSMS($msg='',$mobile=''){
	$returnArr=array();
	//echo "hiiss".$mobile;
	$ch = curl_init();
	//echo "hiiss";
	$url = SEND_SMS_URL;
	$login = SMS_URL_LOGIN;
	$pwd = SMS_URL_PWD;
	$sndID =SMS_URL_SNDRID;
	$message=urlencode($msg);
	 $urlnew=$url."login=".$login."&pword=".$pwd."&msg=".$message."&senderid=".$sndID."&mobnum=".$mobile;
	curl_setopt($ch, CURLOPT_URL, $urlnew);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "login=".$login."&pword=".$pwd."&msg=".$message."&senderid=".$sndID."&mobnum=".$mobile);
	$buffer = curl_exec($ch);
	if(empty($buffer)){
		$returnArr['errMsg']="Message";
		$returnArr['errCode'][-1]=-1;
	}else{
		$returnArr['errMsg']=$buffer;
		$returnArr['errCode'][-1]=-1;
	}
	curl_close($ch);
	//printArr($returnArr);
	return $returnArr;
}
?>