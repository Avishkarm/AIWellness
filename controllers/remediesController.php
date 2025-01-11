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
include($pathprefix."models/remediesModel.php");
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
    if($type=='description')
    {
       $remedyN=$_POST['remedyN'];
       $check=getRemedyDescription($conn,$remedyN);
       //printArr($check);
       if(noError( $check)){
          $returnArr["errCode"] =-1 ;
          //$returnArr["errMsg"] = $check;
          $returnArr["remedy_name"]=$check['errMsg']['remedy_name'];
          $returnArr["remedy_full_name"]=$check['errMsg']['remedy_full_name'];
          $returnArr["remedy_description"]=utf8_encode($check['errMsg']['remedy_description']);
          $msg="successfully get remedy description";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
       }else{
          $msg="could not get remedy description";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          $returnArr["errCode"] =1 ;
          $returnArr["errMsg"] = $msg;
       }
    }else if($type=='prescription'){
      //printArr($_POST);
      if((isset($_POST['preference']) && !empty($_POST['preference']))){
        if((isset($_POST['followup']) && !empty($_POST['followup']))){

          $userInfo["preference"]=cleanQueryParameter($conn,cleanXSS($_POST["preference"]));
          $userInfo["followup"]=cleanQueryParameter($conn,cleanXSS($_POST["followup"]));
          $userInfo['precribe_comments']=cleanQueryParameter($conn,cleanXSS($_POST["precribe_comments"]));
          $userInfo['consult_type']=cleanQueryParameter($conn,cleanXSS($_POST["consult_type"]));
          $userInfo['periodicity']=cleanQueryParameter($conn,cleanXSS($_POST["periodicity"]));
          $userInfo['currency']=cleanQueryParameter($conn,cleanXSS($_POST["currency"]));
          $userInfo['charges']=cleanQueryParameter($conn,cleanXSS($_POST["charges"]));
          //$userInfo['potency']=cleanQueryParameter($conn,cleanXSS($_POST["potency"]));
          //$userInfo['dosage']=cleanQueryParameter($conn,cleanXSS($_POST["dosage"]));
          $userInfo['case_id']=cleanQueryParameter($conn,cleanXSS($_POST["case_id"]));
          $userInfo['remedyName']=cleanQueryParameter($conn,cleanXSS($_POST["remedyName"]));
          $addPrescribtion=addPrescribtion($userInfo,$conn);
          if(noError( $addPrescribtion)){
              $returnArr["errCode"] =-1 ;
              $returnArr["errMsg"] = $addPrescribtion['errMsg'];
              $msg="successfully added prescription";
              $xml_data['step'.++$i]["data"] = $i.". {$msg}";
           }else{
              $msg="Failed to add prescription";
              $xml_data['step'.++$i]["data"] = $i.". {$msg}";
              $returnArr["errCode"] =1 ;
              $returnArr["errMsg"] = $msg;
           }
        }else{
            $msg="Please select follow up duration";
            $xml_data['step'.++$i]["data"] = $i.". {$msg}";
            $returnArr["errCode"] =1 ;
            $returnArr["errMsg"] = $msg;
        }
     }else{
          $msg="Please select preference";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          $returnArr["errCode"] =1 ;
          $returnArr["errMsg"] = $msg;
     }
      //printArr($_POST);
    }else if($type=='dose'){
      
      $userInfo['potency']=cleanQueryParameter($conn,cleanXSS($_POST["potency"]));
      $userInfo['dosage']=cleanQueryParameter($conn,cleanXSS($_POST["dosage"]));
      $userInfo['case_id']=cleanQueryParameter($conn,cleanXSS($_POST["case_id"]));
      $addPrescribtionDose=addPrescribtionDose($userInfo,$conn);
      if(noError( $addPrescribtionDose)){
          $returnArr["errCode"] =-1 ;
          $returnArr["errMsg"] = $$addPrescribtionDose['errMsg'];
          $msg="successfully added prescription";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
       }else{
          $msg="Failed to add prescription";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          $returnArr["errCode"] =1 ;
          $returnArr["errMsg"] = $msg;
       }
      //printArr($_POST);
    }else{
      $msg="could not get post type";
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
?>