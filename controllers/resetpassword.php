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
	
	$user_email=cleanQueryParameter($conn,cleanXSS($_POST['username']));
	$new_password=cleanQueryParameter($conn,cleanXSS($_POST['password']));
	$confirm_password=cleanQueryParameter($conn,cleanXSS($_POST['confirm_password']));

	if ($user_email!="" && $new_password!="" && $confirm_password!=""){
		if($new_password==$confirm_password){
			$user_info=getUserInfo($user_email, $conn);
			if(noError($user_info)){
				$user_info = $user_info["errMsg"];	
				$newPass = encryptPassword($new_password, $user_info["salt"]);
				$query = "UPDATE users SET user_password = '".$newPass."' WHERE user_email='".$user_email."'";
				$result = runQuery($query, $conn);
				
				if(noError($result)){
					$msg = "Password changed successfully. Please login with your new password.";
					//$redirectURL = "../views/login.html";
					$redirectURL="../views/sign_in.php";
					$returnArr['errCode'][-1]=-1;
					$returnArr['errMsg']=$msg;
				} else {
					$msg = "Error updating password. Please try again or contact us if the problem persists.";
					$redirectURL = $_SERVER["HTTP_REFERER"];
					$returnArr['errCode'][4]=4;
					$returnArr['errMsg']=$msg;
				}
			} else {
				$msg = "Error fetching user info: Could not find username";
				$redirectURL = $_SERVER["HTTP_REFERER"];
				$returnArr['errCode'][4]=4;
				$returnArr['errMsg']=$msg;
			}
		} else {
			$msg = "Passwords mismatch";
			$redirectURL = $_SERVER["HTTP_REFERER"];
			$returnArr['errCode'][4]=4;
			$returnArr['errMsg']=$msg;
		}
	
	} else {
		$returnArr['errCode'][4]=4;
		$msg = "All form fields are mandatory";
		$returnArr['errMsg']=$msg;	
		$redirectURL = $_SERVER["HTTP_REFERER"];
	}

} else {
	$returnArr['errCode'][7]=7;
	$msg = "Could not connect to database";
    $returnArr['errMsg']=$msg;	
	//$redirectURL = "../views/forget.html";
	$redirectURL="../views/forgot_password.php";
}


if(isset($_POST["API"]) &&  $_POST["API"]==1)
	print(json_encode($returnArr,true));
else{
	printArr($msg);
		
	print("<script>");
		print("var t = setTimeout(\"window.location='".$redirectURL."';\", 3000);");
	print("</script>");
	
	print("<a href=".$redirectURL.">Click here if you are not redirected automatically by your browser within 3 seconds</a>");
	exit;
}

?>
