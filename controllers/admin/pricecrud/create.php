<?php
session_start();


require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/priceModel.php");


$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

//echo "hiii";
if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $newPlanId = cleanQueryParameter($conn, $_GET["planId"]);
        $newRegionId = cleanQueryParameter($conn, $_POST["regionId"]);
        $newAmount = cleanQueryParameter($conn, $_POST["amount"]);
        $newRubric = addNewPlanPrice($newPlanId,$newRegionId,$newAmount, $conn);
        if(noError($newRubric)){
            $returnArr["Result"]="OK";
            $returnArr["Record"]=array("priceId"=>$newRubric["errMsg"],
                                 "PlanId"=>$newPlanId,
                                 "regionId"=>$newRegionId,
                                 "amount"=>$newAmount
                                 );

        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error adding new rubric".$newRubric['errMsg'];
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