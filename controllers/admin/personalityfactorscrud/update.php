<?php
session_start();

require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/personalityFactorMasterModel.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $newFactorName = cleanQueryParameter($conn,$_POST["Name"]);
        $newFactorTitle = cleanQueryParameter($conn,$_POST["title"]);
		$newLowScoreDescr = cleanQueryParameter($conn,$_POST["LowScoreDescr"]);
		$newHighScoreDescr = cleanQueryParameter($conn,$_POST["HighScoreDescr"]);
		$newLowScoreRems = cleanQueryParameter($conn,$_POST["LowScoreRemedies"]);
		$newHighScoreRems = cleanQueryParameter($conn,$_POST["HighScoreRemedies"]);
        $factorId = cleanQueryParameter($conn,$_POST["factorId"]);
        
        $newLowScoreRems=implode(",",array_unique(explode(',',$newLowScoreRems,-1)));
        $newHighScoreRems=implode(",",array_unique(explode(',',$newHighScoreRems,-1)));

        $editRemedy = editPersonalityFactor($newFactorName,$newFactorTitle, $newLowScoreDescr, $newHighScoreDescr, $newLowScoreRems, $newHighScoreRems, $factorId, $conn);
        if(noError($editRemedy)){
            $returnArr["Result"]="OK";
        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error editing personality factor";
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