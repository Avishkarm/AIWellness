<?php
//$activeHeader = "2opinion"; 
$pathPrefix="../";
$activeHeader = "doctorsArea"; 

session_start();
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php"); 
require_once("../../models/userModel.php");
require_once("../../models/dashboardModel.php");
require_once("../../models/admin/planModel.php");
  //database connection handling

$blogurl="http://192.168.1.103/hansinfo_eheilung/wordpress";
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
  $conn = $conn["errMsg"];
} else {
      //printArr("Database Error");
  exit;
}

//printArr($_SESSION);
$now=time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    // this session has worn out its welcome; kill it and start a brand new one
   /* session_unset();
    session_destroy();
    session_start();*/
    printArr("You do not have sufficient privileges to access this page<br>Login to continue <a href='".$rootUrl."/controllers/logout.php'>Home</a> ");
  exit;
  //echo "bye";
}else{
  //echo "hiii";
}

printArr($_POST);
?>