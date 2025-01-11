<?php

function getAllCoupon($conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM coupon WHERE status=1";
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

function addNewCoupon($newCouponTitle,$newCouponDescription,$newCouponExpiryDate,$newCouponDiscount,$conn){
    global $blanks;

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $returnArr = array();
    $date=date("Y-m-d", strtotime(str_replace('/','-',$newCouponExpiryDate)));
    $insertQuery = "INSERT INTO `coupon` ( `title`,`description`,`expiry_date`,`percent_discount`) VALUES ( '".$newCouponTitle."', '".$newCouponDescription."', '".$date."', '".$newCouponDiscount."')";
  
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

function editCoupon($newCouponTitle,$newCouponDescription,$newCouponExpiryDate,$newCouponDiscount,$couponId,$conn){
  global $blanks;
  $returnArr = array();
  $date=date("Y-m-d", strtotime(str_replace('/','-',$newCouponExpiryDate)));
  $updateQuery = "UPDATE coupon SET title='".$newCouponTitle."',description='".$newCouponDescription."',expiry_date='".$date."',percent_discount='".$newCouponDiscount."' WHERE id='".$couponId."'";
  $result = runQuery($updateQuery, $conn);
  if(noError($result)){
    $returnArr["errCode"][-1]=-1;
    $returnArr["errMsg"]="coupon succesfully updated";
  } else {
    $returnArr["errCode"][1]=1;
    $returnArr["errMsg"]=mysqli_error($conn);
  }

  return $returnArr;
}

  function deleteCoupon($couponId, $conn){
    global $blanks;
    $returnArr = array();

  $deleteQuery = "UPDATE coupon SET status=0 WHERE id='".$couponId."'";
    $result = runQuery($deleteQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="coupon deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error deleting coupon";
    }

    return $returnArr;
  }


?>