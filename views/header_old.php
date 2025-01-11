<?php
session_start();

 
  if($activeHeader=="2opinion" || $activeHeader=='knowledge_center')
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
//database connection handling

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();
if(noError($conn)){
  $conn = $conn["errMsg"];
} else {
  //printArr("Database Error");
  exit;
}

/*$user=$_SESSION['user'];
$getDoctorsuser=getDoctorsUser($conn,$user);*/
//printArr($getDoctorsuser);


  $doctorPlanOver = 0;
  $fifteenDaysOver = 0;

?>
<style>



@font-face{
  font-family: Montserrat-Regular;
  src: url(<?php echo $pathprefix."assets/fonts/montserrat/Montserrat-Regular.woff"?>); 
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
    .active{	background-color:#fffaee;}
    .navbar .navbar-default
    {
      margin-bottom: 0px;
    background-color: #ffffff;
    }

  .navbar li a, .navbar .navbar-brand {
      color: #080808 !important;
      padding-top: 55px;
    padding-bottom: 50px;
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
    left: 20px;
}

@media(max-width: 768px){
   .nav-extended{
    position: absolute;
    top: 44px;
    left: 15% !important;
    font-size: 8px !important;
}
.navbar-default{
    left: 0;
}
.navbar li a, .navbar .navbar-brand {
    padding: 10px 15px;
    padding-left: 0;
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


@media (min-width: 992px){

}
@media (min-width: 1200px){
  
}
@media (min-width: 1157px){

}
@media screen and (min-width: 768px) and (max-width:1024px) {
      .btn-gray{
      border: none;
      border-bottom: 1px solid grey;
      border-left: 1px solid grey;
      border-right: 1px solid grey;
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
    .nav>li>a{
          padding: 10px 15px;
    }
}

@media screen and (min-width:320px) and (max-width:640px) {
  .nav-extended{
    position: relative;
    top:0px;
  }

}
@media (min-width: 768px) and (max-width: 1000px) {
   .collapse {
       display: none !important;
   }
    .nav>li>a {
        padding: 10px 6px;
    }
    
   header{
    padding: 0 !important;
   }
}


    </style>

    <header >
      <nav class="navbar navbar-default" style="margin-bottom: 10px;background-color: #ffffff;border:0;z-index: 999;">
        <div class="">
           <?php if(!isset($_SESSION["user"])){ ?>
           <!--Signup for Not Logged In User-->
       		 <!-- <div class="pull-right">
              <div class="register-button hidden-xs" >
                <a class="btn-gray"  href="sign_in.php">Sign up | Log in</a>
              </div>
            </div> -->
           <?php } ?>
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
               
                <?php if(isset($_SESSION["user"]) and $_SESSION['group']['patient']==$_SESSION['userInfo']['user_type_id']){ ?>

                    <input class="" id="check" value="1" type="hidden">
                    <div class="lginList col-sm-10 pull-right">
                      <ul class="list-inline pull-right">
                        <li>
                          <a href="<?php echo $views; ?>caseHistory.php">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            Hello <?php echo $_SESSION['userInfo']['user_first_name']." ".$_SESSION['userInfo']['user_last_name']; ?> 
                          </a>
                        </li>
                        <li class="line-content"><span  style="color:black;">|</span></li>
                        <li>
                          <a  href="<?php echo $views; ?>dashboard.php">Complete profile</a>
                        </li>
                        <li class="line-content"><span  style="color:black;">|</span></li>
                        <li>
                          <a href="<?php echo $views; ?>notification_view.php" class="notification" style="position: relative;">
                            <i class="fa fa-bell" aria-hidden="true"></i>
                            <span>&nbsp;</span>
                          </a>
                        </li>
                        <li class="line-content"><span  style="color:black;">|</span></li>
                        <li>
                          <a  href="<?php echo $controllers; ?>logout.php">Logout</a>
          				      </li>
                      </ul>
                    </div> 
                    <ul class="nav navbar-nav pull-right nav1">
                      <li class="nav-hidden">
                        <ul class="nav">
                          <li>
                            <a  href="<?php echo $views; ?>dashboard.php"><i class="fa fa-user" aria-hidden="true"></i>Complete profile</a>
                          </li>
                           <li>
                            <a href="<?php echo $views; ?>notification_view.php" class="notification_view" style="position: relative;">
                              <i class="fa fa-bell" aria-hidden="true"></i>
                              <span>&nbsp;</span>
                            </a>
                          </li>
                          <li>
                            <a  href="<?php echo $controllers; ?>logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a>
                          </li>
                        </ul>
                      </li>
        					    <li>
        					    	<a href="<?php echo $views; ?>caseHistory.php" class="<?php if($activeHeader=='caseHistory'){ echo 'active';}?>">Dashboard</a>
        					    </li>
        					    <li class="doctorsUser">
        					    	<a href="<?php echo $views; ?>get_medicine.php" class="<?php if($activeHeader=="get_medicine"){ echo "active";}?>">Get Medicine</a>
        					    </li>
        					    <li  class="doctorsUser">
        					    	<a href="<?php echo $views; ?>2opinion" class="<?php if($activeHeader=="2opinion"){ echo "active";}?>">2nd Opinion</a>
        					    </li>
        					   <li>
        					   		<a href="<?php echo $views; ?>for_doctors.php" class="<?php if($activeHeader=="for_doctors"){ echo "active";}?>">My Doctors</a>
        					   	</li> 
        					   	<li>
        					   		<a href="<?php echo $views; ?>knowledge_center.php" class="<?php if($activeHeader=="knowledge_center"){ echo "active";}?>">Knowledge Center</a>
        					   	</li>
        					   	
        					  </ul>  
          
                <?php }elseif(isset($_SESSION["user"]) and $_SESSION['group']['doctor']==$_SESSION['userInfo']['user_type_id']){ ?>
                    <input class="" id="check" value="1" type="hidden">
                    <div class="lginList col-sm-10 pull-right">
                      <ul class="list-inline pull-right">
                        <li>
                          <a href="<?php echo $views; ?>doctor_caseHistory.php">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            Hello <?php echo $_SESSION["user"]; ?> 
                          </a>
                        </li>
                        <li class="line-content"><span  style="color:black;">|</span></li>
                        <li>
                          <a  href="<?php echo $views; ?>dashboard.php">Complete profile</a>
                        </li>
                        <li class="line-content"><span  style="color:black;">|</span></li>
                        <li>
                          <a href="<?php echo $views; ?>notification_view.php" class="notification" style="position: relative;">
                            <i class="fa fa-bell" aria-hidden="true"></i>
                            <span>&nbsp;</span>
                          </a>
                        </li>
                        <li class="line-content"><span  style="color:black;">|</span></li>
                        <li>
                          <a  href="<?php echo $controllers; ?>logout.php">Logout</a>
                        </li>
                      </ul>
                    </div> 
                    <ul class="nav navbar-nav pull-right nav1">
                        <li class="nav-hidden">
                          <ul class="nav">
                            <li>
                              <a  href="<?php echo $views; ?>dashboard.php"><i class="fa fa-user" aria-hidden="true"></i>Complete profile</a>
                            </li>
                             <li>
                              <a href="<?php echo $views; ?>notification_view.php" class="notification_view" style="position: relative;">
                                <i class="fa fa-bell" aria-hidden="true"></i>
                                <span>&nbsp;</span>
                              </a>
                            </li>
                            <li>
                              <a  href="<?php echo $controllers; ?>logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Logout</a>
                            </li>
                          </ul>
                        </li>
                        <li>
                          <a href="<?php echo $views; ?>doctor_caseHistory.php" class="<?php if($activeHeader=="doctor_caseHistory"){ echo "active";}?>">Dashboard</a>
                        </li> 
                        <li>
                          <a href="<?php echo $views; ?>patient.php" class="<?php if($activeHeader=="patient"){ echo "active";}?>">My Patient</a>
                        </li> 
                        <li>
                          <a href="<?php echo $views; ?>knowledge_center.php" class="<?php if($activeHeader=="knowledge_center"){ echo "active";}?>">Knowledge Center</a>
                        </li>
                    </ul>  
                    <?php
                      $renewArr = checkExpiryPurchase($conn, $_SESSION['userInfo']['user_type_id'], $_SESSION["user"]);
                     
                      if(noError($renewArr)){

                        $doctorPlanOver = $renewArr['errMsg'];
                        $fifteenDaysOver = $renewArr['fifteenDaysOver'];
                        
                      }else{
                        printArr($renewArr['errMsg']);
                      }
                    ?>
                <?php }elseif(!isset($_SESSION["user"])){ ?>

                  <!--Navigation Menu for Not Logged In User-->
    			        <ul class="nav navbar-nav nav-extended">
          					    <li>
          					    	<a href="<?php echo $views; ?>disease_compass.php" class="<?php if($activeHeader=="disease_compass"){ echo "active";}?>">Disease Compass</a>
          					    </li>
          					    <li>
          					    	<a href="<?php echo $views; ?>2opinion" class="<?php if($activeHeader=="2opinion"){ echo "active";}?>">2nd Opinion</a>
          					    </li>
          					    <li>
          					    	<a href="<?php echo $views; ?>stress_calculator.php" class="<?php if($activeHeader=="stress_calculater"){ echo "active";}?>">Stress Calculator</a>
          					    </li>
          					   <li>
          					   		<a href="<?php echo $views; ?>KnowledgeCenter" class="<?php if($activeHeader=="knowledge_center"){ echo "active";}?>" style="">Knowledge Center</a>
          					   	</li>
                        <li>
                        <!-- <a  data-toggle="modal" href="#signUpModal" id="modellink">Show Modal</a>
                         <a data-toggle="modal" href="views/Login/sign_up.html" data-target="#myModal">Doctors Area</a> -->
                          <!-- <a href="<?php echo $views; ?>Login/sign_in.php" class="">Doctors Area</a> -->
                           <!-- <a href="<?php echo $views; ?>sign_in.php?luser=doctor" class="">Doctors Area</a> -->
                           <a href="#" data-toggle="modal" data-target="#signmodal">Doctors Area</a>
                        </li>
                        <li>
                         <a href="<?php echo $views; ?>Login/sign_in.php" class="">Patients Area</a>
                          <!-- <a href="<?php echo $views; ?>sign_in.php?luser=patient" class="">Patients Area</a> -->
                        </li>
            						<li class="hide-li <?php if($activeHeader=="sign_in"){ echo "active";}?>" >
            							<a href="<?php echo $views; ?>sign_in.php">Sign up | Log in</a>
            						</li>
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
