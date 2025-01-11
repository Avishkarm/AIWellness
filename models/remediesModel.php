<?php
    function getDocCaseSheet($caseId,$conn) {
      global $blanks;
      $returnArr = array();

      $query="SELECT * FROM doctors_patient_cases where id='".$caseId."'";
      
      $result=runQuery($query, $conn);
      if(noError($result)){
        $res=array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res=$row;
        }
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching case sheet data";
      }

      return $returnArr;
    }

function calc8PFRemedy($caseId, $doctor_id,$userInfo){
  $allRemedies=array();

  $sixteenPFResults = (json_decode($userInfo["doctor_8pf"], true));
/*printArr($sixteenPFResults);
printArr($sixteenPFResults[$caseId][$doctor_id]);
die;*/
  foreach($sixteenPFResults[$caseId][$doctor_id] as $factorId=>$factorDetails){
    //if($factorDetails["sten_score"]>=0 && $factorDetails["sten_score"]<=4){
      $remedies = explode(",", $factorDetails["factor_remedy"]);
      $allRemedies['factor'][$factorId]['factor_name']= $factorDetails["factor_name"];
      $allRemedies['factor'][$factorId]['factor_remedy']= $factorDetails["factor_remedy"];

      foreach($remedies as $a=>$remedyName){
        $remedyName = trim($remedyName);
        if(array_key_exists($remedyName,$allRemedies)){
          $allRemedies['factor_remedy'][$remedyName]+=3;
        } else {
          $allRemedies['factor_remedy'][$remedyName]=3;
        }
      }
    //} 
  }
  //printArr($allRemedies);
  return $allRemedies;
}
function getBlankSheetDetails($caseId,$patient_id,$conn){
	global $blanks;
  $returnArr = array();

  $query="SELECT * FROM kes_case_details where KES_case_id='".$caseId."' and user_id='".$patient_id."'";
  
  $result=runQuery($query, $conn);
  if(noError($result)){
    $res=array();
    while($row=mysqli_fetch_assoc($result["dbResource"])){
      $res[]=$row;
    }
    $returnArr["errCode"][-1]=-1;
    $returnArr["errMsg"]=$res;
  } else {
    $returnArr["errCode"][1]=1;
    $returnArr["errMsg"]="Error fetching case sheet data";
  }
  //printArr($returnArr);
      return $returnArr;
}

function getRubricsDetails($conn, $qid, $aid){
    global $blanks;
    $returnArr = array();
    
    $query=sprintf("SELECT r.*,
                           q.*
                    FROM   rubrics r
                           INNER JOIN (SELECT ka.*,
                                              kq.rubric_id
                                       FROM   kes_answer_options ka
                                              INNER JOIN kes_questions kq
                                                      ON ka.qid = kq.question_id
                                       WHERE  ka.qid = '%s'
                                              AND ka.aid = '%s') q
                                   ON q.rubric_id = r.rubric_id ",$qid, $aid);
  
    $result=runQuery($query, $conn);
    if(noError($result)){
        $res=array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res[]=$row;
        }
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
       //print_r($res);
    } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching case sheet data";
    }
   
    return $returnArr;
}
function calculate16PF_RubricRemedy($conn,$caseId, $doctor_id, $patient_id){
    $remedy16PF = array();
    $remedyRubric = array();
    $rubricArray = array();
    $userInfo = getUserInfoWithUserId1($patient_id, $conn);
    if(noError($userInfo)){
        $userInfo = $userInfo["errMsg"];
    } else {
        $returnArr['errMsg']="Error fetching User Details ".$userInfo['errMsg'];
        $returnArr['errCode'][1]=1;
        return $returnArr;
    }
  /*$chckPF = check16PFUser($conn, $user);
  if(noError($chckPF)){
    $chckPF = $chckPF['errMsg'];
    if($chckPF['caseInfoFlag']){*/
     
      //16PF younger then 3 yrs
    $remedy16PF =calc8PFRemedy($caseId, $doctor_id,$userInfo);
    $allRemedies = $remedy16PF['factor_remedy'];
    /*}else{
       //16PF older then 3 yrs
        $remedy16PF = calc16PfRemedy($userInfo);
        $allRemedies = $remedy16PF['factor_remedy'];
    }
     */
    $getBlankSheetDetails=getBlankSheetDetails($caseId,$patient_id,$conn);
    if(noError($getBlankSheetDetails)){
        $getBlankSheetDetails=$getBlankSheetDetails['errMsg'];
        foreach ($getBlankSheetDetails as $key => $value) {
            //printArr($value);
            $rubric =getRubricsDetails($conn, $value['question_id'], $value['aid']);
            //printArr($rubric);
            if(noError($rubric)){
                $rubric = $rubric["errMsg"];

                foreach($rubric as $priQuestionName=>$priQuesDets){
                    //printArr($priQuesDets);
                    /*if($priQuesDets["rubric_name"]=="Thermals" || $priQuesDets["rubric_name"]=="THIRST")
                    {
                      echo "thermal and thirst 10<br>";
                      echo $priQuesDets['ans_label']."<br>";
                      echo $priQuesDets["answerRemedies"]."<br>"; 
                    }else if($priQuesDets["rubric_name"]=="Mentally sensitive to" || $priQuesDets["rubric_name"]=="Mental Reactions"){
                       echo "Reactions sensitive 5<br>";
                        echo $priQuesDets['ans_label']."<br>";
                       echo $priQuesDets["answerRemedies"]."<br>";
                    }else{
                        echo "Normal 2<br>";
                         echo $priQuesDets['ans_label']."<br>";
                        echo $priQuesDets["answerRemedies"]."<br>";
                    }*/
                    $remedies = explode(",", $priQuesDets["answerRemedies"]);

                    $rubricArray[$priQuesDets['rubric_name']]['answer'] = $priQuesDets['ans_label'];
                    $rubricArray[$priQuesDets['rubric_name']]['remedies'] = $priQuesDets["answerRemedies"];

                    //echo $rubricArray[$priQuesDets['rubric_name']];
                    foreach($remedies as $a=>$remedyName){

                        $remedyName = trim($remedyName);
                        //echo $remedyName."<br>";

                        if(array_key_exists($remedyName,$allRemedies)){
                            if(strcasecmp("Thermals", $priQuesDets['rubric_name']) == 0 or strcasecmp("THIRST", $priQuesDets['rubric_name']) == 0 ){
                                //echo "thermal and thirst 10<br>";
                                $allRemedies[$remedyName]+=10;
                                //printArr($allRemedies);
                            }
                            else if(strcasecmp("Mentally SENSITIVITY", $priQuesDets['rubric_name']) == 0 or strcasecmp("Mental Reactions", $priQuesDets['rubric_name']) == 0 or strcasecmp("Causation", $priQuesDets['rubric_name']) == 0){
                                // echo "Reactions sensitive 5<br>";
                                $allRemedies[$remedyName]+=5;
                                //printArr($allRemedies);
                            }
                            else{        
                                //echo "Normal 2<br>";
                                $allRemedies[$remedyName]+=2; 
                                //printArr($allRemedies);
                            }
                        } 
                        else {
                            if(strcasecmp("Thermals", $priQuesDets['rubric_name']) == 0 or strcasecmp("THIRST", $priQuesDets['rubric_name']) == 0 ){
                                // echo "thermal and thirst 10<br>";
                                $allRemedies[$remedyName]=10;
                                //printArr($allRemedies);
                            }
                            else if(strcasecmp("Mentally SENSITIVITY", $priQuesDets['rubric_name']) == 0 or strcasecmp("Mental Reactions", $priQuesDets['rubric_name']) == 0 or strcasecmp("Causation", $priQuesDets['rubric_name']) == 0){
                                // echo "Reactions sensitive 5<br>";
                                $allRemedies[$remedyName]=5;
                                // printArr($allRemedies);
                            }
                            else{
                                // echo "Normal 2<br>";
                                $allRemedies[$remedyName]=2;
                                //printArr($allRemedies);  
                            }
                        }

                    }

                }
            }
            else {
              $returnArr['errMsg']="Error fetching Rubrics Details ".$rubric['errMsg'];
              $returnArr['errCode'][1]=1;
              return $returnArr;
            }
        }
    }else{
        $returnArr['errMsg']="Error fetching blank sheet case Details ".$getBlankSheetDetails['errMsg'];
        $returnArr['errCode'][1]=1;
    }
    
    $returnArr['errMsg']['remedy16PF'] = $remedy16PF;
    $returnArr['errMsg']['remedyRubric'] = $rubricArray;
    $returnArr['errMsg']['allRemedies'] = $allRemedies;
    $returnArr['errCode'][-1] = -1;
    // printArr($returnArr);
    return $returnArr;
}

function getDescription($conn,$remedyN)
{

 $returnArr = array();
    global $blanks;
 $query = "SELECT remedy_full_name FROM remedies WHERE remedy_name ='".$remedyN."'";     

    $result=runQuery($query,$conn); 
    if(noError($result)){
      $res =[];
      while($row=mysqli_fetch_assoc($result["dbResource"])){

        $res=$row;
      }
        
              $returnArr["errCode"]=-1;
         $returnArr["errMsg"]=$res['remedy_full_name'];
  
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching complaints data";
    }
   // printArr($returnArr);
    return $returnArr;
}
function listCurrency($conn){
	  $returnArr = array();
	  $res = array();
	  $query = "Select * From currency_value";

	  $query = runQuery($query, $conn);

	if(noError($query)){
	   while($row=mysqli_fetch_assoc($query["dbResource"])){
	    $res[$row['id']] = $row;
	  }
	  $returnArr['errCode'][-1] = -1;
	  $returnArr['errMsg'] = $res;     
	}else{
	  $returnArr['errCode'][2] = 2;
	  $returnArr['errMsg'] = $query['errMsg'];
	}

return $returnArr;
}
  function getRemedyDescription($conn,$remedyN)
  {
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM remedies WHERE remedy_name='".$remedyN."'";
   
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching remedies data";
    }

    return $returnArr;
  }

  function addPrescribtion($userInfo,$conn){
  	global $blanks;
    $returnArr = array();
    if($userInfo['preference']==1){
    	$prescribe='primary_prescription';
    }else if($userInfo['preference']==2){
    	$prescribe='second_prescription';
    }else if($userInfo['preference']==3){
    	$prescribe='third_prescription';
    }
     if($userInfo['followup']=='week'){
      $date = strtotime("+7 day");
      $followup_date= date('Y-m-d H:i:s', $date);
    }else if($userInfo['followup']=='month'){
      $date = strtotime("+1 month");
      $followup_date= date('Y-m-d H:i:s', $date);
    }else if($userInfo['followup']=='threemonth'){
      $date = strtotime("+3 month");
      $followup_date= date('Y-m-d H:i:s', $date);
    }
if($userInfo['preference']==1){
  $updateQuery = "UPDATE doctors_patient_cases SET ".$prescribe."='".$userInfo['remedyName']."',followup_date='".$followup_date."',followup_duration='".$userInfo['followup']."',  precribe_comments='".$userInfo['precribe_comments']."', consult_type='".$userInfo['consult_type']."', periodicity='".$userInfo['periodicity']."', currency='".$userInfo['currency']."', charges='".$userInfo['charges']."' WHERE id=".$userInfo['case_id'];
}else{
  $updateQuery = "UPDATE doctors_patient_cases SET ".$prescribe."='".$userInfo['remedyName']."',  precribe_comments='".$userInfo['precribe_comments']."', consult_type='".$userInfo['consult_type']."', periodicity='".$userInfo['periodicity']."', currency='".$userInfo['currency']."', charges='".$userInfo['charges']."' WHERE id=".$userInfo['case_id'];
}
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="prescription added successfully";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to add prescription".mysqli_error($conn);
    }
  return $returnArr;
  }
  function addPrescribtionDose($userInfo,$conn){
    global $blanks;
    $returnArr = array();
   
   $updateQuery = "UPDATE doctors_patient_cases SET potency='".$userInfo['potency']."', dosage='".$userInfo['dosage']."', status=1  WHERE id=".$userInfo['case_id'];
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="prescription dose added successfully";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to add prescription dose".mysqli_error($conn);
    }
  return $returnArr;
  }
  /*, potency='".$userInfo['potency']."', dosage='".$userInfo['dosage']."'*/
?>