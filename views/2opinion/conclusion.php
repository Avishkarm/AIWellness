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

//ACCEPT
  $startForm = $_POST["start"];
parse_str($startForm); //complaints in array $complaint, probDuration in array $probDuration, other complaint in variable $ocomplaint1
//printArr($startForm);
$probDurationMain=$probDuration;
$firstHalf = $_POST["fsthalf"];
parse_str($firstHalf); 
$firstHalfData = $input;
$secondHalf = $_POST["scndhalf"];
parse_str($secondHalf); 
$secondHalfData = $input1;

//PROCESS
//first_half_general
$fg_score1=($firstHalfData['generals']["Energy_Feeling_of_well_being"]);
$fg_score2=($firstHalfData['generals']["Mind"]);
$fg_score3 = ($firstHalfData['generals']["Appetite"]);
$fg_score4=($firstHalfData['generals']["Sleep"]);
$fg_score5=($firstHalfData['generals']["Stool"]);
$fg_score7=($firstHalfData['generals']["Urine"]);
$fg_score8=($firstHalfData['generals']["Menses"]);
$fg_score6=($firstHalfData['generals']["Sexual_desire"]);

//first_half_particular
$fp_score1=($firstHalfData['particular']["Intensity"]);
$fp_score2=($firstHalfData['particular']["Duration"]);  
$fp_score3=($firstHalfData['particular']["Frequency"]);
$fp_score4=($firstHalfData['particular']["Recovery_period"]);
$fp_score5=($firstHalfData['particular']["Medicine_dosage"]);
$fp_score6=($firstHalfData['particular']["New_medicines"]);

//first_half_eliminations
 $fe_score1=($firstHalfData['eliminations']["Mild_Diarrhoea"]);
 $fe_score2=($firstHalfData['eliminations']["Itching"]);
 $fe_score3=($firstHalfData['eliminations']["Leakage"]);
 $fe_score4=($firstHalfData['eliminations']["Cough"]);
 $fe_score6=($firstHalfData['eliminations']["Fever"]);
 $fe_score5=($firstHalfData['eliminations']["Discharge"]);

//second_half_generals
$sg_score1=($secondHalfData['generals']["Energy_Feeling_of_well_being"]);
$sg_score2=($secondHalfData['generals']["Mind"]);
$sg_score4=($secondHalfData['generals']["Sleep"]);
$sg_score5=($secondHalfData['generals']["Stool"]);
$sg_score7=($secondHalfData['generals']["Urine"]);
$sg_score8=($secondHalfData['generals']["Menses"]);
$sg_score6=($secondHalfData['generals']["Sexual_desire"]);


//second_half_particular
$sp_score1=($secondHalfData['particular']["Intensity"]);
$sp_score2=($secondHalfData['particular']["Duration"]);
$sp_score3=($secondHalfData['particular']["Frequency"]);
$sp_score4=($secondHalfData['particular']["Recovery_period"]);
$sp_score5=($secondHalfData['particular']["Medicine_dosage"]);
$sp_score6=($secondHalfData['particular']["New_medicines"]);

//second_half_eliminations
$se_score1=($secondHalfData['eliminations']["Mild_Diarrhoea"]);
$se_score2=($secondHalfData['eliminations']["Itching"]);
$se_score3=($secondHalfData['eliminations']["Leakage"]);
$se_score4=($secondHalfData['eliminations']["Cough"]);
$se_score6=($secondHalfData['eliminations']["Fever"]);
$se_score5=($secondHalfData['eliminations']["Discharge"]);

//check the maximum main complaint
$maxMainComplaint1=checkComplaintPriority($complaint, $conn);
  if(noError($maxMainComplaint1)){
    foreach ($maxMainComplaint1['errMsg'] as $key => $value) {
      foreach ($value as $key => $maxMainComplaint) {
      }
    }
  }
  else
  {
      $msg= $maxMainComplaint1['errMsg'];
  }

  
$maxFirstMainComplaint1=checkComplaintPriority($fstcomplaint, $conn);//printArr($maxFirstMainComplaint1);
  if(noError($maxFirstMainComplaint1)){
    foreach ($maxFirstMainComplaint1['errMsg'] as $key => $value) {
      foreach ($value as $key => $maxFirstMainComplaint) {
      }
    }
  }
  else
  {
    $msg=$maxFirstMainComplaint1['errMsg'];
  }

  //check the maximum second half additional complaint
  $maxSecondMainComplaint1=checkComplaintPriority($scndcomplaint, $conn); //printArr($maxSecondMainComplaint1);
  if(noError($maxSecondMainComplaint1)){
    foreach ($maxSecondMainComplaint1['errMsg'] as $key => $value) {
      foreach ($value as $key => $maxSecondMainComplaint) {
      }
    }
  }
  else
  {
    $msg=$maxSecondMainComplaint1['errMsg'];
  }

  $main_complaint_name_array=getMainComplaintName($conn,$maxMainComplaint);  

  $complaintsQuery1= get2opinioncomplaint($complaint, $maxMainComplaint, $conn);
  $complaintsQuery2= get2opinioncomplaint($fstcomplaint, $maxFirstMainComplaint, $conn);
  $complaintsQuery3= get2opinioncomplaint($scndcomplaint, $maxSecondMainComplaint, $conn);
  
//  $complaintsQuery1= _get2opinioncomplaint($conn, $maxMainComplaint);
//  $complaintsQuery2= _get2opinioncomplaint($conn, $maxFirstMainComplaint);
//  $complaintsQuery3= _get2opinioncomplaint($conn, $maxSecondMainComplaint);
//  
   //printArr($complaintsQuery1);
   //printArr($complaintsQuery2);
   //printArr($complaintsQuery3);
   $miasm1=$complaintsQuery1['errMsg']['miasm'];
   $miasm2=$complaintsQuery2['errMsg']['miasm'];
   $miasm3=$complaintsQuery3['errMsg']['miasm'];
   $embryo1=$complaintsQuery1['errMsg']['embryologcial'];
   $embryo2=$complaintsQuery2['errMsg']['embryologcial'];
   $embryo3=$complaintsQuery3['errMsg']['embryologcial'];
   $system1=$complaintsQuery1['errMsg']['system'];
   $system2=$complaintsQuery2['errMsg']['system'];
   $system3=$complaintsQuery3['errMsg']['system'];
   $organ1=$complaintsQuery1['errMsg']['organ'];
   $organ2=$complaintsQuery2['errMsg']['organ'];
   $organ3=$complaintsQuery3['errMsg']['organ'];
   $suborgan1=$complaintsQuery1['errMsg']['subOrgan'];
   $suborgan2=$complaintsQuery2['errMsg']['subOrgan'];
   $suborgan3=$complaintsQuery3['errMsg']['subOrgan'];

   $miasmQuery1= get2opinionMiasm($conn, $miasm1);
   $miasmQuery2= get2opinionMiasm($conn, $miasm2);
   $miasmQuery3= get2opinionMiasm($conn, $miasm3);
   $embryoQuery1= get2opinionEmbryo($conn, $embryo1);
   $embryoQuery2= get2opinionEmbryo($conn, $embryo2);
   $embryoQuery3= get2opinionEmbryo($conn, $embryo3);
   $systemQuery1= get2opinionSystem($conn, $system1);
   $systemQuery2= get2opinionSystem($conn, $system2);
   $systemQuery3= get2opinionSystem($conn, $system3);
   $organQuery1= get2opinionOrgan($conn, $organ1);
   $organQuery2= get2opinionOrgan($conn, $organ2);
   $organQuery3= get2opinionOrgan($conn, $organ3);
   $suborganQuery1= get2opinionSuborgan($conn, $suborgan1);
   $suborganQuery2= get2opinionSuborgan($conn, $suborgan2);
   $suborganQuery3= get2opinionSuborgan($conn, $suborgan3);
   

  $m_id1=$miasmQuery1['errMsg']['m_id'];
  $m_id2=$miasmQuery2['errMsg']['m_id'];
  $m_id3=$miasmQuery3['errMsg']['m_id'];
  $e_id1=$embryoQuery1['errMsg']['e_id'];
  $e_id2=$embryoQuery2['errMsg']['e_id'];
  $e_id3=$embryoQuery3['errMsg']['e_id'];
  $sys_id1=$systemQuery1['errMsg']['system_id'];
  $sys_id2=$systemQuery2['errMsg']['system_id'];
  $sys_id3=$systemQuery3['errMsg']['system_id'];
  $o_id1=$organQuery1['errMsg']['o_id'];
  $o_id2=$organQuery2['errMsg']['o_id'];
  $o_id3=$organQuery3['errMsg']['o_id'];
  $s_id1=$suborganQuery1['errMsg']['s_id'];
  $s_id2=$suborganQuery2['errMsg']['s_id'];
  $s_id3=$suborganQuery3['errMsg']['s_id'];




$start=20;
$System1='';
$System2='';
if(!empty($maxFirstMainComplaint))
{
  if($m_id1!=$m_id2)
  {
   if($m_id1>$m_id2)
   {
    //echo "good";
    $System1='Good';
    $fh_s=$start+10;
   }
   else if($m_id1<$m_id2)
   {
     //echo "bad1";
      $System1='Bad';
       $fh_s=$start-10;

   }
  }
  else if($e_id1!=$e_id2)
  {
    if($e_id1>$e_id2)
   {
   // echo "good";
    $System1='Good';
    $fh_s=$start+10;
   }
   else if($e_id1<$e_id2)
   {
     //echo "bad2";
      $System1='Bad';
      $fh_s=$start-10;
   }
  }
  else if($sys_id1!=$sys_id2)
  {
    if($sys_id1>$sys_id2)
   {
   // echo "good";
    $System1='Good';
    $fh_s=$start+10;
   }
   else if($sys_id1<$sys_id2)
   {
     //echo "bad3";
      $System1='Bad';
      $fh_s=$start-10;
   }
  }
  else if($o_id1!=$o_id2)
  {
    if($o_id1>$o_id2)
   {
   // echo "good";
    $System1='Good';
    $fh_s=$start+10;
   }
   else if($o_id1<$o_id2)
   {
     //echo "bad4";
      $System1='Bad';
      $fh_s=$start-10;
   }
  }
  else if($s_id1!=$s_id2)
  {
    if($s_id1>$s_id2)
   {
    //echo "good";
    $System1='Good';
    $fh_s=$start+10;
   }
   else if($s_id1<$s_id2)
   {
    //echo "bad5";
     $System1='Bad';
     $fh_s=$start-10;
   }
  }
  else
  {
    //echo 'same';
     $System1='same';
     $fh_s=$start;
  }
}
else
{  //echo "same";
   $System1='same';
   $fh_s=$start;
}

if(!empty($maxSecondMainComplaint))
{
  if($m_id1!=$m_id3)
        {
         if($m_id1>$m_id3)
         {
          //echo "good1";
          $System2='Good';
          $sh_s=$fh_s+10;
         }
         else if($m_id1<$m_id3)
         {
           //echo "bad1";
            $System2='Bad';
             $sh_s=$fh_s-10;

         }
        }
        else if($e_id1!=$e_id3)
        {
          if($e_id1>$e_id3)
         {
          //echo "good2";
          $System2='Good';
           $sh_s=$fh_s+10;
         }
         else if($e_id1<$e_id3)
         {
           //echo "bad2";
            $System2='Bad';
             $sh_s=$fh_s-10;
         }
        }
        else if($sys_id1!=$sys_id3)
        {
          if($sys_id1>$sys_id3)
         {
          //echo "good3";
          $System2='Good';
           $sh_s=$fh_s+10;
         }
         else if($sys_id1<$sys_id3)
         {
           //echo "bad3";
            $System2='Bad';
             $sh_s=$fh_s-10;
         }
        }
        else if($o_id1!=$o_id3)
        {
          if($o_id1>$o_id3)
         {
          //echo "good4";
          $System2='Good';
           $sh_s=$fh_s+10;
         }
         else if($o_id1<$o_id3)
         {
          // echo "bad4";
            $System2='Bad';
             $sh_s=$fh_s-10;
         }
        }
        else if($s_id1!=$s_id3)
        {
          if($s_id1>$s_id3)
         {
          //echo "good5";
          $System2='Good';
           $sh_s=$fh_s+10;
         }
         else if($s_id1<$s_id3)
         {
           //echo "bad5";
           $System2='Bad';
            $sh_s=$fh_s-10;
         }
        }
        else
        {
         //echo 'same';
           $System2='same';
            $sh_s=$fh_s;
           
        }
  }
  else
  {
    //echo 'same';
    $System2='same';
     $sh_s=$fh_s;
  }

/*echo $System1."...".$System2;
echo $fh_s."...".$sh_s;*/


 $fg=getsecondOpinionGeneralResult($fg_score1,$fg_score2,$fg_score3,$fg_score4,$fg_score5,$fg_score6,$fg_score7,$fg_score8);
 $fp=getsecondOpinionParticularResult($fp_score1,$fp_score2,$fp_score3,$fp_score4,$fp_score5,$fp_score6);
 $sg=getsecondOpinionGeneralResult($sg_score1,$sg_score2,$sg_score3,$sg_score4,$sg_score5,$sg_score6,$sg_score7,$sg_score8);
 $sp=getsecondOpinionParticularResult($sp_score1,$sp_score2,$sp_score3,$sp_score4,$sp_score5,$sp_score6);

if($fg=='Good')
{
  $fg_c=$start+10;
}
else 
{
  $fg_c=$start-10;
}
if($sg=='Good')
{
  $sg_c=$fg_c+10;
}
else 
{
  $sg_c=$fg_c-10;
}
if($fp=='Good')
{
  $fp_c=$start+10;
}
else 
{
  $fp_c=$start-10;
}
if($sp=='Good')
{
  $sp_c=$fp_c+10;
}
else 
{
  $sp_c=$fp_c-10;
}

if($fe_score1 == 'good' || $fe_score2 == 'good'  || $fe_score3 == 'good' || $fe_score4 == 'good' || $fe_score5 == 'good' || $fe_score6 == 'good')
{
  $fe='Good';
  $fe_c=$start+10;
}
else
{
  $fe='Bad';
  $fe_c=$start-10;
}

if($se_score1 == 'good' || $se_score2 == 'good'  || $se_score3 == 'good' || $se_score4 == 'good' || $se_score5 == 'good' || $se_score6 == 'good')
{
  $se='Good';
  $se_c=$fe_c+10;
}
else
{
  $se='Bad';
  $se_c=$fe_c-10;
}


/*
printArr( "fg-".$fg." fp-".$fp." fe-".$fe." sg-".$sg." sp-".$sp." se-".$se);
printArr( "fg-".$fg_c." fp-".$fp_c." fe-".$fe_c." sg-".$sg_c." sp-".$sp_c." se-".$se_c);*/

$year=$_GET['year'];
 $month=$_GET['month'];
 $day=$_GET['day'];
/*if($month!=0 && $month!=""){
   $halfDuration=$year.".".$month;
}else{
   $halfDuration=$year;
}*/

if(($year==0 || $year=="") && ($month==0 || $month=="")){
  if($day>1){
    $halfDuration=$day.' days';
  }else{
    $halfDuration=$day.' day';
  }
}elseif(($month==0 || $month=="") && ($year!=0 && $year!="")){
  if($day>1){
    $halfDuration=$year.' yrs';
  }else{
    $halfDuration=$year.' yr';
  }
}elseif(($month!=0 && $month!="") && ($year==0 || $year=="")){
  if($day>1){
    $halfDuration=$month.' months';
  }else{
    $halfDuration=$month.' month';
  }
}elseif(($month!=0 && $month!="") && ($year!=0 && $year!="")){
  if($day>1){
    $halfDuration=$year.".".$month.' yrs';
  }else{
    $halfDuration=$year.".".$month.' yr';
  }
}

//$keyComplaintDignos=preg_replace('/\s+/', '_', $main_complaint_name);
$keyComplaintDignos=preg_replace('/\s+/', '_', $main_complaint_name);
foreach ($main_complaint_name_array as $key => $value) {
    $main_complaint_name=$value['Diagnostic_term'];
    foreach ($probDurationMain as $key1 => $value1) {
     if($key1==preg_replace('/\s+/', '_', $main_complaint_name) || $key1==preg_replace('/\s+/', '_', $value['Common_name'])){
      $year=$value1['years'];
      $month=$value1['months'];
      $day=$value1['days'];
     }
    }
  }
//echo $main_complaint_name;

/*if($month!=0 && $month!=""){
   $fullDuration=$year.".".$month;
}else{
   $fullDuration=$year;
}*/

if(($year==0 || $year=="") && ($month==0 || $month=="")){
  if($day>1){
    $fullDuration=$day.' days';
  }else{
    $fullDuration=$day.' day';
  }
}elseif(($month==0 || $month=="") && ($year!=0 && $year!="")){
  if($day>1){
    $fullDuration=$year.' yrs';
  }else{
    $fullDuration=$year.' yr';
  }
}elseif(($month!=0 && $month!="") && ($year==0 || $year=="")){
  if($day>1){
    $fullDuration=$month.' months';
  }else{
    $fullDuration=$month.' month';
  }
}elseif(($month!=0 && $month!="") && ($year!=0 && $year!="")){
  if($day>1){
    $fullDuration=$year.".".$month.' yrs';
  }else{
    $fullDuration=$year.".".$month.' yr';
  }
}
//echo $halfDuration;
//echo $fullDuration;

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
        display: flex;
        max-width: 100%;
        margin-bottom: 21px;
        font-size: 24px;
        word-spacing: 4px;
        font-weight: normal!important;
        color: #444;
        letter-spacing: 1px;
        word-spacing: 2px;
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
           ul.label-ui li > label {
        padding: 15px;
        font-size: 18px;/*
         //color: #444; */
        letter-spacing: 1px;
        line-height: 1.7em;
        font-weight: normal;
    }
    ul{
      list-style: none;
    }

     
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

      .submit-questbtn  {
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
            margin-bottom: 10%;      
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
        label {
          font-size: 17px;
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
     <!-- <div class="row">
      <div class="col-md-12 banner" style="margin: 0px 0px 30px 0px;" >
       <img src="../../assets/images/2opinionbanner.png" class="img-responsive">
       <h1><span style="color:#ffb600;">Let's see how your treatment is on track or off the rails! </span><span style="color:#ffffff;">Introducing a foolproof application built by the world famous Dr. Khedekar</span></h1>
      </div>
    </div> -->
    <div style="display: none">
     <?php  printArr( "fg-".$fg." fp-".$fp." fe-".$fe." sg-".$sg." sp-".$sp." se-".$se);
            printArr( "fg-".$fg_c." fp-".$fp_c." fe-".$fe_c." sg-".$sg_c." sp-".$sp_c." se-".$se_c);
            echo "system1-".$System1."..."."system2-".$System2;
?>
    </div>
      
     <div class="row result">
         <div>
              <?php
              $conclusion="";
                  $getconclusion=getObservation($fg,$fp,$fe,$System1,$sg,$sp,$se,$System2,$conn);
                  //$conclusionStatus=1;
                    $conclusionStatus=0;
                    if(noError($getconclusion)){
                      $conclusionStatus=0;
                     $test_case=$getconclusion['errMsg']['test_case'];
                      //$conclusionStatus=0;
                     // echo $test_case=$conclusion11['errMsg']['test_case'];
                    $conclusion = $getconclusion['errMsg']['conclusion'];
                   
                    //echo $test_case=$conclusion11['errMsg']['sh_s'];
                  } else {
                    $conclusionStatus=1;
                      //echo "new Logic";

                        ?>
                        <div class="otherQuestion" style="margin-top: 15%;">
                          <form method="post" action="2ndConclusion.php?halfDuration=<?php echo $halfDuration;?>&fullDuration=<?php echo $fullDuration;?>" class="row-fluid margin-gap-50">
                             <div class="flexCont">
                             <?php 
                              $Questions2opinion=getQuestions2opinion($conn);
                                foreach($Questions2opinion['errMsg'] as $key=>$value){ 
                                 // echo $value['quest_id'];
                                  ?>
                                  <div class="form-group" style="border-bottom: 1px solid #BFBFBF; margin: 1.5em 0; padding: 10px;">
                                        <input type="radio" id="<?php echo 'radio_'.$value['quest_id']; ?>" name="questions" value="<?php echo $value['quest_id'];?>">
                                        <label style="" for="<?php echo 'radio_'.$value['quest_id']; ?>" data-remdies="<?php echo $value['title'];?>"><?php echo $value['title'];?></label>
                                        <input type="hidden" name="fg" value="<?php echo $fg; ?>" >
                                        <input type="hidden" name="fp" value="<?php echo $fp; ?>" >
                                        <input type="hidden" name="fe" value="<?php echo $fe; ?>" >
                                        <input type="hidden" name="sg" value="<?php echo $sg; ?>" >
                                        <input type="hidden" name="sp" value="<?php echo $sp; ?>" >
                                        <input type="hidden" name="se" value="<?php echo $se; ?>" >
                                        <input type="hidden" name="System1" value="<?php echo $System1; ?>" >
                                        <input type="hidden" name="System2" value="<?php echo $System2; ?>" >
                                  </div>
                              <?php }
                             ?>
                             </div>
                             <input type="hidden" name="complaint" id="complaint" value="<?php echo $main_complaint_name; ?>"/>
                              <div class="col-md-12" style="text-align: center;margin-top:80px;" >
                                  <h4 id="errMsg" style="color:red;"></h4>
                                  <input type="submit" name="" id="getQuestion" class="submit-questbtn" value="SUBMIT">
                              </div>  
                         </form>
                         </div>
                             <?php
                  }
              ?>
          </div>
        </div>


          <?php if($test_case==0){
                  $url="background: url(../../assets/images/good.png);";
                }else{
                  $url="background: url(../../assets/images/bad.png);";
              }
              if($conclusionStatus!=0){
                $display="display:none;";
              }
              else{
                 $display="display:block;";
              }
              ?>


           <!--Conclusion -->
                <div class="">   
                  <div id="conclusion" class="text-center" style="<?php echo $display;?>">
                    <div class="col-md-12 good"  id="good" style="<?php echo $url;?>background-size: cover;">
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
                if($test_case!=0)
                {
              ?>
               <!--  <div class="row" style="margin:10% 0 ;">
         
          <div class="col-md-6 col-sm-6 col-xs-12" >
              <input type="button" name="" id="" class="submit-btn getMedicine pull-right" value="GET MEDICINE" style="background-color: #ffb431;margin-right: 10%;">
          </div>


          <div class="col-md-6 col-sm-6 col-xs-12" >
              <input  type="button" name="" id="" class="submit-btn consultDoctor " value="CONSULT DOCTOR" style="margin-left: 10%;"   onclick="location.href='../contactDoctors.php';" >
          </div>
            
         
      </div> -->
                  <?php } ?> 



        <div class="text-center" id="conclusionChart" style="<?php if($conclusionStatus==1){ echo 'display:none;'; }?>min-height: 100%;margin-top:30px;">
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
              <h1 class="panel-heading" >Symptoms of<br> <?php echo " ".$main_complaint_name;?></h1>
             
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

              <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header" style="">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h3 id="myModalLabel">CASE ID</h3>
                    </div>
                    <div class="modal-body">
                     <p>Please note down your case id to import after login</p>
                      <div class="form-group">
                       <input class="text-center" type="text" id="caseId" value="" name="caseId" readonly>
                       </div>
                    </div>
                    
                    <div class="modal-footer" style="">
                      <div class="text-center">
                      <a href="<?php echo ".." ; ?>/sign_in.php" class="btn btn-common" style="">
                        Sign UP
                      </a>
                      <a href="<?php echo ".." ; ?>/sign_in.php" class="btn btn-common">
                        Login
                      </a>
                      </div>
                    </div>
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

$("#getQuestion").click(function(e){
    if($("input[type='radio']:checked").length == 0){
      $('#errMsg').html("Please select one question");
        $('#errMsg').show();
        e.preventDefault();
    }
    else
    {
      $('#errMsg').hide();
    }
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
    var myLineChart3 = new Chart(ct4).Line(lineChartData4,lineOption4);
</script>
    </body>
</html>

<!-- This is the div where the graph will be displayed -->
