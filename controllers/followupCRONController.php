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
include($pathprefix."models/followUpModel.php");
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
$getFollowupBefore=getfollowupsBefore(2,$conn); printArr($getFollowupBefore);//die;
foreach ($getFollowupBefore['errMsg'] as $key => $value) {
 $main_complaint_name=$value['complaint_name'];
 $case_id=$value['id'];
 $patient_id=$value['patient_id'];
 $doctor_id=$value['doctor_id'];
  
  //$updateFollowupDate=updateFollowupDate($followup_date,$case_id,'pending',$conn);
  //printArr($updateFollowupDate);
  $getDoctorData=getUserData($doctor_id,$conn);
  //printArr($getDoctorData);
  $getPatientData=getUserData($patient_id,$conn);
  //printArr($getPatientData);
  $doctor_email=$getDoctorData['errMsg']['user_email'];
  $patient_email=$getPatientData['errMsg']['user_email'];
  $doctor_mob=$getDoctorData['errMsg']['user_email'];
  $patient_mob=$getPatientData['errMsg']['user_email'];
  //$patient_id=$getPatientData['errMsg']['user_id'];
  $url= $rootUrl."/views/followup/followup.php?case_id=".$case_id."&patient_id=".$patient_id."&doctor_id=".$doctor_id."&complaint='".$main_complaint_name."'";
   $email=$user;
    $mailauto='eHeilung@donotreply.com';
     $subject="Case Follow Up";
      $doctorSMS = "You have upcoming follow up of patient ". ucfirst($getPatientData['errMsg']['user_first_name'])." ".ucfirst($getPatientData['errMsg']['user_last_name'])." for '".strtoupper($main_complaint_name)."'" ;
      $doctorMessage="";
        $patientMsg = "You have upcoming follow up with Dr. ". ucfirst($getDoctorData['errMsg']['user_first_name'])." ".ucfirst($getDoctorData['errMsg']['user_last_name'])." for '".strtoupper($main_complaint_name)."'" ;
    
   

  $arr=sendFollowupNotification($conn,3,$mailauto, $doctor_email,$doctor_mob,$patient_email,$patient_mob,$doctorMessage,$doctorSMS,$subject,$patientMsg);
  //printArr($arr);

}

//echo "hidfgdfgfgfi";
$getFollowup=getAllfollowups($conn);
//printArr($getFollowup);

foreach ($getFollowup['errMsg'] as $key => $value) {
 $main_complaint_name=$value['complaint_name'];
 $case_id=$value['id'];
 $patient_id=$value['patient_id'];
 $doctor_id=$value['doctor_id'];
  if($value['followup_duration']=='week'){
    $date = strtotime("+7 day");
    $followup_date= date('Y-m-d H:i:s', $date);
  }else if($value['followup_duration']=='month'){
    $date = strtotime("+1 month");
    $followup_date= date('Y-m-d H:i:s', $date);
  }else if($value['followup_duration']=='threemonth'){
    $date = strtotime("+3 month");
    $followup_date= date('Y-m-d H:i:s', $date);
  }


  $updateFollowupDate=updateFollowupDate($followup_date,$case_id,'pending',$conn);
  //printArr($updateFollowupDate);
  $getDoctorData=getUserData($doctor_id,$conn);
  //printArr($getDoctorData);
  $getPatientData=getUserData($patient_id,$conn);
  //printArr($getPatientData);
  $doctor_email=$getDoctorData['errMsg']['user_email'];
  $patient_email=$getPatientData['errMsg']['user_email'];
  $doctor_mob=$getDoctorData['errMsg']['user_email'];
  $patient_mob=$getPatientData['errMsg']['user_email'];
  //$patient_id=$getPatientData['errMsg']['user_id'];
  $url= $rootUrl."/views/followup/followup.php?case_id=".$case_id."&patient_id=".$patient_id."&doctor_id=".$doctor_id."&complaint='".$main_complaint_name."'";
   $email=$user;
    $mailauto='eHeilung@donotreply.com';
     $subject="Case Follow Up";
      $doctorSMS = "You have follow up to do of patient ". ucfirst($getPatientData['errMsg']['user_first_name'])." ".ucfirst($getPatientData['errMsg']['user_last_name'])." for '".strtoupper($main_complaint_name)."'" ;
    
        $patientMsg = "You have follow up to do with Dr. ". ucfirst($getDoctorData['errMsg']['user_first_name'])." ".ucfirst($getDoctorData['errMsg']['user_last_name'])." for '".strtoupper($main_complaint_name)."'" ;
    
/*    
     $message=  "<div style='font-family: arial,sans-serif'>"
       ."<h4 class='btn-common' style='background-color:#0be1a5;padding:5px;margin: 0px;'>
  <div class='' style='display:inline;color:white;padding: 15px; font-size: 45px;'> <img style='width: 30px; height: 30px; padding-right: 10px; padding-top: 10px;' src=VIEWS.'/images/logo1.png'>eHeilung </div>
  </h4>"
  ."
  <div style='border:solid thin #0be1a5; padding: 15px; margin: 0px;'>
  <p>
  Dear ".strtolower($userInfo['user_first_name']).",
  <br>
   Your due for a follow up about the following case
  <table style='border: 1px solid black;border-collapse: collapse;'>
    <tr>
      <th style='border: 1px solid black;'>CaseId</th>
      <th style='border: 1px solid black;'>Complaint Name</th>
      <th style='border: 1px solid black;'>Case Date</th>
    <tr>
    <tr>
      <td style='border: 1px solid black;'>".$case_id."</td>
      <td style='border: 1px solid black;'>".$main_complaint_name."</td>
      <td style='border: 1px solid black;'>".date('M j Y g:i A', strtotime($value['created_on']))."</td>
    <tr>
  </table>
  <br>
  Please 
  <a href='".$url."' style='color:#0be1a5;'>click here</a> 
  to complete the follow up.
  <br>
  </p>
  </div>
  </div>";*/

//echo "<br>";
   $doctorMessage='<div style="width: 80%;margin: 0 auto;margin-bottom:20px;">
                    <img src="'.$image.'">
                    <br>
                    <h1 style="color: #454545;width: 60%;font-family: arial;font-size: 35px;">You have a follow up to do for patient '.ucfirst($getPatientData['errMsg']['user_first_name']).' '.ucfirst($getPatientData['errMsg']['user_last_name']).'</h1>
                    <br>
                     <table style="border: 1px solid black;border-collapse: collapse;">
                    <tr>
                      <th style="border: 1px solid black;">CaseId</th>
                      <th style="border: 1px solid black;">Complaint Name</th>
                      <th style="border: 1px solid black;">Case Date</th>
                    <tr>
                    <tr>
                      <td style="border: 1px solid black;">'.$case_id.'</td>
                      <td style="border: 1px solid black;">'.strtoupper($main_complaint_name).'</td>
                      <td style="border: 1px solid black;">'.date("M j Y g:i A", strtotime($value["created_on"])).'</td>
                    <tr>
                  </table>
                  <br>
                    <h4 style="width:70%;color: #454545;font-family: arial;letter-spacing: 1px;font-size: 25px;">Please do a follow up to track a progress of patient. </h4>
                    <br>

                    <a href="'.$url.'" value="Follow up" style="text-align: center;background-color: #0dae04;color: #fff;padding: 10px;width:280px;border-radius:20px;font-weight: bold;font-size:20px;border:none;outline: none;font-family: arial;">FOLLOW UP</a>
                    </div>';   

  $arr=sendFollowupNotification($conn,3,$mailauto, $doctor_email,$doctor_mob,$patient_email,$patient_mob,$doctorMessage,$doctorSMS,$subject,$patientMsg);
  //printArr($arr);

}


?>