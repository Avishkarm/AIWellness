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

        $newLowScoreRems=implode(",",array_unique(explode(',',$newLowScoreRems,-1)));
        $newHighScoreRems=implode(",",array_unique(explode(',',$newHighScoreRems,-1)));
		
        $newFactor = addNewPersonalityFactor($newFactorName,$newFactorTitle, $newHighScoreDescr, $newHighScoreRems, $newLowScoreDescr, $newLowScoreRems, $conn);
        if(noError($newFactor)){
            $returnArr["Result"]="OK";
            $returnArr["Record"]=array("factorId"=>$newFactor["errMsg"], "Name"=>$newFactorName,"title"=>$newFactorTitle, "LowScoreDescr"=>$newLowScoreDescr, "LowScoreRemedies"=>$newLowScoreRems, "HighScoreDescr"=>$newHighScoreDescr, "HighScoreRemedies"=>$newHighScoreRems);
        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error adding new remedy";
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