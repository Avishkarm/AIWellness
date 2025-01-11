<?php 
////////////////////////////DB handlers//////////////////////////////////

/*
function: createDbConnection
Purpose: Creates a connection to the MySQL database and selectes the right database
Returns: A db connection string
Arguments: Connection parameters: servername, dbusername, password, database name
*/
function createDbConnection($servername, $username, $password, $dbname) {
	$returnArr = array();
	
	
	$conn = @mysqli_connect($servername,$username,$password );	

	if(!$conn){
		$returnArr["errCode"][5] = 5;
		$returnArr["errMsg"] = "Could not connect to DB: ".mysqli_error();
	} else{
		if(!mysqli_select_db($conn, $dbname)){		
			$returnArr["errCode"][6] = 6;
			$returnArr["errMsg"] = "Could not select DB: ".mysqli_error();
		} else {
			//Added to Get UTF-8 data
			mysqli_query("SET NAMES 'utf8'", $conn);
			$returnArr["errCode"][-1] = -1;
			$returnArr["errMsg"] = $conn;
		}
	}
		
	return $returnArr;
}

/*
function: runQuery
Purpose: executes the query and performs error handling
Returns: success/error array
Arguments: the query to run and the db connection string
*/
function runQuery($query, $conn) {
	$returnArr = array();

	$result = mysqli_query($conn, $query);				
			
	if (!$result) {
		$returnArr["errCode"][3] = 3;
		$returnArr["errMsg"] = "Query Error: ".mysqli_error($conn);
		$returnArr["query"] = $query;		
	} else {
		
		
		$returnArr["errCode"][-1] = -1;
		$returnArr["errMsg"] = "Query Successful";
		$returnArr["dbResource"] = $result;
	}
	
	return $returnArr;
}
/*
function: startTransaction
Purpose: executes the transaction query and performs error handling
Returns: success/error array
Arguments: db connection string
*/
function startTransaction($conn) {
	
	$returnArr = array();
	
	$result = mysqli_query("START TRANSACTION", $conn);
	if (!$result) {
		$returnArr["errCode"][7] = 7;
		$returnArr["errMsg"] = "Could not start transaction: ".mysqli_error($conn);		
	} else {
		$returnArr["errCode"][-1] = -1;
		$returnArr["errMsg"] = "Transaction started";
	}
	
	return $returnArr;
}

/*
function: commitTransaction
Purpose: executes the transaction query and commits it
Returns: success/error array
Arguments: db connection string 
*/
function commitTransaction($conn) {
	
	$returnArr = array();
	
	$result = mysqli_query("COMMIT", $conn);
	if (!$result) {
		$returnArr["errCode"][8] = 8;
		$returnArr["errMsg"] = "Could not commit transaction: ".mysqli_error($conn);
	} else {
		$returnArr["errCode"][-1] = -1;
		$returnArr["errMsg"] = "Transaction committed";
	}
	
	return $returnArr;
}

function cleanQueryParameter($conn,$string) {

	//remove extraneous spacess
	$string = trim($string);

	/* prevents duplicate backslashes:
	One way to check if Magic Quotes is running is to run get_magic_quotes_gpc(). 
	*/
	if(get_magic_quotes_gpc()) { 
		$string = stripslashes($string);
	}
		
	/*escape the string with backward compatibility
	Escapes special characters in the unescaped_string, 
	taking into account the current character set of the connection so that it is safe to place it in a mysql_query(). 
	mysql_real_escape_string() calls MySQL's library function mysql_real_escape_string, 
	which prepends backslashes to the following characters: \x00, \n, \r, \, ', " and \x1a.	
	*/
	if (phpversion() >= '4.3.0'){
		$string = mysqli_real_escape_string($conn,$string);
	} else{
		$string = mysqli_escape_string($conn,$string);
	}
	return $string;
}

/*
function: rollbackTransaction
Purpose: roll back to the commit point 
Returns: success/error array
Arguments: db connection string
*/
function rollbackTransaction($conn) {
	
	$returnArr = array();
	
	$result = mysqli_query("ROLLBACK", $conn);
	if (!$result) {
		$returnArr["errCode"][9] = 9;
		$returnArr["errMsg"] = "Could not rollback transaction: ".mysqli_error($conn);
	} else {
		$returnArr["errCode"][-1] = -1;
		$returnArr["errMsg"] = "Transaction rolled back";
	}
	
	return $returnArr;
}

function runTransactionedQuery($query, $conn) {
	$returnArr = array();
	
	$startTransaction = startTransaction($conn);
	if(noError($startTransaction)){
		$result = runQuery($query, $conn);
		if(noError($result)){
			commitTransaction($conn);
			$returnArr["errCode"][-1] = -1;
			$returnArr["errMsg"] = "Query Transaction successfully commited.";
		} else {			
			rollbackTransaction($conn);
			$returnArr["errCode"][4] = 4;
			$returnArr["errMsg"] = "Query Transaction Failure. DB state rolled back. ".$result["errMsg"];
		}
	}
	
	return $returnArr;
}
function roundToTwoDecimals($varToBeRounded) {
    
  return  number_format((double)$varToBeRounded, 2, '.', '');
    
}


?>