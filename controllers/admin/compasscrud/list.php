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

$allCompassData = getAllCompassData($conn);
if(noError($allCompassData)){
    $allCompassData = $allCompassData["errMsg"];
    $returnArr["Result"]="OK";
    foreach($allCompassData as $compassId=>$compassDetails){
        $returnArr["Records"][]=array("compassId"=>$compassId,
                                     "Name"=>$compassDetails["title"],
                                     "url"=>$compassDetails["video_url"],
                                     "description"=>$compassDetails["description"]
                                     );
    }    
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Error fetching all compass data";
}    
print(json_encode($returnArr));
?>