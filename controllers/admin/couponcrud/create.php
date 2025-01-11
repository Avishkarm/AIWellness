<?php
session_start();

require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/couponModel.php");
require_once("../../../models/admin/promocodeModel.php");


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
        $newCoupon = addNewCoupon($newCouponTitle,$newCouponDescription,$newCouponExpiryDate,$newCouponDiscount,$conn); 
        if(noError($newCoupon)){
            $returnArr["Result"]="OK";
            $returnArr["Record"]=array("couponId"=>$newCoupon["errMsg"],
                                 "title"=>$newCouponTitle,
                                 "description"=>$newCouponDescription,
                                 "expiry"=>$newCouponExpiryDate,
                                 "discount"=>$newCouponDiscount
                                 );

            $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
             $newCouponId=$newCoupon["errMsg"];
        for($i=0;$i<100;$i++){
            $res="";
            for($j=0;$j<8;$j++){
                $res .= $chars[mt_rand(0, strlen($chars)-1)];
            }
            $newCouponCode=$res;
            $addNewPromocode=addNewPromocode($newCouponId,$newCouponCode, $conn);
            //printArr($addNewPromocode);
        }

        } else {
            $returnArr["Result"]="ERROR";
            $returnArr["Message"]="Error adding new coupon".$newCoupon['errMsg'];
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

        /*$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        
        if(!empty($_POST['count'])){
            $count=$_POST['count'];
        }else{
            $count=10;
        }
        for($i=0;$i<=$count;$i++){
            $res .= $chars[mt_rand(0, strlen($chars)-1)];
            echo $res."<br>";
        }

*/

       
?>