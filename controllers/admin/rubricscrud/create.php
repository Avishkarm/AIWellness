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
        $newRubric = addNewRubric($newRubricName,$newRubrictype,$newRubricusergroup,$newRubricGender,$newRubricAge,$newRubricDoctorOrder,$newRubricPatientOrder, $conn);
        if(noError($newRubric)){
            $returnArr["Result"]="OK";
            $returnArr["Record"]=array("rubricId"=>$newRubric["errMsg"],
                                 "Name"=>$newRubricName,
                                 "type"=>$newRubrictype,
                                 "usergroup"=>$newRubricusergroup,
                                 "gender"=>$newRubricGender,
                                 "age"=>$newRubricAge,
                                 "doctors_order"=>$newRubricDoctorOrder,
                                 "patients_order"=>$newRubricPatientOrder
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