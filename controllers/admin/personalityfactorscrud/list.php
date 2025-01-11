<?php
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/personalityFactorMasterModel.php");

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

$allFactors = getPersonalityFactors($conn);
if(noError($allFactors)){
	$allFactors = $allFactors["errMsg"];
    $returnArr["Result"]="OK";
    foreach($allFactors as $factorId=>$factorDetails){
        $returnArr["Records"][]=array("factorId"=>$factorId, "Name"=>$factorDetails["factor_name"],"title"=>$factorDetails["factor_title"], "LowScoreDescr"=>$factorDetails["low_score_description"], "HighScoreDescr"=>$factorDetails["high_score_description"], "LowScoreRemedies"=>$factorDetails["low_score_remedies"], "HighScoreRemedies"=>$factorDetails["high_score_remedies"]);
    }    
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all Factors";
}  

print(json_encode($returnArr));
?>