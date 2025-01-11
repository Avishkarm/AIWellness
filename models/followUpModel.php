<?php 
function getAllfollowups($conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM `doctors_patient_cases` WHERE DATE(`followup_date`) = CURDATE() and followup_status!=closed";
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
      $returnArr["errMsg"]="Error fetching Modalities data";
    }

    return $returnArr;
  }
  function getfollowupsBefore($days,$conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

    //$query = "SELECT * FROM `doctors_patient_cases` WHERE DATE(`followup_date`) = CURDATE()";
    $query = "SELECT * FROM `doctors_patient_cases` WHERE DATE(`followup_date`) =  DATE_ADD(CURDATE(), INTERVAL +".$days." DAY) and followup_status!='closed'";
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
      $returnArr["errMsg"]="Error fetching Modalities data";
    }

    return $returnArr;
  }

  function getUpcomingfollowups($days,$doctor_id,$conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

    //$query = "SELECT * FROM `doctors_patient_cases` WHERE DATE(`followup_date`) = CURDATE()";
    //$query = "SELECT * FROM `doctors_patient_cases` WHERE DATE(`followup_date`) =  DATE_ADD(CURDATE(), INTERVAL +".$days." DAY) and followup_status!='closed'";
     //$query = "SELECT * FROM `doctors_patient_cases` WHERE DATE(`followup_date`) BETWEEN curdate() and curdate() + interval .$days." day and followup_status!='closed'";
    $cntQuery = "SELECT count(*) as countCases FROM `doctors_patient_cases` dp INNER JOIN users u WHERE u.user_id=dp.patient_id and DATE(dp.`followup_date`) BETWEEN curdate() and curdate() + interval ".$days." day and dp.followup_status!='closed' and dp.doctor_id=".$doctor_id;
    $countresult = runQuery($cntQuery, $conn);
    $row=mysqli_fetch_assoc($countresult["dbResource"]);
    //printArr($row);
    $count=$row['countCases'];

//    $query = "SELECT u.user_image,u.user_first_name,u.user_last_name,dp.id,dp.patient_id,dp.followup_date,dp.complaint_name FROM `doctors_patient_cases` dp INNER JOIN users u WHERE u.user_id=dp.patient_id and DATE(dp.`followup_date`) BETWEEN curdate() and curdate() + interval ".$days." day and dp.followup_status!='closed'";
    $query = "SELECT u.user_image,u.user_first_name,u.user_last_name,dp.id,dp.patient_id,dp.followup_date,dp.complaint_name FROM `doctors_patient_cases` dp INNER JOIN users u WHERE u.user_id=dp.patient_id and DATE(dp.`followup_date`) BETWEEN curdate() and curdate() + interval ".$days." day and dp.followup_status!='closed' and dp.doctor_id=".$doctor_id;
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["count"]=$count;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching Modalities data";
    }

    return $returnArr;
  }
  
  function updateFollowupDate($followup_date,$case_id,$followup_status,$conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

    $updateQuery = "UPDATE doctors_patient_cases SET followup_date='".$followup_date."',followup_status='".$followup_status."' WHERE id=".$case_id;
    $result = runQuery($updateQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="followup date updated successfully";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to update followup date".mysqli_error($conn);
    }

    return $returnArr;
  }
  function updateFollowupstatus($case_id,$followup_status,$conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

    $updateQuery = "UPDATE doctors_patient_cases SET followup_status='".$followup_status."' WHERE id=".$case_id;
    $result = runQuery($updateQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="followup status updated successfully";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to update followup date".mysqli_error($conn);
    }
//  printArr($returnArr);
    return $returnArr;
  }

  function getUserEmail($doctor_id,$conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

    $query = "SELECT user_email,user_first_name,user_last_name FROM `users` WHERE user_id =".$doctor_id;
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
      $returnArr["errMsg"]="Error fetching Modalities data";
    }
    //return $res['user_email']; 
    return $returnArr;
  }
  function getUserData($patient_id,$conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

    $query = "SELECT user_email,user_mob,user_first_name,user_last_name FROM `users` WHERE user_id =".$patient_id;
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
      $returnArr["errMsg"]="Error fetching Modalities data";
    }
    return $returnArr;
  }
  function getFollowUpScore($option)
  {
    $option=strtolower($option);
    if($option=='good'){
      return $option=1;
    }
    elseif($option=='bad'){
      return $option=-1;
    }else{
      return $option=0;
    }
  }


  function getFollowUpConclusionStatus($conn,$case_id,$user)
  {
    //$row=mysql_num_rows($result["dbResource"]);

      global $totalQuestions;
     $query="SELECT status  FROM follow_up WHERE case_id=".$case_id." and user_id='".$user."' ORDER BY id DESC LIMIT 0,2" ;
      
      $result=runQuery($query,$conn); 
  
      if(noError($result)){
       while($row1=mysqli_fetch_assoc($result["dbResource"]))
       {
        $res[]=$row1;
      }
      $row=mysqli_num_rows($result["dbResource"]);
         if($row>=2)
         {
          $returnArr["errCode"]=-1;
          $returnArr["errMsg"]=$res;
         }
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching questions data";
      }
//printArr($result);
      return $returnArr;
  }

    function saveFollowUp($conn,$case_id,$user,$FollowUp,$conclusion,$conclusion_status,$status,$followup_status)
  {

      $query = "INSERT INTO `follow_up`(`case_id`,`user_id`,`caseData`,`conclusion`,`conclusion_status`,`status`,`date`) VALUES (".$case_id.",'".$user."','".$FollowUp."','".$conclusion."','".$conclusion_status."','".$status."','".date('Y-m-d H:i:s')."')";
      //$query="INSERT INTO `ip_access`(`id`, `ip_address`) VALUES ('','192.168.0.1')";

    $result = runQuery($query, $conn);

    if(noError($result)){
      updateFollowupstatus($case_id,$followup_status,$conn);
     //$status=setFollowUpStatus($conn,$KES_case_id,2);
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=mysqli_insert_id($conn);
    }else{
      $returnArr["errCode"][8]= 8;
      $returnArr["errMsg1"]=" Insertion failed".mysqli_error($conn);
    }
      //printArr($returnArr);
    return $returnArr;
  }

  function getcaseFollowups($case_id,$conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM `follow_up` WHERE case_id =".$case_id;
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
      $returnArr["errMsg"]="Error fetching Modalities data";
    }

    return $returnArr;
  }

  /*function setFollowUpStatus($conn,$case_id,$followup_status){
    global $blanks;
    $returnArr = array();
    $patient_email;

    $updateQuery = "UPDATE KES SET followup_status='".$followup_status."'  WHERE KES_case_id=".$KES_case_id;
    $result = runTransactionedQuery($updateQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="updated foloow up status";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error Updating form_status";
    }
      //printArr($returnArr);
    return $returnArr;
  }*/

 
  function getCaseData($case_id,$conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

    //$query = "SELECT * FROM `doctors_patient_cases` WHERE case_id =".$case_id;
   //echo  $query ="INSERT into doctors_patient_cases (ref_case_id,patient_id,doctor_id,complaint_name,prob_duration,other_complaint,sensation,modalities,pathalogical_process,path_pros_image,past_history,family_hist_satus,family_history,face_type,close_img,full_img,nose,nails,fingers,toes,feel_strange_mind_body,describe_yourself,mental_status,height,height_unit,step5CaseData) values(".$case_id.", SELECT patient_id,patient_id,doctor_id,complaint_name,prob_duration,other_complaint,sensation,modalities,pathalogical_process,path_pros_image,past_history,family_hist_satus,family_history,face_type,close_img,full_img,nose,nails,fingers,toes,feel_strange_mind_body,describe_yourself,mental_status,height,height_unit,step5CaseData FROM doctors_patient_cases WHERE id=".$case_id.")"; 
    $query ="INSERT into doctors_patient_cases SELECT '',".$case_id.",patient_id,doctor_id,0,'','','',complaint_name,prob_duration,other_complaint,sensation,modalities,pathalogical_process,path_pros_image,past_history,family_hist_satus,family_history,'','','','','','','','','',0,face_type,close_img,full_img,nose,nails,fingers,toes,feel_strange_mind_body,'','','','','',describe_yourself,mental_status,height,height_unit,step5CaseData,'".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."',0 FROM doctors_patient_cases WHERE id=".$case_id;
   // die;
    $result = runQuery($query, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=mysqli_insert_id($conn);
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching Modalities data";
    }

    return $returnArr;
  }

  function getCaseSheetData($case_id,$conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM `kes_case_details` WHERE KES_case_id =".$case_id;
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
      $returnArr["errMsg"]="Error fetching Modalities data";
    }

    return $returnArr;
  }

  function createBlankSheet($kes_case_id,$value,$conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

   // $query ="INSERT into kes_case_details SELECT '',".$case_id.",patient_id,doctor_id,0,'','','',complaint_name,prob_duration,other_complaint,sensation,modalities,pathalogical_process,path_pros_image,past_history,family_hist_satus,family_history,'','','','','','','','','',0,face_type,close_img,full_img,nose,nails,fingers,toes,feel_strange_mind_body,'','','','','',describe_yourself,mental_status,height,height_unit,step5CaseData,'".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."',0 FROM doctors_patient_cases WHERE id=".$case_id;

     $query ="INSERT into kes_case_details (KES_case_id,user_id,user_email,q_type,question_id,aid,question_name,ans_label,answer_chosen,answerRemedies,created_on,updated_on,has_fuq,fuq_id,hasPuzzles,noOfPuzzleQuestions,secondsPerPuzzleQuestion,low_score_remedies,med_score_remedies,high_score_remedies,q_help_text,multiChoice,videoURL,caseType,user_answered) values('".$kes_case_id."',".$value['user_id'].",'".$value['user_email']."','".$value['q_type']."','".$value['question_id']."','".$value['aid']."','".$value['question_name']."','".$value['ans_label']."','".$value['answer_chosen']."','".$value['answerRemedies']."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','".$value['has_fuq']."','".$value['fuq_id']."','".$value['hasPuzzles']."','".$value['noOfPuzzleQuestions']."','".$value['secondsPerPuzzleQuestion']."','".$value['low_score_remedies']."','".$value['med_score_remedies']."','".$value['high_score_remedies']."','".$value['q_help_text']."','".$value['multiChoice']."','".$value['videoURL']."','".$value['caseType']."','".$value['user_answered']."')";
   // die;
    $result = runQuery($query, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=mysqli_insert_id($conn);
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching Modalities data";
    }

    return $returnArr;
  }

  //$query ="INSERT into doctors_patient_cases (ref_case_id,patient_id,doctor_id,complaint_name,prob_duration,other_complaint,sensation,modalities,pathalogical_process,path_pros_image,past_history,family_hist_satus,family_history,face_type,close_img,full_img,nose,nails,fingers,toes,feel_strange_mind_body,describe_yourself,mental_status,height,height_unit,step5CaseData) values(".$case_id.", SELECT patient_id,patient_id,doctor_id,complaint_name,prob_duration,other_complaint,sensation,modalities,pathalogical_process,path_pros_image,past_history,family_hist_satus,family_history,face_type,close_img,full_img,nose,nails,fingers,toes,feel_strange_mind_body,describe_yourself,mental_status,height,height_unit,step5CaseData FROM doctors_patient_cases WHERE id=".$case_id.")";

   //$query ="INSERT into doctors_patient_cases SELECT '',".$case_id.",patient_id,doctor_id,0,'','','',complaint_name,prob_duration,other_complaint,sensation,modalities,pathalogical_process,path_pros_image,past_history,family_hist_satus,family_history,'','','','','','','','','',0,face_type,close_img,full_img,nose,nails,fingers,toes,feel_strange_mind_body,'','','','','',describe_yourself,mental_status,height,height_unit,step5CaseData,'','',0 FROM doctors_patient_cases WHERE id=".$case_id;
?>