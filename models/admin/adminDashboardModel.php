<?php
function getAllActiveDoctors($conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT count(*) as active_doctor FROM users WHERE user_type_id=2 and status='Active'";   
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res=$row;
      }
     // print_r($res);
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res['active_doctor'];
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching doctors data";
    }

    return $returnArr;
}
function getAllInActiveDoctors($conn){
    global $blanks;
    $returnArr = array();

     $query = "SELECT count(*) as inactive_doctor FROM users WHERE user_type_id=2 and status!='Active'";   
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res['inactive_doctor'];
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching doctors data";
    }

    return $returnArr;
}
function getAllDoctors($conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT count(*) as total_doctor FROM users WHERE user_type_id=2";   
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res['total_doctor'];
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching doctors data";
    }

    return $returnArr;
}

function getAllPatients($conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT count(*) as total_doctor FROM users WHERE user_type_id=3";   
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res['total_doctor'];
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching doctors data";
    }

    return $returnArr;
}

function getAllUsers($conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT count(*) as total_doctor FROM users";   
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res['total_doctor'];
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching doctors data";
    }

    return $returnArr;
}
?>