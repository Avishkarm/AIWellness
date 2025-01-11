<?php
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/complaintModel.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $id = cleanQueryParameter($conn,$_POST["id"]);
        $id=explode(",",$id);
       //$id1 = cleanQueryParameter($conn,$_POST["priority_id"]);
       for($i=0;$i<=sizeof($id);$i++)
        {   
            if(!empty($id[$i])){
             $newComplaint["id"] = cleanQueryParameter($conn,$id[$i]);
            $deleteQuestion = deleteComplaint($newComplaint["id"], $conn);
            }
        }
        
        if(noError($deleteQuestion)){
            $returnArr["Result"]="OK";
        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error deleting question";
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