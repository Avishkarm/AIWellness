  <?php
  session_start();


 $activeHeader = "doctorsArea";

  require_once("../../utilities/config.php");
  require_once("../../utilities/dbutils.php");  
  require_once("../../models/startCaseModel.php");
  require_once("../../models/userModel.php"); 

  //database connection handling
  $conn = createDbConnection($servername, $username, $password, $dbname);
  $returnArr=array();
  if(noError($conn)){
    $conn = $conn["errMsg"];
  }
  else{
    printArr("Database Error");
    exit;
  }

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
$patient_id=$_GET['patient_id'];

$patientInfo=getUserInfoWithUserId($patient_id,3,$conn);

if(noError($patientInfo)){
    $patientInfo = $patientInfo["errMsg"];  
  
  } else {
    printArr("Error fetching user info".$patientInfo["errMsg"]);
    
  }

if(!isset($_GET['case_id']) || $_GET['case_id']==""){
if(isset($_SESSION['stepFlag']) && $_SESSION['stepFlag']==0){
  $_SESSION['stepFlag']==1;
unset($_SESSION['stepFlag']);  
   $addStepNo=addStepNo($_SESSION['userInfo']['user_id'],$patient_id,1,$conn);

        if(noError($addStepNo)){
          //$returnArr['errCode']=-1;
          //$returnArr['errMsg']=$addStepNo['errMsg'];
          $case_id=$addStepNo['errMsg'];
          $_SESSION['step_case_id']=$case_id;
          $rtArrRubrics = createBlankSheetRubrics($case_id,$patient_id,$conn);
          //printArr($rtArrRubrics);
          if(noError($rtArrRubrics)){
            $returnArr['errCode']=-1;
            $returnArr['errMsg']=$addStepNo['errMsg'];
          }else{
            $returnArr['errCode']=1;
            $returnArr['errMsg']=$rtArrRubrics['errMsg'];
          }
        }else{
         $returnArr['errCode']=1;
         $returnArr['errMsg']="Failed to add stepno";
        }
  }
}


  $occupationQuery=getAllOccupations($conn, $occupationId);
if(noError($occupationQuery)){
  $occupationQuery=$occupationQuery['errMsg'];
} else {
  printArr("Error Fetching Occupations Data".$occupationQuery['errMsg']);
}
$systemsQuery = getAllSystems($conn, $systemsId);
if(noError($systemsQuery)){
  $systemsQuery=$systemsQuery['errMsg'];
} else {
  printArr("Error Fetching Systems Data".$systemsQuery['errMsg']);
}
if(isset($_GET['case_id']) && !empty($_GET['case_id'])){
  $case_id=$_GET['case_id'];
}else{
  $case_id=$_SESSION['step_case_id'];
}

//echo $case_id;
$updateStepNo=updateStepNo($case_id,1,$conn);
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <?php include_once("../metaInclude.php"); ?>
    <style type="text/css">

      .stepFrmDiv{
        margin-top: 80px;
      }
  
      .questTitle{
        letter-spacing: 1px;
        word-spacing: 1px;
        line-height: 1.5em;
        font-size: 28px;
        color:#333;
        min-height: 85px;
      }
     
      .ans2,.ans3{
          padding-left: 10%;
      }
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
      
      .quest{
        border-bottom:1px solid grey;
        margin-bottom: 10px;
        /*margin-top: 50px;*/
      }
     
    
        .ans1,.ans2,.ans3{
          display: inline-block;
          padding-left: 0;
          width: 25%;
        }

        .ans11,.ans22,.ans33{
          display: inline-block;
          padding-left: 0;
          width: 30%;
        }

        .ans23{
          display: inline-block;
          padding-left: 0;
          width: 40%;
        }
      

        @media(max-width: 991px){
        .ans1,.ans2,.ans3,.ans11,.ans22,.ans33,.ans12,.ans23,.ans34 {
            width: 100%;
        }
        }

        @media(max-width: 768px){
        .questTitle{
          font-size: 23px;
          color:#333;
          min-height: 30px;
        }
        label{
          font-size: 20px;
        }
        }
       
.gerneral-form .form-group{
  padding: 1px;
}
.gerneral-form .label-div{
  width: 40%;
}
.gerneral-form .label-div label{
  flex-direction : row-reverse;
  cursor: auto;
}    
.gerneral-form .field-input{
  width: 60%;
}
.gerneral-form  .form-control{
  font-size: 18px;
}      
.set-height{
      height: 100px ;
    }
    
 @media (max-width: 475px){
  .gerneral-form .label-div {
    width: 100%;
    text-align: left;
    float: left;
    margin-bottom: 0px;
    padding-left: 10px;
    
}
.gerneral-form .label-div label{
  flex-direction : row;
}    
.gerneral-form .field-input {
    width :100%;
    }

    .hideme{
      display: none;
    }
    .set-height{
      height: 50px !important;
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
    background-position: calc(100% - 23px) calc(1em + 5px), calc(100% - 16px) calc(1em + 5px), calc(100% - 2.5em) 0.5em;
    background-size: 8px 8px, 8px 8px, 0px 5em;
    background-repeat: no-repeat;

}     
      
.chosen-single {
        background-image: linear-gradient(45deg, transparent 50%, gray 50%), linear-gradient(135deg, gray 50%, transparent 50%), linear-gradient(to right, #ccc, #ccc) !important;
    background-position: calc(100% - 23px) calc(1em + 5px), calc(100% - 16px) calc(1em + 5px), calc(100% - 2.5em) 0.5em !important;
    background-size: 8px 8px, 8px 8px, 0px 5em !important;
    background-repeat: no-repeat !important;

}   
.chosen-single span{
   font-size: 18px;
   color: #000;

}   
.chosen-container{
    margin-left: 4%;
  }

    


      

    </style>
    <main class="container" style="min-height: 100%;">
    <link rel="stylesheet" type="text/css" href="../../assets/css/chosen.min.css">
      <?php  include_once("../header.php"); ?> 
      <section>
      <div class="main-container">
        
          <div class="row">
            <div class="col-md-8 col-md-offset-2 " style="margin-top: 10%;text-align:center;" >
             <img src="../../assets/images/step1.png"   style="width:100%;">

             <div class="steplinks step1">

                <a href="#" class="active">Step 1</a>
                <a href="step2.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $case_id;?>" >Step 2</a>
                <a href="step3.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $case_id;?>" >Step 3</a>
                <a href="step4.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $case_id;?>" >Step 4</a>
                <a href="step5.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $case_id;?>" >Step 5</a>
                <a href="step6.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $case_id;?>" >Step 6</a>
             </div>
            
            </div>
          </div>

          <form action="javascript:;" name="step1Form" id="step1Form" method="post">
          <div class="row stepFrmDiv">
            
            <div class="col-md-10 col-xs-12 gerneral-form" style="padding-left: 0px;" >
            
            

               <!-- <div class="form-group">
                  <div class="label-div" >
                    <label>Doctor's Name</label>
                  </div>
                  <div class="field-input">
                    <input readonly type="text"  value="<?php echo ucfirst(strtolower($userInfo['user_first_name'])); ?>" class="form-control">
                  </div>  
               </div>  -->


                <div class="form-group">
                  <div class="label-div" >
                    <label>Patient's Full Name</label>
                  </div>
                  <div class="field-input">
                    <input readonly type="text" value="<?php echo ucfirst(strtolower($patientInfo['user_first_name'])).' '.ucfirst(strtolower($patientInfo['user_last_name'])); ?>" class="form-control">
                  </div>  
               </div> 

               <!-- <div class="form-group">
                  <div class="label-div" >
                    <label>Age</label>
                  </div>
                  <div class="field-input">
                    <input type="text" readonly value="<?php echo calcAge($patientInfo['user_dob']);?>" class="form-control">
                  </div>  
               </div>  -->

               <div class="form-group">
                  <div class="label-div">
                    <label>Gender</label>
                  </div>
                  <div class="field-input">

                    <select  disabled="" class="form-control" >
                      <option <?php if($patientInfo['user_gender']=='Male') echo 'selected'; ?> >Male</option>
                      <option <?php if($patientInfo['user_gender']=='Female') echo 'selected'; ?> >Female</option>                 
                      <option <?php if($patientInfo['user_gender']=='Transgender') echo 'selected'; ?> >Transgender</option>
                    </select>
                  </div>  
               </div> 


               <div class="form-group">
                  <div class="label-div">
                    <label>Marital Status</label>
                  </div>
                  <div class="field-input">
                    <select name="user_marital_status " data-placeholder="Select status" onchange="saveTextAns(this.value,'user_marital_status')" class="form-control minimal">
                      <option vlaue="" selected="" disabled="">Select status</option>
                      <option <?php if($patientInfo['user_marital_status']=='Single') echo 'selected'; ?> >Single</option>
                      <option <?php if($patientInfo['user_marital_status']=='Married') echo 'selected'; ?> >Married</option>
                      <option <?php if($patientInfo['user_marital_status']=='Divorced') echo 'selected'; ?> >Divorced</option>
                    </select>
                  </div>  
               </div> 

               <div class="form-group">
                  <div class="label-div" >
                    <label>Occupation</label>
                  </div>
                  <div class="field-input">
                    <!-- <input type="text" name="user_occ" value="<?php echo $patientInfo['user_occ'];?>" class="form-control"> -->
                    <select class="form-control chosen-select"   onchange="saveTextAns(this.value,'user_occ')" type="text" id="user_occ" name="user_occ" >
                    <option vlaue="" selected="" disabled="">Select occupation</option>
                    <?php
                    foreach($occupationQuery as $occupationId=>$occupationDetails){
                      $occupationName = $occupationDetails["occupationName"];
                      $selected = "";
                      if($occupationName==$patientInfo["user_occ"])
                        $selected = "selected";
                      ?>
                      <option <?php echo $selected; ?> value="<?php echo $occupationName; ?>"><?php echo $occupationName; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  </div>  
               </div> 


              <!--  <div class="form-group">
                  <div class="label-div" >
                    <label>System</label>
                  </div>
                  <div class="field-input">
                    
                    <select class="form-control chosen-select" onchange="saveTextAns(this.value,'user_system')" type="text" id="user_system" name="user_system" >
                    <?php
                    foreach($systemsQuery as $systemsId=>$systemsDetails){
                      $systemsName = $systemsDetails["system_name"];
                      $selected = "";
                      if($systemsName==$patientInfo["user_system"])
                        $selected = "selected";
                      ?>
                      <option <?php echo $selected; ?> value="<?php echo $systemsName; ?>"><?php echo $systemsName; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  </div>  
               </div>  -->

               <div class="form-group">
                  <div class="label-div" >
                    <label>Position at job</label>
                  </div>
                  <div class="field-input">
                    <input type="text" name="user_job_position" value="<?php echo $patientInfo['user_job_position'];?>" onblur="saveTextAns(this.value,'user_job_position')" class="form-control">
                  </div>  
               </div> 

               <div class="form-group">
                  <div class="label-div" >
                    <label>No of jobs upto now</label>
                  </div>
                  <div class="field-input">
                    <input type="text" name="user_job_no" value="<?php echo $patientInfo['user_job_no'];?>" onblur="saveTextAns(this.value,'user_job_no')" class="form-control">
                  </div>  
               </div> 

               <div class="form-group">
                  <div class="label-div set-height"  >
                    <label>Job</label>
                  </div>
                  <div class="field-input" style="padding-left:25px;padding-top:10px;height:100px;">
                    <input type="radio" id="emp" name="user_work" <?php if($patientInfo['user_work']=='employee'){ echo 'checked';} ?> value="employee" class="form-control" onchange="saveTextAns(this.value,'user_work')" onclick="Showcompany()">
                    <label for="emp">Working for</label>

                    <input type="radio" id="self" name="user_work" <?php if($patientInfo['user_work']=='self'){ echo 'checked';} ?>  value="self" class="form-control" onchange="saveTextAns(this.value,'user_work')" onclick="Showpromotion()">
                    <label for="self">Self-employed</label>
                  </div> 
                     <div style="clear:left;"></div>
               </div> 





                <div class="form-group" id="company" style="display:none;">
                  <div class="label-div hideme" style="height:100px;" >
                    <label></label>
                  </div>
                  <div class="field-input" style="height:100px;">
                    <textarea onblur="saveTextAns(this.value,'user_company')" class="form-control" name="user_company" placeholder="Company name"><?php echo $patientInfo['user_company'];?></textarea>
                  </div>  
                </div>

                <div class="form-group" id="promotion" style="display:none;">
                  <div class="label-div hideme" style="height:100px;" >
                    <label></label>
                  </div>
                  <div class="field-input" style="height:100px;">
                    <textarea onblur="saveTextAns(this.value,'user_promotion')" class="form-control" name="user_promotion" placeholder="promotions"><?php echo $patientInfo['user_promotion'];?></textarea>
                  </div>  

                </div>

                

                <div class="form-group" style="margin-bottom:20px;">
                  
                  <div class="label-div set-height" style="height:100px;margin-bottom:0;" >
                    <label>Educational background</label>
                  </div>
                  <div class="field-input" style="height:100px;margin-bottom:0;">
                    <textarea onblur="saveTextAns(this.value,'user_education')" name="user_education" class="form-control"><?php echo $patientInfo['user_education'];?></textarea>
                  </div>  
                  <div style="clear:both;"></div>
               </div> 

               <div class="form-group">
                  <div class="label-div" >
                    <label>Address</label>
                  </div>
                  <div class="field-input">
                    <input type="text" name="user_address" value="<?php echo $patientInfo['user_address'];?>" onblur="saveTextAns(this.value,'user_address')" class="form-control">
                  </div>  
               </div> 

               <div class="form-group">
                  <div class="label-div" >
                    <label>Phone</label>
                  </div>
                  <div class="field-input">
                    <input type="text" readonly value="<?php echo $patientInfo['user_mob'];?>" class="form-control">
                  </div>  
               </div> 

               <div class="form-group">
                  <div class="label-div" >
                    <label>Email</label>
                  </div>
                  <div class="field-input">
                    <input type="text" readonly name="user_email" value="<?php if($patientInfo['user_email']!='dummy'.$patientInfo['user_mob'].'@eHeilung.com') { echo $patientInfo['user_email']; } ?>" class="form-control">
                  </div>  
               </div> 

               <div class="form-group">
                  <div class="label-div" >
                    <label>Skype name</label>
                  </div>
                  <div class="field-input">
                    <input type="text" onblur="saveTextAns(this.value,'user_skype')" name="user_skype" value="<?php echo $patientInfo['user_skype'];?>" class="form-control">
                  </div>  
               </div> 

               <div class="form-group">
                  <div class="label-div" >
                    <label>Reference by</label>
                  </div>
                  <div class="field-input">
                    <input type="text" name="user_ref" onblur="saveTextAns(this.value,'user_ref')" value="<?php echo $patientInfo['user_ref'];?>" class="form-control">
                  </div>  
               </div> 
                 <input type="hidden" name="user_type" value="3" class="form-control">
                 <input type="hidden" name="user_id" value="<?php echo $_GET['patient_id'];?>" class="form-control">
                 <input type="hidden" name="case_id" value="<?php echo $case_id;?>" class="form-control">
                 <input type="hidden" name="step_no" value="1">

            </div>
          </div>
          
            <div class="row">
                <div class="col-md-12" style="text-align: center;margin-top:80px;" >
                  <h4 id="errorMsg" style="color:red;"></h4>
                  <button style="outline:0;border: none; background-color: transparent;" id="step1submit" type="submit" class="next-btn"><img src="../../assets/images/nextbtn.png"/></button>
                  <button button type=""  onClick="" class="signupbtn load" style="width: 20%;display: none;" >Loading...<img src="../../assets/images/ajax-loader.gif"></button>
                  <!-- <input type="image" src="../../assets/images/nextbtn.png" id="" class="next-btn" value="" style="outline:0;"> -->
                </div>
            </div>
        </form>
      </section>
    </main> 
    <?php include("../modals.php"); ?> 
    <?php include('../footer.php'); ?>
<script type = "text/javascript" src= "../../assets/js/chosen.jquery.min.js"></script>
<script type="text/javascript">
  $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
function Showcompany()
{
$("#company").css("display","block");
$("#promotion").css("display","block");
}

function Showpromotion()
{
$("#company").css("display","block");
$("#promotion").css("display","none");
}
function saveTextAns(val,column_name){
  var patient_id=<?php echo $_GET['patient_id']; ?>;
  $.ajax({type: "POST",
            url:"../../controllers/startCaseController.php",
            data:{val:val,
                  column_name:column_name,
                  patient_id:patient_id,
                  type:'textans_step1'},
            dataType:'json',
      })
      .done(function(data) {
        console.log(data);
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

/*$('.next-btn').click(function(){
    alert('hiii');  
});*/
  $('form#step1Form').submit(function(event) {
   
    /* Act on the event */
   // var type="step1";
    //var formdata=$('#contactForm').serialize();
    var formdata = new FormData($(this)[0]);
    formdata.append('type','step1');
    //console.log(formdata);
   // location.href="controllers/signUpController.php";
   ajaxCall('../../controllers/startCaseController.php',formdata,$(this));

    event.preventDefault();
$( "#step1Form" ).scrollTop( 0 );
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
               $('.load').show();
              $('.next-btn').hide();
            }
      })
      .done(function(data) {
        $('.load').hide();
        $('.next-btn').show();
        if(data['errCode']==-1){
          $('.successMsg').html(data['errMsg']);
          $('.successBox').show();
          $('.errBox').hide();
         //alert(data['errMsg']);
          window.location.href="step2.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $case_id;?>";
         /* // alert(data['errMsg']);
          window.scrollTo(1, 1);*/
        }else{
          $('.successBox').hide();
          $('.errMsg').html(data['errMsg']);
          $('.errBox').show();
           //alert(data['errMsg']);
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
