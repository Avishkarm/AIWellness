<?php

require_once("../utilities/config.php");
//echo $rootUrl;
 $luser=$_GET['luser'];

?>
<head>
 <?php include_once("metaInclude.php"); ?>
<style type="text/css">

  form .animelabel.active{
  webkit-transform: translateY(-140%);
    transform: translateY(-140%);
    font-size: 1.2rem;
    color:#999;
    font-weight: bold;
    padding-left: 7px;
    background-color: transparent;
  }
  form .animelabel{
      color: #999;
      position: absolute;
      top: 0.8rem;
      left: 0.15rem;
      font-size: 1.5rem;
      cursor: text;
      transition: .2s ease-out;
      font-weight: lighter;
      padding-left: 20px;
  }
  form  div{
    position: relative;
      margin-top: 1rem;
  }
  form input[type="text"],input[type="password"],form input[type="email"],.input{
    background-color: white;
     
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
  .btn.btn-common
    {
        background-color:#0be1a5 ;
        border-radius: 15px;
        border: 1px solid transparent;
        padding: 10px;         
        color: #555;
        width:100px;
    }
    .btn:hover, .btn:focus {

       border: 1px solid #999;
       background-color:#fff;
       color: #555;
    }
    

  .row
  {
    margin:30px;
  }
  .signBox
    {
      
    }
    @media screen and (max-width:1024px )
  {
    .signBox 
    {
     
    }
   
  }
  @media screen and (max-width:786px )
  {
    #border
    {
      display:none;

      margin-left:0px;
      border-left:0px solid green;
      min-height:600px;
      margin-right: 0px;
    }
    .signBox 
    {
      margin-left: 30px;
    }
    .SignUpUser
    {
      margin-left: 30px;
      margin-top: 30px;
    }
    
  }
  @media screen and (min-width: 768px) {
  
  #myModal .modal-dialog  {width:50%;height: 80%;}

}

</style>


<main class="container" style="min-height: 100%;">
    <?php  include_once("header.php"); ?>
    <div class="row" id="login" >
            <div class="col-md-5 col-sm-12 signBox" style="">
                <span  style="color:#080808;font-size:20px;">Sign In</span>                                
                <form autocomplete="false" id="form1" role="form" action="../controllers/login.php" 
                method="post" class="form-horizontal" autocomplete="off">
                  <div class="form-group">   
                    <span style="color:red;">*</span>
                    <input type="text" name="username" id="usernameFrm1" class="" required="" style="" autocomplete="off">
                    <label for="usernameFrm1" class="animelabel">Enter Email.</label>
                  </div>
                  <div class="form-group">
                    <span style="color:red;">*</span>
                    <input type="password" name="password" id="passwordfrm1" class="" required="" style="" autocomplete="off">
                    <label for="passwordfrm1" class="animelabel">Password</label>
                  </div>
                  <input type="hidden" name="url1"  id="url1" value="">
                    <div class="form-group" style="margin-left: 5px;">
                        <input class="btn btn-common"  type="submit"  id="some" name="some" value="Go">
                    </div>
                </form>
                <div class="ForPass" >
                <a href="forgot_password.php" style="color:#7B7979;font-size:20px;">Forgot your Password ? </a>
                </div>
            </div>
           
            <div id="border" class="col-md-2 col-sm-12" style="position:relative;">
              <div style="border-left:1px solid #0be1a5;min-height:600px;margin-left:50px;"></div>
            </div><!-- <?php echo CONTROLLER; ?>/register.php -->
            <div class="col-md-5 col-sm-12 SignUpUser" style="" >
                <span style="color:#080808;font-size:20px;">Create New Account</span>                                 
                <form  id="myForm"  class="myForm1" autocomplete="false"  action="" method="post" class="form-horizontal">
                         <div class="form-group"> 
                            <input type="text" class="" name="user_first_name"  id="usernameFrm2" style="margin-left: 9px; required="">
                            <label for="usernameFrm2" class="animelabel">First Name</label>
                          </div> 
                          <div class="form-group"> 
                            <span style="color:red"></span>
                            <input type="text" class="" name="user_last_name" id="userLnameFrm2" style="margin-left: 9px;" required="">
                            <label for="userLnameFrm2" class="animelabel">Last Name</label>
                          </div> 
                          <div class="form-group"> 
                            <span style="color:red;">*</span> 
                            <input type="text" class="" name="user_email" id="user_email" style="" required="">
                            <label for="usremail" class="animelabel">Email</label>
                          </div> 
                          <div class="form-group">   
                            <input type="text" class="" name="user_mob" id="usrMob"   pattern="[789][0-9]{9}" style="margin-left: 9px;" >
                            <label for="usrMob" class="animelabel">Mobile</label>
                          </div> 
                          <div class="form-group"> 
                            <span style="color:red;">*</span>   
                            <input type="password" class="" name="user_password" id="formPwd" style="" required="">
                            <label for="formPwd" class="animelabel">Password</label>
                          </div>  
                          <div class="form-group">  
                            <span style="color:red;">*</span> 
                            <input type="password" class="" name="cpassword" id="cnfPwd" style="" required="">
                            <label for="cnfPwd" class="animelabel">Confirm Password</label>
                          </div>
                          <div class="form-group">  
                            <span style="color:red;">*</span> 
                            <input type="text" class="" name="promocode" id="promocode" style="" required="">
                            <label for="promocode" class="animelabel">Promocode</label>
                          </div>
                          
                          <ul class="radioUI list-inline" style="margin-left: 5px;">
                            <li>Iam a </li>
                            <li>
                              <input type="radio" <?php if($luser=='doctor') {echo "checked";} ?> id="doctor" name="radio" class="role" value="2" readonly required="">
                              <label for="doctor">Doctor</label>
                            </li>
                            <li>
                              
                              <input id="patient" type="radio" <?php if($luser=='patient') {echo "checked";} ?> name="radio" class="role" value="3" readonly required="">
                              <label for="patient">Patient</label>
                            </li>
                          </ul>
                         <!--  <div class="">
                              <input type="checkbox" name="termCond" id="termnCond" required>
                              <span><a href="disclaimer.php" target="_blank">I agree to the Terms and Condition</a></span>
                          </div> -->
                          <div id="errMsg" style="margin-left: 5px;color:red;">
                          </div>
                          <div class="form-group" style="margin-left: 5px;">
                            <a class="btn btn-common" id="signup" name="" value="SignUp">SignUp</a>
                          </div>
                          
                         <!--   <div class="form-group" style="margin-left: 5px;">
                            <input class="btn btn-common"  type="submit"  id="submit" name="submit" value="SignUp">
                          </div> -->
                </form>
            </div>
    </div><!-- data-toggle="modal" data-target="#myModal" aria-hidden="true"
 -->
     <div class="modal fade disclaimer" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title">Disclaimer</h3>
        </div>
        <div class="modal-body"> 
         <?php include('disclaimer.php'); ?>  
        </div>
         <div class="modal-footer">
         <input type="hidden" value="false" name="applicant_read_the_content" id="applicant_read_the_content" />
           <button  onClick="" id="accepted1" class="btn btn-common accepted " >I Accept</button>                 
           <button data-dismiss="modal" aria-hidden="true" onClick="" class="btn btn-common" >Cancel</button>               
        </div>
      </div>
    </div>
   </div>
   
   </main>
   <?php include('modals.php'); ?>  
   <?php include('footer.php'); ?> 

<!-- Footer -->


<script type="text/javascript">
/*$(document).ready(function(){
     $("#btn").click(function () {
            var selectedText = $("select").find("option:selected").text();
            var selectedValue = $("select").val();
             
            $('#defShow').html(selectedValue);
        });
});*/

  $(document).ready(function(){
 var url="" 


 var url1 = localStorage.getItem('url1');
  $('#url1').val(url1);
 $(".navbar-toggle").on("click", function () {
           $(this).toggleClass("active");
       });

 
  /*   $(".role").click(function () {
      
    //  var val=$('.role').val();
    var val=$("input[name='radio']:checked"). val();
     
        if(val==2)
        {
          url="<?php echo CONTROLLER; ?>/register_doctor.php";
        }
        else if(val==3)
        {
         url="<?php echo CONTROLLER; ?>/register.php";
        }

      $("#myForm").attr("action",url);
    });
*/
          $('#signup').click(function(e){
        
          var cnt = 0;
          $('.myForm1 input:not(input[name="user_mob"])').each(function() {
                      if ($(this).val() == '') {
                       
                       cnt++;
                      }
                 
           });
         if(cnt!=0) {$('#errMsg').html("Please fill all fields"); }
         else if(!$('.role').is(':checked')) { $('#errMsg').html("Please choose user type doctor or patient"); }

         else if(cnt == 0){
            $('#myModal').modal('toggle');
          }

          });

        $(".disclaimer").scroll(function(){
            var totalScrollHeight = $(".disclaimer")[0].scrollHeight
            var scrollBarHeight = $(".disclaimer")[0].clientHeight
            var scrollBarTopPosition = $(".disclaimer")[0].scrollTop
            if (totalScrollHeight== scrollBarHeight + scrollBarTopPosition){
                $("#applicant_read_the_content").val("true")
            }
        })

        $(".accepted").click(function(){
          
            if ($("#applicant_read_the_content").val() != "true"){
            
                alert("Please scroll through the disclosure text before clicking I Accept.")
                return false
            }
            else{
                    //$("#esign_acceptance_form").submit()
                   var val=$("input[name='radio']:checked").val();
                 
                    if(val==2)
                    {
                      url="../controllers/register_doctor.php";
                    }
                    else if(val==3)
                    {
                    
                     url="../controllers/register.php";
                    }

                 $("#myForm").attr("action",url);

                  $("#myForm").submit();
                  
               }
        })

/*$("#myForm").submit(function(){
    alert("Submit");
    //return true;
   });*/
/*$("#submit").click(function () {
   // $('#myForm').on('submit', function(e){
   //   event.preventDefault();
  //  var formData = new FormData($(this)[0]);
     
     var user_email=$('#user_email').val();
      alert('hii');
       if ($('#doctor').is(':checked')) 
       {
           alert('doctor');
                // window.location.href = "../../controllers/register_doctor.php";

         $.ajax({


                url: '../../controllers/register_doctor.php',
                type: 'POST',
                //data: formData + '&adminemail=' + adminarr + '&memberemail=' + memberarr,
                data: user_email,

                dataType: "json",
                success: function (data) {
                   alert('success');
                },
                error:function() {
                      alert('Error in Ajax');
                }

            });
        }


       else if ($('#patient').is(':checked')) 
       {

           alert('patient');
                   window.location.href = "../../controllers/register.php";
                 /* $.ajax({
                          url: '../../controllers/register.php',
                          type: 'POST',
                          //data: formData + '&adminemail=' + adminarr + '&memberemail=' + memberarr,
                          data: formData,

                          dataType: "json",
                          success: function (data) {
                          },
                          error:function() {
                                alert('Error in Ajax');
                          }
                        });
     }

    }); */


  setTimeout(function(){

  $("form input[type=text],input[type=password]").each(function(){
      if($(this).val()){
        $(this).siblings('label').addClass('active');
      }
    });

  $("form input[type=password]").val('');
  },100);
  
  $('form input[type=text],input[type=password]').on('focus',function(){
    $(this).siblings('label').addClass('active');
  });
  $('form input[type=text],input[type=password]').on('blur',function(){
    if(!$(this).val()){
      $(this).siblings('label').removeClass('active');
    }
  });

});




  /*$('form input[type="submit"]').hover(function(){
    $(this).toggleClass('btnActive');
  })*/


</script>
</body>
</html>

