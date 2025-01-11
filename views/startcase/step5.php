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


  $usergroup='Doctor_Only';
  $gender=$patientInfo['user_gender'];
  if(calcAge($patientInfo["user_dob"]) > 21){
    $age="Adult";
  }else{
    $age="Minor";
  }
  $caseSheet1 = getsectionrubrics(Mental,$usergroup,$gender,$age, $conn);
//printArr($caseSheet1);
if(noError($caseSheet1)){
  $caseSheet1 = $caseSheet1["errMsg"];
} else {
  printArr("Error fetching case sheet");
  exit;
}
 $caseSheet2 = getsectionrubrics(Sensitive,$usergroup,$gender,$age, $conn);
//printArr($caseSheet2);
if(noError($caseSheet2)){
  $caseSheet2 = $caseSheet2["errMsg"];
} else {
  printArr("Error fetching case sheet");
  exit;
}
//printArr($caseSheet);
$getAllCaseDetails= getAllCaseDetails($conn,$_GET['case_id']);
if(noError($getAllCaseDetails)){
  $getAllCaseDetails=$getAllCaseDetails['errMsg'];
} else {
  printArr("Error Fetching case details".$getAllCaseDetails['errMsg']);
}
  $getAllCaseSheetDetails= getAllCaseSheetDetails($conn,$_GET['case_id']);
if(noError($getAllCaseSheetDetails)){
  $getAllCaseSheetDetails=$getAllCaseSheetDetails['errMsg'];
} else {
  printArr("Error Fetching case details".$getAllCaseSheetDetails['errMsg']);
}
$case_id=$_GET['case_id'];
$updateStepNo=updateStepNo($case_id,5,$conn);
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <?php include_once("../metaInclude.php"); ?>
    <style type="text/css">

      .stepFrmDiv{
        margin-top: 80px;
      }
  
       h1{
        width: 40%;
        text-align: right;
        color: #454545;
         font-family: Montserrat-Regular;
        font-size: 30px;
        padding-right: 15px;
      }
      .questTitle{
        letter-spacing: 1px;
        word-spacing: 1px;
        line-height: 1.5em;
        font-size: 28px;
        color:#333;
        /*min-height: 85px;*/
      }
        .questHead p{
        text-align: left;
        font-size: 20px;
        margin-bottom: 50px;
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
        word-break: break-word;
        padding-right: 15px;
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
      
      .allquestions .quest{
        border-bottom:1px solid grey;
        margin-bottom: 10px;
        /*margin-top: 50px;*/
      }
      .allquestions .quest:nth-child(6) {
        border-bottom: none !important;
     
        }
     
    
        .ans1,.ans2,.ans3{
          display: inline-block;
          padding-left: 0;
          width: 25%;
        }

        .ans11,.ans22,.ans33{
          display: inline-block;
          padding-left: 0;
          width: 33%;
        }

        .ans23{
          display: inline-block;
          padding-left: 0;
          width: 40%;
        }
      

        .ans30{
          display: inline-block;
          padding-left: 0;
          width: 30%;
        }
      
      .ans50{
          display: inline-block;
          padding-left: 0;
          width: 50%;
        }
      .set-height{
       height: 100px;
        } 
       .set-biglabel{
        height: 140px;
       }
       .set-bigfield{
        height: 140px !important;
       }
       .set-biglabel label{
        margin-top: 30px;
       }
        @media(max-width: 991px){
        .ans1,.ans2,.ans3,.ans11,.ans22,.ans33,.ans12,.ans23,.ans34,.ans30,.ans50 {
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
.cam-pic {
    height: 120px !important;
    width: 120px !important;
    margin-top: 0px;
   }   
    

 .top30m7{
  margin-top: 30px;
 }   
 @media (max-width: 475px){

   .top30m7{
  margin-top: 7px !important;
 }  
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
.set-height{
 height: 50px !important;
}    

h1{
        width: 100%;
        text-align: left;
        font-size: 25px;
        margin-left: 10px;
        margin-bottom: 15px;
  }
  .set-biglabel{
        height: 50px !important;
       } 
  .set-bigfield{
    height: 30px !important;
  }       
   .set-biglabel label{
        margin-top: 0px !important;
       }
  .cam-pic {
    height: 70px !important;
    width: 70px !important;
    margin-top: 0px;
   }   
    .gerneral-form .field-inputtxt {
    width: 68.8% !important;
   }  
      .gerneral-form .field-inputdrop {
    width: 30% !important;
   }
 }

.gerneral-form .field-inputtxt {
    width: 38.8%;
    display: inline-block;
    float: left;
    height: 50px;
    margin-left: 1.2%;
    margin-bottom: 40px;
}
.gerneral-form .field-inputtxt input[type=text]{
border-color: #9b9b9b !important;
    width: 96%;
    height: 50px;
    border-radius: 0px;
    margin-right: 0px;
    margin-left: 4%;
    background-color: white;
    color: #000;
    padding-left: 10px;
    font-family: Montserrat-Regular;
}
      
.gerneral-form .field-inputdrop {
    width: 20%;
    display: inline-block;
    float: right;
    height: 50px;
    margin-bottom: 40px;
}
   
.gerneral-form .field-inputdrop select {
    border-color: #9b9b9b;
    width: 100%;
    height: 50px;
    border-radius: 0px;
    margin-right: 0px;
    margin-left: 0;
    background-color: white;
    color: #000;
    padding-left: 10px;
    font-family: Montserrat-Regular;
    border-left: none;
    outline: none;
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




    </style>
    <main class="container" style="min-height: 100%;">
    <link rel="stylesheet" type="text/css" href="../../assets/css/chosen.min.css">
      <?php  include_once("../header.php"); ?> 
      <section>
      <div class="main-container">
       
          <div class="row">
            <div class="col-md-8 col-md-offset-2 " style="margin-top: 10%;text-align:center;" >
             <img src="../../assets/images/step5.png"   style="width:100%;">

              <div class="steplinks step5">

                <a href="step1.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" >Step 1</a>
                <a href="step2.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" >Step 2</a>
                <a href="step3.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>">Step 3</a>
                <a href="step4.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" >Step 4</a>
                <a href="#" class="active">Step 5</a>
                <a href="step6.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" >Step 6</a>
             </div>


            </div>
          </div>

          <form action="javascript:;" name="step5Form" id="step5Form" method="post" enctype="multipart/form-data" >
          <div class="row stepFrmDiv">
            
            <div class="col-md-10 col-xs-12 gerneral-form" style="padding-left: 0px;" >
            
            
              <h1>Face type</h1>
               
               <div class="form-group">
                    <div class="label-div set-height" >
                      <label>How is your face type?</label>
                    </div>
                    <div class="field-input set-height" >
                      <textarea onblur="saveTextAns(this.value,'face_type')" name="face_type" class="form-control" ><?php echo $getAllCaseDetails["face_type"]; ?></textarea>
                    </div> 
                    <div style="clear:left;"></div>
               </div> 


                <!--  <div class="form-group">
                  <div class="label-div " style="height:100px;" >
                    <label style="margin-top: 30px;">Please attach a close-up picture without make up</label>
                  </div>
                 <div class="field-input set-bigfield" >
                 <div class="col-md-3 col-xs-3">
                   <img id="close-display" src="../../assets/images/cam.png" class="img-circle cam-pic">
                  </div>
                 
                  <div class="col-md-9 col-xs-9" style="padding-right: 0;">
                    <input type="text" id="close-select" class="form-control" readonly style="float: right;margin-top: 30px;" value="Choose file">
                    <input type="file" onchange="saveTextAns(this.value,'close_img')" name="close_img" id="close-pic" class="form-control" style="position:relative;overflow:hidden;display: none;">
                  </div>
                 </div>
                 <div style="clear:left;"></div>
               </div> 


                <div class="form-group">
                  <div class="label-div " style="height:100px;">
                    <label style="margin-top: 30px;">Please attach your full picture</label>
                  </div>
                 <div class="field-input set-bigfield">
                 <div class="col-md-3 col-xs-3">
                   <img id="user-pic" src="../../assets/images/cam.png" class="img-circle cam-pic">
                  </div>
                 
                  <div class="col-md-9 col-xs-9" style="padding-right: 0;">
                    <input type="text" id="full-select" class="form-control" readonly style="float: right;margin-top: 30px;" value="Choose file">
                    <input type="file" onchange="saveTextAns(this.value,'full_img')" name="full_img" id="full-pic" class="form-control" style="position:relative;overflow:hidden;display: none;">
                  </div>
                 </div>
                  <div style="clear:both;"></div>
               </div>  -->

               <div class="form-group">
                  <div class="label-div">
                    <label>Height</label>
                  </div>
                  <div class="field-inputtxt">
                    <input type="text" onblur="saveTextAns(this.value,'height')" name="height" value="<?php echo $getAllCaseDetails["height"]; ?>" class="form-control">
                  </div>  
                  <div class="field-inputdrop">
                    <select name="height_unit" class="form-control minimal" onchange="saveTextAns(this.value,'height_unit')">
                    <option <?php if($getAllCaseDetails['height_unit']=='feet') echo 'selected'; ?>>feet</option>
                    <option <?php if($getAllCaseDetails['height_unit']=='cm') echo 'selected'; ?>>cm</option>
                    </select>
                  </div>  
                  <div style="clear:both;"></div>
                  
               </div>




 <!--               <h1>Structure (attachment)</h1>
             
                <div class="form-group" style="margin-bottom:0px;">
                  <div class="label-div set-biglabel" >
                    <label>Nose</label>
                  </div>
                 <div class="field-input set-bigfield" >
                 <div class="col-md-3 col-xs-3">
                   <img id="nose-display" src="../../assets/images/cam.png" class="img-circle cam-pic">
                  </div>
                 
                  <div class="col-md-9 col-xs-9" style="padding-right: 0;">
                    <input type="text" id="nose-select" class="form-control top30m7" readonly style="float: right;" value="Choose file">
                    <input type="file" onchange="saveTextAns(this.value,'nose')" name="nose" id="nose-pic" class="form-control" style="position:relative;overflow:hidden;display: none;">
                  </div>
                 </div>
                 <div style="clear:both;"></div>
               </div> 

               <div class="form-group" style="margin-bottom:0px;">
                  <div class="label-div set-biglabel">
                    <label >Nails</label>
                  </div>
                 <div class="field-input set-bigfield" >
                 <div class="col-md-3 col-xs-3">
                   <img id="nails-display" src="../../assets/images/cam.png" class="img-circle cam-pic">
                  </div>
                 
                  <div class="col-md-9 col-xs-9" style="padding-right: 0;">
                    <input type="text" id="nails-select" class="form-control top30m7" readonly style="float: right;" value="Choose file">
                    <input type="file" onchange="saveTextAns(this.value,'nails')" name="nails" id="nails-pic" class="form-control" style="position:relative;overflow:hidden;display: none;">
                  </div>
                 </div>
                  <div style="clear:both;"></div>
               </div>
               

                <div class="form-group" style="margin-bottom:0px;">
                  <div class="label-div set-biglabel" >
                    <label>Fingers</label>
                  </div>
                 <div class="field-input set-bigfield">
                 <div class="col-md-3 col-xs-3">
                   <img id="fingers-display" src="../../assets/images/cam.png" class="img-circle cam-pic">
                  </div>
                 
                  <div class="col-md-9 col-xs-9" style="padding-right: 0;">
                    <input type="text" id="fingers-select" class="form-control top30m7" readonly style="float: right;" value="Choose file">
                    <input type="file" onchange="saveTextAns(this.value,'fingers')" name="fingers" id="fingers-pic" class="form-control" style="position:relative;overflow:hidden;display: none;">
                  </div>
                 </div>
                  <div style="clear:both;"></div>
               </div>

               <div class="form-group" style="margin-bottom:0px;">
                  <div class="label-div set-biglabel" >
                    <label>Toes</label>
                  </div>
                 <div class="field-input set-bigfield">
                 <div class="col-md-3 col-xs-3">
                   <img id="toes-display" src="../../assets/images/cam.png" class="img-circle cam-pic">
                  </div>
                 
                  <div class="col-md-9 col-xs-9" style="padding-right: 0;">
                    <input type="text" id="toes-select" class="form-control top30m7" readonly style="float: right;" value="Choose file">
                    <input type="file" onchange="saveTextAns(this.value,'toes')" name="toes" id="toes-pic" class="form-control" style="position:relative;overflow:hidden;display: none;">
                  </div>
                  
               </div>
               <div style="clear:both;"></div>
             </div> -->

             <div class="form-group">
                    <div class="label-div"  style="height:100px;" >
                      <label>Anything that you feel strange about your Mind or Body?</label>
                    </div>
                    <div class="field-input" style="height:100px;">
                      <textarea onblur="saveTextAns(this.value,'feel_strange_mind_body')" name="feel_strange_mind_body" class="form-control" ><?php echo $getAllCaseDetails["feel_strange_mind_body"]; ?></textarea>
                    </div> 
               </div> 

<div style="clear:both;"></div>
               


        <!--        <h1>Attachments: how are your attachmets towards</h1>

               <div class="form-group">
                <div style="clear:left"></div>
                  <div class="label-div set-height" >
                    <label>Material things</label>
                  </div>
                  <div class="field-input">
                   <select name="material_attach" class="form-control minimal">
                      <option >Attachment</option>
                      <option >Non-attachment</option>
                    </select>
                  </div>  
               </div> 


               <div class="form-group">
                  <div class="label-div" >
                    <label>Friends</label>
                  </div>
                  <div class="field-input">
                   <select name="friends_attach" class="form-control minimal">
                      <option >Attachments</option>
                      <option >Non-attachment</option>
                    </select>
                  </div>  
               </div> 

               <div class="form-group">
                  <div class="label-div" >
                    <label>Family</label>
                  </div>
                  <div class="field-input">
                   <select name="family_attach" class="form-control minimal">
                      <option >Attachments</option>
                      <option >Non-attachment</option>
                    </select>
                  </div>  
               </div> 

               <div class="form-group">
                  <div class="label-div" >
                    <label>Colleagues</label>
                  </div>
                  <div class="field-input">
                   <select name="colleagues_attach" class="form-control minimal">
                      <option >Attachments</option>
                      <option >Non-attachment</option>
                    </select>
                  </div>  
               </div> 

               <div class="form-group">
                  <div class="label-div" >
                    <label>People around you</label>
                  </div>
                  <div class="field-input">
                   <select name="people_attach" class="form-control minimal">
                      <option >Attachments</option>
                      <option >Non-attachment</option>
                    </select>
                  </div>  
               </div>  -->

               <h1 >Mental history</h1>

               

                <div class="form-group">
                    <div class="label-div " style="height:100px;">
                      <label>How would you describe yourself?</label>
                    </div>
                    <div class="field-input set-height">
                      <textarea onblur="saveTextAns(this.value,'describe_yourself')" name="describe_yourself" class="form-control" ><?php echo $getAllCaseDetails["describe_yourself"]; ?></textarea>
                    </div> 
                    <div style="clear:left;"></div>
               </div> 

                 <!-- <textarea onblur="saveTextAns(this.value,'mental_status')" name="mental_status" class="form-control" ><?php echo $getAllCaseDetails["mental_status"]; ?></textarea> -->
                <!-- <div class="form-group">
                    <div class="label-div " style="height:100px;">
                      <label>Mentally are you positive or negative?</label>
                    </div>
                    <div class="field-input set-height" >
                    
                   
                      <select name="mental_status" class="form-control minimal" onchange="saveTextAns(this.value,'mental_status')">
                        <option <?php if($getAllCaseDetails["mental_status"] == "Positive" ) { echo "selected"; } ?> value="Positive">Positive</option>
                        <option <?php if($getAllCaseDetails["mental_status"] == "Negative" ) { echo "selected"; } ?> value="Negative">Negative</option>
                      </select>

                    </div> 
                    <div style="clear:left;"></div>
               </div>   -->

            </div>
          </div>

          <div class="allquestions">
          <?php foreach($caseSheet1 as $priQuestionName=>$priQuesDets){ 
              //printArr($priQuesDets);
              $keys = array_keys($priQuesDets);
              $userGender=$priQuesDets[$keys[0]]["gender"];
               $userType=$priQuesDets[$keys[0]]["usergroup"];
               $userAge=$priQuesDets[$keys[0]]["age"];
               $multi=$priQuesDets[$keys[0]]["multiChoice"];
              
            if($gender=='Transgender'){
                 if($userType==$usergroup || $userType=='Both'){
                  if($userAge==$age || $userAge=='Both'){
                     if( $multi=='yes'){
                      $type='checkbox';
                      $selection='Multiple selection';
                    }else
                    {
                      $type='radio';
                      //$selection='Single selection';
                      $selection='Select Prominent Option';
                      
                    }
            ?>

              <div class="col-md-12 col-sm-12 col-xs-12 quest" style="position:initial;">
              <div class="">
                <div class="questHead">
                  <h3 class="questTitle"><?php echo $priQuesDets[$keys[0]]["question_name"]; ?></h3>
                  <p><?php echo $selection; ?></p>
                </div>
               
                <div class="ansdiv">
                <?php
                  foreach($priQuesDets as $ansLabel=>$ansDets){
                    //printArr($ansDets);
                    if( $multi=='yes'){
                      $type='checkbox';
                    }else
                    {
                      $type='radio';
                    }
                      foreach ($getAllCaseSheetDetails as $key => $value) {
                       
                          if($value['question_id']==$ansDets["question_id"])
                          { 
                            $checked = "checked";
                          if(!empty($ansDets['ans_label'])){
                        
                ?>
                  <div class="ans11">
                    <!-- <input type="<?php echo $type; ?>" id="desire[0]" name="" value="High" required="">
                    <label class="event" for="desire[0]">High</label> -->
                    <input id="<?php echo $priQuesDets[$keys[0]]["question_id"].$ansDets["aid"]; ?>"  <?php if ($value['aid']==$ansDets["aid"]) echo $checked; ?>  entry_id="<?php echo $ansDets["aid"]; ?>" ans_id="<?php echo $ansDets["aid"]; ?>" type="<?php echo $type ;?>" name="<?php echo $ansDets["question_id"]; ?>[]" value="<?php echo $ansDets["aid"]; ?>" fuq_id="<?php echo $ansDets["fuq_id"]; ?>" has_fuq="<?php echo $ansDets["has_fuq"]; ?>" entryId="<?php echo $ansDets["entry_id"]; ?>" onclick="saveAnswer('<?php echo $priQuesDets[$keys[0]]["question_id"];?>','<?php echo $ansDets["aid"];?>','<?php echo $ansDets["answerRemedies"];?>','<?php echo $ansDets["ans_label"];?>')" /> 
                    <label for="<?php echo $priQuesDets[$keys[0]]["question_id"].$ansDets["aid"]; ?>"><?php echo ucfirst(strtolower($ansDets["ans_label"])); ?></label>
                  </div>
                <?php } }
                      } }?>
                </div>
              </div>
              </div>
              <?php } }  }else{
                if($userGender==$gender || $userGender=='Both'){
                 if($userType==$usergroup || $userType=='Both'){
                  if($userAge==$age || $userAge=='Both'){
                     if( $multi=='yes'){
                      $type='checkbox';
                      $selection='Multiple selection';
                    }else
                    {
                      $type='radio';
                      //$selection='Single selection';
                      $selection='Select Prominent Option';
                      
                    }
            ?>

              <div class="col-md-12 col-sm-12 col-xs-12 quest" style="position:initial;">
              <div class="">
                <div class="questHead">
                  <h3 class="questTitle"><?php echo $priQuesDets[$keys[0]]["question_name"]; ?></h3>
                  <p><?php echo $selection; ?></p>
                </div>
               
                <div class="ansdiv">
                <?php
                  foreach($priQuesDets as $ansLabel=>$ansDets){
                    //printArr($ansDets);
                    if($multi=='yes'){
                      $type='checkbox';
                    }else
                    {
                      $type='radio';
                    }
                      foreach ($getAllCaseSheetDetails as $key => $value) {
                       
                          if($value['question_id']==$ansDets["question_id"])
                          { 
                            $checked = "checked";
                          if(!empty($ansDets['ans_label'])){
                        
                ?>
                  <div class="ans11">
                    <!-- <input type="<?php echo $type; ?>" id="desire[0]" name="" value="High" required="">
                    <label class="event" for="desire[0]">High</label> -->
                    <input id="<?php echo $priQuesDets[$keys[0]]["question_id"].$ansDets["aid"]; ?>"  <?php if ($value['aid']==$ansDets["aid"]) echo $checked; ?>  entry_id="<?php echo $ansDets["aid"]; ?>" ans_id="<?php echo $ansDets["aid"]; ?>" type="<?php echo $type ;?>" name="<?php echo $ansDets["question_id"]; ?>[]" value="<?php echo $ansDets["aid"]; ?>" fuq_id="<?php echo $ansDets["fuq_id"]; ?>" has_fuq="<?php echo $ansDets["has_fuq"]; ?>" entryId="<?php echo $ansDets["entry_id"]; ?>" onclick="saveAnswer('<?php echo $priQuesDets[$keys[0]]["question_id"];?>','<?php echo $ansDets["aid"];?>','<?php echo $ansDets["answerRemedies"];?>','<?php echo $ansDets["ans_label"];?>')" /> 
                    <label for="<?php echo $priQuesDets[$keys[0]]["question_id"].$ansDets["aid"]; ?>"><?php echo ucfirst(strtolower($ansDets["ans_label"])); ?></label>
                  </div>
                <?php } }
                      } }?>
                </div>
              </div>
              </div>
              <?php } } }
                } } ?>

              <?php foreach($caseSheet2 as $priQuestionName=>$priQuesDets){ 
              //printArr($priQuesDets);
              $keys = array_keys($priQuesDets);
              $userGender=$priQuesDets[$keys[0]]["gender"];
               $userType=$priQuesDets[$keys[0]]["usergroup"];
               $userAge=$priQuesDets[$keys[0]]["age"];
               $multi=$priQuesDets[$keys[0]]["multiChoice"];
              if($gender=='Transgender'){
                 if($userType==$usergroup || $userType=='Both'){
                  if($userAge==$age || $userAge=='Both'){
                      if( $multi=='yes'){
                        $type='checkbox';
                        $selection='Multiple selection';
                      }else
                      {
                        $type='radio';
                        //$selection='Single selection';
                        $selection='Select Prominent Option';

                      }
            ?>

              <div class="col-md-12 col-sm-12 col-xs-12 quest">
              <div class="">
                <div class="questHead">
                  <h3 class="questTitle"><?php echo $priQuesDets[$keys[0]]["question_name"]; ?></h3>
                  <p><?php echo $selection; ?></p>
                </div>
               
                <div class="ansdiv">
                <?php
                  foreach($priQuesDets as $ansLabel=>$ansDets){
                    //printArr($ansDets);
                    if($ansDets['multiChoice']=='yes'){
                      $type='checkbox';
                    }else
                    {
                      $type='radio';
                      
                    }
                    foreach ($getAllCaseSheetDetails as $key => $value) {
                          if($value['question_id']==$ansDets["question_id"])
                          { 
                            $checked = "checked";
                           if(!empty($ansDets['ans_label'])){
                ?>
                  <div class="ans11">
                    <!-- <input type="<?php echo $type; ?>" id="desire[0]" name="" value="High" required="">
                    <label class="event" for="desire[0]">High</label> -->
                    <input id="<?php echo $priQuesDets[$keys[0]]["question_id"].$ansDets["aid"]; ?>"  <?php if ($value['aid']==$ansDets["aid"]) echo $checked; ?>  entry_id="<?php echo $ansDets["aid"]; ?>" ans_id="<?php echo $ansDets["aid"]; ?>" type="<?php echo $type ;?>" name="<?php echo $ansDets["question_id"]; ?>[]" value="<?php echo $ansDets["aid"]; ?>" fuq_id="<?php echo $ansDets["fuq_id"]; ?>" has_fuq="<?php echo $ansDets["has_fuq"]; ?>" entryId="<?php echo $ansDets["entry_id"]; ?>" onclick="saveAnswer('<?php echo $priQuesDets[$keys[0]]["question_id"];?>','<?php echo $ansDets["aid"];?>','<?php echo $ansDets["answerRemedies"];?>','<?php echo $ansDets["ans_label"];?>')" /> 
                    <label for="<?php echo $priQuesDets[$keys[0]]["question_id"].$ansDets["aid"]; ?>"><?php echo ucfirst(strtolower($ansDets["ans_label"])); ?></label>
                  </div>
                <?php } }
                      } }?>
                </div>
              </div>
              </div>
              <?php } } }else{
                if($userGender==$gender || $userGender=='Both'){
                 if($userType==$usergroup || $userType=='Both'){
                  if($userAge==$age || $userAge=='Both'){
                      if( $multi=='yes'){
                        $type='checkbox';
                        $selection='Multiple selection';
                      }else
                      {
                        $type='radio';
                        //$selection='Single selection';
                        $selection='Select Prominent Option';

                      }
            ?>

              <div class="col-md-12 col-sm-12 col-xs-12 quest">
              <div class="">
                <div class="questHead">
                  <h3 class="questTitle"><?php echo $priQuesDets[$keys[0]]["question_name"]; ?></h3>
                  <p><?php echo $selection; ?></p>
                </div>
               
                <div class="ansdiv">
                <?php
                  foreach($priQuesDets as $ansLabel=>$ansDets){
                    //printArr($ansDets);
                    if($ansDets['multiChoice']=='yes'){
                      $type='checkbox';
                    }else
                    {
                      $type='radio';
                      
                    }
                    foreach ($getAllCaseSheetDetails as $key => $value) {
                          if($value['question_id']==$ansDets["question_id"])
                          { 
                            $checked = "checked";
                           if(!empty($ansDets['ans_label'])){
                ?>
                  <div class="ans11">
                    <!-- <input type="<?php echo $type; ?>" id="desire[0]" name="" value="High" required="">
                    <label class="event" for="desire[0]">High</label> -->
                    <input id="<?php echo $priQuesDets[$keys[0]]["question_id"].$ansDets["aid"]; ?>"  <?php if ($value['aid']==$ansDets["aid"]) echo $checked; ?>  entry_id="<?php echo $ansDets["aid"]; ?>" ans_id="<?php echo $ansDets["aid"]; ?>" type="<?php echo $type ;?>" name="<?php echo $ansDets["question_id"]; ?>[]" value="<?php echo $ansDets["aid"]; ?>" fuq_id="<?php echo $ansDets["fuq_id"]; ?>" has_fuq="<?php echo $ansDets["has_fuq"]; ?>" entryId="<?php echo $ansDets["entry_id"]; ?>" onclick="saveAnswer('<?php echo $priQuesDets[$keys[0]]["question_id"];?>','<?php echo $ansDets["aid"];?>','<?php echo $ansDets["answerRemedies"];?>','<?php echo $ansDets["ans_label"];?>')" /> 
                    <label for="<?php echo $priQuesDets[$keys[0]]["question_id"].$ansDets["aid"]; ?>"><?php echo ucfirst(strtolower($ansDets["ans_label"])); ?></label>
                  </div>
                <?php } }
                      } }?>
                </div>
              </div>
              </div>
              <?php } } }
                } } ?>

              <input type="hidden" name="case_id" value="<?php echo $_GET['case_id'] ;?>">
              <input type="hidden" name="step_no" value="5">

            </div>
            <div class="col-md-12" style="text-align: center;margin-top:80px;position:inherit;" >
                  <h4 id="errorMsg" style="color:red;"></h4>
                  <button style="outline:0;border: none; background-color: transparent;" id="step5submit" type="submit" class="next-btn"><img src="../../assets/images/nextbtn.png"/></button>
                  <button button type=""  onClick="" class="signupbtn load" style="width: 20%;display: none;" >Loading...<img src="../../assets/images/ajax-loader.gif"></button>
                  <!-- <input type="image" src="../../assets/images/nextbtn.png" id="" class="next-btn" value="" style="outline:0;"> -->
                </div>

        </form>
        </div>
      </section>
    </main> 
    <?php include("../modals.php"); ?> 
    <?php include('../footer.php'); ?>
<script type = "text/javascript" src= "../../assets/js/chosen.jquery.min.js"></script>

<script type="text/javascript">
$('#close-select').click(function () {
$('#close-pic').click();
});

$('#full-select').click(function () {
$('#full-pic').click();
});

$('#nose-select').click(function () {
$('#nose-pic').click();
});

$('#nails-select').click(function () {
$('#nails-pic').click();
});

$('#toes-select').click(function () {
$('#toes-pic').click();
});

$('#fingers-select').click(function () {
$('#fingers-pic').click();
});

 function readURL(input,a) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#'+a).attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#close-pic").change(function(){
        readURL(this,'close-display');
    });

    $("#full-pic").change(function(){
        readURL(this,'user-pic');
    });

    $("#nose-pic").change(function(){
        readURL(this,'nose-display');
    });

    $("#nails-pic").change(function(){
        readURL(this,'nails-display');
    });

    $("#toes-pic").change(function(){
        readURL(this,'toes-display');
    });

    $("#fingers-pic").change(function(){
        readURL(this,'fingers-display');
    });

  function saveAnswer(q_id,a_id,answerRemedies,ans_label){
   var case_id=<?php echo $_GET['case_id']; ?>;
   var patient_id=<?php echo $_GET['patient_id']; ?>;   
   //alert(case_id); 
   $.ajax({type: "POST",
            url:"../../controllers/startCaseController.php",
            data:{patient_id:patient_id,
                  case_id:case_id,
                  q_id:q_id,
                  a_id:a_id,
                  answerRemedies:answerRemedies,
                  ans_label:ans_label,
                  type:'ans_step5'},
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
  function saveTextAns(val,column_name){
  var case_id=<?php echo $_GET['case_id']; ?>;
  $.ajax({type: "POST",
            url:"../../controllers/startCaseController.php",
            data:{val:val,
                  column_name:column_name,
                  case_id:case_id,
                  type:'textans_steps'},
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

 /* $('form#step5Form').submit(function(event) {
    var case_id=<?php echo $_GET['case_id']; ?>;
    $.ajax({type: "POST",
            url:"../../controllers/startCaseController.php",
            data:{case_id:case_id,
                  type:'step5'},
            dataType:'json',
      })
      .done(function(data) {
        console.log(data);
        window.location.href="../remedies/remedies.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>";
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        //alert("error");
        console.log('error');
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       }) 
 
    event.preventDefault();
$( "#step5Form" ).scrollTop( 0 );
  });*/


  $('form#step5Form').submit(function(event) {

    var formdata = new FormData($(this)[0]);
    formdata.append('type','step5');
    ajaxCall('../../controllers/startCaseController.php',formdata,$(this));

    event.preventDefault();
$( "#step5Form" ).scrollTop( 0 );
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
          console.log(data);
          window.location.href="step6.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>";
          //window.location.href="../remedies/remedies.php?doctor_id=<?php echo $userInfo['user_id'];?>&patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>";
        }else{
          $('.successBox').hide();
          $('.errMsg').html(data['errMsg']);
          $('.errBox').show();
           alert(data['errMsg']);
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
