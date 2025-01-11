<?php

/*
function: getUserInfo
Purpose: gets all account and personal info from db about the user
Returns: standard format success/error array
Arguments: db conn and username
*/
function getUserInfo($user_mob, $conn) {
	
	$returnArr = array();
	
	//$query = sprintf("SELECT * FROM users u WHERE 1=1 AND u.user_email='%s' OR u.user_mob='%s'", $user_mob,$user_mob);
	$query = sprintf("SELECT * FROM users u WHERE 1=1 AND u.user_email='%s'", $user_mob);

	$result = runQuery($query, $conn);
	
	if(noError($result))
  {
    
		if(mysql_num_rows($result["dbResource"])==0){
			//username does not exist
			$returnArr["errCode"][1]=1;
			$returnArr["errMsg"] = "Could not find username: ".$result["errMsg"];
		} else {
		
			$returnArr["errCode"][-1]=-1;
			$returnArr["errMsg"] = mysql_fetch_assoc($result["dbResource"]);
	
		}
	} else {
		$returnArr["errCode"][3]=3;
		$returnArr["errMsg"] = "Could not get user info: ".$result["errMsg"];
	}
	
		return $returnArr;
	
	
	
}



function getUserInfo1($email, $conn) {
 
 $returnArr = array();
 
 //$query = sprintf("SELECT * FROM users u WHERE 1=1 AND u.user_email='%s' OR u.user_mob='%s'", $user_mob,$user_mob);
 
 $query = "SELECT * FROM users WHERE user_email = '".$email."'";
 //echo $email;

$result = runQuery($query, $conn);

//print_r($result);
 
 if(noError($result)){
  if(mysql_num_rows($result["dbResource"])==0){
   //username does not exist
   $returnArr["errCode"][1]=1;
   $returnArr["errMsg"] = "Could not find username: ".$result["errMsg"];
  } else {
  
   $returnArr["errCode"][-1]=-1;
   $returnArr["errMsg"] = mysql_fetch_assoc($result["dbResource"]);
 
  }
 } else {
  $returnArr["errCode"][3]=3;
  $returnArr["errMsg"] = "Could not get user info: ".$result["errMsg"];
 }
 
  return $returnArr; 
}


/*
function: getUserInfoWithType
Purpose: gets all account and personal info from db about the user
Returns: standard format success/error array
Arguments: db conn and username and type
*/
function getUserInfoWithType($user_email,$type,$conn) {

	$returnArr = array();
	global $blanks;
	$query = "SELECT * FROM users WHERE user_email='$user_email' AND user_type_id='$type'";		
		
	$result = runQuery($query, $conn);
		
	if(noError($result)){
		if(mysql_num_rows($result["dbResource"])==0){
			//username does not exist
			$returnArr["errCode"][1]=1;
			$returnArr["errMsg"] = "Could not find username: ".$result["errMsg"];
		} else {		
			$returnArr["errCode"][-1]=-1;
			$returnArr["errMsg"] = mysql_fetch_assoc($result["dbResource"]);	
		}
	} else {
		$returnArr["errCode"][3]=3;
		$returnArr["errMsg"] = "Could not get user info: ".$result["errMsg"];
	}

	return $returnArr;
	
}


?>