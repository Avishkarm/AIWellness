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
//printArr($flag);
$secondHalf = $_POST["scndhalf"];
parse_str($secondHalf); 
$modernData = $input;
//VALIDATE

//PROCESS
if($problem=='yes')
{

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

  $complaintsQuery1= get2opinioncomplaint($complaint, $maxMainComplaint, $conn);
  $complaintsQuery2= get2opinioncomplaint($scndcomplaint, $maxSecondMainComplaint, $conn);

 //  $complaintsQuery1= get2opinioncomplaint($conn, $maxMainComplaint);
 //  $complaintsQuery2= get2opinioncomplaint($conn, $maxSecondMainComplaint);


   $miasm1=$complaintsQuery1['errMsg']['miasm'];
   $miasm2=$complaintsQuery2['errMsg']['miasm'];
   $embryo1=$complaintsQuery1['errMsg']['embryologcial'];
   $embryo2=$complaintsQuery2['errMsg']['embryologcial'];
   $system1=$complaintsQuery1['errMsg']['system'];
   $system2=$complaintsQuery2['errMsg']['system'];
   $organ1=$complaintsQuery1['errMsg']['organ'];
   $organ2=$complaintsQuery2['errMsg']['organ'];
   $suborgan1=$complaintsQuery1['errMsg']['subOrgan'];
   $suborgan2=$complaintsQuery2['errMsg']['subOrgan'];

   $miasmQuery1= get2opinionMiasm($conn, $miasm1);
   $miasmQuery2= get2opinionMiasm($conn, $miasm2);
   $embryoQuery1= get2opinionEmbryo($conn, $embryo1);
   $embryoQuery2= get2opinionEmbryo($conn, $embryo2);
   $systemQuery1= get2opinionSystem($conn, $system1);
   $systemQuery2= get2opinionSystem($conn, $system2);
   $organQuery1= get2opinionOrgan($conn, $organ1);
   $organQuery2= get2opinionOrgan($conn, $organ2);
   $suborganQuery1= get2opinionSuborgan($conn, $suborgan1);
   $suborganQuery2= get2opinionSuborgan($conn, $suborgan2);
   

   $m_id1=$miasmQuery1['errMsg']['m_id'];
   $m_id2=$miasmQuery2['errMsg']['m_id'];
   $e_id1=$embryoQuery1['errMsg']['e_id'];
   $e_id2=$embryoQuery2['errMsg']['e_id'];
   $sys_id1=$systemQuery1['errMsg']['system_id'];
   $sys_id2=$systemQuery2['errMsg']['system_id'];
   $o_id1=$organQuery1['errMsg']['o_id'];
   $o_id2=$organQuery2['errMsg']['o_id'];
   $s_id1=$suborganQuery1['errMsg']['s_id'];
   $s_id2=$suborganQuery2['errMsg']['s_id'];

  if($m_id1!=$m_id2)
  {
   if($m_id1>$m_id2)
   {
    //echo "good";
    $system='good';
   }
   else if($m_id1<$m_id2)
   {
    // echo "bad";
      $system='bad';

   }
  }
  else if($e_id1!=$e_id2)
  {
    if($e_id1>$e_id2)
   {
   // echo "good";
    $system='good';
   }
   else if($e_id1<$e_id2)
   {
    // echo "bad";
      $system='bad';
   }
  }
  else if($sys_id1!=$sys_id2)
  {
    if($sys_id1>$sys_id2)
   {
   // echo "good";
    $system='good';
   }
   else if($sys_id1<$sys_id2)
   {
    // echo "bad";
      $system='bad';
   }
  }
  else if($o_id1!=$o_id2)
  {
    if($o_id1>$o_id2)
   {
   // echo "good";
    $system='good';
   }
   else if($o_id1<$o_id2)
   {
    // echo "bad";
      $system='bad';
   }
  }
  else if($s_id1!=$s_id2)
  {
    if($s_id1>$s_id2)
   {
    //echo "good";
    $system='good';
   }
   else if($s_id1<$s_id2)
   {
    // echo "bad";
     $system='bad';
   }
  }
  else
  {
  //  echo 'same';
     $system='same';
  }

}


//FollowUp_general
 $fg_score1=($modernData['generals']["Energy_Feeling_of_well_being"]);
 $fg_score2=($modernData['generals']["Mind"]);
 $fg_score3=($modernData['generals']["Appetite"]);
 $fg_score4=($modernData['generals']["Sleep"]);
 $fg_score5=($modernData['generals']["Sexual_Desire"]);


//FollowUp_particular
 $fp_score1=($modernData['particular']["Intensity"]);
 $fp_score2=($modernData['particular']["Duration"]);
 $fp_score3=($modernData['particular']["Medicine_Dosage"]);
 $fp_score4=($modernData['particular']["New_Medicines"]);


?>
<!DOCTYPE html>
  <html lang="en">
  <head>
  <?php include_once("../metaInclude.php"); ?>
  <style type="text/css">
      .conclusionContent
      {
        font-size:30px !important;
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
        font-size:50px !important;
        font-weight: 600 !important;
        color:#fff;
        letter-spacing: 1px;
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
    /*  .submit-btn .getMedicine{
        float:right;
      }
      .submit-btn .consultDoctor{
        float:left;
      }*/
    @media(max-width: 768px){
       .submit-btn {
        text-align: center;
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
     .chosen-container-multi .chosen-choices li.search-choice .search-choice-close{
        background: url('../../assets/images/error.png')  no-repeat !important;
        background-size: contain !important;
  }
  </style>

 <main class="container" style="min-height: 100%;">
     <?php  include_once("../header.php"); ?> 

  <!--    <div class="row">
      <div class="col-md-12 banner" style="margin: 0px 0px 30px 0px;" >
       <img src="../../assets/images/2opinionbanner.png" class="img-responsive">
       <h1><span style="color:#ffb600;">Let's see how your treatment is on track or off the rails! </span><span style="color:#ffffff;">Introducing a foolproof application built by the world famous Dr. Khedekar</span></h1>
      </div>
    </div> -->
      
     <div class="row result">
           <?php   

                //conclusio calculation
        $conclusion="";
            $start=10;
            
            if($problem=='no')
            {
            
              if( $fg_score1=='good' && $fg_score2=='good' && $fg_score3=='good' && $fg_score4=='good' && $fg_score5=='good' && $fp_score1=='good' && $fp_score2=='good' && $fp_score3=='good' && $fp_score4=='good')
              {
                 $conclusion="You are on the right track.Please continue in the same manner.";
                 $test_case=0;
                  $url="background: url(../../assets/images/good.png);";

              }
              else
              {
                 $conclusion="You might be on the wrong track.Please get medicine recommendation from eH&nbsp;Genius or consult expert";
                 $url="background: url(../../assets/images/bad.png);";
                 $test_case=1;
              }

            }
           else if($problem=='yes' && $system=="bad"){

             $conclusion="You might be on the wrong track.Please get medicine recommendation from eH&nbsp;Genius or consult expert";
               $url="background: url(../../assets/images/bad.png);";
                 $test_case=1;
            
             

            }else if($problem=='yes'){
              if( $fg_score1=='good' && $fg_score2=='good' && $fg_score3=='good' && $fg_score4=='good' && $fg_score5=='good' && $fp_score1=='good' && $fp_score2=='good' && $fp_score3=='good' && $fp_score4=='good')
              {
                 $conclusion="You are on the right track.Please continue in the same manner.";
                 $test_case=0;
                  $url="background: url(../../assets/images/good.png);";

              }
              else
              {
                 $conclusion="You might be on the wrong track.Please get medicine recommendation from eH&nbsp;Genius or consult expert";
                 $url="background: url(../../assets/images/bad.png);";
                 $test_case=1;
              }
            }else {
              if( $fg_score1=='good' && $fg_score2=='good' && $fg_score3=='good' && $fg_score4=='good' && $fg_score5=='good' && $fp_score1=='good' && $fp_score2=='good' && $fp_score3=='good' && $fp_score4=='good')
              {
                 $conclusion="You are on the right track.Please continue in the same manner.";
                 $test_case=0;
                  $url="background: url(../../assets/images/good.png);";

              }
              else
              {
                 $conclusion="You might be on the wrong track.Please get medicine recommendation from eH&nbsp;Genius or consult expert";
                 $url="background: url(../../assets/images/bad.png);";
                 $test_case=1;
              }
            }


             
            ?>

          </div>


           <!--Conclusion -->
                <div class="row">   
                  <div id="conclusion" class="col-md-12 text-center" style="">
                    <div class="col-md-12 good"  id="good" style="<?php echo $url;?>">
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
                <!-- <div class="row">
                    <div class="col-md-12" style="text-align: center;margin-top:80px;" >
                    <div class="col-md-6"><input type="button" name="" id="" class="submit-btn getMedicine" value="Get medicine" style="background-color: #ffb431;"></div>
                    <div class="col-md-6"><input type="button" name="" id="" class="submit-btn consultDoctor" value="Consult doctor"   onclick="location.href='../contactDoctors.php';" ></div>
                      
                    </div>
                </div> -->
                  <?php } ?> 
  </main>
  <?php include('../modals.php'); ?> 
   <?php include('../footer.php'); ?>
<script type="text/javascript">


$(".getMedicine").click(function(){
  location.href='../../views/get_medicine.php';
});
$(".consultDoctor").click(function(){
  // location.href="http://imperialclinics.com/";
});

</script>
    </body>
</html>

<!-- This is the div where the graph will be displayed -->
