<?php


require_once("../utilities/config.php");
require_once("../utilities/dbutils.php");
require_once("../utilities/authentication.php");
require_once("../models/loginModal.php");
require_once("accesscontrol.php");
require_once("notification.php");
require_once("session.php");

$email = $_REQUEST['email'];
$com_code = $_REQUEST['com_code']; 
/*
 $msg = $_GET['msg'];
$subject = $_GET['subject'];
$from = $_GET['from']; 
*/

  //$email = $_REQUEST['email'];

	$subject="Activation Link";
  //$url=$rootUrl."/views/verifiedlogin.php?passkey=$com_code&username=$email";
  $url=$_REQUEST['url'];
	/*$msg="Congratulations! You1 have successfully created a new account at eHeilung.\nYour username is:".$email."http://hansinfotech.in/eheilung/views/verifiedlogin.php?passkey=$com_code&username=$email";*/
  $from = "eHeilung <donotreply@eheilung.com>";
   $image=$rootUrl."/assets/images/logo.png";
  /*$msg=  "<div style='font-family: arial,sans-serif'>"
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
                    Your username is:<span style='color:#0be1a5 !important;'>".$email."</span> 
                    <br> 
                    <br>
                    Click 
                    <a href='".$url."' style='color:#0be1a5;'>here</a> 
                    to activate your user account.
                    <br>
                    </p>
                    </div>";*/
$message='<div style="width: 80%;margin: 0 auto;margin-bottom:20px;">
                <img src="'.$image.'">
                <br>
                <h1 style="color: #454545;width: 60%;font-family: arial;font-size: 35px;">Congratulations!</h1>
                <br>
                <h4 style="width:70%;color: #454545;font-family: arial;letter-spacing: 1px;font-size: 25px;">You have successfully created a new account at eHeilung.<br>Your username is:"'.$email.'"<br> Just Click on the button below to activate your user account.</h4>
                <br>
                <a href="'.$url.'" value="CLICK HERE" style="text-align: center;background-color: #0dae04;color: #fff;padding: 10px;width:280px;border-radius:20px;font-weight: bold;font-size:20px;border:none;outline: none;font-family: arial;">CLICK HERE</a>
                </div>';        
								
$send=sendMail($email, $from, $subject, $msg);

if($send)
{
  if(isset($_REQUEST["API"]) &&  $_REQUEST["API"]==1)
  {  
   $returnArr["errCode"][-1]=-1;
	  $returnArr["errMsg"] = "Activation link has been send successfully. ";
   
     

   
      print(json_encode($returnArr,true));
     
  }
  else
  {
    $msg="Activation link has been send successfully. ";
    printArr($msg);
	$redirectURL = "../views/sign_in.php";
	print("<script>");
		print("var t = setTimeout(\"window.location='".$redirectURL."';\", 3000);");
	print("</script>");
	
	print("<a href=".$redirectURL.">Click here if you are not redirected automatically by your browser within 3 seconds</a>");
	  
	exit;   
	
	}
}

else
{
	if( $_REQUEST["API"]==1)
  {
        $returnArr["errCode"] = 2 ;
					$returnArr["errMsg"] = $msg;
          print(json_encode($returnArr,true));
          die();
          
  }
  else
  {
          $msg = "Error in Sending Reactivation Link";
					$redirectURL = "../views/sign_in.php";
  }
          
}


?>