<?php

session_start();

  $title="Conclusion";
  $activeHeader="2opinion";

  require_once("../../utilities/config.php");
  require_once("../../utilities/dbutils.php");
  require_once("../../models/followUpModel.php");
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
  //echo "hii";
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

$FollowUp = $_POST["followupdata"];
parse_str($FollowUp); 
$followupdata = $input1;
$case_id=$_POST["case_id"];
$complaint=$_POST['complaint'];
$patient_id=$_POST['patient_id'];
//printArr($followupdata);
  //VALIDATE
//echo $followupdata['generals']["Sexual_desire"];
//PROCESS
//FollowUp_general
 $fg_score1=getFollowUpScore($followupdata['generals']["Energy_Feeling_of_well_being"]);
 $fg_score2=getFollowUpScore($followupdata['generals']["Mind"]);
 $fg_score3=getFollowUpScore($followupdata['generals']["Appetite"]);
 $fg_score4=getFollowUpScore($followupdata['generals']["Sleep"]);
 $fg_score5=getFollowUpScore($followupdata['generals']["Stool"]);
 $fg_score6=getFollowUpScore($followupdata['generals']["Sexual_desire"]);
 $fg_score7=getFollowUpScore($followupdata['generals']["Urine"]);
 $fg_score8=getFollowUpScore($followupdata['generals']["Menses"]);

//FollowUp_particular
 $fp_score1=getFollowUpScore($followupdata['particular']["Intensity"]);
 $fp_score2=getFollowUpScore($followupdata['particular']["Duration"]);
 $fp_score3=getFollowUpScore($followupdata['particular']["Frequency"]);
 $fp_score4=getFollowUpScore($followupdata['particular']["Recovery_period"]);
 $fp_score5=getFollowUpScore($followupdata['particular']["Medicine_dosage"]);
 $fp_score6=getFollowUpScore($followupdata['particular']["New_medicines"]);

//Followup_eliminations
  $fe_score1=getFollowUpScore($followupdata['eliminations']["Mild_Diarrhoea"]);
  $fe_score2=getFollowUpScore($followupdata['eliminations']["Itching"]);
  $fe_score3=getFollowUpScore($followupdata['eliminations']["Leakage"]);
  $fe_score4=getFollowUpScore($followupdata['eliminations']["Cough"]);
  $fe_score6=getFollowUpScore($followupdata['eliminations']["Fever"]);
  $fe_score5=getFollowUpScore($followupdata['eliminations']["Discharge"]);



 $fg_score=$fg_score1+$fg_score2+$fg_score3+$fg_score4+$fg_score5+$fg_score6+$fg_score7+$fg_score8;
 $fp_score=$fp_score1+$fp_score2+$fp_score3+$fp_score4+$fp_score5+$fp_score6; //printArr($fg_score);
 $fe_score=$fe_score1+$fe_score2+$fe_score3+$fe_score4+$fe_score5+$fe_score6; //printArr($fe_score);

$finalScore=$fg_score+$fp_score+$fe_score;

?>

<!DOCTYPE html>
  <html lang="en">
  <head>
  <?php include_once("../metaInclude.php"); ?>
  <style type="text/css">
      
      /*.fstbtn:hover{
        color:#fff !important;
      }
      .fstbtn{
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
      }*/
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



@media only screen and (max-width: 991px) {
.fstbtn , .retakeCase,.endFollowup{
    background-color: #0dae04;
    border-radius: 7px;
    color: #fff;
    text-align: center;
    padding: 0px 40px;
    outline: none;
    border: none;
    font-size: 40px;
    margin-top: 10px;
    margin-bottom: 20px;
   /* margin-bottom: auto;
    margin-top: 10px;
}*/
      }
}






@media only screen and (max-width: 991px) {
.fstbtn {
    background-color: #0dae04;
    border-radius: 7px;
    color: #fff;
    text-align: center;
    padding: 0px 90px;
    outline: none;
    border: none;
    font-size: 40px;
    margin-top: 10px;
    margin-bottom: 20px;
   /* margin-bottom: auto;
    margin-top: 10px;
}*/
      }
}




@media only screen and (max-width: 991px) {
.retakeCase{
    background-color: #0dae04;
    border-radius: 7px;
    color: #fff;
    text-align: center;
    padding: 0px 60px;
    outline: none;
    border: none;
    font-size: 40px;
    margin-top: 10px;
    margin-bottom: 20px;
   /* margin-bottom: auto;
    margin-top: 10px;
}*/
      }
}






@media only screen and (max-width: 600px) {
.fstbtn , .retakeCase,.endFollowup{
    background-color: #0dae04;
    border-radius: 7px;
    color: #fff;
    text-align: center;
    padding: 0px 16px;
    outline: none;
    border: none;
    font-size: 20px;
    margin-top: 10px;
    margin-bottom: 20px;
    width: 90%;
    display: inline-block;

}
}






@media only screen and (max-width: 600px) {
.fstbtn {
    background-color: #0dae04;
    border-radius: 7px;
    color: #fff;
    text-align: center;
    padding: 0px 52px;
    outline: none;
    border: none;
    font-size: 20px;
    margin-top: 10px;
    margin-bottom: 10px;
    /*padding-left: 30px;
    padding-right: 30px;*/
    

}
}





@media only screen and (max-width: 600px) {
.retakeCase{
    background-color: #0dae04;
    border-radius: 7px;
    color: #fff;
    text-align: center;
    padding: 0px 28px;
    outline: none;
    border: none;
    font-size: 20px;
    margin-top: 10px;
    margin-bottom: 20px;
    /*padding-left: 30px;
    padding-right: 30px;*/
    
}
}




/*@media only screen and (max-width: 991px) {
.retakeCase{
    background-color: #0dae04;
    border-radius: 7px;
    color: #fff;
    text-align: center;
    padding: 0px 16px;
    outline: none;
    border: none;
    font-size: 30px;
    margin-top: 10px;
    margin-bottom: 20px;
    padding-left: 55px;
    padding-right: 55px;    
}
}*/




/*@media only screen and (max-width: 600px) {
.retakeCase{
  padding-left: 30px;
  padding-right: 30px;

}
}


@media only screen and (max-width: 992px) {
  .retakeCase{
    padding-left: 55px;
    padding-right: 55px
}
}
*/



/*@media only screen and (max-width: 600px) {
.fstbtn{
 padding-left: 40px;
 padding-right: 40px;

}
}
*/


/*@media only screen and (max-width: 992px) {
.fstbtn{
 padding-left: 55px;
  padding-right: 55px;
}
}
*/


/*@media (max-width: 600px) {
 .fstbtn , .retakeCase,.endFollowup{
    padding:2px 4px;
    font-size:80%;
    line-height: 1;
  }
}*/









      
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
     
      
        @media(max-width: 435px){
         .head{
          font-size: 30px;
         }
         .conclusionContent{
          font-size:16px;
         }
      }

      
  </style>

 <main class="container" style="min-height: 100%;">
     <?php  include_once("../header.php"); ?> 
<section>
<div class="main-container">
    
    <div style="display: none">
     <?php  printArr( "fg-".$fg." fp-".$fp." fe-".$fe." sg-".$sg." sp-".$sp." se-".$se);
            printArr( "fg-".$fg_c." fp-".$fp_c." fe-".$fe_c." sg-".$sg_c." sp-".$sp_c." se-".$se_c);
            echo "system1-".$System1."..."."system2-".$System2;
     ?>
    </div>
      
    <?php
                             if($finalScore>0)
                              {
                                //status 1 for good result
                                $status=1;
                                $button=1;
                                $conclusion_status="good";
                                 $url="background: url(../../assets/images/good.png);";
                                 $conclusion="You are on the right track. Keep going!";
                              }
                              else if($finalScore<=0)
                              {
                                //status 2 for bad result
                                $status=2;
                                $count=0;
                                $followUpConclusionStatus=getFollowUpConclusionStatus($conn,$case_id,$patient_id);
                                //printArr($followUpConclusionStatus);
                                foreach ($followUpConclusionStatus as $key => $value) {
                                  # code...
                                  foreach ($value as $key1 => $value1) {
                                    # code...
                                   if($value1['status']==2)
                                   {
                                    $count++;
                                   }
                                  }
                                 
                                }


                                if($count==2)
                                {
                                  $button=2;
                                  $conclusion_status="bad";
                                  $url="background: url(../../assets/images/bad.png);";
                                  $conclusion="You are on the wrong track. You might need medicine.";
                                  /*$getDoctorsUser=getDoctorsUser($conn,$user);
                                  if($getDoctorsUser['errCode']!=-1)
                                  {
                                    $usertype='eheilungUser';
                                     $conclusion="You might be on the wrong track.Please get medicine from eheilung again";
                                  }
                                  else
                                  { 
                                    $usertype="docUser";
                                      $conclusion="You might be on the wrong track.Please get medicine from your doctor Again";
                                  }*/
                                }
                                else
                                {
                                   $conclusion_status="noChange";
                                   $button=1;
                                   $url="background: url(../../assets/images/statusquo.png);";
                                   $conclusion="It may be too soon to tell. Please continue your medicine and be regular about follow ups.";
                                }
                              }
                            /*  else
                              {
                                $status=0;
                                 $conclusion="You might need to change potency.";
                              }*/


                           
                                      //echo $status;
                                 // echo $_SESSION['flag'];
                                 if($_SESSION['flag']==false)
                                 {
                                  //echo "hii";
                                   $check=saveFollowUp($conn,$case_id,$patient_id,$FollowUp,$conclusion,$conclusion_status,$status,'done');
                                   //printArr($check);
                                   if(noError($check)){
                                          $returnArr["errCode"]=-1;
                                          $returnArr["errMsg"]="Insertion successful";
                                          $caseId = $check['errMsg'];
                                     $_SESSION['flag']=true;
                                      }else{
                                          $caseId =0;
                                          $returnArr["errCode"] = 8;
                                          $returnArr["errMsg"]=" Insertion failed".mysqli_error($conn);
                                         
                                      }
                                  }

                        ?>


           <!--Conclusion -->
                <div class="row noleft-right" >
                    <div class="col-md-5 col-sm-5 col-xs-12 managepatient" >
                      <h2 style="margin-left: -15px;font-weight: 600;margin-top: 7%;">Follow up <img src="../../assets/images/info.png" class="heading-info" data-toggle="modal" data-target="#infoModal" /></h2>
                    </div>
                </div>
                <div class="">   
                  <div id="conclusion" class="text-center" style="">
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
                <div class="row" style="text-align: center; margin:10% 0 ;">
                    <?php 
                      if($button==1)
                      {
                    ?>
                   <!--  <div class="col-md-12" >
                        <a class="fstbtn" href="../patient/patientCaseHistory.php?patient_id=<?php echo $patient_id; ?>">MY CASES</a>
                    </div> -->
                    <div class="row">

                    <div class="col-md-12" style="text-align: center;margin-top:80px;" >
                      <h4 id="errorMsg" style="color:red;"></h4>
                      <div class="col-md-4 col-sm-12 col-xs-12" style="text-align: center;">
                        <a id="" class="endFollowup" style="margin: 10px;background-color: #e74f62;cursor: pointer;">End follow up</a>
                      </div> 





                      <div class="col-md-4 col-sm-12 col-xs-12" style="text-align: center;cursor: pointer; ">
                        <a id="" class="fstbtn" href="../patient/patientCaseHistory.php?patient_id=<?php echo $patient_id; ?>" style="">My cases</a>
                      </div>
                      <div class="col-md-4 col-sm-12 col-xs-12" style="text-align: center;">
                        <a id="" class="retakeCase" style="margin: 10px;background-color: #ffb431;cursor: pointer;">Retake case</a>
                      </div>
                    </div>
                   </div>
                  <?php }elseif ($button==2) { ?>
                    <!--  <div class="col-md-12" >
                        <a class="fstbtn" href="../patient/patientCaseHistory.php?patient_id=<?php echo $patient_id; ?>">GET MEDICINE</a>
                    </div> -->
                  <div class="row">
                    <div class="col-md-12" style="text-align: center;margin-top:80px;" >
                      <h4 id="errorMsg" style="color:red;"></h4>
                      <div class="col-md-4 col-sm-12 col-xs-12" style="text-align: center;">
                        <a id="" class="endFollowup" style="margin: 10px;background-color: #e74f62;cursor: pointer;">End follow up</a>
                      </div> 
                      <div class="col-md-4 col-sm-12 col-xs-12" style="text-align: center;cursor: pointer;">
                        <a class="fstbtn" href="../patient/patientCaseHistory.php?patient_id=<?php echo $patient_id; ?>">GET MEDICINE</a>
                      </div>
                      <div class="col-md-4 col-sm-12 col-xs-12" style="text-align: center;">
                        <a id="" class="retakeCase" style="margin: 10px;background-color: #ffb431;cursor: pointer;">Retake case</a>
                      </div>
                    </div>
                   </div>
                  <?php } ?> 
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
     // alert('hiii');
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

</script>
    </body>
</html>

<!-- This is the div where the graph will be displayed -->

