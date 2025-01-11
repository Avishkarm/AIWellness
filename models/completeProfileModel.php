<?php
function editGeneralProfileInfo($userInfo, $conn){
      global $blanks;
      
      $returnArr = array();
      $values=array();
      $userProg=0;
      $totProg=17;
      //initializing the query string variables
      $query = "UPDATE users"; 
      //printArr($userInfo);
      //customizing the values array
      /*if(isset($userInfo["user_email"]) && !(in_array($userInfo["user_email"], $blanks))){
        $values["user_email"] = $userInfo["user_email"];
        $userProg++;
      }*/
      if(isset($userInfo["user_first_name"]) && !(in_array($userInfo["user_first_name"], $blanks))){
        $values["user_first_name"] = $userInfo["user_first_name"];
        $userProg++;
      }
      if(isset($userInfo["user_last_name"]) && !(in_array($userInfo["user_last_name"], $blanks))){
        $values["user_last_name"] = $userInfo["user_last_name"];
        $userProg++;
      }
      if(isset($userInfo["user_reg_no"]) && !(in_array($userInfo["user_reg_no"], $blanks))){
        $values["user_reg_no"] = $userInfo["user_reg_no"];
          //$userProg++;
      }else{
        $values["user_reg_no"]="";
      }
      if(isset($userInfo["user_gender"]) && !(in_array($userInfo["user_gender"], $blanks))){
        $values["user_gender"] = $userInfo["user_gender"];
        $userProg++;
      }
      if(isset($userInfo["title"]) && !(in_array($userInfo["title"], $blanks))){
        $values["title"] = $userInfo["title"];
        $userProg++;
      }
      if(isset($userInfo["user_nationality"]) && !(in_array($userInfo["user_nationality"], $blanks))){
        $values["user_nationality"] = $userInfo["user_nationality"];
        $userProg++;
      }else{
        $values["user_nationality"]="";
      }
      if(isset($userInfo["user_marital_status"]) && !(in_array($userInfo["user_marital_status"], $blanks))){
        $values["user_marital_status"] = $userInfo["user_marital_status"];
        $userProg++;
      }else{
        $values["user_marital_status"]="";
      } 
      if(isset($userInfo["user_dob"]) && !(in_array($userInfo["user_dob"], $blanks))){
        $values["user_dob"] = date("Y-m-d", strtotime($userInfo["user_dob"]));
        $userProg++;
      }      
      if(isset($userInfo["height"]) && !(in_array($userInfo["height"], $blanks))){
        $values["height"] = $userInfo["height"];
        $userProg++;
      }else{
        $values["height"]="";
      }     
      if(isset($userInfo["weight"]) && !(in_array($userInfo["weight"], $blanks))){
        $values["weight"] = $userInfo["weight"];
        $userProg++;
      }else{
        $values["weight"]="";
      }
      if(isset($userInfo["height_unit"]) && !(in_array($userInfo["height_unit"], $blanks))){
        $values["height_unit"] = $userInfo["height_unit"];
      }else{
        $values["height_unit"]="";
      }
      if(isset($userInfo["weight_unit"]) && !(in_array($userInfo["weight_unit"], $blanks))){
        $values["weight_unit"] = $userInfo["weight_unit"];
      }else{
        $values["weight_unit"]="";
      }
      if(isset($userInfo["highest_degree"]) && !(in_array($userInfo["highest_degree"], $blanks))){
        $values["highest_degree"] = $userInfo["highest_degree"];
        $userProg++;
      }else{
        $values["highest_degree"]="";
      }
      if(isset($userInfo["user_image"]) && !(in_array($userInfo["user_image"], $blanks))){
        $values["user_image"] = $userInfo["user_image"];
        if(!isset($userInfo["defaultImag"]) and empty($userInfo["defaultImag"])){
          $userProg++;  
        }
      }
      
      //printArr($values);
      $percentProg=floor(($userProg/$totProg)*100);

      $values['profProgress']=$percentProg;
      //looping thru the col names and values arrays to for related query strings
      $colNamesStr = ""; $valuesStr = ""; $updateStr = "";
      foreach($values as $colName=>$val){             
        if($colName != "user_email"){
          if(!in_array($updateStr, $blanks))
            $updateStr .= ",";
          $updateStr .= cleanQueryParameter($conn,$colName)."='".cleanQueryParameter($conn,$val)."'";
        }
      }
      
      $query .= " SET ".$updateStr." WHERE user_email='".$userInfo["user_email"]."'and user_type_id=".$userInfo["user_type"];
      //run the query and return success or failure

      $result = runQuery($query, $conn);
      
      if(noError($result)){
        $returnArr["errCode"][-1] = -1;
        $returnArr["errMsg"] = "Personal Info Succesfully Added/Edited";

      } else {
        $returnArr["errCode"][5] = 5;
        $returnArr["errMsg"] = "Personal Info Add/Edit FAILED: ".$result["errMsg"]; 
      }
      //printArr($returnArr);
      return $returnArr;
    }

    function editContactProfileInfo($userInfo, $conn){
      global $blanks;
      //printArr($userInfo);
      $returnArr = array();
      $userProg=0;
      $totProg=17;
      //initializing the query string variables
      $query = "UPDATE users"; 
      
      //customizing the values array
      if(isset($userInfo["user_mob"]) && !(in_array($userInfo["user_mob"], $blanks))){
        $values["user_mob"] = $userInfo["user_mob"];
        $userProg++;    
      }
      if(isset($userInfo["country_code"]) && !(in_array($userInfo["country_code"], $blanks))){
        $values["country_code"] = $userInfo["country_code"];
      }
      if(isset($userInfo["user_address"]) && !(in_array($userInfo["user_address"], $blanks))){
        $values["user_address"] = $userInfo["user_address"];
        $userProg++;
      }else{
        $values["user_address"]="";
      }
      if(isset($userInfo["user_country"]) && !(in_array($userInfo["user_country"], $blanks))){
        $values["user_country"] = $userInfo["user_country"];
        $userProg++;
      }else{
        $values["user_country"]="";
      }
      if(isset($userInfo["user_city"]) && !(in_array($userInfo["user_city"], $blanks))){
        $values["user_city"] = $userInfo["user_city"];
        $userProg++;
      }else{
        $values["user_city"]="";
      }
      if(isset($userInfo["user_state"]) && !(in_array($userInfo["user_state"], $blanks))){
        $values["user_state"] = $userInfo["user_state"];
        $userProg++;
      }else{
        $values["user_state"]="";
      }
      if(isset($userInfo["user_zip"]) && !(in_array($userInfo["user_zip"], $blanks))){
        $values["user_zip"] = $userInfo["user_zip"];
        $userProg++;
      }else{
        $values["user_zip"]="";
      }
      if(isset($userInfo["user_landline_no"]) && !(in_array($userInfo["user_landline_no"], $blanks))){
        $values["user_landline_no"] = $userInfo["user_landline_no"];
        $userProg++;
      }else{
        $values["user_landline_no"]="";
      }
      if(isset($userInfo["user_alt_email"]) && !(in_array($userInfo["user_alt_email"], $blanks))){
        $values["user_alt_email"] = $userInfo["user_alt_email"];
        $userProg++;
      }else{
        $values["user_alt_email"]="";
      }
      
      
      $percentProg=floor(($userProg/$totProg)*100);

      $values['profProgress']=$percentProg;
      //looping thru the col names and values arrays to for related query strings
      $colNamesStr = ""; $valuesStr = ""; $updateStr = "";
      foreach($values as $colName=>$val){             
        if($colName != "user_email"){
          if(!in_array($updateStr, $blanks))
            $updateStr .= ",";
          $updateStr .= cleanQueryParameter($conn,$colName)."='".cleanQueryParameter($conn,$val)."'";
        }
      }
      
     $query .= " SET ".$updateStr." WHERE user_email='".$userInfo["user_email"]."' and user_type_id=".$userInfo["user_type"];
      //run the query and return success or failure

      $result = runQuery($query, $conn);
      
      if(noError($result)){
        $returnArr["errCode"][-1] = -1;
        $returnArr["errMsg"] = "Personal Info Succesfully Added/Edited";

      } else {
        $returnArr["errCode"][5] = 5;
        $returnArr["errMsg"] = "Personal Info Add/Edit FAILED: ".$result["errMsg"]; 
      }
     // printArr($returnArr);
      return $returnArr;
    }

function getAllContries($conn) {

    $returnArr = array();
    global $blanks; 
    $query = "SELECT * FROM countries";
    $res=array();
    $result = runQuery($query, $conn);
    if(noError($result)){
     
      while($row=mysqli_fetch_assoc($result["dbResource"])){
       $res[$row['country_id']]=$row;
     }
     $returnArr["errCode"][-1]=-1;
     $returnArr["errMsg"]=$res;
   } else {
    $returnArr["errCode"][1]=1;
    $returnArr["errMsg"]="Error fetching doctors contact data";
  }
  //printArr($returnArr);
  return $returnArr;
}

function getAllNAtinalities($conn) {

    $returnArr = array();
    global $blanks; 
    $query = "SELECT * FROM nationality";
    $res=array();
    $result = runQuery($query, $conn);
    if(noError($result)){
     
      while($row=mysqli_fetch_assoc($result["dbResource"])){
       $res[$row['id']]=$row;
     }
     $returnArr["errCode"][-1]=-1;
     $returnArr["errMsg"]=$res;
   } else {
    $returnArr["errCode"][1]=1;
    $returnArr["errMsg"]="Error fetching doctors contact data";
  }
  //printArr($returnArr);
  return $returnArr;
}

  function getAllStates($conn,$country_id) {

    $returnArr = array();
    global $blanks; 
   $query = "SELECT * FROM states where country_id=".$country_id;
 $res=array();
    $result = runQuery($query, $conn);
    if(noError($result)){
     
      while($row=mysqli_fetch_assoc($result["dbResource"])){
       $res[]=$row;
     }
     $returnArr["errCode"][-1]=-1;
     $returnArr["errMsg"]=$res;
   } else {
    $returnArr["errCode"][1]=1;
    $returnArr["errMsg"]="Error fetching doctors contact data";
  }
   // printArr($returnArr);
  return $returnArr;
  }


  function getAllCities($conn,$state_id) {

    $returnArr = array();
    global $blanks; 
    $query = "SELECT * FROM cities where state_id=".$state_id;
   $res=array();
    $result = runQuery($query, $conn);
    if(noError($result)){
     
      while($row=mysqli_fetch_assoc($result["dbResource"])){
       $res[]=$row;
     }
     $returnArr["errCode"][-1]=-1;
     $returnArr["errMsg"]=$res;
   } else {
    $returnArr["errCode"][1]=1;
    $returnArr["errMsg"]="Error fetching doctors contact data";
  }
   // printArr($returnArr);
  return $returnArr;
  }
?>