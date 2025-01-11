<?php
/*
  if($activeHeader=="2opinion")
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
  } */
  
$companyName = "KES";

$servername = "localhost";

$dbname = "eh_v2-live";

$username = "root";

$password = "gctlab1";

$secret="gaLFGASDI123542UJZZX@#$35dfgg";

$tokenExpiryTime=60*60;

$adminUserName = "admin";
$adminPassword = "admin123";
$PatientMessageLimit="300"; 


$ContactEmail="mahesh.nijai@gmail.com";
$FeedbackEmail = "krunal.rele@gctlab.in";

$rootUrl="http://localhost/eheilung";
//mail send via mailgun or php mail  ///
$mailSender="php_mail";
$blogurl="http://localhost/eheilung/ehblog";

#$ContactEmail="malvikm@gmail.com";
#$rootUrl="http://192.168.1.103/hansinfo_eheilung";
//mail send via mailgun or php mail  ///
//$mailSender="mailGun";
//$blogurl="http://192.168.1.103/hansinfo_eheilung/wordpress";


$Syphilis="Syphilis or Destruction of an organ or a tissue and its constituent cells due to auto-immunity or necrosis, etc where it can no longer perform its normal physiological function";
$Sycosis="Sycosis or Disturbance or change in the normal physiological structure of a tissue due to excess accumulation, proliferation or shrinking of cells involved.";
$Psora="Psora or Functional disturbance only limited to altered sensations like pain from inflammation or increased sensitivity without any permanent alterations in the structure or Functional disturbance only limited to altered sensations like pain from inflammation or increased sensitivity without any permanent alterations in the structure";

//$datetime=setTimezone(new DateTimeZone('Asia/Calcutta'));
//print $datetime->format('Y-m-d H:i:s (e)');
$logPath=array();
$logPath['signUp']          =   "../logs/signUp/";
$logPath['login']           =   "../logs/login/";
$logPath['userProfile']     =   "../logs/userProfile/";
$logPath['managePatient']   =   "../logs/managePatient/";

$footerSocialLinks=array();
$footerSocialLinks['fb']="https://www.facebook.com/";
$footerSocialLinks['tweet']="https://twitter.com/";
$footerSocialLinks['gplus']="https://plus.google.com/";
$footerSocialLinks['pin']="https://www.pinterest.com/";


/*
URL to be used inside HTML as absolute links
*/
define("DOMAIN_NAME", getBaseUrl());

define("CONTROLLER",DOMAIN_NAME."/controllers");
define("VIEWS",DOMAIN_NAME."/views");
define("mailSender",'php_mail');

/*
URL to be used in  include or required absolute links
*/


define("BASE_PATH", $_SERVER['DOCUMENT_ROOT']);
define("CONTROLLER_DIR", "controllers");
define("CONTROLLER_ADMIN_DIR", "controllers/admin");
define("VIEWS_DIR", "views");
define("VIEWS_ADMIN_DIR","views/admin");



error_reporting(0);

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$newUI_URL=array('index'=>'index.php','loginSignUP'=>'views/sign_in.php','forgetPass'=>'views/forgot_password.php','dashboard'=>'views/dashboard.php','changePassword'=>'views/changepassword.php','doctor_caseHistory'=>'views/doctor_caseHistory.php','caseHistory'=>'views/caseHistory.php');


define('SEND_SMS_URL', 'http://onlinesms.in/api/sendValidSMSdataUrl.php?');
define('SMS_URL_LOGIN','9320027660');
define('SMS_URL_PWD','tagepuguz');
define('SMS_URL_SNDRID','OPTINS');

date_default_timezone_set('Asia/Kolkata');



/*
function noError
Purpose: To check error arrays for no error state
Arguments: The error array returned by any function
Returns: True/False
*/
function noError($errorArr) {
	$noError = false;
	if(array_key_exists(-1, $errorArr["errCode"]))
		$noError=true;
	return $noError;
}

function authorizeweb($tempsecret){
    global $secret;
	$test=false;
	if($tempsecret==$secret){
	 $test=true;
	}
	
	return $test;
}


$sessionTimeout = 60;

$resultsPerPage = 10;

$captchaPublickey = ""; 
$captchaPrivatekey = "";

$tokenExpiryTime = 60*60;

$blanks= array("", " ", '', ' ');

/*
function PrintArray 
Purpose: Text in a pre element
is displayed in a fixed-width
font, and it preserves
both spaces and
line breaks
Arguments: $arr prints the text element
*/
function printArr($arr){
	print("<pre>");
		print_r($arr);
	print("</pre>");
	
}
/*
Purpose: just declaring the timespan duration for day, week, month and year
*/
$graphDurationsMap = array(1=>"Today's", 7=>"This week's", 30=>"This Month", 90=>"Last 3 months", 180=>"Last 6 months", 365=>"This year");

$monthsMap = array(
	1=>"January",2=>"February",3=>"March",4=>"April",5=>"May",6=>"June",7=>"July",8=>"August",9=>"September",10=>"October",11=>"November",12=>"December"
);

$httpBadResponses = array(
	400 =>"Bad Request",
	401=>"Authentication Required",
	403 =>"Forbidden: Not sufficient Access",
	404 =>"Not Found",
	500 =>"Youtube Error",
	501 =>"No such command or operation",
	503 =>"Youtube API unreachable"
);

$errorCode = array(
	 1=>"successful",
	 2=>"Could not connect to database",
	 3=>"Could not find the database",
	 4=>"oops! something is wrong with server",
	 5=>"Invalid credentials",
	 6=>"Invalid request",
     7 =>""
);

$states_arr = array('AL'=>"Alabama",'AK'=>"Alaska",'AZ'=>"Arizona",'AR'=>"Arkansas",'CA'=>"California",'CO'=>"Colorado",'CT'=>"Connecticut",'DE'=>"Delaware",'DC'=>"District Of Columbia",'FL'=>"Florida",'GA'=>"Georgia",'HI'=>"Hawaii",'ID'=>"Idaho",'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa",  'KS'=>"Kansas",'KY'=>"Kentucky",'LA'=>"Louisiana",'ME'=>"Maine",'MD'=>"Maryland", 'MA'=>"Massachusetts",'MI'=>"Michigan",'MN'=>"Minnesota",'MS'=>"Mississippi",'MO'=>"Missouri",'MT'=>"Montana",'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma", 'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming", 'AS'=>'American Samoa', 'DC'=>'District of Columbia', 'GU'=>'Guam', 'MP'=>'Northern Marina Islands', 'PR'=>'Puerto Rico', 'VI'=>'U.S. Virgin Islands');

/*
function cleanQueryParameter
Purpose: To clean inputs to queries in order to prevent SQL Injection Attacks
		 This function must always (with few exceptions) be used to make data safe before sending a query to MySQL.
Arguments: The parameter to be cleaned
Returns: The escaped parameter
*/

/*
function cleanDisplayParameter
Purpose: To clean outputs to screen in order to prevent XSS Attacks
Arguments: The parameter to be cleaned
Returns: The escaped parameter
*/
/*function cleanDisplayParameter($string) {
	//strip HTML tags from input data
	$string = strip_tags($string);
	//turn all characters into their html equivalent	  
	$string = htmlentities(stripslashes($string), ENT_QUOTES);
	
	return $string;
}*/


function cleanXSS($string){
    return htmlspecialchars($string,ENT_QUOTES,'UTF-8');
}
function cleanDisplayParameter($conn, $string){ 
   $string = stripslashes($string);    
   $string = sanitize_data($string, ENT_QUOTES | ENT_HTML5);
   $string=htmlspecialchars_decode($string);    
   $string=html_entity_decode($string,ENT_QUOTES);    
   $string = sanitize_data($string);    
   $string = mysqli_real_escape_string($conn, $string);    
   return $string;
}

//=====================================================================

/*
function sendMail
Purpose: To send emails
Arguments: The from and to email addresses, subject and message
Returns: error array
*/



/*function sendMail($to, $from, $subject, $message) {
	
			//echo $to."<br>".$from."<br>".$subject."<br>".$message."<br>";
		$returnArr = array();
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";


		// Additional headers
		//$headers .= 'To: ' .$to. "\r\n";
		$headers .= 'From: ' .$from. "\r\n";
	if($mailSender=='php_mail')
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
	else if($mailSender=='mailGun')
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
}*/

/* SMS SEND by CUrl

Purpose: To send SMS

Arguments: userID, userPWD, recerverNO and message

Returns: success/failure result */



/*
function per_days_diff
Purpose: To calculate date differences
Arguments: The start and end dates
Returns: array of differences in different formats
*/

function per_days_diff($start_date, $end_date) {
	$per_days = 0;
	$noOfWeek=0;
	$noOfWeekEnd =0;
	$highSeason=array("7","8");
	
	$current_date = strtotime($start_date);
	$current_date += (24 * 3600);
	$end_date = strtotime($end_date);
	
	$seassion = (in_array(date('m', $current_date),$highSeason))?"2":"1";
	
	$noOfdays = array('');
	
	while ($current_date <= $end_date) {
		if ($current_date <= $end_date) {
			$date = date('N', $current_date); 
			array_push($noOfdays,$date);
			$current_date = strtotime('+1 day', $current_date);
		}
	}
	$finalDays = array_shift($noOfdays);
	//print_r($noOfdays);
	$weekFirst = array("week"=>array(),"weekEnd"=>array());
	for($i=0;$i<count($noOfdays);$i++)
	{
		if($noOfdays[$i]==1) {
			//echo "this is week";
			//echo "<br/>";
			if($noOfdays[$i+6]==7){
				$noOfWeek++;
				$i=$i+6;
			} else {
				$per_days++;
			}
		//array_push($weekFirst["week"],$day);
		} else if($noOfdays[$i]==5) {
			//echo "this is weekend";
			//echo "<br/>";
			if($noOfdays[$i+2] ==7) {
				$noOfWeekEnd++;
				$i = $i+2;
			} else {
				$per_days++;
			}
			//echo "after weekend value:- ".$i;
			//echo "<br/>";
		} else {
			$per_days++;
		}	
	}
	/*echo $noOfWeek;
	echo "<br/>";
	echo $noOfWeekEnd;
	echo "<br/>";
	print_r($per_days);
	echo "<br/>";
	print_r($weekFirst);*/
	
	$duration = array("week"=>$noOfWeek,"weekEnd"=>$noOfWeekEnd,"perDay"=>$per_days,"seassion"=>$seassion);
	return $duration;
}


function getBaseUrl() 
{
    // output: /myproject/index.php
    $currentPath = $_SERVER['PHP_SELF']; 

    // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index ) 
    $pathInfo = pathinfo($currentPath); 

    // output: localhost
    $hostName = $_SERVER['HTTP_HOST']; 

    // output: http://
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'https://';

    // return: http://localhost/myproject/
    //printArr($protocol.$hostName);
    return $protocol.$hostName;
}

/*function calcAge($dob){
    $diff = (date('Y') - date('Y',strtotime($dob)));
    return $diff;
}*/

function calcAge($dob){
	//echo date('Y',strtotime($dob));
    $diff = (date('Y') - date('Y',strtotime($dob)));
  	$birthday=date('Y').'-'.date('m',strtotime($dob)).'-'.date('d',strtotime($dob));
  	$now=date('Y-m-d');
  	 if ($now >= $birthday) 	return date('Y') - date('Y',strtotime($dob));
      else	return date('Y') - date('Y',strtotime($dob)) - 1;
}
/*function get_age(born, now) {
  $birthday = new Date(now.getFullYear(), born.getMonth(), born.getDate()); 
  $co
    if (now >= birthday) 	return now.getFullYear() - born.getFullYear();
      else	return now.getFullYear() - born.getFullYear() - 1;
  }*/
function ageCalculator($dob){
	if(!empty($dob)){
		$birthdate = new DateTime($dob);
		$today   = new DateTime('today');
		$age = $birthdate->diff($today)->y;
		return $age;
	}else{
		return 0;
	}
}
?>
