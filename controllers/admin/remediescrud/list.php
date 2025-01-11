<?php
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/remediesModel.php");

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

$q = "";
if(isset($_POST["q"]))
	$q = $_POST["q"];

$allRemedies = getAllRemedies($conn, 1, $q);
//printArr($allRemedies);
if(noError($allRemedies)){
	$allRemedies = $allRemedies["errMsg"];
    $returnArr["Result"]="OK";
    foreach($allRemedies as $remedyId=>$remedyDetails){
        $returnArr["Records"][]=array("remedyId"=>$remedyId, "Name"=>$remedyDetails["remedy_name"],"remedy_full_name"=>$remedyDetails["remedy_full_name"],"remedy_description"=>stripcslashes(str_replace("\u00a0", "",cleanQueryParameter($conn,utf8_encode($remedyDetails["remedy_description"])))));
    }    
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all remedy data";
}    

print(json_encode($returnArr));
?>