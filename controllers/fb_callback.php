<?php
session_start();
require_once( '../assets/php-graph-sdk-5.5/src/Facebook/autoload.php' ); 
 
require_once("../utilities/config.php");
require_once("../utilities/dbutils.php");
require_once("../utilities/authentication.php");
include("../models/userModel.php");
include("../models/sessionModel.php");

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

$fb = new Facebook\Facebook([
  'app_id' => '275536566188915',
  'app_secret' => '86f24850bae20e40328daa20b6c3d272',
  'default_graph_version' => 'v2.9',
  'default_access_token' => '275536566188915|86f24850bae20e40328daa20b6c3d272'
]); 
$user_type=$_GET['user_type'];
$helper = $fb->getRedirectLoginHelper();  
  
try {   
  $accessToken = $helper->getAccessToken();  
} catch(Facebook\Exceptions\FacebookResponseException $e) {  
  // When Graph returns an error  
  
  echo 'Graph returned an error: ' . $e->getMessage();  
  exit;  
} catch(Facebook\Exceptions\FacebookSDKException $e) {  
  // When validation fails or other local issues  
 
  echo 'Facebook SDK returned an error: ' . $e->getMessage(); 
  var_dump($helper->getPersistentDataHandler());
 
  exit;  
}  

 
try {
  // Get the Facebook\GraphNodes\GraphUser object for the current user.
  // If you provided a 'default_access_token', the '{access-token}' is optional.
  $response = $fb->get('/me?fields=id,name,email,birthday,picture{url},gender,first_name,last_name', $accessToken->getValue());
//  print_r($response);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'ERROR: Graph ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'ERROR: validation fails ' . $e->getMessage();
  exit;
}
$returnArr=array();
$userInfo=array();
$me = $response->getGraphUser();
//print_r($me);
/*echo "Full Name: ".$me->getProperty('name')."<br>";
echo "First Name: ".$me->getProperty('first_name')."<br>";
echo "Last Name: ".$me->getProperty('last_name')."<br>";
echo "Email: ".$me->getProperty('email')."<br>";
echo "picture: ".$me->getProperty('picture')."<br>";
echo "Facebook ID: <a href='https://www.facebook.com/".$me->getProperty('id')."' target='_blank'>".$me->getProperty('id')."</a>";*/
$userInfo['user_email']=$me->getProperty('email');
$picture=$me->getProperty('picture');
$userInfo['user_image']=$picture->getProperty('url');
$userInfo['user_first_name']=$me->getProperty('first_name');
$userInfo['user_last_name']=$me->getProperty('last_name');
$userInfo['user_gender']=$me->getProperty('gender');
$array =  (array) $me->getProperty('birthday');
$userInfo['user_dob']=$array['date'];
$userInfo['user_type']=$user_type;
$userInfo['login_type']="facebook";
$userInfo['status']="Active";
//print_r($userInfo['user_dob']);
/*$array =  (array) $userInfo['user_dob'];
print_r($array['date']);
*/
$user=getUserInfoWithType($userInfo['user_email'],$userInfo['user_type'],$conn);

 if(noError($user)){
    $signUpUser=insertSocialProfileInfo($userInfo, $conn);
   
      if(noError($signUpUser)){
        $_SESSION['user'] = $userInfo['user_email'];
        if($admin){
          $_SESSION['admin']=1;
        }  

       /* if(isset($_POST["remeberme"]) && !empty($_POST["remeberme"])) {

          setcookie ("eheilung_username",$_POST["username"],time()+ (10 * 365 * 24 * 60 * 60));
          setcookie ("eheilung_password",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
          setcookie ("eheilung_discard_after",time()+ (90 * 24 * 60 * 60));


        } else {
          setcookie ("eheilung_username",$_POST["username"]);
          setcookie ("eheilung_discard_after",time() + 3600);
        }*/
        $user1=getUserInfoWithType($userInfo['user_email'],$userInfo['user_type'],$conn);
        $userInfo=$user1['errMsg'];
         $_SESSION['userInfo']=$userInfo;
        $group=all_user_type($conn);
        $_SESSION['user_type']=$user_type;
        if(noError($group)){
          $_SESSION['group']=$group['errMsg'];
        }
        setcookie ("eheilung_username",$_POST["username"]);
        setcookie ("eheilung_discard_after",time() + 3600);
        //update session id and start time in db
        $updateSession = updateSession($userInfo['user_email'], $conn, "", session_id());
        if(noError($updateSession)){
          $msg= "Session successfully updated";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        }
        $redirectURL ="../views/dashboard/doctorsDashboard.php";
        header("Location:".$redirectURL); 
        exit();

        $msg = "User login successful. Loading dashboard...";
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["userInfo"] = $userInfo; 
        $returnArr["errCode"] =-1 ;
        $returnArr["errMsg"] =$msg ;
      }else{
        $msg= "something went wrong. Failed to login";
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =7 ;
        $returnArr["errMsg"] = $msg;
        $redirectURL ="../index.php?user_type=".$user_type."&user=failed&";
        header("Location:".$redirectURL); 
        exit();
      }
  } else{
    if($user['errMsg']['login_type']=="facebook"){

      $_SESSION['user'] = $userInfo['user_email'];
        if($admin){
          $_SESSION['admin']=1;
        }  

       /* if(isset($_POST["remeberme"]) && !empty($_POST["remeberme"])) {

          setcookie ("eheilung_username",$_POST["username"],time()+ (10 * 365 * 24 * 60 * 60));
          setcookie ("eheilung_password",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
          setcookie ("eheilung_discard_after",time()+ (90 * 24 * 60 * 60));


        } else {
          setcookie ("eheilung_username",$_POST["username"]);
          setcookie ("eheilung_discard_after",time() + 3600);
        }*/
        $userInfo=$user['errMsg'];
         $_SESSION['userInfo']=$userInfo;
        $group=all_user_type($conn);
        $_SESSION['user_type']=$user_type;
        if(noError($group)){
          $_SESSION['group']=$group['errMsg'];
        }
        setcookie ("eheilung_username",$_POST["username"]);
        setcookie ("eheilung_discard_after",time() + 3600);
        //update session id and start time in db
        $updateSession = updateSession($userInfo['user_email'], $conn, "", session_id());
        if(noError($updateSession)){
          $msg= "Session successfully updated";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        }
        $redirectURL ="../views/dashboard/doctorsDashboard.php";
        header("Location:".$redirectURL); 
        exit();
 
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
         $redirectURL ="../index.php?user_type=".$user_type."&user=exist&";
        header("Location:".$redirectURL); 
        exit();
    }
  }
//echo $userInfo['user_dob']->'date';
//348391152393-shjsfrf02j2ot8h91kom6kqj4kg6913p.apps.googleusercontent.com

//b-61_Bs_KkQAhoy-LrLPedMh
//echo json_encode($returnArr);
?>