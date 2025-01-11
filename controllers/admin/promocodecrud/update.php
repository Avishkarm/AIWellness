<?php
session_start();

require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/couponModel.php");


$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];	
        $newCouponTitle = cleanQueryParameter($conn, $_POST["title"]);
        $newCouponDescription = cleanQueryParameter($conn, $_POST["description"]);
        $newCouponExpiryDate = cleanQueryParameter($conn, $_POST["expiry"]);
        $newCouponDiscount = cleanQueryParameter($conn, $_POST["discount"]);
        $couponId = cleanQueryParameter($conn, $_POST["couponId"]);
        $editRubric = editCoupon($newCouponTitle,$newCouponDescription,$newCouponExpiryDate,$newCouponDiscount,$couponId,$conn);
        //printArr($editRubric);
        if(noError($editRubric)){
            $returnArr["Result"]="OK";
        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error editing coupon".$editRubric['errMsg'];
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