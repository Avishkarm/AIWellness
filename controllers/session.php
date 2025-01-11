<?php

/*
function getCurrUser
Purpose: To get the current logged in user from the session array
Accepts: db conn
returns: username
*/
function getCurrUser()
{
	if(sizeOf($_SESSION)>0)
		return $_SESSION['user'];
}

/*
function updateSession
Purpose: To update session info in the user table
Accepts: username, db conn, session start time, session id
returns: standard format error or success array
*/
function updateSession($username, $conn, $time="", $sessId){

	$returnArr = array();
	//create and run the update query	
	$query = sprintf("UPDATE users SET session_start_time=NOW(), session_id='%s' WHERE user_email='%s'", $sessId, $username);
	$result = runQuery($query, $conn);
	
	//return error / success array
	if(noError($result)){
		$returnArr["errCode"]=array("-1"=>-1);
		$returnArr["errMsg"]="Session Updated";
	} else {
		$returnArr["errCode"]=array("2"=>2);
		$returnArr["errMsg"]="Session Error: Could not update session: ".$result["errMsg"];
	}

	return $returnArr;
}

/*
function updateNoOfLogin
Purpose: To update noOfLogin in the user table
Accepts: username, db conn
returns: standard format error or success array
*/
function updateNoOfLogin($username, $conn){

	$returnArr = array();
	//create and run the update query	
	$query = sprintf("UPDATE users SET no_of_login=no_of_login+1 WHERE user_email='%s'", $username);
	$result = runQuery($query, $conn);
	
	//return error / success array
	if(noError($result)){
		$returnArr["errCode"]=array("-1"=>-1);
		$returnArr["errMsg"]="Login number Updated";
	} else {
		$returnArr["errCode"]=array("2"=>2);
		$returnArr["errMsg"]="Session Error: Could not update login number: ".$result["errMsg"];
	}

	return $returnArr;
}


/*
function checkSession
Purpose: To check if session is active
Accepts: db conn
returns: standard format error or success array
*/
function checkSession($conn){

	global $sessionTimeout, $blanks;
	
	$returnArr = array();
	
	if(isset($_SESSION['user']) && !(in_array($_SESSION['user'], $blanks))){
		
		$sessionTimeQuery = sprintf("SELECT session_start_time FROM user_info WHERE user_email='%s'", $_SESSION['user']);
		$result = runQuery($sessionTimeQuery, $conn);

		$result = mysql_fetch_array($result["dbResource"]);
		$time = $result['session_start_time'];
		
		$currentTime = date("Y-m-d H:i:s", time());
				
		$diff = (strtotime($currentTime)-strtotime($time));

		if($diff < ($sessionTimeout*60))
		{
			$returnArr["errCode"]=array("-1"=>-1);
			$returnArr["errMsg"]="Session Active";
		} else{
			$returnArr["errCode"][2]=2;
			$returnArr["errMsg"]="Session timedout";
		}
	} else {
		session_destroy();
		$returnArr["errCode"][2]=2;
		$returnArr["errMsg"]="Session Inctive";
	}
	
	return $returnArr;
}

?>