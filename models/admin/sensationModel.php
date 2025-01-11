<?php

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

  function addNewSensation($newSensationName,$newSensationRemedies, $conn){
    global $blanks;

    $returnArr = array();

    $insertQuery = "INSERT INTO `sensation` (`sensationId`, `sensationName`,`sensationRemedies`, `created_on`, `updated_on`, `updated_by`) VALUES (NULL, '".$newSensationName."', '".$newSensationRemedies."',  '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 'admin');";
    $result = runQuery($insertQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=mysqli_insert_id($conn);
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error inserting Sensation";
    }

    return $returnArr;
  }
    function deleteSensation($SensationId, $conn){
    global $blanks;
    $returnArr = array();

    $deleteQuery = "UPDATE sensation SET sensation_status=0 WHERE sensationId='".$SensationId."'";
    $result = runQuery($deleteQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="Sensation deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error deleting Sensation";
    }

    return $returnArr;
  }
   function editSensation($newSensationName,$newSensationRemedies, $SensationId, $conn){
    global $blanks;
    $returnArr = array();

    $updateQuery = "UPDATE sensation SET sensationName='".$newSensationName."',sensationRemedies='".$newSensationRemedies."', updated_on=CURRENT_TIMESTAMP WHERE sensationId='".$SensationId."'";
    $result = runQuery($updateQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="Sensation succesfully updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Sensation update failed";
    }

    return $returnArr;
  }
  function getSensationRemedies($SensationId, $conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM sensation WHERE sensationId='".$SensationId."'";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $row=mysqli_fetch_assoc($result["dbResource"]);
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$row;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching remedies data";
    }

    return $returnArr;  
  }

?>