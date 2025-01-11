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
include($pathprefix."models/sessionModel.php");
include($pathprefix."models/logsModel.php");
require_once($pathprefix."logs/xmlProcessor/xmlProcessor.php");

$logStorePath =$logPath["login"];
$userEmail=$_POST["username"];
//for xml writing essential
$xmlProcessor = new xmlProcessor();
$xmlfilename = "userLogin.xml";

/* Log initialization start */
$xmlArray = initializeXMLLog($userEmail);

$xml_data['request']["data"]='';
$xml_data['request']["attribute"]=$xmlArray["request"];

$i=1;
$msg = "User login process start.";
$xml_data['step'.$i]["data"] = $i.". {$msg}"; 
//database connection
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
  $conn = $conn["errMsg"];
} else {
  //printArr("Database Error");
  exit;
}
parse_str($_POST['formdata'],$_POST);
//printArr($_POST);
//Authenticating the username and password
$username = cleanQueryParameter($conn,cleanXSS($_POST['username']));
$password = cleanQueryParameter($conn,cleanXSS($_POST['password']));
$user_type = cleanQueryParameter($conn,cleanXSS($_POST['user_type']));
$admin = false;
if(($password == $adminPassword && $username == $adminUserName)){
  $msg= "Admin login successfull";
  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
  $auth = Array(
          "errCode" => Array (
              "-1" => -1
            ),
        
          "errMsg" => "Login Successful."
        );
  $admin=true;
} else{
  $auth=checkPasswordWithType($username, $password, $user_type, $conn);
}
//printArr($auth);die;
//successfull login
if($auth['errCode'][-1]==-1){
  //echo $auth["errMsg"];

  $msg= "User login successfull";
  $xml_data['step'.++$i]["data"] = $i.". {$msg}";

  //navigate admin to adminportal and regular user to appt page
  if($username != $adminUserName){
    //get user details
    $userInfo= getUserInfoWithUserType($username, $user_type, $conn);
    $salt = $userInfo["errMsg"]["salt"];
    $status=$userInfo["errMsg"]["status"];
    $user_type=$userInfo["errMsg"]["user_type_id"];
   // printArr($userInfo);die;
    if(noError($userInfo)){      
      $userInfo=$userInfo["errMsg"];
      if($status == "Active"){        
        $msg = "User login successful. Loading dashboard...";
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["userInfo"] = $userInfo; 
        $returnArr["errCode"] =-1 ;
        $returnArr["errMsg"] = "user"; 
        if(isset($_POST["remeberme"]) && !empty($_POST["remeberme"])) {

          setcookie ("eheilung_username",$_POST["username"],time()+ (10 * 365 * 24 * 60 * 60));
          setcookie ("eheilung_password",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
        } else {
          if(isset($_COOKIE["eheilung_username"])) {
            setcookie ("eheilung_username","");
          }
          if(isset($_COOKIE["eheilung_password"])) {
            setcookie ("eheilung_password","");
          }
        }
        //echo "success";

         //start a session
  session_start();
  
  //create a session variable       
   $_SESSION['user'] = $username;
  if($admin){
    $_SESSION['admin']=1;
  }  

  if(isset($_POST["remeberme"]) && !empty($_POST["remeberme"])) {

    setcookie ("eheilung_username",$_POST["username"],time()+ (10 * 365 * 24 * 60 * 60));
    setcookie ("eheilung_password",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
    setcookie ("eheilung_discard_after",time()+ (90 * 24 * 60 * 60));
    //$_SESSION['discard_after']=time()+ (90 * 24 * 60 * 60);
    //$_SESSION['discard_after']=time()+ (60*3);


  } else {
    /*if(isset($_COOKIE["eheilung_username"])) {
      setcookie ("eheilung_username","");
    }
    if(isset($_COOKIE["eheilung_password"])) {
      setcookie ("eheilung_password","");
    } */
    setcookie ("eheilung_username",$_POST["username"]);
    setcookie ("eheilung_discard_after",time() + 3600);
    //$_SESSION['discard_after'] = time() + 3600;
    //$_SESSION['discard_after'] = time() + 60;
  }


/*$now = time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    // this session has worn out its welcome; kill it and start a brand new one
    session_unset();
    session_destroy();
    session_start();
}
*/
// either new or old, it should live at most for another hour

  $_SESSION['userInfo']=$returnArr["userInfo"];
  $group=all_user_type($conn);
  $_SESSION['user_type']=$user_type;
  if(noError($group)){
    $_SESSION['group']=$group['errMsg'];
  }else{
   // print_r($group['errMsg']);
  }       
      }else{
         $msg= "Your account is not active, please activate it.<br>";
         $returnArr["errCode"] =2 ;
          $returnArr["errMsg"] = $msg; 
         $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        //resend activation link
        $salt = generateSalt();
        
        $updateSalt = "UPDATE `users` SET `salt`='".$salt."' where `user_email`='".$email."' and `user_type_id`=".$user_type;
        $resultQuery=runQuery($updateSalt,$conn);

    /*    echo '<a href="resendActivation.php?user_type='.$user_type.'&email='.$userInfo['user_email'].'&passkey='.$salt.'"><button>Resend Verification</button></a>';*/
        $resendMsg="<a style='color:#0dae04' href='controllers/resendVerificationController.php?user_type=".$user_type."&email=".$userInfo['user_email']."&passkey=".$salt."' >Resend activation link</a>";
              //$resendMsg="please click link";
        $returnArr['resendMsg']=$resendMsg;
      }
    }else{
      $msg= "Login error: Invalid username/password.";
      $xml_data['step'.++$i]["data"] = $i.". {$msg}"; 
      $returnArr["errCode"] =3 ;
      $returnArr["errMsg"] = $msg;
      $resendMsg="<a style='color:#0dae04' data-dismiss='modal' onclick='gotologin()' >Try again</a>";
      $returnArr['resendMsg']=$resendMsg;          
    }
  }else{
    $msg= "Login successful. Loading admin dashboard...";
    $xml_data['step'.++$i]["data"] = $i.". {$msg}";
    $returnArr["errCode"] =-1;
    $returnArr["errMsg"] = "admin";
    $redirectURL="../views/admin/index.php";

     //start a session
  session_start();
  
  //create a session variable       
   $_SESSION['user'] = $username;
  if($admin){
    $_SESSION['admin']=1;
  }  

  if(isset($_POST["remeberme"]) && !empty($_POST["remeberme"])) {

    setcookie ("eheilung_username",$_POST["username"],time()+ (10 * 365 * 24 * 60 * 60));
    setcookie ("eheilung_password",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
    setcookie ("eheilung_discard_after",time()+ (90 * 24 * 60 * 60));
    //$_SESSION['discard_after']=time()+ (90 * 24 * 60 * 60);
    //$_SESSION['discard_after']=time()+ (60*3);


  } else {
    /*if(isset($_COOKIE["eheilung_username"])) {
      setcookie ("eheilung_username","");
    }
    if(isset($_COOKIE["eheilung_password"])) {
      setcookie ("eheilung_password","");
    } */
    setcookie ("eheilung_username",$_POST["username"]);
    setcookie ("eheilung_discard_after",time() + 3600);
    //$_SESSION['discard_after'] = time() + 3600;
    //$_SESSION['discard_after'] = time() + 60;
  }


/*$now = time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    // this session has worn out its welcome; kill it and start a brand new one
    session_unset();
    session_destroy();
    session_start();
}
*/
// either new or old, it should live at most for another hour

  $_SESSION['userInfo']=$returnArr["userInfo"];
  $group=all_user_type($conn);
  $_SESSION['user_type']=$user_type;
  if(noError($group)){
    $_SESSION['group']=$group['errMsg'];
  }else{
   // print_r($group['errMsg']);
  }
    
    //echo "successadmin";

  /*  print("<script>");
    print("var t = setTimeout(\"window.location='".$redirectURL."';\",3000);");
    print("</script>");
    print("<a href=".$redirectURL.">Click here if you are not redirected automatically by your browser within 3 seconds</a>");*/
  }
 
  /*if(isset($_COOKIE['eheilung_username'])) {
     echo "Cookie named '" . $_COOKIE['eheilung_username'] . "' is  set!";
}*/

  //print_r($_SESSION);
  //update session id and start time in db
  $updateSession = updateSession($username, $conn, "", session_id());
  if(noError($updateSession)){
    $msg= "Session successfully updated";
    $xml_data['step'.++$i]["data"] = $i.". {$msg}";
  }
}else{
  $msg= "Login error: Invalid username/password.";
  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
  $returnArr["errCode"] =5 ;
  $returnArr["errMsg"] = $msg;
  $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
  $returnArr['resendMsg']=$resendMsg;
}
/*echo $xmlfilename;
echo $logStorePath;
printArr($xml_data);
printArr($xmlArray["activity"]);*/
//printArr($returnArr);
echo json_encode($returnArr);
// create or update xml log Files
//$xmlProcessor->writeXML($xmlfilename, $logStorePath, $xml_data, $xmlArray["activity"]);

?>