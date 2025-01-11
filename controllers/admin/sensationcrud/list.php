<?php
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/sensationModel.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
    $conn = $conn["errMsg"];
} else {
    printArr("Database Error");
    exit;
}


$user = "";
if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
	$user = $_SESSION["user"];	
} else {
	printArr("You do not have sufficient privileges to access this page");
	exit;
}	

$allSensations = getAllSensations($conn);
if(noError($allSensations)){
    $allSensations = $allSensations["errMsg"];
    $returnArr["Result"]="OK";
    foreach($allSensations as $sensationId=>$sensationDetails){
        $returnArr["Records"][]=array("sensationId"=>$sensationId, "sensationName"=>$sensationDetails["sensationName"],"sensationRemedies"=>$sensationDetails["sensationRemedies"]);
    }    
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all Sensations data";
}    
print(json_encode($returnArr));
?>