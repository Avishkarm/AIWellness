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
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $newModalitiesName = cleanQueryParameter($conn,$_POST["modalitiesName"]);
        $newModalitiesRemedies = cleanQueryParameter($conn,$_POST["modalitiesRemedies"]);
       
        $ModalitiesId = cleanQueryParameter($conn,$_POST["modalitiesId"]);
        $editModalities = editModalities($newModalitiesName,$newModalitiesRemedies,$ModalitiesId, $conn);
        if(noError($editModalities)){
            $returnArr["Result"]="OK";
        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error editing Modalities";
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