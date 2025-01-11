<?php
session_start();

require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/priceModel.php");


$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";

    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $newPlanId = cleanQueryParameter($conn, $_GET["planId"]);
        $newRegionId = cleanQueryParameter($conn, $_POST["regionId"]);
        $newAmount = cleanQueryParameter($conn, $_POST["amount"]);
        $priceId = cleanQueryParameter($conn, $_POST["priceId"]);

        $editRubric = editPlanPrice($newPlanId,$newRegionId,$newAmount, $priceId, $conn);

        if(noError($editRubric)){
            $returnArr["Result"]="OK";
        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error editing rubric".$editRubric['errMsg'];
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