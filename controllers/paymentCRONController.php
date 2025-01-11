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
include($pathprefix."models/paymentModel.php");
include($pathprefix."models/notificationModel.php");
include($pathprefix."models/logsModel.php");
require_once($pathprefix."logs/xmlProcessor/xmlProcessor.php");

$logStorePath =$logPath["userProfile"];
//$userEmail=$_POST['user_email'];
//for xml writing essential
$xmlProcessor = new xmlProcessor();
$xmlfilename = "completeProfile.xml";

/* Log initialization start */
$xmlArray = initializeXMLLog($userEmail);

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
//get all followups in upcoming 2

//echo "hidfgdfgfgfi";
$getDoctorsPlanDetails=getDoctorPlanDetails($conn);
//printArr($getDoctorsPlanDetails);
foreach ($getDoctorsPlanDetails['errMsg'] as $key => $value) {
 
  //$updatePlanStatus=updatePlanStatus($value['user_id'],$conn);
 
  //$patient_id=$getPatientData['errMsg']['user_id'];
  $url= $rootUrl."/index.php??luser=doctor";
   $email=$value['user_email'];
    $mailauto='eHeilung@donotreply.com';
     $subject="Eheilung plan expired";
      /*$doctorSMS = "You have follow up to do of patient ". ucfirst($getPatientData['errMsg']['user_first_name'])." ".ucfirst($getPatientData['errMsg']['user_last_name'])." for '".strtoupper($main_complaint_name)."'" ;
    
        $patientMsg = "You have follow up to do with Dr. ". ucfirst($getDoctorData['errMsg']['user_first_name'])." ".ucfirst($getDoctorData['errMsg']['user_last_name'])." for '".strtoupper($main_complaint_name)."'" ;*/
    

    $message='<div style="width: 80%;margin: 0 auto;margin-bottom:20px;">
                    <img src="'.$image.'">
                    <br>
                    <h1 style="color: #454545;width: 60%;font-family: arial;font-size: 35px;">Extend the account usage period</h1>
                    <br>
                    <h4 style="width:70%;color: #454545;font-family: arial;letter-spacing: 1px;font-size: 25px;">Your 90-day free trial is over.Continue helping your patients, paying for your account on a monthly rate with the best price offers</h4>
                    <br>
                    <a href="'.$url.'" value="Pay now" style="text-align: center;background-color: #0dae04;color: #fff;padding: 10px;width:280px;border-radius:20px;font-weight: bold;font-size:20px;border:none;outline: none;font-family: arial;">PAY NOW</a>
                    </div>';                    

  
  $sendMail=sendMail($email, $mailauto, $subject, $message);
      if(noError($sendMail)){        
        $returnArr['errMsg'].="Success";
        $returnArr['errCode'][-1]=-1;
      }else{
        $returnArr['errCode'][1]=1;
        $returnArr['errMsg'].=$sendMail['errMsg'];
      }
  //printArr($arr);

}


?>