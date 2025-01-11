<?php
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/2opinionTreatmentSubtypeModel.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $newTreatmentName = cleanQueryParameter($conn,$_POST["Name"]);
        $treatmentId = cleanQueryParameter($conn,$_POST["treatmentId"]);
        $type_id=$_GET['type_id'];
        $editTreatment = editTreatmentSubtype($newTreatmentName,$treatmentId,$type_id, $conn);
        //printArr($editTreatment);
        if(noError($editTreatment)){
            $returnArr["Result"]="OK";
        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error editing rubric".$editTreatment['errMsg'];
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