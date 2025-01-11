<?php
//$activeHeader = "2opinion"; 
$pathPrefix="../";
$activeHeader = "doctorsArea"; 
session_start();
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php"); 
require_once("../../models/userModel.php");
require_once("../../models/followUpModel.php");
require_once("../../models/patientCaseHistoryModel.php");
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

$now=time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    // this session has worn out its welcome; kill it and start a brand new one
    printArr("You do not have sufficient privileges to access this page<br>Login to continue <a href='".$rootUrl."/controllers/logout.php'>Home</a> ");
  exit;
}
$_SESSION['stepFlag']=0;
//printArr($_SESSION);
$user = "";
if(isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)){
  $user = $_SESSION["user"];
  $user_type=$_SESSION["user_type"];

  $userInfo = getUserInfoWithUserType($user,$user_type,$conn);
  if(noError($userInfo)){
    $userInfo = $userInfo["errMsg"];  
  
  } else {
    printArr("Error fetching user info".$userInfo["errMsg"]);
    exit;
  }
} else {
   $redirectURL ="../../index.php?luser=doctor";
  header("Location:".$redirectURL); 
  exit;
}

//printArr($getPatientCases);
/*if(noError($getPatientCases)){
  $totalItems=$getPatientCases['countCases'];
  //$getPatientCases = $getPatientCases["errMsg"];
   $totalPageNo=ceil($totalItems/3);
//ceil($getCases['countCases']/5);

} else {

  //printArr("Error fetching case detailss");
  $getPatientCases="No Data";
}*/
 
/*foreach ($getPatientCases['errMsg'] as $key => $value) {
  $complaint_name=$value['complaint_name'];
  //$res=$res[$complaint_name];
  echo  $complaintQuery = sprintf("SELECT * FROM doctors_patient_cases WHERE patient_id='".$_GET['patient_id']."' and doctor_id='".$userInfo['user_id']."' and complaint_name='".$complaint_name."'ORDER BY created_on  DESC LIMIT %s,%s",$start,$limit);
           //echo $complaintQuery .= sprintf(" ORDER BY created_on  DESC LIMIT %s,%s",$start,$limit);
          $cquery=runQuery($complaintQuery,$conn);
           if(noError($cquery)){
              $returnArr['errCode'][-1]=-1;
              $result=$cquery['dbResource'];
              $arr1=array();
              while ($row = mysqli_fetch_assoc($result)) {
                $arr1[]=$row;
              }
          }
          printArr($arr1);
}*/
       $totalItems=0;
 if(isset($_GET['pageNo'])){
        $pageNo=$_GET['pageNo'];
      }else{
        $pageNo=1;
      }
      $limit=3;
      $getPatientCases = getAllPatientCases($conn,$user_type,$_GET['patient_id'],$userInfo['user_id'],'DESC',$pageNo,$limit=3);
     //printArr($getPatientCases);
      if(noError($getPatientCases)){
          $totalItems=$getPatientCases['countCases'];
          $totalPageNo=ceil($totalItems/3);
        //ceil($getCases['countCases']/5);

        } else {

          //printArr("Error fetching case detailss");
          $getPatientQuery="No Data";
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once("../metaInclude.php"); ?>
  <style type="text/css">
    input[type="radio"] {
        display: none;
      }
      label {
        cursor: pointer;
        display: flex;
        max-width: 100%;
        margin-bottom: 21px;
        font-size: 20px;
        word-spacing: 4px;
        font-weight: normal!important;
        color: #444;
      }
      input[type="radio"] + label:before {
        border: 1px solid #555;
        content: "\2713";
        display: inline-block;
        font: 25px/1em sans-serif;
        height: 25px;
        margin: 5px  1.0em 0 0;
        padding: 0;
        vertical-align: top;
        width: 25px;
        border-radius: 3px;
        color: #ffffff;
      }
      input[type="radio"] + label.event1:before {
        margin: 5px 0.5em 0 0 !important;
      }
      input[type="radio"] + label.event2:before {
        margin: 5px 1.5em 0 0 !important;
      }
      input[type="radio"]:checked + label:before {
        background: #0dae04;
        color: #ffffff;
        content: "\2713";
        text-align: center;
        border: transparent;
        width: 25px;
        padding: 1px;
      }
      input[type="radio"]:checked + label:after {
        font-weight: bold;
      }

  .social-icon ul li a i {
    color: #666;
    font-size: 20px;
    text-align: center;
    background-color: bisque;
   
}
 .social-icon ul li {
   display: inline-block;
}

.social-icon ul li a {
   padding: 3px 9px;
}
.alert-danger{
background color:#f99a9a;
}
.alert-success{
  background color:#87de87;
}

.searchinput{
    float: right;
  }

  @media(max-width: 768px){
 
  .searchinput{
    float: left !important;
  }
 
}

.nopatients {
    min-height: 100px;
    box-shadow: none;
    margin-top: 0px;
    padding: 10px;
}
.nopatients a:hover,.addnew:hover{
  color: #fff !important;
}
.addnew{
letter-spacing: 1px;
  background-color: #0dae04;
    color: #fff;
    border-radius: 8px;
    text-align: center;
    border: none;
    /*outline: none;*/
    padding: 10px;
    font-family: Montserrat-Regular;
    min-width: 180px;
    margin-top: 50px;
}


.search-box{
  width: 300px;
}

@media(max-width: 400px){
  .search-box{
    width: 84%;
  }
  .searchinput{
    width: 100%;
  }
}

@media(max-width: 767px){
  .searchinput{
    margin-top: 0px;
  }
  .managepatient h2{
    margin-bottom: 0px;
  }
  .managepatient-filter input[type=button]{
    margin-top: 20px;
    margin-bottom: 10px;
  }
}


.managepatient-filter .dropdown-menu{
    left: -19px !important;
  }

 @media(max-width: 1200px) {
  .managepatient-filter .dropdown-menu{
    left: -73px !important;
  }
 }
 @media(max-width: 767px) {
  .managepatient-filter .dropdown-menu{
    left: 33% !important;
  }
 }
  @media(max-width: 500px) {
  .managepatient-filter .dropdown-menu{
    left: 10% !important;
  }
 }

/*#setstatus .checked{
  add-attr(checked="true");
} */
    /*header{
      padding:7px 20px !important;
    }*/
  </style>
  <link rel="stylesheet" type="text/css" href="../../assets/css/home.css?aghrd=r4564298">
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.min.css">
  <!-- header-->

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">



  <main class="container" style="min-height: 100%;">

    <?php  include_once("../header.php"); ?> 
  <?php if($totalItems!=0 && $totalItems!=""){ ?>

<div class="row noleft-right" style="display: block;">
      <div class="col-md-12 xol-xs-12">
      <?php if($totalItems<6){ ?>
        <div class="infobar">
          <h4>You have taken only <?php echo $totalItems; ?> cases on eHielung.<span style="cursor: pointer;" class="startCase"> Start a new one now</span></h4>
        </div>
      <?php } ?>
      </div>
    </div>
    
    <div class="row noleft-right" >
      <div class="col-md-5 col-sm-5 col-xs-12 managepatient" >
        <h2>Patient history <img src="../../assets/images/info.png" class="heading-info" data-toggle="modal" data-target="#infoModal" /></h2>
      </div>

      <div class="col-md-7 col-sm-7 col-xs-12 managepatient" >
      <div class="searchinput">
         <input type="text" placeholder="Search for something" class="search-box"  />
          <button><img src="../../assets/images/search.png"></button>
          </div>
      </div>
    </div>


    <div class="row managepatient-filter noleft-right" >
      <div class="col-md-10 col-sm-10 col-xs-6">
      <input type="button" class="startCase" value="START CASE" >
      </div>
      

      <div class="col-md-2 col-sm-2 col-xs-12">
        <!-- Dropdown filter -->
      <div class="dropdown">
        <h4 class="dropdown-toggle" data-toggle="dropdown" style="text-align: center;cursor:pointer;">Sort by <i class="fa fa-angle-down" ></i></h4>
          <ul class="dropdown-menu">
            <li>
              <a>
                <input type="radio" class="name_sort" id="atoz" value="ASC" name="name_sort">
                <label for="atoz">A to Z</label>
              </a>
            </li>

            <li>
              <a>
                <input type="radio" class="name_sort" id="ztoa" value="DESC" name="name_sort">
                <label for="ztoa">Z to A</label>
              </a>
            </li>
          </ul>
      </div>
      <!-- Dropdown filter-->
      </div>
    </div>
 <div id="patientsfound" style="display: block;">
 <div class="search_div"> </div>   
 <div class="main_div">
  

    <?php
     
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
           <th width="15%">CASE ID</th>
           <th width="15%">CREATION DATE</th>
           <th width="15%">UPDATED DATE</th>
           <th width="35%">PRESCRIPTIONS</th>
           <th width="20%">ACTION</th>
         </tr> 

          <?php //foreach ($arr1 as $key => $value) {
          # code...
          //printArr($value);
         ?>
         <tr>
           <td><?php echo $value['id'] ; ?></td>
           <td><?php echo date('d/m/Y', strtotime($value['created_on'])); ?></td>
           <td><?php echo date('d/m/Y', strtotime($value['updated_on'])); ?></td>
           <td><?php echo $value['primary_prescription'] ; ?></td>
           <td>
              <?php  if($value['followup_status']!='closed' && $value['step_no']==0) { $followupUrl="../followup/followup.php?case_id=".$value['id']."&doctor_id=".$value['doctor_id']."&patient_id=".$value['patient_id']."&complaint='".$complaint_name."'"; ?>
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
              <!-- <a href="<?php echo $url; ?>"><img src="../../assets/images/resume.png"></a> -->
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
             <!--  <a href="<?php echo $url; ?>"><img src="../../assets/images/resume.png"></a> -->
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
                    <a href="patientCaseHistory.php?patient_id=<?php echo $_GET['patient_id'];?>&doctor_id=<?php echo $_GET['doctor_id'];?>&pageNo=<?php echo $pageNo - 1; ?>"><i class="fa fa-angle-double-left "></i></a>
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
                        <a href="patientCaseHistory.php?patient_id=<?php echo $_GET['patient_id'];?>&doctor_id=<?php echo $_GET['doctor_id'];?>&pageNo=<?php echo ($pageNo) ; ?>" class="active"><?php echo $pageNo; ?></a>
           <?php    } else { ?>
                        <a href="patientCaseHistory.php?patient_id=<?php echo $_GET['patient_id'];?>&doctor_id=<?php echo $_GET['doctor_id'];?>&pageNo=<?php echo ($i) ; ?>"><?php echo $i; ?></a>
           <?php    }
                }

          ?>
            <?php if ($pageNo < $totalPageNo) {  ?>          
                    <a href="patientCaseHistory.php?patient_id=<?php echo $_GET['patient_id'];?>&doctor_id=<?php echo $_GET['doctor_id'];?>&pageNo=<?php echo $pageNo + 1; ?>"><i class="fa fa-angle-double-right"></i></a>
                    &nbsp;
            <?php }else if($pageNo == $totalPageNo){ ?>
                   <a style="opacity:0.5;"><i class="fa fa-angle-double-right"></i></a>
                   &nbsp; 
            <?php } ?>
          </div>    
        </div>
    </div>
    </div>
  </div>
    <!-- end of patient found -->
<?php } else{ ?>

    <!-- Show THIS IF NO PATIENTS FOUND IN LIST  -->
    <div id="nopatients" class="nopatients" style="display: block;">
    <div class="row noleft-right" >
      <div class="row noleft-right" >
      <div class="col-md-5 col-sm-5 col-xs-12 managepatient" >
        <h2>Patient history <img src="../../assets/images/info.png" class="heading-info" data-toggle="modal" data-target="#infoModal" /><!-- <img src="../../assets/images/info.png" style="margin-left: 5px;vertical-align: super;cursor:pointer;" data-toggle="modal" data-target="#infoModal" /> --></h2>
      </div>
    </div>
          <h1>Welcome to Patient history</h1>
            
          <h4 style="text-align:left;">In this section you will find the patient’s disease history, options to set a follow-up with him, chat with him, and prescribe medicnes for him.</h4>

              <div class="col-md-12"  style="text-align:center;margin-top:20px;" >
              <!-- <input type="button" value="ADD NEW CASE" class="addnew myPatient"> -->
              <a style="cursor: pointer;" class="startCase addnew myPatient">START CASE</a>
            </div>
    </div>  



 <!--    <div class="row managepatient-filter noleft-right" >
      <div class="col-md-12" style="text-align: center;margin-top:60px;" >
        <input type="button" class="startCase" value="START CASE" >
        <a href="" class="startCase" >START CASE</a>
      </div>
    </div> -->
  </div>


    <!-- End of no patients found -->

<?php }?>

    
</main> 
<?php  include("../modals.php"); ?> 
<?php  include('../footer.php'); ?>

<script type="text/javascript">

function showgeneral_info(){
  $("#general-form").css("display","block");
  $("#contact-form").css("display","none");
  $(".general").addClass( "active" );
  $(".coninfo").removeClass( "active" ); 

}


function showcontact_info(){
  $("#general-form").css("display","none");
  $("#contact-form").css("display","block");
  $(".general").removeClass( "active" );
  $(".coninfo").addClass( "active" ); 
}

$('.startCase').click(function(){
  window.location.href="../startcase/step1.php?doctor_id=<?php echo $userInfo['user_id']; ?>&patient_id=<?php echo $_GET['patient_id']; ?>";
});


  $(document).ready(function() {
    var text_max = 250;
    $('#textarea_feedback').html(text_max);

    $('#higest-degree').keyup(function() {
        var text_length = $('#higest-degree').val().length;
        var text_remaining = text_max - text_length;

        $('#textarea_feedback').html(text_remaining);
    });



    var text_max1 = 250;
    $('#addr_feedback').html(text_max1);

    $('#address').keyup(function() {
        var text_length1 = $('#address').val().length;
        var text_remaining1 = text_max1 - text_length1;

        $('#addr_feedback').html(text_remaining1);
    });
});

$('.name_sort').click(function(){
 var name_sort=$(this).val();
 //alert(name_sort);
  //var label_sort = $('input[name="label_sort"]:checked').val();
  var patient_id=<?php echo $_GET['patient_id']; ?>;
sortFunction(name_sort,patient_id,pageNo=1,$limit=3);
});
function sortFunction(name_sort,patient_id,pageNo=1,limit=3){
$.ajax({type: "POST",
            url:"../../controllers/searchsortController.php",
            data:{patient_id:patient_id,
                  filterName:name_sort,
                  pageNo:pageNo,
                  limit:limit,
                  type:'filterCase'},
            dataType:'html',
            /*beforeSend: function () {
              $ele.find('.stopAccess').show();
            }*/
      })
      .done(function(data) {
        console.log(data);

        $('.main_div').hide();
        $('.search_div').html(data);
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        //alert("error");
        console.log('error');
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       }) 
$( "body" ).scrollTop( 300 );
}
function searchFunction(search,patient_id,pageNo=1,limit=3){
$.ajax({type: "POST",
            url:"../../controllers/searchsortController.php",
            data:{patient_id:patient_id,
                  search:search,
                  pageNo:pageNo,
                  limit:limit,
                  type:'search_cases'},
            dataType:'html',
            /*beforeSend: function () {
              $ele.find('.stopAccess').show();
            }*/
      })
      .done(function(data) {
        console.log(data);

        $('.main_div').hide();
        $('.search_div').html(data);
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        //alert("error");
        console.log('error');
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       }) 
       $( "body" ).scrollTop( 300 );

}
$(".search-box").keyup(function(){
 //alert(this.value);
  var search=$(this).val();
  //alert(search);
  var patient_id=<?php echo $_GET['patient_id']; ?>;
  searchFunction(search,patient_id,pageNo=1,limit=3);
  
});


</script>
</body>
</html>
