<?php
function getAllPlanPrice($newPlanId,$conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM plan_price WHERE plan_id=".$newPlanId." AND  status=1";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching rubrics data";
    }

    return $returnArr;
}

function addNewPlanPrice($newPlanId,$newRegionId,$newAmount, $conn){
    global $blanks;

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $returnArr = array();

    $insertQuery = "INSERT INTO `plan_price` ( `plan_id`,`region_id`,`amount`) VALUES ( ".$newPlanId.", ".$newRegionId.",'".$newAmount."')";
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
function editPlanPrice($newPlanId,$newRegionId,$newAmount, $priceId, $conn){
  global $blanks;
  $returnArr = array();

  $updateQuery = "UPDATE plan_price SET plan_id=".$newPlanId.",region_id=".$newRegionId.",amount=".$newAmount."  WHERE id='".$priceId."'";
  $result = runQuery($updateQuery, $conn);
  if(noError($result)){
    $returnArr["errCode"][-1]=-1;
    $returnArr["errMsg"]="plan price succesfully updated";
  } else {
    $returnArr["errCode"][1]=1;
    $returnArr["errMsg"]=mysqli_error($conn);
  }

  return $returnArr;
}

  function deletePlanPrice($priceId, $conn){
    global $blanks;
    $returnArr = array();

   $deleteQuery = "UPDATE plan_price SET status=0 WHERE id='".$priceId."'";
    $result = runQuery($deleteQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="Plan price deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error deleting plan price";
    }

    return $returnArr;
  }


?>