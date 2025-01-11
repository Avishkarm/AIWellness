<?php
//$activeHeader = "2opinion"; 
$pathPrefix="../";
$activeHeader = "doctorsArea"; 
//echo $_GET['pageNo'].'get<br>';
session_start();
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php"); 
require_once("../../models/userModel.php");
require_once("../../models/followUpModel.php");
require_once("../../models/paymentModel.php");
require("../../models/completeProfileModel.php");
//require_once("../../controllers/managePatientController.php");
require_once("../../models/managePatientModel.php");
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

 if(isset($_GET['pageNo'])){
          $pageNo=$_GET['pageNo'];
        }else{
          $pageNo=1;
        }

        $getPatientQuery=getDoctorsPatient($conn,$userInfo['user_id'],$pageNo,$limit=10);

        if(noError($getPatientQuery)){
         $totalItems=$getPatientQuery['countCases'];
          $getPatientQuery = $getPatientQuery["errMsg"];
          $totalPageNo=ceil($totalItems/10);
        //ceil($getCases['countCases']/5);

        } else {

          //printArr("Error fetching case detailss");
          $getPatientQuery="No Data";
        }
      //$getPlanExpiring=getPlanExpiring($userInfo['user_id'],$conn);//ALERT-KDR
      //printArr($getPlanExpiring);
      //echo $getPlanExpiring['diff'];
 $access=1;     
//if($getPlanExpiring['diff']<=0 || $getPlanExpiring['diff']==""){//ALERT-KDR
//  $access=0;
//  $redirectURL ="../dashboard/doctorsDashboard.php";
//  header("Location:".$redirectURL); 
//}
//$access=1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once("../metaInclude.php"); ?>


<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
x = 0;
$(document).ready(function(){
    $("div").scroll(function(){
        $("span").text( x+= 1);
    });
});
</script> -->




	<style type="text/css">
    
.managepatient-filter .dropdown-menu{
    left: -19px !important;
  }

 @media(max-width: 1200px) {
  .managepatient-filter .dropdown-menu{
    left: -118px !important;
  }
 }
 @media(max-width: 767px) {
  .managepatient-filter .dropdown-menu{
    left: 31% !important;
  }
 }
  @media(max-width: 500px) {
  .managepatient-filter .dropdown-menu{
    left: 10% !important;
  }
 }

  .dropdown-menu input[type="radio"] {
        display: none;
      }
     .dropdown-menu label {
        cursor: pointer;
        display: flex;
        max-width: 100%;
        margin-bottom: 21px;
        font-size: 20px;
        word-spacing: 4px;
        font-weight: normal!important;
        color: #444;
      }
    .dropdown-menu input[type="radio"] + label:before {
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
     .dropdown-menu input[type="radio"] + label.event1:before {
        margin: 5px 0.5em 0 0 !important;
      }
      .dropdown-menu input[type="radio"] + label.event2:before {
        margin: 5px 1.5em 0 0 !important;
      }
      .dropdown-menu input[type="radio"]:checked + label:before {
        background: #0dae04;
        color: #ffffff;
        content: "\2713";
        text-align: center;
        border: transparent;
        width: 25px;
        padding: 1px;
      }
      .dropdown-menu input[type="radio"]:checked + label:after {
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
background-color:#f99a9a;
}
.alert-success{
  background-color:#87de87;
}
.alert{
  display: none;
}
.prescriptions {
  list-style: none;
    color: #454545;
    line-height: 25px !important;
    padding: 0;
    font-family: Montserrat-Regular;
    color: #454545;
    text-align: left;
    line-height: 25px;
    font-size: 18px;
    letter-spacing: 1px;
    margin-top: 10px;
    margin-bottom: 0px;
}
.presc-label{
  display: inline-block;
}
.presc-list{
 display: inline-block;
 padding-left: 0;
 vertical-align: top;
}
@media(max-width: 768px){
  .addpatient h4{
    text-align: left;
  }
  .searchinput{
    float: left !important;
  }
  .managepatient-filter .dropdown-menu{
    /*left: 15px !important;*/
  }
}

  .searchinput{
    float: right;
  }

select{
    -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-appearance: none;
  -moz-appearance: none;
}

select.minimal {
        background-image: linear-gradient(45deg, transparent 50%, gray 50%), linear-gradient(135deg, gray 50%, transparent 50%), linear-gradient(to right, #ccc, #ccc);
    background-position: calc(100% - 23px) calc(1em + 10px), calc(100% - 16px) calc(1em + 10px), calc(100% - 2.5em) 0.5em;
    background-size: 8px 8px, 8px 8px, 0px 5em;
    background-repeat: no-repeat;

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

@media(max-width: 768px){
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

.datepicker{z-index:9999 !important}

.display-only ul{
 padding-left: 20px !important;
 list-style: initial;
}
.display-only li{
  height: auto !important;
  display: list-item  !important;
}

.cke_bottom{
  display: none !important;
}

#cke_27,#cke_26{
  display: none;
}

.ui-datepicker-month ,.ui-datepicker-year{
    border-color: #ccc !important;
    width: 65%;
    height: 20px !important; 
    border-radius: 0px; 
    margin-right: 10px;
    background-color: white;
    color: #555;
  }


  .followup-list{
    box-shadow: 0 0 15px rgb(246, 246, 246);
    position: absolute;
    background-color: #fff;
    width: 97.5%;
    z-index: 9999;
    max-height: 250px;
    overflow-y:scroll;
  }

  @media(max-width: 400px){
  .followup-list{
    width: 90.5%;
  }
}

@media(min-width: 401px){
  .followup-list{
    width: 94%;
  }
}

@media(min-width: 768px){
  .followup-list{
    width: 95.5%;
  }
}

@media(min-width: 1024px){
  .followup-list{
    width: 97.5%;
  }
}


  .followup-list h5{
    text-align: right;
    color:#0075c4;
  }
  .followup-list h5 span{
    color: blue;
    color:#454545;
  }

  .followup-list h4{
    text-align: left;
    color:#454545;
  }

  .followup-list .single-item{
    margin-bottom: 5px;
    margin-top: 5px;
    cursor: pointer;
  }

    .followup-list .single-item img{
    width: 120%;
    object-fit: cover;
    border-radius: 500px;
   }

  .followup-list .single-item:hover{
    background-color: #faf9f9;
  }






.vertical-menu {
    width: 100%;
    height: 240px;
    overflow-y: auto;
    margin-left: -10px;
    overflow: scroll;
}

.vertical-menu a {
    background-color: #eee;
    color: black;
    display: block;
    padding: 12px;
    text-decoration: none;
}

.vertical-menu a:hover {
    background-color: #ccc;
}

.vertical-menu a.active {
    background-color: #4CAF50;
    color: white;

}


.showNotes{
  word-break: break-word;
}


@media(min-width: 991px){
.desk-paddleft {
  padding-left: 40px;
}
}






	</style>
	<link rel="stylesheet" type="text/css" href="../../assets/css/home.css?a=1">
 <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>


 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  

	<main class="container" style="min-height: 100%;">


		<?php  include_once("../header.php"); ?>
    <?php
    /*
    if($getPlanExpiring['diff']==""){
    ?>
    <div class="row noleft-right"  >
    <div class="col-md-12">
      <div class="followup">
        <h4 style="cursor:pointer;">You dont have any plan.<a class="freePlan"> Get free trial now!</a></h4>
      </div>
    </div>
  </div> 
   <?php
  }
  else if($userInfo['plan_name']=='trial' && $getPlanExpiring['diff']>0 && $getPlanExpiring['diff']<4){
?>
  <div class="row noleft-right"  >
    <div class="col-md-12">
      <div class="followup">
        <h4 style="cursor:pointer;">Your 30-day free trial ends in <?php echo $getPlanExpiring['diff'];?> day.<a class="payu"> Extend it now!</a></h4>
      </div>
    </div>
  </div> 
<?php    
  }
  else if($userInfo['plan_name']=='trial' && $getPlanExpiring['diff']<=0){
?>
  <div class="row noleft-right"  >
    <div class="col-md-12">
      <div class="followup">
        <h4 style="cursor:pointer;">Your 30-day free trial expired.<a class="payu"> Extend it now!</a></h4>
      </div>
    </div>
  </div> 
<?php    
  }
  else if($userInfo['plan_name']!='trial' && $getPlanExpiring['diff']>0 && $getPlanExpiring['diff']<4){
?>
  <div class="row noleft-right"  >
    <div class="col-md-12">
      <div class="followup">
        <h4 style="cursor:pointer;">Your plan ends in <?php echo $getPlanExpiring['diff'];?> day.<a class="payu"> Extend it now!</a></h4>
      </div>
    </div>
  </div> 
<?php    
  }
  else if($userInfo['plan_name']!='trial' && $getPlanExpiring['diff']<=0 ){
?>
  <div class="row noleft-right"  >
    <div class="col-md-12">
      <div class="followup">
        <h4 style="cursor:pointer;">Your plan is expired.<a class="payu" > Extend it now!</a></h4>
      </div>
    </div>
  </div> 
<?php    
  }
  */
?>
<!--check => have plan or plan expired -->
<?php if($access==1) { ?>
   <?php if($totalItems!=0 && $totalItems!=""){ ?>  
   <div class="row noleft-right">
      <div class="col-md-12">
      <?php if($totalItems<101){ ?>
        <div class="infobar">
        <?php if($totalItems<=0){ ?>        
          <h4>You have no patients added. <span class="addUser" data-toggle="modal" data-target="#addpatient">Please add patients.</span></h4>
          <?php }else{?>
          <h4>You have added only <?php echo $totalItems; ?> patients.<b style="cursor: pointer;" class="addUser" data-toggle="modal" data-target="#addpatient"> Add more patients now</b></h4>
          <?php } ?>
        </div>
      <?php } ?>
      </div>
    </div>


<?php 
$getUpcomingfollowups=getUpcomingfollowups(3,$userInfo['user_id'],$conn);
$FollowupCount=$getUpcomingfollowups['count'];
      //printArr($getUpcomingfollowups);
if(noError($getUpcomingfollowups) && !empty($getUpcomingfollowups) && $FollowupCount!=0){
?>

    <div class="row noleft-right"  >
      <div class="col-md-12">
        <div class="followup" data-toggle="collapse" data-target="#demo">
          <h4 style="cursor:pointer;">You have <?php echo $FollowupCount;?> upcoming follow ups</h4>
        </div>
      </div>
    </div> 


<!-- <div class="vertical-menu"> -->
    <div class="row noleft-right">
      <div class="col-md-12">
     <!--  <div class="vertical-menu"> -->
          <div class="row noleft-right followup-list collapse" id="demo"  >
            <?php 
                foreach ($getUpcomingfollowups['errMsg'] as $key => $value) {
                  
            ?>
            <div class="followup1 single-item" onclick="patientCaseHistory1(<?php echo $value['patient_id'];?>);">
                <div class="col-md-1 col-xs-3">
                  <img src="<?php echo $value['user_image'];?>">
                </div>
                <div class="col-md-2 col-xs-4">
                  <h4><?php echo ucfirst(strtolower($value['user_first_name'])).' '.ucfirst(strtolower($value['user_last_name'])) ?></h4>
                </div>
                <div class="col-md-4 col-md-push-5 col-xs-5" >
                  <h5>Follow up: <span><?php echo date('d/m/Y', strtotime($value['followup_date']));?></span></h5>
                </div>
                <div style="clear:both"></div>
            </div> 
            <?php
                }
              }
            ?>





          
            <!-- <div class="followup2 single-item">
                <div class="col-md-1 col-xs-3">
                  <img src="../../assets/images/maria.png">
                </div>
                <div class="col-md-2 col-xs-4">
                  <h4>Emily Jhonson</h4>
                </div>
                <div class="col-md-4 col-md-push-5 col-xs-5" >
                  <h5>Follow up: <span>03/03/2017</span></h5>
                </div>
                <div style="clear:both"></div>
            </div> 


            <div class="followup3 single-item">
                <div class="col-md-1 col-xs-3">
                  <img src="../../assets/images/video1.png">
                </div>
                <div class="col-md-2 col-xs-4">
                  <h4>Emily Jhonson</h4>
                </div>
                <div class="col-md-4 col-md-push-5 col-xs-5" >
                  <h5>Follow up: <span>03/03/2017</span></h5>
                </div>
                <div style="clear:both"></div>
            </div> 
 -->
          </div>
          </div>
      </div>
    <!-- </div> -->



	   <div class="row noleft-right" >
      <div class="col-md-5 col-sm-5 col-xs-12 managepatient" >
<!--        <h2>Manage Patient <img src="../../assets/images/info.png" class="heading-info patientModal" /></h2>-->
          <h2>Manage Patient</h2>
      </div>

      <div class="col-md-7 col-sm-7 col-xs-12 managepatient" >
        <div class="searchinput">
         <input type="text" placeholder="Search for something" class="search-box" />
          <button><img src="../../assets/images/search.png"></button>
          </div>
      </div>
    </div>
    <div id="patientsfound" style="display: block;">

    <!-- <div class="row noleft-right" >
      <div class="col-md-5 col-sm-5 col-xs-10 managepatient" >
        <h2>Manage Patient<img src="../../assets/images/info.png"/></h2>
      </div>

      <div class="col-md-7 col-sm-7 col-xs-2 managepatient" >
      <input style="text" placeholder="search for something"> 
      </div>
    </div> -->


    <div class="row managepatient-filter noleft-right" >
      <div class="col-md-3 col-sm-3 col-xs-12">
      <input type="button" class="addUser" value="ADD PATIENT" data-toggle="modal" data-target="#addpatient">
      </div>

 <!-- Dropdown for other filter -->
     <!--   <div class="col-md-3 col-sm-3 col-xs-12 pull-right">
       
      <div class="dropdown">
        <h4 class="dropdown-toggle" data-toggle="dropdown" style="text-align: center;">Other Filters <i class="fa fa-angle-down" ></i></h4>
          <ul class="dropdown-menu">
            <li>
              <a href="#">
                <input type="checkbox" id="upcoming" name="upcoming">
                <label for="upcoming">Upcoming Follow ups</label>
              </a>
            </li>

            <li>
              <a href="#">
                <input type="checkbox" id="recentcases" name="recentcases" checked>
                <label for="recentcases">Recent Cases</label>
              </a>
            </li>

           

          </ul>
      </div>
     
      </div> -->
 <!-- Dropdown for Name end -->

     



      <div class="col-md-2  col-sm-3 col-xs-12 pull-right">
        <!-- Dropdown for label -->
      <div class="dropdown keep-open1">
          <h4 class="dropdown-toggle" data-toggle="dropdown" style="text-align: center;cursor:pointer;">Name <i class="fa fa-angle-down" ></i></h4>
          <ul class="dropdown-menu">
            <li>
              <a>
                <input type="radio" class="name_sort" value="ASC" id="atoz" name="name_sort">
                <label for="atoz">From A to Z</label>
              </a>
            </li>

            <li>
              <a>
                <input type="radio" class="name_sort" value="DESC" id="ztoa" name="name_sort">
                <label for="ztoa">From Z to A</label>
              </a>
            </li>
            </li>

           

          </ul>
      </div>
      <!-- Dropdown for Name end -->
      </div>


       <div class="col-md-2 col-sm-3 col-xs-12 pull-right">
    <form name="labelForm" id="labelForm" action="javascript:;" >
      <!-- Dropdown for label -->
      <div class="dropdown keep-open">
          <h4 class="dropdown-toggle lablelclick" data-toggle="dropdown" style="text-align: center;cursor:pointer;">Label <i class="fa fa-angle-down" ></i></h4>
          <ul class="dropdown-menu dropdown-status ">

           <!--  <li>
              <a >
                <input class="label_sort" type="radio" id="vip-notimproving" value="vip-notimproving" name="label_sort">
                <label for="vip-notimproving">VIP - Not Improving</label>
              </a>
            </li>

            <li>
              <a >
                <input class="label_sort" type="radio" id="notimproving-vip" value="vip-notimproving" name="label_sort">
                <label for="notimproving-vip">Not Improving - VIP</label>
              </a>
            </li> -->

            <li>
              <a>
                <input class="label_sort " type="checkbox" id="vip" value="vip" name="label_sort1">
                <label for="vip">VIP</label>
              </a>
            </li>

            <li>
              <a>
                <input class="label_sort" type="checkbox" id="emergency" value="emergency" name="label_sort2">
                <label for="emergency">Emergency</label>
              </a>
            </li>

            <li>
              <a>
                <input class="label_sort" type="checkbox" id="to-discuss" value="discuss" name="label_sort3">
                <label for="to-discuss">To Discuss</label>
              </a>
            </li>

            <li>
              <a >
                <input class="label_sort" type="checkbox" id="unimportant" value="unimportant" name="label_sort4">
                <label for="unimportant">Unimportant</label>
              </a>
            </li>

            <li>
              <a >
       
                <input class="label_sort" type="checkbox" id="improving" value="improving" name="label_sort5">
                <label for="improving">Improving</label>

              </a>
            </li>

            <li>
              <a >
                <input class="label_sort"  type="checkbox" id="notimproving" value="notimproving"  name="label_sort6">
                <!-- <input type="button" onclick="myFunction()" value="Submit form"> -->
                <label for="notimproving">Not Improving</label>
              </a>
            </li>
            <li>
              <a>
                <input class="labelSort" type="submit"  id="labelSort" value="Submit" name="submit" style="border:none;background-color: transparent;margin-left: 20%;font-size: 21px;font-weight: 600;">
                  <!-- <input type="button" onclick="myFunction()" value="Submit form"> -->
              </a>
            </li>
<!-- <input class="labelSort dropdown-toggle" type="submit"  data-toggle="dropdown" id="labelSort" value="Submit" name="submit" style="border:none;background-color: transparent;margin-left: 20%;font-size: 21px;font-weight: 600;"> -->
<!-- <form action="/action_page.php" method="get">
g
<li>
<a>
  <input type="checkbox" name="vehicle" value="Bike"> I have a bike<br>
  <input type="checkbox" name="vehicle" value="Car" checked> I have a car<br>
  <input type="submit" value="Submit" id="labelSort" class="labelSort" name="submit">
 </a>
 </li>
</form> -->


          </ul>
      </div>
      <!-- Dropdown for label end -->
    </form>
      </div>
     
    </div>


    <script type="text/javascript">
      $('.dropdown.keep-open').on({
    "shown.bs.dropdown": function() { this.closable = true; },
    "click":             function(e) { 
        var target = $(e.target);
        if(target.hasClass("labelSort") || target.hasClass("lablelclick"))
        {
            this.closable = true;
      
        } 
       else if(target.hasClass("label_sort")){
        this.closable = false;
       }
        else{
           this.closable = false;
        }            
    },
    "hide.bs.dropdown":  function() { return this.closable; }
});
    </script>





 <script type="text/javascript">
//       $('.dropdown.keep-open1').on({
//     "shown.bs.dropdown": function() { this.closable = true; },
//     "click":             function(e) { 
//         var target = $(e.target);
//         if(target.hasClass("labelSort")) 
//             this.closable = true;
//         else 
//            this.closable = false; 
//     },
//     "hide.bs.dropdown":  function() { return this.closable; }
// });
    </script>




<div class="search_div"> </div>   
<div class="main_div">
    <div class="row noleft-right" >
      <div class="col-md-12 col-sm-12 col-xs-12 ">
        
      <!-- 1 Patient -->
      <?php foreach($getPatientQuery as $patientsId=>$patientDetails) {
              $patient_id=$patientDetails['patient_id'];
              $doctor_id=$patientDetails['doctor_id'];
              $doctor_patient_id=$patientDetails['doctor_patient_id'];
              $getUserInfoWithUserId=getUserInfoWithUserId($patient_id,3,$conn);
              $getUserInfoWithUserId=$getUserInfoWithUserId['errMsg'];
              //printArr($getUserInfoWithUserId);
        ?>

        <div class="patient-info">
          <div class="row noleft-right">

            <div class="col-md-offset-9 col-md-3 col-sm-offset-8 col-sm-4 col-xs-12 actionimg">
              <img src="../../assets/images/remove.png" title="Delete" onclick="deletePatientInfo(<?php echo $patient_id.','.$doctor_patient_id ;?>);" />
              <img src="../../assets/images/tag.png" title="Set Status" onclick="editPatientLabelInfo(<?php echo $patient_id.','.$doctor_patient_id ;?>);" />
              <img src="../../assets/images/pen.png" title="Edit" onclick="editPatientInfo(<?php echo $patient_id.','.$doctor_patient_id ;?>);" />
              
            </div>
            
            <div class="col-md-2 col-xs-12 status" >
              <!-- <div class="patient-img">
                <img src="../../assets/images/maria.png" class="img-circle" />
              </div>  -->
              <div class="patient-img">
                <?php if(!empty($getUserInfoWithUserId['user_image'])){ ?>
                <img src="<?php echo $getUserInfoWithUserId['user_image']; ?>" class="img-circle" />
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
              <h1 style="word-break: break-word;"><?php echo ucfirst(strtolower($getUserInfoWithUserId['user_first_name'])).' '.ucfirst(strtolower($getUserInfoWithUserId['user_last_name'])); ?></h1>
              <h2><?php if($getUserInfoWithUserId['user_email']!='dummy'.$getUserInfoWithUserId['user_mob'].'@eHeilung.com') { echo $getUserInfoWithUserId['user_email']; } ?></h2>
              <h2><?php echo $getUserInfoWithUserId['user_mob'];?></h2>
            </div>

            <?php $getRecentCaseOfUser=getRecentCaseOfUser($patient_id,$doctor_id,$conn);
                  if(noError($getRecentCaseOfUser)){
                    $getRecentCaseOfUser=$getRecentCaseOfUser['errMsg'];
                  }else{
                    printArr('No data found');
                  }
                 //printArr($getRecentCaseOfUser);
             ?>
            <div class="col-md-4 col-xs-12 desk-paddleft">
              <h1 style="word-break: break-word;"><?php echo $getRecentCaseOfUser['complaint_name']; ?></h1>
              <?php if(!empty($getRecentCaseOfUser['created_on'])) { ?>
              <h3>Start date: <span><?php if(!empty($getRecentCaseOfUser['created_on']))echo date('d/m/Y', strtotime($getRecentCaseOfUser['created_on'])); ?></span></h3>
              <?php } if(!empty($getRecentCaseOfUser['primary_prescription'])){ ?>
                  <div  class="presc-label">
                   <h3>Prescriptions: </h3>
                  </div>
                  <div class="presc-list">
                    <ul class="prescriptions">
                      <li><?php echo $getRecentCaseOfUser['primary_prescription']; ?></li>
                      <li><?php echo $getRecentCaseOfUser['second_prescription']; ?></li>
                      <li><?php echo $getRecentCaseOfUser['third_prescription']; ?></li>
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
           if ($pageNo > 1) { ?>
                    <a href="manage_patient.php?pageNo=<?php echo $pageNo - 1; ?>"><i class="fa fa-angle-double-left "></i></a>
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
                        <a href="manage_patient.php?pageNo=<?php echo ($pageNo) ; ?>" class="active"><?php echo $pageNo; ?></a>
           <?php    } else { ?>
                        <a href="manage_patient.php?pageNo=<?php echo ($i) ; ?>"><?php echo $i; ?></a>
           <?php    }
                }

          ?>
            <?php if ($pageNo < $totalPageNo) {  ?>          
                    <a href="manage_patient.php?pageNo=<?php echo $pageNo + 1; ?>"><i class="fa fa-angle-double-right"></i></a>
                    &nbsp;
            <?php }else if($pageNo == $totalPageNo){ ?>
                   <a style="opacity:0.5;"><i class="fa fa-angle-double-right"></i></a>
                   &nbsp; 
            <?php } ?>
          </div>    
        </div>
    </div>
</div>
<!-- hide when search -->
    </div>
<!-- end of patient found -->
<?php } else{ ?>


    <!-- Show THIS IF NO PATIENTS FOUND IN LIST  -->
    <div id="nopatients" style="display: block;">
    <div class="row noleft-right" >

       <div class="col-md-5 col-sm-5 col-xs-12 managepatient" >
        <h2>Manage Patient </h2>
      </div>
      <!-- <div class="col-md-5 col-sm-5 col-xs-12 managepatient" >
        <h2>Manage Patient <img src="../../assets/images/info.png" style="margin-left: 5px;vertical-align: super;cursor:pointer;" data-toggle="modal" data-target="#infoModal" /></h2>
      </div> -->
    </div>
    <div class="row noleft-right" >
      <div class="col-md-12 col-sm-12 col-xs-12 ">
        <div class="nopatients">
          <div class="row noleft-right">      
            
          <h1>Welcome to my patients</h1>
            
          <h4>In this section you will find the list of all your patients. From here, you will be able to carry out following things:</h4>

          <div class="col-md-4">
            <h3>Actions</h3>

            <h5>Add patients</h5>
            <h5>Add patient's info</h5>
            <h5>Label patients</h5>
            <h5>Delete patients</h5>
          </div>

          <div class="col-md-4">
            <h3>See</h3>

            <h5>Patients contact info</h5>
            <h5>Start and follow up dates</h5>
            <h5>Prescribed medicines</h5>
            <h5>Private notes</h5>
          </div>

          <div class="col-md-4">
            <h3>Filter by</h3>

            <h5>Labels</h5>
            <h5>Names</h5>
            <h5>Upcoming follow ups</h5>
            <h5>Recent cases</h5>

          </div>
            

            
          </div>
        </div>
      </div>
    </div>  



    <div class="row managepatient-filter noleft-right" >
      <div class="col-md-12" style="text-align: center;">
        <input type="button" class="addUser" value="ADD PATIENT" data-toggle="modal" data-target="#addpatient" style="margin-top: 60px;">
      </div>
    </div>
  </div>


    <!-- End of no patients found -->

<?php } }?>


<!-- PATIENT PRIVATE NOTES MODAL -->
<div id="privatenotes" class="modal fade" role="dialog">
  <div class="modal-dialog" >
    <div class="modal-content">
      <form name="privatenotes" id="setPrivateNoteForm" >
        <div class="modal-body">
          <div class="row">
              <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;" src="../../assets/images/close.png"></button>
          </div>
          <div class="alert"></div>
          <div class="row modalstyle">
            <div class="col-md-12">
              <h2 style="text-align: left;margin-top: 0;">Private Notes</h2>
              <!-- <h4 style="text-align: left;" class="display-only"></h4> -->
              <div style="text-align: left;" class="showNotes display-only" ></div>
              <textarea class="form-control Notes editNotes" placeholder="Please add note here" name="patientNote" id="patientNote" style="resize: none;"></textarea>
               
              
            </div>
          </div>
          <input type="hidden" name="form_type" value="setPrivateNote" class="form-control">
          <input type="hidden" name="doctor_patient_id" value="" class="form-control dp_id">
        </div>
        <div class="modal-footer" style="text-align: left;">
        <button type="button" class="mod-footer-btn" id="editbtn" onclick="showprivatesavebtn()">Edit</button>
          <button type="submit" class="mod-footer-btn" id="savebtn" style="display: none;">Save</button>
          <button type="button" class="mod-footer-btn" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>

  </div>
</div>



<!-- DELETE PATIENT MODAL -->
<div id="deletepatient" class="modal fade" role="dialog">
  <div class="modal-dialog" >
    <div class="modal-content">
    <form name="deletepatient" id="deletePatientForm" >
      <div class="modal-body">
        <div class="row">
            <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;" src="../../assets/images/close.png"></button><!-- 
            <div class="alert" style="margin-right: 34px;"> -->
        </div>
        

          <div class="alert alert-danger"></div>
         


        <input type="hidden" name="form_type" value="deletePatient" class="form-control">
        <input type="hidden" name="doctor_patient_id" value="" class="form-control dp_id">
         <input type="hidden" name="deleted_patient_id" value="" class="form-control p_id">
        <div class="row modalstyle">
          <div class="col-md-12">
            <h2 style="text-align: center;margin-top: 0;">Delete Patient</h2>
            <h4>Are you sure you want to delete this patient?</h4>
          </div>

          <div class="col-md-12" style="text-align: center;">
            <img src="../../assets/images/delete.png" style="height: 165px;width: 165px;">
          </div>
          

        </div>

      </div>
      <div class="modal-footer" style="text-align: left;">
        <button type="submit" class="mod-footer-btn" >Delete</button>
        <button type="button" class="mod-footer-btn" data-dismiss="modal">Cancel</button>
      </div>
    </form>
    </div>

  </div>
</div>

      
<!-- ADD PATIENT MODAL -->
<div id="addpatient" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
     <form name="createPatient" id="addPatientForm">
      <div class="modal-body addpatient">
        <div class="row">
            <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;" src="../../assets/images/close.png"></button>            
        </div>

        <div class="alert"></div>

        <h2 style="margin-left: 10px;color: #000;margin-top: 0;">Add Patient</h2>
            <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <h4>Mobile <span class="req">*</span></h4>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-12" style="display:inline-flex;">
                  <?php $countries=getAllContries($conn); //printArr($countries); ?>
                  <select class="form-control  minimal" id="country_code" name="country_code"  style="width:25%;display:inline-block;">
                         <?php               
                  foreach($countries['errMsg'] as $countryId=>$countryDetails){
                    $countryName = $countryDetails["name"];
                    $country_code= $countryDetails["country_code"];
                    $selected = "";
                    if($countryId==101)
                      $selected = "selected";
                    if(!empty($country_code)){
                    ?>
                    <option data-id="<?php echo $country_code; ?>" value="<?php echo $country_code; ?>" <?php echo $selected; ?>><?php echo $country_code; ?></option>
                    <?php 
                  }}
                  ?> 
            </select>
                <input type="number" name="user_mob" id="mob_no" class="form-control" autocomplete="off" style="width:74%;">
              </div>
            </div>

            <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <h4>Email</h4>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="email" name="user_email" id="uemail" class="form-control">
              </div>
            </div>
            <div class="row addpatient-fields">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <h4>Name <span class="req">*</span></h4>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" name="user_first_name" id="ufname" class="form-control" maxlength="20">
              </div>
            </div>
            <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <h4>Last name <span class="req">*</span></h4>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" name="user_last_name" id="ulname" class="form-control"  maxlength="20">
              </div>
            </div>



           

            <!-- <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <h4>Mobile <span class="req">*</span></h4>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-12" style="display:inline-flex;">
                 <select class="form-control  minimal" name="country_code"  style="width:25%;display:inline-block;">
                          <option>+91</option>
                           <option>+1</option>
                            <option>+44</option>
            </select>
                <input type="number" name="user_mob" class="form-control" style="width:74%;">
              </div>
            </div>

            <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <h4>Email</h4>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="email" name="user_email" class="form-control">
              </div>
            </div> -->

            <!-- <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <h4>Date of Birth <span class="req">*</span></h4>
              </div>
             

              <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="date" >
                      <div class="input-group input-append date" id="datePicker1" style="margin-bottom:15px;">
                        <input type="text" class="form-control" name="user_dob" style="margin-bottom:0px;" />
                        <span class="input-group-addon add-on"><img src="../../assets/images/datepicker.png" style="width: 25px;"></span>
                      </div>
                </div>  
             </div>
            </div> -->



            <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                 <h4>Date of Birth <span class="req">*</span></h4>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-12" atyle="position:relative">
                <input type="text" id="datePicker1" name="user_dob"  class="form-control" style="background-color:#fff;">
                <img src="../../assets/images/datepicker.png" onclick="showcalender()" style="cursor:pointer;width:25px;position:absolute;top:10px;right:25px;">
              </div>
            </div>





            <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <h4>Gender <span class="req">*</span></h4>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <select class="form-control minimal" id="ugender" name="user_gender" >
                <option selected="" disabled="">Select gender</option>
                  <option >Male</option>
                  <option>Female</option>                  
                  <option>Transgender</option> 
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <h4>Label</h4>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <select class="form-control minimal" name="label" >
                  <option selected="" disabled="">Select label</option>
                  <option value="vip">VIP</option>
                  <option value="emergency">Emergency</option>                  
                  <option value="discuss">To Discuss</option> 
                  <option value="unimportant">Unimportant</option> 
                  <option value="improving">Improving</option>
                  <option value="notimproving">Not Improving</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <h4>Private notes</h4>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <textarea class="form-control note_add"  name="private_notesadd"  style="resize: none;"></textarea>
              </div>
            </div>

            <div class="row" style="margin-top : 20px;">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <h4 style="margin-top:35px;">Upload picture </h4>
              </div>
               <div class="col-md-7 col-sm-7 col-xs-12">
                <img id="user-pic" src="../../assets/images/cam.png" class="img-circle cam-pic" style="width:100px;object-fit:cover;height:100px;cursor:pointer;">
                 <input type="text" id="img1" class="form-control" readonly style="float: right;margin-top: 25px;width:60%;background-color:#fff;" value="Choose file">
                 <input type="file" name="profile_pic" id="select-img" class="form-control" style="position:relative;overflow:hidden;display: none;">
              </div>

            </div>

 

            <input type="hidden" name="form_type" value="addPatient" class="form-control">
            <input type="hidden" id="userStatus" name="userStatus" value="0" class="form-control">
            <input type="hidden" id="userId" name="user_id" value="" class="form-control">

          </div>
          <div class="modal-footer" style="text-align: left;">
            <input type="submit" name="addpatient"  value="Add" class="mod-footer-btn createPatient" >
            <button type="button" class="mod-footer-btn" data-dismiss="modal">Cancel</button>
          </div>
        </form>
    </div>

  </div>
</div>

<!-- EDIT PATIENT MODAL -->
<div id="editpatient" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <form name="editpatient" id="editPatientForm">
      <div class="modal-body addpatient">
        <div class="row">
            <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;" src="../../assets/images/close.png"></button>
        </div>
        <div class="alert"></div>
        <h2 style="margin-left: 10px;color: #000;margin-top: 0;">Edit patient info</h2>
        <div class="row addpatient-fields">
          <div class="col-md-3 col-sm-3 col-xs-12">
            <h4>Name <span class="req">*</span></h4>
          </div>
          <div class="col-md-7 col-sm-7 col-xs-12">
            <input type="text" name="user_first_name" class="form-control fnm" value="Emily">
          </div>
        </div>
        <div class="row">
          <div class="col-md-3 col-sm-3 col-xs-12">
            <h4>Last name <span class="req">*</span></h4>
          </div>
          <div class="col-md-7 col-sm-7 col-xs-12">
            <input type="text" name="user_last_name"  class="form-control lnm" value="Jhonson">
          </div>
        </div>

        <div class="row">
          <div class="col-md-3 col-sm-3 col-xs-12">
            <h4>Mobile <span class="req">*</span></h4>
          </div>
          <div class="col-md-7 col-sm-7 col-xs-12" style="display:inline-flex;">
           <?php $countries=getAllContries($conn); //printArr($countries); ?>
             <select class="form-control country_code minimal"  name="country_code" style="width:25%;display:inline-block;">
                  <?php               
                  foreach($countries['errMsg'] as $countryId=>$countryDetails){
                    $countryName = $countryDetails["name"];
                    $country_code= $countryDetails["country_code"];
                    $selected = "";
                    if($countryId==101)
                      $selected = "selected";
                    if(!empty($country_code)){
                    ?>
                    <option data-id="<?php echo $country_code; ?>" value="<?php echo $country_code; ?>" <?php echo $selected; ?>><?php echo $country_code; ?></option>
                    <?php 
                  }}
                  ?> 
            </select>
            <input type="number" name="user_mob" pattern="[0-9]{5}[-][0-9]{7}[-][0-9]{1}"  class="form-control mno" value="+987 26 25 25" style="width:74%;">
          </div>
        </div>

        <div class="row">
          <div class="col-md-3 col-sm-3 col-xs-12">
            <h4>Email</h4>
          </div>
          <div class="col-md-7 col-sm-7 col-xs-12">
            <input type="email" name="user_email" class="form-control email" value="">
          </div>
        </div>

        <!-- <div class="row">
          <div class="col-md-3 col-sm-3 col-xs-12">
            <h4>Date of Birth <span class="req">*</span></h4>
          </div>
          <div class="col-md-7 col-sm-7 col-xs-12">
              <div class="date" >
                      <div class="input-group input-append date" id="datePicker" style="margin-bottom:15px;">
                        <input type="text" class="form-control dob" name="user_dob" style="margin-bottom:0px;" />
                        <span class="input-group-addon add-on"><img src="../../assets/images/datepicker.png" style="width: 25px;"></span>
                      </div>
              </div>  
          </div>
        </div> -->


        <div class="row">
              <div class="col-md-3 col-sm-3 col-xs-12">
                 <h4>Date of Birth <span class="req">*</span></h4>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-12" atyle="position:relative">
                <input type="text" id="datePicker" name="user_dob" class="form-control dob" style="background-color:#fff;">
                <img src="../../assets/images/datepicker.png" style="width:25px;position:absolute;top:10px;right:25px;">
              </div>
            </div>

        <div class="row">
          <div class="col-md-3 col-sm-3 col-xs-12">
            <h4>Gender <span class="req">*</span></h4>
          </div>
          <div class="col-md-7 col-sm-7 col-xs-12">
            <select class="form-control gender minimal" name="user_gender">
              <option>Male</option>
              <option>Female</option>                  
              <option>Transgender</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3 col-sm-3 col-xs-12">
            <h4>Label</h4>
          </div>
          <div class="col-md-7 col-sm-7 col-xs-12">
            <select class="form-control label1 minimal" name="label" >
              <option selected="" disabled="">Select label</option>
              <option value="vip">VIP</option>
              <option value="emergency">Emergency</option>                  
              <option value="discuss">To Discuss</option> 
              <option value="unimportant">Unimportant</option> 
              <option value="improving">Improving</option>
              <option value="notimproving">Not Improving</option>
            </select>
          </div>
        </div>


        <div class="row">
          <div class="col-md-3 col-sm-3 col-xs-12">
            <h4>Private notes</h4>
          </div>
          <div class="col-md-7 col-sm-7 col-xs-12">
            <textarea class="form-control note_edit" name="private_notesedit" style="resize: none;"></textarea>
          </div>
        </div>


        <div class="row" style="margin-top:20px;">
              <div class="col-md-3 col-sm-3 col-xs-12">
                <h4>Upload picture</h4>
              </div>
               <div class="col-md-7 col-sm-7 col-xs-12">
                <img id="user-pic1" src="../../assets/images/cam.png" class="img-circle cam-pic" style="width:100px;object-fit:cover;height:100px;cursor:pointer;">
                 <input type="text" id="img2" class="form-control" readonly style="float: right;margin-top: 25px;width:60%;background-color:#fff;" value="Choose file">
                 <input type="file" name="profile_pic" id="select-img1" class="form-control" style="position:relative;overflow:hidden;display: none;">
              </div>


            </div>

        <input type="hidden" name="form_type" value="editPatient" class="form-control">
        <input type="hidden" name="patient_id" value="" class="form-control p_id">
        <input type="hidden" name="doctor_patient_id" value="" class="form-control dp_id">

      </div>
     
      <div class="modal-footer" style="text-align: left;">
        <input type="submit" name="editpatient"  value="Save" class="mod-footer-btn" />
        <button type="button" class="mod-footer-btn" data-dismiss="modal">Cancel</button>
      </div>
    </form>
    </div>

  </div>
</div>

<!-- PATIENT SET STATUS MODAL -->
<div id="setstatus" class="modal fade" role="dialog">
  <div class="modal-dialog" >
    <div class="modal-content">
      <form name="label" id="setLabelForm">
        <div class="modal-body">
          <div class="row">
              <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;" src="../../assets/images/close.png"></button>
              </div>
          <div class="alert"></div>
          <div class="row modalstyle">
            <div class="col-md-12">
              <h2 style="text-align: left;margin-top: 0;margin-left: 20px;">Set Status</h2>
                <ul>
                  <li>
                    <input type="radio" id="status-vip" class="vip" value="vip" name="patientLabel">
                    <label for="status-vip">VIP</label>
                    
                    <div class="check vip"></div>
                  </li>
                  
                  <li>
                    <input type="radio" id="status-emergency" class="emergency" value="emergency"   name="patientLabel">
                    <label for="status-emergency">Emergency</label>
                    
                    <div class="check emergency"><div class="inside"></div></div>
                  </li>
                  
                  <li><!--  id="status-discuss" -->
                    <input type="radio" class="discuss"  id="status-discuss" value="discuss" name="patientLabel">
                    <label for="status-discuss">To Discuss</label>
                    
                    <div class="check todiscuss"><div class="inside"></div></div>
                  </li>

                  

                  <li>
                    <input type="radio" id="status-uninmportant" class="unimportant" value="unimportant" name="patientLabel">
                    <label for="status-uninmportant">Unimportant</label>
                    
                    <div class="check unimportant"><div class="inside"></div></div>
                  </li>


                  <li>
                    <input type="radio" id="status-improving" class="improving" value="improving" name="patientLabel">
                    <label for="status-improving">Improving</label>
                    
                    <div class="check improving"><div class="inside"></div></div>
                  </li>


                  <li>
                    <input type="radio" id="status-notimp" class="notimproving" value="notimproving" name="patientLabel">
                    <label for="status-notimp">Not Improving</label>
                    
                    <div class="check notimproving"><div class="inside"></div></div>
                  </li>
                </ul>
            </div>
            <input type="hidden" name="form_type" value="setLabel" class="form-control">
            <input type="hidden" name="doctor_patient_id" value="" class="form-control dp_id">
          </div>

        </div>
       
        <div class="modal-footer" style="text-align: left;margin-left: 15px;">
          <button type="submit" class="mod-footer-btn" id="set_status_btn" >Set</button>
          <button type="button" class="mod-footer-btn" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>

  </div>
</div>



</main> 



<?php include("../modals.php"); ?> 
<?php  include('../footer.php'); ?>

<script type="text/javascript">

$(document).ready(function() {

  $('.payu').click(function(){
    //window.location.href='../dashboard/doctorsDashboard.php?payuStatus=payment';      //ALERT-KDR  
  });

  $('.freePlan').click(function(){
     //window.location.href='../dashboard/doctorsDashboard.php?payuStatus=freeTrial';    //ALERT-KDR
  });
$(".patientModal").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>By categories listed below.</span></li></ul></div>');
  });
  $('#mob_no').change(function(){

    var value=$(this).val();
    //alert(value);

    $.ajax({type: "POST",
            url:"../../controllers/managePatientController.php",
            data:{mob_no:value,
                  type:'checkNumber'},
            dataType:'json',
            /*beforeSend: function () {
              $ele.find('.stopAccess').show();
            }*/
      })
      .done(function(data) {
       // alert("hiii");
        console.log(data);
        if(data['errCode']==-1){
          var dob=data['dob'];
          data=data['errMsg'];
          console.log(data['user_image']);
          $("#ulname").attr('disabled',"disabled");
          $("#ufname").attr('disabled',"disabled");
          $("#country_code").attr('disabled',"disabled");
          $("#select-img").attr('disabled',"disabled");
          $("#uemail").attr('disabled',"disabled");
          $("#datePicker1").attr('disabled',"disabled");
          $("#ugender").attr('disabled',"disabled");
          $("#user-pic").attr("src", data['user_image']);
          $("#ufname").val(data['user_first_name']);
          $("#ulname").val(data['user_last_name']);
            $("#datePicker1").val(dob);
          $("#ugender").val(data['user_gender']);
          $("#country_code").val(data['country_code']);
          if(data['user_email']!='dummy'+data['user_mob']+'@eHeilung.com'){
            $('#uemail').val(data['user_email']);
          }
          $("#userStatus").val(1);
          $("#userId").val(data['user_id']);

        }else{
            //$('form#addPatientForm').trigger("reset");
            $("#ulname").removeAttr('disabled');
            $("#ufname").removeAttr('disabled');
            $("#country_code").removeAttr('disabled');
            $("#select-img").removeAttr('disabled');
            $("#uemail").removeAttr('disabled');
            $("#datePicker1").removeAttr('disabled');
            $("#ugender").removeAttr('disabled');
            $("#ulname").val('');
            $("#ufname").val('');
            $("#select-img").val('');
            $("#uemail").val('');
            $("#datePicker1").val('');
            $("#ugender").val('');
            $("#user-pic").attr("src", "../../assets/images/cam.png");
            $("#userStatus").val(0);
        }
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("error");
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       }) 
  });
/*
function checkNumber(value){
  alert(value);
}
*/  $('.addUser').click(function(){
    CKEDITOR.replace( 'private_notesadd' );
    $('.alert').removeClass('alert-danger');
    $('.alert').html('');
  });
  $('form#addPatientForm').submit(function(event) {
    /* Act on the event */
    var type="addPatient";
    //var formdata=$('#contactForm').serialize();
    var list1 = $("#cke_1_contents .cke_wysiwyg_frame").contents().find("body").html();
    var formdata = new FormData($(this)[0]);    
   formdata.append("private_notesadd",list1);
    //console.log(formdata);
   // location.href="controllers/signUpController.php";
    ajaxCall('../../controllers/managePatientController.php',formdata,$(this));
$("#addpatient").scrollTop(0);
    event.preventDefault();
 
    
  });
 $('form#editPatientForm').submit(function(event) {


  /* Act on the event */
  var type="editPatient";
  //var formdata=$('#contactForm').serialize();
  var list1 = $("#cke_1_contents .cke_wysiwyg_frame").contents().find("body").html();

  var formdata = new FormData($(this)[0]);
   formdata.append("private_notesedit",list1);
  //console.log(formdata);
 // location.href="controllers/signUpController.php";
  ajaxCall('../../controllers/managePatientController.php',formdata,$(this));

  $("#editpatient").scrollTop(0);
  event.preventDefault();


});


 $('form#setLabelForm').submit(function(event) {
  /* Act on the event */
  var type="setLabel";
  //var formdata=$('#contactForm').serialize();
  var formdata = new FormData($(this)[0]);
  //console.log(formdata);
 // location.href="controllers/signUpController.php";

   // global_loader('Y','set_status_btn','saving','#000','Y','Saved');
  ajaxCall('../../controllers/managePatientController.php',formdata,$(this));

  event.preventDefault();
$( "#setLabelForm" ).scrollTop( 300 );
});
 $('form#setPrivateNoteForm').submit(function(event) {
  /* Act on the event */
  var type="setPrivateNote";
  //var formdata=$('#contactForm').serialize();

var list1 = $("#cke_1_contents .cke_wysiwyg_frame").contents().find("body").html();

  var formdata = new FormData($(this)[0]);
  formdata.append("richPrivateNote",list1);
  //console.log(formdata);
 // location.href="controllers/signUpController.php";
  ajaxCall('../../controllers/managePatientController.php',formdata,$(this));

  event.preventDefault();

  // $('.showNotes').css("display","block");
  $('.editNotes').css("display","none");
  $("#editbtn").css("display","inline-block");
  $("#savebtn").css("display","none");
 $( "#setPrivateNoteForm" ).scrollTop( 300 );

});
  $('form#deletePatientForm').submit(function(event) {
  /* Act on the event */
  var type="deletePatient";
  //var formdata=$('#contactForm').serialize();
  var formdata = new FormData($(this)[0]);
  //console.log(formdata);
 // location.href="controllers/signUpController.php";
  ajaxCall('../../controllers/managePatientController.php',formdata,$(this));

  event.preventDefault();
 $( "#deletePatientForm" ).scrollTop( 300 );
});

  /*ajax definition starts*/
    function ajaxCall(url,formdata,$ele){
    $.ajax({type: "POST",
            url:url,
            data:formdata,
            dataType:'json',
            cache: false, 
            contentType: false,                
            processData: false,
            /*beforeSend: function () {
              $ele.find('.stopAccess').show();
            }*/
      })
      .done(function(data) {
        if(data['errCode']==-1){
          //alert(data['errMsg']);
          var newUrl = refineUrl();
          window.history.pushState("object or string", "Title",newUrl );
          $('.alert').css("display","block");
          $('.alert').removeClass('alert-danger').addClass('alert-success');
          $('.alert').html('<strong>Success ! </strong>'+data['errMsg']);
          setTimeout(function(){
            location.reload(true);
          },3000);
        }
        else if(data['errCode']==6){
          $('.alert').css("display","block");
          $('.alert').removeClass('alert-success').addClass('alert-danger');
          $('.alert').html('<strong>Error ! </strong>'+data['errMsg']);
           /*setTimeout(function(){
            location.reload(true);
          },3000);*/
        }else{
          $('.alert').css("display","block");
          $('.alert').removeClass('alert-success').addClass('alert-danger');
          $('.alert').html('<strong>Error ! </strong>'+data['errMsg']);
          /* setTimeout(function(){
            location.reload(true);
          },3000);*/
           //alert(data['errMsg']);
        }        
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log("error");
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       })  

  }
  /*ajax definition ends*/
});
function editPatientInfo(patient_id,doctor_patient_id){
$('form#editPatientForm').trigger("reset");
$('.alert').removeClass('alert-danger');
$('.alert').html('');
$('.alert').css("display","none");
//alert(doctor_patient_id);
    $.ajax({type: "POST",
            url:"../../controllers/managePatientController.php",
            data:{patient_id:patient_id,
                  doctor_patient_id:doctor_patient_id,
                  type:'edit'},
            dataType:'json',
            /*beforeSend: function () {
              $ele.find('.stopAccess').show();
            }*/
      })
      .done(function(data) {

        console.log(data);
        var dob=data['dob'];
        data=data['errMsg'];
        var qwe=  HTMLDecode(data[1]['private_notes']);
        $('.note_edit').val(qwe);
        // CKEDITOR.replace( 'private_notesedit' );
        $('.p_id').val(data[1]['patient_id']);
        $('.dp_id').val(data[1]['doctor_patient_id']);
        $('.fnm').val(data[0]['user_first_name']);
        $('.lnm').val(data[0]['user_last_name']);
        $('.mno').val(data[0]['user_mob']);
        if(data[0]['user_email']!='dummy'+data[0]['user_mob']+'@eHeilung.com'){
        $('.email').val(data[0]['user_email']);
        }
        $('.dob').val(dob);
        //$('.dob').val(data[0]['user_dob']);
        $('.country_code').val(data[0]['country_code']);        
        $('.gender').val(data[0]['user_gender']);
        if(data[1]['label']!="")
        $('.label1').val(data[1]['label']);/*
        else
        $('.label1').val("");*/
        
        if(data[0]['user_image'] != null)
        {
            $("#user-pic1").attr("src", data[0]['user_image']);
        }
         //$("#select-img1").val(data[0]['user_image']); 
        $('.country_code').attr('disabled',"disabled"); 
        $(".fnm").attr('disabled',"disabled");
        $(".lnm").attr('disabled',"disabled");
        $(".mno").attr('disabled',"disabled");
        $(".email").attr('disabled',"disabled");
        $("#datePicker").attr('disabled',"disabled");
        $(".gender").attr('disabled',"disabled");
        $("#select-img1").attr('disabled',"disabled"); 
        //$("#user-pic1").attr("src", data['user_image']);
        
        CKEDITOR.replace( 'private_notesedit' );
        $("#editpatient").modal();
       
              
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        //alert("error");
        console.log('error');
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       }) 

}

 
  $('#editpatient').on('hidden.bs.modal', function (){
   // do something ...

 // CKEDITOR.instances.private_notesedit.destroy();
if  (CKEDITOR.instances.private_notesedit != null && CKEDITOR.instances.private_notesedit != 'undefined')
     {
       CKEDITOR.instances.private_notesedit.destroy();
     }
 // location.reload();
 });

function editPatientLabelInfo(patient_id,doctor_patient_id){
  $('.alert').removeClass('alert-danger');
  $('.alert').html('');
  $('.alert').css("display","none");



//alert(doctor_patient_id);
    $.ajax({type: "POST",
            url:"../../controllers/managePatientController.php",
            data:{patient_id:patient_id,
                  doctor_patient_id:doctor_patient_id,
                  type:'editLabel'},
            dataType:'json',
            /*beforeSend: function () {
              $ele.find('.stopAccess').show();
            }*/
      })
      .done(function(data) {
        console.log(data);

        data=data['errMsg'];
        $('.p_id').val(data['patient_id']);
        $('.dp_id').val(data['doctor_patient_id']);
     var ele= 'input[type=radio]#'+data['label'];
     //alert(ele);
     if(data['label']!=""){
        $('.'+data['label']).attr( "checked",true );
      }
        //$('#'+data['label']).addClass('checked');
        $("#setstatus").modal();
              
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert("error");
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       }) 

}
function patientCaseHistory(patient_id,doctor_patient_id){
  window.location.href='patientCaseHistory.php?patient_id='+patient_id+'&doctor_patient_id='+doctor_patient_id;
}
function patientCaseHistory1(patient_id){
  window.location.href='patientCaseHistory.php?patient_id='+patient_id;
}
function startCase(patient_id,doctor_id){
  window.location.href='../startcase/step1.php?patient_id='+patient_id+'&doctor_id='+doctor_id;
}
function decodeEntities(encodedString) {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = encodedString;
    return textArea.value;
}



 function HTMLDecode(s){
return jQuery('<div></div>').html(s).text();
}





function editPatientNotesInfo(patient_id,doctor_patient_id){
   $('.alert').removeClass('alert-danger');
   $('.alert').html('');
   $('.alert').css("display","none");
  $.ajax({type: "POST",
            url:"../../controllers/managePatientController.php",
            data:{patient_id:patient_id,
                  doctor_patient_id:doctor_patient_id,
                  type:'editNotes'},
            dataType:'json',
           
      })
      .done(function(data) {
        console.log(data);
         
          var note=HTMLDecode(data['private_notes']);
         

        data=data['errMsg'];
        $('.p_id').val(data['patient_id']);
        $('.dp_id').val(data['doctor_patient_id']);
       
        $(".display-only").html(HTMLDecode(data['private_notes']));


        if(data['private_notes']!='')
        {
          
         // $('.showNotes').html(note);
         $('.editNotes').html(data['private_notes']);
          $('.editNotes').css("display","none"); 
          $('.showNotes').css("display","block");
          $("#editbtn").css("display","inline-block");
          $("#savebtn").css("display","none");
        }else{
          //alert( data['private_notes']);
          $('.showNotes').css("display","none");
          $('.editNotes').css("display","block");
          $("#editbtn").css("display","none");
          $("#savebtn").css("display","inline-block");
        }
       

        $("#privatenotes").modal();

      /*  $('.showNotes').css("display","block");
        $('.editNotes').css("display","none");
        $("#editbtn").css("display","inline-block");
        $("#savebtn").css("display","none");
              */
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert("error");
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       }) 

}

function deletePatientInfo(patient_id,doctor_patient_id){
  $('.alert').removeClass('alert-danger');
  $('.alert').html('');
  $('.alert').css("display","none");
  $('.p_id').val(patient_id);
  $('.dp_id').val(doctor_patient_id);
  $("#deletepatient").modal();
}

function showprivatesavebtn(){

  $('.showNotes').css("display","none");
  $('.editNotes').css("display","block");
  $("#editbtn").css("display","none");
  $("#savebtn").css("display","inline-block");

    CKEDITOR.replace( 'patientNote' );

}

$(document).ready(function() {

    // $('#datePicker')
    //     .datepicker({
    //        format: 'dd/mm/yyyy',
    //         changeMonth: true,
    //   changeYear: true,
    //   maxDate: "0D"
    //     })

    // $('#datePicker1')
    //     .datepicker({
    //         format: 'dd/mm/yyyy',
    //         changeMonth: true,
    //   changeYear: true,
    //   maxDate: "0D"
    //     })  


var currentDate = new Date();

    $("#datePicker1").datepicker({
        dateFormat: 'dd/mm/yy',
        maxDate: 0,
        changeMonth: true,
        changeYear: true,
        yearRange: '1930:2030'
    }).attr('readonly', 'readonly');
    //$("#datePicker1").datepicker("setDate", currentDate);


     $("#datePicker").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        maxDate: 0,
        changeYear: true,
        yearRange: '1930:2030'
    }).attr('readonly', 'readonly');
    //$("#datePicker").datepicker("setDate", currentDate);

     
       
});


$("html, body").on("DOMMouseScroll MouseScrollEvent MozMousePixelScroll wheel scroll", function ()
{
   $('#datePicker').datepicker("hide");
   $('#datePicker1').datepicker("hide");

   // $(#datePicker).trigger('blur');
   // $(#datePicker1).trigger('blur');
  $(':focus').blur();
});

// $( document ).scroll(function(){
//         $('#addpatient #datePicker1').datepicker('place'); 
//         $('#editpatient #ui-datepicker-div').datepicker('place');
// // $.datepicker._pos[1] += input.offsetHeight + document.body.scrollTop;
// //         $('#addpatient #datePicker1').datepicker('hide');
//     });

// var t ;
// $( document ).on(
//     'DOMMouseScroll mousewheel scroll',
//     '#addpatient', 
//     function(){       
//          window.clearTimeout( t );
//           t = window.setTimeout( function(){
//              $('#addpatient #datePicker1').datepicker('place');
//              // $.datepicker._pos[1] += input.offsetHeight + document.body.scrollTop;
//           }, 0 ); 
//     }
// );

// $( document ).on(
//     'DOMMouseScroll mousewheel scroll',
//     '#editpatient', 
//     function(){       
//         window.clearTimeout( t );
//         t = window.setTimeout( function(){            
//             $('#datePicker').datepicker('place');
//             // $.datepicker._pos[1] += input.offsetHeight + document.body.scrollTop;
//         }, 0 );        
//     }
// );


 $( document ).scroll(function(){
        $('#editpatient #datepicker').datepicker('place'); //#modal is the id of the modal
    });


/*$('.label_sort').click(function(){
  //alert('hii');
  var label_sort=$(this).val();
  var name_sort = $('input[name="name_sort"]:checked').val();
  sortFunction(label_sort,name_sort,pageNo=1,$limit=10);
      
});*/

/*$('form#labelForm').submit(function(event) {
    
   alert('hiii123');
     var label_sort = new FormData($('form#labelForm')[0]); 
       var name_sort = $('input[name="name_sort"]:checked').val();
  sortFunction(label_sort,name_sort,pageNo=1,$limit=10);
    event.preventDefault();
 
    
  });*/
/*$('.labelSort').click(function(){
  //alert('hii');
   var name_sort = $('input[name="name_sort"]:checked').val();
  var label_sort = new FormData($('form#labelForm')[0]); 
  sortFunction(label_sort,name_sort,pageNo=1,$limit=10);
      
});*/

$( "form#labelForm" ).on( "submit", function( event ) {  
 // event.preventDefault();  
 //$('.dropdown-menu').fadeOut();
//$(".dropdown-status").dropdown("hide");
//alert('hiii');

$(".lablelclick").click();

  console.log( $( this ).serialize() );
var name_sort = $('input[name="name_sort"]:checked').val();
  var label_sort = $( this ).serialize(); 
sortFunction(label_sort,name_sort,pageNo=1,$limit=10);

});
$('.name_sort').click(function(){
 var name_sort=$(this).val();
  //var label_sort = $('input[name="label_sort"]:checked').val();
  var label_sort = $( "form#labelForm" ).serialize(); 
sortFunction(label_sort,name_sort,pageNo=1,$limit=10);
});

function sortFunction(label_sort,name_sort,pageNo=1,limit=10){
  //alert('hiii');
$.ajax({type: "POST",
            url:"../../controllers/searchsortController.php", 
            data:{filterLabel:label_sort,
                  filterName:name_sort,
                  pageNo:pageNo,
                  limit:limit,
                  type:'filter'},
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

$('#img1').click(function () {
    $('#select-img').click();
});
$('#user-pic').click(function () {
    $('#select-img').click();
});

$('#img2').click(function () {
    $('#select-img1').click();
});
$('#user-pic1').click(function () {
    $('#select-img1').click();
});



function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#user-pic').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
function readURL1(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#user-pic1').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }    
    
    $("#select-img").change(function(){
        readURL(this);
    });
     $("#select-img1").change(function(){
        readURL1(this);
    });

$(".search-box").keyup(function(){
 //alert(this.value);
  var search=$(this).val();
  //alert(search);
  searchFunction(search,pageNo=1,limit=10);
  
});


function searchFunction(search,pageNo=1,limit=10){
$.ajax({type: "POST",
            url:"../../controllers/searchsortController.php",
            data:{
                  search:search,
                  pageNo:pageNo,
                  limit:limit,
                  type:'search_patient'},
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
</script>


<script>
function myFunction() {
    document.getElementById("myForm").submit();
}
</script>

<script type="text/javascript">
    function showcalender(){
      $("#datePicker1").focus();             
    }
</script>


</body>
</html>
