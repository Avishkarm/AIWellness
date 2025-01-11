<?php
  function addNewContact($newName,$newlocation,$newprice,$newrating, $conn){
    global $blanks;

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $returnArr = array();

    $insertQuery = "INSERT INTO `admin_contact_doctor` ( `Name`,`location`,`price`, `rating`) VALUES ( '".$newName."','".$newlocation."','".$newprice."','".$newrating."')";
    $result = runQuery($insertQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=mysqli_insert_id($conn);
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error inserting contact";
    }
  //printArr($returnArr);
    return $returnArr;
  }

  function getAllContacts($conn, $doc_status=1){
    global $blanks;
    $returnArr = array();

   $query = "SELECT * FROM admin_contact_doctor WHERE doc_status='".$doc_status."'";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["doctorId"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching doctors data";
    }
  //printArr($returnArr);
    return $returnArr;
  }
  function editContact($newName,$newlocation,$newprice,$newrating,$doctorId, $conn){
    global $blanks;
    $returnArr = array();

   $updateQuery = "UPDATE admin_contact_doctor SET Name='".$newName."',location='".$newlocation."',price='".$newprice."',rating='".$newrating."' WHERE doctorId=".$doctorId;

    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="contact succesfully updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="contact update failed";
    }

    return $returnArr;
  }
  function deleteContact($doctorId, $conn){
    global $blanks;
    $returnArr = array();

    $deleteQuery = "UPDATE admin_contact_doctor SET doc_status=0 WHERE doctorId=".$doctorId;
    $result = runQuery($deleteQuery, $conn);

    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="Contact deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error deleting Contact";
    }
    return $returnArr;
  }
?>