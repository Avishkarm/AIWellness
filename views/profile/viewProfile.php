<?php

$pathPrefix="../";
$activeHeader = "doctorsArea";
session_start();

require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php");
require_once("../../models/userModel.php");
require_once("../../models/completeProfileModel.php");

  //database connection handling
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
  $conn = $conn["errMsg"];
} else {
      //printArr("Database Error");
  exit;
}

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
//printArr($userInfo);

$profProgress=1;
$totalFields=19;
if(!empty($userInfo['user_first_name'])){
  $profProgress++;
}
if(!empty($userInfo['user_last_name'])){
  $profProgress++;
}
if(!empty($userInfo['user_reg_no'])){
  $profProgress++;
}
if(!empty($userInfo['user_gender'])){
  $profProgress++;
}
if(!empty($userInfo['user_mob'])){
  $profProgress++;
}
if(!empty($userInfo['user_nationality'])){
  $profProgress++;
}
if(!empty($userInfo['user_marital_status'])){
  $profProgress++;
}
if(!empty($userInfo['user_dob'])){
  $profProgress++;
}
if(!empty($userInfo['highest_degree'])){
  $profProgress++;
}
if(!empty($userInfo['user_image'])){
  $profProgress++;
}
if(!empty($userInfo['user_address'])){
  $profProgress++;
}
if(!empty($userInfo['user_country'])){
  $profProgress++;
}
if(!empty($userInfo['user_state'])){
  $profProgress++;
}
if(!empty($userInfo['user_city'])){
  $profProgress++;
}
if(!empty($userInfo['user_zip'])){
  $profProgress++;
}
if(!empty($userInfo['user_landline_no'])){
  $profProgress++;
}
if(!empty($userInfo['user_alt_email'])){
  $profProgress++;
}
if(!empty($userInfo['height'])){
  $profProgress++;
}
if(!empty($userInfo['weight'])){
  $profProgress++;
}
$profProgress;
$progPercent=floor(($profProgress*100)/$totalFields);
//printArr(date_create(date('Y').'-'.date('m').'-'.date('d')));
//$getPlanExpiring=getPlanExpiring($userInfo['user_id'],$conn);  //ALERT-KDR
      //printArr($getPlanExpiring);
      //echo $getPlanExpiring['diff'];
 $access=1;     
//if($getPlanExpiring['diff']<=0 || $getPlanExpiring['diff']==""){//ALERT-KDR
//  $access=0;
//  //$redirectURL ="../dashboard/doctorsDashboard.php";
//  //header("Location:".$redirectURL); 
//}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once("../metaInclude.php"); ?>
  <style type="text/css">
    

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

h3{
  font-size: 16px;
}

@media(max-width: 768px){
  h1{
    font-size: 21px;
  }
  h2{
    font-size: 16px;
  }
}
    /*header{
      padding:7px 20px !important;
    }*/
  </style>
<link rel="stylesheet" type="text/css" href="../../assets/css/home.css?aghrd=r4564298">

<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">

 -->

  <main class="container" style="min-height: 100%;">
    <?php  include_once("../header.php"); ?> 


     <?php
     /*//ALERT-KDR
      if($getPlanExpiring['diff']==""){
    ?>
      <div class="row noleft-right"  >
      <div class="col-md-12">
        <div class="followup">
          <h4 style="cursor:pointer;">You dont have any plan.<a > Get free trial now!</a></h4>
        </div>
      </div>
    </div> 
     <?php
    }
    else if($getPlanExpiring['diff']<=0){
  ?>
    <div class="row noleft-right"  >
      <div class="col-md-12">
        <div class="followup">
          <h4 style="cursor:pointer;">Your plan has expired.<a class="payu"> Extend it now!</a></h4>
        </div>
      </div>
    </div> 
  <?php    
    }
    */
  ?>

 <?php //if($access==1) { ?>   

    <div class="row noleft-right profilepage" >
      <?php if(!($progPercent>=100)){?>
      <h4><img src="../../assets/images/iconuser.png"/> Your profile is only <?php echo $progPercent; ?>% complete. <a href="completeProfile.php">Update your profile now</a></h4>
      <?php } ?>

      <div class="col-md-12 col-sm-12 col-xs-12 my-profile" >
        <div class="" style="margin-top: 40px;padding-bottom: 100px;">

        <div class="col-md-3 col-sm-3 col-sm-12 doctorprofile">
        
           <?php if(!empty($userInfo['user_image'])){ ?>
          <img src="<?php echo $userInfo['user_image']; ?>" class="img-circle" >
          <?php }else { ?>
          <img src="../../assets/images/cam.png" class="img-circle" >
          <?php } ?>

          <input type="button" name="" class="edit-profile" value="EDIT PROFILE">
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12 doctorinfo">
          <h1><?php if(!empty($userInfo['title'])){ echo $userInfo['title'].'. '; } echo ucfirst(strtolower($userInfo['user_first_name'])).' '.ucfirst(strtolower($userInfo['user_last_name'])); ?></h1>
          <h2>Date of birth: <span><?php if(!empty($userInfo['user_dob'])) { echo date('d/m/Y', strtotime($userInfo['user_dob'])); ?> (age <?php echo calcAge($userInfo['user_dob']).')'; } ?></span></h2>
          <h2>Nationality: <span><?php echo $userInfo['user_nationality']; ?></span></h2>
          <h2 style="display:flex;"><img src="../../assets/images/loc.png"/><span><?php if(!empty($userInfo['user_city'])) { echo $userInfo['user_city']; } ?><?php if(!empty($userInfo['user_state'])) { echo '  '.$userInfo['user_state'];} ?><?php if(!empty($userInfo['user_country'])) { echo '  '.$userInfo['user_country']; } ?></span></h2>
        </div>
      </div>
    </div>


    <div class="col-md-12 col-sm-12 col-xs-12 infobox">
      <div class="personal-info">
        <div class="col-md-12">
          <h2>Personal info</h2>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <h3>Gender: <span><?php echo $userInfo['user_gender']; ?></span></h3>
          <h3>Marital Status: <span><?php echo $userInfo['user_marital_status']; ?></span></h3> 
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <h3>Highest degree: <span><?php echo $userInfo['highest_degree']; ?></span></h3>                   
          <h3>Registration N: <span><?php echo $userInfo['user_reg_no']; ?></span></h3>
          <!-- <h3>Height: <span><?php echo $userInfo['height']; ?> <?php if(!empty($userInfo['height'])) echo $userInfo['height_unit']; ?></span></h3>
          <h3>Weight: <span><?php echo $userInfo['weight']; ?> <?php if(!empty($userInfo['weight'])) echo $userInfo['weight_unit']; ?></span></h3> -->
        </div>
      </div>



      <div class="contact-info">
        <div class="col-md-12">
          <h2>Contact info</h2>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
          <h3><img src="../../assets/images/call.png" /><span><?php echo $userInfo['user_mob']; ?></span></h3>
          <h3><img src="../../assets/images/contmsg.png" /><span><?php echo $userInfo['user_email']; ?></span></h3>
          <h3><img src="../../assets/images/alt-email.png" /><span><?php echo $userInfo['user_alt_email']; ?></span></h3>
          <h3><img src="../../assets/images/home.png" /><span><?php echo $userInfo['user_address']; ?></span></h3>
          <h3><img src="../../assets/images/lan.png" /><span><?php echo $userInfo['user_landline_no']; ?></span></h3>
        </div>
        
      </div>
    </div>





    </div>
<?php //} ?>

</main> 
<?php include("../modals.php"); ?> 
<?php  include('../footer.php'); ?>
<script type="text/javascript">
  $('.edit-profile').click(function(){
    window.location.href='completeProfile.php';
  });


  $('.payu').click(function(){
    window.location.href='../dashboard/doctorsDashboard.php?payuStatus=payment';    
  });
</script>
</body>
</html>

