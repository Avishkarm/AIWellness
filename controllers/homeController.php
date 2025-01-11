<?php

require_once("../utilities/config.php");
require_once("../utilities/dbutils.php");
include("../models/homeModel.php");
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
} else {
	    //printArr("Database Error");
	exit;
}

if(isset($_POST) && !empty($_POST)){
	$name=$_POST['data'];
	$diseaseName=$_POST['diseaseName'];
	if(!empty($name)){
		$getSuggetions=getDiseaseNameSuggetion($name,$conn);
		//printArr($getSuggetions);
		$data=array();
		$newData=array();
		foreach ($getSuggetions['errMsg'] as $key => $value) {
			# code...

			$Diagnostic_term=utf8_encode($value['Diagnostic_term']);
			 $Common_name=utf8_encode($value['Common_name']);
			 //$Common_name=str_replace("\u00a0", "",$Common_name);
			 //$Diagnostic_term=str_replace("\u00a0", "",$Diagnostic_term);
			// echo $Diagnostic_term;
			array_push($data, cleanQueryParameter($conn,strtolower($Diagnostic_term)));
			array_push($data, cleanQueryParameter($conn,strtolower($Common_name)));
			
		}
		//printArr($data);
		$data=array_unique($data);
		//printArr($data);
		foreach ($data as $key => $value){
			$value=str_replace("\u00a0", "",$value);
			$value=stripcslashes($value);
			array_push($newData, ucfirst($value));
		}		
		$newData=array_unique($newData);
		echo json_encode($newData);
	}

	if(!empty($diseaseName)){
		//echo $diseaseName;
		$getHomeopathyModalInfo=getHomeopathyModalInfo($diseaseName,$conn);
		// printArr($getHomeopathyModalInfo['errMsg']);

		echo json_encode($getHomeopathyModalInfo['errMsg']);		
	}

}
?>