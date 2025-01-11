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
//database connection
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
  $conn = $conn["errMsg"];
} else {
  printArr("Database Error");
  exit;
}
parse_str($_POST['formdata'],$_POST);
//printArr($_POST);
if(isset($_POST) && !empty($_POST)){
 $email=cleanQueryParameter($conn,cleanXSS($_POST['inputEmail']));
$user_type=$_POST['user_type'];
  if (filter_var($email, FILTER_VALIDATE_EMAIL)){
    $user_info=getUserInfoWithUserType($email, $user_type, $conn);
    if(noError($user_info)){
      $user_info = $user_info["errMsg"];
      if($user_info['user_email']==$email){
        if($user_info['status']=="Active"){
          $user=array();        
          $time = time();       
          $oldpassword = hash('sha256', $user_info['user_password']);
          $token = "t={$time}&i={$email}&o={$oldpassword}";
          $verification = hash_hmac('sha256', $token, $user_info['salt']);
          $userid=base64_encode($email);
          $verified=$rootUrl."/index.php?status=forgotPass&email=".$email."&user_type=".$user_type;
           $image=$rootUrl."/assets/images/logo.png";
          $subject="Reset Password for eHeilung";
          /*$message=  "<div style='font-family: arial,sans-serif'>"
                         ."<h4 class='btn-common' style='background-color:#0be1a5;padding:5px;margin: 0px;'>
                    <div class='' style='display:inline;color:white;padding: 15px; font-size: 45px;'> <img style='width: 30px; height: 30px; padding-right: 10px; padding-top: 10px;' src='".$rootUrl."/assets/images/logo.png'>eHeilung </div>
                    </h4>"
                    ."
                    
                    <p style='border:solid thin #0be1a5; padding: 15px; margin: 0px;'>
                    Dear ".$user_info['user_first_name'].", 
                    <br> 
                    <br> 
                    A request to change account password has been initiated.
                    <br>
                    If it wasn't you who initiated this request then please ignore this email.
                    <br><br> 
                    If it was you,
                    <br> 
                    Please click 
                    <a href='".$verified."' style='color:#0be1a5;'>here</a> 
                    to reset your password.
                    <br>
                    </p>
                    </div>";
          */
                    

          $message='<div style="width: 80%;margin: 0 auto;margin-bottom:20px;">
                    <img src="'.$image.'">
                    <br>
                    <h1 style="color: #454545;width: 60%;font-family: arial;font-size: 35px;">One short step to reset your password</h1>
                    <br>
                    <h4 style="width:70%;color: #454545;font-family: arial;letter-spacing: 1px;font-size: 25px;">We will help you to quickly restore your password. Just Click on the button below and follow simple instructions</h4>
                    <br>
                    <a href="'.$verified.'" value="RESET PASSWORD" style="text-align: center;background-color: #0dae04;color: #fff;padding: 10px;width:280px;border-radius:20px;font-weight: bold;font-size:20px;border:none;outline: none;font-family: arial;">RESET PASSWORD</a>
                    </div>';                     
          $to = $email;
          $from = "eHeilung <donotreply@eheilung.com>";
           //echo "hii";die;
          $sendMail=sendMail($to, $from, $subject,$message);
          //echo "hii2"; 
          if(noError($sendMail)){
            $msg = "Password reset link sent successfully";
            $returnArr["errCode"] =-1 ;
            $returnArr["errMsg"] = $msg;
          }else{
           $msg = "Failed to send password reset link";
           $returnArr["errCode"] =2 ;
           $returnArr["errMsg"] = $msg;
           $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
           $returnArr['resendMsg']=$resendMsg;
          }
        }else{
          $msg =  "User not active please activate your account first";
          $returnArr["errCode"] =3 ;
          $returnArr["errMsg"] = $msg;
          $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
          $returnArr['resendMsg']=$resendMsg;
        }

      }else{
        $msg =  "User does not exits";
        $returnArr["errCode"] =4 ;
        $returnArr["errMsg"] = $msg;
        $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
        $returnArr['resendMsg']=$resendMsg;
      }
    }else{
      $msg =  "Error fetching user info: Could not find username";
      $returnArr["errCode"] =5;
      $returnArr["errMsg"] = $msg;
      $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
      $returnArr['resendMsg']=$resendMsg;

    }
  }else{
    $msg =  "Invalid email format!";
    $returnArr["errCode"] =6 ;
    $returnArr["errMsg"] = $msg;
    $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
    $returnArr['resendMsg']=$resendMsg;
  }
}else{
  $msg =  "Mandatory Parameters not passed";
  $returnArr["errCode"] =7 ;
  $returnArr["errMsg"] = $msg;
  $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
  $returnArr['resendMsg']=$resendMsg;
}

echo json_encode($returnArr); 
?>