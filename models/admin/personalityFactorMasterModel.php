<?php

function getPersonalityFactors($conn) {
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM personality_factors_master WHERE factor_status=1";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["factor_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching personality factors data";
    }

    return $returnArr;
 }

function addNewPersonalityFactor($newFactorName, $newFactorTitle, $newHighScoreDescr, $newHighScoreRems, $newLowScoreDescr, $newLowScoreRems, $conn){
	global $blanks;

	$returnArr = array();

	$insertQuery = "INSERT INTO `personality_factors_master` (`factor_id`, `factor_name`,`factor_title`, `created_on`, `updated_on`, `updated_by`, `low_score_remedies`, `high_score_remedies`, `low_score_description`, `high_score_description`) VALUES (NULL, '".$newFactorName."','".$newFactorTitle."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 'admin', '".$newLowScoreRems."', '".$newHighScoreRems."', '".$newLowScoreDescr."', '".$newHighScoreDescr."');";
	$result = runQuery($insertQuery, $conn);

	if(noError($result)){
	  $returnArr["errCode"][-1]=-1;
	  $returnArr["errMsg"]=mysqli_insert_id($conn);
	} else {
	  $returnArr["errCode"][1]=1;
	  $returnArr["errMsg"]="Error inserting factor";
	}

	return $returnArr;
}


function editPersonalityFactor($newFactorName, $newFactorTitle, $newLowScoreDescr, $newHighScoreDescr, $newLowScoreRemedies, $newHighScoreRemedies, $factorId, $conn){
    global $blanks;
    $returnArr = array();

    $updateQuery = "UPDATE personality_factors_master SET factor_name='".$newFactorName."', factor_title='".$newFactorTitle."', updated_on=CURRENT_TIMESTAMP, low_score_description='".$newLowScoreDescr."', high_score_description='".$newHighScoreDescr."', low_score_remedies='".$newLowScoreRemedies."', high_score_remedies='".$newHighScoreRemedies."' WHERE factor_id='".$factorId."'";
    $result = runQuery($updateQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="PF succesfully updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="PF update failed";
    }

    return $returnArr;
}

  function deletePersonalityFactor($factorId, $conn){
    global $blanks;
    $returnArr = array();

    $deleteQuery = "UPDATE personality_factors_master SET factor_status=0 WHERE factor_id='".$factorId."'";
    $result = runQuery($deleteQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="Factor deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error deleting factor";
    }

    return $returnArr;
  }
?>