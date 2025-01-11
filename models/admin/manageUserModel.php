<?php

function getAllDoctors($conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM users WHERE user_type_id=2";   
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["user_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching doctors data";
    }

    return $returnArr;
}

function getAllPatients($user_id,$conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT users.*,doctors_patient.status FROM users INNER JOIN doctors_patient  ON users.user_id=doctors_patient.patient_id WHERE doctors_patient.doctor_id=".$user_id;   
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["user_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching patients data";
    }

    return $returnArr;
}


function addNewPlan($newPlanTitle,$newPlanDuration, $conn){
    global $blanks;

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $returnArr = array();

    $insertQuery = "INSERT INTO `plan` ( `title`,`duration`) VALUES ( '".$newPlanTitle."', '".$newPlanDuration."')";
    $result = runQuery($insertQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=mysqli_insert_id($conn);
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error inserting plan";
    }

    return $returnArr;
}
function editPlan($newTitle,$newDuration,$planId,$conn){
  global $blanks;
  $returnArr = array();

  $updateQuery = "UPDATE plan SET title='".$newTitle."',duration='".$newDuration."' WHERE id='".$planId."'";
  $result = runQuery($updateQuery, $conn);
  if(noError($result)){
    $returnArr["errCode"][-1]=-1;
    $returnArr["errMsg"]="plan succesfully updated";
  } else {
    $returnArr["errCode"][1]=1;
    $returnArr["errMsg"]=mysqli_error($conn);
  }

  return $returnArr;
}

  function deletePlan($planId, $conn){
    global $blanks;
    $returnArr = array();

  $deleteQuery = "UPDATE plan SET status=0 WHERE id='".$planId."'";
    $result = runQuery($deleteQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="plan deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error deleting plan";
    }

    return $returnArr;
  }



?>