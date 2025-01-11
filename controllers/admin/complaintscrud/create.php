<?php
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/complaintModel.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();
$newComplaint=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $newComplaint["priority_id"] = cleanQueryParameter($conn,$_POST["priority_id"]);
        $newComplaint["Diagnostic_term"] = cleanQueryParameter($conn,$_POST["Diagnostic_term"]);
        $newComplaint["Definition"] = cleanQueryParameter($conn,$_POST["Definition"]);
        //$newComplaint["Common_name"] = cleanQueryParameter($conn,$_POST["Common_name"]);
        $Common_name = cleanQueryParameter($conn,$_POST["Common_name"]);
        $newComplaint["Expert_Comments"] = cleanQueryParameter($conn,$_POST["Expert_Comments"]);
        $newComplaint["Possibility"] = cleanQueryParameter($conn,$_POST["Possibility"]);
        $newComplaint["CTA"] = cleanQueryParameter($conn,$_POST["CTA"]);
        $newComplaint["Duration"] = cleanQueryParameter($conn,$_POST["Duration"]);
        $newComplaint["Improvement_rate"] = cleanQueryParameter($conn,$_POST["Improvement_rate"]);
        $newComplaint['system'] = cleanQueryParameter($conn,$_POST['system']);
        $newComplaint['organ'] = cleanQueryParameter($conn,$_POST['organ']);
        $newComplaint['subOrgan'] = cleanQueryParameter($conn,$_POST['subOrgan']);
        $newComplaint['embryologcial'] = cleanQueryParameter($conn,$_POST['embryologcial']);
        $newComplaint['miasm'] = cleanQueryParameter($conn,$_POST['miasm']);

        $Common_name=explode(",",$Common_name);
        //printArr($Common_name);
       // printArr(sizeof($Common_name));
        for($i=0;$i<=sizeof($Common_name);$i++)
        {   
            if(!empty($Common_name[$i])){
            $newComplaint["Common_name"] = cleanQueryParameter($conn,$Common_name[$i]);
            $addComplaint = addNewComplaint($newComplaint, $conn);
            }
        }
      // $addComplaint = addNewComplaint($newComplaint, $conn);
        if(noError($addComplaint)){
            $returnArr["Result"]="OK";
            $returnArr["Record"]=array("id"=>$addComplaint['errMsg'],
                                     "priority_id"=>$newComplaint["priority_id"],
                                     "Diagnostic_term"=>$newComplaint["Diagnostic_term"],
                                     "Definition"=>$newComplaint["Definition"],
                                     //"Common_name"=>$newComplaint["Common_name"],
                                     "Common_name"=>$Common_name,
                                     "Expert_Comments"=>$newComplaint["Expert_Comments"],
                                     "Possibility"=>$newComplaint["Possibility"],
                                     "CTA"=>$newComplaint["CTA"],
                                     "Duration"=>$newComplaint["Duration"],
                                     "Improvement_rate"=>$newComplaint["Improvement_rate"],
                                     "system"=>$newComplaint['system'],
                                     "organ"=>$newComplaint["organ"],
                                     "subOrgan"=>$newComplaint["subOrgan"],
                                     "embryologcial"=>$newComplaint["embryologcial"],
                                     "miasm"=>$newComplaint["miasm"]
                                 );

        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error adding new rubric".$addComplaint['errMsg'];
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