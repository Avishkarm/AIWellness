<?php


require_once("../utilities/config.php");
require_once("../utilities/dbutils.php");
require_once("../utilities/authentication.php");
require_once("../models/userModel.php");
require_once("session.php");

//connecting to the db

$conn = createDbConnection($servername, $username, $password, $dbname);

if(noError($conn)){


	$conn = $conn["errMsg"];	
	//Authenticating the username and password
	$username = cleanQueryParameter($conn,$_POST['username']);
	
	$password = cleanQueryParameter($conn,$_POST['password']);
	$url1=$_POST['url1'];
	$admin = false;
	if(($password == $adminPassword && $username == $adminUserName)){

		$auth = Array(
					"errCode" => Array (
							"-1" => -1
						),
				
					"errMsg" => "Login Successful."
				);
		$admin=true;
	} 
	else 
	{

		 //$auth = checkPasswordWithType($username, $password, 3, $conn);
		$auth=checkPassword($username, $password, $conn);
	
	}
	
//printArr($auth['errCode'][-1]);
	if($auth['errCode'][-1]==-1)
	{
		//login successful
		$msg = $auth["errMsg"];
		//navigate admin to adminportal and regular user to appt page
		if($username != $adminUserName)
		{
					//get user details
					//$userInfo = getUserInfoWithType($username, 3, $conn);
					$userInfo= getUserInfo($username, $conn);
					$pass_code = $userInfo["errMsg"]["pass_code"];
					$user_type_id=$userInfo["errMsg"]["user_type_id"];
					
				  if(noError($userInfo))
					{
						$userInfo = $userInfo["errMsg"];	
						if($pass_code == "*****")
						{ 

						   //sett success/error messages
								$msg = "Login Successful. Loading Dashboard...";
								//$redirectURL = "../views/dashboard.php";
								if($user_type_id == 2){
								$redirectURL="../".$newUI_URL['doctor_caseHistory'];
								}
								else if($user_type_id == 3){
									if(!empty($url1))
									{
									$redirectURL="../views/caseHistory.php?url1=".$url1;
									}
									else
									{
										$redirectURL="../views/caseHistory.php";	
									}
								}
								$returnArr['errCode']=-1;
								$returnArr['errMsg']=$msg;
								$returnArr["userInfo"] = $userInfo;		
						}
						else{
							
							  //echo "<a href="javascript:;"  onclick="sendMail();">Resend Activation Link</a>";
								
								//print_r($com_code);
								//echo "<a href='$return_arr'>Click on Link to Activate Your Account</a>";
								$email=$userInfo['user_email'];
								$com_code = md5(uniqid(rand(), true));
									
								$getRemediesQuery = "UPDATE `users` SET `pass_code`='".$com_code."' where `user_email`='".$email."'"; 
								$resultQuery=runQuery($getRemediesQuery,$conn);
    
								$msg ="../views/verifiedlogin.php?passkey=$com_code&username=$email";
								
								$subject="Activation Link";
								$message="Congratulations! You have successfully created a new account at eHeilung.\nYour username is:".$userInfo['user_email']."../views/verifiedlogin.php?passkey=$com_code&username=$email";
								$from = "eHeilung <donotreply@eheilung.com>";
							
                
			                if(isset($_POST["API"]))
			                {
			                   $msg="Your Account is not active,Please Activate it.";
			                   //$redirectURL = "../views/login.html";
			                   $redirectURL='../'.$newUI_URL['loginSignUP'];
					           $returnArr['errCode'][9]=9;
						       $returnArr['errMsg']['msg']=$msg;
						       $returnArr['errMsg']['email']=$email;
						       $returnArr['errMsg']['com_code']=$com_code;
						      // $returnArr['errMsg']['subject']=$subject;
			                }
			                else
			                {
			                  echo '<a href="resendActivation.php?email='.$email.'&from='.$from.'&subject='.$subject.'&msg='.$msg.'&com_code='.$com_code.'"><button>Resend Verification</button></a>';
			                }
											
			               //sendMail($userInfo["user_email"], $from, $subject, $msg);
			               //echo "<span onclick='send()'>Resend Verification</span>";
						   
						 
						   		
						    $msg = "Your Account is not Active Please Activate it.";	
			               //$redirectURL = "../views/login.html";
			               // $returnArr['errCode'][8]=8;
			                //$returnArr['errMsg']=$msg;
						}

								
								
								
					}					
						
				  else
					 {
						//login error
						$msg = "Login Error: Invalid username/password.";	
						//$redirectURL = "../views/login.html";
						$redirectURL='../'.$newUI_URL['loginSignUP'];
						$returnArr['errCode'][7]=7;
						$returnArr['errMsg']=$msg;
					 }	
		} 
		
		else 
		{
		
			//$redirectURL = "../views/admin/index.php";
			$redirectURL = "../views/admin/index.php";
		}
		
		//start a session
		session_start();
		
		//create a session variable				
		 $_SESSION['user'] = $username;
		if($admin){
			$_SESSION['admin']=1;
		}

		$_SESSION['userInfo']=$returnArr["userInfo"];
		$group=all_user_type($conn);
		if(noError($group)){
			$_SESSION['group']=$group['errMsg'];
		}else{
			print_r($group['errMsg']);

		}
		//update session id and start time in db
		$updateSession = updateSession($username, $conn, "", session_id());
		
	} 
	
	else {
		//login error
		$msg = "Login Error: Invalid username/password.";	
		//$redirectURL = "../views/login.html";
		$redirectURL='../'.$newUI_URL['loginSignUP'];
		$returnArr['errCode'][7]=7;
		$returnArr['errMsg']=$msg;	
	}
} 


else {
	$returnArr['errCode'][7]=7;
	$msg = "Could not connect to database";
    $returnArr['errMsg']=$msg;	
	//$redirectURL = "../views/login.html";
	$redirectURL='../'.$newUI_URL['loginSignUP'];
}








if(isset($_POST["API"]) &&  $_POST["API"]==1){
	unset($userInfo["password"]);
	unset($userInfo["salt"]);				
	//$returnArr["userInfo"] = $userInfo;
	print(json_encode($returnArr,true));
} else{
	printArr($msg);
		
	print("<script>");
		print("var t = setTimeout(\"window.location='".$redirectURL."';\",3000);");
	print("</script>");
	
	print("<a href=".$redirectURL.">Click here if you are not redirected automatically by your browser within 3 seconds</a>");
	exit;
}

?>