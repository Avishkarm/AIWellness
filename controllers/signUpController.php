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
require_once($pathprefix."utilities/dbutils.php");
require_once($pathprefix."utilities/authentication.php");
include($pathprefix."models/userModel.php");
include($pathprefix."models/notificationModel.php");
include($pathprefix."models/logsModel.php");
require_once($pathprefix."logs/xmlProcessor/xmlProcessor.php");

$logStorePath =$logPath["signUp"];
$userEmail=$_POST["user_email"];
//for xml writing essential
$xmlProcessor = new xmlProcessor();
$xmlfilename = "userSignUp.xml";

/* Log initialization start */
$xmlArray = initializeXMLLog($userEmail);

$xml_data['request']["data"]='';
$xml_data['request']["attribute"]=$xmlArray["request"];


$msg = "User registration process start.";
$xml_data['step'.$i]["data"] = $i.". {$msg}"; 

//database connection
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
	$msg = "Success : Search database connection";
	$xml_data['step'.++$i]["data"] = $i.". {$msg}";
} else {
	printArr("Erroe : Database connection");
	exit;
}
//printArr($_POST['formdata']);
parse_str($_POST['formdata'],$_POST);
//printArr($_POST);
//accept request
//validtation for madatory parameters
if(isset($_POST) && !empty($_POST)){
	$msg = "Post array not empty";
	$xml_data['step'.++$i]["data"] = $i.". {$msg}";
	if(isset($_POST['user_first_name']) && !empty($_POST['user_first_name']) && isset($_POST['user_last_name']) && !empty($_POST['user_last_name']) && isset($_POST['user_mob']) && !empty($_POST['user_mob']) && isset($_POST['user_email']) && !empty($_POST['user_email']) && isset($_POST['user_password']) && !empty($_POST['user_password']) && isset($_POST['cpassword']) && !empty($_POST['cpassword'])){
		$msg="All mandatory parameters are passed.";		
		$xml_data['step'.++$i]["data"] = $i.". {$msg}";
		if(isset($_POST['terms']) && !empty($_POST['terms'])){
			$msg="Terms and conditions are accepted";		
			$xml_data['step'.++$i]["data"] = $i.". {$msg}";
			$userInfo=array();
			$userInfo['user_first_name']=cleanQueryParameter($conn,cleanXSS($_POST["user_first_name"]));
			$userInfo['user_last_name']=cleanQueryParameter($conn,cleanXSS($_POST["user_last_name"]));
			$userInfo['user_email']=cleanQueryParameter($conn,cleanXSS($_POST["user_email"]));
			$userInfo['cpassword']=cleanQueryParameter($conn,cleanXSS($_POST["cpassword"]));
			$userInfo['password']=cleanQueryParameter($conn,cleanXSS($_POST["user_password"]));
			$userInfo['country_code']=cleanXSS($_POST["country_code"]);
			$userInfo['user_mob']=cleanXSS($_POST["user_mob"]);
			$userInfo['user_type']=cleanXSS($_POST["user_type"]);
			//Email validation
			if (filter_var($userInfo['user_email'], FILTER_VALIDATE_EMAIL)){
				$msg="Valid email format";
				$xml_data['step'.++$i]["data"] = $i.". {$msg}";
				//Password length validation
				if(strlen($userInfo['password'])>=8){
					$msg="Success : Password length validation";
					$xml_data['step'.++$i]["data"] = $i.". {$msg}";
					//Password match validation
					if($userInfo['cpassword']==$userInfo['password']){
						$msg="Success : Confirm password match";
						$xml_data['step'.++$i]["data"] = $i.". {$msg}";
						//chexk user existance
						$user=getUserInfoWithType($userInfo['user_email'],$userInfo['user_type'],$conn);
						if(noError($user)){
							$msg="Success : User not exist";
							$xml_data['step'.++$i]["data"] = $i.". {$msg}";
							$salt=generateSalt();
							$userInfo['salt'] = $salt;
							$userInfo['user_password'] = encryptPassword($userInfo['password'],$salt);
                                                        $userInfo['user_status'] = 'Active';
							//printArr($userInfo);
							//insert userinfo in user table
							$signUpUser=insertProfileInfo($userInfo, $conn);
							if(noError($signUpUser)){
								$msg="eHeilung account created successfully.";
								$xml_data['step'.++$i]["data"] = $i.". {$msg}";
								$returnArr["errCode"] =-1 ;
                                                                $returnArr["errMsg"] = $msg;
                                                                $resendMsg="<a style='color:#0dae04' onclick='gotologin()' data-dismiss='modal' >Get Started</a>";
                                                                //$returnArr['resendMsg']=$resendMsg;											
							} else{
								$msg= "Failed to signing up";
								$xml_data['step'.++$i]["data"] = $i.". {$msg}";
								$returnArr["errCode"] =7 ;
		  						$returnArr["errMsg"] = $msg;
		  						$resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
		  						$returnArr['resendMsg']=$resendMsg;
							}
						}else{
							$msg= "User already exists.<br>";
							if($user['errMsg']['login_type']=="eheilung"){
                                                                $msg= "User already exists.Login to continue <br> Please, click the link below to login";
                                                                $resendMsg="<a style='color:#0dae04' onclick='gotologin()'  data-dismiss='modal' >Login</a>";
                                                                $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                                                                $returnArr["errCode"] =5 ;
                                                                $returnArr["errMsg"] = $msg;
                                                                $returnArr['resendMsg']=$resendMsg;
							}else{
								$msg= "User already register with another account.Please click the link below to login";
								$resendMsg="<a style='color:#0dae04' onclick='gotologin()'  data-dismiss='modal' >Login</a>";
								$xml_data['step'.++$i]["data"] = $i.". {$msg}";
								$returnArr["errCode"] =5 ;
		  						$returnArr["errMsg"] = $msg;
		  						$returnArr['resendMsg']=$resendMsg;
							}
						}
					} else{
						$msg= "Password not matched";
						$xml_data['step'.++$i]["data"] = $i.". {$msg}";
						$returnArr["errCode"] =4 ;
		  				$returnArr["errMsg"] = $msg;
		  				$resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
		  				$returnArr['resendMsg']=$resendMsg;
		  				
					}
				} else{
					$msg= "Password must have 8 characters";
					$xml_data['step'.++$i]["data"] = $i.". {$msg}";
					$returnArr["errCode"] =3 ;
		  			$returnArr["errMsg"] = $msg;
		  			$resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
		  			$returnArr['resendMsg']=$resendMsg;
				}
				
			} else{
				$msg= "Invalid email format!";
				$xml_data['step'.++$i]["data"] = $i.". {$msg}";
				$returnArr["errCode"] =2 ;
		  		$returnArr["errMsg"] = $msg;
		  		$resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
		  		$returnArr['resendMsg']=$resendMsg;
			}	
		}else{
		$msg= "Terms and conditions are not accepted.Please accept it first";
		$xml_data['step'.++$i]["data"] = $i.". {$msg}";
		$returnArr["errCode"] =1 ;
	  	$returnArr["errMsg"] = $msg;
	  	$resendMsg="<a style='color:#0dae04' data-dismiss='modal'>Try again</a>";
	  	$returnArr['resendMsg']=$resendMsg;
		}
	}else{
			$msg= "Mandatory parameters not passed";
			$xml_data['step'.++$i]["data"] = $i.". {$msg}";
			$returnArr["errCode"] =1 ;
		  	$returnArr["errMsg"] = $msg;
		  	$resendMsg="<a style='color:#0dae04' data-dismiss='modal'>Try again</a>";
		  	$returnArr['resendMsg']=$resendMsg;

		}
} else{
	$msg= "Parameters not passed";
	$xml_data['step'.++$i]["data"] = $i.". {$msg}";
	$returnArr["errCode"] =1 ;
  	$returnArr["errMsg"] = $msg;
  	$resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
	$returnArr['resendMsg']=$resendMsg;
}

// create or update xml log Files
//$xmlProcessor->writeXML($xmlfilename, $logStorePath, $xml_data, $xmlArray["activity"]);
echo json_encode($returnArr);

?>