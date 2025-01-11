<?php
function getAllComplaints($conn, $status=1){
    global $blanks;
    $returnArr = array();
    
    $query = "SELECT *, GROUP_CONCAT(Common_name) as Common_name, GROUP_CONCAT(id) as uniqid FROM complaint_info WHERE status=".$status." group by Diagnostic_term ORDER BY priority_id" ;
    //$query = "SELECT * FROM complaint_info WHERE status=".$status;
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["id"]]=$row;
       // printArr($row);
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
      //$returnArr["Common_name"]=
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching complaint data";
    }

    return $returnArr;
  }

    function addNewComplaint($newComplaint, $conn){
    global $blanks;

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $returnArr = array();

   $insertQuery = "INSERT INTO `complaint_info` ( `priority_id`,`Diagnostic_term`,`Definition`, `Common_name`, `Expert_Comments`, `Possibility`,`CTA`, `Duration`, `Improvement_rate`, `system`,`organ`,`subOrgan`,`embryologcial`,`miasm`) VALUES (".$newComplaint['priority_id'].",'".$newComplaint['Diagnostic_term']."', '".$newComplaint['Definition']."', '".$newComplaint['Common_name']."', '".$newComplaint['Expert_Comments']."','".$newComplaint['Possibility']."','".$newComplaint['CTA']."','".$newComplaint['Duration']."','".$newComplaint['Improvement_rate']."','".$newComplaint['system']."','".$newComplaint['organ']."','".$newComplaint['subOrgan']."','".$newComplaint['embryologcial']."','".$newComplaint['miasm']."')";
    $result = runQuery($insertQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=mysqli_insert_id($conn);
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error inserting complaint";
    }

    return $returnArr;
  }

  function editComplaint($newComplaint, $conn){
    //echo "inn";
    global $blanks;
    $returnArr = array();

     $updateQuery = "UPDATE complaint_info SET Diagnostic_term='".$newComplaint['Diagnostic_term']."',Common_name='".$newComplaint['Common_name']."',Expert_Comments='".$newComplaint['Expert_Comments']."',Possibility='".$newComplaint['Possibility']."',CTA='".$newComplaint['CTA']."',Duration='".$newComplaint['Duration']."',Improvement_rate='".$newComplaint['Improvement_rate']."',system='".$newComplaint['system']."',organ='".$newComplaint['organ']."',subOrgan='".$newComplaint['subOrgan']."',embryologcial='".$newComplaint['embryologcial']."',miasm='".$newComplaint['miasm']."',Definition='".$newComplaint['Definition']."'  WHERE id=".$newComplaint['id'];
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="complaint succesfully updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="complaint update failed";
    }

    return $returnArr;
  }

  function deleteComplaint($complaintId, $conn){
    global $blanks;
    $returnArr = array();

  $deleteQuery = "UPDATE complaint_info SET status=0 WHERE id='".$complaintId."'";
    $result = runQuery($deleteQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="complaint deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error deleting complaint";
    }

    return $returnArr;
  }
?>