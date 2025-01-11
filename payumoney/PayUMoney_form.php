<?php
require_once("../utilities/config.php");
require_once("../models/commonModel.php");
require_once("../utilities/dbutils.php");
require_once("../models/contactDoctorsModel.php");

//database connection handling

$blogURL ="../ehblog/index.php";
$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();
if(noError($conn)){
  $conn = $conn["errMsg"];
} else {
      //printArr("Database Error");
  exit;
}




  $merchant_key  = "LidU1G";
  $salt          = "XxLiRUjZ";
//  $payu_base_url = "https://secure.payu.in"; // For Test environment
  $payu_base_url = "https://test.payu.in";
  $action        = '';
  $currentDir    = $rootUrl.'/payumoney/';
  $posted = array();

  if(!empty($_POST)) {
    foreach($_POST as $key => $value) {    
      $posted[$key] = $value; 
    }
  }
 /* $posted['firstname']='raisa';
  $posted['email']='raisabargir@gmail.com';
  $posted['productinfo']='eheilung';
  $posted['amount']=10;
  $posted['phone']='7775095661';
  $posted['key']='LidU1G';
  $posted['surl']=$rootUrl.'/payumoney/success.php';
  $posted['furl']=$rootUrl.'/payumoney/failure.php';*/
  $formError = 0;
  if(empty($posted['txnid'])) {
    $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    $posted['txnid']=$txnid;
  } else {
    $txnid = $posted['txnid'];
  }
//$PAYU_BASE_URL = "https://test.payu.in";

  $hash         = '';
  $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

  if(empty($posted['hash']) && sizeof($posted) > 0) {
    if(
          empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
          || empty($posted['surl'])
          || empty($posted['furl'])
    ){
      $formError = 1;

    } else {
      $hashVarsSeq = explode('|', $hashSequence);
      $hash_string = '';  
    foreach($hashVarsSeq as $hash_var) {
        $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
        $hash_string .= '|';
      }
      $hash_string .= $salt;
      $hash = strtolower(hash('sha512', $hash_string));
      $action = $payu_base_url . '/_payment';
    }
  } elseif(!empty($posted['hash'])) {
    $hash = $posted['hash'];
    $action = $payu_base_url . '/_payment';
  }
?>
<!DOCTYPE html>
<html>
<head>
 <?php  include("../views/metaInclude.php"); ?>  
 <style>

    #form1{
        padding: 5%;
      margin-top: 2%;
      border: 1px solid #0be1a5;
    }

  /*Chose Select Bootstrap Fix*/
  .chosen-container-single .chosen-single {
      height: 30px;
      border-radius: 3px;
      border: 1px solid #CCCCCC;
  }
  .chosen-container-single .chosen-single span {
      padding-top: 2px;
  }
  .chosen-container-single .chosen-single div b {
      margin-top: 2px;
  }
  .chosen-container-active .chosen-single,
  .chosen-container-active.chosen-with-drop .chosen-single {
      border-color: #ccc;
      border-color: rgba(82, 168, 236, .8);
      outline: 0;
      outline: thin dotted \9;
      -moz-box-shadow: 0 0 8px rgba(82, 168, 236, .6);
      box-shadow: 0 0 8px rgba(82, 168, 236, .6)
  }
 </style>
  <script>
    var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
       var payuForm = document.forms.payuForm;
       payuForm.submit();
    }
  </script>
  </head>
  <body onload="submitPayuForm()">
  <main class="container" style="min-height: 100%;">
    <?php  include("../views/header2.php"); ?> 
    <h3 style="text-align: center;margin-top: 30px;">Payment Confirmation</h3>
        <hr/>
    <br/>
    <p style="text-align: center;">Please confirm your details and complete payment:</p>
    <?php if($formError) { ?>
      <span style="color:red">Please fill all mandatory fields.</span>
      <br/>
      <br/>
    <?php } ?>

    <form  action="<?php echo $action; ?>" method="post" data-toggle="validator" id="form1" name="payuForm" style="">
      <input type="hidden" name="key" value="<?php echo $merchant_key ?>" />
      <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
      <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />

            <div class="form-group" style="">
                <label for="firstname" class="control-label">Name</label>
                <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo (empty($posted['firstname'])) ? $invoiceInfo['customer_name'] : $posted['firstname']; ?>" readonly  />
            </div>
            <div class="form-group" style="">
                <label for="userEmail" class="control-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo (empty($posted['email'])) ? $invoiceInfo['email'] : $posted['email']; ?>" readonly />
            </div>
            <div class="form-group">
                <label for="phone_no" class="control-label">Product Info</label>
                <textarea name="productinfo" class="form-control" readonly  ><?php echo (empty($posted['productinfo'])) ? "Dr. Khedekar's Invoice" : $posted['productinfo'] ?></textarea>
            </div> 
            <div class="form-group">
                <label for="amount" class="control-label">Amount</label>
                <input name="amount" type="number" class="form-control" value="<?php echo (empty($posted['amount'])) ? $invoiceInfo['amount'] : $posted['amount'] ?>" readonly  />
            </div>
            <div class="form-group" style="display: none;">
            <input type="text" name="phone" value="<?php echo (empty($posted['phone'])) ? '1234567890' : $posted['phone']; ?>" readonly />
            <input type="text" name="surl" class="form-control" value="<?php echo (empty($posted['surl'])) ? $currentDir.'success.php?InvoiceId='.$_GET["InvoiceId"] : $posted['surl'] ?>" size="64" readonly  />
            <input type="text" name="furl" class="form-control" value="<?php echo (empty($posted['furl'])) ? $currentDir.'failure.php?InvoiceId='.$_GET["InvoiceId"] : $posted['furl'] ?>" size="64" readonly  />
            <input type="hidden" name="service_provider" class="form-control" value="payu_paisa" size="64" readonly />
            </div>
            <?php if(!$hash) { ?>
            <div class="form-group text-center" style="">
                <button type="submit" name="paySubmit" value="Submit" class="btn btn-default">Confirm</button>
                <a href='<?php echo $currentDir.'failure.php?payStatus=canceled&InvoiceId='.$_GET["InvoiceId"] ; ?>' type="button" name="payTest" value="Submit" class="btn btn-default">Cancel</a>
            </div>
            <?php } ?>

            
        </form>
    </main>
    <?php include('../views/footer2.php'); ?>
  </body>
</html>
<?php?>

