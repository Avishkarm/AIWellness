<?php

require_once("../utilities/config.php");
require_once("../utilities/dbutils.php");
require_once("../utilities/authentication.php");
require_once("../models/loginModal.php");
require_once("accesscontrol.php");
require_once("notification.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
	$userInfo=array();
	$userInfo['user_first_name']=$_POST["user_first_name"];
	$userInfo['user_last_name']=$_POST["user_last_name"];
	$userInfo['user_email']=$_POST["user_email"];
	$userInfo['cpassword']=$_POST["cpassword"];
	$userInfo['user_mob']=$_POST["user_mob"];
	$userInfo['user_type_id']=2;
	$password=$_POST["user_password"];
	$userInfo['promocode']=strip_tags($_POST["promocode"]);
	if (filter_var($userInfo['user_email'], FILTER_VALIDATE_EMAIL)) 
	{
		//email is valid
		if(isset($_POST["user_first_name"]) && isset($_POST["user_last_name"]) && isset($_POST["user_email"]) && isset($_POST["user_password"]) && isset($_POST["cpassword"]))
		{
		
			if($userInfo['cpassword']!=$password)
			{
				$msg = "Passwords do not match";
				$returnArr['errCode']=2;
				$returnArr['errMsg']=$msg;	
				$redirectURL = "../views/sign_in.php";

			} 
			else if($userInfo['promocode']!='ehtesting0001')
				{
					$msg = "Invalid promocode";
				$returnArr['errCode']=2;
				$returnArr['errMsg']=$msg;	
				$redirectURL = "../views/sign_in.php";
				}else {
			
				$email=$userInfo['user_email'];
				$user=getUserInfoWithType($_POST["user_email"],2,$conn);
				
				if(noError($user)){
					$user=$user["errMsg"]; 
					$returnArr['errCode']=2;
					$msg = "User already exists.";
					$returnArr['errMsg']=$msg;	
					
					///Exist user activation link
					
					$email=$userInfo['user_email'];

				if($user['pass_code']!=="*****"){
					$com_code = generateSalt();
						
					$getRemediesQuery = "UPDATE `users` SET `pass_code`='".$com_code."' where `user_email`='".$email."'"; 
					$resultQuery=runQuery($getRemediesQuery,$conn);

					$msg = $rootUrl."/views/verifiedlogin.php?passkey=$com_code&username=$email";
					$url=$rootUrl."/views/verifiedlogin.php?passkey=$com_code&username=$email";
					$subject="Activation Link";
					/*$message="Congratulations! You have successfully created a new account at eHeilung.\nYour username is:".$userInfo['user_email'] ."https://eheilung.com/views/verifiedlogin.php?passkey=$com_code&
username=$email";*/

					$message=  "<div style='font-family: arial,sans-serif'>"
                       ."<h4 class='btn-common' style='background-color:#0be1a5;padding:5px;margin: 0px;'>
	                  <div class='' style='display:inline;color:white;padding: 15px; font-size: 45px;'> <img style='width: 30px; height: 30px; padding-right: 10px; padding-top: 10px;' src='images/logo1.png'>eHeilung </div>
	                  </h4>"
	                  ."
	                  
	                  <p style='border:solid thin #0be1a5; padding: 15px; margin: 0px;'>
	                  Hi, 
	                  <br> 
	                  <br> 
	                  <span style='color:#0be1a5;'>Congratulations!</span>
	                  <br> <br>
	                   You have successfully created a new account at eHeilung.
	                  <br> <br> 
	                  Your username is:<span style='color:#0be1a5 !important;'>".$userInfo['user_email']."</span> 
	                  <br> 
	                  <br>
	                  Click 
	                  <a href='".$url."' style='color:#0be1a5;'>here</a> 
	                  to activate your user account.
	                  <br>
	                  </p>
	                  </div>";
					$from = "eHeilung <donotreply@eheilung.com>";
					//echo 'hi'
					echo '<a href="resendActivation.php?email='.$email.'&from='.$from.'&subject='.$subject.'&msg='.$msg.'"><button>Resend Verification</button></a>';
					$resend="notActive";
					                              
                   $msg = "User Already Exist......Your Account is not Active Please Activate it.";						

                   }else{
                   	$msg = "User Already Exist.......";
                   }									   
                    //$redirectURL = "../views/login.html";
                    $redirectURL='../'.$newUI_URL['loginSignUP'];
                    $returnArr['errCode'][8]=8;
                    $returnArr['errMsg']=$msg;
							
					
					
					
					
					
					///////
					$redirectURL = "../views/sign_in.php";	
				}
				else{
					$salt=generateSalt();
					$userInfo['salt'] = $salt;
					$userInfo['user_password'] = encryptPassword($password,$salt);
					$userInfo['user_type_id'] = 2;
					$check=insertProfileInfo($userInfo, $conn);
				
					if(noError($check))
					{
						$returnArr['errCode']=-1;
						$returnArr['errMsg']=$check["errMsg"];
            $msg = "Registered Successfully<br> Check Email for Activation link";
						//$user_id=mysql_insert_id($conn);
						$com_code = md5(uniqid(rand(), true));
						//print_r($com_code);	
						$getRemediesQuery = "UPDATE `users` SET `pass_code`='".$com_code."' where `user_email`='".$email."'"; 
   					    $resultQuery=runQuery($getRemediesQuery,$conn);

						//$message= "verifiedlogin.php?passkey=$com_code&username=$email";
						$returnArr['errMsg']=$msg;	
						$redirectURL = "../views/sign_in.php";
						$url=$rootUrl."/views/verifiedlogin.php?passkey=$com_code&username=$email";
						$subject="eHeilung Account created Successfully";
						/*$message="Congratulations! You have successfully created a new account at eHeilung.\nYour username is:".$userInfo['user_email'] ."https://eheilung.com/views/verifiedlogin.php?passkey=$com_code&username=$email";*/
						$message=  "<div style='font-family: arial,sans-serif'>"
                       ."<h4 class='btn-common' style='background-color:#0be1a5;padding:5px;margin: 0px;'>
	                  <div class='' style='display:inline;color:white;padding: 15px; font-size: 45px;'> <img style='width: 30px; height: 30px; padding-right: 10px; padding-top: 10px;' src='images/logo1.png'>eHeilung </div>
	                  </h4>"
	                  ."
	                  
	                  <p style='border:solid thin #0be1a5; padding: 15px; margin: 0px;'>
	                  Hi, 
	                  <br> 
	                  <br> 
	                  <span style='color:#0be1a5;'>Congratulations!</span>
	                  <br> <br>
	                   You have successfully created a new account at eHeilung.
	                  <br> <br> 
	                  Your username is:<span style='color:#0be1a5 !important;'>".$userInfo['user_email']."</span> 
	                  <br> 
	                  <br>
	                  Click 
	                  <a href='".$url."' style='color:#0be1a5;'>here</a> 
	                  to activate your user account.
	                  <br>
	                  </p>
	                  </div>";
						$from = "eHeilung <donotreply@eheilung.com>";
						$return_arr=sendMail($userInfo["user_email"], $from, $subject, $message);
         				}
            		else {
							$returnArr['errCode']=3;
							$msg = "Failed to register";
							$returnArr['errMsg']=$msg;	
							$redirectURL = "../views/sign_in.php";
					}
				}
			}
		}
		else{
			$returnArr["errCode"][5]=5;
			$returnArr["errMsg"]="Mandatory Parameters not passed";
			$msg = "Mandatory Parameters not passed";
			$redirectURL = "../views/sign_in.php";
		}
	} else {
		$returnArr["errCode"][5]=5;
		$msg = "Invalid Email format";
		$returnArr["errMsg"]=$msg;
		$redirectURL = "../views/sign_in.php";
	}
	
} else {
	$returnArr['errCode']=7;
	$msg = "Could not connect to database";
    $returnArr['errMsg']=$msg;	
	$redirectURL = "../views/sign_in.php";
}

if(isset($_POST["API"]) &&  $_POST["API"]==1)
	print(json_encode($returnArr,true));
else{
	printArr($msg);
	if($resend!="notActive")
	{	
	print("<script>");
		print("var t = setTimeout(\"window.location='".$redirectURL."';\", 3000);");
	print("</script>");
	}
	
	print("<a href=".$redirectURL.">Click here if you are not redirected automatically by your browser within 3 seconds</a>");
	exit;
}
 
?>