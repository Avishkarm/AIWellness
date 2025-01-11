<?php
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/modalitiesModel.php");

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

$allModalities = getAllModalities($conn);
if(noError($allModalities)){
    $allModalities = $allModalities["errMsg"];
    $returnArr["Result"]="OK";
    foreach($allModalities as $modalitiesId=>$modalitiesDetails){
        $returnArr["Records"][]=array("modalitiesId"=>$modalitiesId, "modalitiesName"=>$modalitiesDetails["modalitiesName"],"modalitiesRemedies"=>$modalitiesDetails["modalitiesRemedies"]);
    }    
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all modalities data";
}    
print(json_encode($returnArr));
?>