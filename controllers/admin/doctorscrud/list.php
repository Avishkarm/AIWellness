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
} else {
	printArr("Database Error");
	exit;
}


$user = "";
if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
	$user = $_SESSION["user"];	
} else {
	printArr("You do not have sufficient privileges to access this page");
	exit;
}	

$allContacts = getAllContacts($conn);
if(noError($allContacts)){
    $allContacts = $allContacts["errMsg"]; 
    $returnArr["Result"]="OK";
    foreach($allContacts as $doctorId=>$ContactDetails){
        
        $returnArr["Records"][]=array("doctorId"=>$doctorId,
                                     "Name"=>$ContactDetails["Name"],
                                     //"clinic_name"=>$ContactDetails["clinic_name"],
                                     "location"=>$ContactDetails["location"],
                                     //"ph_no"=>$ContactDetails["ph_no"],
                                     //"dct_email"=>$ContactDetails["dct_email"],
                                     //"timings"=>$ContactDetails["timings"],
                                     //"other_comments"=>$ContactDetails["other_comments"],
                                     //"title"=>$ContactDetails["title"],
                                     //"description"=>$ContactDetails['description'],
                                     "price"=>$ContactDetails['price'],
                                     "rating"=>$ContactDetails['rating'],
                                     "image"=>$ContactDetails['image']
                                     );
    }    
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all doctors data";
}    
print(json_encode($returnArr));
?>