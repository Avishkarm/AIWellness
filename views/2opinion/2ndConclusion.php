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
 $halfDuration=$_GET['halfDuration'];
 $fullDuration=$_GET['fullDuration'];
/*echo $fullDuration=$year.".".$month;
echo "<br>".$year."<br>".$month."<br>".$day."<br>";*/

$quest_id=$_POST['questions'];
$main_complaint_name=$_POST['complaint'];
$getConclusion=get2ndOpinionQuestionsConclusion($conn,$quest_id);
//printArr($getConclusion);
$getMedicine=$getConclusion['errMsg'][0]['get_medicine_status'];
$conclusion=$getConclusion['errMsg'][0]['conclusion'];
$fh_g=$_POST['fg'];
$fh_p=$_POST['fp'];
$fh_e=$_POST['fe'];
$sh_g=$_POST['sg'];
$sh_p=$_POST['sp'];
$sh_e=$_POST['se'];
$fs=$_POST['System1'];
$ss=$_POST['System2'];
$start=20;

if($getMedicine=='Yes'){
  $url="background: url(../../assets/images/bad.png);";
}else{
  $url="background: url(../../assets/images/good.png);";
}

//General
if($fh_g=='Good')
{
  $fg='Good';
  $fg_c=$start+10;
}
else if($fh_g=='Bad')
{
  $fg='Bad';
  $fg_c=$start-10;
}
else
{
  $fg_c=$start;
}

if($sh_g=='Good')
{
  $sg='Good';
  $sg_c=$fg_c+10;
}
else if($sh_g=='Bad')
{
  $sg='Bad';
  $sg_c=$fg_c-10;
}
else
{
  $sg_c=$fg_c;
}

//Particular
if($fh_p=='Good')
{
  $fp='Good';
  $fp_c=$start+10;
}
else if($fh_p=='Bad')
{
  $fp='Bad';
  $fp_c=$start-10;
}
else
{
  $fp_c=$start;
}

if($sh_p=='Good')
{
  $sp='Good';
  $sp_c=$fp_c+10;
}
else if($sh_g=='Bad')
{
  $sp='Bad';
  $sp_c=$fp_c-10;
}
else
{
  $sp_c=$fp_c;
}

//Elimination
if($fh_e=='Good')
{
  $fe='Good';
  $fe_c=$start+10;
}
else if($fh_e=='Bad')
{
  $fe='Bad';
  $fe_c=$start-10;
}
else
{
  $fe_c=$start;
}

if($sh_e=='Good')
{
  $se='Good';
  $se_c=$fe_c+10;
}
else if($sh_e=='Bad')
{
  $se='Bad';
  $se_c=$fe_c-10;
}
else
{
  $se_c=$fe_c;
}
if($fs=="Good")
{
  $fh_s=$start+10;
}else if($fs=="Bad"){
  $fh_s=$start-10;
}else{
  $fh_s=$start;
}
if($ss=="Good")
{
  $sh_s=$start+10;
}else if($ss=="Bad"){
  $sh_s=$start-10;
}else{
  $sh_s=$fh_s;
}


/*printArr( "fg-".$fg." fp-".$fp." fe-".$fe." sg-".$sg." sp-".$sp." se-".$se);
printArr( "fg-".$fg_c." fp-".$fp_c." fe-".$fe_c." sg-".$sg_c." sp-".$sp_c." se-".$se_c);*/

?>
<!DOCTYPE html>
  <html lang="en">
  <head>
  <?php include_once("../metaInclude.php"); ?>
  <style type="text/css">
     
       .submit-btn{
          background-color: #0dae04;
          border-radius: 7px;
          color: #fff;
          text-align: center;
          padding: 15px 10%;
          outline: none;
          border: none;
          font-size: 23px;
          margin-top: 10px;
          margin-bottom: 20px;
          width:65%;
      }
      @media(max-width: 1000px){
         .submit-btn{
          font-size: 20px;
          width: 100%;
         }
      }

  @media(max-width: 768px){
         .submit-btn{
          margin:5px 0 !important; 
         }
      }




       @media(max-width: 550px){
         .submit-btn{
          font-size: 15px;
          width: 100%;
         }
      }

       @media(max-width: 360px){
         .submit-btn{
          font-size: 12px;
          width: 100%;
         }
      }


      .panel{
        border:none;
        box-shadow:none;
      }
      .leftLabel{
    /*    -webkit-transform-origin: 0 50%;
         -moz-transform-origin: 0 50%;
         -webkit-transform: rotate(-90deg) translate(-50%, 50%);
        -moz-transform: rotate(-90deg) translate(-50%, 50%);
        margin-left: 25%;
        padding-right: 30%;
        font-size: 20px;
        letter-spacing: 1px;*/


            -webkit-transform-origin: 0 50%;
    -moz-transform-origin: 0 50%;
    -webkit-transform: rotate(-90deg) translate(-50%, 50%);
    -moz-transform: rotate(-90deg) translate(-50%, 50%);
    /* margin-left: 25%; */
    /*margin-right: 10%;*/
    padding-right: 55%;
    font-size: 20px;
    letter-spacing: 1px;
    /* right: 0; */
    /* bottom: 0; */
    margin-left: -15px;
      }
      .x-axis{
        font-size: 20px;
        letter-spacing: 1px;
        margin-top: 3%;
      }
      .panel-heading{
        letter-spacing: 1px;
        word-spacing: 1px;
        margin-top: 10%;
        font-size: 25px;
        min-height: 100px;
        max-height: 100px;
      }
      .text-bottom{
        font-size: 25px;
        letter-spacing: 1px;
        word-spacing: 1px;
      }
        .submit-btn .getMedicine{
          float: right;
          /*margin-left: 20%*/
      }
      .submit-btn .consultDoctor{
        float: left;
       /*margin-right: 20%*/
      }
       .conclusionContent
      {
        font-size:30px;
        font-weight: 500 !important;
        color:#fff;
        padding: 20px;
        letter-spacing: 1px;
      }
      #good {
          background-repeat: no-repeat!important;
          margin-top: 80px;
          padding: 60px 40px 60px 40px;         
      }
      .head
      {
        font-size:50px ;
        font-weight: 600 !important;
        color:#fff;
        letter-spacing: 1px;
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
        @media(max-width: 435px){
         .head{
          font-size: 30px;
         }
         .conclusionContent{
          font-size:16px;
         }
         .panel-heading{
          font-size: 24px;
         }
         .x-axis{
          font-size: 16px
         }
         .leftLabel{
          font-size: 16px;
         }
         .text-bottom{
          font-size: 18px;
         }
      }

.chosen-container-multi .chosen-choices li.search-choice .search-choice-close{
        background: url('../../assets/images/error.png')  no-repeat !important;
        background-size: contain !important;
  }
  </style>

 <main class="container" style="min-height: 100%;">
     <?php  include_once("../header.php"); ?>
<section>
<div class="main-container">
   <!--   <div class="row">
      <div class="col-md-12 banner" style="margin: 0px 0px 30px 0px;" >
       <img src="../../assets/images/2opinionbanner.png" class="img-responsive">
       <h1><span style="color:#ffb600;">Let's see how your treatment is on track or off the rails! </span><span style="color:#ffffff;">Introducing a foolproof application built by the world famous Dr. Khedekar</span></h1>
      </div>
    </div> -->
     <!--Conclusion -->
      <div class="row">   
        <div id="conclusion" class="col-md-12 text-center" style="">
          <div class="col-md-12 good"  id="good" style="<?php echo $url;?>;background-size: cover;">
            <div class="text-center" >
              <div class="row"> <h1 class="head">Conclusion</h1></div>
            </div>
            <div>
              <p class="conclusionContent"><?php echo $conclusion; ?></p>
            </div>
          </div>
        </div>
      </div>
    <!-- Conclusion ends -->
     <?php 
      if($getMedicine=='Yes'){
    ?>
     <!--  <div class="row" style="margin:10% 0 ;">
         
          <div class="col-md-6 col-sm-6 col-xs-12" >
              <input type="button" name="" id="" class="submit-btn getMedicine pull-right" value="GET MEDICINE" style="background-color: #ffb431;margin-right: 10%;">
          </div>


          <div class="col-md-6 col-sm-6 col-xs-12" >
              <input  type="button" name="" id="" class="submit-btn consultDoctor " value="CONSULT DOCTOR" style="margin-left: 10%;" onclick="location.href='../contactDoctors.php';" >
          </div>
            
         
      </div> -->
        <?php } ?> 

        <div class="text-center" id="conclusionChart" style="min-height: 100%;margin-top:30px;">
        <div class="row">
            <div class="col-md-6 col-xs-12 col-sm-12"   >
              <div class="panel ">
              <h1 class="panel-heading" >General</h1>
              
              <div class="panel-body" style="">
                  <div  >
                  <p class="leftLabel">Improvement (%)</p>
                      <canvas id="myChart1" style="width:80%;margin-left: 5%;" ></canvas>
                  </div>
                  <div id="js-legend1" style="" class="line-legend ">
                  </div>
                  <p class="x-axis text-center">Time</p>                 
              </div>
            </div>
             <h3 class="text-bottom">General condition</h3>
            </div>


            <div class="col-md-6 col-xs-12 col-sm-12"   >
            <div class="panel " >
              <h1 class="panel-heading" >Symptoms of <br><?php echo " ".$main_complaint_name;?></h1>
             
              <div class="panel-body" style="">
                  <div  >
                   <p class="leftLabel">Improvement (%)</p>
                      <canvas id="myChart2" style="width:80%;margin-left: 5%;" ></canvas>
                  </div>
                  <div id="js-legend1" style="" class="line-legend ">
                  </div>
                  <p class="x-axis text-center">Time</p>
              </div>
            </div>
             <h3 class="text-bottom">Disease condition</h3>
            </div>

            </div>

            

            <div class="row">
            <div class="col-md-6 col-xs-12 col-sm-12"  >
              <div class="panel">
              <h1 class="panel-heading" >Elimination</h1>
             
              <div class="panel-body" style="">
              
                  <div >
                   <p class="leftLabel">Improvement (%)</p>
                      <canvas id="myChart3" style="width:80%;margin-left: 5%;" ></canvas>
                  </div>
                  <div id="js-legend1" style="" class="line-legend ">
                  </div>
                  <p class="x-axis text-center">Time</p>                 
              </div>
            </div>
             <h3 class="text-bottom">Toxic elimination</h3>
            </div>

<!-- 4th graph -->
            <div class="col-md-6 col-xs-12 col-sm-12"  >
              <div class="panel">
              <h1 class="panel-heading" >System</h1>
             
              <div class="panel-body" style="">
              
                  <div >
                   <p class="leftLabel">Improvement (%)</p>
                      <canvas id="myChart4" style="width:80%;margin-left: 5%;" ></canvas>
                  </div>
                  <div id="js-legend1" style="" class="line-legend ">
                  </div>
                  <p class="x-axis text-center">Time</p>                 
              </div>
            </div>
             <h3 class="text-bottom">System progress</h3>
            </div>

            </div>

        </div>
  </div>
  </section>
  </main> 
  <?php include('../modals.php'); ?>
   <?php include('../footer.php'); ?>
<script src="../../assets/js/Chart.js"></script> 
<script type="text/javascript">

$(".getMedicine").click(function(){
  location.href='../../views/get_medicine.php';
});
$(".consultDoctor").click(function(){
  // location.href="http://imperialclinics.com/";
});

var start=<?php echo $start; ?>;
var fg_c=<?php echo $fg_c; ?>;
var fp_c=<?php echo $fp_c; ?>;
var fe_c=<?php echo $fe_c; ?>;
var sg_c=<?php echo $sg_c; ?>;
var sp_c=<?php echo $sp_c; ?>;
var se_c=<?php echo $se_c; ?>;
var fh_s=<?php echo $fh_s; ?>;
var sh_s=<?php echo $sh_s; ?>;
var fstdur='<?php echo $halfDuration; ?>';
var fulldur='<?php echo $fullDuration; ?>';
/*if(fulldur>1){
  fulldur=fulldur+" yrs"
}
else{
  fulldur=fulldur+" yr"
}*/
var flag = localStorage.getItem('start_flag');
//alert(flag);
//localStorage.clear();

  var lineChartData1 = {
      labels: [0,fstdur,fulldur],
      datasets: [{
        //label:'Second Half',
          fillColor: 'transparent',
          strokeColor: '#337ab7',
          pointColor: 'white',
          data: [start,fg_c,sg_c]

      }]

  }

  var lineOption1={
    pointDotRadius: 5,
      bezierCurve: false,
      scaleShowVerticalLines: false,
      scaleGridLineColor: "transparent",
      responsive:true,
      scaleLineColor: "#48E5C2",
      scaleLineWidth: 5,
      scaleFontSize : 12,
  }

  var lineChartData2 = {
      labels: [0,fstdur,fulldur],
      datasets: [{
        //label:'Second Half',
          fillColor: 'transparent',
          strokeColor: '#337ab7',
          pointColor: 'white',
          data: [start,fp_c,sp_c]
      }]

  }

  var lineOption2={
    pointDotRadius: 5,
      bezierCurve: false,
      scaleShowVerticalLines: false,
      scaleGridLineColor: "transparent",
      responsive:true,
      scaleLineColor: "#48E5C2",
      scaleLineWidth: 5,
      scaleFontSize : 12,
  }

  var lineChartData3= {
      labels: [0,fstdur,fulldur],
      datasets: [{
        //label:'Second Half',
          fillColor: 'transparent',
          strokeColor: '#337ab7',
          pointColor: 'white',
          data: [start,fe_c,se_c]
      }]

  }

  var lineOption3={
    pointDotRadius: 5,
      bezierCurve: false,
      scaleShowVerticalLines: false,
      scaleGridLineColor: "transparent",
      responsive:true,
      scaleLineColor: "#48E5C2",
      scaleLineWidth: 5,
      scaleFontSize : 12,
  }

 var lineChartData4= {
      labels: [0,fstdur,fulldur],
      datasets: [{
        //label:'Second Half',
          fillColor: 'transparent',
          strokeColor: '#337ab7',
          pointColor: 'white',
          data: [start,fh_s,sh_s]
      }]

  }

  var lineOption4={
    pointDotRadius: 5,
      bezierCurve: false,
      scaleShowVerticalLines: false,
      scaleGridLineColor: "transparent",
      responsive:true,
      scaleLineColor: "#48E5C2",
      scaleLineWidth: 5,
      scaleFontSize : 12,
  }

  var c1=$('#myChart1'),
  c2=$('#myChart2'),
  c3=$('#myChart3'),
  c4=$('#myChart4');

    Chart.defaults.global.responsive = true;
    var ct1=c1.get(0).getContext('2d');
    var ctx1 = document.getElementById("myChart1").getContext("2d");
    var ct2=c2.get(0).getContext('2d');
    var ctx2 = document.getElementById("myChart2").getContext("2d");
    var ct3=c3.get(0).getContext('2d');
    var ctx3 = document.getElementById("myChart3").getContext("2d");
    var ct4=c4.get(0).getContext('2d');
    var ctx4 = document.getElementById("myChart4").getContext("2d");
    /*************************************************************************/
    var myLineChart1 = new Chart(ct1).Line(lineChartData1,lineOption1);
    var myLineChart2 = new Chart(ct2).Line(lineChartData2,lineOption2);
    var myLineChart3 = new Chart(ct3).Line(lineChartData3,lineOption3);
    var myLineChart4 = new Chart(ct4).Line(lineChartData4,lineOption4);
   


    // document.getElementById('js-legend1').innerHTML =myLineChart.generateLegend();

</script>
    </body>
</html>

<!-- This is the div where the graph will be displayed -->
