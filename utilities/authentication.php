<?php

function generateSalt(){

	$salt_length = 12;

	$salt = substr(md5(uniqid()), 0, $salt_length);

	return $salt;

}

function generatePassword($length = 9, $add_dashes = false, $available_sets = 'luds')
{
     $sets = array();
        if(strpos($available_sets, 'l') !== false)
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
        $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
        $sets[] = '23456789';
        if(strpos($available_sets, 's') !== false)
        $sets[] = '!#@$%&';
       

        $all = '';
        $password = '';
        foreach($sets as $set)
        {
        $password .= $set[array_rand(str_split($set))];
        $all .= $set;
        }

        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++)
        $password .= $all[array_rand($all)];

        $password = str_shuffle($password);

        if(!$add_dashes)
        return $password;

        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
        $dash_str .= substr($password, 0, $dash_len) . '-';
        $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
}

function encryptPassword($pwd, $salt){

    $hashed_password = sha1($salt . $pwd);

	return $hashed_password;

}

/*
** Creates a user account. 
** 1. Checks if curr user is admin.
** 2. Generates a password and makes an entry in user info table
** 3. Accepts an array of info: 
		array(
			[type]=>2=STUDENT/3=SUPERVISOR
			[fname]=>First name
			[lname]=>Last name
			[username]=>chosen username i.e email address
			[email_addr]=>chosen email address
			[password]=>chosen password
		)
** 4. returns: error/success
		returnArr=array(
			[errCode]=>errorCode from error table
			[errMsg]=>Custom error message
		}
*/
function createUserAccount($userInfo, $conn){
	global $blanks;
	
	$returnArr = array();
	
	//validate username field
	if(in_array($userInfo, $blanks)){
		$returnArr["errCode"][1]=1;
		$returnArr["errMsg"]="Invalid or blank";			
	} else {
		//setting default user type=2: 
		if(in_array($userInfo["type"], $blanks) || is_nan($userInfo["type"])){
			$userInfo["type"]=0;   // by default for customer
			
		}
		else{
			$userInfo["type"]=2; // for merchant
		}
		// setting join method
                if(in_array($userInfo["join_method"], $blanks) || is_nan($userInfo["join_method"])){
			$userInfo["join_method"]=9;   // by default for customer
			
		}
		else{
			$join_method=$userInfo["join_method"]; // for merchant
		}
		//everything seems to be ok. Lets create the account
		//generate salt and password. Default password=username
		$salt = generateSalt();
		
		$password = encryptPassword($userInfo["password"], $salt);
		
		$query = sprintf("INSERT INTO users (user_first_name, user_last_name, salt, user_password, user_email,user_mob,user_type_id, join_method) VALUES('%s', '%s', '%s', '%s', '%s', '%s','%s','%s')", strip_tags($userInfo['name']), strip_tags($userInfo["lname"]), $salt, $password, strip_tags($userInfo["email"]),strip_tags($userInfo['mobno']), strip_tags($userInfo["type"]),strip_tags($userInfo["join_method"]));
	
		
		$result = runQuery($query, $conn);
		//error
	
		if(noError($result)){
			$returnArr["errCode"]=array("-1"=>-1);
			$returnArr["errMsg"]="User Account successfully created.";
		} else {
			$returnArr["errCode"]=array("5"=>5);
			$returnArr["errMsg"]="User Account NOT created: ".$result["errMsg"];
		}
	}
	
	
			return $returnArr;
	
	
}

/*
** function checkPassword
** 1. retrieves salt
** 2. encrypts provided password
** 3. checks if provided password matches
*/
function checkPassword($email, $password, $conn){
	
	
	
	//check if username already exists
	//$userInfo = getUserInfo($user_mob, $conn);
	$userInfo=getUserInfo($email, $conn);
	if(noError($userInfo)){
		$userInfo = $userInfo["errMsg"];
		//print_r("$userInfo<br>");
		$salt = $userInfo["salt"];
		//print_r("$password<br>");
        $pwd = encryptPassword($password, $salt);
		//print_r($pwd."<br/>");print_r($userInfo["user_password"]);exit(1);

		
		if($pwd==$userInfo["user_password"]){
			$returnArr["errCode"]=array("-1"=>-1);
			$returnArr["errMsg"]="Login Successful.";
		} else {
			$returnArr["errCode"][10]=10;
			$returnArr["errMsg"]="Incorrect Username/Password. Please try to login again.";
		}
	} else {
		//error fetching user info
		$returnArr["errCode"][$userInfo["errCode"]] = $userInfo["errCode"];
		$returnArr["errMsg"] = "Error Fetching User Info: ".$userInfo["errMsg"];
	}
			return $returnArr;
	
}


/*
** function checkPasswordWithType
** 1. retrieves salt
** 2. encrypts provided password
** 3. checks if provided password matches
*/
function checkPasswordWithType($user_email,$password,$type,$conn){

	//check if username already exists
	$userInfo = getUserInfoWithUserType($user_email,$type,$conn);
	
	if(noError($userInfo)){
		$userInfo = $userInfo["errMsg"];
		$salt = $userInfo["salt"];
		//print_r("$password<br>");
        $pwd = encryptPassword($password, $salt);
		//print_r($pwd);print_r($userInfo["user_password"]);exit(1);

		
		if($pwd==$userInfo["user_password"]){
			$returnArr["errCode"]=array("-1"=>-1);
			$returnArr["errMsg"]="Login Successful.";
		} else {
			$returnArr["errCode"][10]=10;
			$returnArr["errMsg"]="Incorrect Username/Password. Please try to login again.";
		}
	} else {
		//error fetching user info
		$returnArr["errCode"][$userInfo["errCode"]] = $userInfo["errCode"];
		$returnArr["errMsg"] = "Error Fetching User Info: ".$userInfo["errMsg"];
	}

	return $returnArr;
	
}

?>