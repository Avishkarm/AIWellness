<?php
//error_reporting (E_ALL ^ E_DEPRECATED);
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/complaintModel.php");

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
   /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);*/

$allQuestion = getAllComplaints($conn);
//printArr($allQuestion);

if(noError($allQuestion)){
    $allQuestion = $allQuestion["errMsg"]; 
    $returnArr["Result"]="OK";
    foreach($allQuestion as $complaintId=>$QuestionDetails){
//echo $QuestionDetails['Diagnostic_term'].'<br>'. $complaintId;
        $returnArr["Records"][]=array("id"=>$QuestionDetails["uniqid"],
                                     "priority_id"=>$QuestionDetails["priority_id"],
                                     "Diagnostic_term"=>utf8_encode($QuestionDetails["Diagnostic_term"]),
                                     "Definition"=>utf8_encode($QuestionDetails["Definition"]),
                                     "Common_name"=>utf8_encode($QuestionDetails["Common_name"]),
                                     "Expert_Comments"=>utf8_encode($QuestionDetails["Expert_Comments"]),
                                     "Possibility"=>utf8_encode($QuestionDetails["Possibility"]),
                                     "CTA"=>$QuestionDetails["CTA"],
                                     "Duration"=>utf8_encode($QuestionDetails["Duration"]),
                                     "Improvement_rate"=>utf8_encode($QuestionDetails["Improvement_rate"]),
                                     "system"=>$QuestionDetails['system'],
                                     "organ"=>$QuestionDetails["organ"],
                                     "subOrgan"=>$QuestionDetails["subOrgan"],
                                     "embryologcial"=>$QuestionDetails["embryologcial"],
                                     "miasm"=>$QuestionDetails["miasm"]
                                     );
    }    
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all rubrics data";
}    
//printArr($returnArr);
echo json_encode($returnArr);

?>