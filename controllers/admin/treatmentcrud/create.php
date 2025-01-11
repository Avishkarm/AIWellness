<?php
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/2opinionTreatmentModel.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();


if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $newTreatmentName = cleanQueryParameter($conn,$_POST["Name"]);
       
        $newTreatment = addNewTreatment($newTreatmentName,$conn);
        if(noError($newTreatment)){
            $returnArr["Result"]="OK";
            $returnArr["Record"]=array("rubricId"=>$newTreatment["errMsg"],
                                 "Name"=>$newTreatmentName
                                 );

        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error adding new rubric".$newTreatment['errMsg'];
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