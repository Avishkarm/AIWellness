  <?php
  session_start();
   $activeHeader="2opinion";
  $activeHeader1="stress_calculater";
  $title="StressCalculator";
  $pathPrefix="../";
  require_once("../../utilities/config.php");
  require_once("../../utilities/dbutils.php");
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
  
  
 /*   $user = "";
if(isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)){
  $user = $_SESSION["user"];
  $user_type=$_SESSION["user_type"];
  $session=true;
   //$patient_id=$_GET['patient_id'];
  $patient_id=71;
} else {
  $session=false;
}  */
 //echo $patient_id;
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <?php include_once("../metaInclude.php"); ?>
    <style type="text/css">
    .text{
      word-spacing: 4px;
      margin-bottom: 30px;
    }
    .main-container .row
    {
      margin:0px 0 30px 0;
    }
    .age-btn{
      border-radius: 15px;
      text-align: center;
      padding: 15px;
      outline: none;
      border: none;
      width: 44%;
      font-size: 22px;
      margin-top: 10px;
      margin-bottom: 20px;
      word-spacing: 2px;
      color:#fff;
      background: #c7d2d8; 
      background: -webkit-linear-gradient(#e4ecf1, #c7d2d8); 
      background: -o-linear-gradient(#e4ecf1, #c7d2d8); 
      background: -moz-linear-gradient(#e4ecf1, #c7d2d8);
      background: linear-gradient(#e4ecf1, #c7d2d8); 
    }
    .age-btn-style{
      background: #f97c89;
      background: -webkit-linear-gradient(#f97c89, #f5cc84);
      background: -o-linear-gradient(#f97c89, #f5cc84);
      background: -moz-linear-gradient(#f97c89, #f5cc84); 
      background: linear-gradient(#f97c89, #f5cc84);
    }
    .bannerContent{
      color: #555;
      line-height: 1.4em;
      word-spacing: 1px;
      letter-spacing: 1px;
    }
    #minor{
      margin-right: 20px;
    }
    #adult{
      margin-left: 20px;
    }
    .minor{
      text-align: right;
    }
    .adult{
      text-align: left;
    }
    .age{
      margin-bottom:8%;
    }
    @media(max-width: 768px){
      .minor,.adult{
        text-align: center;
      }
      #minor,#adult{
      margin-right: 0px;
      margin-left: 0px;
      }
      .age-btn{
        width:80%;
      }
      .text h2
      {
        font-size: 19px
      }
    }
    @media(min-width: 768px and max-width: 1366px){
      .age-btn{
        width:65%;
      }
    }
  


    
    </style>
    <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <main class="container" style="min-height: 100%;">
    <link rel="stylesheet" type="text/css" href="../../assets/css/chosen.css">
      <?php  include_once("../header.php"); ?> 
      <section>
      <div class="main-container">
        <div class="row">
          <div class="col-md-12" >
           <img src="../../assets/images/stressbanner1.png" class="img-responsive">
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text">
            <h3 class="bannerContent" style="color: rgba(0,0,0,.87);">Use this stress test to check stress levels accurately.</h3>
           <h3>Why check stress levels?</h3>
 
           <h3>The situations and pressures that cause stress are known as stressors. We usually think of stressors as being negative, such as an exhausting work schedule or a difficult relationship. However, anything that puts high demands on you can be stressful. This includes positive events such as getting married, buying a house, going to college, or receiving a promotion.

           <br/>Of course, not all stress is caused by external factors. Stress can also be internal or self-generated, when you worry excessively about something that may or may not happen, or have irrational, pessimistic thoughts about life.</h3>
          </div>
        </div> 
        <div class="row age">
          <div class="col-md-12">
            <div class="col-md-6 col-sm-6 col-xs-12 minor">
            <input type="button" name="" id="minor" class="age-btn" value="I am under 18">
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 adult" >
            <input type="button" name="" id="adult" class="age-btn" value="I am over 18">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12" id="stress_container">
          </div>
        </div>  
             
      </div>
      </section>
    </main> 
    <?php include("../modals.php"); ?>
    <?php include('../footer.php'); ?>


    <script type = "text/javascript" src= "../../assets/js/chosen.jquery.js"></script>
    <script type="text/javascript">
    /*var session=<?php echo $session;?>;*/
    //alert(session);
     $("#minor").click(function(){
            $("#minor").addClass('age-btn-style');
            $("#adult").removeClass('age-btn-style');
            /*if(session){
              var patient_id=<?php echo $patient_id; ?>;
              //alert(patient_id);
              $("#stress_container").load("stress_test.php?type=minor&patient_id="+patient_id);
            }else{*/
              $("#stress_container").load("stress_test.php?type=minor");
           /* }*/
        });
        $("#adult").click(function(){
            $("#adult").addClass('age-btn-style');
            $("#minor").removeClass('age-btn-style');
            /*if(session){
              var patient_id=<?php echo $patient_id; ?>;
              $("#stress_container").load("stress_test.php?type=adult&patient_id="+patient_id);
            }else{*/
              $("#stress_container").load("stress_test.php?type=adult");
            /*}*/
        });


    </script>
</body>

</html>
