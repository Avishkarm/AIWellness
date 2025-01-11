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
        $newContact = addNewContact($newName,$newlocation,$newprice,$newrating,$conn);
        if(noError($newContact)){
            $returnArr["Result"]="OK";
            $returnArr["Record"]=array("doctorId"=>$newContact["errMsg"],
                                 "Name"=>$newName,
                                 //"clinic_name"=>$newclinicname,
                                 "location"=>$newlocation,
                                 //"ph_no"=>$newphno,
                                 //"dct_email"=>$newemail,
                                 //"timings"=>$newtimings,
                                 //"other_comments"=>$newcomments,
                                 //"title"=>$newtitle,
                                 //"description"=>$newdescription,
                                 "price"=>$newprice,
                                 "rating"=>$newrating,
                                 );

        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error adding new contact".$newContact['errMsg'];
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