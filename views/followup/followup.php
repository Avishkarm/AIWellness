  <?php
  session_start();

  $title="Follow-up";
  $activeHeader = "doctorsArea";

  require_once("../../utilities/config.php");
  require_once("../../utilities/dbutils.php");
  require_once("../../models/commonModel.php");
   require_once("../../models/userModel.php");
  $complaint=$_GET['complaint']; 
  $year=$_GET['year'];
$month=$_GET['month'];
  $day=$_GET['day']; 

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

  $_SESSION['flag']=false;
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
  $case_id=$_GET['case_id'];
  $complaint=$_GET['complaint'];
$patient_id=$_GET['patient_id']
  

 /*$case_id=35;
  $complaint='Eczema';
$patient_id=71;*/
  
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
      .fstbtn:hover ,.retakeCase:hover, .endFollowup:hover{
        color:#fff !important;
      }
      .fstbtn , .retakeCase,.endFollowup{
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
      .particular,.eliminations{
        margin-top: 10%;
      }
      .complaint{
        margin-top: 10%;
      }
      .questTitle{
        letter-spacing: 1px;
        word-spacing: 1px;
        line-height: 1.5em;
        font-size: 28px;
        color:#333;
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
        font-size: 22px;
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
            margin-left: 45px
        }
        .ans21 {
            display: inline-block;
            padding-left: 0;
            padding-right: 0;
            margin-left: 5px
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
            margin-left: 65px;
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
          padding: 60px 60px 60px 0px;         
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
      .scndhalf h4 {
        position: absolute;
        top: 15%;
        width: 55%;
        left: 12%;
        letter-spacing: 1px;
        font-size: 24px;
        line-height: 1.5em;
        color: #555;
        word-spacing: 1px;
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
        .scndhalf h4 {
          position: absolute;
          top: 15px;
          width: 55%;
          left: 12%;
          letter-spacing: 1px;
          font-size: 22px;
          line-height: 1.5em;
          color: #555;
          word-spacing: 1px;
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
        .scndhalf h4 {
          position: absolute;
          top: 15px;
          width: 55%;
          left: 12%;
          letter-spacing: 1px;
          font-size: 16px;
          line-height: 1.5em;
          color: #555;
          word-spacing: 1px;
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
        .scndhalf h4 {
          position: absolute;
          top: 5px;
          width: 55%;
          left: 12%;
          letter-spacing: 1px;
          font-size: 8px;
          line-height: 1.5em;
          color: #555;
          word-spacing: 1px;
      }
      } 
      @media(max-width: 400px){
        .scndhalf h4 {
          position: absolute;
          top: 5px;
          width: 55%;
          left: 12%;
          letter-spacing: 1px;
          font-size: 7px;
          line-height: 1.5em;
          color: #555;
          word-spacing: 1px;
      }
      } 
      @media(max-width: 320px){
        .scndhalf h4 {
          position: absolute;
          top: 0;
          width: 55%;
          left: 12%;
          letter-spacing: 1px;
          font-size: 6px;
          line-height: 1.5em;
          color: #555;
          word-spacing: 1px;
      }
      } 

      .chosen-container-multi .chosen-choices li.search-choice .search-choice-close{
        background: url('../../assets/images/error.png')  no-repeat !important;
        background-size: contain !important;
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

      .heading-info {
          margin-left: 5px;
          vertical-align: super;
          cursor: pointer;
          height: 20px;
      }


    </style>
    <main class="container" style="min-height: 100%;">
    <link rel="stylesheet" type="text/css" href="../../assets/css/chosen.min.css">
      <?php  include_once("../header.php"); ?> 
      <section>
      <div class="main-container">
        <form name="followupform" method="post" id="followupform" action="">
          <!-- <div class="row">
            <div class="col-md-12 banner" style="margin: 0px 0px 30px 0px;" >
             <img src="../../assets/images/2opinionbanner.png" class="img-responsive">
             <h1><span style="color:#ffb600;">Let's see whether your treatment is on track or off the rails! </span><span style="color:#ffffff;">Introducing a foolproof application built by the world famous Dr. Khedekar</span></h1>
            </div>
          </div> -->
          <!-- <div class="row">
            <div class="col-md-12 scndhalf" style="margin-top: 10%;" >
             <img src="../../assets/images/2ndHalf.png" style="width:100%;" class="img-responsive">
             <h4>Thank you for your patience. We are almost there. Now please answer the list of questions, connected with Second Half (second <?php if(!empty($year) && $year!=0 ){ echo $year." year ";} if(!empty($month) && $month!=0 ){ echo $month." month ";} if(!empty($day) && $day!=0 ){ echo $day." day";}?>) of the disease,  so we are able to give an opinion as helpful as possible</h4>
            </div>
          </div> -->
          <div class="row noleft-right" >
                <div class="col-md-5 col-sm-5 col-xs-12 managepatient" >
                  <h2 style="margin-left: -15px;font-weight: 600;">Follow up <img src="../../assets/images/info.png" class="heading-info" data-toggle="modal" data-target="#infoModal" /></h2>
                </div>
          </div>

          <h3 style="margin-top: 1px;"> Please, answer the questions below honestly, and let us calculate the outcome of the remedy prescribed to you.<br>
          We will be scientific, neutral and unbiased.</h3>
          <div class="row generals">
            <div class="col-md-12 text-center" style="" >
             <h1 style="    font-weight: 600;letter-spacing: 1px;">Generals <img src="../../assets/images/info.png" class="heading-info general"  /></h1>
            </div>
            <div class="col-md-12" style="padding-left: 0px;" >
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Energy/Feeling of well being <img src="../../assets/images/info.png" class="heading-info energy" /></h3>
                </div>
                <div class="ansdiv">
                  <div class="ans1">
                    <input type="radio" id="energy[0]" name="input1[generals][Energy_Feeling_of_well_being]" value="good" required="">
                    <label class="event" for="energy[0]">Better</label>
                  </div>
                  <div class="ans2" style="">
                    <input type="radio" id="energy[1]" name="input1[generals][Energy_Feeling_of_well_being]" value="bad" required="">
                    <label class="event" for="energy[1]">Worse</label>
                  </div>
                  <div class="ans3">
                    <input type="radio" id="energy[2]" name="input1[generals][Energy_Feeling_of_well_being]" value="nochange" required="">
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
                  <input type="radio" id="mind[0]" name="input1[generals][Mind]" value="good" required="">
                  <label class="event" for="mind[0]">Better</label>
                </div>
                <div class="ans2">
                  <input type="radio" id="mind[1]" name="input1[generals][Mind]" value="bad" required="">
                  <label class="event" for="mind[1]">Worse</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="mind[2]" name="input1[generals][Mind]" value="nochange" required="">
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
                  <input type="radio" id="Appetite[0]" name="input1[generals][Appetite]" value="good" required="">
                  <label class="event" for="Appetite[0]">Better</label>
                </div>
                <div class="ans2">
                  <input type="radio" id="Appetite[1]" name="input1[generals][Appetite]" value="bad" required="">
                  <label class="event" for="Appetite[1]">Worse</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Appetite[2]" name="input1[generals][Appetite]" value="nochange" required="">
                  <label class="event" for="Appetite[2]">No change</label>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Sleep <img src="../../assets/images/info.png" class="heading-info sleep" /></h3>
                </div>
                <div class="ans1">
                  <input type="radio" id="sleep[0]" name="input1[generals][Sleep]" value="good" required="">
                  <label class="event" for="sleep[0]">Better</label>
                </div>
                <div class="ans2">
                  <input type="radio" id="sleep[1]" name="input1[generals][Sleep]" value="bad" required="">
                  <label class="event" for="sleep[1]">Worse</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="sleep[2]" name="input1[generals][Sleep]" value="nochange" required="">
                  <label class="event" for="sleep[2]">No change</label>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Stool <img src="../../assets/images/info.png" class="heading-info stool"  /></h3>
                </div>
                <div class="ans1">
                  <input type="radio" id="Stool[0]" name="input1[generals][Stool]" value="good" required="">
                  <label class="event" for="Stool[0]">Better</label>
                </div>
                <div class="ans2">
                  <input type="radio" id="Stool[1]" name="input1[generals][Stool]" value="bad" required="">
                  <label class="event" for="Stool[1]">Worse</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Stool[2]" name="input1[generals][Stool]" value="nochange" required="">
                  <label class="event" for="Stool[2]">No change</label>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Urine <img src="../../assets/images/info.png" class="heading-info urine" /></h3>
                </div>
                <div class="ans1">
                  <input type="radio" id="Urine[0]" name="input1[generals][Urine]" value="good" required="">
                  <label class="event" for="Urine[0]">Better</label>
                </div>
                <div class="ans2">
                  <input type="radio" id="Urine[1]" name="input1[generals][Urine]" value="bad" required="">
                  <label class="event" for="Urine[1]">Worse</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Urine[2]" name="input1[generals][Urine]" value="nochange" required="">
                  <label class="event" for="Urine[2]">No change</label>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Reproductive system<img src="../../assets/images/info.png" class="heading-info menses" /></h3>
                </div>
                <div class="ans1">
                  <input type="radio" id="Menses[0]" name="input1[generals][Menses]" value="good" required="">
                  <label class="event" for="Menses[0]">Better</label>
                </div>
                <div class="ans2">
                  <input type="radio" id="Menses[1]" name="input1[generals][Menses]" value="bad" required="">
                  <label class="event" for="Menses[1]">Worse</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Menses[2]" name="input1[generals][Menses]" value="nochange" required="">
                  <label class="event" for="Menses[2]">No change</label>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Sexual desire <img src="../../assets/images/info.png" class="heading-info sexual-desire"  /></h3>
                </div>
                <div class="ans1">
                  <input type="radio" id="Sexual_desire[0]" name="input1[generals][Sexual_desire]" value="good" required="">
                  <label class="event" for="Sexual_desire[0]">Better</label>
                </div>
                <div class="ans2">
                  <input type="radio" id="Sexual_desire[1]" name="input1[generals][Sexual_desire]" value="bad" required="">
                  <label class="event" for="Sexual_desire[1]">Worse</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Sexual_desire[2]" name="input1[generals][Sexual_desire]" value="nochange" required="">
                  <label class="event" for="Sexual_desire[2]">No change</label>
                </div>
              </div>
              </div>
            </div>
          </div>
          <div class="row particular">
            <div class="col-md-12 text-center" style="" >
             <h1 style="    font-weight: 600;letter-spacing: 1px;">Symptoms of <img src="../../assets/images/info.png" class="heading-info particulars" /></h1>
             <h1 style="    font-weight: 600;letter-spacing: 1px;"><?php echo $complaint; ?></h1>
            </div>
            <div class="col-md-12" style="padding-left: 0px;" >
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Intensity <img src="../../assets/images/info.png" class="heading-info intensity"  /></h3>
                </div>
                <div class="ansdiv">
                  <div class="ans11">
                    <input type="radio" id="Intensity[0]" name="input1[particular][Intensity]" value="bad" required="">
                    <label class="event1" for="Intensity[0]">Increased</label>
                  </div>
                  <div class="ans12" style="">
                    <input type="radio" id="Intensity[1]" name="input1[particular][Intensity]" value="good" required="">
                    <label class="event1" for="Intensity[1]">Decreased</label>
                  </div>
                  <div class="ans3">
                    <input type="radio" id="Intensity[2]" name="input1[particular][Intensity]" value="nochange" required="">
                    <label class="event1" for="Intensity[2]">No change</label>
                  </div>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Duration <img src="../../assets/images/info.png" class="heading-info duration"  /></h3>
                </div>
                <div class="ans11">
                  <input type="radio" id="Duration[0]" name="input1[particular][Duration]" value="bad" required="">
                  <label class="event1" for="Duration[0]">Increased</label>
                </div>
                <div class="ans12">
                  <input type="radio" id="Duration[1]" name="input1[particular][Duration]" value="good" required="">
                  <label class="event1" for="Duration[1]">Decreased</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Duration[2]" name="input1[particular][Duration]" value="nochange" required="">
                  <label class="event1" for="Duration[2]">No change</label>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Frequency <img src="../../assets/images/info.png" class="heading-info frequency"  /></h3>
                </div>
                <div class="ans11">
                  <input type="radio" id="Frequency[0]" name="input1[particular][Frequency]" value="bad" required="">
                  <label class="event1" for="Frequency[0]">Increased</label>
                </div>
                <div class="ans12">
                  <input type="radio" id="Frequency[1]" name="input1[particular][Frequency]" value="good" required="">
                  <label class="event1" for="Frequency[1]">Decreased</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Frequency[2]" name="input1[particular][Frequency]" value="nochange" required="">
                  <label class="event1" for="Frequency[2]">No change</label>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Recovery period <img src="../../assets/images/info.png" class="heading-info recovery"  /></h3>
                </div>
                <div class="ans11">
                  <input type="radio" id="Recovery_period[0]" name="input1[particular][Recovery_period]" value="bad" required="">
                  <label class="event1" for="Recovery_period[0]">Increased</label>
                </div>
                <div class="ans12">
                  <input type="radio" id="Recovery_period[1]" name="input1[particular][Recovery_period]" value="good" required="">
                  <label class="event1" for="Recovery_period[1]">Decreased</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Recovery_period[2]" name="input1[particular][Recovery_period]" value="nochange" required="">
                  <label class="event1" for="Recovery_period[2]">No change</label>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Medicine dosage <img src="../../assets/images/info.png" class="heading-info medicine"  /></h3>
                </div>
                <div class="ans11">
                  <input type="radio" id="Medicine_dosage[0]" name="input1[particular][Medicine_dosage]" value="bad" required="">
                  <label class="event1" for="Medicine_dosage[0]">Increased</label>
                </div>
                <div class="ans12">
                  <input type="radio" id="Medicine_dosage[1]" name="input1[particular][Medicine_dosage]" value="good" required="">
                  <label class="event1" for="Medicine_dosage[1]">Decreased</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Medicine_dosage[2]" name="input1[particular][Medicine_dosage]" value="nochange" required="">
                  <label class="event1" for="Medicine_dosage[2]">No change</label>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">New medicines <img src="../../assets/images/info.png" class="heading-info newMedicine"  /></h3>
                </div>
                <div class="ans11">
                  <input type="radio" id="New_medicines[0]" name="input1[particular][New_medicines]" value="bad" required="">
                  <label class="event1" for="New_medicines[0]">Increased</label>
                </div>
                <div class="ans12">
                  <input type="radio" id="New_medicines[1]" name="input1[particular][New_medicines]" value="good" required="">
                  <label class="event1" for="New_medicines[1]">Decreased</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="New_medicines[2]" name="input1[particular][New_medicines]" value="nochange" required="">
                  <label class="event1" for="New_medicines[2]">No change</label>
                </div>
              </div>
              </div>
            </div>
          </div>
          <div class="row eliminations">
            <div class="col-md-12 text-center" style="" >
             <h1 style="    font-weight: 600;letter-spacing: 1px;">Eliminations <img src="../../assets/images/info.png" class="heading-info elimination"  /></h1>
            </div>
            <div class="col-md-12" style="padding-left: 0px;" >
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Mild Diarrhoea <img src="../../assets/images/info.png" class="heading-info diarrhoea"  /></h3>
                </div>
                <div class="ansdiv">
                  <div class="ans11">
                    <input type="radio" id="Mild_Diarrhoea[0]" name="input1[eliminations][Mild_Diarrhoea]" value="good" required="">
                    <label class="event1" for="Mild_Diarrhoea[0]">Appeared</label>
                  </div>
                  <div class="ans21" style="">
                    <input type="radio" id="Mild_Diarrhoea[1]" name="input1[eliminations][Mild_Diarrhoea]" value="bad" required="">
                    <label class="event1" for="Mild_Diarrhoea[1]">Not appeared</label>
                  </div>
                  <div class="ans3">
                    <input type="radio" id="Mild_Diarrhoea[2]" name="input1[eliminations][Mild_Diarrhoea]" value="nochange" required="">
                    <label class="event1" for="Mild_Diarrhoea[2]">Don't know</label>
                  </div>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Itching or rash on the skin <img src="../../assets/images/info.png" class="heading-info itching"  /></h3>
                </div>
                <div class="ans11">
                  <input type="radio" id="Itching[0]" name="input1[eliminations][Itching]" value="good" required="">
                  <label class="event1" for="Itching[0]">Appeared</label>
                </div>
                <div class="ans21">
                  <input type="radio" id="Itching[1]" name="input1[eliminations][Itching]" value="bad" required="">
                  <label class="event1" for="Itching[1]">Not appeared</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Itching[2]" name="input1[eliminations][Itching]" value="nochange" required="">
                  <label class="event1" for="Itching[2]">Don't know</label>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Mild to moderate leakage from nose <img src="../../assets/images/info.png" class="heading-info nose"  /></h3>
                </div>
                <div class="ans11">
                  <input type="radio" id="Leakage[0]" name="input1[eliminations][Leakage]" value="good" required="">
                  <label class="event1" for="Leakage[0]">Appeared</label>
                </div>
                <div class="ans21">
                  <input type="radio" id="Leakage[1]" name="input1[eliminations][Leakage]" value="bad" required="">
                  <label class="event1" for="Leakage[1]">Not appeared</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Leakage[2]" name="input1[eliminations][Leakage]" value="nochange" required="">
                  <label class="event1" for="Leakage[2]">Don't know</label>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Mild Cough <img src="../../assets/images/info.png" class="heading-info cough"  /></h3>
                </div>
                <div class="ans11">
                  <input type="radio" id="Cough[0]" name="input1[eliminations][Cough]" value="good" required="">
                  <label class="event1" for="Cough[0]">Appeared</label>
                </div>
                <div class="ans21">
                  <input type="radio" id="Cough[1]" name="input1[eliminations][Cough]" value="bad" required="">
                  <label class="event1" for="Cough[1]">Not appeared</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Cough[2]" name="input1[eliminations][Cough]" value="nochange" required="">
                  <label class="event1" for="Cough[2]">Don't know</label>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Fever <img src="../../assets/images/info.png" class="heading-info fever"  /></h3>
                </div>
                <div class="ans11">
                  <input type="radio" id="Fever[0]" name="input1[eliminations][Fever]" value="good" required="">
                  <label class="event1" for="Fever[0]">Appeared</label>
                </div>
                <div class="ans21">
                  <input type="radio" id="Fever[1]" name="input1[eliminations][Fever]" value="bad" required="">
                  <label class="event1" for="Fever[1]">Not appeared</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Fever[2]" name="input1[eliminations][Fever]" value="nochange" required="">
                  <label class="event1" for="Fever[2]">Don't know</label>
                </div>
              </div>
              </div>
              <div class="col-md-6 col-sm-6">
              <div class="quest">
                <div class="questHead">
                  <h3 class="questTitle">Some bland discharge from eyes, ear and urethra </h3>
                </div>
                <div class="ans11">
                  <input type="radio" id="Discharge[0]" name="input1[eliminations][Discharge]" value="good" required="">
                  <label class="event1" for="Discharge[0]">Appeared</label>
                </div>
                <div class="ans21">
                  <input type="radio" id="Discharge[1]" name="input1[eliminations][Discharge]" value="bad" required="">
                  <label class="event1" for="Discharge[1]">Not Appeared</label>
                </div>
                <div class="ans3">
                  <input type="radio" id="Discharge[2]" name="input1[eliminations][Discharge]" value="nochange" required="">
                  <label class="event1" for="Discharge[2]">Don't know</label>
                </div>
              </div>
              </div>
            </div>
          </div>
          <div class="row complaint">
            <div class="col-md-12">
               <div class="otherComplaint text-center"><h3 style="letter-spacing: 1px;margin-bottom: 5%;word-spacing: 1px;font-size: 26px;">Do you face any additional problem?</h3></div>
            </div>
            <div class="col-md-12 complaintsDetails" style="" >
              <div class="col-md-3"></div>
              <div class="col-md-6 col-sm-6" id="dropdown1 mainComplaint" >
              <div class="quest" style="border-bottom: none;">
                <div class="complaintAns">
                  <div class="ans1">
                  <input type="radio" class="addProblem" id="complaint[0]" name="problem2" value="yes" required="">
                  <label class="event2" for="complaint[0]">Yes</label>
                </div>
                <div class="ans22">
                  <input type="radio" class="addProblem" id="complaint[1]" name="problem2" value="no" required="">
                  <label class="event2" for="complaint[1]">No</label>
                </div>
                <div class="ans3">
                  <input type="radio" class="addProblem" id="complaint[2]" name="problem2" value="nochange" required="">
                  <label class="event2" for="complaint[2]">Don't know</label>
                </div>
                </div>
                </div>
                <div class="complaintBox mainComplaint" id="mainComplaint" style="">
                  <select class="chosen-select form-control" placeholder="Select" required="" type="text" id="complaint" name="scndcomplaint[]" multiple="" style="padding: 5px;">
                    <?php
                    foreach($complaintsQuery as $complaintsId=>$complaintsDetails)
                    {
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
                    }}
                    ?> 
                  </select>
              </div>
               <div class="" id="duration" style="margin-top: 20px"></div>
              </div>
              <div class="col-md-3"></div>
            </div>
           </div>
            <div class="row">
                <div class="col-md-12" style="text-align: center;margin-top:80px;" >
                  <h4 id="errorMsg" style="color:red;"></h4>
                  <a id="" style="cursor: pointer;" class="fstbtn"> Save</a>                
                </div>
            </div>
             <!--  <input type="image" src="../../assets/images/nextbtn.png" id="" class="next-btn" value="" style="outline:0;"> -->
            <!--  <div class="row">

                <div class="col-md-12" style="text-align: center;margin-top:80px;" >
                  <h4 id="errorMsg" style="color:red;"></h4>
                  <div class="col-md-4 col-sm-12 col-xs-12" style="text-align: center;">
                    <a id="" class="endFollowup" style="margin: 10px;background-color: #e74f62;cursor: pointer;"> End follow up</a>
                  </div> 
                  <div class="col-md-4 col-sm-12 col-xs-12" style="text-align: center;cursor: pointer;">
                    <a id="" class="fstbtn" style="margin: 10px;cursor: pointer;"> Save</a>
                  </div>
                  <div class="col-md-4 col-sm-12 col-xs-12" style="text-align: center;">
                    <a id="" class="retakeCase" style="margin: 10px;background-color: #ffb431;cursor: pointer;"> Retake case</a>
                  </div>
                </div>
            </div> -->

                  <!-- <a id="" class="fstbtn" style="margin-right: 10px;"> Save</a><a id="" class="fstbtn"> Save</a><a id="" class="fstbtn" style="margin-left: 10px;"> Save</a> -->
                 <!--  <input type="image" src="../../assets/images/nextbtn.png" id="" class="next-btn" value="" style="outline:0;"> -->
        </form>
        <!-- <form class="hidden" id="finalform" action="conclusion.php?year=<?php echo $year;?>&month=<?php echo $month;?>&day=<?php echo $day;?>" method="post">
            <input type="hidden" name="start" id="start" value=""/>
            <input type="hidden" name="fsthalf" id="fsthalf" value=""/>
            <input type="hidden" name="scndhalf" id="scndhalf" value=""/>
            <input type="hidden" name="complaint" id="complaint" value="<?php echo $complaint; ?>"/> 
            <a class="btn btn-common scndbtn">Next</a>
        </form> -->
        <form class="hidden" id="finalform" action="conclusion.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id'];?>" method="post">
            <input type="hidden" name="followupdata" id="followupdata" value=""/>
            <input type="hidden" name="case_id" id="case_id" value="<?php echo $case_id;?>"/>
            <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $patient_id;?>"/>
            <input type="hidden" name="complaint" id="complaint" value="<?php echo $complaint;?>"/>
            <a class="btn btn-common scndbtn">Save</a>
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

  $(".elimination").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Body cures itself after it detoxifies. There are several routes through which the process happens as listed below.</span></li></ul></div>')
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
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Any changes with your patient&#39;s Reproductive system?</span></li></ul></div>')
  });

  $(".sexual-desire").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>How is your patient&#39;s desire to have sex?</span></li></ul></div>')
  });
  $(".intensity").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Intensity of common symptoms of your patient&#39;s disease</span></li></ul></div>')
  });

  $(".duration").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Duration of aggravations</span></li></ul></div>')
  });

  $(".frequency").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>How frequent does your patient have periods of aggravations per day/week/month/year.</span></li></ul></div>')
  });
  $(".recovery").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>How much time does it take for your patient to recover or get better after medicines are taken as compared to before the start of treatment? Especially allopathic.</span></li></ul></div>')
  });

  $(".medicine").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Has the quantity of medicines changed? Especially allopathic.</span></li></ul></div>')
  });

  $(".newMedicine").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Have any new medicines been introduced to have better control over the disease?</span></li></ul></div>')
  });

  $(".diarrhoea").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Most patients forget this symptom but this usually occurs as a bland diarrhea of unexplained origin. Doesn’t include infectious diarrheas.</span></li></ul></div>')
  });

  $(".itching").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Most common occurrence, presents as a mild and transient expression on skin. Most patients do not relate this to the treatment given and apply oils, creams or moisturizers to it.</span></li></ul></div>')
  });

  $(".nose").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>The second level of elimination happens through the respiratory system upper and lower. This is usually a bland discharge and patients tend to suppress it with antihistaminic drugs.</span></li></ul></div>')
  });
  $(".cough").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>The second level of elimination happens through the respiratory system upper and lower.</span></li></ul></div>')
  });

  $(".fever").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Especially of unknown origin and mild or self limiting. Doesn’t include severe or infectious fevers.</span></li></ul></div>')
  });

  $(".discharge").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span></span></li></ul></div>')
  });

  $('#mainComplaint').hide();
    var mainComplaint="";

   $(".addProblem").change(function () {
                
        var selectedValue=$("input[name='problem2']:checked"). val();
          
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

      
   $('.endFollowup').click(function () {
      var case_id=<?php echo $_GET['case_id']; ?>;
      $.ajax({type: "POST",
            url:"../../controllers/followupController.php",
            data:{case_id:case_id,
                  type:'endFollowup'},
            dataType:'json',
            /*beforeSend: function () {
              $ele.find('.stopAccess').show();
            }*/
      })
      .done(function(data) {
        console.log(data);
        window.location.href="../patient/patientCaseHistory.php?patient_id=<?php echo $_GET['patient_id'];?>";
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        //alert("error");
        console.log('error');
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       });

   });

    $('.retakeCase').click(function () {
      var case_id=<?php echo $_GET['case_id']; ?>;
      $.ajax({type: "POST",
            url:"../../controllers/followupController.php",
            data:{case_id:case_id,
                  type:'retakeCase'},
            dataType:'json',
            /*beforeSend: function () {
              $ele.find('.stopAccess').show();
            }*/
      })
      .done(function(data) {
        console.log(data);
        if(data['errCode']==-1){
          window.location.href="../startcase/step1.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id="+data['case_id'];
        }
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        //alert("error");
        console.log('error');
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       });

   });
      $('#errorMsg').hide();
     $('.fstbtn').click(function () {   
      var selectedValue1=$("input[name='problem2']:checked"). val();
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

              var datastring = $("#followupform").serialize();
              localStorage.setItem('followupform', datastring);

              var followupform = localStorage.getItem('followupform');
              var decodedUri3 = decodeURIComponent(followupform);
              var array3 = decodedUri3.replace(/\&/g, '<br/>');
    
              $("#followupdata").val(followupform);

              /*localStorage.removeItem(startform);
              localStorage.removeItem(firstform);
              localStorage.removeItem(secondform);*/
              //localStorage.clear();

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
        complaintName = $("option[value="+values[i]+"]").text();
        durationHTML += '<div class="col-md-12 timeDuration" style="padding-right: 0px;padding-left:0px;margin-bottom:30px" id="durationsBox_'+i+'"><div style="font-size:20px;padding-bottom:10px;">'+complaintName+'</div><div class="col-md-3" style="padding-left:0px"><input class="form-control durationInput" type="number" placeholder="Year" name="probDuration['+values[i]+'][years]" id="year_'+i+'" min="1" value="" required/><label for="probDurationYears"></label></div><div class="col-md-3 month" style=""><input class="form-control durationInput" placeholder="Month" type = "number" name="probDuration['+values[i]+'][months]"  id="month_'+i+'" min="1" max="12" value="" required /><label for="probDurationMonths"></label></div><div class="col-md-3"><input class="form-control durationInput" type="number" placeholder="Day" name="probDuration['+values[i]+'][days]"  id="day_'+i+'" value="" min="1" max="31"  required/><label for="probDurationDays"></label></div></div>';
      }

      $("#duration").html(durationHTML);
      }else{
            $("#duration").hide();
          }
  }

  /*$('.next-btn').click(function() {
        window.location.href = 'conclusion.php';
  });*/
</script>
</body>

</html>
