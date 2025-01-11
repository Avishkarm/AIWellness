<?php

function addUserPersonalInfo($userInfo,$conn){
	      global $blanks;
      
      $returnArr = array();
      $values=array();
      $userProg=0;
      $totProg=17;
      //initializing the query string variables
      $query = "UPDATE users"; 
      //printArr($userInfo);
      //customizing the values array
      /*if(isset($userInfo["user_email"]) && !(in_array($userInfo["user_email"], $blanks))){
        $values["user_email"] = $userInfo["user_email"];
        $userProg++;
      }*/
      if(isset($userInfo["user_marital_status"]) && !(in_array($userInfo["user_marital_status"], $blanks))){
        $values["user_marital_status"] = $userInfo["user_marital_status"];
      }
      if(isset($userInfo["user_occ"]) && !(in_array($userInfo["user_occ"], $blanks))){
        $values["user_occ"] = $userInfo["user_occ"];
      }
      if(isset($userInfo["user_system"]) && !(in_array($userInfo["user_system"], $blanks))){
        $values["user_system"] = $userInfo["user_system"];
      }
      if(isset($userInfo["user_job_position"]) && !(in_array($userInfo["user_job_position"], $blanks))){
        $values["user_job_position"] = $userInfo["user_job_position"];
      }
      if(isset($userInfo["user_job_no"]) && !(in_array($userInfo["user_job_no"], $blanks))){
        $values["user_job_no"] = $userInfo["user_job_no"];
      }
      if(isset($userInfo["user_work"]) && !(in_array($userInfo["user_work"], $blanks))){
        $values["user_work"] = $userInfo["user_work"];
      } 
      if(isset($userInfo["user_company"]) && !(in_array($userInfo["user_company"], $blanks))){
        $values["user_company"] = date("Y-m-d", strtotime($userInfo["user_company"]));
      }      
      if(isset($userInfo["user_promotion"]) && !(in_array($userInfo["user_promotion"], $blanks))){
        $values["user_promotion"] = $userInfo["user_promotion"];
      }   
      if(isset($userInfo["user_education"]) && !(in_array($userInfo["user_education"], $blanks))){
        $values["user_education"] = $userInfo["user_education"];
      }
      if(isset($userInfo["user_address"]) && !(in_array($userInfo["user_address"], $blanks))){
        $values["user_address"] = $userInfo["user_address"];
      }
      if(isset($userInfo["user_skype"]) && !(in_array($userInfo["user_skype"], $blanks))){
        $values["user_skype"] = $userInfo["user_skype"];
      }
      if(isset($userInfo["user_ref"]) && !(in_array($userInfo["user_ref"], $blanks))){
        $values["user_ref"] = $userInfo["user_ref"];
      }
      
      //printArr($values);
      $percentProg=floor(($userProg/$totProg)*100);

      $values['profProgress']=$percentProg;
      //looping thru the col names and values arrays to for related query strings
      $colNamesStr = ""; $valuesStr = ""; $updateStr = "";
      foreach($values as $colName=>$val){             
        if($colName != "user_email"){
          if(!in_array($updateStr, $blanks))
            $updateStr .= ",";
          $updateStr .= cleanQueryParameter($conn,$colName)."='".cleanQueryParameter($conn,$val)."'";
        }
      }
      
      $query .= " SET ".$updateStr." WHERE user_id='".$userInfo["user_id"]."'and user_type_id=".$userInfo["user_type"];
      //run the query and return success or failure

      $result = runQuery($query, $conn);
      
      if(noError($result)){
        $returnArr["errCode"][-1] = -1;
        $returnArr["errMsg"] = "Personal Info Succesfully Added/Edited";

      } else {
        $returnArr["errCode"][5] = 5;
        $returnArr["errMsg"] = "Personal Info Add/Edit FAILED: ".$result["errMsg"]; 
      }
      //printArr($returnArr);
      return $returnArr;
}

function addStepNo($doctor_id,$patient_id,$step_no,$conn){
	  global $blanks;
	  $returnArr = array();
	$insertquery = "INSERT INTO `doctors_patient_cases`(`doctor_id`,`patient_id`,`step_no`) VALUES (".$doctor_id.",".$patient_id.",".$step_no.")";
	  $result = runQuery($insertquery, $conn);
	  if(noError($result)){
	    $returnArr["errCode"][-1]=-1;
	    $returnArr["errMsg1"]="patient added successfully";
	    $returnArr["errMsg"]=mysqli_insert_id($conn);
	  }else{
	    $returnArr["errCode"][1]= 1;
	    $returnArr["errMsg"]=" Insertion failed".mysqli_error();
	  }
	  return $returnArr;
}
function updateStepNo($case_id,$step_no,$conn){
      global $blanks;
    $returnArr = array();
   $updateQuery = "UPDATE doctors_patient_cases SET step_no='".$step_no."' WHERE id=".$case_id;
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="patient edited successfully";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to edit patient".mysqli_error($conn);
    }
  return $returnArr;
}

function getAllSensations($conn, $SensationStatus=1){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM sensation WHERE sensation_status='".$SensationStatus."'";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["sensationId"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching sensations data";
    }

    return $returnArr;
  }
  function getAllModalities($conn, $ModalitiesStatus=1){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM modalities WHERE modalities_status='".$ModalitiesStatus."'";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["modalitiesId"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching Modalities data";
    }

    return $returnArr;
  }

  function addStep2MainComplaint($userInfo,$conn){
    global $blanks;
    $returnArr = array();
    $updateQuery = "UPDATE doctors_patient_cases SET complaint_name='".$userInfo['complaint']."', other_complaint='".$userInfo['other_complaint']."', sensation='".$userInfo['sensation']."', modalities='".$userInfo['modalities']."', pathalogical_process='".$userInfo['pathalogical_process']."', path_pros_image='".$userInfo['path_pros_image']."', past_history='".$userInfo['past_history']."', family_hist_satus='".$userInfo['family_hist_satus']."', family_history='".$userInfo['family_history']."', prob_duration='".$userInfo['prob_duration']."' WHERE id=".$userInfo['case_id'];
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="patient edited successfully";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to edit patient".mysqli_error($conn);
    }
  return $returnArr;
  }

  function addStep5FormInfo($userInfo,$step5CaseData,$conn){
    global $blanks;
    $returnArr = array();
    $updateQuery = "UPDATE doctors_patient_cases SET face_type='".$userInfo['face_type']."', height='".$userInfo['height']."', height_unit='".$userInfo['height_unit']."', feel_strange_mind_body='".$userInfo['feel_strange_mind_body']."', material_attach='".$userInfo['material_attach']."', friends_attach='".$userInfo['friends_attach']."', family_attach='".$userInfo['family_attach']."', colleagues_attach='".$userInfo['colleagues_attach']."', describe_yourself='".$userInfo['describe_yourself']."', mental_status='".$userInfo['mental_status']."', close_img='".$userInfo['close_img']."', full_img='".$userInfo['full_img']."', nose='".$userInfo['nose']."', nails='".$userInfo['nails']."', fingers='".$userInfo['fingers']."', toes='".$userInfo['toes']."', step5CaseData='".$step5CaseData."' WHERE id=".$userInfo['case_id'];
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="patient edited successfully";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to edit patient".mysqli_error($conn);
    }
  return $returnArr;
  }


  function createBlankSheetRubrics($case_id,$patient_id,$conn){
  $returnArr=array();
  
$newCase['caseId']=$case_id;
  $newCase['usergroup']="Doctor_Only";
  $userInfoDt = getUserInfoWithUserId1($patient_id, $conn);
  $userInfoDt = $userInfoDt['errMsg'];
  $newCase['user_id']=$patient_id;
  $newCase['user_email']=$userInfoDt["user_email"];
  $newCase['gender']=$userInfoDt["user_gender"];
  if(calcAge($userInfo["user_dob"]) > 21){
    $newCase['age']="Adult";
  }else{
    $newCase['age']="Minor";
  }

  $resultRubrics=insertBlankSheetRubrics($newCase,$conn);
  if(noError($resultRubrics)){
    $returnArr['errMsg']="Created rubrics Blank Sheet Successfully";
    $returnArr['errCode'][-1]=-1;
    $returnArr['rowCnt']=$resultRubrics['rowCnt'];
  }else{
    $returnArr['errMsg']="Error in creating rubrics Blank Sheet".$resultRubrics['errMsg'];
    $returnArr['errCode'][1]=1;
  }
  return $returnArr;
}

  function insertBlankSheetRubrics($newCaseInfo,$conn){
    global $blanks;
    $returnArr = array();
    /*Inserting 16PF in BlankSHeet*/

if($newCaseInfo['gender']=='Transgender'){
     $query = sprintf("INSERT INTO kes_case_details (
     KES_case_id,user_id,user_email,q_type,question_id,
     question_name,hasPuzzles,noOfPuzzleQuestions,secondsPerPuzzleQuestion,
     low_score_remedies,med_score_remedies,high_score_remedies,
     q_help_text,multiChoice,videoURL,caseType)
     SELECT '%s','%s','%s',0, kq.question_id, 
     kq.question_name,kq.has_puzzle,kq.noOfPuzzleQuestions,
     kq.secondsPerPuzzleQuestion,kq.low_score_remedies,kq.med_score_remedies,
     kq.high_score_remedies,kq.help_text,kq.multiChoice,kq.video_url,'1'
     FROM   `kes_questions` kq
     INNER JOIN rubrics r
     ON ( r.rubric_id = kq.rubric_id
     AND ( r.usergroup = 'Both'
     OR r.usergroup = '%s' ) 
     AND ( r.age = 'Both'
     OR r.age = '%s' )
     AND r.rubric_status = 1
     )
     INNER JOIN kes_answer_options kao
     ON ( ( kq.question_id = kao.qid
     OR kq.has_puzzle = 1 )
     AND ( kao.usergroup = 'Both'
     OR kao.usergroup = '%s' )
     AND ( r.age = 'Both'
     OR r.age = '%s' ) ) GROUP BY  kq.question_id",
     $newCaseInfo["caseId"],$newCaseInfo['user_id'],$newCaseInfo['user_email'],$newCaseInfo['usergroup'],$newCaseInfo['age'],$newCaseInfo['usergroup'],$newCaseInfo['age']    
     );
   }else{
    $query = sprintf("INSERT INTO kes_case_details (
     KES_case_id,user_id,user_email,q_type,question_id,
     question_name,hasPuzzles,noOfPuzzleQuestions,secondsPerPuzzleQuestion,
     low_score_remedies,med_score_remedies,high_score_remedies,
     q_help_text,multiChoice,videoURL,caseType)
     SELECT '%s','%s','%s',0, kq.question_id, 
     kq.question_name,kq.has_puzzle,kq.noOfPuzzleQuestions,
     kq.secondsPerPuzzleQuestion,kq.low_score_remedies,kq.med_score_remedies,
     kq.high_score_remedies,kq.help_text,kq.multiChoice,kq.video_url,'1'
     FROM   `kes_questions` kq
     INNER JOIN rubrics r
     ON ( r.rubric_id = kq.rubric_id
     AND ( r.usergroup = 'Both'
     OR r.usergroup = '%s' ) 
     AND ( r.gender = 'Both'
     OR r.gender = '%s' ) 
     AND ( r.age = 'Both'
     OR r.age = '%s' )
     AND r.rubric_status = 1
     )
     INNER JOIN kes_answer_options kao
     ON ( ( kq.question_id = kao.qid
     OR kq.has_puzzle = 1 )
     AND ( kao.usergroup = 'Both'
     OR kao.usergroup = '%s' ) 
     AND ( kao.gender = 'Both'
     OR kao.gender = '%s' ) 
     AND ( r.age = 'Both'
     OR r.age = '%s' ) ) GROUP BY  kq.question_id",
     $newCaseInfo["caseId"],$newCaseInfo['user_id'],$newCaseInfo['user_email'],$newCaseInfo['usergroup'],$newCaseInfo['gender'],$newCaseInfo['age'],$newCaseInfo['usergroup'],$newCaseInfo['gender'],$newCaseInfo['age']    
     );
   }
    $resultQuery=runQuery($query,$conn);

    if(noError($resultQuery)){
      $returnArr["errCode"][-1] = -1;
      $returnArr["errMsg"] = "Succesfully Created BlankSHeet";
      $returnArr['rowCnt']=mysqli_num_rows($resultQuery['dbResource']);

    }else{
      $returnArr["errCode"][5] = 5;
      $returnArr["errMsg"] = "Not Created BlankSHeet".$resultQuery['errMsg'];
    }
    return $returnArr;
  }

      function getsectionrubrics($type, $usergroup,$gender,$age, $conn, $qType=0, $quesId="") {
      global $blanks;
      $returnArr = array();
      if($usergroup=='Doctor_Only'){
        $order="doctors_order";
      }
       if($usergroup=='Patient_Only'){
        $order="patients_order";
      }
    $query="SELECT * FROM (rubrics LEFT OUTER JOIN kes_questions ON rubrics.rubric_id=kes_questions.rubric_id and rubrics.rubric_status='1' AND rubrics.type='".$type."' ) LEFT OUTER JOIN kes_answer_options ON kes_questions.question_id=kes_answer_options.qid where rubrics.rubric_status='1' AND rubrics.type='".$type."' AND rubrics.usergroup='Both' or rubrics.usergroup='".$usergroup."'AND kes_answer_options.usergroup='Both' or kes_answer_options.usergroup='".$usergroup."' AND rubrics.gender='Both' or rubrics.gender='".$gender."'AND kes_answer_options.gender='Both' or kes_answer_options.gender='".$gender."' AND rubrics.age='Both' or rubrics.age='".$age."'AND kes_answer_options.age='Both' or kes_answer_options.age='".$age."' AND rubrics.type='".$type."' ORDER BY ".$order;
      
      $result=runQuery($query, $conn);
      if(noError($result)){
        $res=array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res[$row["rubric_id"]][$row["ans_label"]]=$row;
        }
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching case sheet data";
      }

      return $returnArr;
    }

    function updateStepAns($ansDetails,$conn){
      global $blanks;
    $returnArr = array();
      $updateQuery = "UPDATE kes_case_details SET aid='".$ansDetails['a_id']."', ans_label='".$ansDetails['ans_label']."', answerRemedies='".$ansDetails['answerRemedies']."' WHERE KES_case_id=".$ansDetails['case_id']." and user_id=".$ansDetails['patient_id']." and question_id=".$ansDetails['q_id'];
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="step ans updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to update step ans".mysqli_error($conn);
    }
  return $returnArr;
    }

  function getAllOccupations($conn, $occupationId){   
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM occupations";
    if(!in_array($occupationId, $blanks)){
      $query .= " WHERE occupation_id='".$occupationId."'";
    }   
    $result = runQuery($query, $conn);


    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["occupation_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching occupations data";
    }

    return $returnArr;
  }
  function getAllSystems($conn, $systemId="") {

    $returnArr = array();
    global $blanks;   
    $query = "SELECT * FROM systems";
    if(!in_array($systemId, $blanks)){
      $query .= " WHERE system_id='".$systemId."'";
    }

    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["system_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching systems data";
    }

    return $returnArr;
  }
function udateUserInfoStep1($column_name,$val,$patient_id,$conn){
   global $blanks;
    $returnArr = array();
    $updateQuery = "UPDATE users SET ".$column_name."='".$val."' WHERE user_id=".$patient_id;
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="step ans updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to update step ans".mysqli_error($conn);
    }
  return $returnArr;
}

function getComplaintDetailsById($complaint,$conn) {
      global $blanks;
      $returnArr = array();

      $query="SELECT * FROM complaint_info where id=".$complaint ;
      
      $result=runQuery($query, $conn);
      if(noError($result)){
        $res=array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res=$row;          
        }
        $miasm=$res['miasm'];
        $embryologcial=$res['embryologcial'];
        $system=$res['system'];
        $organ=$res['organ'];
        $subOrgan=$res['subOrgan'];
        $res1['m_id']=get2opinionMiasm($conn, $miasm);
        $res1['e_id']=get2opinionEmbryo($conn, $embryologcial);
        $res1['system_id']=get2opinionSystem($conn, $system);
        $res1['o_id']=get2opinionOrgan($conn, $organ);
        $res1['s_id']=get2opinionSuborgan($conn, $subOrgan);
        /* $res1[$key]['m_id']=get2opinionMiasm($conn, $miasm);
        $res1[$key]['e_id']=get2opinionEmbryo($conn, $embryologcial);
        $res1[$key]['system_id']=get2opinionSystem($conn, $system);
        $res1[$key]['o_id']=get2opinionOrgan($conn, $organ);
        $res1[$key]['s_id']=get2opinionSuborgan($conn, $subOrgan);*/
        //printArr($res1);
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]= $res1;
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching case sheet data";
      }

      return $res1;
    }
function udateStepsCaseInfo($column_name,$val,$case_id,$conn){
   global $blanks;
    $returnArr = array();
   //$updateQuery = "UPDATE doctors_patient_cases SET ".$column_name."='".$val."' WHERE id=".$case_id;
    $updateQuery = 'UPDATE doctors_patient_cases SET '.$column_name.'="'.$val.'" WHERE id='.$case_id;
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="step ans updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to update step ans".mysqli_error($conn);
    }
  return $returnArr;
}

 function getAllCaseSheetDetails($conn, $case_id){   
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM kes_case_details WHERE KES_case_id=".$case_id;
    $result = runQuery($query, $conn);


    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching caseDetails data";
    }

    return $returnArr;
  }
  function getAllCaseDetails($conn, $case_id){   
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM doctors_patient_cases WHERE id=".$case_id;
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
      $returnArr["errMsg"]="Error fetching case details";
    }

    return $returnArr;
  }

  function getComplaintsSystem($conn, $comp){
    $returnArr = array();
    global $blanks; 
    $query="SELECT * FROM complaint_info WHERE Diagnostic_term ='".$comp."'";
    $result = runQuery($query, $conn);
        //printArr($result);
      if(noError($result)){
        $res = array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res=$row;
        }
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      }else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching complaints data";
      }
    return $returnArr;
  }
?>