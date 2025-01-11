<?php 
  function getAllSubscribers($conn) {
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM subscriber";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["s_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching subscriber data";
    }

    return $returnArr;
  }

    function addNewRemedy($newRemedyName,$newRemedyFullName,$newRemedyDescription, $conn){
    global $blanks;

    $returnArr = array();
   /* $check=mysqli_query("select * from remedies where remedy_name='$newRemedyName' and remedy_status=1",$conn);
    printArr($check);
    $checkrows=mysqli_num_rows($check);*/
    $query="select * from remedies where remedy_name='$newRemedyName' and remedy_status=1";
    $result1 = runQuery($query, $conn);
    $checkrows=mysqli_num_rows($result1["dbResource"]);
    if($checkrows>0)
    {
      $returnArr["errCode"][2]=2;
      $returnArr["errMsg"]="alredy exist";
      
    }
    else
    {
      
      $insertQuery = "INSERT INTO `remedies` (`remedy_name`,`remedy_full_name`,`remedy_description`, `created_on`, `updated_on`, `updated_by`) VALUES ('".$newRemedyName."','".$newRemedyFullName."','".$newRemedyDescription."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 'admin');";
      $result = runQuery($insertQuery, $conn);

      if(noError($result)){
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=mysqli_insert_id($conn);
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error inserting remedy";
      }
    }
   // printArr($returnArr);
    return $returnArr;
  }

    function editRemedy($newRemedyName,$newRemedyFullName,$newRemedyDescription, $remedyId, $conn){
    global $blanks;
    $returnArr = array();
    $query="select * from remedies where remedy_name='$newRemedyName' and remedy_status=1 and remedy_id!=".$remedyId;
    $result1 = runQuery($query, $conn);    
    $checkrows=mysqli_num_rows($result1["dbResource"]);
    if($checkrows>0)
    {
      $returnArr["errCode"][2]=2;
      $returnArr["errMsg"]="alredy exist";
      
    }
    else
    {      

       $updateQuery = "UPDATE remedies SET remedy_name='".$newRemedyName."',remedy_full_name='".$newRemedyFullName."',remedy_description='".$newRemedyDescription."', updated_on='".date('Y-m-d H:i:s')."' WHERE remedy_id='".$remedyId."'";
      $result = runQuery($updateQuery, $conn);

      if(noError($result)){
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]="Remedy succesfully updated";
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Remedy update failed";
      }
    }
    return $returnArr;
  }

    function deleteRemedy($remedyId, $conn){
    global $blanks;
    $returnArr = array();

    $deleteQuery = "UPDATE remedies SET remedy_status=0 WHERE remedy_id='".$remedyId."'";
    $result = runQuery($deleteQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="Remedy deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error deleting remedy";
    }

    return $returnArr;
  }
?>