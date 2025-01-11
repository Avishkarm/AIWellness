<?php 
function initializeXMLLog($userEmail){
    $retArray = array();
    $deviceType = "web";    
    $userIp = getClientIP();    
    $data = getLocationUserFromIP($userIp);    
    // check whether request parameter is email or account handle, if account handle then accordingly make the 
    // activity attributes
    if(preg_match("/^[^@]{1,64}@[^@]{1,255}$/", 
    	$userEmail)){        
    	$activity = "email";
    } else{        
    	$activity = "account_handle";    
    }    
    $activity_attribute=array();    
    $activity_attribute[$activity]=$userEmail;    
    $activity_attribute['timestamp']=date("Y-m-d h A");    
    $activity_attribute['browser']=getBrowserName();    
    $activity_attribute['userIp']=$data["userIP"];    
    $activity_attribute['device']=$deviceType;    
    $activity_attribute['country']=$data["country"];    
    $activity_attribute['state']=$data["state"];    
    $activity_attribute['city']=$data["city"];    
    $activity_attribute['gender']=$_SESSION["gender"];    
    $activity_attribute['username']=$_SESSION["first_name"]." ".$_SESSION["last_name"];    
    $request_attribute=array();    
    $retArray = array('activity' => $activity_attribute, 'request' => $request_attribute);    
    return $retArray;
}


function getClientIP(){
	$ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');    
	foreach ($ip_keys as $key) {        
	    if (array_key_exists($key, $_SERVER) === true) {            
		    foreach (explode(',', $_SERVER[$key]) as $ip) {                
			    // trim for safety measures                
			    $ip = trim($ip);                
			    // attempt to validate IP                
			    if (validate_ip($ip)) {                    
			    	return $ip;                
			    }            
		    }        
	    }    
	}    
	return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}
function getLocationUserFromIP($requestorIP){    
	$geopluginURL = 'http://www.geoplugin.net/php.gp?ip=' . $requestorIP;    
	$addrDetailsArr = unserialize(file_get_contents($geopluginURL));    
	$ipinfo = array();    
	$ipinfo["country"] = strtolower($addrDetailsArr['geoplugin_countryName']);    
	$ipinfo["city"] = strtolower($addrDetailsArr['geoplugin_city']);    
	$ipinfo["state"] = strtolower($addrDetailsArr['geoplugin_regionName']);    
	$ipinfo["userIP"] = $requestorIP;    
	$ipinfo["countryCode"] =  $addrDetailsArr['geoplugin_countryCode'];    
	return $ipinfo;
}
function getBrowserName(){    
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)        
	$brawserName = 'Internet explorer';    
	elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) 
	//For Supporting IE 11        
		$brawserName = 'Internet explorer';    
	elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)        
		$brawserName = 'Mozilla Firefox';    
	elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)        
		$brawserName = 'Google Chrome';    
	elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)        
		$brawserName = "Opera Mini";    
	elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)        
		$brawserName = "Opera";    
	elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)        
		$brawserName = "Safari";    
	else        $brawserName = 'Something else';    
	return $brawserName;
}

function validate_ip($ip){    
	if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE | FILTER_FLAG_IPV6) === false){
		return false;    
	}    
	return true;
}
?>