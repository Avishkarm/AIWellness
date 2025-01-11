<?php

    function getAllTreatmentSubtype($conn,$type_id,$treatmentStatus=1){
    global $blanks;
    $returnArr = array();

   $query = "SELECT * FROM treatment_subtype WHERE type_id='".$type_id."' and subtype_status='".$treatmentStatus."'";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["subtype_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching treatment subtype data";
    }

    //printArr($returnArr);
    return $returnArr;
  }

  function addNewTreatmentSubtype($newTreatmentName, $treatmentTypeId,$conn){
    global $blanks;

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $returnArr = array();

 $insertQuery = "INSERT INTO `treatment_subtype` ( `subtype_name`,`type_id`,`created_on`, `updated_on`, `updated_by`) VALUES ( '".$newTreatmentName."',".$treatmentTypeId.",'".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 'admin')";
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
    function editTreatmentSubtype($newTreatmentName,$treatmentId,$type_id, $conn){
    global $blanks;
    $returnArr = array();

  $updateQuery = "UPDATE treatment_subtype SET subtype_name='".$newTreatmentName."', updated_on='".date('Y-m-d H:i:s')."' WHERE subtype_id='".$treatmentId."' and type_id='".$type_id."'";
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

    function deleteTreatmentSubtype($treatmentId,$type_id, $conn){
    global $blanks;
    $returnArr = array();

    $deleteQuery = "UPDATE treatment_subtype SET subtype_status=0 WHERE type_id='".$type_id."' and subtype_id='".$treatmentId."'";
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

?>