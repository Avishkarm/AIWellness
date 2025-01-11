<?php
session_start();

require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/rubricsModel.php");


$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $newRubricName = cleanQueryParameter($conn, $_POST["Name"]);
        $newRubrictype = cleanQueryParameter($conn, $_POST["type"]);
        $newRubricusergroup = cleanQueryParameter($conn, $_POST["usergroup"]);
        $newRubricGender = cleanQueryParameter($conn, $_POST["gender"]);
        $newRubricAge = cleanQueryParameter($conn, $_POST["age"]);
        $newRubricDoctorOrder = cleanQueryParameter($conn, $_POST["doctors_order"]);
        $newRubricPatientOrder = cleanQueryParameter($conn, $_POST["patients_order"]);
        $rubricId = cleanQueryParameter($conn, $_POST["rubricId"]);
        $editRubric = editRubric($newRubricName,$newRubrictype,$newRubricusergroup,$newRubricGender,$newRubricAge, $rubricId,$newRubricDoctorOrder,$newRubricPatientOrder, $conn);
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