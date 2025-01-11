<?php
session_start();
//print_r($_POST);
$status=$_POST["status"];
$firstname=$_POST["firstname"];
$amount=$_POST["amount"];
$txnid=$_POST["txnid"];

$posted_hash=$_POST["hash"];
$key=$_POST["key"];
$productinfo=$_POST["productinfo"];
$email=$_POST["email"];
$phone = $_POST['phone'];
//$SALT = "e5iIg1jwi8";
$SALT = "XxLiRUjZ";
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php"); 
require_once("../../models/userModel.php");
include("../../models/paymentModel.php");
require_once("../../models/admin/planModel.php");  

 $conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
  $conn = $conn["errMsg"];
} else {
      //printArr("Database Error");
  exit;
}
$now=time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    // this session has worn out its welcome; kill it and start a brand new one
    printArr("You do not have sufficient privileges to access this page<br>Login to continue <a href='".$rootUrl."/controllers/logout.php'>Home</a> ");
  exit;
}

$_SESSION['stepFlag']=0;
//printArr($_SESSION);
$user = "";
if(isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)){
  $user = $_SESSION["user"];
  $user_type=$_SESSION["user_type"];

  $userInfo = getUserInfoWithUserType($user,$user_type,$conn);
  if(noError($userInfo)){
    $userInfo = $userInfo["errMsg"];  
  
  } else {
    printArr("Error fetching user info".$userInfo["errMsg"]);
    exit;
  }
} else {
  printArr("You do not have sufficient privileges to access this page");
  exit;
}

If (isset($_POST["additionalCharges"])) {
       $additionalCharges=$_POST["additionalCharges"];
        $retHashSeq = $additionalCharges.'|'.$SALT.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        
                  }
  else {  
        $retHashSeq = $SALT.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

         }
     $hash = hash("sha512", $retHashSeq);
  
       if ($hash != $posted_hash) {
        
         //echo "Invalid Transaction. Please try again";
        //$redirectURL ="../dashboard/doctorsDashboard.php?payuStatus=failure";
        $redirectURL ="../dashboard/doctorsDashboard.php?payuStatus=failure";
       }
     else {
     
          // $update_order_query = "UPDATE aol_orders SET status=0,email='".$email."',phone='".$phone."' WHERE order_id=".$_GET['order_id'];
          // $result1 = runQuery($update_order_query, $connAdmin);
         
            /*echo "<h3>Your order status is ". $status .".</h3>";
            echo "<h4>Your transaction id for this transaction is ".$txnid.". You may try making the payment by clicking the link below.</h4>";*/
          
          $redirectURL ="../dashboard/doctorsDashboard.php?payuStatus=failure";
     } 
          
        $insertQuery="INSERT into user_payment_history (user_id,region_id,plan_id,trans_id,discount,paid_amount,hash,status) VALUES(".$userInfo['user_id'].",".$_SESSION['posted']['region_id'].",".$_SESSION['posted']['duration'].",'".$txnid."',".$_SESSION['posted']['discount'].",".$amount.",'".$posted_hash."','failure')";
          $result = runQuery($insertQuery, $conn);
          //printArr($date); die;
        //  printArr($result); die;
        header("Location:".$redirectURL); 
     
?>
<!--Please enter your website homepagge URL -->
<!-- <p><a href=http://localhost/payment/PayUMoney_form.php> Try Again</a></p> -->
