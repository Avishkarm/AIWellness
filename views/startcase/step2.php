  <?php
  session_start();


 $activeHeader = "doctorsArea";

  require_once("../../utilities/config.php");
  require_once("../../utilities/dbutils.php");
  require_once("../../models/commonModel.php");
  require_once("../../models/userModel.php"); 
  require_once("../../models/startCaseModel.php"); 



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
  $complaintsQuery=getSystemComplaint($conn,'');
  
  if(noError($complaintsQuery)){     
    $complaintsQuery=$complaintsQuery['errMsg'];
  }            
  else{
    $msg= printArr("Error Fetching Complaints Data".$complaintsQuery['errMsg']);  
  }
  $complaintsQuery1=getSystemCommonNames($conn,'');
    
  if(noError($complaintsQuery1)){    
     $complaintsQuery1=$complaintsQuery1['errMsg'];
    }            
  else{
     $msg= printArr("Error Fetching Complaints Data".$complaintsQuery1['errMsg']);  
  }
  //printArr($complaintsQuery);

  //get all main sensations for drop down
$sensationsQuery= getAllSensations($conn);
if(noError($sensationsQuery)){
  $sensationsQuery=$sensationsQuery['errMsg'];
} else {
  printArr("Error Fetching sensations Data".$sensationsQuery['errMsg']);
}
//get all main modalities for drop down
$modalitiesQuery= getAllModalities($conn);
if(noError($modalitiesQuery)){
  $modalitiesQuery=$modalitiesQuery['errMsg'];
} else {
  printArr("Error Fetching modalities Data".$modalitiesQuery['errMsg']);
}
$getAllCaseDetails= getAllCaseDetails($conn,$_GET['case_id']);
if(noError($getAllCaseDetails)){
  $getAllCaseDetails=$getAllCaseDetails['errMsg'];

  $getComplaint=explode(",", $getAllCaseDetails["complaint_name"]);
 

} else {
  printArr("Error Fetching case details".$getAllCaseDetails['errMsg']);
}
//printArr($getAllCaseDetails);
$case_id=$_GET['case_id'];
$updateStepNo=updateStepNo($case_id,2,$conn);
if(!empty($getAllCaseDetails["complaint_name"])){
  $complaintStatus=1;
  $getComplaint=explode(",", $getAllCaseDetails["complaint_name"]);
}else{
  $complaintStatus=0;
}

/*printArr($getComplaint);


foreach($complaintsQuery as $complaintsId=>$complaintsDetails)
                        {
                          $selected = "";
                          //$complaintsName=utf8_encode($complaintsDetails["Common_name"]);
                          $complaintsName1=cleanQueryParameter($conn,utf8_encode($complaintsDetails["Diagnostic_term"]));
                          $complaintsName_new=preg_replace('/\s+/', '_', str_replace("'", "",stripcslashes($complaintsName1)));
                          //echo $complaintsName_new;
                           $getComplaint=explode(",", $getAllCaseDetails["complaint_name"]);
                           // 

                          foreach ($getComplaint as $key => $value) {
                              $comp=stripcslashes(ucfirst(strtolower($complaintsName1)));
                                if($comp==$value){
                                   $selected = "selected";
                                 }
                            }
                            echo "1". $selected;
                        }
foreach($complaintsQuery1 as $complaintsId=>$complaintsDetails)
                        {
                          $selected = "";
                          //$complaintsName=utf8_encode($complaintsDetails["Common_name"]);
                          $complaintsName1=cleanQueryParameter($conn,utf8_encode($complaintsDetails["Common_name"]));
                          $complaintsName_new=preg_replace('/\s+/', '_', str_replace("'", "",stripcslashes($complaintsName1)));
                          //echo $complaintsName_new;
                           $getComplaint=explode(",", $getAllCaseDetails["complaint_name"]);
                           // 

                          foreach ($getComplaint as $key => $value) {
                              $comp=stripcslashes(ucfirst(strtolower($complaintsName1)));
                                if($comp==$value){
                                   $selected = "selected";
                                 }
                            }
                            echo "2". $selected;
                        }*/

  
                        //printArr($getComplaint);
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
          font-size: 21px;
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
.chosen-container{
  font-size: 18px !important;
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
  height: inherit;
}    
.gerneral-form .field-input {
    width :100%;
    }
 .hideme{
      display: none;
    }   
 .left-padd-15 {
  padding-left: 0px;
}
.set-height{
      height: 50px !important;
    }
  
  h1{
        width: 100%;
        text-align: left;
        font-size: 25px;
        margin-left: 10px;
      } 
 }

.gerneral-form input[type=number]{
   border-color: #9b9b9b;
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


 .left-padd-15 {
  padding-left: 15px;
 }
    
    
select{
    -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-appearance: none;
  -moz-appearance: none;
}

.chosen-single {
        background-image: linear-gradient(45deg, transparent 50%, gray 50%), linear-gradient(135deg, gray 50%, transparent 50%), linear-gradient(to right, #ccc, #ccc) !important;
    background-position: calc(100% - 23px) calc(1em + 5px), calc(100% - 16px) calc(1em + 5px), calc(100% - 2.5em) 0.5em !important;
    background-size: 8px 8px, 8px 8px, 0px 5em !important;
    background-repeat: no-repeat !important;

}     
   

  .chosen-choices{
    position: relative;
    display: block;
    overflow: scroll;
    padding: 0 0 0 8px;
    height: 50px !important;
    border: 1px solid #9b9b9b;
    border-radius: 0px;
    background-color: #fff;
    background-clip: padding-box;
    box-shadow: 0 0 3px #fff inset, 0 1px 1px rgba(0,0,0,.1);
    color: #444;
    text-decoration: none;
    white-space: nowrap;
    line-height: 24px;


    background-image: linear-gradient(45deg, transparent 50%, gray 50%), linear-gradient(135deg, gray 50%, transparent 50%), linear-gradient(to right, #ccc, #ccc) !important;
    background-position: calc(100% - 23px) calc(1em + 5px), calc(100% - 16px) calc(1em + 5px), calc(100% - 2.5em) 0.5em !important;
    background-size: 8px 8px, 8px 8px, 0px 5em !important;
    background-repeat: no-repeat !important;
  } 

  .chosen-container-multi .chosen-choices li.search-choice .search-choice-close{
        background: url('../../assets/images/error.png')  no-repeat !important;
        background-size: contain !important;
  }
  .chosen-container-multi .chosen-choices li.search-choice{
   height: 20px !important;
   font-size: 14px;
  }

  .chosen-container{
    margin-left: 4%;
  }

  .textleft h4{
    text-align: left !important;
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
             <img src="../../assets/images/step2.png"   style="width:100%;">
            
              <div class="steplinks step2">

                <a href="step1.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" >Step 1</a>
                <a href="#" class="active" >Step 2</a>
                <a href="step3.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" >Step 3</a>
                <a href="step4.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" >Step 4</a>
                <a href="step5.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" >Step 5</a>
                <a href="step6.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" >Step 6</a>
             </div>
            </div>
          </div>

          <form action="javascript:;" name="step2Form" id="step2Form" method="post" enctype="multipart/form-data" >
          <div class="row stepFrmDiv">
            
            <div class="col-md-10 col-xs-12 gerneral-form" style="padding-left: 0px;" >
            
            

               <div class="form-group">
                  <div class="label-div" >
                    <label>Main complaint</label>
                  </div>
                  <div class="field-input">
                 
                    <select onchange="setDurationBoxes(this)" class="form-control  chosen-select chosen-mui" required type="text" id="complaint" name="complaint[]" multiple <?php if($getAllCaseDetails["ref_case_id"]!=0){ echo "disabled"; } ?>>
                        
                        <?php
                        

                        $getComplaint=explode(",", $getAllCaseDetails["complaint_name"]);
                        foreach($complaintsQuery as $complaintsId=>$complaintsDetails)
                        {
                          //$complaintsName=utf8_encode($complaintsDetails["Common_name"]);
                          $complaintsName1=cleanQueryParameter($conn,utf8_encode($complaintsDetails["Diagnostic_term"]));
                          //$definition=cleanQueryParameter($conn,nl2br($complaintsDetails["Definition"],false));
                          /*$definition=cleanQueryParameter($conn,$complaintsDetails["Definition"]);
                          $definition=str_replace( "\n", '<br />', $definition );*/
                           $definition=stripcslashes(str_replace("\u00a0", "",cleanQueryParameter($conn,$complaintsDetails["Definition"])));
                          $System=$complaintsDetails["system"];
                          $Organ=$complaintsDetails["organ"];
                          $Suborgan=$complaintsDetails["subOrgan"];
                          $Embryological=$complaintsDetails["embryologcial"];
                          $Miasm=$complaintsDetails["miasm"];
                           $definition = str_replace('"', '', $definition);
                           

                           $selected = "";
                           $complaintsName_new=preg_replace('/\s+/', '_', str_replace("'", "",stripcslashes($complaintsName1)));
                           $complaintsName_new;
                           foreach ($getComplaint as $key => $value) {
                           // echo $value;
                            $comp=stripcslashes(ucfirst(strtolower($complaintsName1)));
                                if(stripcslashes($comp)==$value){
                                $selected = "selected";
                                 }
                            }
                          /*if($complaintsName1==$getAllCaseDetails["complaint_name"] || $complaintsName1==$getAllCaseDetails["complaint_name"])
                            $selected = "selected";*/

                        ?>
                        <!-- data-def="<?php echo utf8_encode($definition);?>" -->
                        <option id="<?php echo $complaintsId; ?>" value="<?php echo $complaintsName_new ;?>" data-opt="drop2" <?php echo $selected; ?> data-sys="<?php echo $System;?>" data-organ="<?php echo $Organ;?>" data-suborgan="<?php echo $Suborgan;?>" data-embbryo="<?php echo $Embryological;?>" data-miasm="<?php echo $Miasm;?>"  data-def="<?php echo utf8_encode(nl2br($definition));?>"  data-name="<?php echo $complaintsName1;?>" >

                          <?php echo ucfirst(strtolower(stripcslashes(str_replace("\u00a0", "",$complaintsName1))));?> 

                        </option> 
                        <?php   }
                          foreach($complaintsQuery1 as $complaintsId1=>$complaintsDetails1)
                          {
                              $selected = "";
                              $complaintsName=cleanQueryParameter($conn,utf8_encode($complaintsDetails1["Common_name"]));
                              $definition=stripcslashes(str_replace("\u00a0", "",cleanQueryParameter($conn,$complaintsDetails1["Definition"])));
                              $System=$complaintsDetails1["system"];
                              $Organ=$complaintsDetails1["organ"];
                              $Suborgan=$complaintsDetails1["subOrgan"];
                              $Embryological=$complaintsDetails1["embryologcial"];
                              $Miasm=$complaintsDetails1["miasm"];
                              $definition = str_replace('"', '', $definition);
                              
                              /*foreach ($getComplaint as $key => $value) {
                              $comp=stripcslashes(ucfirst(strtolower($complaintsName)));
                                if($comp==$value){
                                   $selected = "selected";
                                 }
                            }*/

                            foreach ($getComplaint as $key => $value) {
                           //echo $value;
                            $comp1=stripcslashes(ucfirst(strtolower($complaintsName)));
                                if(stripcslashes($comp1)==trim($value)){
                                $selected = "selected";
                                 }
                            }
                            $complaintsName_new=preg_replace('/\s+/', '_', str_replace("'", "",stripcslashes($complaintsName)));
                               //$definition=stripcslashes(str_replace("\u00a0", "",cleanQueryParameter($conn,$definition)));
                            if($complaintsName !='Eczema' && $complaintsName!='Dermatitis'){ ?>
                             <option id="<?php echo $complaintsId; ?>" value="<?php echo $complaintsName_new;?>" data-opt="drop2" <?php echo $selected; ?> data-sys="<?php echo $System;?>" data-organ="<?php echo $Organ;?>" data-suborgan="<?php echo $Suborgan;?>" data-embbryo="<?php echo $Embryological;?>" data-miasm="<?php echo $Miasm;?>"  data-def="<?php echo utf8_encode(nl2br($definition));?>"  data-name="<?php echo $complaintsName;?>" >

                                <?php echo ucfirst(strtolower(stripcslashes(str_replace("\u00a0", "",$complaintsName))));?> 

                              </option> 

                    <?php
                  }}
                  ?> 
                  </select>
                  </div>  
               </div> 


               
               <div id="durationsContainer"></div> 
               <div class="form-group" id="extra-info">
                   <!-- <div class='label-div hideme' style='height:140px;'><label></label></div><div class='field-input ' style='height:140px;'><div class='row left-padd-15' ><div class='col-md-4 col-sm-4 col-xs-4'><input onblur="saveTextAns(this.value,'year')" type='number' class='form-control' name='year' placeholder='Year' min='1' ></div><div class='col-md-4 col-sm-4 col-xs-4'><input  onblur="saveTextAns(this.value,'month')" type='number' class='form-control' name='month' placeholder='Month' min='1' max='12'></div><div class='col-md-4 col-sm-4 col-xs-4'><input onblur="saveTextAns(this.value,'day')" type='number' class='form-control' name='day' placeholder='Day' min='1' max='31'></div></div><div class='row left-padd-15' style='margin-top:35px;'><div class='col-md-4 col-sm-4 col-xs-4'><input type='text' id='system' class='form-control'  name='system' placeholder='System'></div><div class='col-md-4 col-sm-4 col-xs-4'><input type='text' class='form-control' id='organ'  name='organ' placeholder='Organ'></div><div class='col-md-4 col-sm-4 col-xs-4'><input type='text' class='form-control' id ='diagnosis' name='diagnosis' placeholder='Diagnosis'></div></div></div> -->


               </div>
               <div id="durationsContainer"></div>    


               <div style="clear:both;"></div>
                <div class="form-group" >
                  <div class="label-div set-height"  >
                    <label>Other complaint</label>
                  </div>
                  <div class="field-input" style="height:100px;">
                    <textarea  onblur="saveTextAns(this.value,'other_complaint')" class="form-control"  name="other_complaint" ><?php echo $getAllCaseDetails["other_complaint"]; ?></textarea>
                    <h4 style="text-align:left;margin-left:20px;">Please mention if any</h4>
                  </div>  
                  <div style="clear:left;"></div>
                </div>



               <!--  <div class="form-group">
                  <div class="label-div" >
                    <label id="sensation">Sensation(how do you feel)</label>
                  </div>
                  <div class="field-input">
                   
                    <select onchange="saveTextAns(this.value,'sensation')" class="form-control chosen-select "  type="text" id="sensation" name="sensation">
                    <?php
                    foreach($sensationsQuery as $sensationsId=>$sensationsDetails){
                      $sensationsName = $sensationsDetails["sensationName"];
                      $selected = "";
                      if($sensationsName==$getAllCaseDetails["sensation"])
                        $selected = "selected";
                      ?>
                      <option <?php echo $selected; ?> value="<?php echo $sensationsName; ?>"><?php echo $sensationsName; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  </div>  
               </div>  -->


            <!--    <div class="form-group">
                  <div class="label-div" >
                    <label style="margin-top: -18px;" id="modalities">What makes it better/worse (modalities)</label>
                  </div>
                  <div class="field-input">
                  
                    <select onchange="saveTextAns(this.value,'modalities')" class="form-control chosen-select"  type="text" id="modalities" name="modalities" >
                    <?php
                    foreach($modalitiesQuery as $modalitiesId=>$modalitiesDetails){
                      $modalitiesName = $modalitiesDetails["modalitiesName"];
                      $selected = "";
                      if($modalitiesName==$getAllCaseDetails["modalities"])
                        $selected = "selected";
                      ?>
                      <option <?php echo $selected; ?> value="<?php echo $modalitiesName; ?>"><?php echo $modalitiesName; ?></option>
                      <?php
                    }
                    ?>
                  </select>
                  </div>  
               </div>   -->

               <!-- <div class="form-group" >
                  <div class="label-div set-height"  >
                    <label>Pathological process</label>
                  </div>
                  <div class="field-input" style="height:100px;margin-bottom:10px;">
                    <textarea  onblur="saveTextAns(this.value,'pathalogical_process')" class="form-control" name='pathalogical_process' id="pathalogical_process" ><?php echo $getAllCaseDetails["pathalogical_process"]; ?></textarea> 
                  </div>  
                </div> -->  

                
               <!--  <div class="field-input ">
                  <div class="col-md-9 col-xs-9" style="padding-right: 0;">
                    <input type="text" id="fc" class="form-control" readonly style="float: left;margin-left:2%;" value="Choose file">
                    <input type="file" onchange="saveTextAns(this.value,'path_pros_image')" name="path_pros_image" id="select-img" class="form-control" style="position:relative;overflow:hidden;display: none;">
                  </div>
                 
                 </div> -->
            <div style="clear:both;"></div>



                <h1>Patient history</h1>

                <div class="form-group" >
                  <div class="label-div " style="height:120px;" > <!-- 120 height -->
                    <label>Did you suffer from any major illnesses in the past?
Any history of accidents, surgeries?</label>
                  </div>
                  <div class="field-input" style="height:120px;">
                    <textarea onblur="saveTextAns(this.value,'past_history')" class="form-control" name='past_history' style="height:115px;"><?php echo $getAllCaseDetails["past_history"]; ?></textarea> 
                  </div> 
                  <div style="clear:left;"> </div>
                </div>

                 <h1>Family history</h1>
                

                <div class="form-group">
                  <div class="label-div " style="height:100px;" >
                    <label>Is anybody from your family suffering from any illnesses?</label>
                  </div>
                  <div class="field-input" style="padding-left:25px;padding-top:10px;height:100px;">
                    <input onchange="saveTextAns(this.value,'family_hist_satus')"  <?php if($getAllCaseDetails['family_hist_satus']=='yes'){ echo 'checked';} ?> type="radio" id="yes" name="family_hist_satus" onclick="ShowFamilyHistory()" value="yes" class="form-control">
                    <label for="yes">Yes</label>

                    <input onchange="saveTextAns(this.value,'family_hist_satus')" <?php if($getAllCaseDetails['family_hist_satus']=='no'){ echo 'checked';} ?> type="radio" id="no" name="family_hist_satus" onclick="HideFamilyHistory()" value="no" class="form-control">
                    <label for="no">No</label>
                  </div>  
               </div> 
               <?php if($getAllCaseDetails['family_hist_satus']=='yes'){ ?>
                <div class="form-group" id="family_history">
                  <div class="label-div hideme">  <!-- style="height:120px;" -->
                    <label></label>
                  </div>
                  <div class="field-input" style="height:120px;">
                    <textarea onblur="saveTextAns(this.value,'family_history')" class="form-control" name='family_history' style="height:115px;"><?php echo $getAllCaseDetails["family_history"]; ?></textarea> 
                  </div>  
              </div>
               <?php }else{ ?>
               <div class="form-group" id="family_history" style="display: none;">
                  <div class="label-div hideme">  <!-- style="height:120px;" -->
                    <label></label>
                  </div>
                  <div class="field-input" style="height:120px;">
                    <textarea onblur="saveTextAns(this.value,'family_history')" class="form-control" name='family_history' style="height:115px;"><?php echo $getAllCaseDetails["family_history"]; ?></textarea> 
                  </div>  
              </div>
              <?php } ?>
               <input type="hidden" name="case_id" value="<?php echo $_GET['case_id'] ;?>">
                 <input type="hidden" name="step_no" value="2">

            </div>
          </div>
          
          
          

            <div class="row">
                <div class="col-md-12" style="text-align: center;margin-top:80px;" >
                  <h4 id="errorMsg" style="color:red;"></h4>
                  <button style="outline:0;border: none; background-color: transparent;" id="step2submit" type="submit" class="next-btn"><img src="../../assets/images/nextbtn.png"/></button>
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

  $( window ).load(function() {
  // Run code
var complaintStatus=<?php echo $complaintStatus; ?>;
if(complaintStatus){
  getComplaintDetails();
}
});
function getComplaintDetails(){
  var pausecontent = new Array();
    <?php foreach($getComplaint as $key => $val){ ?>
        pausecontent.push('<?php echo strtoupper(preg_replace('/\s+/', '_', str_replace("'", "",stripcslashes($val)))); ?>');
    <?php } ?>
    console.log(pausecontent);
    getDurationContainer(pausecontent);
}


function getDurationContainer(values){

    var complaintName = "";
    var durationHTML = "";
    var durationHTML1 = "";
    var system="";
    var organ="";
    var def="";
    for(var i in values){
      complaintName = $('option[value="'+values[i]+'"]').text();
      system=$("#complaint option[value="+values[i]+"]").attr('data-sys');
      organ=$("#complaint option[value="+values[i]+"]").attr('data-organ');
      suborgan=$("#complaint option[value="+values[i]+"]").attr('data-suborgan');
      miasm=$("#complaint option[value="+values[i]+"]").attr('data-miasm');
      def=$("#complaint option[value="+values[i]+"]").attr('data-def');    
      def=nl2br (def, false);
     
      durationHTML += "<div class='label-div hideme' id='durationsBox_"+i+"'  style='height:auto;'></div><div class='field-input ' style='height:auto;'><div class='row left-padd-15 ' ><div class='col-md-12'><label>"+complaintName+"</label></div><div class='col-md-4 col-sm-4 col-xs-4'><input type='number' class='form-control' name='probDuration["+values[i]+"][years]' placeholder='Year' min='1' ></div><div class='col-md-4 col-sm-4 col-xs-4'><input  type='number' class='form-control' name='probDuration["+values[i]+"][months]' placeholder='Month' min='1' max='12'></div><div class='col-md-4 col-sm-4 col-xs-4'><input  type='number' class='form-control' name='probDuration["+values[i]+"][days]' placeholder='Day' min='1' max='31'></div></div><div class='row left-padd-15 textleft' style='margin-top:35px;'><h4>System: "+system+"<h4><h4>Organ: "+organ+"<h4><h4>Suborgan: "+suborgan+"<h4><h4>Miasm: "+miasm+"<h4><h4 id='cdef'>Definition: "+def+"<h4></div> <div style='clear: both;''></div>     </div>";
    }

    $("#durationsContainer").html(durationHTML);
}


  $('#fc').click(function () {
    $('#select-img').click();
  });

//$("#family_history").hide();
  function ShowFamilyHistory()
{
$("#family_history").css("display","block");
}

  function HideFamilyHistory()
{
$("#family_history").css("display","none");
}
$('form#step2Form').submit(function(event) {
   
    /* Act on the event */
   // var type="step1";
    //var formdata=$('#contactForm').serialize();
   // alert('hii');
    if(checkComplaint()){
    var formdata = new FormData($(this)[0]);
    formdata.append('type','step2');
    //alert('noError');
    //console.log(formdata);
   // location.href="controllers/signUpController.php";
   ajaxCall('../../controllers/startCaseController.php',formdata,$(this));
  }else{
   // alert('error');
    event.preventDefault();
    console.log('errrrr');
  }
$("#step2Form" ).scrollTop( 0 );
  });
function checkComplaint() {
  //alert('hiii');
        flag = 1;
          var options = $('#complaint > option:selected');
          //alert(options.length);
            if(options.length == 0){
            //No input filled
            alert("Please select complaint duration");
            flag = 0;
            return false;
          }
         
         return flag;
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
          window.location.href="step3.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>";
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

  /*$('#complaint').on('change', function(event, params) {
                //console.log(params);
    if(params.hasOwnProperty('selected')){
      var compName = $("#complaint option[value="+params['selected']+"]").text();
       getPathalogicalProcess(compName);
    }else if(params.hasOwnProperty('deselected')){
      var compName = $("#complaint option[value="+params['deselected']+"]").text();
      updatePathalogicalProcess(compName);
    }
});*/
/*  function nl2br (str, is_xhtml) {
     var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}*/
function nl2br (str, is_xhtml) {   
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}
function stripslashes (str) {

  return (str + '').replace(/\\(.?)/g, function (s, n1) {
    switch (n1) {
    case '\\':
      return '\\';
    case '0':
      return '\u0000';
    case '':
      return '';
    default:
      return n1;
    }
  });
}

function titleCase(str) {
  string=str.toLowerCase()
    return string.charAt(0).toUpperCase() + string.slice(1);
}


function setDurationBoxes(ele){
//console.log(ele);
  var val = $("#complaint").val();
          //console.log(val);
          if(val!== null){
            $("#duration").show();
              var vals = val.toString();
            
              var values = vals.split(",");
           
  console.log(values);
  // alert(values);

  var dataArray = new Array();
  var complaintName = "";
  var durationHTML = "";
   var durationHTML1 = "";
    var system="";
     var organ="";
     var def="";
  for(var i in values){
    if(values[i]!=""){
   // console.log(values[i]);
     //console.log($("#complaint option[value="+values[i]+"]"));
    complaintName = $('option[value="'+values[i]+'"]').text();
    //console.log(complaintName);

     dataArray.push(jQuery.trim(complaintName));
    system=$("#complaint option[value="+values[i]+"]").attr('data-sys');
    organ=$("#complaint option[value="+values[i]+"]").attr('data-organ');
    suborgan=$("#complaint option[value="+values[i]+"]").attr('data-suborgan');
    miasm=$("#complaint option[value="+values[i]+"]").attr('data-miasm');
   // system=$("#complaint option[value="+values[i]+"]").attr('data-sys');
    //organ=$("#complaint option[value="+values[i]+"]").attr('data-organ');
    def=$("#complaint option[value="+values[i]+"]").attr('data-def');    
    def=nl2br (def, false);
    /*if(complaintName==""){
        complaintName=titleCase(stripslashes(values[i].replace(/_/g, ' ')));

    }*/
   // console.log(system);

    //console.log(complaintName);
    durationHTML += "<div class='label-div hideme' id='durationsBox_"+i+"'  style='height:auto;'></div><div class='field-input ' style='height:auto;'><div class='row left-padd-15 ' ><div class='col-md-12'><label>"+complaintName+"</label></div><div class='col-md-4 col-sm-4 col-xs-4'><input type='number' class='form-control' name='probDuration["+values[i]+"][years]' placeholder='Year' min='1' ></div><div class='col-md-4 col-sm-4 col-xs-4'><input  type='number' class='form-control' name='probDuration["+values[i]+"][months]' placeholder='Month' min='1' max='12'></div><div class='col-md-4 col-sm-4 col-xs-4'><input  type='number' class='form-control' name='probDuration["+values[i]+"][days]' placeholder='Day' min='1' max='31'></div></div><div class='row left-padd-15 textleft' style='margin-top:35px;'><h4>System: "+system+"<h4><h4>Organ: "+organ+"<h4><h4>Suborgan: "+suborgan+"<h4><h4>Miasm: "+miasm+"<h4><h4 id='cdef'>Definition: "+def+"<h4></div> <div style='clear: both;''></div>     </div>";
}
}
      //console.log(complaintName);
      //saveTextAns(complaintName,'complaint_name')
//console.log(dataArray);
     saveComplaint(dataArray,'complaint_name')
      //$('#modalities').html("What makes it better or worse:(for "+complaintName+" modalities)");
      //$('#sensation').html("Sensation (how do you feel for: "+complaintName+" )");
      /*var g = 0;
      var values1 = [];
      $(".search-choice-close").each(function(){
            var p = parseInt($(this).attr("data-option-array-index")); //this.id
            values1[g] = p+1;
            var d=g;
            g++;

        });
      //console.log(values);
      var complaintName1 = "";
      
      for(var i=0;i<values1.length;i++){
        complaintName1 = $("#complaint option[value="+values1[i]+"]").text();
      }*/
      $("#durationsContainer").html(durationHTML);

      }else{
            $("#durationsContainer").hide();
          }
/* $('#cdef').html().replace(/\n/g, "<br />");
 alert( $('#cdef').html().replace(/\n/g, "<br />"));*/
    }

    function saveComplaint(val,column_name){
  var case_id=<?php echo $_GET['case_id']; ?>;
  $.ajax({type: "POST",
            url:"../../controllers/startCaseController.php",
            data:{val:val,
                  column_name:column_name,
                  case_id:case_id,
                  type:'saveComplaint'},
            dataType:'json',
      })
      .done(function(data) {
        //console.log(data);
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


function getPathalogicalProcess(compName){
  var comp=compName;
  $("#pathoProcess").find(".ajax_wait").show();
  $.ajax({  
    url:"../../controllers/mainComplaintController.php",  
    method:"POST",  
    data:{type:'pathalogicalProcessAdd' ,
    comp:comp

  },  
  dataType:"json",  
  success:function(data){ 
    //$("#pathoProcess").find(".ajax_wait").hide();
    var dt=data;
    

    if(dt['errCode'][-1]==-1){
      dt=dt['errMsg'];
//console.log(dt['errMsg']);
         

        var val2=dt['Definition'];
        var val1=dt['Diagnostic_term'];
        //alert(val2);
          //strip new line
          val2 = val2.replace(/\n|\r/g, "");
          //val2 =val2.replace(/(['"])/g, "\\$1");

          var pathStr = $('#pathalogical_process').val();

          pathStr +=val1+"::"+val2+"\n"
          $('#pathalogical_process').val(pathStr);

        }else{
         // alert('hii');
        }
      }  

    }); 
}

function updatePathalogicalProcess(compName){
  var str = $('#pathalogical_process').val()
  //alert(compName);
  //alert(str);
  var b= str.split('\n');
  b= b.filter(Boolean);
   //alert(b);
  var obj = {}
  for(var i = 0; i< b.length; i++){
    var arr = b[i].split("::");
    obj[arr[0]] =  arr[1];
  }
  delete obj[compName];
  console.log(obj);
  var af = Object.keys(obj).map(function (key) { return key+"::"+obj[key]; });
  af = af.join("\n");
  $('#pathalogical_process').val(af);
}

</script>




</body>

</html>
