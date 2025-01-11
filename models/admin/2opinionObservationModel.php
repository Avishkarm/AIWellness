<?php 
function getAllObservations($conn, $que_status=1){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM 2opinion_observation WHERE status='".$que_status."'";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["ob_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching 2nd opinion observations";
    }

    return $returnArr;
  }

    function addNewObservations($newQuestionConclusion,$newQuestionFG,$newQuestionFP,$newQuestionFE,$newQuestionSG,$newQuestionSP,$newQuestionSE,$newQuestionFHS,$newQuestionSHS,$newQuestionGetMedicine, $conn){
    global $blanks;

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $returnArr = array();

    $insertQuery = "INSERT INTO `2opinion_observation` (`conclusion`, `fh_g`, `fh_p`, `fh_e`,`sh_g`, `sh_p`, `sh_e`,`fh_s`, `sh_s`, `test_case`) VALUES ('".$newQuestionConclusion."', '".$newQuestionFG."', '".$newQuestionFP."','".$newQuestionFE."','".$newQuestionSG."','".$newQuestionSP."','".$newQuestionSE."','".$newQuestionFHS."','".$newQuestionSHS."','".$newQuestionGetMedicine."')";
    $result = runQuery($insertQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=mysqli_insert_id($conn);
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error inserting 2nd opinion observation";
    }

    return $returnArr;
  }

  function editObservations($newQuestionConclusion,$newQuestionFG,$newQuestionFP,$newQuestionFE,$newQuestionSG,$newQuestionSP,$newQuestionSE,$newQuestionFHS,$newQuestionSHS,$newQuestionGetMedicine,$questionId, $conn){
    global $blanks;
    $returnArr = array();

   $updateQuery = "UPDATE 2opinion_observation SET conclusion='".$newQuestionConclusion."',fh_g='".$newQuestionFG."',fh_p='".$newQuestionFP."',fh_e='".$newQuestionFE."',sh_g='".$newQuestionSG."',sh_p='".$newQuestionSP."',sh_e='".$newQuestionSE."',fh_s='".$newQuestionFHS."',sh_s='".$newQuestionSHS."', test_case='".$newQuestionGetMedicine."' WHERE ob_id='".$questionId."'";
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="2nd opinion observation succesfully updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="2nd opinion observation update failed";
    }

    return $returnArr;
  }

  function deleteObservations($questionId, $conn){
    global $blanks;
    $returnArr = array();

    $deleteQuery = "UPDATE 2opinion_observation SET status=0 WHERE ob_id='".$questionId."'";
    $result = runQuery($deleteQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="2nd opinion observation deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error deleting 2nd opinion observation";
    }

    return $returnArr;
  }
?>