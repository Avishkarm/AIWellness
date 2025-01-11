<?php
$activeHeader = "doctorsArea"; 
$pathPrefix="../";
session_start();

require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php"); 
require_once("../../models/userModel.php");
require_once("../../models/doctors_8pfModel.php");
require_once("../../models/remediesModel.php");
require_once("../../models/dosNdontsModel.php");
require_once("../../models/startCaseModel.php");

  //database connection handling
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

//$date='2017-7-27';


$case_id=$_GET['case_id'];
$updateStepNo=updateStepNo($case_id,0,$conn);



    $chck8PF = check8PFUser($conn, $_GET['doctor_id'], $_GET['patient_id'],$_GET['case_id']);
  if(noError($chck8PF)){
    $chck8PF = $chck8PF['errMsg'];
    if(!$chck8PF['user8PF']){
      // 8PF doesn't exist
      //$redirectURL ="../remedies/doctors_8pf.php?doctor_id=".$_GET['doctor_id']."&patient_id=".$_GET['patient_id']."&case_id=".$_GET['case_id'];
      $redirectURL ="../remedies/doctors_8pf.php?doctor_id=".$_GET['doctor_id']."&patient_id=".$_GET['patient_id']."&case_id=".$_GET['case_id'];
      header("Location:".$redirectURL); 
      // Redirect browser 
      exit();
    }
  }else{
   /* printArr($chck8PF['errMsg']);
    $redirectURL = "../startcase/step1.php?doctor_id=".$_GET['doctor_id']."&patient_id=".$_GET['patient_id']."&case_id=".$_GET['case_id'];*/
  }

  $caseSheet = getDocCaseSheet($_GET['case_id'], $conn);
//printArr($caseSheet);
if(noError($caseSheet)){
  $rubricsQid_Aid = array();
  $caseSheet = $caseSheet["errMsg"];
  $RemName1=$caseSheet['primary_prescription'];
  $RemName2=$caseSheet['second_prescription'];
  $RemName3=$caseSheet['third_prescription'];
  $potency=$caseSheet['potency'];
  $dosage=$caseSheet['dosage'];
  //$caseDt = json_decode($caseSheet['caseId']['caseData'], true);
 //$rubricsQid_Aid = $caseDt['personal']+$caseDt['Mental']+$caseDt['physical']+$caseDt['Sensitive'];
  $allRemedies = calculate16PF_RubricRemedy($conn,$_GET['case_id'],$_GET['doctor_id'], $_GET['patient_id']);
  if(noError($allRemedies)){
    $allRemedies = $allRemedies['errMsg'];
    $remedy16PF=$allRemedies['remedy16PF'];
    //printArr($allRemedies);
    $remedyRubric=$allRemedies['remedyRubric'];
    $allRemedies=$allRemedies['allRemedies'];
 /*   //printArr($allRemedies);
   arsort($allRemedies);
    unset($allRemedies['']);*/
     //printArr($allRemedies);
  }else{
    printArr("Error".$allRemedies['errMsg']);
  }
} else {
  printArr("Error fetching case sheet");
  exit;
}

$currency = listCurrency($conn);

if(noError($currency)){
  $currency = $currency['errMsg'];
}else{
  printArr("Error Fetching Currency".$currency['errMsg']);
}


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
.alert-danger{
background color:#f99a9a;
}
.alert-success{
  background color:#87de87;
}

.card{
  box-shadow: 0 0 10px #d6d2d2;
}

.remedy-icon{
  margin-left: 5px;
  width: 15px;
  vertical-align: super;
}
.btn-save{
 background-color: #0dae04;
 color: #fff;
 text-align: center;
 padding: 10px;
 border: none;
outline: none;
min-width: 180px;
font-family: Montserrat-Regular;
border-radius: 8px;

}
.charges{
  border-color: #9b9b9b; width: 96%;   height: 50px;    border-radius: 0px;    margin-right: 0px;    margin-left: 4%;    background-color: white;    color: #000;    padding-left: 10px;    font-family: Montserrat-Regular;
}

.searchinput{
    float: right;
  }

  @media(max-width: 768px){
 
  .searchinput{
    float: left !important;
  }
 
}

select{
    -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-appearance: none;
  -moz-appearance: none;
}

select.minimal {
        background-image: linear-gradient(45deg, transparent 50%, gray 50%), linear-gradient(135deg, gray 50%, transparent 50%), linear-gradient(to right, #ccc, #ccc);
    background-position: calc(100% - 24px) calc(1em + 2px), calc(100% - 16px) calc(1em + 2px), calc(100% - 2.5em) 0.5em;
    background-size: 8px 8px, 8px 8px, 0px 5em;
    background-repeat: no-repeat;

}


@media (max-width: 767px){
  .remedy-modal .righttxt h4{
    text-align: left;
  }
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

  </style>



  <main class="container" style="min-height: 100%;">

    <?php  include_once("../header.php"); ?>    
	   
  
    <div class="row noleft-right" >
      <div class="col-md-5 col-sm-5 col-xs-12 managepatient" >
        <h2>Prescribe Medicine <img src="../../assets/images/info.png" class="heading-info"  data-toggle="modal" data-target="#infoModal"  /></h2>
      </div>

      <!-- <div class="col-md-7 col-sm-7 col-xs-12 managepatient" >
      <div class="searchinput">
         <input type="text" placeholder="Search for something" class="search-box" />
          <button><img src="../../assets/images/search.png"></button>
          </div>
      </div> -->
    </div>





    <div class="row noleft-right remedies-tablinks" style="text-align:center;">
      <h4 onclick="showremedies()" id="remedy" style="text-decoration:underline;">REMEDIES</h4>
      <h4 onclick="showdodont()" id="dodont">DO'S AND DONTS</h4>  

    </div>


    

    <div class="row noleft-right" >
      <div class="col-md-12 col-sm-12 col-xs-12 ">

        <!-- Remedies block -->

        <div class="remedies table-responsive">
            <table class="table" style="border-spacing:1em;">
             <tr>
               <th>REMEDY NAME</th>
               <th>SCORE</th>
               <th>ACTION</th>
               <th>PREFERENCE</th>
             </tr>
              <?php
              if(isset($_GET['pageNo'])){
                $pageNo=$_GET['pageNo'];
              }else{
                $pageNo=1;
              }
              $totalItems= sizeof($allRemedies);
              arsort($allRemedies);
              unset($allRemedies['']);

              $count=array_count_values($allRemedies);
              $count=array_slice($count, 0, 3);
               $remedyLimit= array_sum($count);
              //printArr($allRemedies);
            /*  printArr($count);
               $remedyLimit=3;*/

              /*foreach (array_reverse($allRemedies) as $key => $value) {
                echo $value.'<br>';
              }*/
             /* foreach ($allRemedies as $key => $value) {
                echo $value.'<br>';
              }*/

              
             /* foreach ($count as $key => $value) {
                # code...
                $count1=$count[$value];
              printArr($count1);
              }*/
              /* $count1=$count[$value];
              //printArr($count1);
              $remedyLimit=10;
              if($count1<=10)
              {
                $remedyLimit=10;
              }else if($count1>10){
                $remedyLimit=$count1;
              }*/
              //echo $remedyLimit;
            /*  //printArr($RemName);
              $start=($pageNo-1)*3;
              $elemPerPage=3;
              if($totalItems<10)
              {
                $totalItems=$totalItems;
              }else{
                $totalItems=9;
              }*/
              $totalPageNo=ceil($totalItems/$elemPerPage);
              $allRemedies=array_slice($allRemedies, 0, $remedyLimit);
              //$allRemedies=array_slice(array_slice($allRemedies, 0, 9),$start, $elemPerPage);
              foreach($allRemedies as $remedyName=>$score){

                if($RemName1==$remedyName){
                  $remName=prescribed;
                  $preference='First Preference';
                }
                else if($RemName2==$remedyName){  
                  $remName=prescribed;
                  $preference='Second Preference';
                }
                else if($RemName3==$remedyName){
                  $remName=prescribed;
                  $preference='Third Preference';
                }
                else{
                  $remName=prescribe;
                  $preference="";
                }
                if($remedyName)
                {

                  $getDescription=getDescription($conn,$remedyName);

                  ?>

                  <tr>
                   <!--  <td data-th="remedyName" <?php if(!empty($getDescription['errMsg'])){?> data-toggle="modal" data-target="#myModal" aria-hidden="true" <?php }?> onclick="descriptiondbox('<?php echo $remedyName; ?>')" ><?php echo $remedyName; ?></td>
                    <td data-th="Score">
                      <?php echo $score; ?>
                    </td>
                    <td data-th="High Score Description"><button class="btn btn-common" style="<?php if($remName==prescribed){echo "background-color:white;border:1px solid grey;";}?>" type="button" onclick="prescribedbox('<?php echo $remedyName; ?>')" name="<?php echo $remedyName; ?>"><?php echo $remName; ?></button></td>
                    <td data-th="Preference">
                      <?php echo $preference; ?>
                    </td> -->
                    <td><?php echo $remedyName; ?> <img style="cursor: pointer" src="../../assets/images/info.png" class="remedy-icon" onclick="descriptiondbox('<?php echo $remedyName; ?>')" /></td>
                    <td><?php echo $score; ?></td>                   
                    <td>
                     <?php if($caseSheet['status']!=1){?>
                    <input type="button" onclick="prescribedbox('<?php echo $remedyName; ?>')" value="PRESCRIBE" class="prescribe-btn" >
                    <?php } ?>
                    </td>
                    <!-- data-toggle="modal" data-target="#remediesmodal" -->
                    <td id="preference"><?php echo $preference; ?></td>
                  </tr>

                  <?php
                }
              }?>
            </table>
        <!--   <div class="col-md-12 pagiate">
                <?php if($pageNo==1){ ?>
                  <a style="opacity: 0.5;"><i class="fa fa-angle-double-left"></i>  Previous</a>
                  <label><?php echo $pageNo;?> of <?php echo $totalPageNo;?></label>
                  <a href="remedies.php?doctor_id=<?php echo $_GET['doctor_id'];?>&patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>&pageNo=<?php echo $pageNo+1;?>">Next  <i class="fa fa-angle-double-right"></i></a>
                <?php }else if($pageNo>1 && $pageNo<$totalPageNo){ ?>
                  <a href="remedies.php?doctor_id=<?php echo $_GET['doctor_id'];?>&patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>&pageNo=<?php echo $pageNo-1;?>"><i class="fa fa-angle-double-left"></i>  Previous</a>
                  <label><?php echo $pageNo;?> of <?php echo $totalPageNo;?></label>
                  <a href="remedies.php?doctor_id=<?php echo $_GET['doctor_id'];?>&patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>&pageNo=<?php echo $pageNo+1;?>">Next  <i class="fa fa-angle-double-right"></i></a>
                <?php } else if($pageNo==$totalPageNo){ ?>
                  <a href="remedies.php?doctor_id=<?php echo $_GET['doctor_id'];?>&patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>&pageNo=<?php echo $pageNo-1;?>"><i class="fa fa-angle-double-left"></i>  Previous</a>
                  <label><?php echo $pageNo;?> of <?php echo $totalPageNo;?></label>
                  <a style="opacity: 0.5;">Next  <i class="fa fa-angle-double-right"></i></a>
                <?php } ?>                
          </div> -->
        </div>  <!-- End of remedies section -->


        <!-- do's and dont's section -->

        <div class="do-and-donts card" style="display:none;word-spacing: 2px;letter-spacing: 0.5px;font-size: 15px;">
        <?php 
            $dos=getdondont($conn);
            echo $dos['errMsg']['doNdont']; 
        ?>
          <!-- <p>Know the benefits and side effects of a medicine before taking it. Use medicines only if nondrug approaches are not working.</p>
          <br>
          <p>Follow these over-the-counter medicine precautions.</p>
          <br>
          <p>Carefully read and follow all directions on the medicine bottle and box slideshow.gif. Or let your doctor know why you think you should take the medicine in a different way.<br>
          Take the minimum effective dose. When using a liquid drug, use the measuring device that comes with the drug.
          <br>Call your doctor if you think you are having a problem with your medicine. If you have been told to avoid a medicine, call your doctor before you take it.
          <br>Do not take a medicine if you have had an allergic reaction to it in the past.
          <br>If you are or could be pregnant, call your doctor before taking any medicine.
          <br>Keep a list of all your medicines(What is a PDF document?), including over-the-counter medicines, vitamins, and supplements. And share the list with your doctor.
          <br>Here are some safety tips about giving children medicines:</p>
          <br>
          <p>Do not give aspirin to anyone younger than 20 unless your doctor tells you to, because of the risk of Reye syndrome.
          <br>Talk to your doctor before you give fever medicine to a baby who is 3 months of age or younger. This is to make sure a young baby's fever is not a sign of a serious illness. Ask your doctor what other medicines may not be safe to give your child.
          <br>Don't take medicines in front of small children. Children are great mimics. Don't say that medicine tastes like candy.</p> -->
        </div>  <!-- End of do's and dont's section -->



      </div>

      <div class="col-md-12 closecase" style="overflow:hidden;" >
              <h4>Potency</h4>
              <select class="form-control minimal" name="potency" id="potency" style="padding-right:35px;">
              <option  disabled selected>Select potency</option>
                <option <?php if($potency=='5M: symptoms only on the Mind acute or chronic') echo 'selected'; ?> value="5M: symptoms only on the Mind acute or chronic">5M: symptoms only on the Mind acute or chronic</option>
                <option <?php if($potency=='1M: for intense or increased sensitivity') echo 'selected'; ?> value="1M: for intense or increased sensitivity">1M: for intense or increased sensitivity</option>
                <option <?php if($potency=='200C: for dynamic and fast course of disease or changeable modalities in conditions lasting for less than a week') echo 'selected'; ?> value="200C: for dynamic and fast course of disease or changeable modalities in conditions lasting for less than a week">200C: for dynamic and fast course of disease or changeable modalities in conditions lasting for less than a week</option>
                <option <?php if($potency=='30C: for slow, obstinate and chronic course of pathology for conditions lasting a few months or years.') echo 'selected'; ?> value="30C: for slow, obstinate and chronic course of pathology for conditions lasting a few months or years.">30C: for slow, obstinate and chronic course of pathology for conditions lasting a few months or years.</option>
              </select>


              <h4>Dosage</h4>
              <select class="form-control minimal" name="dosage" id="dosage" >
              <option  disabled selected>Select dosage</option>
                <option <?php if($dosage=='1 dose') echo 'selected'; ?> value="1 dose">1 dose</option>
                <option <?php if($dosage=='3 doses (one every 8 hrs)') echo 'selected'; ?> value="3 doses (one every 8 hrs)">3 doses (one every 8 hrs)</option>
              </select>
        </div>
         <?php if($caseSheet['status']!=1){?>
        <div class="col-md-12" style="text-align:center;margin-top:50px;">
          <p class="errMsg" style="color:red;text-align: center;"></p>
          <input type="button" value="SAVE & CLOSE CASE" class="btn-save">
        </div>
        <?php } ?>




    </div>



<!--Modal start-->
        <div class="modal fade" id="descriptiondbox" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Remedy Description</h3>
              </div>
              
              <div class="modal-body">
                <h4 class="rName" ></h4>
                <div class="rDescription" style="letter-spacing: 0.20px;word-spacing: 1px;padding: 10px;"></div>  
              </div>

            </div>
          </div>
        </div>
        <!--Modal End-->

<!-- PATIENT SET STATUS MODAL -->
<div id="remediesmodal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
        <div class="modal-body">
          <div class="row">
              <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;" src="../../assets/images/close.png"></button>
              </div>
         <div class="alert"></div>
          <div class="row  remedy-modal">
            <div class="col-md-12 ">
              <h2 style="text-align: left;margin-top: 0;margin-left: 20px;">Remedies</h2>
              <h4>Are you sure you want to prescribe <b class='pres'></b> ? <img onclick="descriptiondboxModal()" src="../../assets/images/info.png" class="remedy-icon" style="cursor:pointer;" /></h4>
            </div>
           </div>
          <form action="javascript:;" name="prescribtionForm" id="prescribtionForm" method="post" enctype="multipart/form-data" >
          <div class="row  remedy-modal">
            <div class="col-md-4 col-sm-4 righttxt">
              <h4>Preference</h4>
            </div>
            <div class="col-md-6 col-sm-6">
              <select class="form-control minimal" name="preference">
                <option name="" value="" selected="" disabled="">Select Preference</option>
                <option name="" value="1">First Preference</option>
                <option name="" value="2">Second Preference</option>
                <option name="" value="3">Third Preference</option>
              </select>
            </div>
          </div>

          <div class="row  remedy-modal top20">
            <div class="col-md-4 col-sm-4 righttxt">
              <h4>Next follow up<span style="color:red"> *</span></h4>
            </div>
            <div class="col-md-6 col-sm-6">
              <select class="form-control minimal" name="followup" id="followup">
                <option name="" value="" selected="" disabled="">Select follow up duration</option>
                <option name="" value="week">Every week</option>
                <option name="" value="month">Every month</option>
                <option name="" value="threemonth">Every three months</option>
              </select>
            </div>
          </div>

          <div class="row  remedy-modal top20">
            <div class="col-md-4 col-sm-4 righttxt">
              <h4>Additional comments</h4>
            </div>

            <div class="col-md-6 col-sm-6">
              <textarea class="form-control" name="precribe_comments"></textarea>
            </div>
          </div>


          <div class="row  remedy-modal">
            <div class="col-md-12 ">
               <br>
               <h4>Please, tell us more about this consultation</h4>
               <br><br>
            </div>
          </div>
        
          <div class="row  remedy-modal">
            <div class="col-md-5 col-sm-5 ">
               <select class="form-control minimal" name="consult_type">
                <option name="" value="" selected="" disabled="">Type</option>
                <option name="" value="FirstConsult">First Consult</option>
                <option name="" value="FollowUp">Follow Up</option>
                <option name="" value="VisitCharges">Visit Charges</option>
                
              </select>
            </div>

            <div class="col-md-5 col-sm-5">
              <select class="form-control minimal" name="periodicity">
                <option>Periodicity</option>
                <option name="" value="Weekly">Weekly</option>
                <option name="" value="Monthly">Monthly</option>
                <option name="" value="Yearly">Yearly</option>
                <option name="" value="Quarterly">Quarterly</option>
                <option name="" value="Biannually">Biannually</option>
              </select>
            </div>
            </div>

            <div class="row  remedy-modal top20">
            <div class="col-md-5 col-sm-5 ">
               <select class="form-control minimal" name='currency'>
                <option>Currency</option>
                <?php foreach ($currency as $key => $value) { ?>
                <option name="" value="<?php echo $value['id']; ?>"><?php echo $value['longforms']."(".$value['shortforms'].")"; ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="col-md-5 col-sm-5">
              <!-- <select class="form-control minimal">
                <option>Charges</option>
              </select> -->
              <input  style="" class="form-control charges" type="text" name="charges" placeholder="Charges"/>
            </div>

          </div>
          <input class="form-control" type="hidden"  id="case_id" name="case_id" value="<?php echo $_GET['case_id']; ?>" />
         <input class="form-control" type="hidden"  id="remedydesc" name="remedyName" placeholder="Charges"/>

        </div>
       
        <div class="modal-footer" style="text-align: left;margin-left: 15px;">
          <button type="submit" class="mod-footer-btn" >Add</button>
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
var case_id=<?php echo $_GET['case_id'];?>;
function showdodont(){
  $(".remedies").fadeOut();
  $(".do-and-donts").fadeIn();
  $("#remedy").css("text-decoration","none");
  $("#dodont").css("text-decoration","underline");
  
  
}

function showremedies(){
  $(".do-and-donts").fadeOut();
  $(".remedies").fadeIn();
  $("#remedy").css("text-decoration","underline");
  $("#dodont").css("text-decoration","none");
  
}
function prescribedbox(remedy){
  remedyN=remedy;
  $("#remedydesc").val(remedyN);
  $("#remediesmodal").modal();
  $(".pres").text(remedyN);
}
function descriptiondbox(remedy){
  remedyN=remedy;

  $.ajax({
    url:'../../controllers/remediesController.php',
    type:'POST',
    dataType:'json',
    data:  {remedyN:remedyN,
      type:'description'},
      success:function(data){
        console.log(data);
       // data=data['errMsg'];
        $(".rName").html("Full name of remedy :\n<b style='letter-spacing:1px;'>"+data['remedy_full_name']+"</b>\n");
        $(".rDescription").html("<b>Description : </b>\n"+data['remedy_description']+"\n");
        //$(".rDescription").html("afasfafafafaf:<br>"+"sdfdsgsdgsgsd"+"\n");
        $("#descriptiondbox").modal();
       // console.log(data['errMsg']['remedy_description']);
   }               
});
  
}

function descriptiondboxModal(){
  remedyN=$("#remedydesc").val();
   $("#remediesmodal").removeClass("fade").modal("hide");
//alert(remedyN);
  $.ajax({
    url:'../../controllers/remediesController.php',
    type:'POST',
    dataType:'json',
    data:  {remedyN:remedyN,
      type:'description'},
      success:function(data){
       // data=data['errMsg'];
        $(".rName").text("Full name of remedy:\n<b>"+data['remedy_full_name']+"</b>\n");
        $(".rDescription").html("<b>Description :</b>\n"+data['remedy_description']+"\n");
        
        $("#descriptiondbox").modal();
       // console.log(data['errMsg']['remedy_description']);
   }               
});
}
 $('.errMsg').hide();

$('.btn-save').click(function(e){
  var RemName1='<?php echo $RemName1; ?>';
  //var dosage=$('#dosage').val()
    var potency=$('#potency').val();
    var dosage=$('#dosage').val();

  // alert(potency);
   if(RemName1=="")
   {  //alert('hii');
      $('.errMsg').show();
      $('.errMsg').html("Please select the prescription first.");
   }else if(potency==null){
      $('.errMsg').show();
      $('.errMsg').html("Please select potency.");
   }else if(dosage==null){
     $('.errMsg').show();
      $('.errMsg').html("Please select dosage.");
   }else{
    $('.errMsg').hide();
    $.ajax({type: "POST",
            url:"../../controllers/remediesController.php",
            data:{case_id:case_id,
                  potency:potency,
                  dosage:dosage,
                  type:'dose'},
            dataType:'json',
      })
      .done(function(data) {
        console.log(data);
        window.location.href="../patient/manage_patient.php";
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
e.preventDefault();
});


$('form#prescribtionForm').submit(function(event) {
  /*var potency=$('#potency').val();
  var dosage=$('#dosage').val();*/
  //alert(potency);
 /* var followup=$("#followup").val();
    if(followup!=null){
      alert(followup);
    }else{
      alert('please select follow up');
       alert(followup);
    }*/
    var formdata = new FormData($(this)[0]);
    formdata.append('type','prescription');
   /* formdata.append('potency',potency);
     formdata.append('dosage',dosage);*/
  
    ajaxCall('../../controllers/remediesController.php',formdata,$(this));

    event.preventDefault();
$( "#prescribtionForm" ).scrollTop( 0 );
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
            beforeSend: function () {
              $ele.find('.stopAccess').show();
            }
      })
      .done(function(data) {
      
        if(data['errCode']==-1){
          console.log(data);
          $('.alert').css("display","block");
          $('.alert').removeClass('alert-danger').addClass('alert-success');
          $('.alert').html('<strong>Success ! </strong>'+data['errMsg']);
              setTimeout(function(){
            location.reload(true);
          },2000);
        
          /*
          window.location.href="../remedies/remedies.php?doctor_id=<?php echo $userInfo['user_id'];?>&patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>";*/
        }else{
          $('.alert').css("display","block");
          $('.alert').removeClass('alert-success').addClass('alert-danger');
          $('.alert').html('<strong>Error ! </strong>'+data['errMsg']);
          // alert(data['errMsg']);
            window.scrollTo(1, 1);
        }        
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert("error");
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       }) 
  } 
  /*ajax definition ends*/
</script>



</body>
</html>
