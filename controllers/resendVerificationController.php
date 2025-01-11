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
$userEmail=$_GET["email"];
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

if(isset($_GET) && !empty($_GET)){

  $email=cleanQueryParameter($conn,cleanXSS($_GET['email']));
  //$salt=cleanQueryParameter($conn,cleanXSS($_GET['passkey']));
  $user_type=$_GET['user_type'];
  //resend activation link
  $salt = generateSalt();
  
 $updateSalt = "UPDATE `users` SET `salt`='".$salt."' where `user_email`='".$email."' and `user_type_id`=".$user_type; 
  $resultQuery=runQuery($updateSalt,$conn);
  //printArr($resultQuery);die;
  $url=$rootUrl."/controllers/accountActivationController.php?user_type=".$user_type."&passkey=".$salt."&username=".$email;
  $subject="Activation Link";
  $image=$rootUrl."/assets/images/logo.png";
  /*$message=  "<div style='font-family: arial,sans-serif'>"
                   ."<h4 class='btn-common' style='background-color:#0be1a5;padding:5px;margin: 0px;'>
                <div class='' style='display:inline;color:white;padding: 15px; font-size: 45px;'> <img style='width: 30px; height: 30px; padding-right: 10px; padding-top: 10px;' src='".$rootUrl."/assets/images/logo.png'>eHeilung </div>
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
  $from = "eHeilung <donotreply@eheilung.com>";

  $sendMail=sendMail($email, $from, $subject, $message);
  //printArr($sendMail);
  if(noError($sendMail)){
    $msg= "successfully sent resend activation";
     $status="verifymailsuccess";
  }else{
    $msg= "Failed to send resend activation mail";
    $status="verifymailfailed";
  }
}else{
  $msg= "Mandatory Parameters not passed";
  $status="verifymailfailed1";
}
 $redirectURL = $rootUrl."/index.php?status=". $status;
    print("<script>");
        print("var t = setTimeout(\"window.location='".$redirectURL."';\", 000);");
    print("</script>");
      
    

?>