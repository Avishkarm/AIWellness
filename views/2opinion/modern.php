  <?php
  session_start();

  //$title="2nd-opinion";
  $title="Case Checker";
  $activeHeader="2opinion";
  $activeHeader1="2opinion";
  require_once("../../utilities/config.php");
  require_once("../../utilities/dbutils.php");
  require_once("../../models/commonModel.php");
  require_once("../../models/userModel.php");
  $complaint=$_GET['complaint']; 

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
  $TreatmentQuery= getAllTreatment($conn);
   // printArr($TreatmentQuery);
  if(noError($TreatmentQuery)){
    $TreatmentQuery=$TreatmentQuery['errMsg'];
  } else {
      printArr("Error Fetching Complaints Data".$TreatmentQuery['errMsg']);
  }

  
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <?php include_once("../metaInclude.php"); ?>
    <style type="text/css">
      .complaintsDetails{
        padding-left: 0px;
        padding-right: 0px;
      }
      .submit-btn{
          background-color: #0dae04;
          border-radius: 7px;
          color: #fff;
          text-align: center;
          padding: 10px 50px;
          outline: none;
          border: none;
          font-size: 23px;
          margin-top: 10px;
          margin-bottom: 20px;
      }
      .chosen-container .chosen-results {
        font-size: 19px !important;
      }
      .chosen-container-multi .chosen-choices li.search-field input[type=text] {
        color:#777;
      }
      .chosen-container-active .chosen-choices {
            border: 1px solid #aaa !important;
            box-shadow: 0 0 5px rgba(0,0,0,.3);
        }
      .chosen-container-multi .chosen-choices {
          font-size: 19px !important;
          font-weight: 500 !important;
          padding: 15px 15px !important;
          background: #fff !important;
         /* border-radius: 4px !important;*/
      }
      .chosen-container-multi .chosen-choices li.search-choice {
        padding: 10px 20px 10px 10px !important;
      }
      .generals{
        margin-top: 80px;
      }
      .particular{
        margin-top: 10%;
      }
      .complaint1{
        margin-top: 10%;
      }
      .questTitle{
        letter-spacing: 1px;
        word-spacing: 1px;
        line-height: 1.5em; 
        font-size: 28px;
        color:#00ad2a;
        min-height: 85px;
      }
      .ans1,.ans2,.ans3{
        display:inline-flex;
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
        font-size: 24px;
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
      .questHead{
        /*margin-bottom: 23px;*/
      }
      .quest{
        border-bottom:1px solid grey;
        margin-bottom: 10px;
        margin-top: 50px;
        margin-left: 15px;
        margin-right: 15px;
      }
      @media(max-width: 786px){
        .ans1,.ans2,.ans3{
          display:block;
        }
      }
      @media(max-width: 1024px){
        .ans1,.ans2,.ans3{
          display:block;
        }
        .ans2,.ans3{
          padding-left: 0%;
        }
      }
      /*@media(max-width: 1024px){
        .ans2,.ans3{
          padding-left: 0%;
        }
        input[type="radio"] + label:before {
            margin: 0  0.1em 0 0;
        }
      }*/
       /*@media(min-width: 1024px and max-width: 1199px){
        .ans2,.ans3{
          padding-left: 2%;
        }
        input[type="radio"] + label:before {
            margin: 0  0.5em 0 0;
        }
      }*/


      @media(min-width: 1024px){
        .ans1,.ans2,.ans3{
          display: block;
          padding-left: 0;

        }
      }
      @media (min-width: 1200px){
        .ans12 {
            display: inline-block;
            padding-left: 0;
            padding-right: 0;
            margin-left: 25px
        }
        .ans11 {
             padding-right: 0px;
             display: inline-block;
        }
      }
      @media (min-width: 1200px){
        .ans22 {
          display: inline-block;
            padding-left: 0;
            padding-right: 0;
            margin-left: 50px;
        }
      }
      @media(min-width: 1200px){
        .ans1{
          display: inline-block;
          padding-left: 0;
          padding-right: 20px;

        }

        .ans2{
          display: inline-block;
          padding-left: 20px;
          padding-right: 20px;

        }

        .ans3{
          display: inline-block;
          padding-left: 0;
          padding-right: 0px;

        }
        .ans2{
          margin-left: 20px;
        }
        .ans3{
          float: right;
        }
      }
      .conclusionContent
      {
        font-size:30px !important;
        font-weight: 600 !important;
        color:#fff;
        padding: 20px;
      }
      #good {
          background-repeat: no-repeat!important;
          margin-left: 15px;
          margin-top: 80px;
          padding: 60px 20px 60px 20px;         
      }
      .head
      {
        font-size:50px !important;
        font-weight: 700 !important;
        color:#fff;
      }
      .complaintAns{
        margin-bottom: 21px;
      }
      .durationInput{
        height: 55px;
          border-radius: 0;
      }
      .timeDuration .col-md-3{
        width:31%;
        padding-right: 0px;
        padding-left: 0px;
      }

        .timeDuration .month{
          margin-left: 19px;
          margin-right: 19px;
      }
      @media(max-width: 786px){
        .timeDuration .month{
            margin-left: 0px;
            margin-right: 0px;
        }
      } 
      @media screen and (min-width: 992px) and (max-width: 1200px) {
        .timeDuration .month{
            margin-left: 15px;
            margin-right: 15px;
        }
      }
      .banner{
          position: relative; 
            width: 100%;
      }
      .banner h1 {
          position: absolute;
          top: 2px;
          left: 18px;
          width: 90%;
          padding: 40px;
          font-size: 55px;
          letter-spacing: 1px;
          word-spacing: 3px;
          line-height: 100%;
      }     
      @media(max-width: 1024px){
        .banner h1 {
            position: absolute;
            top: -4px;
            left: 11px;
            width: 90%;
            padding: 40px;
            font-size: 40px;
            letter-spacing: 4px;
            word-spacing: 0px;
            font-weight: 600;
        }
      }
      @media(max-width: 786px){
        #dropdown1,#dropdown2{
          width:100%;
        }
        .banner h1 {
            position: absolute;
            top: -12px;
            left: 9px;
            width: 90%;
            padding: 32px;
            font-size: 32px;
            font-weight: 600;
            letter-spacing: 1px;
            word-spacing: 8px;
        }
      }
      @media(max-width: 435px){
        .banner h1 {
            position: absolute;
            top: -40px;
            left: -12px;
            width: 100%;
            padding: 40px;
            font-size: 12px;
            letter-spacing: 1px;
            word-spacing: 4px;
            font-weight: 600;
            line-height: 1.3em;
        }
      } 
      .heading-info {
          margin-left: 5px;
          vertical-align: super;
          cursor: pointer;
          height: 20px;
      }


     /* @media(min-width: 1024px and max-width: 1366px){
        .ans2,.ans3{
          padding-left: 5%;
        }
        input[type="radio"] + label:before {
            margin: 0  0.5em 0 0;
        }
      }
      */

.chosen-container-multi .chosen-choices li.search-choice .search-choice-close{
        background: url('../../assets/images/error.png')  no-repeat !important;
        background-size: contain !important;
  }
    </style>
    <main class="container" style="min-height: 100%;">
    <link rel="stylesheet" type="text/css" href="../../assets/css/chosen.min.css">
      <?php  include_once("../header.php"); ?> 
      <section>
      <div class="main-container">
        <form name="secondform" method="post" id="secondform" action="">
          <!-- <div class="row">
            <div class="col-md-12 banner" style="margin: 0px 0px 30px 0px;" >
             <img src="../../assets/images/2opinionbanner.png" class="img-responsive">
             <h1><span style="color:#ffb600;">Let's see whether your treatment is on track or off the rails! </span><span style="color:#ffffff;">Introducing a foolproof application built by the world famous Dr. Khedekar</span></h1>
            </div>
          </div> -->
                <div class="row generals">
                  <div class="col-md-12 text-center" style="" >
                   <h1 style="    font-weight: 600;letter-spacing: 1px;">Generals <img src="../../assets/images/info.png" class="heading-info general"/></h1>
                  </div>
                  <div class="col-md-12" style="padding-left: 0px;" >
                    <div class="col-md-6 col-sm-6">
                    <div class="quest">
                      <div class="questHead">
                        <h3 class="questTitle">Energy/Feeling of well being <img src="../../assets/images/info.png" class="heading-info energy"  /></h3>
                      </div>
                      <div class="ansdiv">
                        <div class="ans1">
                          <input type="radio" id="energy[0]" name="input[generals][Energy_Feeling_of_well_being]" value="good" required="">
                          <label class="event" for="energy[0]">Better</label>
                        </div>
                        <div class="ans2" style="">
                          <input type="radio" id="energy[1]" name="input[generals][Energy_Feeling_of_well_being]" value="bad" required="">
                          <label class="event" for="energy[1]">Worse</label>
                        </div>
                        <div class="ans3">
                          <input type="radio" id="energy[2]" name="input[generals][Energy_Feeling_of_well_being]" value="nochange" required="">
                          <label class="event" for="energy[2]">No change</label>
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                    <div class="quest">
                      <div class="questHead">
                        <h3 class="questTitle">Mind <img src="../../assets/images/info.png" class="heading-info mind"  /></h3>
                      </div>
                      <div class="ans1">
                        <input type="radio" id="mind[0]" name="input[generals][Mind]" value="good" required="">
                        <label class="event" for="mind[0]">Better</label>
                      </div>
                      <div class="ans2">
                        <input type="radio" id="mind[1]" name="input[generals][Mind]" value="bad" required="">
                        <label class="event" for="mind[1]">Worse</label>
                      </div>
                      <div class="ans3">
                        <input type="radio" id="mind[2]" name="input[generals][Mind]" value="nochange" required="">
                        <label class="event" for="mind[2]">No change</label>
                      </div>
                    </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                    <div class="quest">
                      <div class="questHead">
                        <h3 class="questTitle">Appetite <img src="../../assets/images/info.png" class="heading-info appetite"  /></h3>
                      </div>
                      <div class="ans1">
                        <input type="radio" id="Appetite[0]" name="input[generals][Appetite]" value="good" required="">
                        <label class="event" for="Appetite[0]">Better</label>
                      </div>
                      <div class="ans2">
                        <input type="radio" id="Appetite[1]" name="input[generals][Appetite]" value="bad" required="">
                        <label class="event" for="Appetite[1]">Worse</label>
                      </div>
                      <div class="ans3">
                        <input type="radio" id="Appetite[2]" name="input[generals][Appetite]" value="nochange" required="">
                        <label class="event" for="Appetite[2]">No change</label>
                      </div>
                    </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                    <div class="quest">
                      <div class="questHead">
                        <h3 class="questTitle">Sleep <img src="../../assets/images/info.png" class="heading-info sleep"  /></h3>
                      </div>
                      <div class="ans1">
                        <input type="radio" id="sleep[0]" name="input[generals][Sleep]" value="good" required="">
                        <label class="event" for="sleep[0]">Better</label>
                      </div>
                      <div class="ans2">
                        <input type="radio" id="sleep[1]" name="input[generals][Sleep]" value="bad" required="">
                        <label class="event" for="sleep[1]">Worse</label>
                      </div>
                      <div class="ans3">
                        <input type="radio" id="sleep[2]" name="input[generals][Sleep]" value="nochange" required="">
                        <label class="event" for="sleep[2]">No change</label>
                      </div>
                    </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                    <div class="quest">
                      <div class="questHead">
                        <h3 class="questTitle">Sexual Desire <img src="../../assets/images/info.png" class="heading-info sexual-desire"  /></h3>
                      </div>
                      <div class="ans1">
                        <input type="radio" id="Sexual_Desire[0]" name="input[generals][Sexual_Desire]" value="good" required="">
                        <label class="event" for="Sexual_Desire[0]">Better</label>
                      </div>
                      <div class="ans2">
                        <input type="radio" id="Sexual_Desire[1]" name="input[generals][Sexual_Desire]" value="bad" required="">
                        <label class="event" for="Sexual_Desire[1]">Worse</label>
                      </div>
                      <div class="ans3">
                        <input type="radio" id="Sexual_Desire[2]" name="input[generals][Sexual_Desire]" value="nochange" required="">
                        <label class="event" for="Sexual_Desire[2]">No change</label>
                      </div>
                    </div>
                    </div>
                  </div>
                </div>
                <div class="row particular">
                  <div class="col-md-12 text-center" style="" >
                   <h1 style="    font-weight: 600;letter-spacing: 1px;">Symptoms of <img src="../../assets/images/info.png" class="heading-info particulars" /></h1>
                   <h1 style="    font-weight: 600;letter-spacing: 1px;"><?php echo $complaint ;?></h1>
                  </div>
                  <div class="col-md-12" style="padding-left: 0px;" >
                    <div class="col-md-6 col-sm-6">
                    <div class="quest">
                      <div class="questHead">
                        <h3 class="questTitle">Intensity <img src="../../assets/images/info.png" class="heading-info intensity" /></h3>
                      </div>
                      <div class="ansdiv">
                        <div class="ans11">
                          <input type="radio" id="Intensity[0]" name="input[particular][Intensity]" value="bad" required="">
                          <label class="event1" for="Intensity[0]">Increased</label>
                        </div>
                        <div class="ans12" style="">
                          <input type="radio" id="Intensity[1]" name="input[particular][Intensity]" value="good" required="">
                          <label class="event1" for="Intensity[1]">Decreased</label>
                        </div>
                        <div class="ans3">
                          <input type="radio" id="Intensity[2]" name="input[particular][Intensity]" value="nochange" required="">
                          <label class="event1" for="Intensity[2]">No change</label>
                        </div>
                      </div>
                    </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                    <div class="quest">
                      <div class="questHead">
                        <h3 class="questTitle">Duration <img src="../../assets/images/info.png" class="heading-info duration" /></h3>
                      </div>
                      <div class="ans11">
                        <input type="radio" id="Duration[0]" name="input[particular][Duration]" value="bad" required="">
                        <label class="event1" for="Duration[0]">Increased</label>
                      </div>
                      <div class="ans12">
                        <input type="radio" id="Duration[1]" name="input[particular][Duration]" value="good" required="">
                        <label class="event1" for="Duration[1]">Decreased</label>
                      </div>
                      <div class="ans3">
                        <input type="radio" id="Duration[2]" name="input[particular][Duration]" value="nochange" required="">
                        <label class="event1" for="Duration[2]">No change</label>
                      </div>
                    </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                    <div class="quest">
                      <div class="questHead">
                        <h3 class="questTitle">Medicine Dosage <img src="../../assets/images/info.png" class="heading-info medicine"  /></h3>
                      </div>
                      <div class="ans11">
                        <input type="radio" id="Medicine_Dosage[0]" name="input[particular][Medicine_Dosage]" value="bad" required="">
                        <label class="event1" for="Medicine_Dosage[0]">Increased</label>
                      </div>
                      <div class="ans12">
                        <input type="radio" id="Medicine_Dosage[1]" name="input[particular][Medicine_Dosage]" value="good" required="">
                        <label class="event1" for="Medicine_Dosage[1]">Decreased</label>
                      </div>
                      <div class="ans3">
                        <input type="radio" id="Medicine_Dosage[2]" name="input[particular][Medicine_Dosage]" value="nochange" required="">
                        <label class="event1" for="Medicine_Dosage[2]">No change</label>
                      </div>
                    </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                    <div class="quest">
                      <div class="questHead">
                        <h3 class="questTitle">New Medicines <img src="../../assets/images/info.png" class="heading-info newMedicine" /></h3>
                      </div>
                      <div class="ans11">
                        <input type="radio" id="New_Medicines[0]" name="input[particular][New_Medicines]" value="bad" required="">
                        <label class="event1" for="New_Medicines[0]">Increased</label>
                      </div>
                      <div class="ans12">
                        <input type="radio" id="New_Medicines[1]" name="input[particular][New_Medicines]" value="good" required="">
                        <label class="event1" for="New_Medicines[1]">Decreased</label>
                      </div>
                      <div class="ans3">
                        <input type="radio" id="New_Medicines[2]" name="input[particular][New_Medicines]" value="nochange" required="">
                        <label class="event1" for="New_Medicines[2]">No change</label>
                      </div>
                    </div>
                    </div>
                  </div>
                </div>
                <div class="row complaint1">
                  <div class="col-md-12 complaintsDetails" style="" >
                    <div class="col-md-3"></div>
                    <div class="col-md-6 col-sm-6" id="dropdown1 " >
                      <div class="quest" style="border-bottom: none;">
                        <div class="otherComplaint text-center"><h3 style="letter-spacing: 1px;margin-bottom: 15%;word-spacing: 1px;font-size: 26px;">Do you face any additional problem?</h3></div>
                        <div class="complaintAns">
                          <div class="ans1">
                          <input type="radio" class="addProblem" id="complaint1[0]" name="problem" value="yes" required="">
                          <label class="event" for="complaint1[0]">Yes</label>
                        </div>
                        <div class="ans22">
                          <input type="radio" class="addProblem" id="complaint1[1]" name="problem" value="no" required="">
                          <label class="event2 " for="complaint1[1]">No</label>
                        </div>
                        <div class="ans3">
                          <input type="radio" class="addProblem" id="complaint1[2]" name="problem" value="dontKnow" required="">
                          <label class="event2 " for="complaint1[2]">Don't know</label>
                        </div>
                        </div>
                      </div>
                      <div class="complaintBox mainComplaint" id="mainComplaint" style="">
                        <select class="chosen-select form-control" placeholder="Select" required="" type="text" id="complaint" name="scndcomplaint[]" multiple="" style="padding: 5px;">
                          <?php
                          foreach($complaintsQuery as $complaintsId=>$complaintsDetails){
                            $complaintsName1=cleanQueryParameter($conn,utf8_encode($complaintsDetails["Common_name"]));
                            $complaintsName=cleanQueryParameter($conn,utf8_encode($complaintsDetails["Diagnostic_term"]));
                            $selected = "";
                            if($complaintsName==$caseDetails["Common_name"])
                              $selected = "selected";
                            ?>  

                              <option <?php echo $selected; ?> value="<?php echo $complaintsName_new=preg_replace('/\s+/', '_', $complaintsName);?>">
                                <?php echo ucfirst(strtolower($complaintsName));?>
                              </option>
                               <?php }
                          foreach($complaintsQuery1 as $complaintsId1=>$complaintsDetails1)
                          {
                              $complaintsName1=cleanQueryParameter($conn,utf8_encode($complaintsDetails1["Common_name"]));
                              if($complaintsName1 !='Eczema' && $complaintsName1!='Dermatitis'){ ?>
                              <option <?php echo $selected; ?> value="<?php echo $complaintsName_new=preg_replace('/\s+/', '_', $complaintsName1);?>">
                                <?php echo ucfirst(strtolower($complaintsName1));?>
                              </option>                  

                            <?php
                         } }
                          ?> 
                        </select>
                    </div>
                    <div class="" id="duration" style="margin-top: 20px"></div>
                    </div>
                    <div class="col-md-3"></div>
                  </div>
                 </div>
                <!-- <div class="row complaint">
                  <div class="col-md-12 complaintsDetails" style="display:none;" >
                    <div class="col-md-3"></div>
                    <div class="col-md-6 col-sm-6" id="dropdown1 mainComplaint" >
                    
                    </div>
                    <div class="col-md-3"></div>
                  </div>
                </div> -->
                   <!--Conclusion -->
                <div class="row">   
                  <div id="conclusion" class="text-center" style="">
                    <div class="col-md-12 good"  id="good" style="background: url(../../assets/images/good.png);">
                      <div class="text-center" >
                        <div class="row"> <h1 class="head">Conclusion</h1></div>
                      </div>
                      <div>
                        <p class="conclusionContent">Everything is going well.Your disease is being cured.</p>
                      </div>
                    </div>
                  </div>
                </div>
                  <!-- Conclusion ends -->
                
                <div class="row">
                    <div class="col-md-12" style="text-align: center;margin-top:80px;" >
                      <h4 id="errorMsg" style="color:red;"></h4>
                      <input type="button" name="" id="" class="submit-btn" value="SUBMIT">
                    </div>
                </div>
        </form>
        <form class="hidden" id="finalform" action="modernConclusion.php" method="post">
                <input type="hidden" name="start" id="start" value=""/>
                <input type="hidden" name="scndhalf" id="scndhalf" value=""/>
                <input type="hidden" name="complaint" id="complaint" value=""/>
                <a class="btn btn-common scndbtn">Next</a>
              </form> 
      </section>
    </main>
    <?php include('../modals.php'); ?> 
    <?php include('../footer.php'); ?>
<script type = "text/javascript" src= "../../assets/js/chosen.jquery.min.js"></script>
<script type="text/javascript">
  $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
  $(".default").val("Select");

    $(".general").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>How does your patient feel ‘Generally’?</span></li></ul></div>')
  });

  $(".particulars").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Please answer questions pertaining to the disease condition or particulars</span></li></ul></div>')
  });

  

  $(".energy").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>This is the first thing that the patient will experience after a good remedy before the disease actually improves. An indicator of DNA repair mechanism being kicked in.</span></li></ul></div>')
  });

  $(".mind").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>How does your patient feel at the mental level?</span></li></ul></div>')
  });

  $(".appetite").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>How is your patient&#39;s desire to eat?</span></li></ul></div>')
  });

  $(".sleep").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>What is the quality of your patient&#39;s sleep?</span></li></ul></div>')
  });

  $(".stool").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Is there any change with your patient&#39;s stool or bowel movements?</span></li></ul></div>')
  });
  $(".urine").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>How is your patient&#39;s urine quality or colour?</span></li></ul></div>')
  });

  $(".menses").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Any changes with your patient&#39;s menstrual cycle?</span></li></ul></div>')
  });

  $(".sexual-desire").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>How is your patient&#39;s desire to have sex?</span></li></ul></div>')
  });
  $(".intensity").click(function(){    
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Intensity of common symptoms of your patient&#39;s disease</span></li></ul></div>')
    $('#infoModal').modal();
  });

  $(".duration").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Duration of aggravations</span></li></ul></div>')
  });

  
  $(".medicine").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Has the quantity of medicines changed? Especially allopathic.</span></li></ul></div>')
  });

  $(".newMedicine").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Have any new medicines been introduced to have better control over the disease?</span></li></ul></div>')
  });

  
  $('#mainComplaint').hide();
  $('#conclusion').hide();
  $('#errorMsg').hide();


  $('.submit-btn').click(function () {   
      var selectedValue1=$("input[name='problem']:checked"). val();
       if(selectedValue1=='yes')
          {
             
                if($("#complaint option:selected").length == 0)
             
              {
                 $('#errorMsg').show();
                $('#errorMsg').html("please select your complaint");
                $('#complaint').focus();
                return false;
                
              }
               if ($("#complaint option:selected").length>0) 
              {
              
                var flag = 1;
                $(".timeDuration").each(function(i){
                    if(!$(this).find('input').filter(function(){ return $(this).val(); }).length)
                    {
                        flag++;
                        $(this).focus();
                        $('#errorMsg').show();
                        $('#errorMsg').html("please enter complaint duration");
                    }
                });
                    if (flag == 1)
                    {
                      $('#errorMsg').hide();
                    }
                    else
                    {
                      return false;
                    }     
              }
            }
            else
            {
                      $('#duration').hide();
               $('#errorMsg').hide();
            } 
      var $elems = $(".quest");

        (function fetch(i) {
             // no elems left
        if(i >= $elems.length) 
        {

              var datastring = $("#secondform").serialize();
              localStorage.setItem('secondform', datastring);

              var startform = localStorage.getItem('startform');
              $("#start").val(startform);

              var secondform = localStorage.getItem('secondform');
              
              $("#scndhalf").val(secondform);

              $("#finalform").submit();

              return false;  


    }

            var $elem = $elems.eq(i);

            var sel = $($elem).find("input[type='radio']:checked").val();

            if (sel == null) {

                    $('#errorMsg').show();
                    $('#errorMsg').html("Please answer all questions");
                  
            }
            else
            {
             
                fetch(i + 1); //next one
              //apply more code here if form validation successfull
            }
        })(0);  // start with first elem
});
  $(".addProblem").change(function () {
        var selectedValue=$("input[name='problem']:checked"). val();
          
          if(selectedValue=='yes')
          {
           $('#mainComplaint').show();
           $('#duration').show();
    
         }
         else
         {
           $('#mainComplaint').hide();
             $('#duration').hide();
              $('#errorMsg').hide();
         }
        //      $('#defShow').html(selectedValue);
  });

  $("#complaint").change(function(){
      $('#errorMsg').hide();
      setDurationBoxes();
  });

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

  function setDurationBoxes()
  {
      var val = $("#complaint").val();
       if(val!== null){
        $("#duration").show();
        var vals = val.toString();

      var values = vals.split(",");
   
      var complaintName = "";
      var durationHTML = "";
      var validation

      for(var i in values)
      {
      
        complaintName = $("option[value="+values[i]+"]").text();//console.log(complaintName);
        if(complaintName==""){
            complaintName=titleCase(stripslashes(values[i].replace(/_/g, ' ')));
        }
        durationHTML += '<div class="col-md-12 timeDuration" style="padding-right: 0px;padding-left:0px;margin-bottom:30px" id="durationsBox_'+i+'"><div style="font-size:20px;padding-bottom:10px;">'+complaintName+'</div><div class="col-md-3" style="padding-left:0px"><input class="form-control durationInput" type="number" placeholder="Year" name="probDuration['+values[i]+'][years]" id="year_'+i+'" min="1" value="" required/><label for="probDurationYears"></label></div><div class="col-md-3 month" style=""><input class="form-control durationInput" placeholder="Month" type = "number" name="probDuration['+values[i]+'][months]"  id="month_'+i+'" min="1" max="12" value="" required /><label for="probDurationMonths"></label></div><div class="col-md-3"><input class="form-control durationInput" type="number" placeholder="Day" name="probDuration['+values[i]+'][days]"  id="day_'+i+'" value="" min="1" max="31"  required/><label for="probDurationDays"></label></div></div>';
      }

      $("#duration").html(durationHTML);
      }else{
            $("#duration").hide();
          }
  }


 /* $('.submit-btn').click(function() {
        window.location.href = 'firstHalf.php';
  });*/
</script>
</body>

</html>
