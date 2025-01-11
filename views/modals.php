<?php

if($activeHeader=="2opinion" || $activeHeader=='knowledge_center' || $activeHeader=="doctorsArea")
  {
    $pathprefix="../../";
    $views =  "../";
    $controllers = "../../controllers/";
  }else if($activeHeader == "index.php"){
    $pathprefix="";
    $views =  "views/";
    $controllers = "controllers/";
  }else {
    $pathprefix="../";
    $views = "";
    $controllers = "../controllers/";

  }

//include( $pathprefix."models/completeProfileModel.php");
include_once( $pathprefix."models/completeProfileModel.php");
include_once("metaInclude.php");

?>
<style type="text/css">
  .red{
    border-color: red;
    border-width: 2px;
  }

  .error{
        font-size: 12px;
    line-height: 15px;
    transform: translate(0,0);
    text-overflow: ellipsis;
  }
  .terms h4{
    font-size: 25px;
    margin-top: 16px;
  }
  
  .feedbackmodal h4{
    text-align: right;
    margin-bottom: 15px;
    color: #454545;
    font-family: Montserrat-Regular;
  }
  
  @media(max-width: 768px){
  .feedbackmodal h4{
    text-align: left;
  }
  
  @media screen and (min-width: 768px) {
  
  #signmodal .modal-dialog  {width:60%;}

}
  
</style>
<script type="text/javascript" src="<?php echo $pathprefix; ?>assets/js/validator.js"></script>

<?php  include_once("../header.php"); ?> 
<!-- ************************* Signup modal **************************** -->
<section name="modal">
      <div class="modal fade" id="signmodal" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
                </div>
            <form class="mui-form error-messaging" id="signUpForm"  data-toggle="validator" role="form">
              <div class="row" style="display:none;">
                <h4>I am</h4>
                <div class="col-md-12 col-xs-12 col-sm-12">
                  <div class="col-md-6 col-sm-6 col-xs-6 doctor" >
                  <input type="button" name="doc_btn" id="doc_btn" class="graybtn" value="Doctor" style="float: right;"  onclick="activedoc('doctor')">
                  <input type="button" name="doc_btnactive" id="doc_btnactive" class="graybtnclick" value="Doctor"   style="display: none;float: right;">
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-6 patient" >
                  <input type="button" name="pat_btn" id="pat_btn" class="graybtn" value="Patient" onclick="activepat('patient')">
                  <input type="button" name="pat_btnactive" id="pat_btnactive" class="graybtnclick" value="Patient"  style="display: none;">
                  </div>
                </div>
              </div>
              <div class="row">

                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">

                <div class="mui-textfield mui-textfield--float-label">
                  <input type="text" name="user_first_name" id="fnm" required="required" onblur="checktxt(this.id)" tabindex="1">
                  <label tabindex="-1">First Name</label>
                  <div tabindex="-1" class="error error-fnm" style="color:red;padding-top:10px"></div>
                </div>

                <div class="mui-textfield mui-textfield--float-label">
                  <input type="text" name="user_last_name" id="lnm" required onblur="checktxt(this.id)" tabindex="2">
                  <label tabindex="-1">Last Name</label>
                  <div tabindex="-1" class="error error-lnm" style="color:red;padding-top:10px"></div>
                </div>
                <div class="mui-textfield mui-textfield--float-label">
                  <input type="email" id="email" name="user_email" required onblur="checktxt(this.id)" tabindex="3">
                  <label tabindex="-1"  for="email" >Email</label>
                  <div tabindex="-1" class="error error-email" style="color:red;padding-top:10px"></div>
                </div>

                <div class="mui-textfield mui-textfield--float-label">
                  <input type="password" name="user_password" id="pwd" tabindex="4" data-minlength="8" minlength="8" required onblur="checktxt(this.id)">
                  <label tabindex="-1">Choose Password</label>
                  <div tabindex="-1" class="error error-pwd" style="color:red;padding-top:10px"></div>
                </div>

                <div class="mui-textfield mui-textfield--float-label">
                  <input type="password" name="cpassword" id="cpwd" required onblur="checktxt(this.id)" tabindex="5">
                  <label tabindex="-1">Confirm Password</label>
                  <div tabindex="-1" class="error error-cpwd" style="color:red;padding-top:10px"></div>
                </div>


                <div class="mui-select " style="display:inline-block;width:21%;margin-bottom:10px;">
                 <?php $countries=getAllContries($conn); //printArr($countries); ?>
                 <select required="true" name="country_code" id="ccode" onchange="checktxt(this.id)" tabindex="6" >
                 <?php               
                  foreach($countries['errMsg'] as $countryId=>$countryDetails){
                    $countryName = $countryDetails["name"];
                    $country_code= $countryDetails["country_code"];
                    $selected = "";
                    if($countryId==101)
                      $selected = "selected";
                    if(!empty($country_code)){
                    ?>
                    <option data-id="<?php echo $country_code; ?>" value="<?php echo $country_code; ?>" <?php echo $selected; ?>><?php echo $country_code; ?></option>
                    <?php 
                  }}
                  ?> 
                  </select>
                </div>                 

                 <div class="mui-textfield mui-textfield--float-label" style="display:inline-block;width:77%;margin-bottom:10px;">
                  <input type="tel" name="user_mob"  id="mob" required onblur="checktxt(this.id)" tabindex="7">
                  <label tabindex="-1" for="mob">Mobile Number</label>
                 
                </div>
                <div class="error error-ccode" style="color:red;padding-top:10px;display:inline-block;width:21%;float:left;"></div>
               <div class="error error-mob" style="color:red;padding-top:10px;display:inline-block;width:79%;float:right;"></div>
                <input type="hidden" name="user_type" id="user_type"  >
                <div class="terms">
              
                <input type="checkbox" id="terms" name="terms" value="accept" tabindex="8"> 
                   <label tabindex="8" for="terms" style="line-height:29px; ">By signing up, I agree to &nbsp;<a href="<?php echo $views; ?>terms.php" target="_blank" style="text-decoration: underline; margin-left: -6px;">Terms and Conditions</a></label>
                   <div class="error error-terms" style="color:red;"></div>
                </div> 
                 
                <button  type="button" class="signupbtn signup" tabindex="9">Sign up</button>
                <button button type=""  onClick="" class="signupbtn load" style="display:none" >Loading...<img src="assets/images/ajax-loader.gif"></button>
                <!--  <button type="submit" class="signupbtn" id="signup" >Sign up</button> -->
               <!--  <button type="button" class="signupbtn" onclick="signup()">Sign up</button> -->

                </div>

              </div>

            </form>
				<hr style="margin: 40px 0 30px 0;">

 <div class="row">

                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1 " style="text-align: center;" >
              <a onclick="fbLogin()" ><img class="img-responsive" style="width: 40%;cursor:pointer;" tabindex="10" src="<?php echo $pathprefix; ?>assets/images/facebook-login.png" alt="Facebook Login Button"></a>
          </div>
</div>


              <div class="row" style="margin-bottom:10px;">
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">  
                  <div class="terms" style="margin-top: 20px;">
                   
                  <h4 style="display: inline-block;text-align: left;width: auto;">Have an account?</h4>
                  <input type="button" tabindex="11" value="Login" class="Loginbtn"  onclick="gotologin()">

                  </div>
                </div>
              </div>
                </div>
               
              </div>
        </div>
</section>
 <!-- ******************** Signup Modal End  ************************* -->


  <!-- ******************** Successful Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="successful-signup" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
            <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h2>Successful sign up! <img src="<?php echo $pathprefix; ?>assets/images/success.png"></h2>

                <h4 class="msg"></h4>
                <input type="button" name="getstarted" class="getstarted" value="Get Started" data-dismiss="modal" onclick="gotologin()">
            </div>
            </div>

            </div>
        </div>
      </div>
  </section>

  <!-- ******************** Suceessful Modal End *********************** -->

  
  <!-- ******************** Resend activation Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="resend-activation" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <h2> Verification failure! <img src="<?php echo $pathprefix; ?>assets/images/failed.png"></h2>
                    <h4 class="msg"></h4>
                    <button button  type="button" name="resendbtn" class="resendbtn resendMsg" id="resendbtn"></button>
                    
                </div>
              </div>

            </div>
        </div>
      </div>
  </section>

  <!-- ******************** Resend activation Modal End *********************** -->
  <!-- ******************** signup failed Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="failed-signup" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content failed-signup">

            <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
            <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h2>Sign up failure! <img src="<?php echo $pathprefix; ?>assets/images/failed.png"></h2>

                <h4 class="msg"></h4>
                <p name="getstarted" class="getstarted resendMsg"  id="resendbtn"></p><!-- 
                <input type="button" name="getstarted" class="getstarted" value="Login" data-dismiss="modal" onclick="gotologin()"> -->
            </div>
            </div>

            </div>
        </div>
      </div>
  </section>
<!-- ******************** signup failed End *********************** -->
 <!-- ******************** Verfied Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="failed-soacial-signup" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h2>Sign up failure! <img src="<?php echo $pathprefix; ?>assets/images/failed.png"></h2>
                <h4>User already register with another account.Please click the link below to login</h4>
                   <input type="hidden" name="user_type" id="user_type">
                  <input type="button" name="getstarted" class="getstarted" value="Get Started" onclick="gotologin()"><!-- 
                  <a data-toggle="modal" data-target="#signup-not-verified" data-dismiss="modal">Failure</a>
 -->
                </div>
            </div>

            </div>
        </div>
      </div>
  </section>

  <!-- ******************** verified Modal End *********************** -->
<!-- ******************** signup failed End *********************** -->
    <!-- ******************** Verfied Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="signup-verified" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h2>Successfully verified! <img src="<?php echo $pathprefix; ?>assets/images/success.png"></h2>
                <h4>Your profile is successfully verified. Please, click on the button below to get started</h4>
                   <input type="hidden" name="user_type" id="user_type">
                  <input type="button" name="getstarted" class="getstarted" value="Get Started" onclick="gotologin()"><!-- 
                  <a data-toggle="modal" data-target="#signup-not-verified" data-dismiss="modal">Failure</a>
 -->
                </div>
            </div>

            </div>
        </div>
      </div>
  </section>

  <!-- ******************** verified Modal End *********************** -->

  <!-- ******************** Verification failed Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="signup-not-verified" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

              <div class="row">
                <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
              </div>

              <div class="row">
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <h2>Verification failure! <img src="<?php echo $pathprefix; ?>assets/images/failed.png"></h2>
                    <h4 class="resendMsg">Seems like something went wrong with your profile verification. Please, click the link below to resend</h4>
                    <p name="resendbtn" class="resendbtn" id="resendbtn">Resend Verification link</p>
                </div>
              </div>
              <input type="hidden" name="user_type" id="user_type">

            </div>
        </div>
      </div>
  </section>

  <!-- ******************** Verification failed Modal End *********************** -->


   <!-- ******************** login  Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="loginmodal" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content loginmodal">

            <div class="row">
              <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <form class="mui-form" id="loginForm">
             
              <div class="row">

              <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1" style="margin-bottom: 20px;">
              
              <br>    
              

                <div class="mui-textfield mui-textfield--float-label">
                  <input type="text" name="username" tabindex="1" id="u_nm"  value="<?php if(isset($_COOKIE["eheilung_username"])) { echo $_COOKIE["eheilung_username"]; } ?>" required onblur="checktxt(this.id)">
                  <label tabindex="-1">Username</label>
                  <div tabindex="-1" class="error error-u_nm" style="color:red;padding-top:10px"></div>
                </div>

                <br>

                <div class="mui-textfield mui-textfield--float-label">
                  <input type="password" name="password" tabindex="2"  id="u_pwd" value="<?php if(isset($_COOKIE["eheilung_password"])) { echo $_COOKIE["eheilung_password"]; } ?>" required onblur="checktxt(this.id)">
                  <label tabindex="-1">Password</label>
                  <div tabindex="-1" class="error error-u_pwd" style="color:red;padding-top:10px"></div>
                </div>

                 <input type="hidden" name="user_type" id="user_type" value="2">
                <div class="terms">
                <br>
                <div class="col-md-6" style="padding-left: 0;">
                  <input type="checkbox" id="remeberme" name="remeberme" value="remeberme" <?php if(isset($_COOKIE["eheilung_username"])) { ?> checked <?php } ?>  > 
                  <label tabindex="3" for="remeberme" >Remember me</label>
                </div>

                <div class="col-md-6" style="text-align: right;padding-right: 0;">
                    <a onclick="forgotmodal()" style="cursor:pointer;" tabindex="4">Forgot Password?</a>
                </div>
                
                </div>


                <button tabindex="5" type="button" class="signupbtn login">Login</button>
                <button button type=""  onClick="" class="signupbtn load" style="display:none" >Loading...<img src="assets/images/ajax-loader.gif"></button>
                </div>
                
              </div>

            </form>
<hr style="margin: 40px 0 30px 0;color: #808080">
              <!-- <div >
                
                  <a onclick="fbLogin()" ><img src="<?php echo $pathprefix; ?>assets/images/facebook-login.png" alt="Facebook Login Button"></a>

              </div> -->

               <div class="row">

                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1 " style="text-align: center;" >
              <a onclick="fbLogin()" tabindex="6"><img class="img-responsive" style="width: 40%;cursor:pointer;" src="<?php echo $pathprefix; ?>assets/images/facebook-login.png" alt="Facebook Login Button"></a>
</div>
</div>
              
              <div class="row">
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1" style="margin-bottom: 20px;">  
                  <div class="terms" style="margin:0px;">
                   
                  <h2 style="display: inline-block;text-align: left;width: auto;">Dont have an account?</h2>
                  <input type="button" tabindex="7" value="Sign up" class="Loginbtn" onclick="gotosignup()" >

                  </div>
                </div>
              </div>
<!-- href="views/fblogin.php" -->
              <!-- <div id="fb-root"></div>
              <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9&appId=275536566188915";
                fjs.parentNode.insertBefore(js, fjs);
              }(document, 'script', 'facebook-jssdk'));</script>-->
             <!--  <div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false"></div> 

            </div> -->
        </div>
      </div>
  </section>

  <!-- ******************** login Modal End *********************** -->
  <!-- ******************** login failed Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="failed-login" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content failed-signup">

            <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
            <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h2>Login failure! <img src="<?php echo $pathprefix; ?>assets/images/failed.png"></h2>

                <h4 class="msg"></h4>
                <p name="getstarted" class="getstarted resendMsg" style="cursor:pointer;" id="resendbtn"></p><!-- 
                <input type="button" name="getstarted" class="getstarted" value="Login" data-dismiss="modal" onclick="gotologin()"> -->
            </div>
            </div>

            </div>
        </div>
      </div>
  </section>
<!-- ******************** login failed End *********************** -->

   <!-- ******************** forgot  Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="forgotmodal" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content forgotmodel">

            <div class="row">
              <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
              <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                  <h2>Forgot Password</h2>
                  <br>
                  <h4>Please, type in your email address, connected to your eHeilung profile. so we can send you a link to reset your password</h4>
                  <br>
                </div>
            </div>
            

            <form class="mui-form" id="forgotForm" >
             
              <div class="row">

              <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1" style="margin-bottom: 20px;">
                  

                <div class="mui-textfield mui-textfield--float-label">
                  <input type="email" name="inputEmail" id="f_email" required onblur="checktxt(this.id)">
                  <input type="hidden" name="user_type" id="user_type" required>
                  <label>Email</label>
                  <div class="error error-f_email" style="color:red;padding-top:10px"></div>
                </div>
                
                <button type="button" class="sendbtn forgot">Send</button>
                 <button button type=""  onClick="" class="sendbtn load" style="display:none" >Loading...<img src="assets/images/ajax-loader.gif"></button>
                </div>

              </div>
              <div class="row" style="margin-bottom:10px;">
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">  
                  <div class="terms" style="margin-top: 20px;">
                   
                  <h4 style="display: inline-block;text-align: left;width: auto;">Got here by mistake?</h4>
                  <input type="button" value="Login" class="Loginbtn"  onclick="gotologin()">

                  </div>
                </div>
              </div>

            </form>


              

            </div>
        </div>
      </div>
  </section>

  <!-- ******************** forgot Modal End *********************** -->
  <!-- ******************** link sent Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="link-sent" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

              <div class="row">
                <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
              </div>

              <div class="row">
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <h2>Link is sent</h2>
                      

                    <h4 class="msg">Please check your email and follow the instruction to reset your password</h4>
                
                    <h1><img src="<?php echo $pathprefix; ?>assets/images/success.png" onclick="restpass()"></h1>
                </div>
              </div>

            </div>
        </div>
      </div>
  </section>

  <!-- ******************** link sent modal End *********************** -->
 <!-- ******************** signup failed Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="link-sent-failed" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content failed-signup">

            <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
            <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h2>Sent link failure! <img src="<?php echo $pathprefix; ?>assets/images/failed.png"></h2>

                <h4 class="msg"></h4>
                <p name="getstarted" class="getstarted resendMsg"  id="resendbtn"></p><!-- 
                <input type="button" name="getstarted" class="getstarted" value="Login" data-dismiss="modal" onclick="gotologin()"> -->
            </div>
            </div>

            </div>
        </div>
      </div>
  </section>
<!-- ******************** signup failed End *********************** -->


  <!-- ******************** Reset password Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="resetpass" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content forgotmodel">

            <div class="row">
                <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                  <h2>Reset Password</h2>
                  <h4>Please, type in your new password and confirm it</h4>
                </div>
            </div>
            
            <form class="mui-form" id="resetForm">
              <div class="row">
              <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <div class="mui-textfield mui-textfield--float-label">
                  <input type="password" name="password" id="r_pwd" required onblur="checktxt(this.id)">
                  <label>Password</label>
                  <div class="error error-r_pwd" style="color:red;padding-top:10px"></div>
                </div>

                <div class="mui-textfield mui-textfield--float-label">
                  <input type="password" name="confirm_password" id="r_cpwd" required onblur="checktxt(this.id)">
                  <label>Confirm Password</label>
                  <div class="error error-r_cpwd" style="color:red;padding-top:10px"></div>
                </div>
                 <input type="hidden" class="username" name="username" required>
                  <input type="hidden" name="user_type" id="user_type" required>
                <button type="button" class="sendbtn reset">Reset</button>
                <button button type=""  onClick="" class="sendbtn load" style="display:none" >Loading...<img src="assets/images/ajax-loader.gif"></button>
              </div>
              </div>
            </form>

            </div>
        </div>
      </div>
  </section>

  <!-- ******************** Reset Password modal End *********************** -->
  <!-- ******************** password reset successfully Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="passwordreset-success" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
              <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                  <h2>Password is sucessfully reset! <img src="<?php echo $pathprefix; ?>assets/images/success.png"></h2>
                  <h4>Welcome back to Eheilung. Your password has successfully been reset.<br>Plese, click on the link below to login</h4>
                  <input type="hidden" name="user_type" id="user_type" required>
                <input type="button" name="getstarted" class="getstarted" value="Login" data-dismiss="modal" onclick="gotologin()">
              </div>
            </div>

            </div>
        </div>
      </div>
  </section>

  <!-- ******************** password reset successfully  Modal End *********************** -->


    <!-- ******************** password reset failed Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="passwordreset-failed" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
              <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                  <h2>Password reset failed <img src="<?php echo $pathprefix; ?>assets/images/failed.png"></h2>
                  <h4 class="msg" >Sorry, something went wrong with password reset, please, try again or request a new link</h4>
                <input type="hidden" name="user_type" id="user_type" required>
                <input type="button" name="getstarted" class="signupbtn" value="Try again" data-dismiss="modal" >

                <h2 style="margin: 30px 0 30px 0;">or</h2>
                <input type="button" name="getstarted" class="getstarted" value="Reset link" data-dismiss="modal" onclick="gotoforgotpass()">
              </div>
            </div>

            </div>
        </div>
      </div>
  </section>

  <!-- ******************** password reset failed  Modal End *********************** -->
<!-- Subscription models start -->
<section name="modal">
      <div class="modal fade" id="successsubscribemodal" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
            <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h2>Successful subscription! <img src="<?php echo $pathprefix; ?>assets/images/success.png"></h2>

                <h4 class="msg">You have successfully subscribed to eheilung.</h4>
                <!-- <input type="hidden" name="" class="resendMsg" > -->
                <!-- <input type="button" name="gotomail" class="gotomailbtn"  data-dismiss="modal" value="Go to Email" onclick="openMailBox()"> -->
            </div>
            </div>

            </div>
        </div>
      </div>
  </section>

  <section name="modal">
      <div class="modal fade" id="failuresubscribemodal" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <h2> Subscription failure! <img src="<?php echo $pathprefix; ?>assets/images/failed.png"></h2>
                    <h4 class="msg"></h4>
                    <p name="resendbtn" class="resendbtn resendMsg subscribe" id="resendbtn">Try again</p>
                    
                </div>
              </div>

            </div>
        </div>
      </div>
  </section>

  <section name="modal">
      <div class="modal fade" id="existsubscribemodal" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content failed-signup">

            <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
            <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h2>Subscription failure! <img src="<?php echo $pathprefix; ?>assets/images/failed.png"></h2>

                <h4 class="msg">You have already subscribed to eheilung</h4><!-- 
                <input type="button" name="getstarted" class="getstarted" value="Login" data-dismiss="modal" onclick="gotologin()"> -->
            </div>
            </div>

            </div>
        </div>
      </div>
  </section>
<!-- Subscription model ends -->

<!-- ******************** Feedback Modal ************************* -->
<section name="modal">
    <div class="modal fade" id="feedbackmodal" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content feedbackmodal">
                <form class="mui-form" id="feedbackForm">
                    <div class="modal-body addpatient">
                        <div class="row">
                            <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
                        </div>
                        <div class="alert"></div>

                        <h2 style="margin-left: 10px;color: #000;margin-top: 0;"> Feedback</h2>
                        <h4 style="text-align: left; margin-left: 10px;color: #000;margin-top: 0;"> Please, type in your Feedback and Submit it</h4>

                        <div class="row addpatient-fields">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <h4>Name <span class="req">*</span></h4>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" name="user_first_name" class="form-control fnm" value="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <h4>Last name <span class="req">*</span></h4>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" name="user_last_name"  class="form-control lnm" value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <h4>Message <span class="req">*</span></h4>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12 ">
                                <textarea  name="fdbk_msg" style="resize:none; margin-bottom: 30px; height:150px;" class="form-control"></textarea>               
                            </div>
                            <div style="clear:both;"></div>
                        </div>

                        <input type="hidden" name="user_type" id="user_type" value="2">


                        <button tabindex="3" type="button" class="signupbtn submitfdbk">Submit</button>
                        <button button type=""  onClick="" class="signupbtn load" style="display:none" >Loading...<img src="assets/images/ajax-loader.gif"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

  <!-- ******************** Feedback Modal End *********************** -->

<!-- ******************** Change Password Modal ************************* -->
    <section name="modal">
    <div class="modal fade" id="chngpswdmodal" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content chngpswdmodal">

                <div class="row">
                    <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
                    <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                        <h2> Change Password</h2>
                        <h4> Please, type in your new password and confirm it</h4>
                    </div>
                </div>
                
                <form class="mui-form" id="chngpswdForm">

                <div class="row">

                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1" style="margin-bottom: 20px;">

                <br>    

                <div class="mui-textfield mui-textfield--float-label">
                  <input type="password" name="user_password" id="npwd" tabindex="1" data-minlength="8" minlength="8" required onblur="checktxt(this.id)">
                  <label tabindex="-1">New Password</label>
                  <div tabindex="-1" class="error error-npwd" style="color:red;padding-top:10px"></div>
                </div>

                <div class="mui-textfield mui-textfield--float-label">
                  <input type="password" name="cpassword" id="cnpwd" required onblur="checktxt(this.id)" tabindex="2">
                  <label tabindex="-1">Confirm New Password</label>
                  <div tabindex="-1" class="error error-cnpwd" style="color:red;padding-top:10px"></div>
                </div>
 
                <input type="hidden" name="user_type" id="user_type" value="2">


              <button tabindex="3" type="button" class="signupbtn chngpswd">Change Password </button>
              <button button type=""  onClick="" class="signupbtn load" style="display:none" >Loading...<img src="assets/images/ajax-loader.gif"></button>
              </div>

            </div>

          </form>
      </div>
        </div>
</section>

  <!-- ******************** Change Password Modal End *********************** -->

<!-- ******************** change password successfully Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="changepassword-success" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
              <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                  <h2>Password changed sucessfully! <img src="<?php echo $pathprefix; ?>assets/images/success.png"></h2>
                  <h4>Your Password is changed sucessfully. Please, click on the button below to get started</h4>
                  <input type="hidden" name="user_type" id="user_type" required>
                  <input type="button" class="getstarted" data-dismiss="modal" value="Get Started">
              </div>
            </div>

            </div>
        </div>
      </div>
  </section>

  <!-- ******************** changed password successfully  Modal End *********************** -->


    <!-- ******************** change password failed Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="changepassword-failed" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
              <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                  <h2>Change Password failed <img src="<?php echo $pathprefix; ?>assets/images/failed.png"></h2>
                  <h4 class="msg" >Sorry, something went wrong with change password reset, please, try again.</h4>
                <input type="hidden" name="user_type" id="user_type" required>

              </div>
            </div>

            </div>
        </div>
      </div>
  </section>

  <!-- ******************** password reset failed  Modal End *********************** -->


<script type="text/javascript">





var rooturl="<?php echo $rootUrl; ?>";
var emailFilter =  /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
var mobFilter= /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
var nameFilter = /^([a-zA-Z]{1,16})$/;

/*function refineUrl()
{
    //get full url
    var url = window.location.href;
    //get url after/  
    var value = url.substring(url.lastIndexOf('/') + 1);
    //get the part after before ?
    value  = value.split("?")[0];   
    return value;     
}*/
$(".subscribe").click(function(){
    window.location.href="views/subscriber.php";
});

function checktxt(e){

 if($("#"+e).val() == ""){
  $("#"+e).removeClass( "mui--is-untouched" );
  $("#"+e).addClass( "mui--is-touched" );
  $(".error-"+e).html('This field is required');
 }
 else{
  $("#"+e).removeClass( "mui--is-untouched" );
  $("#"+e).addClass( "mui--is-touched" );
  $(".error-"+e).html('');

  var inputType = $("#"+e).attr('type');
  var val = $("#"+e).val();

  if(inputType == "email"){
    if(!emailFilter.test(val)) {
     
      $(".error-"+e).html('Wrong email');
       $("#"+e).removeClass( "mui--is-untouched" );
      $("#"+e).addClass( "mui--is-touched" );
      
    }
    else{
      $(".error-"+e).html('');
       $("#"+e).removeClass( "mui--is-untouched" );
      $("#"+e).addClass( "mui--is-touched" );
    
    }
  }  // END FOR EMAIL

  if(inputType == "password"){
 
    if(e == "pwd"){
      if(val.length < 8) {
      //alert('All fields are mandatory');
      $(".error-"+e).html('Minimum 8 characters required');
       $("#"+e).removeClass( "mui--is-untouched" );
      $("#"+e).addClass( "mui--is-touched" );
      
    }
    else{
      $(".error-"+e).html('');
       $("#"+e).removeClass( "mui--is-untouched" );
      $("#"+e).addClass( "mui--is-touched" );
    
    }
    }

    if(e == "cpwd"){
    
      if(val != $("#pwd").val() ) {
      //alert('All fields are mandatory');
      $(".error-"+e).html('Password does not match');
       $("#"+e).removeClass( "mui--is-untouched" );
      $("#"+e).addClass( "mui--is-touched" );
      
    }
    else{
      $(".error-"+e).html('');
       $("#"+e).removeClass( "mui--is-untouched" );
      $("#"+e).addClass( "mui--is-touched" );
    
    }
    }
    
            if(e == "npwd"){
                if(val.length < 8) {
                    //alert('All fields are mandatory');
                    $(".error-"+e).html('Minimum 8 characters required');
                    $("#"+e).removeClass( "mui--is-untouched" );
                    $("#"+e).addClass( "mui--is-touched" );
                }
                else{
                    $(".error-"+e).html('');
                    $("#"+e).removeClass( "mui--is-untouched" );
                    $("#"+e).addClass( "mui--is-touched" );
                }
            }

            if(e == "cnpwd"){
                if(val != $("#npwd").val() ) {
                    //alert('All fields are mandatory');
                    $(".error-"+e).html('Password does not match');
                    $("#"+e).removeClass( "mui--is-untouched" );
                    $("#"+e).addClass( "mui--is-touched" );
                }
                else{
                    $(".error-"+e).html('');
                    $("#"+e).removeClass( "mui--is-untouched" );
                    $("#"+e).addClass( "mui--is-touched" );
                }
            }
    
  }  // END FOR PASSWORD

 if(inputType == "Number"){

//  if(val.length!=10 && !mobFilter.test(val)) {                //ALERT-KDR
//      //alert('All fields are mandatory');
//      $(".error-"+e).html('Invalid mobile number');
//       $("#"+e).removeClass( "mui--is-untouched" );
//      $("#"+e).addClass( "mui--is-touched" );
//    }
//    else{
//       $(".error-"+e).html('');
//    }
 }

 }  // Else part end 
}



    function activedoc(e){
    document.getElementById("user_type").value = 2;
    $("#pat_btn").css("display","block");
    $("#pat_btnactive").css("display","none");
    $("#doc_btn").css("display","none");
    $("#doc_btnactive").css("display","block");
  }

  function activepat(e){
    document.getElementById("user_type").value = 3;
    $("#pat_btn").css("display","none");
    $("#pat_btnactive").css("display","block");
    $("#doc_btn").css("display","block");
    $("#doc_btnactive").css("display","none");
  }
  function gotologin(){
    var user_type="2";
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    $("#signup-verified").removeClass("fade").modal("hide");
    $("#signup-not-verified").removeClass("fade").modal("hide");
    $("#signmodal").removeClass("fade").modal("hide");
    $("#passwordreset-success").removeClass("fade").modal("hide"); 
    $("#forgotmodal").removeClass("fade").modal("hide");  
    if($("#signmodal #user_type").val()!=""){
      user_type=$("#signmodal #user_type").val();
    }else if($("#signup-verified #user_type").val()!="")
    {
      user_type=$("#signup-verified #user_type").val();
    }else if($("#signup-not-verified #user_type").val()!="")
    {
      user_type=$("#signup-not-verified #user_type").val();
    }
    else if($("#failed-soacial-signup #user_type").val()!="")
    {
      user_type=$("#failed-soacial-signup #user_type").val();
    }
    else if($("#failed-signup #user_type").val()!="")
    {
      user_type=$("#failed-signup #user_type").val();
    }
    else if($("#passwordreset-success #user_type").val()!="")
    {
      user_type=$("#passwordreset-success #user_type").val();
    }
      user_type=2;
    $("#loginmodal #user_type").val(user_type);
    $("#loginmodal").modal();

  }
  function gotosignup(){
    $("#mymodal").removeClass("fade").modal("hide");
    $("#loginmodal").removeClass("fade").modal("hide");
    var user_type=$("#loginmodal #user_type").val();
    $("#signmodal #user_type").val(user_type);
  $("#signmodal").modal();
  }
  
  function gotochngpswd(){
    
//    $("#loginmodal").removeClass("fade").modal("hide");
//    var user_tgotochngpswd()ype=$("#loginmodal #user_type").val();
//    $("#signmodal #user_type").val(user_type);
    console.log("gotochngpswd Func");
  $("#chngpswdmodal").modal();
  }
  function gotofeedback(){
    
//    $("#loginmodal").removeClass("fade").modal("hide");
//    var user_tgotochngpswd()ype=$("#loginmodal #user_type").val();
//    $("#signmodal #user_type").val(user_type);
    $('.alert').removeClass('alert-danger');
    $('.alert').removeClass('alert-success');
    $('.alert').html('');
    $('.alert').css("display","none");
    
    console.log("feedback Func");
    $("#feedbackmodal").modal();
  }
  
   function forgotmodal(){
      $("#loginmodal").removeClass("fade").modal("hide");
      var user_type=$("#loginmodal #user_type").val();
    $("#forgotmodal #user_type").val(user_type);
  $("#forgotmodal").modal();
  }

   function resetpasssuccess(){
    location.hash="resetpass";
      $("#loginmodal").removeClass("fade").modal("hide");
  $("#forgotmodal").modal();
  }
   function gotoforgotpass(){
      $("#passwordreset-failed").removeClass("fade").modal("hide");
      $("#resetpass").removeClass("fade").modal("hide");
      var user_type=$("#passwordreset-failed #user_type").val();
      $("#forgotmodal #user_type").val(user_type);
        $("#forgotmodal").modal();
  }

$('#signUpForm input').keypress(function(e){
    if(e.which == 13){//Enter key pressed
        $('.signup').click();//Trigger search button click event
        e.preventDefault();
    }
});
$('#loginForm input').keypress(function(e){
    if(e.which == 13){//Enter key pressed
        $('.login').click();//Trigger search button click event
        e.preventDefault();
    }
});

$('#chngpswdForm input').keypress(function(e){
    if(e.which == 13){//Enter key pressed
        $('.submitfdbk').click();//Trigger search button click event
        e.preventDefault();
    }
});

$('#feedbackForm input').keypress(function(e){
    if(e.which == 13){//Enter key pressed
        $('.chngpswd').click();//Trigger search button click event
        e.preventDefault();
    }
});

$('.signup').keypress(function(e){
    if(e.which == 13){//Enter key pressed
        $('.signup').click();//Trigger search button click event
    }
});

$('.submitfdbk').click(function(e) {
    /* Act on the event */
    var formdata=$('#feedbackForm').serialize();

    ajaxCall('<?php echo $pathprefix; ?>controllers/feedbackController.php',formdata,$(this));

     /*ajax definition starts*/
    function ajaxCall(url,formdata,$ele){
        $.ajax({type: "POST",
               url:url,
               data:{'formdata':formdata},
               dataType:'JSON',

         })
        .done(function(data) {

            console.log(data);
            if(data['errCode']==-1){
                $('.alert').css("display","block");
                $('.alert').removeClass('alert-danger').addClass('alert-success');
                $('.alert').html('<strong>Success ! </strong>'+data['errMsg']);
                setTimeout(function(){
                    location.reload(true);
                },3000);
            }else{
                $('.alert').css("display","block");
                $('.alert').removeClass('alert-success').addClass('alert-danger');
                $('.alert').html('<strong>Error ! </strong>'+data['errMsg']);
            }   
        })    
        .fail(function(jqXHR, textStatus, errorThrown) {
            alert("error");
            console.log(jqXHR.responseText);
        })  
        .error(function(jqXHR, textStatus, errorThrown) { 
            console.log(jqXHR.responseText);
        })  
    }
});


$('.chngpswd').click(function(e) {
    $('.error').html('');
    
    var pwd = $('#chngpswdmodal #npwd').val();
    var cpwd = $('#chngpswdmodal #cnpwd').val();
    
    console.log(pwd + " , " + cpwd);
    
    if (pwd=="") {
      //alert('All fields are mandatory');
      $('.error-npwd').html('This field is required');
       $('#npwd').removeClass( "mui--is-untouched" );
      $('#npwd').addClass( "mui--is-touched" );
      //e.preventDefault();
    }else if (pwd.length < 8) {
      //alert('All fields are mandatory');
       $('#npwd').removeClass( "mui--is-untouched" );
      $('#npwd').addClass( "mui--is-touched" );
      $('.error-npwd').html('Minimum 8 characters required');
      //e.preventDefault();
    }else if (cpwd=="") {
      //alert('All fields are mandatory');
      $('.error-cnpwd').html('This field is required');
       $('#cnpwd').removeClass( "mui--is-untouched" );
      $('#cnpwd').addClass( "mui--is-touched" );
      //e.preventDefault();
    }else if (pwd!=cpwd) {
      //alert('All fields are mandatory');
      $('.error-cnpwd').html('Password does not match');
       $('#cnpwd').removeClass( "mui--is-untouched" );
      $('#cnpwd').addClass( "mui--is-touched" );
      //e.preventDefault();
    }else{
    var type="chngpswd"
    //var user_type=$("#resetpass #user_type").val();
    var user_type = 2;
    var formdata=$('#chngpswdForm').serialize();
    //console.log(formdata);
   // location.href="controllers/signUpController.php";
    ajaxCall('<?php echo $pathprefix; ?>controllers/changePasswordController.php',formdata,$(this));
    
   // event.preventDefault();
     /*ajax definition starts*/
 function ajaxCall(url,formdata,$ele){
    $.ajax({type: "POST",
            url:url,
            data:{'formdata':formdata},
            dataType:'JSON',
            beforeSend: function () {
              $('.load').show();
              $('.chngpswd').hide();
            }
      })
      .done(function(data) {
      $('.load').hide();
      $('.reset').show();
      console.log(data);
        if(data['errCode']==-1){
          /*$('#successful-signup .msg').html(data['errMsg']);
          $('#successful-signup .resend
          Msg').html(data['resendMsg']);*/
         // var user_type=$("#resetpass #user_type").val();
          $("#chngpswdmodal").removeClass("fade").modal("hide");
          $("#changepassword-success #user_type").val(user_type);
          $("#changepassword-success").modal();
        }else{
          console.log(data['errCode']);
          //$('#passwordreset-failed .resendMsg').empty();
          $('#changepassword-failed .msg').html(data['errMsg']);/*
          $('#link-sent-failed .resendMsg').html(data['resendMsg']);*/
          $("#changepassword-failed #user_type").val(user_type);
          $("#changepassword-failed").modal();
        }   
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert("error");
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       })  
    }
}

  /*ajax definition ends*/
});


  $('.signup').click(function(e) {
    //alert('hii');
    $('.error').html('');
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    
    /* Act on the event */
    var sEmail = $('#signmodal #email').val();
    var fnm = $('#signmodal #fnm').val();
    var lnm = $('#signmodal #lnm').val();
    var pwd = $('#signmodal #pwd').val();
    var cpwd = $('#signmodal #cpwd').val();
    var mob = $('#signmodal #mob').val();
    var ccode = $('#signmodal #ccode').val();

  
   
    // Checking Empty Fields
    if(fnm==""){
      $('#fnm').removeClass( "mui--is-untouched" );
      $('#fnm').addClass( "mui--is-touched" );
      $('.error-fnm').html('This field is required');
      //e.preventDefault();
    }else if(!nameFilter.test(fnm)){
      $('.error-fnm').html('Invalid name');
     // e.preventDefault();
    }else if(lnm==""){
       $('#lnm').removeClass( "mui--is-untouched" );
      $('#lnm').addClass( "mui--is-touched" );
      $('.error-lnm').html('This field is required');
     // e.preventDefault();
    }else if(!nameFilter.test(lnm)){
      $('.error-lnm').html('Invalid name');
      //e.preventDefault();
    }else if ($.trim(sEmail).length == 0 ) {
       $('#email').removeClass( "mui--is-untouched" );
      $('#email').addClass( "mui--is-touched" );
      //alert('All fields are mandatory');
      $('.error-email').html('This field is required');
     // e.preventDefault();
    }else if (!emailFilter.test(sEmail)) {
      //alert('All fields are mandatory');
      $('.error-email').html('Wrong email');
       $('#email').removeClass( "mui--is-untouched" );
      $('#email').addClass( "mui--is-touched" );
      //e.preventDefault();
    }else if (pwd=="") {
      //alert('All fields are mandatory');
      $('.error-pwd').html('This field is required');
       $('#pwd').removeClass( "mui--is-untouched" );
      $('#pwd').addClass( "mui--is-touched" );
      //e.preventDefault();
    }else if (pwd.length < 8) {
      //alert('All fields are mandatory');
       $('#pwd').removeClass( "mui--is-untouched" );
      $('#pwd').addClass( "mui--is-touched" );
      $('.error-pwd').html('Minimum 8 characters required');
      //e.preventDefault();
    }else if (cpwd=="") {
      //alert('All fields are mandatory');
      $('.error-cpwd').html('This field is required');
       $('#cpwd').removeClass( "mui--is-untouched" );
      $('#cpwd').addClass( "mui--is-touched" );
      //e.preventDefault();
    }else if (pwd!=cpwd) {
      //alert('All fields are mandatory');
      $('.error-cpwd').html('Password does not match');
       $('#cpwd').removeClass( "mui--is-untouched" );
      $('#cpwd').addClass( "mui--is-touched" );
      //e.preventDefault();
    }else if (mob=="") {
      //alert('All fields are mandatory');
      $('.error-mob').html('This field is required');
       $('#mob').removeClass( "mui--is-untouched" );
      $('#mob').addClass( "mui--is-touched" );
      //e.preventDefault();
    }
//    else if (mob.length!=10 && !mobFilter.test(mob)) {
//      $('.error-mob').html('Invalid mobile number');      //ALERT-KDR
//       $('#mob').removeClass( "mui--is-untouched" );
//      $('#mob').addClass( "mui--is-touched" );
//    }
    else if (ccode == null) {
      //alert('All fields are mandatory');
      $('.error-ccode').html('This field is required');
       $('#ccode').removeClass( "mui--is-untouched" );
      $('#ccode').addClass( "mui--is-touched" );
      //e.preventDefault();
    }else if($("#terms").prop('checked') != true){
    //do something
      $('.error-terms').html('Terms and conditions not selected.');
     }
    /*else if (!mobFilter.test(mob)) {
      //alert('All fields are mandatory');
      $('.error-mob').html('Invalid mobile number');
      e.preventDefault();
    }*/
    else{
    var type="signup"
    var formdata=$('#signUpForm').serialize();
    //console.log(formdata);
   // location.href="controllers/signUpController.php";
    ajaxCall('<?php echo $pathprefix; ?>controllers/signUpController.php',formdata,$(this));

    //event.preventDefault();
     /*ajax definition starts*/
 function ajaxCall(url,formdata,$ele){
    $.ajax({type: "POST",
            url:url,
            data:{'formdata':formdata},
            dataType:'JSON',
            beforeSend: function () {
              $('.load').show();
              $('.signup').hide();
            }
      })
      .done(function(data) {
      $('.load').hide();
      $('.signup').show();
      $("#signmodal").removeClass("fade").modal("hide");
        if(data['errCode']==-1){
          $('#successful-signup .msg').html(data['errMsg']);
          $('#successful-signup .resendMsg').html(data['resendMsg']);
          $("#successful-signup").modal();
        }
        else{
          $("#signmodal").removeClass("fade").modal("hide");
          $('#failed-signup .resendMsg').empty();
          $('#failed-signup .msg').html(data['errMsg']);
          $('#failed-signup .resendMsg').html(data['resendMsg']);
          $("#failed-signup").modal();
        }        
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert("error");
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       })  
  }
}
  /*ajax definition ends*/
  });
 /* function openMailBox(){
    var mailBoxDomainLink=$('#successful-signup .resendMsg').val();
    window.location.replace("http://"+mailBoxDomainLink)
  }*/
/*    $('#loginForm').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            username: {
                validators: {
                    notEmpty: {
                        message: 'The username is required'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    }
                }
            }
        }
    });*/

  
/*  $('#signUpForm').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()) {
    // handle the invalid form...
    alert('bye');
  } else {
    // everything looks good!
    alert('hiii');
  }
})*/

  $('.forgot').click(function(e) {
    $('.error').html('');
    var sEmail = $('#forgotmodal #f_email').val();
    if ($.trim(sEmail).length == 0 ) {
       $('#f_email').removeClass( "mui--is-untouched" );
      $('#f_email').addClass( "mui--is-touched" );
      $('.error-f_email').html('This field is required');
      //e.preventDefault();
    }else if (!emailFilter.test(sEmail)) {    
       $('#f_email').removeClass( "mui--is-untouched" );
      $('#f_email').addClass( "mui--is-touched" );
      $('.error-f_email').html('Wrong email');
      //e.preventDefault();
    }else{
    /* Act on the event */
    var type="forgot"
    var formdata=$('#forgotForm').serialize();
    //console.log(formdata);
   // location.href="controllers/signUpController.php";
    ajaxCall('<?php echo $pathprefix; ?>controllers/forgotPasswordController.php',formdata,$(this));
    
    //event.preventDefault();
     /*ajax definition starts*/
 function ajaxCall(url,formdata,$ele){
    $.ajax({type: "POST",
            url:url,
            data:{'formdata':formdata},
            dataType:'JSON',
            beforeSend: function () {
              $('.load').show();
              $('.forgot').hide();
            }
      })
      .done(function(data) {
      $('.load').hide();
      $('.forgot').show();
      
        if(data['errCode']==-1){
          /*$('#successful-signup .msg').html(data['errMsg']);
          $('#successful-signup .resendMsg').html(data['resendMsg']);*/
          $("#forgotmodal").removeClass("fade").modal("hide");
          $("#link-sent").modal();
        }else{
          $('#link-sent-failed .resendMsg').empty();
          $('#link-sent-failed .msg').html(data['errMsg']);
          $('#link-sent-failed .resendMsg').html(data['resendMsg']);
          $("#link-sent-failed").modal();
        }   
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert("error");
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       })  
  }
}
  /*ajax definition ends*/
  });


    $('.reset').click(function(e) {
      $('.error').html('');
    /* Act on the event */
    var r_pwd = $('#resetpass #r_pwd').val();
    var r_cpwd = $('#resetpass #r_cpwd').val();
    if (r_pwd=="") {
      //alert('All fields are mandatory');
      $('.error-r_pwd').html('This field is required');
       $('#r_pwd').removeClass( "mui--is-untouched" );
      $('#r_pwd').addClass( "mui--is-touched" );
      //e.preventDefault();
    }else if (r_pwd.length < 8) {
      //alert('All fields are mandatory');
       $('#r_pwd').removeClass( "mui--is-untouched" );
      $('#r_pwd').addClass( "mui--is-touched" );
      $('.error-r_pwd').html('Minimum 8 characters required');
     // e.preventDefault();
    }else if (r_cpwd=="") {
      //alert('All fields are mandatory');
      $('.error-r_cpwd').html('This field is required');
       $('#r_cpwd').removeClass( "mui--is-untouched" );
      $('#r_cpwd').addClass( "mui--is-touched" );
      //e.preventDefault();
    }else if (r_pwd!=r_cpwd) {
      //alert('All fields are mandatory');
      $('.error-r_cpwd').html('Password does not match');
       $('#r_cpwd').removeClass( "mui--is-untouched" );
      $('#r_cpwd').addClass( "mui--is-touched" );
      //e.preventDefault();
    }else{
    var type="reset";
    var user_type=$("#resetpass #user_type").val();
    var formdata=$('#resetForm').serialize();
    //console.log(formdata);
   // location.href="controllers/signUpController.php";
    ajaxCall('<?php echo $pathprefix; ?>controllers/resetPasswordController.php',formdata,$(this));
    
   // event.preventDefault();
     /*ajax definition starts*/
 function ajaxCall(url,formdata,$ele){
    $.ajax({type: "POST",
            url:url,
            data:{'formdata':formdata},
            dataType:'JSON',
            beforeSend: function () {
              $('.load').show();
              $('.reset').hide();
            }
      })
      .done(function(data) {
      $('.load').hide();
      $('.reset').show();
      console.log(data);
        if(data['errCode']==-1){
          /*$('#successful-signup .msg').html(data['errMsg']);
          $('#successful-signup .resendMsg').html(data['resendMsg']);*/
         // var user_type=$("#resetpass #user_type").val();
          $("#resetpass").removeClass("fade").modal("hide");
          $("#passwordreset-success #user_type").val(user_type);
          $("#passwordreset-success").modal();
        }else{
          console.log(data['errCode']);
          //$('#passwordreset-failed .resendMsg').empty();
          $('#passwordreset-failed .msg').html(data['errMsg']);/*
          $('#link-sent-failed .resendMsg').html(data['resendMsg']);*/
           $("#passwordreset-failed #user_type").val(user_type);
          $("#passwordreset-failed").modal();
        }   
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert("error");
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       })  
  }
}

  /*ajax definition ends*/
  });


     $('.login').click(function(e) {
     $('.error').html('');
      var u_nm = $('#loginmodal #u_nm').val();
      var u_pwd = $('#loginmodal #u_pwd').val();
      if (u_nm=="") {
      //alert('All fields are mandatory');
      $('.error-u_nm').html('This field is required');
       $('#u_nm').removeClass( "mui--is-untouched" );
      $('#u_nm').addClass( "mui--is-touched" );
     // e.preventDefault();
    }else if (u_pwd=="") {
      //alert('All fields are mandatory');
      $('.error-u_pwd').html('This field is required');
       $('#u_pwd').removeClass( "mui--is-untouched" );
      $('#u_pwd').addClass( "mui--is-touched" );
     // e.preventDefault();
    }else if (u_pwd.length < 8) {
      //alert('All fields are mandatory');
       $('#u_pwd').removeClass( "mui--is-untouched" );
      $('#u_pwd').addClass( "mui--is-touched" );
      $('.error-u_pwd').html('Minimum 8 characters required');
      //e.preventDefault();
    }else{
    /* Act on the event */
    var type="login"
    var formdata=$('#loginForm').serialize();
    //console.log(formdata);
   // location.href="controllers/signUpController.php";
    ajaxCall('<?php echo $pathprefix; ?>controllers/loginController.php',formdata,$(this));
    
   // event.preventDefault();
     /*ajax definition starts*/
 function ajaxCall(url,formdata,$ele){
    $.ajax({type: "POST",
            url:url,
            data:{'formdata':formdata},
            dataType:'JSON',
           beforeSend: function () {
              $('.load').show();
              $('.login').hide();
            }
      })
      .done(function(data) {
      $('.load').hide();
      $('.login').show();
     //console.log(data);
        if(data['errCode']==-1){
          if(data['errMsg']=="admin"){
            window.location.href="<?php echo $pathprefix; ?>views/admin/index.php"
          } else if(data['errMsg']=="user"){
            window.location.href="<?php echo $pathprefix; ?>views/dashboard/doctorsDashboard.php"
          }          
          /*$('#successful-signup .msg').html(data['errMsg']);
          $('#successful-signup .resendMsg').html(data['resendMsg']);*/
        }else if(data['errCode']==2){
          $('#resend-activation .resendMsg').empty();
          $('#resend-activation .msg').html(data['errMsg']);
          $('#resend-activation .resendMsg').html(data['resendMsg']);
          $("#resend-activation").modal();               
          $("#loginmodal").removeClass("fade").modal("hide"); 
        }else{
          $('#failed-login .resendMsg').empty();
          $('#failed-login .msg').html(data['errMsg']);
          $('#failed-login .resendMsg').html(data['resendMsg']);
          $("#failed-login").modal();
        }   
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert("error");
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       })  
  }
}

  /*ajax definition ends*/
  });

     function fbLogin(){
      if($("#signmodal #user_type").val()!=""){
        var user_type=$("#signmodal #user_type").val();
      }else if($("#loginmodal #user_type").val()!=""){
        var user_type=$("#loginmodal #user_type").val();
      }
      //location.href="views/fblogin.php?user_type="+user_type;
      window.open("views/fblogin.php?user_type="+user_type,'_blank');
    /*$.ajax({type: "POST",
            url:"views/fblogin.php",
            data:{'user_type':user_type},
            dataType:'JSON',
           beforeSend: function () {
              $('.load').show();
              $('.login').hide();
            }
      })
      .done(function(data) {
      $('.load').hide();
      $('.login').show();
        if(data['errCode']==-1){ 
            window.location.href="<?php echo $pathprefix; ?>views/dashboard/doctorsDashboard.php"
         
        }else if(data['errCode']==2){
          $('#resend-activation .resendMsg').empty();
          $('#resend-activation .msg').html(data['errMsg']);
          $('#resend-activation .resendMsg').html(data['resendMsg']);
          $("#resend-activation").modal();   
        }else{
          $('#failed-login .resendMsg').empty();
          $('#failed-login .msg').html(data['errMsg']);
          $('#failed-login .resendMsg').html(data['resendMsg']);
          $("#failed-login").modal();
        }   
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert("error");
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       })  */
  
     }




 
</script>
  <!-- ******************** Signup Modal End  ************************* -->