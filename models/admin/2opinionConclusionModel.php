<?php 
function getAllQuestions($conn, $que_status=1){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM 2opinion_questions WHERE que_status='".$que_status."'";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["quest_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching rubrics data";
    }

    return $returnArr;
  }

    function addNewQuestion($newQuestionName,$newQuestionConclusion,$newQuestionFG,$newQuestionFP,$newQuestionFE,$newQuestionSG,$newQuestionSP,$newQuestionSE,$newQuestionFHS,$newQuestionSHS,$newQuestionGetMedicine, $conn){
    global $blanks;

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $returnArr = array();

    $insertQuery = "INSERT INTO `2opinion_questions` ( `title`,`conclusion`, `fh_g`, `fh_p`, `fh_e`,`sh_g`, `sh_p`, `sh_e`,`fh_s`, `sh_s`, `get_medicine_status`) VALUES ( '".$newQuestionName."', '".$newQuestionConclusion."', '".$newQuestionFG."', '".$newQuestionFP."','".$newQuestionFE."','".$newQuestionSG."','".$newQuestionSP."','".$newQuestionSE."','".$newQuestionFHS."','".$newQuestionSHS."','".$newQuestionGetMedicine."')";
    $result = runQuery($insertQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=mysqli_insert_id($conn);
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error inserting question";
    }

    return $returnArr;
  }

  function editQuestion($newQuestionName,$newQuestionConclusion,$newQuestionFG,$newQuestionFP,$newQuestionFE,$newQuestionSG,$newQuestionSP,$newQuestionSE,$newQuestionFHS,$newQuestionSHS,$newQuestionGetMedicine,$questionId, $conn){
    global $blanks;
    $returnArr = array();

   $updateQuery = "UPDATE 2opinion_questions SET title='".$newQuestionName."',conclusion='".$newQuestionConclusion."',fh_g='".$newQuestionFG."',fh_p='".$newQuestionFP."',fh_e='".$newQuestionFE."',sh_g='".$newQuestionSG."',sh_p='".$newQuestionSP."',sh_e='".$newQuestionSE."',fh_s='".$newQuestionFHS."',sh_s='".$newQuestionSHS."', get_medicine_status='".$newQuestionGetMedicine."' WHERE quest_id='".$questionId."'";
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="question succesfully updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="question update failed";
    }

    return $returnArr;
  }

  function deleteQuestion($questionId, $conn){
    global $blanks;
    $returnArr = array();

    $deleteQuery = "UPDATE 2opinion_questions SET que_status=0 WHERE quest_id='".$questionId."'";
    $result = runQuery($deleteQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="Question deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error deleting Question";
    }

    return $returnArr;
  }
?>