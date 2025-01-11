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
$type_id=$_GET['type_id'];
$allTreatment = getAllTreatmentSubtype($conn,$type_id);
if(noError($allTreatment)){
    $allTreatment = $allTreatment["errMsg"];
    $returnArr["Result"]="OK";
    foreach($allTreatment as $treatmentId=>$treatmentDetails){
        $returnArr["Records"][]=array("treatmentId"=>$treatmentId,
                                     "Name"=>$treatmentDetails["subtype_name"]
                                     );
    }    
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all treatment data";
}    
print(json_encode($returnArr));
?>