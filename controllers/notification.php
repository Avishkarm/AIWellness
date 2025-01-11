<?php 
// require_once("../controllers/config.php");
// require_once("../controllers/dbutils.php");
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

session_start();
require_once($pathprefix."utilities/config.php");
require_once($pathprefix."utilities/dbutils.php");  
require_once($pathprefix."utilities/authentication.php");
require_once($pathprefix."models/userModel.php");
require_once($pathprefix."smtp/mailgun-php/vendor/autoload.php");
use Mailgun\Mailgun;
//database connection handling
$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
} else {
	printArr("Database Error");
	exit;
}


	
if($_SERVER['REQUEST_METHOD']=="POST" ) {


	if($_REQUEST['type']=="notification"){

		$user = "";

		if(isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)){
			$user = $_SESSION["user"];


			$userInfo = getUserInfo($user, $conn);
			if(noError($userInfo)){
				$userInfo = $userInfo["errMsg"];	
			
			} else {
				printArr("Error fetching user info".$userInfo["errMsg"]);
				exit;
			}
		} else {
			printArr("You do not have sufficient privileges to access this page");
			exit;
		}
		$returnArr=array();

		$notify=getNotificationCount($conn,$user,0);

		if(noError($notify)){
			$returnArr['errMsg']=$notify['errMsg'];
			$returnArr['errCode'][-1]=-1;
		}else{
			$returnArr['errMsg']=$notify['errMsg'];
			$returnArr['errCode'][1]=1;
		}
		echo json_encode($returnArr);
	}
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
//echo $mailSender;die;
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
		//echo "hi11";
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

/*
bitwise:type of email
*/
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
	printArr($returnArr);
	return $returnArr;
}
function sendBulkEmail(){

}
function sendBulkSMS(){

}
/*
Getter Notifcation ALl for a User
*/
function getNotifcation($conn,$recipients="",$pageNo,$limit=10){
	$returnArr=array();
	$end=$pageNo*$limit;
	$start=$end-10;
	/*$end=$pageNo*$limit;
	$start=$end-10;*/

	$tableName=getTableName($conn,'Notification_');
	$cnt='';
	if(noError($tableName)){
		$tableName=$tableName['errMsg']['tableName'];
		$strQuery='';
		$cntQuery='select count(*) as countNotify from (';
		$last=count($tableName);
		foreach ($tableName as $key => $value) {
			$strQuery .= sprintf("SELECT * FROM %s Where recipient='%s'",$value,$recipients);
			$cntQuery .= sprintf("SELECT * FROM %s Where recipient='%s'",$value,$recipients);
			if($key<$last-1){
				$strQuery .= ' UNION ';
				$cntQuery .= ' UNION ';
			}		
		}
		$cntQuery .= ') x';
		$cnt=getNotifyCount($conn,$cntQuery);
		//$strQuery .= sprintf(" ORDER BY time_stamp DESC LIMIT %s,%s",$start,$limit);
		$strQuery .= sprintf(" ORDER BY time_stamp DESC LIMIT %s,%s",$start,$limit);
		$query=runQuery($strQuery,$conn);
		if(noError($query)){
			$returnArr['errCode'][-1]=-1;
			$result=$query['dbResource'];
			$arr=array();
			while ($row = mysqli_fetch_assoc($result)) {
				$arr[]=$row;
			}
			$returnArr['errMsg']=$arr;
			$returnArr['countNotify']=$cnt;
		}else{
			$returnArr['errMsg']=$query['errMsg'];
			$returnArr['errCode'][1]=1;
		}
	}else{
		$returnArr['errMsg']=$tableName['errMsg'];
		$returnArr['errCode'][1]=1;
	}
	//printArr($returnArr);
	return $returnArr;
}
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
function getAllNotificationCount($conn,$recipients=""){
	$returnArr=array();
	$tableName=getTableName($conn,'Notification_');
	$cnt='';
	if(noError($tableName)){
		$tableName=$tableName['errMsg']['tableName'];
		$cntQuery='select count(*) as countNotify from (';
		$last=count($tableName);
		foreach ($tableName as $key => $value) {
			$cntQuery .= sprintf("SELECT * FROM %s Where recipient='%s'",$value,$recipients);
			if($key<$last-1){
				$cntQuery .= ' UNION ';
			}		
		}
		$cntQuery .= ') x';
		$cnt=getNotifyCount($conn,$cntQuery);
		$returnArr['errMsg']=$cnt;
		$returnArr['errCode'][-1]=-1;
		}else{
		$returnArr['errMsg']=$tableName['errMsg'];
		$returnArr['errCode'][1]=1;
	}
	return $cnt;
}
function getNewNotifcation($conn,$recipients="",$pageNo,$limit=10){
	$returnArr=array();
	$end=$pageNo*$limit;
	$start=$end-10;
	/*$end=$pageNo*$limit;
	$start=$end-10;*/

	$tableName=getTableName($conn,'Notification_');

	$cnt='';
	if(noError($tableName)){
		$tableName=array_reverse($tableName);
		$tableName=$tableName['errMsg']['tableName'];
		//printArr($tableName);
		$tableName=array_reverse($tableName);
		//printArr($tableName);
		//$strQuery='';
		$res=array();
		$last=count($tableName);
		foreach ($tableName as $key => $value) {
			 $key.'<br>'.$value.'<br>';
		
			$strQuery = sprintf("SELECT * FROM %s Where recipient='%s' ORDER BY time_stamp DESC ",$value,$recipients);
			$cntQuery='select count(*) as countNotify from (';
			$cntQuery .= sprintf("SELECT * FROM %s Where recipient='%s'",$value,$recipients);
			$cntQuery .= ') x';
			$cnt=getNotifyCount($conn,$cntQuery);
				//echo  $strQuery .= sprintf(" ORDER BY time_stamp DESC LIMIT %s,%s",$start,$limit);
				$query=runQuery($strQuery,$conn);
				if(noError($query)){
					$returnArr['errCode'][-1]=-1;
					$result=$query['dbResource'];
					$arr=array();
					while ($row = mysqli_fetch_assoc($result)) {
						$arr[]=$row;
					}
					
				}

				$returnArr['errMsg']=$arr;
				$returnArr['countNotify']=$cnt;
				$returnArr['tableName']=$value;
				/*printArr($arr);
				echo $cnt.'--'.$value;*/
				array_push($res, $returnArr);
		}
		
		
		/*if(noError($query)){
			$returnArr['errCode'][-1]=-1;
			$result=$query['dbResource'];
			$arr=array();
			while ($row = mysqli_fetch_assoc($result)) {
				$arr[]=$row;
			}
			$returnArr['errMsg']=$arr;
			$returnArr['countNotify']=$cnt;
		}else{
			$returnArr['errMsg']=$query['errMsg'];
			$returnArr['errCode'][1]=1;
		}*/
	}else{
		$returnArr['errMsg']=$tableName['errMsg'];
		$returnArr['errCode'][1]=1;
	}
	//printArr($returnArr);
	return $res;
}
function getNotifcation1($conn,$recipients="",$sender,$view){
	$returnArr=array();
	$end=$pageNo*$limit;
	$start=$end-10;
	$tableName=getTableName($conn,'Notification_');
	$cnt='';
	if(noError($tableName)){
		$tableName=$tableName['errMsg']['tableName'];
		$strQuery='';
		$cntQuery='select count(*) as countNotify from (';
		$last=count($tableName);
		foreach ($tableName as $key => $value) {
			$strQuery .= sprintf("SELECT * FROM %s Where recipient='%s' and sender='%s' and view='%s'",$value,$recipients,$sender,$view);
			$cntQuery .= sprintf("SELECT * FROM %s Where recipient='%s' and sender='%s' and view='%s'",$value,$recipients,$sender,$view);
			if($key<$last-1){
				$strQuery .= ' UNION ';
				$cntQuery .= ' UNION ';
			}		
		}
		$cntQuery .= ') x';
		$cnt=getNotifyCount($conn,$cntQuery);
		$strQuery .= sprintf(" ORDER BY time_stamp DESC LIMIT %s,%s",$start,$end);
		$query=runQuery($strQuery,$conn);
		if(noError($query)){
			$returnArr['errCode'][-1]=-1;
			$result=$query['dbResource'];
			$arr=array();
			while ($row = mysqli_fetch_assoc($result)) {
				$arr[]=$row;
			}
			$returnArr['errMsg']=$arr;
			$returnArr['countNotify']=$cnt;
		}else{
			$returnArr['errMsg']=$query['errMsg'];
			$returnArr['errCode'][1]=1;
		}
	}else{
		$returnArr['errMsg']=$tableName['errMsg'];
		$returnArr['errCode'][1]=1;
	}
	
	return $returnArr;
}
function getNotifcation2($conn,$recipients="",$sender,$view){
	$returnArr=array();
	$end=$pageNo*$limit;
	$start=$end-10;
	$tableName=getTableName($conn,'Notification_');
	$cnt='';
	if(noError($tableName)){
		$tableName=$tableName['errMsg']['tableName'];
		$strQuery='';
		$cntQuery='select count(*) as countNotify from (';
		$last=count($tableName);
		foreach ($tableName as $key => $value) {
			$strQuery .= sprintf("SELECT * FROM %s Where recipient='%s' and sender='%s' and view='%s'",$value,$recipients,$sender,$view);
			$cntQuery .= sprintf("SELECT * FROM %s Where recipient='%s' and sender='%s' and view='%s'",$value,$recipients,$sender,$view);
			if($key<$last-1){
				$strQuery .= ' UNION ';
				$cntQuery .= ' UNION ';
			}		
		}
		$cntQuery .= ') x';
		$cnt=getNotifyCount($conn,$cntQuery);

		$strQuery .= sprintf("GROUP BY sender");
		$query=runQuery($strQuery,$conn);
		if(noError($query)){
			$returnArr['errCode'][-1]=-1;
			$result=$query['dbResource'];
			$arr=array();
			while ($row = mysqli_fetch_assoc($result)) {
				$arr[]=$row;
			}
			$returnArr['errMsg']=$arr;
			$returnArr['countNotify']=$cnt;
		}else{
			$returnArr['errMsg']=$query['errMsg'];
			$returnArr['errCode'][1]=1;
		}
	}else{
		$returnArr['errMsg']=$tableName['errMsg'];
		$returnArr['errCode'][1]=1;
	}
	
	return $returnArr;
}
function getfollowupNotification($conn,$recipients="",$sender,$view){
	$returnArr=array();
	$end=$pageNo*$limit;
	$start=$end-10;
	$tableName=getTableName($conn,'Notification_');
	$cnt='';
	if(noError($tableName)){
		$tableName=$tableName['errMsg']['tableName'];
		$strQuery='';
		$cntQuery='select count(*) as countNotify from (';
		$last=count($tableName);
		foreach ($tableName as $key => $value) {
			$strQuery .= sprintf("SELECT * FROM %s Where recipient='%s' and sender='%s' and view='%s'",$value,$recipients,$sender,$view);
			$cntQuery .= sprintf("SELECT * FROM %s Where recipient='%s' and sender='%s' and view='%s'",$value,$recipients,$sender,$view);
			if($key<$last-1){
				$strQuery .= ' UNION ';
				$cntQuery .= ' UNION ';
			}		
		}
		$cntQuery .= ') x';
		$cnt=getNotifyCount($conn,$cntQuery);
		//echo $strQuery .= sprintf(" ORDER BY time_stamp DESC LIMIT %s,%s",$start,$end);
		$query=runQuery($strQuery,$conn);
		if(noError($query)){
			$returnArr['errCode'][-1]=-1;
			$result=$query['dbResource'];
			$arr=array();
			while ($row = mysqli_fetch_assoc($result)) {
				$arr[]=$row;
			}
			$returnArr['errMsg']=$arr;
			$returnArr['countNotify']=$cnt;
		}else{
			$returnArr['errMsg']=$query['errMsg'];
			$returnArr['errCode'][1]=1;
		}
	}else{
		$returnArr['errMsg']=$tableName['errMsg'];
		$returnArr['errCode'][1]=1;
	}
	
	return $returnArr;
}

/*
	Getter Notification Count
*/
function getNotificationCount($conn,$recipients="",$view){
	$returnArr=array();
	$tableName=getTableName($conn,'Notification_');
	if(noError($tableName)){
		$tableName=$tableName['errMsg']['tableName'];
		$strQuery='select count(*) as countNotify from (';
		$last=count($tableName);
		foreach ($tableName as $key => $value) {
			$strQuery .= sprintf("SELECT * FROM %s Where recipient='%s' and view=%s",$value,$recipients,$view);
			if($key<$last-1){
				$strQuery .= ' UNION ';
			}		
		}
		$strQuery.= ') x';
		$query=getNotifyCount($conn,$strQuery);
		$returnArr['errMsg']=$query;
		$returnArr['errCode'][-1]=-1;
	}else{
		$returnArr['errMsg']=$tableName['errMsg'];
		$returnArr['errCode'][1]=1;
	}

	return $returnArr;
}
function getNotificationByID($conn,$notification_ID,$timestamp){
	$returnArr=array();
	$dt=date_parse($timestamp);

	$tableName = "Notification_".$dt['month']."_".$dt["year"];
	
	$strQuery = sprintf(" SELECT * FROM %s Where notification_ID='%s' LIMIT 1",$tableName,$notification_ID);

	$query=runQuery($strQuery,$conn);
	if(noError($query)){
		$result=$query['dbResource'];
		$returnArr['errMsg']=mysqli_fetch_assoc($result);
		$returnArr['errCode'][-1]=-1;	
	}else{
		$returnArr['errMsg']=$query['errMsg'];
		$returnArr['errCode'][1]=1;			
	}
	
	return $returnArr;
}

/*
Setter Notifcation
*/
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

/*
	Update Notifcation via Notification ID and Timestamp
*/
function updateNotificationById($conn,$notification_ID,$timestamp){
	$returnArr=array();
	$dt=date_parse($timestamp);

	$tableName = "Notification_".$dt['month']."_".$dt["year"];

	$strQuery = sprintf(" UPDATE %s set view=1 Where notification_ID=%s ",$tableName,$notification_ID);


	$query=runQuery($strQuery,$conn);
	if(noError($query)){
		$result=$query['dbResource'];
		$returnArr['errMsg']=$result;
		$returnArr['errCode'][-1]=-1;	
	}else{
		$returnArr['errMsg']=$query['errMsg'];
		$returnArr['errCode'][1]=1;			
	}
	return $returnArr;
}

function getTableName($conn,$like){
	$returnArr=array();

	$query=sprintf("SELECT t.TABLE_NAME AS foo FROM   INFORMATION_SCHEMA.TABLES AS t
     WHERE  t.TABLE_TYPE = 'BASE TABLE'   AND  t.TABLE_SCHEMA = 'hansinfo_eheilung'   AND  t.TABLE_NAME LIKE '%s%%'",$like);
	$query=runQuery($query,$conn);

	if(noError($query)){
		$returnArr['errCode'][-1]=-1;
		$arr=array();
		$result=$query['dbResource'];
		while ($row = mysqli_fetch_assoc($result)) {
			$arr[]=$row['foo'];
		}
		$returnArr['errMsg']['tableName']=$arr;
	}else{
		$returnArr['errMsg']=$query['errMsg'];
		$returnArr['errCode'][1]=1;
	}
	//printArr($returnArr);
	return $returnArr;
}

function getNotifyCount($conn,$query){
	$query=runQuery($query,$conn);
	$result=$query['dbResource'];
	$row=mysqli_fetch_assoc($result);
	$row['countNotify'];
	return $row['countNotify'];
}
function sendNotifications1($conn,$bitwise=0, $recipients="", $sender="", $message="",$message1="", $subject="",$mobile=""){
	//echo "hii".$mobile;
	$email=1;
	$sms=2;
	$push=4;
	$returnArr=array();
	$arr=setNotification($conn,$recipients, $sender, $message, $subject,$bitwise);
	//printArr($arr);

	if(noError($arr)){
		if($email &  $bitwise){
			
			$msg=sendMail($recipients, $sender, $subject, $message1);
			//printArr($msg);
			if(noError($msg)){
				$returnArr['errMsg'].="Email Sent ";
				$returnArr['errCode'][-1]=-1;
			}else{
				$returnArr['errCode'][1]=1;
				$returnArr['errMsg'].=$msg['errMsg'];
			}
		}
		if($sms &  $bitwise){
			//echo "hii".$mobile;
			$sms1=sendSMS($message,$mobile);
			//echo "hii11";
			//printArr($sms1);
			if(noError($sms1)){
				$returnArr['errMsg'].="Mobile Sent";
				$returnArr['errCode'][-1]=-1;
			}else{
				$returnArr['errCode'][1]=1;
				$returnArr['errMsg'].=$sms1['errMsg'];
			}
		}
		if($push &  $bitwise){
			//echo "hiittt";
		}
	}else{
		//echo "elsehiittt";
		$returnArr['errCode'][10]=10;
		$returnArr['errMsg']=$arr['errMsg'];
	}
	//printArr($returnArr);
	return $returnArr;
}

?>