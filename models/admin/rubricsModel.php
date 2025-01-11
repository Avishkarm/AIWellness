<?php
function getAllRubrics($conn, $rubricStatus=1){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM rubrics WHERE rubric_status='".$rubricStatus."'";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["rubric_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching rubrics data";
    }

    return $returnArr;
}

function addNewRubric($newRubricName,$newRubrictype,$newRubricusergroup,$newRubricGender,$newRubricAge,$newRubricDoctorOrder,$newRubricPatientOrder, $conn){
    global $blanks;

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $returnArr = array();

    $insertQuery = "INSERT INTO `rubrics` ( `rubric_name`,`type`, `usergroup`,`gender`,`age`,`doctors_order`,`patients_order`, `created_on`, `updated_on`, `updated_by`) VALUES ( '".$newRubricName."', '".$newRubrictype."', '".$newRubricusergroup."','".$newRubricGender."','".$newRubricAge."', ".$newRubricDoctorOrder.", ".$newRubricPatientOrder.", '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 'admin')";
    $result = runQuery($insertQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=mysqli_insert_id($conn);
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error inserting rubric";
    }

    return $returnArr;
}
function editRubric($newRubricName,$newRubrictype,$newRubricusergroup,$newRubricGender,$newRubricAge, $rubricId,$newRubricDoctorOrder,$newRubricPatientOrder, $conn){
  global $blanks;
  $returnArr = array();

  $updateQuery = "UPDATE rubrics SET rubric_name='".$newRubricName."',type='".$newRubrictype."',usergroup='".$newRubricusergroup."',gender='".$newRubricGender."',age='".$newRubricAge."',doctors_order=".$newRubricDoctorOrder.",patients_order=".$newRubricPatientOrder.", updated_on='".date('Y-m-d H:i:s')."' WHERE rubric_id='".$rubricId."'";
  $result = runQuery($updateQuery, $conn);
  if(noError($result)){
    $returnArr["errCode"][-1]=-1;
    $returnArr["errMsg"]="Rubric succesfully updated";
  } else {
    $returnArr["errCode"][1]=1;
    $returnArr["errMsg"]=mysqli_error($conn);
  }

  return $returnArr;
}

  function deleteRubric($rubricId, $conn){
    global $blanks;
    $returnArr = array();

  $deleteQuery = "UPDATE rubrics SET rubric_status=0 WHERE rubric_id='".$rubricId."'";
    $result = runQuery($deleteQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="Rubric deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error deleting rubric";
    }

    return $returnArr;
  }

      function updatePrimaryQuestion($rubricId, $primaryQuestionId, $primaryQuestionText, $conn, $videoURL, $helpText,$multiChoice, $primaryQuestionHasPuzzles, $primaryQuestionNoOfPuzzleQuestions, $primaryQuestionPuzzleQuestionsTimer, $primaryQuestionLowPuzzleScoreRemedies, $primaryQuestionMedPuzzleScoreRemedies, $primaryQuestionHighPuzzleScoreRemedies){
      global $blanks;
    if($primaryQuestionHasPuzzles=="")
    {
      $primaryQuestionHasPuzzles=0;
    } 
     
      $primaryQuestionLowPuzzleScoreRemedies=implode(",",array_unique(explode(',',$primaryQuestionLowPuzzleScoreRemedies,-1)));
      $primaryQuestionMedPuzzleScoreRemedies=implode(",",array_unique(explode(',',$primaryQuestionMedPuzzleScoreRemedies,-1)));
      $primaryQuestionHighPuzzleScoreRemedies=implode(",",array_unique(explode(',',$primaryQuestionHighPuzzleScoreRemedies,-1)));      

      $query = "REPLACE INTO kes_questions VALUES('".cleanQueryParameter($conn, $primaryQuestionId)."', '".cleanQueryParameter($conn, $primaryQuestionText)."', '".cleanQueryParameter($conn, $rubricId)."', 0, '".cleanQueryParameter($conn, $helpText)."','".cleanQueryParameter($conn, $multiChoice)."', '".cleanQueryParameter($conn, $videoURL)."', '".cleanQueryParameter($conn, $primaryQuestionHasPuzzles)."', '".cleanQueryParameter($conn, $primaryQuestionNoOfPuzzleQuestions)."', '".cleanQueryParameter($conn, $primaryQuestionPuzzleQuestionsTimer)."', '".cleanQueryParameter($conn, $primaryQuestionLowPuzzleScoreRemedies)."', '".cleanQueryParameter($conn, $primaryQuestionMedPuzzleScoreRemedies)."', '".cleanQueryParameter($conn, $primaryQuestionHighPuzzleScoreRemedies)."')";   
      $result = runQuery($query, $conn);
      
      if(noError($result)){
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]="Primary Question Updated";
        $returnArr["qid"]=mysqli_insert_id($conn);
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Primary Question Update Failed";
      }
     
      return $returnArr;
    }
   function updateAnswers($rubricId, $primaryQuestionId, $answerOptions, $conn, $new=false) {
    //printArr($answerOptions);die;
      global $blanks; 
      $qIndex = 0;
      if($new) $qIndex="new"; 
      $answersQuery = "REPLACE INTO kes_answer_options VALUES";
      $answersQueryValuesString = "";
      foreach($answerOptions[$qIndex] as $answerId=>$answerDetails) 
      {

       // printArr(explode(',',$answerDetails["answerRemedies"]),0);
        $answerDetails["answerRemedies"]=implode(",",array_unique(explode(',',$answerDetails["answerRemedies"])));
        //printArr($answerDetails["answerRemedies"]);
        if(isset($answerDetails["followupQuestion"])){
          
          $hasFuq = 1;
          $fuqId = array_keys($answerDetails["followupQuestion"]);
          $fuqId = $fuqId[0];
          $fuqLabel = $answerDetails["followupQuestion"][$fuqId]["questionLabel"];
          if($fuqId=="new")
            $fuqId = "";
          $insertFUQQuery = "REPLACE INTO kes_questions VALUES('".cleanQueryParameter($conn, $fuqId)."', '".cleanQueryParameter($conn,$fuqLabel)."', '".cleanQueryParameter($conn, $rubricId)."', 1, '', '', 0, '','','','','')";
          $insertFUQResult = runQuery($insertFUQQuery, $conn);
          if(noError($insertFUQResult)){
                  //get latest inserted fuq id
            if($fuqId==""){
              $fuqId = mysqli_insert_id($conn);
              $fuqIndex = "new";
            } else {
              $fuqIndex = $fuqId;
            }
                  //put in follow up question answers
            $fuqAnswersQuery = "REPLACE INTO kes_answer_options VALUES";
            $fuqAnswersQueryValuesString = ""; $answerCount=1;
            foreach($answerDetails["followupQuestion"][$fuqIndex]["answerOptions"] as $fuqAnswerOptId=>$fuqAnswerOptDetails) {
              $fuqAnswerOptDetails["answerRemedies"]=implode(",",array_unique(explode(',',$fuqAnswerOptDetails["answerRemedies"],-1)));
              if(!in_array($fuqAnswersQueryValuesString, $blanks))
                $fuqAnswersQueryValuesString .= ",";
              $fuqAnswersQueryValuesString .= "('".cleanQueryParameter($conn,$fuqId)."', '".cleanQueryParameter($conn,$answerCount)."', '".cleanQueryParameter($conn,$fuqAnswerOptDetails["answerLabel"])."', 0, NULL, '".cleanQueryParameter($conn,$fuqAnswerOptDetails["answerRemedies"])."','".cleanQueryParameter($conn,$fuqAnswerOptDetails["usergroup"])."')";
              $answerCount++;
            }
            $fuqAnswersQuery .= $fuqAnswersQueryValuesString;
            $fuqAnswersResult = runQuery($fuqAnswersQuery, $conn);
            if(noError($fuqAnswersResult)){
                      //do nothing
            } else {
              printArr("Error inserting follow up question answers: ".$fuqAnswersResult["errMsg"]);
            }
          } else {
            printArr("Error inserting follow up question");
          }
        } else {
          
          $hasFuq = 0;
          $fuqId = "NULL";
        }
        if(!in_array($answersQueryValuesString, $blanks))
          $answersQueryValuesString .= ",";
        $answersQueryValuesString .= "('".cleanQueryParameter($conn,$primaryQuestionId)."', '".cleanQueryParameter($conn,$answerId)."', '".cleanQueryParameter($conn,$answerDetails["answerLabel"])."', '".$hasFuq."', ".$fuqId.", '".cleanQueryParameter($conn,$answerDetails["answerRemedies"])."','".cleanQueryParameter($conn,$answerDetails["usergroup"])."','".cleanQueryParameter($conn,$answerDetails["gender"])."','".cleanQueryParameter($conn,$answerDetails["age"])."')";
      }
      $answersQuery .= $answersQueryValuesString;

      $answersResult = runQuery($answersQuery, $conn);
      
      if(noError($answersResult)){
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]="Primary Question Answers Updated";
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Primary Question Answers Updating Failed: ".$answersResult["errMsg"];
      }
     
      return $returnArr;
    }
  //get primary question for this rubric id
  function getAllPrimaryQuestions($rubricId="", $conn){
    global $blanks;

    $query = "SELECT * FROM (kes_questions q LEFT OUTER JOIN kes_answer_options a ON q.question_id=a.qid) WHERE q.q_type=0";
    if(!in_array($rubricId, $blanks)){
      $query .= " AND rubric_id=".$rubricId;
    }
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[] = $row;
      }

      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching primary questions: ".$result["errMsg"];
    }

    return $returnArr;
  }
  function deleteAnswers($rubricId, $primaryQuestionId, $conn) {
          global $blanks;
      
      $query = "DELETE FROM kes_answer_options WHERE qid='".cleanQueryParameter($conn,$primaryQuestionId)."'";
      $result = runQuery($query, $conn);
      
      if(noError($result)){

        $query2 = "DELETE FROM kes_questions WHERE q_type=1 AND question_id NOT IN (SELECT fuq_id FROM kes_answer_options WHERE fuq_id is NOT NULL)";
        $result2 = runQuery($query2, $conn);
        if(noError($result2)){          
          $query3 = "DELETE FROM kes_answer_options WHERE qid NOT IN (SELECT question_id FROM kes_questions)";
          $result3 = runQuery($query3, $conn);
          if(noError($result3)){                  
            $returnArr["errCode"][-1]=-1;
            $returnArr["errMsg"]="Primary Question Answers Deleted";
          } else {
            $returnArr["errCode"][1]=1;
            $returnArr["errMsg"]="Primary Question FUQ Answers Deletion Failed";
          }
        } else {
          $returnArr["errCode"][1]=1;
          $returnArr["errMsg"]="Primary Question FUQ Deletion Failed";
        }
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Primary Question Answers Deletion Failed";
      }
      
      return $returnArr;
    }

        function getQuestionDetails($questionId, $questionType, $conn){
      global $blanks;
      
      $query = "SELECT * FROM (kes_questions q LEFT OUTER JOIN kes_answer_options a ON q.question_id=a.qid) WHERE q.q_type='".cleanQueryParameter($questionType)."'";
      if(!in_array($questionId, $blanks)){
        $query .= " AND q.question_id='".cleanQueryParameter($conn,$questionId)."'";  
      }
      $result = runQuery($query, $conn);
      
      if(noError($result)){
        $res = array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res[] = $row;
        }

        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching question details: ".$result["errMsg"];
      }
      
      return $returnArr;
    }

?>