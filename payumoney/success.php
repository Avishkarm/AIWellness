<?php

require_once("../controllers/config.php");
require_once("../controllers/utilities.php");
require_once("../controllers/dbutils.php");
require_once("../controllers/accesscontrol.php");
require_once("../controllers/authentication.php");
require_once("../controllers/notification.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$conn = $conn["errMsg"];

  $status      =$_POST["status"];
  $firstname   =$_POST["firstname"];
  $amount      =$_POST["amount"];
  $txnid       =$_POST["txnid"];
  $posted_hash =$_POST["hash"];
  $key         =$_POST["key"];
  $productinfo =$_POST["productinfo"];
  $email       =$_POST["email"];
  $salt        ="XxLiRUjZ";


  /*printArr($_GET);
    printArr($_POST);*/
  $payuResponse=json_encode(cleanQueryParameter($_POST));

  If(isset($_POST["additionalCharges"])) {
    $additionalCharges =$_POST["additionalCharges"];
    $retHashSeq        = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
          
  } else {
    $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
  }
?>
  <!DOCTYPE html>
<html>
<head>
 <?php  include("../views/metaInclude.php"); ?>  
 <style>
 .well
{
  background-color:#ffffff;
  width:80%;
  border: 1px solid #0be1a5 !important;
  margin-left: 10%;
  margin-top: 100px;
  
  }
 </style>
<body>
<main class="container" style="min-height: 100%;">
     <?php  include_once("../views/header2.php"); 

  $hash = hash("sha512", $retHashSeq);
  if ($hash != $posted_hash) {
    ?>
        <div class="well text-center" style="background-color:#ffffff;">
            <h4 style="color:red;">Invalid Transaction. Please try again</h4>
        </div>
    <?php
    
    $updateUser = "UPDATE `Invoice` SET `status`='failed', txnid='".$txnid."',hash='".$posted_hash."',productinfo='".cleanQueryParameter($productinfo)."',payuResponse='".$payuResponse."' where `InvoiceId`='".cleanQueryParameter($_GET["InvoiceId"])."'";   
    $resultQuery=runQuery($updateUser,$conn); 

    $redirectURL="https://eheilung.com/payumoney/PayUMoney_form.php?payStatus='failed'&InvoiceId=".$_GET["InvoiceId"];
      
    print("<script>");
            print("var t = setTimeout(\"window.location='".$redirectURL."';\",3000);");
    print("</script>");


  } else {

    $updateUser = "UPDATE `Invoice` SET `status`='paid', txnid='".$txnid."',hash='".$posted_hash."',productinfo='".cleanQueryParameter($productinfo)."',payuResponse='".$payuResponse."' where `InvoiceId`='".cleanQueryParameter($_GET["InvoiceId"])."'";   
    $resultQuery=runQuery($updateUser,$conn); 
    ?>
     <div class="well text-center" style="background-color:#ffffff;">
          <h3>Your order status is <?php echo $status ;?>.</h3>
          <h4>Your transaction id for this transaction is <?php echo $txnid ;?>.</h4>
          <h4 style="color:#0be1a5;">We have received a payment of Rs.<?php echo $amount; ?></h4>
      </div>
    <?php
    
   /* $from="eHeilung <donotreply@eheilung.com>";
        $subject="Invoice payment successfull"; 
        $message="user".$name. "successfully done transfer Rs. ". $amount;        
    $mail_user=sendMail($email, $from, $subject, $message);
    $mail_doctor=sendMail('raisabargir@gmail.com', $from, $subject, $message);*/

  }
?>
  </main>
    <?php include('../views/footer2.php'); ?>
  </body>
</html>         
	