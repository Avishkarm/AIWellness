<?php
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/subscriberModel.php");

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



$allQuestion = getAllSubscribers($conn);
//printArr($allRemedies);
if(noError($allQuestion)){
    $allQuestion = $allQuestion["errMsg"]; 
    $returnArr["Result"]="OK";
    foreach($allQuestion as $complaintId=>$QuestionDetails){
//echo $QuestionDetails['Diagnostic_term'].'<br>'. $complaintId;
        $returnArr["Records"][]=array("s_id"=>$complaintId,
                                     "name"=>$QuestionDetails["name"],
                                     "email"=>$QuestionDetails["email"],
                                     "mobile"=>$QuestionDetails["mobile"]
                                     );
    } 
}else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all subscriber data";
}    

print(json_encode($returnArr));
?>