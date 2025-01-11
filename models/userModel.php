<?php
/*//prepare for request 
require_once("../utilities/config.php");
require_once("../utilities/dbutils.php");
//databse connection
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
} else {
	printArr("Database Error");
	exit;
}*/
//to check if user exist in user table or not
function getUserInfoWithType($user_email,$type,$conn) {

	$returnArr = array();
	global $blanks;
	$query = "SELECT * FROM users WHERE user_email='$user_email' AND user_type_id='$type'";		
		
	$result = runQuery($query, $conn);
	if(noError($result)){
		if(mysqli_num_rows($result["dbResource"])==0){
			//username does not exist
			$returnArr["errCode"][-1]=-1;
			$returnArr["errMsg"] = "Could not find username: ".$result["errMsg"];
		} else {		
			$returnArr["errCode"][1]=1;
			$returnArr["errMsg"] = mysqli_fetch_assoc($result["dbResource"]);	
		}
	} else {
		$returnArr["errCode"][3]=3;
		$returnArr["errMsg"] = "Could not get user info: ".$result["errMsg"];
	}

	return $returnArr;

}
//to get userinfo with type from user table
function getUserInfoWithUserType($user_email,$type,$conn) {
	$returnArr = array();
	global $blanks;
	$query = "SELECT * FROM users WHERE user_email='$user_email' AND user_type_id='$type'";		
		
	$result = runQuery($query, $conn);
		//printArr($result);
	if(noError($result)){
		if(mysqli_num_rows($result["dbResource"])==0){
			//username does not exist
			$returnArr["errCode"][1]=1;
			$returnArr["errMsg"] = "Could not find username: ".$result["errMsg"];
		} else {		
			$returnArr["errCode"][-1]=-1;
			$returnArr["errMsg"] = mysqli_fetch_assoc($result["dbResource"]);	
		}
	} else {
		$returnArr["errCode"][3]=3;
		$returnArr["errMsg"] = "Could not get user info: ".$result["errMsg"];
	}
	//printArr($returnArr);
	return $returnArr;

}
function getUserInfoWithUserId($user_id,$type,$conn) {
	$returnArr = array();
	global $blanks;
	$query = "SELECT * FROM users WHERE user_id='$user_id' AND user_type_id='$type'";		
		
	$result = runQuery($query, $conn);
		
	if(noError($result)){
		if(mysqli_num_rows($result["dbResource"])==0){
			//username does not exist
			$returnArr["errCode"][1]=1;
			$returnArr["errMsg"] = "Could not find username: ".$result["errMsg"];
		} else {		
			$returnArr["errCode"][-1]=-1;
			$returnArr["errMsg"] = mysqli_fetch_assoc($result["dbResource"]);	
		}
	} else {
		$returnArr["errCode"][3]=3;
		$returnArr["errMsg"] = "Could not get user info: ".$result["errMsg"];
	}
	//printArr($returnArr);
	return $returnArr;

}
function getUserInfoWithUserId1($user_id,$conn) {
	$returnArr = array();
	global $blanks;
	$query = "SELECT * FROM users WHERE user_id='$user_id'";		
		
	$result = runQuery($query, $conn);
		
	if(noError($result)){
		if(mysqli_num_rows($result["dbResource"])==0){
			//username does not exist
			$returnArr["errCode"][1]=1;
			$returnArr["errMsg"] = "Could not find username: ".$result["errMsg"];
		} else {		
			$returnArr["errCode"][-1]=-1;
			$returnArr["errMsg"] = mysqli_fetch_assoc($result["dbResource"]);	
		}
	} else {
		$returnArr["errCode"][3]=3;
		$returnArr["errMsg"] = "Could not get user info: ".$result["errMsg"];
	}
	//printArr($returnArr);
	return $returnArr;

}

//to get userinfo from user table comparing user mobile or email
function getUserInfo($user_mob, $conn) {

	$returnArr = array();

	//$query = sprintf("SELECT * FROM users u WHERE 1=1 AND u.user_email='%s' OR u.user_mob='%s'", $user_mob,$user_mob);
	$query = sprintf("SELECT * FROM users u WHERE 1=1 AND u.user_email='%s' or u.user_mob='%s'", $user_mob,$user_mob);

	$result = runQuery($query, $conn);

	if(noError($result))
	{

		if(mysqli_num_rows($result["dbResource"])==0){
			//username does not exist
			$returnArr["errCode"][1]=1;
			$returnArr["errMsg"] = "Could not find username: ".$result["errMsg"];
		} else {
		
			$returnArr["errCode"][-1]=-1;
			$returnArr["errMsg"] = mysqli_fetch_assoc($result["dbResource"]);

		}
	} else {
		$returnArr["errCode"][3]=3;
		$returnArr["errMsg"] = "Could not get user info: ".$result["errMsg"];
	}
	return $returnArr;
}
//to get userinfo from user table comparing user mobile or email
function getUserInfoWithMobNo($user_mob, $type, $conn) {

	$returnArr = array();

	//$query = sprintf("SELECT * FROM users u WHERE 1=1 AND u.user_email='%s' OR u.user_mob='%s'", $user_mob,$user_mob);
	$query = sprintf("SELECT * FROM users u WHERE 1=1 AND u.user_mob='%s' AND u.user_type_id='%s'", $user_mob,$type);

	$result = runQuery($query, $conn);

	if(noError($result))
	{

		if(mysqli_num_rows($result["dbResource"])==0){
			//username does not exist
			$returnArr["errCode"][1]=1;
			$returnArr["errMsg"] = "Could not find mobile no: ".$result["errMsg"];
		} else {
		
			$returnArr["errCode"][-1]=-1;
			$returnArr["errMsg"] = mysqli_fetch_assoc($result["dbResource"]);

		}
	} else {
		$returnArr["errCode"][3]=3;
		$returnArr["errMsg"] = "Could not get user info: ".$result["errMsg"];
	}
	return $returnArr;
}

//insert signup or registration info of user in user table
function 	insertProfileInfo($userInfo, $conn){

	global $blanks;
	$returnArr = array();
	//initializing the query string variables
	$query = "INSERT INTO users"; 
	//customizing the values array
	if(isset($userInfo["user_mob"]) && !(in_array($userInfo["user_mob"], $blanks))){
		$values["user_mob"] = $userInfo["user_mob"];
	}
	if(isset($userInfo["country_code"]) && !(in_array($userInfo["country_code"], $blanks))){
		$values["country_code"] = $userInfo["country_code"];
	}
	if(isset($userInfo["user_first_name"]) && !(in_array($userInfo["user_first_name"], $blanks))){
		$values["user_first_name"] = $userInfo["user_first_name"];
	}
	if(isset($userInfo["user_last_name"]) && !(in_array($userInfo["user_last_name"], $blanks))){
	 	$values["user_last_name"] = $userInfo["user_last_name"];	
	}
	if(isset($userInfo["user_type"]) && !(in_array($userInfo["user_type"], $blanks))){
	 	$values["user_type_id"] = $userInfo["user_type"];
	}
	if(isset($userInfo["user_email"]) && !(in_array($userInfo["user_email"], $blanks))){
	 	$values["user_email"] = $userInfo["user_email"];
	}
	if(isset($userInfo["salt"]) && !(in_array($userInfo["salt"], $blanks))){
	 	$values["salt"] = $userInfo["salt"];
	}
	if(isset($userInfo["user_password"]) && !(in_array($userInfo["user_password"], $blanks))){
		 $values["user_password"] = $userInfo["user_password"];
	}
        if(isset($userInfo["user_status"]) && !(in_array($userInfo["user_status"], $blanks))){
		 $values["status"] = $userInfo["user_status"];
	}
	
	//looping thru the col names and values arrays to for related query strings
	$colNamesStr = ""; $valuesStr = ""; $updateStr = "";
	foreach($values as $colName=>$val){             
	if(!in_array($colNamesStr, $blanks))
	  $colNamesStr .= ",";
	$colNamesStr .= cleanQueryParameter($conn,$colName);
	if(!in_array($valuesStr, $blanks))
	  $valuesStr .= ",";
	$valuesStr .= "'".cleanQueryParameter($conn,$val)."'";
	}

	$query .= "(".$colNamesStr.") VALUES (".$valuesStr.")";
	//run the query and return success or failure
	$result = runQuery($query, $conn);
	//  printArr($result);
	if(noError($result)){
	$returnArr["errCode"][-1] = -1;
	$returnArr["errMsg"] = "Personal Info Succesfully Added/Edited";
	} else {
	$returnArr["errCode"][5] = 5;
	$returnArr["errMsg"] = "Personal Info Add/Edit FAILED: ".$result["errMsg"]; 
	}
	return $returnArr; 
}
function insertSocialProfileInfo($userInfo, $conn){

	global $blanks;
	$returnArr = array();
	//initializing the query string variables
	$query = "INSERT INTO users"; 
	//customizing the values array
	if(isset($userInfo["user_gender"]) && !(in_array($userInfo["user_gender"], $blanks))){
		$values["user_gender"] = ucfirst($userInfo["user_gender"]);
	}
	if(isset($userInfo["user_dob"]) && !(in_array($userInfo["user_dob"], $blanks))){		
        $values["user_dob"] = date("Y-m-d", strtotime($userInfo["user_dob"]));
	}
	if(isset($userInfo["user_first_name"]) && !(in_array($userInfo["user_first_name"], $blanks))){
		$values["user_first_name"] = $userInfo["user_first_name"];
	}
	if(isset($userInfo["user_last_name"]) && !(in_array($userInfo["user_last_name"], $blanks))){
	 	$values["user_last_name"] = $userInfo["user_last_name"];	
	}
	if(isset($userInfo["user_type"]) && !(in_array($userInfo["user_type"], $blanks))){
	 	$values["user_type_id"] = $userInfo["user_type"];
	}
	if(isset($userInfo["user_email"]) && !(in_array($userInfo["user_email"], $blanks))){
	 	$values["user_email"] = $userInfo["user_email"];
	}
	if(isset($userInfo["user_image"]) && !(in_array($userInfo["user_image"], $blanks))){
	 	$values["user_image"] = $userInfo["user_image"];
	}
	if(isset($userInfo["login_type"]) && !(in_array($userInfo["login_type"], $blanks))){
		 $values["login_type"] = $userInfo["login_type"];
	}
	if(isset($userInfo["status"]) && !(in_array($userInfo["status"], $blanks))){
		 $values["status"] = $userInfo["status"];
	}
	
	//looping thru the col names and values arrays to for related query strings
	$colNamesStr = ""; $valuesStr = ""; $updateStr = "";
	foreach($values as $colName=>$val){             
	if(!in_array($colNamesStr, $blanks))
	  $colNamesStr .= ",";
	$colNamesStr .= cleanQueryParameter($conn,$colName);
	if(!in_array($valuesStr, $blanks))
	  $valuesStr .= ",";
	$valuesStr .= "'".cleanQueryParameter($conn,$val)."'";
	}

	$query .= "(".$colNamesStr.") VALUES (".$valuesStr.")";
	//run the query and return success or failure
	$result = runQuery($query, $conn);
	//  printArr($result);
	if(noError($result)){
	$returnArr["errCode"][-1] = -1;
	$returnArr["errMsg"] = "Personal Info Succesfully Added/Edited";
	} else {
	$returnArr["errCode"][5] = 5;
	$returnArr["errMsg"] = "Personal Info Add/Edit FAILED: ".$result["errMsg"]; 
	}
	return $returnArr; 
}
//get all types of users(doctor,patient,admin) from user_type table
function all_user_type($conn){
   $returnArr = array();

   $query = "SELECT `user_type_description`,`user_type_id` FROM `user_type`";

   $result = runQuery($query, $conn);

   if(noError($result)){
    $res = array();

    while ($row = mysqli_fetch_assoc($result["dbResource"]))

      $res[$row['user_type_description']] = $row['user_type_id'];

    $returnArr["errCode"][-1]=-1;

    $returnArr["errMsg"]=$res;

  } else {

    $returnArr["errCode"][5]=5;

    $returnArr["errMsg"]=$result["errMsg"];

  }

  return $returnArr;
}

function getPlanExpiring($user_id,$conn){
//echo "hii";
    global $blanks;
    $returnArr = array();

    //$query = "SELECT * FROM `doctors_patient_cases` WHERE DATE(`followup_date`) = CURDATE()";
    $query = "SELECT plan_status, DATE(`plan_expiry_date`), DATEDIFF(DATE(`plan_expiry_date`), CURDATE()) as diff FROM `users` WHERE user_id =".$user_id;
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching expiry days";
    }

    return $res;
  }

function getUserCountryId($name,$conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT id FROM countries WHERE name='".$name."'";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
     $row=mysqli_fetch_assoc($result["dbResource"]);
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$row;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching plan data";
    }
//printArr($row['id']);
    return $row['id'];
}


?>