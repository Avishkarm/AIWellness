
<?php
  
//Step1: Prepare for request
//starting session

session_start();

//including necessary files
$rt = $_SERVER['DOCUMENT_ROOT'];
$controller_rt = $rt."/controllers";

require_once("../controllers/config.php");
require_once("../controllers/utilities.php");
require_once("../controllers/dbutils.php");
require_once("../controllers/accesscontrol.php");
require_once("../controllers/authentication.php");
require_once("../controllers/notification.php");


//database connection handling
$conn = createDbConnection($servername, $username, $password, $dbname);

if(noError($conn)){
  $conn = $conn["errMsg"];  
} else {
  printArr("Database Error"); 
  exit;
}

$input = $_GET;

$userid = base64_decode($input['i']);
$time = $input['t'];
$expire_time = strtotime($time)+$tokenExpiryTime;
$oldpassword = $input['o'];
$hash = $input['v'];

$p_info = getUserInfo($userid, $conn);

if(noError($p_info)){
  $p_info = $p_info["errMsg"];  
} else {
  printArr("This email address is not recognized as a registered email address. Please try again.");  
  exit;
}

$token = "t=$time&i=".$userid."&o=$oldpassword";
$correct_hash = hash_hmac('sha256', $token, $p_info['salt']);

if ( ($hash != $correct_hash) || ($oldpassword != hash('sha256', $p_info['user_password'])) ) {
    printArr("That password link is invalid, has expired or has been used.");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<?php include_once("metaInclude.php"); ?> 
<style>
   .btn.btn-common
    {
      background-color:#0be1a5;
       border-radius: 25px;
      border: 1px solid transparant;
          padding: 10px;
          width:120px;
          color: #555;
    }
    .btn:hover, .btn:focus {

       background-color:#fff;
        color: #999;
    }
    form label
    {
      color:#333;
    }
    form  div{
      position: relative;
        margin-top: 1rem;
    }
    form input[type="text"],input[type="password"],form input[type="email"],.input{
        background-color: white;
     
      border: 1px solid #0be1a5;
      border-radius: 8px;
      outline: none;
      height: 4rem;
      width: 80%;
      margin: 0 0 15px 0;
      padding-left: 10px;
      box-shadow: inset 0em 0px #9e9e9e;
      box-sizing: content-box;
      transition: all 0.3s;
    
    }
      form input[type="text"]:focus:not([readonly]),input[type="password"]:focus:not([readonly]),.input:focus{
        color:black;
      }
      form input[type="text"]:focus:not([readonly]),input[type="password"]:focus:not([readonly]),.input:focus{
          box-shadow: inset 0em -2px #18b587;
      }
      .loginbox-center
      {
        margin:100px;
        margin-left: 200px;

      }
      @media screen and (max-width:1024px )
      {
        .loginbox-center
      {
        margin:100px;
        margin-left: 250px;

      }
      }
      @media screen and (max-width:786px )
      {
        .loginbox-center
      {
        margin:100px;
        margin-left: 100px;

      }
      }
      @media screen and (max-width:435px )
      {
        .loginbox-center
      {
        margin:0px;
        margin-top: 100px;
        

      }
      }
 
</style>
</head>
<body>
  <main class="container" style="min-height: 100%">
    <?php  include_once("header.php"); ?>
    <div class="container-fluid" style="min-height:800px">
      <div class="row" >
       <div class="col-md-8 loginbox-center">
        <div class="formy well" style="margin-left:auto;background-color: #ffffff;border: 1px solid #0be1a5;padding: 50px;
    padding-left: 100px;
    padding-right: 0px;"">
         <label style="color:#080808;font-size:20px;">Reset Password</label>
                <form autocomplete="false" name="form1" role="form" action="../controllers/resetpassword.php"  method="post" class="form-horizontal">
                    <div class="form-group" style="margin-left:0px;margin-top: 25px;">
                    <label for="usr" class="">Email</label>
                    </div>
                    <div class="form-group" style="margin-left:0px;">
                      <input type="text" name="username" value="<?php echo $userid; ?>" class="" readonly id="usr" style="" placeholder="">
                    </div>
                    <div class="form-group" style="margin-left:0px;">
                      <span style="color:red">*</span><label for="passwordfrm1" class="">Password</label>
                      </div>
                      <div class="form-group" style="margin-left:0px;">
                      <input type="password" name="password" id="passwordfrm1" class="" required  style="">
                    </div>
                    <div class="form-group" style="margin-left:0px;">
                     <span style="color:red">*</span><label for="passwordfrm1" class="" >Confirm Password</label>
                     </div>
                     <div class="form-group" style="margin-left:0px;">
                      <input type="password" name="confirm_password"  id="passwordCnf" class="" required style="">
                    </div>
                    <input type="hidden" id="fname" name="fname" value="AKSHAY">
                    <input type="hidden" name="user_id" value="1129">
                    <div style="width:80%">
                      <input class="btn btn-common " type="submit" value="Reset">
                    </div>
                </form>
        </div>
      </div>
    </div>
    </div>
  </main>

<?php include('footer.php'); ?>
</body>
</html>