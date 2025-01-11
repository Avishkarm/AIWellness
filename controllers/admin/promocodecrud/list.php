<?php
session_start();

require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/promocodeModel.php");

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
$couponId=$_GET['couponId'];
$allRubrics = getAllPromocode($couponId,$conn);
if(noError($allRubrics)){
    $allRubrics = $allRubrics["errMsg"];
    $returnArr["Result"]="OK";
    foreach($allRubrics as $rubricId=>$rubricDetails){
        $returnArr["Records"][]=array("promocodeId"=>$rubricId,
                                     "code"=>$rubricDetails["code"],
                                     "used"=>$rubricDetails["used"],
                                      "used_by"=>$rubricDetails["used_by"]                                
                                     );
    }    
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all rubrics data";
}    
print(json_encode($returnArr));
?>