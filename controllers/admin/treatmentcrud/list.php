<?php
session_start();

require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/2opinionTreatmentModel.php");

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

$allTreatment = getAllTreatment($conn);
if(noError($allTreatment)){
    $allTreatment = $allTreatment["errMsg"];
    $returnArr["Result"]="OK";
    foreach($allTreatment as $treatmentId=>$treatmentDetails){
        $returnArr["Records"][]=array("treatmentId"=>$treatmentId,
                                     "Name"=>$treatmentDetails["type_name"]
                                     );
    }    
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all rubrics data";
}    
print(json_encode($returnArr));
?>