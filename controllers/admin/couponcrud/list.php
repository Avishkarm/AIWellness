<?php
session_start();

require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/couponModel.php");

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

$allRubrics = getAllCoupon($conn);
if(noError($allRubrics)){
    $allRubrics = $allRubrics["errMsg"];
    $returnArr["Result"]="OK";
    foreach($allRubrics as $rubricId=>$rubricDetails){
        $returnArr["Records"][]=array("couponId"=>$rubricId,
                                     "title"=>$rubricDetails["title"],
                                     "description"=>$rubricDetails["description"],
                                      "expiry"=>$rubricDetails["expiry_date"],
                                     "discount"=>$rubricDetails["percent_discount"]                                 
                                     );
    }    
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all rubrics data";
}    
print(json_encode($returnArr));
?>