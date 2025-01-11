<?php
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/doctorsContactModel.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
         $newName = cleanQueryParameter($conn,$_POST["Name"]);
        //$newclinicname = cleanQueryParameter($conn,$_POST["clinic_name"]);
        $newlocation = cleanQueryParameter($conn,$_POST["location"]);
        //$newphno = cleanQueryParameter($conn,$_POST["ph_no"]);
        //$newemail = cleanQueryParameter($conn,$_POST["dct_email"]);
        //$newtimings = cleanQueryParameter($conn,$_POST["timings"]);
        //$newcomments = cleanQueryParameter($conn,$_POST["other_comments"]);
        //$newtitle = cleanQueryParameter($conn,$_POST["title"]);
        //$newdescription = cleanQueryParameter($conn,$_POST["description"]);
         $newprice = cleanQueryParameter($conn,$_POST["price"]);
        $newrating = cleanQueryParameter($conn,$_POST["rating"]);
        $doctorId = cleanQueryParameter($conn,$_POST["doctorId"]);         
        $editContact = editContact($newName,$newlocation,$newprice,$newrating,$doctorId, $conn);
        if(noError($editContact)){
            $returnArr["Result"]="OK";
        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error editing question".$editContact['errMsg'];
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