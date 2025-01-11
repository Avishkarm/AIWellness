<?php 

if($activeHeader=="2opinion" || $activeHeader=='knowledge_center' || $activeHeader=="doctorsArea"){
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
include($pathprefix."models/startCaseModel.php");
include($pathprefix."models/logsModel.php");
require_once($pathprefix."logs/xmlProcessor/xmlProcessor.php");

$logStorePath =$logPath["managePatient"];
$userEmail=$_SESSION["user"];
//for xml writing essential
$xmlProcessor = new xmlProcessor();
$xmlfilename = "managePatient.xml";

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

//accept request
if((isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)) || (isset($_POST["user"]) && !in_array($_POST["user"], $blanks))){
  if((isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)))
    $user = $_SESSION["user"];
    $user_type=$_SESSION["user_type"];
    $userMob=$_SESSION["userInfo"]['user_mob'];
    $msg = "Manadatory parameter passed";
    $xml_data['step'.++$i]["data"] = $i.". {$msg}";
    $returnArr["errCode"] =2 ;
    $returnArr["errMsg"] = $msg;
/* post parameter check start*/
  if (!empty($_REQUEST['data'])) {
    $dt=$_REQUEST['data'];
    if($dt['info']=='systemAdd'){
          $comp=cleanQueryParameter($conn,cleanXSS($dt['comp']));;
          //PrintArr($comp);
        $systemdetail= getComplaintsSystem($conn,$comp);
        //printArr($systemdetail);
        $returnVal=array();
        if(noError($systemdetail)){
           $returnVal['errMsg']=$systemdetail['errMsg'];
           $returnVal['errCode']=-1;
        }
        else{
           $returnVal['errMsg']=$systemdetail['errMsg'];
           $returnVal['errCode']=5;
         }
        
      echo json_encode($returnVal);
      exit;        //code
    }
      
  }else if($_POST['type']="pathalogicalProcessAdd"){    
    $comp=cleanQueryParameter($conn,cleanXSS($_POST['comp']));
    $complaintdetail= getComplaintsSystem($conn,$comp);
    //printArr($complaintdetail);
    echo json_encode($complaintdetail);
    exit;
  }
  /*post parameter check ends*/  
}else{
  $msg="You need to login first to access this page";
  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
  $returnArr["errCode"] =1 ;
  $returnArr["errMsg"] = $msg;
}  
  //accept request ends  