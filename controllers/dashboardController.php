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
include($pathprefix."models/dashboardModel.php");
require_once($pathprefix."logs/xmlProcessor/xmlProcessor.php");

$logStorePath =$logPath["userProfile"];
$userEmail=$_POST['user_email'];
//for xml writing essential
$xmlProcessor = new xmlProcessor();
$xmlfilename = "completeProfile.xml";

/* Log initialization start */
//$xmlArray = initializeXMLLog($userEmail);

$xml_data['request']["data"]='';
$xml_data['request']["attribute"]=$xmlArray["request"];


$msg = "User registration process start.";
$xml_data['step'.$i]["data"] = $i.". {$msg}"; 

//database connection
$conn = createDbConnection($servername, $username, $password, $dbname);
$user = "";
$returnArr=array();
$userInfo=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
  $msg = "Success : Search database connection";
  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
} else {
	printArr("Database Error");
	exit;
}
  //printArr($_SESSION);die;
 /* printArr($_FILES);
 parse_str($_POST['formdata'],$_POST);*/

//accept request
if((isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)) || (isset($_POST["user"]) && !in_array($_POST["user"], $blanks))){
  if((isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)))
    $user = $_SESSION["user"];
    $user_type=$_SESSION["user_type"];
    $userMob=$_SESSION["userInfo"]['user_mob'];
    $msg = "user is loged in";
    $xml_data['step'.++$i]["data"] = $i.". {$msg}";
    $type=$_POST['type'];
    if($type=='display_graph')
    {
      $year=$_POST['year'];
      $month=$_POST['month'];
      $date=$_POST['date'];
      $doctor_id=$_SESSION['userInfo']['user_id'];
      //printArr($_POST);
      $monthlyReport = getDoctorUserCaseMonthlyReport($conn, $doctor_id, $year, $month, $date);
      //printArr($monthlyReport);
      if(noError($monthlyReport)){
        $msg=$monthlyReport['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =1 ;
        $returnArr["errMsg"] = $monthlyReport['errMsg'];
      }else{
        $msg=$monthlyReport['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =1 ;
        $returnArr["errMsg"] = $msg;
      }
    }else{
      $msg="dint get post parameter type";
      $xml_data['step'.++$i]["data"] = $i.". {$msg}";
      $returnArr["errCode"] =1 ;
      $returnArr["errMsg"] = $msg;
    }
}else{
  $msg="You need to login first to access this page";
  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
  $returnArr["errCode"] =1 ;
  $returnArr["errMsg"] = $msg;
}
//$xmlProcessor->writeXML($xmlfilename, $logStorePath, $xml_data, $xmlArray["activity"]);
echo json_encode($returnArr);