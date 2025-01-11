<?php
session_start();

require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/remediesModel.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $newRemedyName = cleanQueryParameter($conn,$_POST["Name"]);
         $newRemedyName=strtolower($newRemedyName);
          $newRemedyFullName=cleanQueryParameter($conn,$_POST["remedy_full_name"]);
          $newRemedyDescription=stripcslashes(str_replace("\u00a0", "",cleanQueryParameter($conn,utf8_encode($_POST["remedy_description"]))));

        $newRemedy = addNewRemedy($newRemedyName,$newRemedyFullName,$newRemedyDescription, $conn);
        if(noError($newRemedy)){
            $returnArr["Result"]="OK";
            $returnArr["Record"]=array("remedyId"=>$newRemedy["errMsg"], "Name"=>$newRemedyName,"remedy_full_name"=>$newRemedyFullName,"remedy_description"=>$newRemedyDescription);
        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]=$newRemedy["errMsg"];
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