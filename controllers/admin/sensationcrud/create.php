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
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $newSensationName = cleanQueryParameter($conn,$_POST["sensationName"]);
        $newSensationRemedies = cleanQueryParameter($conn,$_POST["sensationRemedies"]);
        
        $newSensation = addNewSensation($newSensationName,$newSensationRemedies, $conn);
        if(noError($newSensation)){
            $returnArr["Result"]="OK";
            $returnArr["Record"]=array("sensationId"=>$newSensation["errMsg"], "sensationName"=>$newSensationName,"sensationRemedies"=>$newSensationRemedies);

        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error adding new Sensation";
        }    
    } else {        
        $returnArr["Result"]="ERROR";
        $returnArr["Message"]="You do not have sufficient privileges to access this page";
    }
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Database Error";
}

print(json_encode($returnArr));

       
?>