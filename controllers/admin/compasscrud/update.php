<?php
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/compassConclusionModel.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $newcompassName = cleanQueryParameter($conn,$_POST["Name"]);
        $newcompassurl = cleanQueryParameter($conn,$_POST["url"]);
        $newcompassdescription = cleanQueryParameter($conn,$_POST["description"]);
        $compassId = cleanQueryParameter($conn,$_POST["compassId"]);
        $editCompass = editCompass($newcompassName,$newcompassurl,$newcompassdescription,$compassId,$conn);
        if(noError($editCompass)){
            $returnArr["Result"]="OK";
        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error editing rubric".$editCompass['errMsg'];
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