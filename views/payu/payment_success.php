<?php



session_start();
/*echo "<pre>";
print_r($_SESSION);
;
echo "</pre>"; die;*/
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

//   printArr($_GET);
// printArr($_POST);
// die;




//printArr($_SESSION['chash']);

if (isset($_POST["additionalCharges"])) {
       $additionalCharges=$_POST["additionalCharges"];
        $retHashSeq = $additionalCharges.'|'.$SALT.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        
                  }
	else {	  

         $retHashSeq = $SALT.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

         }
		//echo '<br>'.$retHashSeq;
     $hash = hash("sha512", $retHashSeq);
		 //die;
        if ($hash != $posted_hash) {
	        //echo "Invalid Transaction. Please try again";
          $redirectURL ="../dashboard/doctorsDashboard.php?payuStatus=failure";
           $updateQuery="UPDATE users SET plan_expiry_date='".$expiry_date."', plan_id=".$plan_id." where user_email='".$_POST['email']."'";
          $result = runQuery($updateQuery, $conn);
          $insertQuery="INSERT into user_payment_history (user_id,region_id,plan_id,trans_id,discount,paid_amount,hash,status) VALUES(".$userInfo['user_id'].",".$_SESSION['region_id'].",".$_SESSION['plan_id'].",'".$txnid."',".$_SESSION['discount'].",".$amount.",'".$posted_hash."','failure')";
          $result = runQuery($insertQuery, $conn);


           //$redirectURL ="../dashboard/doctorsDashboard.php?payuStatus=success";

          /*printArr($result);
          die;*/
          /*if(noError($result)){
             $redirectURL ="../dashboard/doctorsDashboard.php?payuStatus=success";
             
          }else{
            $redirectURL ="../dashboard/doctorsDashboard.php?payuStatus=failure";
          }*/
      // Redirect browser 

		    }
	      else {

          // $update_order_query = "UPDATE aol_orders SET status=1,email='".$email."',phone='".$phone."' WHERE order_id=".$_GET['order_id'];
          // $result1 = runQuery($update_order_query, $connAdmin);

            //echo "<h3>Thank You. Your order status is ". $status .".</h3>";
            //echo "<h4>Your Transaction ID for this transaction is ".$txnid.".</h4>";
            //echo "<h4>We have received a payment of Rs. " . $amount . ". Your order will soon be shipped.</h4>";
            $redirectURL ="../dashboard/doctorsDashboard.php?payuStatus=success";
            $getPlanExpiring=getPlanExpiring($userInfo['user_id'],$conn);
            //printArr($getPlanExpiring);
            $day=$getPlanExpiring['diff'];

            $plan_id=$_SESSION['posted']['duration'];
            $getPlanDetails=getPlanDetails($plan_id,$conn);
          //printArr($getPlanDetails);
                 $duration=$getPlanDetails['errMsg']['duration'];
                if($day<=0){
                  $date = strtotime("+".$duration." month ");
                }else{
                  $date = strtotime("+".$duration." month +".$day." day");
                }
          
           $expiry_date= date('Y-m-d H:i:s', $date); /* date("Y-m-d H:i:s", strtotime(str_replace('/','-',$date)));*/


          $updateQuery="UPDATE users SET plan_expiry_date='".$expiry_date."', plan_id=".$plan_id." where user_email='".$_POST['email']."'";
          $result = runQuery($updateQuery, $conn);
         
          if(!empty($_SESSION['posted']['promocode'])){
             $updateQuery1="UPDATE promocodes SET used_by='".$userInfo['user_id']."', used=1, used_ts='".date('Y-m-d H:i:s')."',status=0  where code='".$_SESSION['posted']['promocode']."'";
            $result = runQuery($updateQuery1, $conn);
          
          }
          
          $insertQuery="INSERT into user_payment_history (user_id,region_id,plan_id,trans_id,discount,paid_amount,hash,status) VALUES(".$userInfo['user_id'].",".$_SESSION['posted']['region_id'].",".$_SESSION['posted']['duration'].",'".$txnid."',".$_SESSION['posted']['discount'].",".$amount.",'".$posted_hash."','failure')";
          $result = runQuery($insertQuery, $conn);
          //printArr($date); die;
         
      
        }

        header("Location:".$redirectURL); 
?>	