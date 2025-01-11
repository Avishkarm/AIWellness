<?php
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/2opinionConclusionModel.php");

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

$allQuestion = getAllQuestions($conn);
if(noError($allQuestion)){
    $allQuestion = $allQuestion["errMsg"]; 
    $returnArr["Result"]="OK";
    foreach($allQuestion as $questionId=>$QuestionDetails){
        $returnArr["Records"][]=array("questionId"=>$questionId,
                                     "Name"=>$QuestionDetails["title"],
                                     "conclusion"=>$QuestionDetails["conclusion"],
                                     "fh_g"=>$QuestionDetails["fh_g"],
                                     "fh_p"=>$QuestionDetails["fh_p"],
                                     "fh_e"=>$QuestionDetails["fh_e"],
                                     "sh_g"=>$QuestionDetails["sh_g"],
                                     "sh_p"=>$QuestionDetails["sh_p"],
                                     "sh_e"=>$QuestionDetails["sh_e"],
                                     "fh_s"=>$QuestionDetails["fh_s"],
                                     "sh_s"=>$QuestionDetails["sh_s"],
                                     "GetMedicine"=>$QuestionDetails['get_medicine_status']
                                     );
    }    
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all rubrics data";
}    
print(json_encode($returnArr));
?>