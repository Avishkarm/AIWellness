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
//database connection
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
    $conn = $conn["errMsg"];
} else {
    printArr("Database Error");
    exit;
}
  

$updateUser = "UPDATE `users` SET `status`='Active' where `user_type_id`='".cleanQueryParameter($conn,cleanXSS($_GET["user_type"]))."' AND salt='".cleanQueryParameter($conn,cleanXSS($_GET["passkey"]))."' AND user_email='".cleanQueryParameter($conn,cleanXSS($_GET["username"]))."'"; 
$resultQuery=runQuery($updateUser,$conn);
//printArr($resultQuery);
$email=$_GET['username'];
$user_type=$_GET['user_type'];
if(noError($resultQuery)){
    if(mysqli_affected_rows($conn) > 0){
        $status="verifysuccess";

    }else{
        $status="alreadyVerified";
    }
}else{
    $status="verifyfailed";
}

    
    
  $redirectURL = $rootUrl."/index.php?status=".$status."&user_type=".$user_type."&email=".$email;
    print("<script>");
        print("var t = setTimeout(\"window.location='".$redirectURL."';\", 000);");
    print("</script>");
      
    exit;   


?>
