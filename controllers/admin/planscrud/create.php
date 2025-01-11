<?php
session_start();


require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/planModel.php");


$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";

    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){

    
        $user = $_SESSION["user"];	
        $newPlanTitle = cleanQueryParameter($conn, $_POST["title"]);
        $newPlanDuration = cleanQueryParameter($conn, $_POST["duration"]);
        $newPlan = addNewPlan($newPlanTitle,$newPlanDuration, $conn); //echo "hii";
        if(noError($newPlan)){
            $returnArr["Result"]="OK";
            $returnArr["Record"]=array("rubricId"=>$newPlan["errMsg"],
                                 "title"=>$newPlanTitle,
                                 "duration"=>$newPlanDuration
                                 );

        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error adding new rubric".$newPlan['errMsg'];
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