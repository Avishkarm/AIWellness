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
  $caseSheet = getsectionrubrics(Physical,$usergroup,$gender,$age, $conn);
//printArr($caseSheet2);
if(noError($caseSheet)){
  $caseSheet = $caseSheet["errMsg"];
} else {
  printArr("Error fetching case sheet");
  exit;
}
//printArr($caseSheet);
$getAllCaseSheetDetails= getAllCaseSheetDetails($conn,$_GET['case_id']);
if(noError($getAllCaseSheetDetails)){
  $getAllCaseSheetDetails=$getAllCaseSheetDetails['errMsg'];
} else {
  printArr("Error Fetching case details".$getAllCaseSheetDetails['errMsg']);
}
$case_id=$_GET['case_id'];
$updateStepNo=updateStepNo($case_id,4,$conn);
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
      .allquestions .quest:last-child{
        border-bottom: none;
     
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

        .ans12 
        {
          display: inline-block;
          padding-left: 0;
          width: 36%;
        }
        .ans23{
          display: inline-block;
          padding-left: 0;
          width: 35%;
        }
        .ans34{
          display: inline-block;
          padding-left: 0;
          width: 25%;
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
      

    </style>
    <main class="container" style="min-height: 100%;">
    <link rel="stylesheet" type="text/css" href="../../assets/css/chosen.min.css">
      <?php  include_once("../header.php"); ?> 
      <section>
      <div class="main-container">
          <div class="row">
            <div class="col-md-8 col-md-offset-2 " style="margin-top: 10%;text-align:center;" >
             <img src="../../assets/images/step4.png"  style="width:100%;" >
              <div class="steplinks step4">

                <a href="step1.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" >Step 1</a>
                <a href="step2.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" >Step 2</a>
                <a href="step3.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>">Step 3</a>
                <a href="#" class="active">Step 4</a>
                <a href="step5.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" >Step 5</a>
                <a href="step6.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>" >Step 6</a>
             </div>
            
            </div>
          </div>


         <form action="javascript:;" name="step4Form" id="step4Form" method="post" enctype="multipart/form-data" >
          <div class="row stepFrmDiv">
            
            <div class="col-md-12 allquestions" style="padding-left: 0px;" >
            <?php foreach($caseSheet as $priQuestionName=>$priQuesDets){ 
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
                <?php     }
                        }
                      }  }?>
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
                <?php     }
                        }
                      }  }?>
                </div>
              </div>
              </div>
              <?php } } }

                } }?>



            </div>
          </div>
          <input type="hidden" name="case_id" value="<?php echo $_GET['case_id'] ;?>">
          <input type="hidden" name="step_no" class="step_no" value="4">
          
          

            <div class="row">
                <div class="col-md-12" style="text-align: center;margin-top:80px;" >
                  <h4 id="errorMsg" style="color:red;"></h4>
                  <button style="outline:0;border: none; background-color: transparent;" id="step4submit" type="submit" class="next-btn"><img src="../../assets/images/nextbtn.png"/></button>
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
                  type:'ans_step4'},
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

  $('form#step4Form').submit(function(event) {
    var case_id=<?php echo $_GET['case_id']; ?>;
    //alert($('.step_no').val());
    var step_no=$('.step_no').val();
    $.ajax({type: "POST",
            url:"../../controllers/startCaseController.php",
            data:{case_id:case_id,
                  step_no:step_no,
                  type:'step4'},
            dataType:'json',
             beforeSend: function () {
               $('.load').show();
              $('.next-btn').hide();
            }
      })
      .done(function(data) {
        $('.load').hide();
        $('.next-btn').show();
        console.log(data);
        window.location.href="step5.php?patient_id=<?php echo $_GET['patient_id'];?>&case_id=<?php echo $_GET['case_id']; ?>";
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
$( "#step4Form" ).scrollTop( 0 );
  });
</script>
</body>

</html>
