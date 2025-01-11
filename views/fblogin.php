	
<?php
session_start();
require_once( '../assets/php-graph-sdk-5.5/src/Facebook/autoload.php' );
require_once("../utilities/config.php");
require_once("../utilities/dbutils.php");
require_once("../utilities/authentication.php");
include("../models/userModel.php");

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
 
$permissions = ['email']; // Optional permissions for more permission you need to send your application for review
$loginUrl = $helper->getLoginUrl($rootUrl.'/controllers/fb_callback.php?user_type='.$user_type, $permissions);
header("location: ".$loginUrl);
 

?>