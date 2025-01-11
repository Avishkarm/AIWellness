<?php
  /*
   *  @author   Gopal Joshi
   *  @about    PayUMoney Payment Gateway integration in PHP
   */
require_once("../controllers/config.php");
require_once("../controllers/utilities.php");
require_once("../controllers/dbutils.php");
require_once("../controllers/accesscontrol.php");
require_once("../controllers/authentication.php");
require_once("../controllers/notification.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$conn = $conn["errMsg"];

/*printArr($_GET);
printArr($_POST);*/
$payuResponse=json_encode(cleanQueryParameter($_POST));


  $payStatus=$_GET['payStatus']; 
 
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
     <?php  include_once("../views/header2.php"); ?>

<?php
  if(isset($_GET['payStatus']))
  { ?>
    
    <div class="well text-center" style="background-color:#ffffff;">
        <h4 style="color:red;">Transaction has been canceled</h4>
    </div>
<?php
    //echo "Transaction has been canceled";
    $updateUser = 'UPDATE `Invoice` SET `status`="'.$payStatus.'" where `InvoiceId`="'.cleanQueryParameter($_GET["InvoiceId"]).'"';   
    $resultQuery=runQuery($updateUser,$conn); 
  } 
 else{

        $status      = $_POST["status"];
        $firstname   = $_POST["firstname"];
        $amount      = $_POST["amount"];
        $txnid       = $_POST["txnid"];
        $posted_hash = $_POST["hash"];
        $key         = $_POST["key"];
        $productinfo = $_POST["productinfo"];
        $email       = $_POST["email"];  
        $salt        = "XxLiRUjZ";   
  // Your salt

    If(isset($_POST["additionalCharges"])) {

      $additionalCharges = $_POST["additionalCharges"];
      $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;      
    } else {	  
      $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
    }  
     

    $hash = hash("sha512", $retHashSeq);

    if ($hash != $posted_hash) {
    ?>
     <div class="well text-center" style="background-color:#ffffff;">
        <h4 style="color:red;">Invalid Transaction. Please try again</h4>
    </div>
    <?php
      //echo "Invalid Transaction. Please try again";
         /* $redirectURL="https://eheilung.com/payumoney/PayUMoney_form.php?payStatus='failed'&InvoiceId=".$_GET["InvoiceId"];
        
          print("<script>");
                  print("var t = setTimeout(\"window.location='".$redirectURL."';\",3000);");
          print("</script>"); 
     */
    } else {   
       
       $updateUser = "UPDATE `Invoice` SET `status`='".$_POST['field9']."', txnid='".$txnid."',hash='".$posted_hash."',productinfo='".cleanQueryParameter($productinfo)."',payuResponse='".$payuResponse."' where `InvoiceId`='".cleanQueryParameter($_GET["InvoiceId"])."'";   
       $resultQuery=runQuery($updateUser,$conn); 
       ?>

        <div class="well text-center" style="background-color:#ffffff;">
          <h4 style="color:red;">Transaction has been <?php echo $_POST['field9']; ?></h4>
          <h3>Your order status is <?php echo $status ;?>.</h3>
          <h4>Your transaction id for this transaction is <?php echo $txnid ;?>.</h4>
        </div>

       <?php
      //$redirectURL="https://eheilung.com/payumoney/PayUMoney_form.php?payStatus='failed'&InvoiceId=".$_GET["InvoiceId"];
    }
}
?>
</main>
    <?php include('../views/footer2.php'); ?>
  </body>
</html>