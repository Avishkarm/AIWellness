<?php
function updatePlan($user_id,$plan_id,$plan_status,$expiry_date,$conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

     $updateQuery = "UPDATE users SET plan_id=".$plan_id.",plan_expiry_date='".$expiry_date."',plan_status=".$plan_status."  WHERE user_id=".$user_id;
    $result = runQuery($updateQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="User plan updated successfully";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to update user plan".mysqli_error($conn);
    }
//  printArr($returnArr);
    return $returnArr;
  }

  function getDoctorPlanDetails($conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM `users` WHERE DATE(`plan_expiry_date`) <= CURDATE() and plan_status>=3 and user_type_id=2";
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
      $returnArr["errMsg"]="Error fetching Modalities data";
    }

    return $returnArr;
  }
  function updatePlanStatus($user_id,$conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

    $updateQuery = "UPDATE users SET plan_status='expired' WHERE user_id=".$user_id;
    $result = runQuery($updateQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="plan status updated successfully";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to update plan status".mysqli_error($conn);
    }

    return $returnArr;
  }

  function getPlanPrice($plan_id,$region_id,$conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT amount FROM `plan_price` WHERE plan_id=".$plan_id." and region_id=".$region_id." and status=1";
    $result = runQuery($query, $conn);
    //printArr($result);

    if(noError($result)){
      $res = array();
     $row=mysqli_fetch_assoc($result["dbResource"]);
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$row['amount'];
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching Modalities data";
    }
    //printArr($returnArr);
    return $returnArr;
  }

  function getDiscount($promocode,$conn){
    global $blanks;
    $returnArr = array();

     $query = "SELECT c.percent_discount,p.status, DATEDIFF(DATE(c.expiry_date), CURDATE()) as expiry FROM `coupon` c INNER JOIN promocodes p  ON c.id=p.coupon_id WHERE p.code='".$promocode."'";
    $result = runQuery($query, $conn);

  /*  if(noError($result)){
      $res = array();
     $row=mysqli_fetch_assoc($result["dbResource"]);
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$row;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Invalid promocode";
    }*/

    if(noError($result)){
    if(mysqli_num_rows($result["dbResource"])==0){
      //username does not exist
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"] = "Invalid Promocode";
    } else {    
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"] = mysqli_fetch_assoc($result["dbResource"]); 
    }
  } else {
    $returnArr["errCode"][3]=3;
    $returnArr["errMsg"] = "Could not get data: ".$result["errMsg"];
  }
   // printArr($returnArr);
    return $returnArr;
  }

  function getRegionDetails($conn, $status=1){
  $returnArr = array();
  $res = array();
  $query = "SELECT m.*,c.* FROM manageRegion m INNER JOIN currency_value c ON m.region_currency=c.id WHERE m.status=".$status;

  $query = runQuery($query, $conn);

  if(noError($query)){
     while($row=mysqli_fetch_assoc($query["dbResource"])){
      $res[$row['region_id']] = $row;
     }
    $returnArr['errCode'][-1] = -1;
    $returnArr['errMsg'] = $res;     
  }else{
    $returnArr['errCode'][2] = 2;
    $returnArr['errMsg'] = $query['errMsg'];
  }

  return $returnArr;
}


  function getPlanDetails($plan_id,$conn){
    global $blanks;
    $returnArr = array();

    echo $query = "SELECT * FROM `plan` WHERE id=".$plan_id." and status=1";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
     $row=mysqli_fetch_assoc($result["dbResource"]);
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$row;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching plans data";
    }
    //printArr($returnArr);
    return $returnArr;
  }

  

?>