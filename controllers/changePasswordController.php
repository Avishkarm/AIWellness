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
session_start();
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


//ALERT-KDR
$user="";
$u_type="";
if(isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)){
  $user = $_SESSION["user"];
  $u_type=$_SESSION["user_type"];
} else {
   $redirectURL ="../../index.php?luser=doctor";
  header("Location:".$redirectURL); 
  exit;
}


//  $user_email=cleanQueryParameter($conn,cleanXSS($_POST['username']));
//  $new_password=cleanQueryParameter($conn,cleanXSS($_POST['password']));
//  $confirm_password=cleanQueryParameter($conn,cleanXSS($_POST['confirm_password']));
//  $user_type=cleanQueryParameter($conn,cleanXSS($_POST['user_type']));

  $user_email= $user;
  $user_type=$u_type;
  $new_password=cleanQueryParameter($conn,cleanXSS($_POST['user_password']));
  $confirm_password=cleanQueryParameter($conn,cleanXSS($_POST['cpassword']));

  if(isset($_POST['user_password']) && !empty($_POST['user_password']) && isset($_POST['cpassword']) && !empty($_POST['cpassword'])){
  if(strlen($new_password)>=8){
    if($new_password==$confirm_password){
      if (filter_var($user_email, FILTER_VALIDATE_EMAIL)){
        $user_info=getUserInfoWithUserType($user_email, $user_type, $conn);
        if(noError($user_info)){
          $user_info = $user_info["errMsg"];
          $newPass = encryptPassword($new_password, $user_info["salt"]);
          $query = "UPDATE users SET user_password = '".$newPass."' WHERE user_email='".$user_email."' and user_type_id=".$user_type;
          $result = runQuery($query, $conn);
          if(noError($result)){
            $msg= "Password changed successfully. Please login with your new password.";
            $returnArr["errCode"] =-1 ;
            $returnArr["errMsg"] = $msg;
            $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
            $returnArr['resendMsg']=$resendMsg;
          }else{
            $msg= "Error updating password.".$result['errMsg'];
            $returnArr["errCode"] =7 ;
            $returnArr["errMsg"] = $msg;
          }
        }else{
          $msg= "User does not exits";
          $returnArr["errCode"] =7 ;
          $returnArr["errMsg"] = $msg;
          $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
          $returnArr['resendMsg']=$resendMsg;
        }
      }else{
        $msg= "Invalid email format!";
        $returnArr["errCode"] =7 ;
        $returnArr["errMsg"] = $msg;
        $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
        $returnArr['resendMsg']=$resendMsg;
      }
    }else{
      $msg= "Password does not match";
      $returnArr["errCode"] =7 ;
      $returnArr["errMsg"] = $msg;
      $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
      $returnArr['resendMsg']=$resendMsg;
    }
  }else{
    $msg= "Password must have 8 characters";
    $returnArr["errCode"] =7 ;
    $returnArr["errMsg"] = $msg;
    $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
    $returnArr['resendMsg']=$resendMsg;
  }
}else{
  $msg= "Mandatory Parameters not passed";
  $returnArr["errCode"] =7 ;
  $returnArr["errMsg"] = $msg;
  $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
  $returnArr['resendMsg']=$resendMsg;
}

echo json_encode($returnArr); 

?>