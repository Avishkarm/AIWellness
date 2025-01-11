<?php

require_once("../utilities/config.php");
require_once("../utilities/dbutils.php");
include("../models/contactDoctorsModel.php");
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
} else {
	    //printArr("Database Error");
	exit;
}

if(isset($_POST) && !empty($_POST)){
	$doctorName=cleanQueryParameter($conn,cleanXSS($_POST['doctorName']));
	$locationName=cleanQueryParameter($conn,cleanXSS($_POST['locationName']));
	
	$getDoctorsContact=getDoctorsContact($doctorName,$locationName,$conn);
		//printArr($getHomeopathyModalInfo);
	echo json_encode($getDoctorsContact);		
}
?>