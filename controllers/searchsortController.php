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
include($pathprefix."models/userModel.php");
include($pathprefix."models/managePatientModel.php");
require_once($pathprefix."models/followUpModel.php");
include($pathprefix."models/patientCaseHistoryModel.php");
include($pathprefix."models/notificationModel.php");
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
//printArr($_POST);
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
  if(isset($_POST) && !empty($_POST)){
 /* types of post taken filter*/   
    if($_POST['type'] == 'filter'){
          if((isset($_POST['filterLabel']) && !empty($_POST['filterLabel'])) || (isset($_POST['filterName']) && !empty($_POST['filterName']))){
            $userInfo['filterLabel']=cleanQueryParameter($conn,cleanXSS($_POST["filterLabel"]));
            $userInfo['filterName']=cleanQueryParameter($conn,cleanXSS($_POST["filterName"]));
            $userInfo['doctor_id']=$_SESSION['userInfo']['user_id'];
            $pageNo=$_POST['pageNo'];
            $limit=$_POST['limit'];
           // printArr($_POST); 
            //$userInfo['otherFilter']=cleanQueryParameter($conn,cleanXSS($_POST["otherFilter"]));
           /* $searchPatients=searchPatients($userInfo,$conn);
            $returnArr["errCode"] =2 ;
            $returnArr["errMsg"] = $searchPatients;*//*
            echo json_encode($searchPatients);*/
            
            $getDemo=getDemo($conn,$userInfo, $pageNo, $limit);
   // printArr($getDemo);
           // foreach ($getDemo as $key => $value) {
              # code...
            //  printArr($_POST);
  /* <!-- NEw managepatient section starts --> */
    if(!empty($getDemo['errMsg'])){

?>
<!--  <div class="main_div"> -->
    <div class="row noleft-right" >
      <div class="col-md-12 col-sm-12 col-xs-12 ">
        
      <!-- 1 Patient -->
      <?php foreach($getDemo['errMsg'] as $patientsId=>$patientDetails) {
             // printArr($patientDetails);
              $patient_id=$patientDetails['patient_id'];
              $doctor_id=$patientDetails['doctor_id'];
              $doctor_patient_id=$patientDetails['doctor_patient_id'];
              //$getUserInfoWithUserId=getUserInfoWithUserId($patient_id,3,$conn);
              //$getUserInfoWithUserId=$getUserInfoWithUserId['errMsg'];
              //printArr($getUserInfoWithUserId);
        ?>

        <div class="patient-info">
          <div class="row noleft-right">

            <div class="col-md-offset-9 col-md-3 col-sm-offset-8 col-sm-4 col-xs-12 actionimg">
              <img src="../../assets/images/remove.png" onclick="deletePatientInfo(<?php echo $patient_id.','.$doctor_patient_id ;?>);" />
              <img src="../../assets/images/tag.png" onclick="editPatientLabelInfo(<?php echo $patient_id.','.$doctor_patient_id ;?>);" />
              <img src="../../assets/images/pen.png" onclick="editPatientInfo(<?php echo $patient_id.','.$doctor_patient_id ;?>);" />
              
            </div>
            
            <div class="col-md-2 col-xs-12 status" >
              <div class="patient-img">
                <?php if(!empty($patientDetails['user_image'])){ ?>
                <img src="<?php echo $patientDetails['user_image']; ?>" class="img-circle" />
                <?php } else { ?>
                <img src="../../assets/images/cam.png" class="img-circle" />
                <?php } ?>
              </div>  
              <?php
              if($patientDetails['label']=='vip'){
                $label='Vip';
              }else if($patientDetails['label']=='emergency'){
                $label='Emergency';
              }else if($patientDetails['label']=='unimportant'){
                $label='Unimportant';
              }else if($patientDetails['label']=='improving'){
                $label='Improving';
              }else if($patientDetails['label']=='notimproving'){
                $label='Not Improving';
              }else if($patientDetails['label']=='discuss'){
                $label='To Discuss';
              }

              ?>
              <label class="<?php echo $patientDetails['label'];?>" style="cursor:auto;display:inline-block;"><?php echo $label;?></label>
            </div>

            <div class="col-md-3 col-xs-12">
              <h1><?php echo ucfirst(strtolower($patientDetails['user_first_name'])).' '.ucfirst(strtolower($patientDetails['user_last_name'])); ?></h1>
              <h2><?php if($patientDetails['user_email']!='dummy'.$patientDetails['user_mob'].'@eHeilung.com') { echo $patientDetails['user_email']; } ?></h2>
              <h2><?php echo $patientDetails['user_mob'];?></h2>
            </div>

            <?php $getRecentCaseOfUser=getRecentCaseOfUser($patient_id,$doctor_id,$conn);
            //printArr($getRecentCaseOfUser);
                  if(noError($getRecentCaseOfUser)){
                    $getRecentCaseOfUser=$getRecentCaseOfUser['errMsg'];
                  }else{
                    printArr('No data found');
                  }
                 
             ?>
            <div class="col-md-4 col-xs-12 desk-paddleft">
              <h1 style="word-break: break-word;"><?php echo $getRecentCaseOfUser['complaint_name']; ?></h1>
              <?php if(!empty($getRecentCaseOfUser['created_on'])){ ?>
              <h3>Start date: <span><?php if(!empty($getRecentCaseOfUser['created_on'])) echo date('d/m/Y', strtotime($getRecentCaseOfUser['created_on'])); ?></span></h3>
               <?php } if(!empty($getRecentCaseOfUser['primary_prescription'])){ ?>
                  <div  class="presc-label">
                   <h3>Prescriptions: </h3>
                  </div>
                  <div class="presc-list">
                    <ul class="prescriptions">
                      <li><?php echo $getRecentCaseOfUser['primary_prescription']; ?></li>
                      <li><?php echo $getRecentCaseOfUser['second_prescription']; ?></li>
                      <li><?php echo $getRecentCaseOfUser['third_prescription ']; ?></li>
                    </ul>
                  </div>
                  <?php } ?>

            </div>

            <div class="col-md-3 col-xs-12 casebtns">
              <input type="button" value="CASE HISTORY" class="casehistory-btn" onclick="patientCaseHistory(<?php echo $patient_id.','.$doctor_patient_id ;?>);">
              <input type="button" value="START CASE" class="startcase-btn" onclick="startCase(<?php echo $patient_id.','.$doctor_id ;?>);">
              <input type="button" value="PRIVATE NOTES" class="privatenotes-btn" onclick="editPatientNotesInfo(<?php echo $patient_id.','.$doctor_patient_id ;?>);">
            </div>



          </div>
        </div>
        <?php } ?>

      <!-- 1st Patient End -->



      </div>
    </div>


    <div class="row managepatient-filter noleft-right" >
      <div class="col-md-12">
        <input type="button" class="addUser" value="ADD PATIENT" data-toggle="modal" data-target="#addpatient" style="margin-top: 60px;">
      </div>
    </div>
    <!-- Paggination -->


      <div class="row noleft-right">
        <div class="col-md-12" style="text-align: center;">
          <div class="pagination">

          <?php 

          $totalItems=$getDemo['countCases'];
          $totalPageNo=ceil($totalItems/10);
           if ($pageNo > 1) { ?>
                    <a style="cursor:pointer;" onclick="sortFunction('<?php echo $userInfo['filterLabel']; ?>','<?php echo $userInfo['filterName']; ?>',<?php echo $pageNo-1; ?>,<?php echo $limit; ?>)" ><i class="fa fa-angle-double-left "></i></a>
          <?php }else if ($pageNo == 1 || $totalPageNo == 1) { ?>
                    <a style="opacity:0.5;"> <i class="fa fa-angle-double-left "></i></a>
          <?php }
                if ($pageNo == 1) {
                    $startLoop = 1;
                    $endLoop = ($totalPageNo < 4) ? $totalPageNo : 4;
                } else if ($pageNo == $totalPageNo) {
                    $startLoop = (($totalPageNo - 4) < 1) ? 1 : ($totalPageNo - 4);
                    $endLoop = $totalPageNo;
                } else {
                    $startLoop = (($pageNo - 2) < 1) ? 1 : ($pageNo - 2);
                    $endLoop = (($pageNo + 2) > $totalPageNo) ? $totalPageNo : ($pageNo + 2);
                } 
                $i=0;
                for ($i = $startLoop; $i <= $endLoop; $i++) {
                    if ($i == $pageNo) { ?>
                        <a style="cursor:pointer;" onclick="sortFunction('<?php echo $userInfo['filterLabel']; ?>','<?php echo $userInfo['filterName']; ?>',<?php echo $pageNo; ?>,<?php echo $limit; ?>)" class="active"><?php echo $pageNo; ?></a>
           <?php    } else { ?>
                        <a style="cursor:pointer;" onclick="sortFunction('<?php echo $userInfo['filterLabel']; ?>','<?php echo $userInfo['filterName']; ?>',<?php echo $i; ?>,<?php echo $limit; ?>)"><?php echo $i; ?></a>
           <?php    }
                }

          ?>
            <?php if ($pageNo < $totalPageNo) {  ?>          
                    <a  onclick="sortFunction('<?php echo $userInfo['filterLabel']; ?>','<?php echo $userInfo['filterName']; ?>',<?php echo $pageNo+1; ?>,<?php echo $limit; ?>)" style="cursor:pointer;"
                      ><i class="fa fa-angle-double-right"></i></a>
                    &nbsp;
            <?php }else if($pageNo == $totalPageNo){ ?>
                   <a style="opacity:0.5;"><i class="fa fa-angle-double-right"></i></a>
                   &nbsp; 
            <?php } ?>
          </div>    
        </div>
    </div>
<!-- </div> -->
 


<?php
}else{ ?>
      <div class="noDataFound" style="text-align: center;color:grey;margin-top:10%;letter-spacing: 2px;word-spacing: 1px;">
        <h1>No matched data found</h1>
      </div>
   <?php } ?>

<!-- NEw managepatient section ends -->


<?php
  }
  } 
  /* type of filter post end */
  /* type of caseFilter starts */
  else if($_POST['type'] == 'filterCase'){
    if((isset($_POST['filterName']) && !empty($_POST['filterName']))){
        $userInfo['filterName']=cleanQueryParameter($conn,cleanXSS($_POST["filterName"]));
        $userInfo['doctor_id']=$_SESSION['userInfo']['user_id'];
        $userInfo['patient_id']=$_POST["patient_id"];
        $pageNo=$_POST['pageNo'];
        $limit=$_POST['limit'];

        $getPatientCases = getAllPatientCasesSort($conn,3,$userInfo['patient_id'],$userInfo['doctor_id'],$userInfo['filterName'],$pageNo,$limit);
        $totalItems=$getPatientCases['countCases'];
        $totalPageNo=ceil($totalItems/3);
        /*empty state if starts*/
        if(!empty($getPatientCases['errMsg'])){
        ?>
           <div class="row noleft-right" >
      <div class="col-md-12 col-sm-12 col-xs-12 ">
        

        <!-- Asthma -->
        <?php foreach ($getPatientCases['errMsg'] as $key => $value) {
              $complaint_name=$value['complaint_name']; ?>
        <div class="patient-history table-responsive">
        <h4><?php echo $complaint_name; ?></h4>
        
        <table class="table">
        
         <tr>
           <th>CASE ID</th>
           <th>CREATION DATE</th>
           <th>UPDATED DATE</th>
           <th>PRESCRIPTIONS</th>
           <th>ACTION</th>
         </tr> 

          <?php //foreach ($arr1 as $key => $value) {
          # code...
          //printArr($value);
         ?>
         <!-- <tr>
           <td><?php echo $value['id'] ; ?></td>
           <td><?php echo date('d/m/Y', strtotime($value['created_on'])); ?></td>
           <td><?php echo date('d/m/Y', strtotime($value['updated_on'])); ?></td>
           <td><?php echo $value['primary_prescription'] ; ?></td>
           <td>
            <?php if($value['step_no']==0){
                $url="../startcase/step1.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==1){
                $url="../startcase/step2.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==2){
                $url="../startcase/step3.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==3){
                $url="../startcase/step4.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==4){
                $url="../startcase/step5.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==5){
                $url="../remedies/remedies.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              } ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/capsule.png"></a>
           </td>
         </tr>  -->
         <tr>
           <td><?php echo $value['id'] ; ?></td>
           <td><?php echo date('d/m/Y', strtotime($value['created_on'])); ?></td>
           <td><?php echo date('d/m/Y', strtotime($value['updated_on'])); ?></td>
           <td><?php echo $value['primary_prescription'] ; ?></td>
           <td>
              <?php  if($value['followup_status']!='closed' && $value['step_no']==0 ) { $followupUrl="../followup/followup.php?case_id=".$value['id']."&doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&complaint='".$complaint_name."'"; ?>
              <a href="<?php echo $followupUrl; ?>"><img src="../../assets/images/calendericon.png"></a>
             <!-- <img src="../../assets/images/chat.png"> -->
            <?php }if($value['step_no']==1){
                $url="../startcase/step1.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==2){
                $url="../startcase/step2.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==3){
                $url="../startcase/step3.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==4){
                $url="../startcase/step4.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==5){
                $url="../startcase/step5.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==0){
                $url="../remedies/remedies.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              } ?>
              <?php if($value['step_no']==0){ ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/remedy.png"></a>
              <?php }else{ ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/resume.png"></a>
              <?php } ?>
           </td>
         </tr>
         <?php 

              $getcaseFollowups=getcaseFollowups($value['id'],$conn);
              //printArr($getcaseFollowups);
              foreach ($getcaseFollowups['errMsg'] as $key1 => $value1) {
        ?>
         <tr>
           <td><?php echo $value1['id'] ; ?></td>
           <td><?php echo date('d/m/Y', strtotime($value1['date'])); ?></td>
           <td><?php echo date('d/m/Y', strtotime($value1['date'])); ?></td>
           <td><?php echo $value1['conclusion'] ; ?></td>
           <td>
              <!-- <?php $followupUrl="../followup/followup.php?case_id=".$value1['case_id']."&doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&complaint='".$complaint_name."'"; ?>
              <a href="<?php echo $followupUrl; ?>"><img src="../../assets/images/calendericon.png"></a> -->
             <!-- <img src="../../assets/images/chat.png"> -->
            <?php if($value['step_no']==1){
                $url="../startcase/step1.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==2){
                $url="../startcase/step2.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==3){
                $url="../startcase/step3.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==4){
                $url="../startcase/step4.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==5){
                $url="../startcase/step5.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==0){
                $url="../remedies/remedies.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              } ?>
              <?php if($value['step_no']==0){ ?>
              <!-- <a href="<?php echo $url; ?>"><img src="../../assets/images/remedy.png"></a> -->
              <?php }else{ ?>
             <!--  <a href="<?php echo $url; ?>"><img src="../../assets/images/resume.png"></a> -->
              <?php } ?>
           </td>
         </tr> 
         <?php 
            }
         ?> 
         <?php 
              $getRefCases=getRefCases($value['id'],$conn);
              //printArr($getcaseFollowups);
              foreach ($getRefCases['errMsg'] as $key2 => $value2) {
        ?>
         <tr>
           <td><?php echo $value2['id'] ; ?></td>
           <td><?php echo date('d/m/Y', strtotime($value2['created_on'])); ?></td>
           <td><?php echo date('d/m/Y', strtotime($value2['updated_on'])); ?></td>
           <td><?php echo $value2['primary_prescription'] ; ?></td>
           <td>
              <?php if($value2['followup_status']!='closed' && $value2['step_no']==0) { $followupUrl="../followup/followup.php?case_id=".$value2['id']."&doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&complaint='".$complaint_name."'"; ?>
              <a href="<?php echo $followupUrl; ?>"><img src="../../assets/images/calendericon.png"></a>
            <?php }if($value2['step_no']==1){
                $url="../startcase/step1.php?doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&case_id=".$value2['id'];
              }else if($value2['step_no']==2){
                $url="../startcase/step2.php?doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&case_id=".$value2['id'];
              }else if($value2['step_no']==3){
                $url="../startcase/step3.php?doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&case_id=".$value2['id'];
              }else if($value2['step_no']==4){
                $url="../startcase/step4.php?doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&case_id=".$value2['id'];
              }else if($value2['step_no']==5){
                $url="../startcase/step5.php?doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&case_id=".$value2['id'];
              }else if($value2['step_no']==0){
                $url="../remedies/remedies.php?doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&case_id=".$value2['id'];
              } ?>
              <?php if($value2['step_no']==0){ ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/remedy.png"></a>
              <?php }else{ ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/resume.png"></a>
              <?php } ?>
           </td>
         </tr>
         <?php 
            }
         ?> 
         <?php 
              $getcaseFollowups=getcaseFollowups($value2['id'],$conn);
              //printArr($getcaseFollowups);
              foreach ($getcaseFollowups['errMsg'] as $key1 => $value1) {
        ?>
         <tr>
           <td><?php echo $value1['id'] ; ?></td>
           <td><?php echo date('d/m/Y', strtotime($value1['date'])); ?></td>
           <td><?php echo date('d/m/Y', strtotime($value1['date'])); ?></td>
           <td><?php echo $value1['conclusion'] ; ?></td>
           <td>
              <!-- <?php $followupUrl="../followup/followup.php?case_id=".$value1['case_id']."&doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&complaint='".$complaint_name."'"; ?>
              <a href="<?php echo $followupUrl; ?>"><img src="../../assets/images/calendericon.png"></a> -->
             <!-- <img src="../../assets/images/chat.png"> -->
            <?php if($value['step_no']==1){
                $url="../startcase/step1.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==2){
                $url="../startcase/step2.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==3){
                $url="../startcase/step3.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==4){
                $url="../startcase/step4.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==5){
                $url="../startcase/step5.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==0){
                $url="../remedies/remedies.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              } ?>
              <?php if($value['step_no']==0){ ?>
              <!-- <a href="<?php echo $url; ?>"><img src="../../assets/images/remedy.png"></a> -->
              <?php }else{ ?>
              <!-- <a href="<?php echo $url; ?>"><img src="../../assets/images/resume.png"></a> -->
              <?php } ?>
           </td>
         </tr> 
         <?php 
            }
         ?> 

         <?php //} ?> 
        </table>

        </div>
 <?php } ?>

      
      </div>
    </div>
    <div class="row managepatient-filter noleft-right" >
      <div class="col-md-12">
        <input type="button" class="startCase" value="START CASE"  style="margin-top: 60px;">
      </div>
    </div>
        <!-- Paggination -->


      <div class="row noleft-right">
        <div class="col-md-12" style="text-align: center;">
          <div class="pagination">

          <?php 
           if ($pageNo > 1) { ?>
                    <a onclick="sortFunction('<?php echo $userInfo['filterName']; ?>',<?php echo $userInfo['patient_id']; ?>,<?php echo $pageNo-1; ?>,<?php echo $limit; ?>)" style="cursor:pointer;"><i class="fa fa-angle-double-left "></i></a>
          <?php }else if ($pageNo == 1 || $totalPageNo == 1) { ?>
                    <a style="opacity:0.5;"> <i class="fa fa-angle-double-left "></i></a>
          <?php }
                if ($pageNo == 1) {
                    $startLoop = 1;
                    $endLoop = ($totalPageNo < 4) ? $totalPageNo : 4;
                } else if ($pageNo == $totalPageNo) {
                    $startLoop = (($totalPageNo - 4) < 1) ? 1 : ($totalPageNo - 4);
                    $endLoop = $totalPageNo;
                } else {
                    $startLoop = (($pageNo - 2) < 1) ? 1 : ($pageNo - 2);
                    $endLoop = (($pageNo + 2) > $totalPageNo) ? $totalPageNo : ($pageNo + 2);
                } 
                $i=0;
                for ($i = $startLoop; $i <= $endLoop; $i++) {
                    if ($i == $pageNo) { ?>
                        <a onclick="sortFunction('<?php echo $userInfo['filterName']; ?>',<?php echo $userInfo['patient_id']; ?>,<?php echo $pageNo; ?>,<?php echo $limit; ?>)" style="cursor:pointer;" class="active"><?php echo $pageNo; ?></a>
           <?php    } else { ?>
                        <a onclick="sortFunction('<?php echo $userInfo['filterName']; ?>',<?php echo $userInfo['patient_id']; ?>,<?php echo $i; ?>,<?php echo $limit; ?>)" style="cursor:pointer;"><?php echo $i; ?></a>
           <?php    }
                }

          ?>
            <?php if ($pageNo < $totalPageNo) {  ?>          
                    <a onclick="sortFunction('<?php echo $userInfo['filterName']; ?>',<?php echo $userInfo['patient_id']; ?>,<?php echo $pageNo+1; ?>,<?php echo $limit; ?>)" style="cursor:pointer;"><i class="fa fa-angle-double-right"></i></a>
                    &nbsp;
            <?php }else if($pageNo == $totalPageNo){ ?>
                   <a style="opacity:0.5;"><i class="fa fa-angle-double-right"></i></a>
                   &nbsp; 
            <?php } ?>
          </div>    
        </div>
    </div>
        <?php
        }else{ 
        ?> 
            <div class="noDataFound" style="text-align: center;color:grey;margin-top:10%;letter-spacing: 2px;word-spacing: 1px;">
              <h1>No matched data found</h1>
            </div> 
        <?php
        }
         /*empty state if ends*/

    }
  }
/*case filter ends*/
  else if($_POST['type'] == 'search_cases'){
    if((isset($_POST['search']) && !empty($_POST['search']))){
        $userInfo['search']=cleanQueryParameter($conn,cleanXSS($_POST["search"]));
        $userInfo['doctor_id']=$_SESSION['userInfo']['user_id'];
        $userInfo['patient_id']=$_POST["patient_id"];
        $pageNo=$_POST['pageNo'];
        $limit=$_POST['limit'];
        $getPatientCases = getAllPatientCasesSearch($conn,3,$userInfo['patient_id'],$userInfo['doctor_id'],$userInfo['search'],$pageNo,$limit);
        $totalItems=$getPatientCases['countCases'];
        $totalPageNo=ceil($totalItems/3);
        /*empty state if starts*/
        if(!empty($getPatientCases['errMsg'])){
        ?>
           <div class="row noleft-right" >
      <div class="col-md-12 col-sm-12 col-xs-12 ">
        

        <!-- Asthma -->
        <?php foreach ($getPatientCases['errMsg'] as $key => $value) {
              $complaint_name=$value['complaint_name']; ?>
        <div class="patient-history">
        <h4><?php echo $complaint_name; ?></h4>
        
        <table class="table">
        
         <tr>
           <th>CASE ID</th>
           <th>CREATION DATE</th>
           <th>UPDATED DATE</th>
           <th>PRESCRIPTIONS</th>
           <th>ACTION</th>
         </tr> 

          <?php //foreach ($arr1 as $key => $value) {
          # code...
          //printArr($value);
         ?>
         <!-- <tr>
           <td><?php echo $value['id'] ; ?></td>
           <td><?php echo date('d/m/Y', strtotime($value['created_on'])); ?></td>
           <td><?php echo date('d/m/Y', strtotime($value['updated_on'])); ?></td>
           <td><?php echo $value['primary_prescription'] ; ?></td>
           <td>
            <?php if($value['step_no']==0){
                $url="../startcase/step1.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==1){
                $url="../startcase/step2.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==2){
                $url="../startcase/step3.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==3){
                $url="../startcase/step4.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==4){
                $url="../startcase/step5.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==5){
                $url="../remedies/remedies.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              } ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/capsule.png"></a>
           </td>
         </tr> -->
         <tr>
           <td><?php echo $value['id'] ; ?></td>
           <td><?php echo date('d/m/Y', strtotime($value['created_on'])); ?></td>
           <td><?php echo date('d/m/Y', strtotime($value['updated_on'])); ?></td>
           <td><?php echo $value['primary_prescription'] ; ?></td>
           <td>
              <?php  if($value['followup_status']!='closed' && $value['step_no']==0 && $value['status']==1) { $followupUrl="../followup/followup.php?case_id=".$value['id']."&doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&complaint='".$complaint_name."'"; ?>
              <a href="<?php echo $followupUrl; ?>"><img src="../../assets/images/calendericon.png"></a>
             <!-- <img src="../../assets/images/chat.png"> -->
            <?php }if($value['step_no']==1){
                $url="../startcase/step1.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==2){
                $url="../startcase/step2.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==3){
                $url="../startcase/step3.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==4){
                $url="../startcase/step4.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==5){
                $url="../startcase/step5.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==0){
                $url="../remedies/remedies.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              } ?>
              <?php if($value['step_no']==0){ ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/remedy.png"></a>
              <?php }else{ ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/resume.png"></a>
              <?php } ?>
           </td>
         </tr>
         <?php 

              $getcaseFollowups=getcaseFollowups($value['id'],$conn);
              //printArr($getcaseFollowups);
              foreach ($getcaseFollowups['errMsg'] as $key1 => $value1) {
        ?>
         <tr>
           <td><?php echo $value1['id'] ; ?></td>
           <td><?php echo date('d/m/Y', strtotime($value1['date'])); ?></td>
           <td><?php echo date('d/m/Y', strtotime($value1['date'])); ?></td>
           <td><?php echo $value1['conclusion'] ; ?></td>
           <td>
              <!-- <?php $followupUrl="../followup/followup.php?case_id=".$value1['case_id']."&doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&complaint='".$complaint_name."'"; ?>
              <a href="<?php echo $followupUrl; ?>"><img src="../../assets/images/calendericon.png"></a> -->
             <!-- <img src="../../assets/images/chat.png"> -->
            <?php if($value['step_no']==1){
                $url="../startcase/step1.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==2){
                $url="../startcase/step2.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==3){
                $url="../startcase/step3.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==4){
                $url="../startcase/step4.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==5){
                $url="../startcase/step5.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==0){
                $url="../remedies/remedies.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              } ?>
              <?php if($value['step_no']==0){ ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/remedy.png"></a>
              <?php }else{ ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/resume.png"></a>
              <?php } ?>
           </td>
         </tr> 
         <?php 
            }
         ?> 
         <?php 
              $getRefCases=getRefCases($value['id'],$conn);
              //printArr($getcaseFollowups);
              foreach ($getRefCases['errMsg'] as $key2 => $value2) {
        ?>
         <tr>
           <td><?php echo $value2['id'] ; ?></td>
           <td><?php echo date('d/m/Y', strtotime($value2['created_on'])); ?></td>
           <td><?php echo date('d/m/Y', strtotime($value2['updated_on'])); ?></td>
           <td><?php echo $value2['primary_prescription'] ; ?></td>
           <td>
              <?php if($value2['followup_status']!='closed' && $value2['step_no']==0 && $value2['status']==1) { $followupUrl="../followup/followup.php?case_id=".$value2['id']."&doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&complaint='".$complaint_name."'"; ?>
              <a href="<?php echo $followupUrl; ?>"><img src="../../assets/images/calendericon.png"></a>
            <?php }if($value2['step_no']==1){
                $url="../startcase/step1.php?doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&case_id=".$value2['id'];
              }else if($value2['step_no']==2){
                $url="../startcase/step2.php?doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&case_id=".$value2['id'];
              }else if($value2['step_no']==3){
                $url="../startcase/step3.php?doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&case_id=".$value2['id'];
              }else if($value2['step_no']==4){
                $url="../startcase/step4.php?doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&case_id=".$value2['id'];
              }else if($value2['step_no']==5){
                $url="../startcase/step5.php?doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&case_id=".$value2['id'];
              }else if($value2['step_no']==0){
                $url="../remedies/remedies.php?doctor_id=".$value2['doctor_id']."&patient_id=".$value2['patient_id']."&case_id=".$value2['id'];
              } ?>
              <?php if($value2['step_no']==0){ ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/remedy.png"></a>
              <?php }else{ ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/resume.png"></a>
              <?php } ?>
           </td>
         </tr>
         <?php 
            }
         ?> 
         <?php 
              $getcaseFollowups=getcaseFollowups($value2['id'],$conn);
              //printArr($getcaseFollowups);
              foreach ($getcaseFollowups['errMsg'] as $key1 => $value1) {
        ?>
         <tr>
           <td><?php echo $value1['id'] ; ?></td>
           <td><?php echo date('d/m/Y', strtotime($value1['date'])); ?></td>
           <td><?php echo date('d/m/Y', strtotime($value1['date'])); ?></td>
           <td><?php echo $value1['conclusion'] ; ?></td>
           <td>
              <!-- <?php $followupUrl="../followup/followup.php?case_id=".$value1['case_id']."&doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&complaint='".$complaint_name."'"; ?>
              <a href="<?php echo $followupUrl; ?>"><img src="../../assets/images/calendericon.png"></a> -->
             <!-- <img src="../../assets/images/chat.png"> -->
            <?php if($value['step_no']==1){
                $url="../startcase/step1.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==2){
                $url="../startcase/step2.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==3){
                $url="../startcase/step3.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==4){
                $url="../startcase/step4.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==5){
                $url="../startcase/step5.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              }else if($value['step_no']==0){
                $url="../remedies/remedies.php?doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&case_id=".$value['id'];
              } ?>
              <?php if($value['step_no']==0){ ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/remedy.png"></a>
              <?php }else{ ?>
              <a href="<?php echo $url; ?>"><img src="../../assets/images/resume.png"></a>
              <?php } ?>
           </td>
         </tr> 
         <?php 
            }
         ?>  

         <?php //} ?> 
        </table>

        </div>
 <?php } ?>

      
      </div>
    </div>
    <div class="row managepatient-filter noleft-right" >
      <div class="col-md-12">
        <input type="button" class="startCase" value="START CASE"  style="margin-top: 60px;">
      </div>
    </div>
        <!-- Paggination -->


      <div class="row noleft-right">
        <div class="col-md-12" style="text-align: center;">
          <div class="pagination">

          <?php 
           if ($pageNo > 1) { ?>
                    <a onclick="searchFunction('<?php echo $userInfo['search']; ?>',<?php echo $userInfo['patient_id']; ?>,<?php echo $pageNo-1; ?>,<?php echo $limit; ?>)"><i class="fa fa-angle-double-left "></i></a>
          <?php }else if ($pageNo == 1 || $totalPageNo == 1) { ?>
                    <a style="opacity:0.5;"> <i class="fa fa-angle-double-left "></i></a>
          <?php }
                if ($pageNo == 1) {
                    $startLoop = 1;
                    $endLoop = ($totalPageNo < 4) ? $totalPageNo : 4;
                } else if ($pageNo == $totalPageNo) {
                    $startLoop = (($totalPageNo - 4) < 1) ? 1 : ($totalPageNo - 4);
                    $endLoop = $totalPageNo;
                } else {
                    $startLoop = (($pageNo - 2) < 1) ? 1 : ($pageNo - 2);
                    $endLoop = (($pageNo + 2) > $totalPageNo) ? $totalPageNo : ($pageNo + 2);
                } 
                $i=0;
                for ($i = $startLoop; $i <= $endLoop; $i++) {
                    if ($i == $pageNo) { ?>
                        <a onclick="searchFunction('<?php echo $userInfo['search']; ?>',<?php echo $userInfo['patient_id']; ?>,<?php echo $pageNo; ?>,<?php echo $limit; ?>)" class="active"><?php echo $pageNo; ?></a>
           <?php    } else { ?>
                        <a onclick="searchFunction('<?php echo $userInfo['search']; ?>',<?php echo $userInfo['patient_id']; ?>,<?php echo $i; ?>,<?php echo $limit; ?>)"><?php echo $i; ?></a>
           <?php    }
                }

          ?>
            <?php if ($pageNo < $totalPageNo) {  ?>          
                    <a onclick="searchFunction('<?php echo $userInfo['search']; ?>',<?php echo $userInfo['patient_id']; ?>,<?php echo $pageNo+1; ?>,<?php echo $limit; ?>)"><i class="fa fa-angle-double-right"></i></a>
                    &nbsp;
            <?php }else if($pageNo == $totalPageNo){ ?>
                   <a style="opacity:0.5;"><i class="fa fa-angle-double-right"></i></a>
                   &nbsp; 
            <?php } ?>
          </div>    
        </div>
    </div>
        <?php
        }else{ 
        ?> 
            <div class="noDataFound" style="text-align: center;color:grey;margin-top:10%;letter-spacing: 2px;word-spacing: 1px;">
              <h1>No matched data found</h1>
            </div> 
        <?php
        }
         /*empty state if ends*/

    }

  }else if($_POST['type'] == 'search_patient'){
                if((isset($_POST['search']) && !empty($_POST['search']))){
            $userInfo['search']=cleanQueryParameter($conn,cleanXSS($_POST["search"]));
            $userInfo['doctor_id']=$_SESSION['userInfo']['user_id'];
            $pageNo=$_POST['pageNo'];
            $limit=$_POST['limit'];
           // printArr($_POST); 
            //$userInfo['otherFilter']=cleanQueryParameter($conn,cleanXSS($_POST["otherFilter"]));
           /* $searchPatients=searchPatients($userInfo,$conn);
            $returnArr["errCode"] =2 ;
            $returnArr["errMsg"] = $searchPatients;*//*
            echo json_encode($searchPatients);*/
            
            $getDemo=getSearchPatient($conn,$userInfo, $pageNo, $limit);
   // printArr($getDemo);
           // foreach ($getDemo as $key => $value) {
              # code...
            //  printArr($_POST);
  /* <!-- NEw managepatient section starts --> */
    if(!empty($getDemo['errMsg'])){

?>
<!--  <div class="main_div"> -->
    <div class="row noleft-right" >
      <div class="col-md-12 col-sm-12 col-xs-12 ">
        
      <!-- 1 Patient -->
      <?php foreach($getDemo['errMsg'] as $patientsId=>$patientDetails) {
             // printArr($patientDetails);
              $patient_id=$patientDetails['patient_id'];
              $doctor_id=$patientDetails['doctor_id'];
              $doctor_patient_id=$patientDetails['doctor_patient_id'];
              //$getUserInfoWithUserId=getUserInfoWithUserId($patient_id,3,$conn);
              //$getUserInfoWithUserId=$getUserInfoWithUserId['errMsg'];
              //printArr($getUserInfoWithUserId);
        ?>

        <div class="patient-info">
          <div class="row noleft-right">

            <div class="col-md-offset-9 col-md-3 col-sm-offset-8 col-sm-4 col-xs-12 actionimg">
              <img src="../../assets/images/remove.png" onclick="deletePatientInfo(<?php echo $patient_id.','.$doctor_patient_id ;?>);" />
              <img src="../../assets/images/tag.png" onclick="editPatientLabelInfo(<?php echo $patient_id.','.$doctor_patient_id ;?>);" />
              <img src="../../assets/images/pen.png" onclick="editPatientInfo(<?php echo $patient_id.','.$doctor_patient_id ;?>);" />
              
            </div>
            
            <div class="col-md-2 col-xs-12 status" >
              <div class="patient-img">
                <?php if(!empty($patientDetails['user_image'])){ ?>
                <img src="<?php echo $patientDetails['user_image']; ?>" class="img-circle" />
                <?php } else { ?>
                <img src="../../assets/images/cam.png" class="img-circle" />
                <?php } ?>
              </div>  
              <?php
              if($patientDetails['label']=='vip'){
                $label='Vip';
              }else if($patientDetails['label']=='emergency'){
                $label='Emergency';
              }else if($patientDetails['label']=='unimportant'){
                $label='Unimportant';
              }else if($patientDetails['label']=='improving'){
                $label='Improving';
              }else if($patientDetails['label']=='notimproving'){
                $label='Not Improving';
              }else if($patientDetails['label']=='discuss'){
                $label='To Discuss';
              }

              ?>
              <label class="<?php echo $patientDetails['label'];?>" style="cursor:auto;display:inline-block;"><?php echo $label;?></label>
            </div>

            <div class="col-md-3 col-xs-12">
              <h1><?php echo ucfirst(strtolower($patientDetails['user_first_name'])).' '.ucfirst(strtolower($patientDetails['user_last_name'])); ?></h1>
              <h2><?php if($patientDetails['user_email']!='dummy'.$patientDetails['user_mob'].'@eHeilung.com') { echo $patientDetails['user_email']; } ?></h2>
              <h2><?php echo $patientDetails['user_mob'];?></h2>
            </div>

            <?php $getRecentCaseOfUser=getRecentCaseOfUser($patient_id,$doctor_id,$conn);
            //printArr($getRecentCaseOfUser);
                  if(noError($getRecentCaseOfUser)){
                    $getRecentCaseOfUser=$getRecentCaseOfUser['errMsg'];
                  }else{
                    printArr('No data found');
                  }
                 
             ?>
            <div class="col-md-4 col-xs-12 desk-paddleft">
              <h1 style="word-break: break-word;"><?php echo $getRecentCaseOfUser['complaint_name']; ?></h1>
              <?php if(!empty($getRecentCaseOfUser['created_on'])){ ?>
              <h3>Start date: <span><?php if(!empty($getRecentCaseOfUser['created_on'])) echo date('d/m/Y', strtotime($getRecentCaseOfUser['created_on'])); ?></span></h3>
               <?php } if(!empty($getRecentCaseOfUser['primary_prescription'])){ ?>
                  <div  class="presc-label">
                   <h3>Prescriptions: </h3>
                  </div>
                  <div class="presc-list">
                    <ul class="prescriptions">
                      <li><?php echo $getRecentCaseOfUser['primary_prescription']; ?></li>
                      <li><?php echo $getRecentCaseOfUser['second_prescription']; ?></li>
                      <li><?php echo $getRecentCaseOfUser['third_prescription ']; ?></li>
                    </ul>
                  </div>
                  <?php } ?>

            </div>

            <div class="col-md-3 col-xs-12 casebtns">
              <input type="button" value="CASE HISTORY" class="casehistory-btn" onclick="patientCaseHistory(<?php echo $patient_id.','.$doctor_patient_id ;?>);">
              <input type="button" value="START CASE" class="startcase-btn" onclick="startCase(<?php echo $patient_id.','.$doctor_id ;?>);">
              <input type="button" value="PRIVATE NOTES" class="privatenotes-btn" onclick="editPatientNotesInfo(<?php echo $patient_id.','.$doctor_patient_id ;?>);">
            </div>



          </div>
        </div>
        <?php } ?>

      <!-- 1st Patient End -->



      </div>
    </div>


    <div class="row managepatient-filter noleft-right" >
      <div class="col-md-12">
        <input type="button" class="addUser" value="ADD PATIENT" data-toggle="modal" data-target="#addpatient" style="margin-top: 60px;">
      </div>
    </div>
    <!-- Paggination -->


      <div class="row noleft-right">
        <div class="col-md-12" style="text-align: center;">
          <div class="pagination">

          <?php 

          $totalItems=$getDemo['countCases'];
          $totalPageNo=ceil($totalItems/10);
           if ($pageNo > 1) { ?>
                    <a style="cursor:pointer;" onclick="searchFunction('<?php echo $userInfo['search']; ?>',<?php echo $pageNo-1; ?>,<?php echo $limit; ?>)" ><i class="fa fa-angle-double-left "></i></a>
          <?php }else if ($pageNo == 1 || $totalPageNo == 1) { ?>
                    <a style="opacity:0.5;"> <i class="fa fa-angle-double-left "></i></a>
          <?php }
                if ($pageNo == 1) {
                    $startLoop = 1;
                    $endLoop = ($totalPageNo < 4) ? $totalPageNo : 4;
                } else if ($pageNo == $totalPageNo) {
                    $startLoop = (($totalPageNo - 4) < 1) ? 1 : ($totalPageNo - 4);
                    $endLoop = $totalPageNo;
                } else {
                    $startLoop = (($pageNo - 2) < 1) ? 1 : ($pageNo - 2);
                    $endLoop = (($pageNo + 2) > $totalPageNo) ? $totalPageNo : ($pageNo + 2);
                } 
                $i=0;
                for ($i = $startLoop; $i <= $endLoop; $i++) {
                    if ($i == $pageNo) { ?>
                        <a style="cursor:pointer;" onclick="searchFunction('<?php echo $userInfo['search']; ?>',<?php echo $pageNo; ?>,<?php echo $limit; ?>)" class="active"><?php echo $pageNo; ?></a>
           <?php    } else { ?>
                        <a style="cursor:pointer;" onclick="searchFunction('<?php echo $userInfo['search']; ?>',<?php echo $i; ?>,<?php echo $limit; ?>)"><?php echo $i; ?></a>
           <?php    }
                }

          ?>
            <?php if ($pageNo < $totalPageNo) {  ?>          
                    <a  onclick="searchFunction('<?php echo $userInfo['search']; ?>',<?php echo $pageNo+1; ?>,<?php echo $limit; ?>)" style="cursor:pointer;"
                      ><i class="fa fa-angle-double-right"></i></a>
                    &nbsp;
            <?php }else if($pageNo == $totalPageNo){ ?>
                   <a style="opacity:0.5;"><i class="fa fa-angle-double-right"></i></a>
                   &nbsp; 
            <?php } ?>
          </div>    
        </div>
    </div>
<!-- </div> -->
 


<?php
}else{ ?>
      <div class="noDataFound" style="text-align: center;color:grey;margin-top:10%;letter-spacing: 2px;word-spacing: 1px;">
        <h1>No matched data found</h1>
      </div>
   <?php } ?>

<!-- NEw managepatient section ends -->


<?php
  }
  }


  }
  /*post parameter check ends*/  
  }  
  //accept request ends  
?>
