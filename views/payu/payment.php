
<?php
//$activeHeader = "2opinion"; 
$pathPrefix="../";
$activeHeader = "doctorsArea"; 

session_start();
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php"); 
require_once("../../models/userModel.php");
require_once("../../models/dashboardModel.php");
require_once("../../models/admin/planModel.php");
  //database connection handling

$blogurl="http://192.168.1.103/hansinfo_eheilung/wordpress";
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
  $conn = $conn["errMsg"];
} else {
      //printArr("Database Error");
  exit;
}

//printArr($_SESSION);
$now=time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    // this session has worn out its welcome; kill it and start a brand new one
   /* session_unset();
    session_destroy();
    session_start();*/
    printArr("You do not have sufficient privileges to access this page<br>Login to continue <a href='".$rootUrl."/controllers/logout.php'>Home</a> ");
  exit;
  //echo "bye";
}else{
  //echo "hiii";
}

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

//echo $_SESSION['payu'];
$payu=0;
if($_SESSION['payu']){
  $_SESSION['payu']=false;
  $payu=1;
}
/*// Merchant key here as provided by Payu
$MERCHANT_KEY = "rjQUPktU";

// Merchant Salt as provided by Payu
$SALT = "e5iIg1jwi8";

// End point - change to https://secure.payu.in for LIVE mode
$PAYU_BASE_URL = "https://test.payu.in";
//$PAYU_BASE_URL = "https://secure.payu.in";*/
$action = '';
//$action = $PAYU_BASE_URL . '/_payment';
$posted = array();
//$MERCHANT_KEY = "rjQUPktU";$SALT = "e5iIg1jwi8";$PAYU_BASE_URL = "https://test.payu.in";$service_provider = "payu_paisa";
/*For live*/
$MERCHANT_KEY = "LidU1G";$SALT = "XxLiRUjZ";$PAYU_BASE_URL = "https://secure.payu.in";$service_provider = "payu_paisa";
$payData=array();
    if(isset($_POST['paySubmit'])){
      //print_r($_POST);
        $data = $_POST;
        $posted=$_POST;
        //print_r($data);
        $payData['amount'] = cleanQueryParameter($conn,round($data['amount']));
        $payData['firstname'] = cleanQueryParameter($conn,$data['firstname']);
        $payData['lastname'] = cleanQueryParameter($conn,$data['lastname']);
        $payData['email'] = cleanQueryParameter($conn,$data['email']);
        $payData['phone'] = cleanQueryParameter($conn,$data['phone']);
        $payData['productinfo'] = cleanQueryParameter($conn,$data['productinfo']);
        $payData['region_id'] = cleanQueryParameter($conn,$data['region_id']);
       
    }
    // printArr($payData);die;
$posted['key']=$MERCHANT_KEY;
$formError = 0;

$orderError = 0;

if(empty($posted['txnid'])) {
  // Generate random transaction id
  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
  $posted['txnid']=$txnid;
} else {
  $txnid = $posted['txnid'];
}
$hash = '';
// Hash Sequence
//printArr($posted);
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
      || empty($posted['service_provider'])
  ) {
    $formError = 1;
  } else {
   // printArr("111".$posted);die;
    $hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';  
    foreach($hashVarsSeq as $hash_var) {
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }

     $hash_string .= $SALT; 

   $_SESSION['posted']=$posted;
    $hash = strtolower(hash('sha512', $hash_string));
    $action = $PAYU_BASE_URL . '/_payment';

    if(isset($_POST['paySubmit'])){
        $data = $_POST;
        $posted['amount'] = cleanQueryParameter($conn,round($data['amount']));
        $posted['firstname'] = cleanQueryParameter($conn,$data['firstname']);
        $posted['lastname'] = cleanQueryParameter($conn,$data['lastname']);
        $posted['email'] = cleanQueryParameter($conn,$data['email']);
        $posted['phone'] = cleanQueryParameter($conn,$data['phone']);
        $posted['productinfo'] = cleanQueryParameter($conn,$data['productinfo']);
        $posted['txnid'] = $txnid;
        $posted['serviceProvider']="payu";
        $posted['region_id'] = cleanQueryParameter($conn,$data['region_id']);
        $posted['currencyId'] = cleanQueryParameter($conn,$data['currencyId']);

      }
  }
} elseif(!empty($posted['hash'])) {
  //echo "12312312423235235: ".$posted['hash'];
  $hash = $posted['hash'];
  $action = $PAYU_BASE_URL . '/_payment';
}else{
  echo "Error";
}
//$action="payment_success.php";

//printArr( $hash);die;

?>
<html>
  <head>
  <script>
    var hash = '<?php echo $hash ?>';
   
    var orderError = Number('<?php echo $orderError ?>');
    function submitPayuForm() {
      if(hash==''){
        return false;
      }
      
        var payuForm = document.forms.payuForm;
        payuForm.submit();
        
      return false;
      
    }

  </script>
 <?php include_once("../metaInclude.php"); ?>

  </head>
  <body>
      <main class="container" style="min-height: 100%;">
         <?php  include_once("../header.php"); ?>
        <?php if($formError) { ?>
      
          <span style="color:red">Something Wrong Try Again.</span>
          <a href='../dashboard/doctorsDashboard.php'>Click here</a>
          <br/>
          <br/>
        <?php } ?>
        <h3 style="text-align: center;margin: 10%;">Please Wait....</h3>
        <form action="<?php echo $action; ?>" method="post" name="payuForm" style="visibility: hidden;">
          <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
          <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
          <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
          <table >
            <tr>
              <td><b>Mandatory Parameters</b></td>
            </tr>
            <tr>
              <td>Amount: </td>
              <td><input  name="amount" value="<?php echo (empty($payData['amount'])) ? '' : $payData['amount'] ?>" /></td>
              <td>First Name: </td>
              <td><input name="firstname" id="firstname" value="<?php echo (empty($payData['firstname'])) ? '' : $payData['firstname'] ?>" /></td>
            </tr>
            <tr>
              <td>Email: </td>
              <td><input name="email" id="email" value="<?php echo (empty($payData['email'])) ? '' : $payData['email'] ?>" /></td>
              <td>Phone: </td>
              <td><input name="phone" value="<?php echo (empty($payData['phone'])) ? '' : $payData['phone'] ?>" /></td>
            </tr>
            <tr>
              <td>Product Info: </td>
              <td colspan="3"><textarea name="productinfo"><?php echo (empty($payData['productinfo'])) ? '' : $payData['productinfo'] ?></textarea></td>
            </tr>
            <tr>
              <td>Success URI: </td>
              <td colspan="3"><input name="surl" value="<?php echo $rootUrl?>/views/payu/payment_success.php" size="64" /></td>
            </tr>
            <tr>
              <td>Failure URI: </td>
              <td colspan="3"><input name="furl" value="<?php echo $rootUrl?>/views/payu/payment_error.php" size="64" /></td>
            </tr>

            <tr>
              <td colspan="3"><input type="hidden" name="service_provider" value="payu_paisa" size="64" /><input type="hidden" name="type" value="getpayu" size="64" /></td>
            </tr>
              <?php if(!$hash) { ?>
                <td colspan="4"><input type="submit"  name="getSubmit" value="Submit" /></td>
              <?php } ?>
            </tr>
          </table>
        </form>
      </main>
       <?php  include("../modals.php"); ?> 
       <?php  include('../footer.php'); ?>
       <script type="text/javascript">
         
         $( window ).load(function() {
      var payu=<?php echo $payu; ?>;
      if(payu==1){
        submitPayuForm();
      }
});
       </script>
  </body>

</html>
