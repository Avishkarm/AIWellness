<?php
$rt = $_SERVER['DOCUMENT_ROOT'];
$controller_rt = $rt."/controllers";

require_once("../controllers/config.php");
require_once("../controllers/notification.php");

?>
<!DOCTYPE html>
<html>
<head>
<?php include_once("metaInclude.php"); ?>

<style>
   
     

    form  div{
      position: relative;
        margin-top: 1rem;
    }
    form input[type="text"],input[type="password"],form input[type="email"],.input{
      background-color: white;
       border-radius: 8px!important;
        border: 1px solid #999;
        border-radius: 0;
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

  <main class="container" style="min-height: 100%;">
    <?php  include_once("header.php"); ?>
   
       <div class="row" style="">
         <div class="col-md-8 loginbox-center">
          <div class="formy well" style="margin-left:auto;background-color: #ffffff;border: 1px solid #0be1a5;padding: 50px;
    padding-left: 100px;
    padding-right: 0px;">
           <label style="color:#080808;font-size:20px;">Forgot Password</label>
              <form autocomplete="false" name="form1" role="form" action="../controllers/forgot_password.php" method="post" class="form-horizontal">
                      <div class="form-group" style="margin-left:0px;margin-top: 25px;color:#333"> 
                        <span style="color:red">*</span><label for="mob" class="">Email</label> 
                        </div> 
                        <div class="form-group" style="margin-left:0px;"> 
                        <input id="mob" name="inputEmail" type="email" value="" placeholder="Enter Your email"  class="" required="" style="border-color: #0be1a5;">
                      </div>
                      <div style="width:80%">
                        <input class="btn btn-common " type="submit" value="Reset">
                      </div>
              </form>
          </div>
        </div>
      </div>
    
  </main>
  <?php include('footer.php'); ?>
</body>
</html>