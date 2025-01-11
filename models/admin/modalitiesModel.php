<?php
	function getAllModalities($conn, $ModalitiesStatus=1){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM modalities WHERE modalities_status='".$ModalitiesStatus."'";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["modalitiesId"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching Modalities data";
    }

    return $returnArr;
  }
   function addNewModalities($newModalitiesName,$newModalitiesRemedies, $conn){
    global $blanks;

    $returnArr = array();

    $insertQuery = "INSERT INTO `modalities` (`modalitiesId`, `modalitiesName`,`modalitiesRemedies`, `created_on`, `updated_on`, `updated_by`) VALUES (NULL, '".$newModalitiesName."', '".$newModalitiesRemedies."',  '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', 'admin');";
    $result = runQuery($insertQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=mysqli_insert_id($conn);
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error inserting Modalities";
    }
    
    return $returnArr;
  }
    function deleteModalities($ModalitiesId, $conn){
    global $blanks;
    $returnArr = array();

    $deleteQuery = "UPDATE modalities SET modalities_status=0 WHERE modalitiesId='".$ModalitiesId."'";
    $result = runQuery($deleteQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="modalities deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error deleting modalities";
    }

    return $returnArr;
  }
    function editModalities($newModalitiesName,$newModalitiesRemedies, $ModalitiesId, $conn){
    global $blanks;
    $returnArr = array();

    $updateQuery = "UPDATE modalities SET modalitiesName='".$newModalitiesName."',modalitiesRemedies='".$newModalitiesRemedies."', updated_on=CURRENT_TIMESTAMP WHERE modalitiesId='".$ModalitiesId."'";
    $result = runQuery($updateQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="modalities succesfully updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="modalities update failed";
    }

    return $returnArr;
  }
    function getModalitiesRemedies($ModalitiesId, $conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM modalities WHERE modalitiesId='".$ModalitiesId."'";
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