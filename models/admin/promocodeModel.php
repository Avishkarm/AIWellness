<?php
function getAllPromocode($couponId,$conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM promocodes WHERE coupon_id=".$couponId." and status=1";
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
      $returnArr["errMsg"]="Error fetching plan data";
    }

    return $returnArr;
}

function getExportPromocode($couponId,$conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT coupon.title,promocodes.code,coupon.percent_discount,coupon.expiry_date FROM promocodes INNER JOIN coupon WHERE promocodes.coupon_id=coupon.id and coupon.status=1 and promocodes.used=0 and promocodes.coupon_id=".$couponId;
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
      $returnArr["errMsg"]="Error fetching plan data";
    }

    return $returnArr;
}

function addNewPromocode($newCouponId,$newCouponCode, $conn){
    global $blanks;

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $returnArr = array();

    $insertQuery = "INSERT INTO `promocodes` ( `coupon_id`,`code`) VALUES ( ".$newCouponId.", '".$newCouponCode."')";
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
/*function editPlan($newTitle,$newDuration,$planId,$conn){
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
  }*/


?>