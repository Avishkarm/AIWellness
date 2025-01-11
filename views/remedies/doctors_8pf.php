<?php
$activeHeader = "doctorsArea"; 
$pathPrefix="../";
session_start();
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php"); 
require_once("../../models/userModel.php");
require_once("../../models/doctors_8pfModel.php");
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

$now=time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    // this session has worn out its welcome; kill it and start a brand new one
    printArr("You do not have sufficient privileges to access this page<br>Login to continue <a href='".$rootUrl."/controllers/logout.php'>Home</a> ");
  exit;
}

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

$pf = getPersonalityFactor($conn);
//printArr($pf);
if(noError($pf)){
  $pf = $pf['errMsg'];
}else{
  printArr("Error Fetching Personality Factor");
}

 

if(isset($_POST['subsave'])){
  $factor= array();
  $oldPF =array();
 // printArr($_POST);die;
  $factor['A']['factor_name'] = cleanQueryParameter($conn,$_POST['A']);
  $factor['A']['factor_remedy'] = cleanQueryParameter($conn,$_POST['A_remedies']);
  $factor['B']['factor_name'] = cleanQueryParameter($conn,$_POST['B']);
  $factor['B']['factor_remedy'] = cleanQueryParameter($conn,$_POST['B_remedies']);
  $factor['E']['factor_name'] = cleanQueryParameter($conn,$_POST['E']);
  $factor['E']['factor_remedy'] = cleanQueryParameter($conn,$_POST['E_remedies']);
  $factor['F']['factor_name'] = cleanQueryParameter($conn,$_POST['F']);
  $factor['F']['factor_remedy'] = cleanQueryParameter($conn,$_POST['F_remedies']);
  $factor['G']['factor_name'] = cleanQueryParameter($conn,$_POST['G']);
  $factor['G']['factor_remedy'] = cleanQueryParameter($conn,$_POST['G_remedies']);
  $factor['H']['factor_name'] = cleanQueryParameter($conn,$_POST['H']);
  $factor['H']['factor_remedy'] = cleanQueryParameter($conn,$_POST['H_remedies']);
  $factor['Q3']['factor_name'] = cleanQueryParameter($conn,$_POST['Q3']);
  $factor['Q3']['factor_remedy'] = cleanQueryParameter($conn,$_POST['Q3_remedies']);
  $factor['Q4']['factor_name'] = cleanQueryParameter($conn,$_POST['Q4']);
  $factor['Q4']['factor_remedy'] = cleanQueryParameter($conn,$_POST['Q4_remedies']);
 //printArr($factor);die;
  $df = getDoctorPF($_GET['patient_id'], $conn);
  $df = $df['errMsg']['doctor_8pf'];
  $oldPF = json_decode($df, true);
  $doctor_id=$userInfo['user_id'];
  $case_id=$_GET['case_id'];
  $oldPF[$case_id][$doctor_id] = $factor;
  //printArr($df);
  $setPF  = setDoctorPF($_GET['patient_id'], json_encode($oldPF), $conn);
  //printArr($setPF);

  if(noError($setPF)){ 
//echo "hii";
    $redirectURL = "remedies.php?doctor_id=".$doctor_id."&patient_id=".$_GET['patient_id']."&case_id=".$_GET['case_id'];
   /* $errMsg = "<p style='width:100%;' class='msg succ'>Successfully Saved</p>";
    $scrip = "<script>setTimeout(function(){
      window.location.href='".$redirectURL."';    
    },1000);</script>";*/
   // echo $redirectURL;
    header("Location:".$redirectURL);
  }else{
    $errMsg = "<p style='width:100%;' class='msg error'>Error Updating 8PF".$setPF['errMsg']."</p>";
    printArr("Error Updating 8PF".$setPF['errMsg']);
  }
}
/*$getPatientQuery=getDoctorsPatient($conn,$userInfo['user_id'],$pageNo,$limit=2);

if(noError($getPatientQuery)){
  $totalItems=$getPatientQuery['countCases'];
  $getPatientQuery = $getPatientQuery["errMsg"];
  $totalPageNo=ceil($totalItems/2);
//ceil($getCases['countCases']/5);

} else {

  //printArr("Error fetching case detailss");
  $getPatientQuery="No Data";
}
//printArr($getPatientQuery);
*/



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
        display: inline-block;
        max-width: 100%;
        margin-bottom: 21px;
        font-size: 16px;
        word-spacing: 2px;
        font-weight: normal!important;
        color: #444;
      }
      input[type="radio"] + label:before {
        border: 1px solid #555;
        content: "\2713";
        display: inline-block;
        font: 25px/1em sans-serif;
        height: 25px;
        margin:  0 1.0em 0 0;
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

.card{
  box-shadow: 0 0 15px #d6d2d2;
  min-height: 255px;
  margin-bottom: 35px;

}
.card label{
  display: flex;
 
  word-break: break-word;
}

h5 .heading-info{
   margin-left: 5px;
      vertical-align: super;
      cursor:pointer;
      height: 20px;
      margin-top: 5px;
}
  </style>
<!-- 
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.min.css">


<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">
 -->


  <main class="container" style="min-height: 100%;">

    <?php  include_once("../header.php"); ?>    
	   
  
     <div class="row noleft-right" style="margin-top:50px;margin-bottom:20px;padding-bottom:10px;" >
      <div class="col-md-12">
      <div class="col-md-12" style="border-bottom:1px solid #454545;">
        <h3>Your opinion about this patient</h3>
      </div>
      </div>
    </div>

    <form method="post" action="doctors_8pf.php?doctor_id=<?php echo $_GET['doctor_id'] ; ?>&patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" class="row-fluid margin-gap-50">
      <div class="row noleft-right get-remedies" >
        <?php 
          foreach($pf as $key=>$value){ ?>
            <div class="col-md-6 col-sm-6 col-xs-12 " >

              <div class="col-md-12 card <?php echo $value['factor_name']; ?>">
                <div>
                <h5><?php echo $value['factor_name'].': '.$value['factor_title'];?> 
<!--                    <img src="../../assets/images/info.png" class="heading-info" data-toggle="modal" data-target="#infoModal" />-->
                </h5>
                </div>
                <div>
                <input type="radio" id="<?php echo "radio1_".$value['factor_name']; ?>" name="<?php echo $value['factor_name']; ?>" value="<?php echo $value['high_score_description'];?>">
                <label for="<?php echo "radio1_".$value['factor_name']; ?>" data-factor="<?php echo $value['factor_name']; ?>" data-remdies="<?php echo $value['high_score_remedies'];?>"><?php echo $value['high_score_description'];?></label>
                </div>
                <div>
                <input type="radio" id="<?php echo "radio2_".$value['factor_name']; ?>" name="<?php echo $value['factor_name']; ?>" value="<?php echo $value['low_score_description'];?>">
                 <label for="<?php echo "radio2_".$value['factor_name']; ?>" data-factor="<?php echo $value['factor_name']; ?>" data-remdies="<?php echo $value['low_score_remedies'];?>"><?php echo $value['low_score_description'];?></label>     
                 </div> 
                 <div>
                <input type="radio" id="<?php echo "radio3_".$value['factor_name']; ?>" name="<?php echo $value['factor_name']; ?>" value="">
                 <label for="<?php echo "radio3_".$value['factor_name']; ?>" data-factor="<?php echo $value['factor_name']; ?>" data-remdies="">Don’t know</label>     
                 </div>   
                     
              </div>
              <input type="hidden" name="<?php echo $value['factor_name'].'_remedies'; ?>" id="<?php echo $value['factor_name'].'_remedies'; ?>" class="remedies">
            </div>
        <?php } ?>
      </div>
      
      <div class="row noleft-right  get-remediesbtn">
      <div class="errMsg" style="display: none;color:red;text-align: center;margin-bottom: 10px;"></div>
        <div class="col-md-12">
            <input type="submit" name="subsave" value="SAVE" class="save-btn">
            <!-- <button button type=""  onClick="" class="signupbtn load" style="width: 20%;display: none;" >Saving...<img src="../../assets/images/ajax-loader.gif"></button> -->
        </div>
     </div>
    </form> 

<!--     <div class="row noleft-right  get-remedies" >

      <div class="col-md-6 col-sm-6 col-xs-12" >
        <div class="col-md-12 card">
          <h5>B</h5>

          <input type="checkbox" id="chk5" name="chk5">
          <label for="chk5">Assertive, aggressive, stuborn, competitive (Dominance)</label>
          <input type="checkbox" id="chk6" name="chk6">
          <label for="chk6">Humble, mild, easily led, docile, accomodating (submissiveness)</label>
        </div>
      </div>


       <div class="col-md-6 col-sm-6 col-xs-12" >
        <div class="col-md-12 card" >
          <h5>E</h5>

          <input type="checkbox" id="chk7" name="chk7" >
          <label for="chk7">Happy-go-lucky, enthusiastic (Surgency)</label>

          <input type="checkbox" id="chk2" name="chk7">
          <label for="chk7">Sober, taciturn, serious (Desurgency)</label>
        </div>
      </div>

     </div> -->



     

    <!-- </div> -->

    
</main> 
<?php include("../modals.php"); ?>
<?php  include('../footer.php'); ?>





<script type="text/javascript">
$(document).ready(function(){
      $(".card label").click(function(){
        //alert($(this));
        var val=$(this).attr('data-factor');
        // alert(val);
        var remedy = $(this).attr('data-remdies');
         // alert(remedy);
       // alert($(this).parent().siblings('.remedies').val());
       //alert('#'+val+'_remedies');
        $(this).parent().siblings('#'+val+'_remedies').val(remedy);
        $('#'+val+'_remedies').val(remedy);
      })
    });

$('.save-btn').click(function(e){

  var blank = false;
$("input:radio").each(function() {
    var val = $('input:radio[name=' + this.name + ']:checked').val();
    if (val === undefined) {
        blank = true;
        return false;
    }
});
//alert(blank ? "At least one group is blank" : "All groups are checked");
if(blank){

//alert('hii');
$('.errMsg').html('Please answer all questions');
$('.errMsg').show();
e.preventDefault();
}else{
  $('.errMsg').hide();
}
});

</script>



</body>
</html>
