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
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $newQuestionName = cleanQueryParameter($conn,$_POST["Name"]);
        $newQuestionConclusion = cleanQueryParameter($conn,$_POST["conclusion"]);
        $newQuestionFG = cleanQueryParameter($conn,$_POST["fh_g"]);
        $newQuestionFP = cleanQueryParameter($conn,$_POST["fh_p"]);
        $newQuestionFE = cleanQueryParameter($conn,$_POST["fh_e"]);
        $newQuestionSG = cleanQueryParameter($conn,$_POST["sh_g"]);
        $newQuestionSP = cleanQueryParameter($conn,$_POST["sh_p"]);
        $newQuestionSE = cleanQueryParameter($conn,$_POST["sh_e"]);
        $newQuestionFHS = cleanQueryParameter($conn,$_POST["fh_s"]);
        $newQuestionSHS = cleanQueryParameter($conn,$_POST["sh_s"]);
        $newQuestionGetMedicine = cleanQueryParameter($conn,$_POST["GetMedicine"]);
        $newQuestion = addNewQuestion($newQuestionName,$newQuestionConclusion,$newQuestionFG,$newQuestionFP,$newQuestionFE,$newQuestionSG,$newQuestionSP,$newQuestionSE,$newQuestionFHS,$newQuestionSHS,$newQuestionGetMedicine, $conn);
        if(noError($newQuestion)){
            $returnArr["Result"]="OK";
            $returnArr["Record"]=array("questionId"=>$newQuestion["errMsg"],
                                 "Name"=>$newQuestionName,
                                 "conclusion"=>$newQuestionConclusion,
                                 "fh_g"=>$newQuestionFG,
                                 "fh_p"=>$newQuestionFP,
                                 "fh_e"=>$newQuestionFE,
                                 "sh_g"=>$newQuestionSG,
                                 "sh_p"=>$newQuestionSP,
                                 "sh_e"=>$newQuestionSE,
                                 "fh_s"=>$newQuestionFHS,
                                 "sh_s"=>$newQuestionSHS,
                                 "GetMedicine"=>$newQuestionGetMedicine
                                 );

        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error adding new rubric".$newQuestion['errMsg'];
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