<?php
session_start();

require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/manageUserModel.php");

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

$allRubrics = getAllDoctors($conn);
if(noError($allRubrics)){
    $allRubrics = $allRubrics["errMsg"];
    $returnArr["Result"]="OK";
    foreach($allRubrics as $rubricId=>$rubricDetails){
        $returnArr["Records"][]=array("user_id"=>$rubricId,
                                     "user_email"=>$rubricDetails["user_email"],
                                     "user_mob"=>$rubricDetails["user_mob"],
                                     "status"=>$rubricDetails["status"],                                 
                                     );
    }    
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all rubrics data";
}    
print(json_encode($returnArr));
?>