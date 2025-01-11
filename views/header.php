<?php

session_start();

 
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

require_once($pathprefix."utilities/config.php");
require_once($pathprefix."utilities/dbutils.php"); 
require_once($pathprefix."models/userModel.php");
require_once($pathprefix."controllers/notification.php");
//database connection handling

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();
if(noError($conn)){
  $conn = $conn["errMsg"];
} else {
  //printArr("Database Error");
  exit;
}

$user = "";
if(isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)){
  $user = $_SESSION["user"];
  $user_type=$_SESSION["user_type"];

  $userInfo = getUserInfoWithUserType($user,$user_type,$conn);
  if(noError($userInfo)){
    $userInfo = $userInfo["errMsg"];  
  
  } 
} 
/*$user=$_SESSION['user'];
$getDoctorsuser=getDoctorsUser($conn,$user);*/
//printArr($getDoctorsuser);


  $doctorPlanOver = 0;
  $fifteenDaysOver = 0;

?>
<?php
if($EnbGA)
{
    include_once($pathprefix."utilities/analytics.php");
}
?>


<style>

    .btnLogin{
        height:36px;
        margin-top: 22px;
        margin-bottom: 22px;
        border : 2px solid #0dae04;
        background-color: transparent;
        text-align: center;
        padding: 8px;
        /*outline: none;*/
        letter-spacing: 1px;
        font-family: Montserrat-Regular;
        border-radius: 5px;
        color: #0dae04;
        
    }
    
    .btnRegister {
        height:36px;
        margin-top: 22px;
        margin-bottom: 22px;
        margin-left: 10px;
        background-color: #0dae04;
        color: #fff;
        border-radius: 5px;
        border:none;
        outline:none;
        padding:10px;
        font-family: Montserrat-Regular;
        text-align: center;
     }


@font-face{
  font-family: Montserrat-Regular;
  src: url(<?php echo $pathprefix."assets/fonts/montserrat/Montserrat-Regular.otf"?>); 
}
 html,body
    {
      height:100%;
      font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;padding-right: 0px!important;padding-left: 0px!important;
    }
/*    *{
      transition: 400ms all cubic-bezier(0.89, 0.36, 0.91, 0.39);
    }*/
   
    i{
      color: #0be1a5;
    }
   .modal-open {
      overflow-y: scroll!important;   
    }


        .btn-gray
    {
      border: 1px solid gray;
      padding: 17px;
      background:transparent;
      color: gray !important;
    }
    .register-button{
      position: relative;
      top:9px;
    }
    .navbar {
      border: 0;
      font-size: 11px !important;
      letter-spacing: 1px;
    }
    .active{  background-color:#fffaee;}
    .navbar .navbar-default
    {
      margin-bottom: 0px;
    background-color: #ffffff;
    }

  .navbar li a, .navbar .navbar-brand {
      color: #080808 !important;
      padding-top: 55px;
    padding-bottom: 60px;
    margin-top: 0px;
  }

  .navbar-nav li a:hover {
      color: #080808 !important;
    /*  padding-top: 55px;
    padding-bottom: 50px;*/
    margin-top: 0px;

  }

  .navbar-nav li.active a {
      color: #080808 !important;
      background-color: #19c794 !important;
  }

  .navbar-default .navbar-toggle {
      border-color: transparent;
  }

  
li.nav-hidden{
  display:none;
}
  /*
common classes
*/
.margin-none{
  margin: 0px !important;
}
.padding-none{
  padding: 0px !important;
}
#header-all{
  border-radius: 0px;
  margin-right: 0px;
}

.notification > span, .notification_view > span{
    border-radius: 50%;
   
    color: white;
    padding: 3px;
    text-align: center;
}
.newMsg{
   background: #F44336;
}
.noMsg{
   background: white;
}
a:hover{
    text-decoration: none;
}

#btn1:hover{
  cursor: pointer;
}

.nav-extended{
    position: absolute;
    left: 15%;
    font-family: Montserrat-Regular;
    text-transform: uppercase;
    font-size: 12px;
}
.navbar-default{
    padding-left:  20px;
}

@media(max-width: 768px){
   .nav-extended{
    position: absolute;
    top: 0px;
    left: 15% !important;
    font-size: 8px !important;
}
 .nav-extended li{
  display: block !important;
 }
 .nav-extended li a{
  padding-top: 25px !important;
 }

.navbar-default{
    left: 0;
}
.navbar li a, .navbar .navbar-brand {
    padding: 10px 15px;
    padding-left: 0;
  }
 .navbar-header a img{
  height: 83px;
 } 

 
}
@media(max-width: 1024px){
   .nav-extended{
    left: 14%;
    font-size: 10px;
}
.navbar-default{
    left: 0;
}
}

@media(min-width: 992px){  .nav-extended{    display: inline-flex !important;  }}
<?php 
  if($getDoctorsuser['errCode']==-1)
  {?>
    li.doctorsUser
    {
      display:none;
    }
    <?php
  }
?>

  @media only screen and (min-width: 768px){
  .nav>li.hide-li{
    display: none;
  }
  .register-button{
    
    }
  }



@media screen and (min-width: 768px) and (max-width:1024px) {
      .btn-gray{
      border: none;
      border-bottom: 1px solid gray;
      border-left: 1px solid gray;
      border-right: 1px solid gray;
      position: relative;
      top: 0;
      right: 0;
    }
   
}

@media screen and (max-width: 765px){
  div.lginList{
    display: none;
  }
  li.nav-hidden{
    display: block;
  }
  ul.nav1 > li{
    float: none;
  }
}

@media screen and (min-width:480px) and (max-width:731px) {
  .nav-extended{
    position: relative;
    top:0px;
  }
  
}

@media screen and (min-width:320px) and (max-width:640px) {
  .nav-extended{
    position: relative;
    top:0px;
  }
  .navbar-default .navbar-nav>li>a{
  height: 50px;
 }
 .nav-extended {

    top: 0px;
}

}
@media (min-width: 769px) and (max-width: 1000px) {
   .collapse {
       display: none !important;
   }
    .nav>li>a {
        padding: 10px 6px !important;
    }
    
   header{
    padding: 0 !important;
   }
}

.has-borders{
  border-top: 1px solid #999;
  border-bottom: 1px solid #999;
  border-radius: 0;
}

.messages-menu .dropdown-menu{
  border : none;

}

.bg-yellow{
  background-color: #ffb600;
  position: absolute;
  margin-left: -5px;
}

.navbar-custom-menu .nav>li>a{
  padding: 10px 6px !important; 
}
.navbar-custom-menu {
  float: right;
}

.drop1{
  left: -19px !important;
  right: -257px !important;
}
.drop2{
  left: -77px !important;
  right: -203px !important;
}
.drop3{
  left: -133px !important;
  right: -18px !important;
}
@media(max-width: 768px){
  .drop3{
  left: -141px !important;
  right: 0px !important;
}
}



.tip:hover:after{
    background: #333;
    background: rgba(0,0,0,.8);
    border-radius: 5px;
    
    color: #fff;
    content: attr(title);
    left: 3%;
    padding: 5px 15px;
    position: absolute;
    top: 20%;
    z-index: 98;
    width: 302px;
}

.linkmenu{
      cursor: pointer;
    /*font-size: 20px;*/
    font-size: 15px;
    margin-left: 20px;
    margin-top: 0px !important;
    /*margin-bottom: 10px !important;*/
    margin-bottom: 10px !important;
}
.menu hr{
  margin-top: 10px;
  margin-bottom: 10px;
}
#complete_mask{
 width: 100vw;
 height: 200vh;
 z-index: 999999;
 position: absolute;
 left : 0;
 display: none;
 opacity: 0.5;
}

.dropdown-menu li ul li .viewpro:hover{
  color: #fff !important;
}

.custom-width{
  width: 320px;
  float: right;
  left: 0px !important;
  right: 1px !important;
}


@media (max-width: 992px) {
  .navbar{
    margin-top: 35px;
  }
  .nav-extended{
    position: relative;
    left: -3% !important;
  }
.navbar-header {
    float: none;
}
.navbar-left,.navbar-right {
    float: none !important;
}
.navbar-toggle {
    display: block;
}
.navbar-collapse {
    border-top: 1px solid transparent;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
}
.navbar-fixed-top {
    top: 0;
    border-width: 0 0 1px;
}
.navbar-collapse.collapse {
    display: none!important;
}
.navbar-nav {
    float: none!important;
    margin-top: 7.5px;
}
.navbar-nav>li {
    float: none;
}
.navbar-nav>li>a {
    padding-top: 10px;
    padding-bottom: 10px;
    height: 40px;
}

.collapse.in{
    display:block !important;
}
}

@media (max-width: 768px) {
  .nav-extended{
    position: relative;
    left: 0% !important;
  }
}

    </style>

<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '275536566188915',
      cookie     : true,
      xfbml      : true,
      version    : 'v2.8'
    });
    FB.AppEvents.logPageView();   
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<script type="text/javascript">
var ajaxarray =["efrg","fth"];

$(window).load(function() {
  $(".loader").fadeOut("slow");
})


$('#monitor').html($(window).width());

    $(window).resize(function() {
    var viewportWidth = $(window).width();
$('#monitor').html(viewportWidth);
});



        // d = disable other  
        // l = loading text
        // dest = destination
        // color code
        // show gif
        // changetxt = change text after completing ajax function 
          // function global_loader(d,dest,txt,color,loader,changetxt) {

          // if(d == "Y"){
          //   $("#complete_mask").css("display","block");
          //   $("#complete_mask").css("background-color",color);
          // }     

          // if(loader == "Y")
          // {
          // $("#"+dest).html(txt + "<img src='../../assets/images/ajax-loader.gif'>");    
          // }
          // else{
          // $("#"+dest).html(txt);  
          // }


          //  $(document).ajaxComplete(function() {
          //    $("#"+dest).html(changetxt); 
          //    $("#complete_mask").css("display","none");
          // });
          
          // }           

   

                    
</script>
</head>
<body>
<div id="complete_mask"></div>


  <div class="loader"></div>
    <header >
       <?php if(isset($_SESSION["user"])){
          $user = $_SESSION["user"];
          $user_type=$_SESSION["user_type"];
          /*$userInfo = getUserInfoWithUserType($user,$user_type,$conn);
          if(noError($userInfo)){
            $userInfo = $userInfo["errMsg"]; 
          } 

          $notificationdrop=getNotifcation($conn,$userInfo['user_email'],$pageNo=1,$limit=10);
          //printArr($notificationdrop);
        if(noError($notificationdrop)){
          //$totalPageNo=ceil($notificationdrop['countNotify']/10);
          $notificationdrop=$notificationdrop['errMsg'];

        }else{
          $notificationdrop[0]="No Data";

        }
*/
        ?>


           <!--Signup for Not Logged In User-->
           <!-- <div class="pull-right">
              <div class="register-button hidden-xs" >
                <a class="btn-gray"  href="sign_in.php">Sign up | Log in</a>
              </div>
            </div> -->
                  <!-- Login Header start -->
      <div class="row" style="margin-left: 0;margin-right: 0;">
        <div class="col-md-5 col-md-offset-7 col-xs-12 col-sm-6 col-sm-offset-6">
            <div class="navbar-custom-menu ">
                <ul class="nav navbar-nav">
                   <li class="dropdown messages-menu profile-menu ">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                      <?php if(!empty($userInfo['user_image']) && $userInfo['user_image']!=""){?>
                                          <img src="<?php echo $userInfo['user_image']; ?>" class="img-circle" alt="User Image" >
                                               <?php if(!empty($userInfo['title'])){ echo $userInfo['title'].'. '; } echo $userInfo['user_first_name']; ?> 
                                <?php }else{ ?>
                                          <img src="<?php echo $pathprefix;?>assets/images/cam.png" class="img-circle" alt="User Image" >
                                          <?php if(!empty($userInfo['title'])){ echo $userInfo['title'].'. '; } echo $userInfo['user_first_name']; ?> 


                                <?php }?>
                     
                      <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu drop3 custom-width">
                      
                      <li>
                        <!-- inner menu: for msg -->
                       <ul class="menu" style="overflow-y: hidden; width: 320px;padding: 10px; min-height: 290px;list-style:none;">
                        <!-- 1st Msg -->
                            <li style="border-bottom : none;">
                              <div class="pull-left">
                                <?php if(!empty($userInfo['user_image']) && $userInfo['user_image']!=""){?>
                                          <img src="<?php echo $userInfo['user_image']; ?>" class="img-circle" alt="User Image" style="margin-left: 20px;margin-bottom:40px;">
                                <?php }else{ ?>
                                          <img src="<?php echo $pathprefix;?>assets/images/cam.png" class="img-circle" alt="User Image" style="margin-left: 20px;margin-bottom:40px;"> 
                                <?php }?>
                              </div>
                             
                              <h3 style="line-height:20px !important;"><?php if(!empty($userInfo['title'])){ echo $userInfo['title'].'. '; } echo ucfirst(strtolower($userInfo['user_first_name'])).' '.ucfirst(strtolower($userInfo['user_last_name'])); ?></h3>
                              <h5  title="<?php echo $userInfo['user_email'];?>" ><?php echo $userInfo['user_email'];?></h5>
                              <!-- <input type="button" value="View profile" class="viewpro"> -->
                              <!-- <a href="<?php echo $views;?>profile/viewprofile.php" class="viewpro" >View profile</a> -->
                               <a href="<?php echo $views;?>profile/viewProfile.php" class="viewpro">View profile</a>
                              <div style="clear:both;"></div>
                              <hr>
                              <h2 class="linkmenu" onclick="location.href='<?php echo $views;?>dashboard/doctorsDashboard.php'">Dashboard</h2>
                              <h2 class="linkmenu"  onclick="location.href='<?php echo $views;?>patient/manage_patient.php'">My Patients</h2>
                              <h2 class="linkmenu"  onclick="location.href='<?php echo $views;?>KnowledgeCenter/'">Knowledge Center</h2>
                              <hr>
                              <h2 class="linkmenu" id="feedback" >Feedback</h2>
                              <hr>
                              <h2 class="linkmenu" id="chngpswd" >Change Password</h2>
                              <h2 class="linkmenu"  onclick="location.href='<?php echo $controllers;?>logout.php'">Logout</h2>
                            </li> 
                      
                        </ul>
                      </li>
                     
                    </ul>
                  </li>

                  <!-- LI END FOR user Profile -->
   
                </ul>
              </div>

        </div>
      </div>
            <?php } 
            else{
            ?>
            <div class="row" style="margin-left: 0;margin-right: 0;">
              <div class="col-md-5 col-md-offset-7 col-xs-12 col-sm-6 col-sm-offset-6">
                  <div style="float: right;">
                      <input type="button" class="btnLogin" value="Login" onclick="gotologin()" >
                      <input type="button" class="btnRegister" value="Register" onclick="gotosignup()" >
<!--                  <input  type="button" class="btnLogin" value="Login" data-toggle="modal" data-target="#loginmodal" ">
                      <input  type="button"  class="btnRegister" value="Register" data-toggle="modal" data-target="#signmodal">-->
                  </div>      
              </div>
            </div>
      
            <?php } ?>   
      <!-- Login Header End -->

      <nav class="navbar navbar-default <?php if(isset($_SESSION['user'])){ echo 'has-borders'; } ?>" style="margin-bottom: 0px;background-color: #ffffff;z-index: 999;">
        <div class="">
          
          <!--on collapse -->
            <div class="navbar-header">
              <button type="button" id="btn1" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a href="<?php echo $pathprefix; ?>index.php"><img style="padding:10px 10px 10px 0;min-height: 0px;max-width: 89px;" class="img-responsive " src="<?php echo $pathprefix;?>assets/images/logo.png"></a>
            </div>
     
          <div class="navbar-prg" style="">
           <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <?php if(!isset($_SESSION["user"]) || isset($_SESSION["user"])){ ?>

                  <!--Navigation Menu for Not Logged In User-->
                  <ul class="nav navbar-nav nav-extended">
                        <li>
                          <!-- <a href="<?php echo $views; ?>disease_compass.php" class="<?php if($activeHeader=="disease_compass"){ echo "active";}?>">Disease Compass</a> -->
                          <a href="<?php echo $views; ?>diseaseCompass/disease_compass.php" class="<?php if($activeHeader1=="disease_compass"){ echo "active";}?>">Disease Compass</a>
                        </li>
                        <li>
                          <a href="<?php echo $views; ?>2opinion" class="<?php if($activeHeader1=="2opinion"){ echo "active";}?>">Case Checker</a>
                        </li>
                        <li>
                          <a href="<?php echo $views; ?>stress_calculator/stress_calculator.php" class="<?php if($activeHeader1=="stress_calculater"){ echo "active";}?>">Stress Calculator</a>
                        </li>
                       <li>
                          <a href="<?php echo $views; ?>KnowledgeCenter" class="<?php if($activeHeader=="knowledge_center"){ echo "active";}?>" style="">Knowledge Center</a>
                        </li>
                        <?php if(!isset($_SESSION["user"])){ ?>
                        <li>
                        <!-- <a  data-toggle="modal" href="#signUpModal" id="modellink">Show Modal</a>
                         <a data-toggle="modal" href="views/Login/sign_up.html" data-target="#myModal">Doctors Area</a> -->
                          <!-- <a href="<?php echo $views; ?>Login/sign_in.php" class="">Doctors Area</a> -->
                           <!-- <a href="<?php echo $views; ?>sign_in.php?luser=doctor" class="">Doctors Area</a> -->
                           <!-- <a href="#" data-toggle="modal" data-target="#signmodal">Doctors Area</a> -->
                           <a href="<?php echo $rootUrl; ?>/index.php?luser=doctor" class="" >Doctors Area</a>
                        </li>
                        
<!--                        <li>
                        <a >Patients Area</a>
                          <a href="<?php echo $rootUrl; ?>/index.php?luser=patient" >Patients Area</a> 
                           <a href="<?php echo $views; ?>sign_in.php?luser=patient" class="">Patients Area</a> 
                        </li>-->
                        <?php }else{ ?>
                          <li>
                        <!-- <a  data-toggle="modal" href="#signUpModal" id="modellink">Show Modal</a>
                         <a data-toggle="modal" href="views/Login/sign_up.html" data-target="#myModal">Doctors Area</a> -->
                          <!-- <a href="<?php echo $views; ?>Login/sign_in.php" class="">Doctors Area</a> -->
                           <!-- <a href="<?php echo $views; ?>sign_in.php?luser=doctor" class="">Doctors Area</a> -->
                           <!-- <a href="#" data-toggle="modal" data-target="#signmodal">Doctors Area</a> -->
                           <a href="<?php echo $views;?>dashboard/doctorsDashboard.php" class="<?php if($activeHeader=="doctorsArea"){ echo "active";}?>" >Doctors Area</a>
                        </li>
                        
<!--                        <li>
                         <a href="#" class="<?php if($activeHeader=="patientsArea"){ echo "active";}?>">Patients Area</a>
                           <a href="<?php echo $views; ?>sign_in.php?luser=patient" class="">Patients Area</a> 
                        </li>-->
                        <?php } ?>
<!-- 
                        <li class="hide-li <?php if($activeHeader=="sign_in"){ echo "active";}?>" >
                          <a href="<?php echo $views; ?>sign_in.php">Sign up | Log in</a>
                        </li> -->
                  </ul>
                  <input class="" id="check" value="0" type="hidden"/>
                <?php } ?>            
           </div>
          </div>
        </div>

      </nav>
      <div class="modal-container"></div>
 

    </header>
   <script type="text/javascript">
     function refineUrl()
{
    //get full url
    var url = window.location.href;
    //get url after/  
    var value = url.substring(url.lastIndexOf('/') + 1);
    //get the part after before ?
    value  = value.split("?")[0];   
    return value;     
}

      var url = "views/Login/sign_up.html";
        jQuery('#modellink').click(function(e) {
            $('.modal-container').load(url,function(result){
                $('#signUpModal').modal({show:true});
            });
        });
  
    window.domainURL = {
        domainName :"<?php echo DOMAIN_NAME;?>",
        controller : "<?php echo "$controllers"; ?>",
        view : "<?php echo $views; ?>"
    }
    
    $('#feedback').click(function(e) {
        console.log('feedback click');
        if (typeof gotofeedback  != 'undefined' && $.isFunction(gotofeedback)) {
            console.log('gotofeedback exists');
            gotofeedback();
        } else {
            console.log('gotofeedback does not exist');
        }
     
    });
    
    
    $('#chngpswd').click(function(e) {
        console.log('chngpswd click');
        if (typeof gotochngpswd  != 'undefined' && $.isFunction(gotochngpswd)) {
            console.log('gotochngpswd exists');
            gotochngpswd();
        } else {
            console.log('gotochngpswd does not exist');
        }
     
    });


   </script>

    <?php 


function for_doctors($requestUri)
{
    $current_file_name = basename($_SERVER['PHP_SELF'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="abc"';
       
}



 function for_students($requestUri)
{
    $current_file_name = basename($_SERVER['PHP_SELF'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="abc"';

} 


function medicine($requestUri)
{
    $current_file_name = basename($_SERVER['PHP_SELF'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="abc"';

} 


function index($requestUri)
{
  $var1="";
    $current_file_name = basename($_SERVER['PHP_SELF'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="abc"';
        echo 'class="index_arrow"';
} 

function caseHistory($requestUri){
    $var1="";
    $current_file_name = basename($_SERVER['PHP_SELF'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="abc"';
        echo 'class="index_arrow"';
}



function knowledge_center($requestUri)
{
    $current_file_name = basename($_SERVER['PHP_SELF'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="abc"';
} 


function sign_up($requestUri)
{
    $current_file_name = basename($_SERVER['PHP_SELF'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="abc"'; 

}




function opinion($requestUri)
{
    $current_file_name = basename($_SERVER['PHP_SELF'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="abc"';
} 


function login($requestUri)
{
    $current_file_name = basename($_SERVER['PHP_SELF'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="abc"';
        echo 'class="triangle"';
} 

?>
