<?php
function check8PFUser($conn,$doctor_id ,$patient_id,$caseID){
  $returnArr = array();
  $df = getDoctorPF($patient_id, $conn);
  $df = $df['errMsg']['doctor_8pf'];
  //print_r($doctor_id);
  $oldPF = json_decode($df, true);
  if(array_key_exists($caseID, $oldPF)){

    if(array_key_exists($doctor_id, $oldPF[$caseID])){ 
      $returnArr['errMsg']['user8PF'] = 1;
    }else{
      $returnArr['errMsg']['user8PF'] = 0;
    }
  }else{
    $returnArr['errMsg']['user8PF'] = 0;
  }
  $returnArr['errCode'][-1] =-1;
  //printArr($returnArr);
  return $returnArr;

}

  function getPersonalityFactor($conn){
    $returnArr = array();
    $res = array();
    $query = sprintf('SELECT * FROM personality_factors_master c WHERE c.factor_name IN ("A", "B", "E", "F", "G", "H", "Q3", "Q4", "")');

    $retQuery = runQuery($query, $conn);

    if(noError($retQuery)){
      while($row = mysqli_fetch_assoc($retQuery['dbResource'])){
        $res[] = $row;
      }
      $returnArr['errMsg'] = $res;
      $returnArr['errCode'][-1] = -1;
    }else{
      $returnArr['errMsg'] = $retQuery['errMsg'];
      $returnArr['errCode'][1] = 1;
    }
    return $returnArr;
  }
  function getDoctorPF($user, $conn){
    $returnArr = array();
    $res = array();
   $query = sprintf('SELECT doctor_8pf FROM users WHERE user_id="%s" LIMIT 1',$user);

    $retQuery = runQuery($query, $conn);

    if(noError($retQuery)){
      $res = mysqli_fetch_assoc($retQuery['dbResource']);
      $returnArr['errMsg'] = $res;
      $returnArr['errCode'][-1] = -1;
    }else{
      $returnArr['errMsg'] = $retQuery['errMsg'];
      $returnArr['errCode'][1] = 1;
    }
    //printArr($returnArr);
    return $returnArr;
  }
    function setDoctorPF($user, $factor, $conn){
    $returnArr = array();
    $res = array();
    $query = sprintf("UPDATE users SET doctor_8pf ='%s' WHERE user_id='%s'", $factor, $user);


    $retQuery = runQuery($query, $conn);

    if(noError($retQuery)){
      $returnArr['errMsg'] ="Updated Succesfully";
      $returnArr['errCode'][-1] = -1;
    }else{
      $returnArr['errMsg'] = $retQuery['errMsg'];
      $returnArr['errCode'][1] = 1;
    }
    return $returnArr;    
  }
?>