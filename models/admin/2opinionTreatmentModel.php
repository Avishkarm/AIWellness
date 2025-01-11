<?php
function getAllTreatment($conn,$treatmentStatus=1){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM treatment_type WHERE type_status='".$treatmentStatus."'";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["type_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching rubrics data";
    }

    return $returnArr;
  }
  function addNewTreatment($newTreatmentName,$conn){
    global $blanks;

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $returnArr = array();

   $insertQuery = "INSERT INTO `treatment_type` ( `type_name`, `created_on`, `updated_on`, `updated_by`) VALUES ( '".$newTreatmentName."','".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 'admin')";
    $result = runQuery($insertQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=mysqli_insert_id($conn);
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error inserting Treatment";
    }
    //printArr($returnArr);
    return $returnArr;
  }
  function deleteTreatment($treatmentId, $conn){
    global $blanks;
    $returnArr = array();

    $deleteQuery = "UPDATE treatment_type SET type_status=0 WHERE type_id='".$treatmentId."'";
    $result = runQuery($deleteQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="treatment deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error deleting treatment";
    }

    return $returnArr;
  }
  function editTreatment($newTreatmentName,$treatmentId, $conn){
    global $blanks;
    $returnArr = array();

  $updateQuery = "UPDATE treatment_type SET type_name='".$newTreatmentName."', updated_on='".date('Y-m-d H:i:s')."' WHERE type_id='".$treatmentId."'";
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="Rubric succesfully updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Rubric update failed";
    }
//printArr($returnArr);
    return $returnArr;
  }
?>