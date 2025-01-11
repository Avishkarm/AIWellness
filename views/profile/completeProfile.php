<?php
//$activeHeader="2opinion";
$pathPrefix="../";
$activeHeader = "doctorsArea";

session_start();

require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php");
require_once("../../models/userModel.php");
require_once("../../models/completeProfileModel.php");

	//database connection handling
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
} else {
	    //printArr("Database Error");
	exit;
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
   $redirectURL ="../../index.php?luser=doctor";
  header("Location:".$redirectURL); 
  exit;
}
//printArr($userInfo);
$trial=0;
if($userInfo['firstUpdate']==0){
  $trial=1;
  $query="UPDATE users SET firstUpdate=1 WHERE user_id=".$userInfo['user_id'];
  $result = runQuery($query, $conn);
}

//printArr($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
<head>


  <?php include_once("../metaInclude.php"); ?>
	<style type="text/css">
    

  .social-icon ul li a i {
    color: #666;
    font-size: 20px;
    text-align: center;
    background-color: bisque;
   
}
 .social-icon ul li {
   display: inline-block;
}

.social-icon ul li a {
   padding: 3px 9px;
}

.form-group {
    padding-right: 0;
}

.cam-pic{
  height: 120px;
  width: 120px;
}

.gerneral-form{
  padding-right: 0;
}

@media( max-width: 475px){

  .gerneral-form .label-div {
    width: 100%;
    text-align: left;
    float: left;
    margin-bottom: 0px;
}

.gerneral-form .field-input {
    width: 100%;
    display: inline-block;
    float: right;
    height: 50px;
    margin-bottom: 40px;
}
.gerneral-form .field-inputtxt input[type=text], .gerneral-form .field-input input[type=text], .gerneral-form .field-input input[type=file], .gerneral-form .field-input input[type=file], .gerneral-form .field-input input[type=date],.gerneral-form .field-input textarea, .gerneral-form .field-inputarea textarea{
  margin-left: 0;
   width: 100%;
}
.gerneral-form .field-input select{
  margin-left: 0;
  width: 100%;
}

.gerneral-form .field-inputarea{
width: 100%;
margin-bottom: 0;

}

.gerneral-form{
  padding-left: 15px;
  padding-right: 15px; 
}
.gerneral-form .form-group{
  padding:0px;
}
.gerneral-form .field-inputtxt{
  width: 68.8%;
}
.gerneral-form .label-divarea{
  width: 100%;
  text-align: left;
  height: auto;
  margin-bottom: 0;
}

.cam-pic{
  height: 70px !important;
  width: 70px !important;
  margin-top: 22px;
}

/*.nav>li>a{
  padding: 10px 11px;
}*/

}

@media(max-width: 1016px) and (min-width: 613px){
  .cam-pic{
  height: 95px !important;
  width: 95px !important;
  margin-top: 0px !important;
}
}

@media(max-width: 612px) and (min-width: 476px){
  .cam-pic{
  height: 90px !important;
  width: 90px !important;
  margin-top: 15px;
}
}


select{
    -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-appearance: none;
  -moz-appearance: none;
}

select.minimal {
        background-image: linear-gradient(45deg, transparent 50%, gray 50%), linear-gradient(135deg, gray 50%, transparent 50%), linear-gradient(to right, #ccc, #ccc);
    background-position: calc(100% - 23px) calc(1em + 10px), calc(100% - 16px) calc(1em + 10px), calc(100% - 2.5em) 0.5em;
    background-size: 8px 8px, 8px 8px, 0px 5em;
    background-repeat: no-repeat;

}

.form-group label{
  cursor: auto;
}
@media(max-width: 475px){
.mobwidth{
  width: 73% !important;
}
}
@media(min-width: 476px){
.mobwidth{
  width: 69% !important;
}
}
@media(min-width: 991px){
.mobwidth{
  width: 70% !important;
}
}


.ui-datepicker-month ,.ui-datepicker-year{
    border-color: #ccc !important;
    width: 65%;
    height: 20px !important; 
    border-radius: 0px; 
    margin-right: 10px;
    background-color: white;
    color: #555;
  }
		/*header{
			padding:7px 20px !important;
		}*/
	</style>
	<link rel="stylesheet" type="text/css" href="../../assets/css/home.css?aghrd=r4564298">
<!--    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script> -->


	<main class="container" style="min-height: 100%;">

       <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>




		<?php  include_once("../header.php"); ?>     
	
         


    <div class="row offering" style="margin-left: 0;margin-right: 0;">
      <div class="col-md-12 col-sm-12 col-xs-12 genralinfo" >
        <!-- <h4>Please tell us more about yourself. This will ensure you get accurate results</h4> -->
      </div>


      <div class="col-md-4 col-sm-4 col-xs-12 info-left-links " >
        <div class="generaltab active">
          <a onclick="showgeneral_info()" style="cursor:pointer;" >General Info</a>
        </div>
        <div class="coninfo">
          <a onclick="showcontact_info()" style="cursor:pointer;">Contact Info</a>
        </div>

        <div class="view-profile-btndiv">
        <input type="button" name="view-profile-btn" class="view-profile" value="VIEW PROFILE">
        </div>
      </div>

      <div id="general-form">
      <form action="javascript:;" name="general-form" enctype="multipart/form-data" id="generalForm" >
        <div class="col-md-8 col-sm-8 col-xs-12 gerneral-form" >
                  
                <div class="errBox" >  
                  <div class="alert alert-danger errMsg">          
                  </div>
                </div>


              
               <div class="successBox">
                  <div class="alert alert-success successMsg">     
               </div>
                <!--   <div class="field-inputarea">
                    <textarea  readonly style="color:white;font-size:18px;border:none;background-color:#87de87;"  class="form-control successMsg"></textarea>
                  </div>   -->
               </div>
               <div class="form-group">
                  <div class="label-div">
                    <label>Title </label>
                  </div>
                  <div class="field-input">
                    <select name="title" class="form-control minimal">
                      <option value="Dr" <?php if(!empty($userInfo['title'])){ if($userInfo['title']=='Dr') echo 'selected'; }else{ echo "selected"; } ?> >Dr</option>
                      <option value="Mr" <?php if($userInfo['title']=='Mr') echo 'selected'; ?> >Mr</option>
                      <option value="Mrs" <?php if($userInfo['title']=='Mrs') echo 'selected'; ?> >Mrs</option>
                      <option value="Miss" <?php if($userInfo['title']=='Miss') echo 'selected'; ?> >Miss</option>
                    </select>
                  </div>  
               </div>
               <div class="form-group">
                  <div class="label-div">
                    <label>Name</label>
                  </div>
                  <div class="field-input">
                    <input type="text" name="user_first_name" maxlength="20" value="<?php echo $userInfo['user_first_name']; ?>" class="form-control">
                  </div>  
               </div> 


               <div class="form-group">
                  <div class="label-div">
                    <label>Last name</label>
                  </div>
                  <div class="field-input">
                    <input type="text" name="user_last_name" maxlength="20" value="<?php echo $userInfo['user_last_name']; ?>" class="form-control">
                  </div>  
               </div> 


               <div class="form-group">
                  <div class="label-div">
                    <label>Registration No</label>
                  </div>
                  <div class="field-input">
                    <input type="text" name="user_reg_no" value="<?php echo $userInfo['user_reg_no']; ?>" class="form-control">
                  </div>  
               </div> 


               <div class="form-group">
                  <div class="label-div">
                    <label>Gender <span class="req">*</span></label>
                  </div>
                  <div class="field-input">

                    <select name="user_gender" class="form-control minimal">

                      <option selected disabled class="hideoption">Select Gender</option>
                      <!--  <option value="audi" selected>Audi</option> -->
                      <option value="Male" <?php if($userInfo['user_gender']=='Male') echo 'selected'; ?> >Male</option>
                    <option value="Female" <?php if($userInfo['user_gender']=='Female') echo 'selected'; ?> >Female</option>
                      <option value="Transgender" <?php if($userInfo['user_gender']=='Transgender') echo 'selected'; ?> >Transgender</option> 
                    </select>
                  </div>  
               </div> 


               <div class="form-group">
                  <div class="label-div">
                    <label>Nationality</label>
                  </div>
                  <div class="field-input">
                    <!-- <select name="user_nationality" class="form-control">
                      <option <?php if($userInfo['user_gender']=='USA') echo 'selected'; ?>>USA</option>
                      <option <?php if($userInfo['user_gender']=='Indian') echo 'selected'; ?>>Indian</option>
                    </select> -->
                    <?php $countries=getAllNAtinalities($conn); ?>
                      <select data-placeholder="Select Nationality " class="form-control minimal" type="text" id="user_nationality" name="user_nationality">
                          <option value="" selected disabled >Select Nationality</option>
                            <?php               
                            foreach($countries['errMsg'] as $countryId=>$countryDetails){
                              $countryName = $countryDetails["name"];
                              $selected = "";
                              if($countryName==$userInfo["user_nationality"])
                                $selected = "selected";
                              ?>
                              <option data-id="<?php echo $countryId; ?>" value="<?php echo $countryName; ?>" <?php echo $selected; ?>><?php echo $countryName; ?></option>
                              <?php 
                            }
                            ?>
                        </select>
                  </div>  
               </div> 


               <div class="form-group">
                  <div class="label-div">
                    <label>Marital Status</label>
                  </div>

                  <div class="field-input">
                    <select name="user_marital_status" class="form-control minimal">
                    <option selected disabled class="hideoption">Select Marital Status</option>
                      <option <?php if($userInfo['user_marital_status']=='Single') echo 'selected'; ?> >Single</option>
                      <option <?php if($userInfo['user_marital_status']=='Married') echo 'selected'; ?> >Married</option>
                      <option <?php if($userInfo['user_marital_status']=='Divorced') echo 'selected'; ?> >Divorced</option>
                    </select>
                  </div>  
               </div> 


            <!--   <div class="form-group">
                  <div class="label-div">
                    <label>Date of birth <span class="req">*</span></label>
                  </div>
                  <div class="field-input date">
                      <div class="input-group input-append date" id="datePicker">
                        <input type="text" class="form-control" name="user_dob" value="<?php if(!empty($userInfo['user_dob'])){ echo date('d/m/Y', strtotime($userInfo['user_dob'])); } ?>" />
                        <span class="input-group-addon add-on"><img src="../../assets/images/datepicker.png" style="width: 25px;"></span>
                      </div>
                  </div>  
              </div> --> 



              <div class="form-group">
                  <div class="label-div">
                    <label>Date of Birth <span class="req">*</span></label>
                  </div>
                  <div class="field-input"  style="position:relative">
                    <input type="text" id="datePicker" name="user_dob" class="form-control" value="<?php if(!empty($userInfo['user_dob'])){ echo date('d/m/Y', strtotime($userInfo['user_dob'])); } ?>">
                    <img src="../../assets/images/datepicker.png" onclick="showcalender()" style="width:25px;position:absolute;top:10px;right:25px;cursor:pointer;">
                  </div>  
              </div>   

              


               <!-- <div class="form-group">
                  <div class="label-div">
                    <label>Height</label>
                  </div>
                  <div class="field-inputtxt">
                    <input type="text" name="height" value="<?php echo $userInfo['height']; ?>" class="form-control">
                  </div>  
                  <div class="field-inputdrop">
                    <select name="height_unit" class="form-control minimal">
                    <option <?php if($userInfo['height_unit']=='feet') echo 'selected'; ?> >feet</option>
                    <option <?php if($userInfo['height_unit']=='cm') echo 'selected'; ?> >cm</option>
                    </select>
                  </div>  
                  
               </div> 

               <div class="form-group">
                  <div class="label-div">
                    <label>Weight</label>
                  </div>
                  <div class="field-inputtxt">
                    <input type="text" name="weight" value="<?php echo $userInfo['weight']; ?>" class="form-control">
                  </div>  

                  <div class="field-inputdrop">
                    <select name="weight_unit" class="minimal">
                      <option <?php if($userInfo['height_unit']=='kg') echo 'selected'; ?> >kg</option>
                      <option <?php if($userInfo['height_unit']=='pounds') echo 'selected'; ?> >pounds</option>
                    </select>
                  </div>  
               </div>  -->
               <div class="form-group">
                  <div class="label-divarea">
                    <label>Highest Degree</label>
                  </div>
                  <div class="field-inputarea">
                    <textarea class="form-control" name="highest_degree" id="higest-degree" maxlength="250"><?php echo $userInfo['highest_degree']; ?></textarea>
                    <span id="textarea_feedback" class="textfeedback"></span>
                  </div>  
               </div> 


               <div class="form-group">
                  <div class="label-div">
                    <label style="margin-top: 30px;">Upload picture</label>
                  </div>
                 <div class="field-input">
                 <div class="col-md-3 col-xs-3">
                   <img id="user-pic" <?php if($userInfo['user_image']!="") echo 'src="'.$userInfo['user_image'].'"'; else echo 'src="../../assets/images/cam.png"'; ?> class="img-circle cam-pic">
                  </div>
                 
                  <div class="col-md-9 col-xs-9" style="padding-right: 0;">
                   <!--  <input type="text" id="fc" class="form-control" readonly style="float: right;margin-top: 30px;" value="Choose file"> -->
                    <input type="file" name="profile_pic" id="select-img" class="" style="position:relative;overflow:hidden;margin-top: 30px;border: none;">
                  </div>
                 </div>
               </div> 

        
                <!-- <input type="hidden" name="firstUpdate" value='1' class="form-control"> -->
                <input type="hidden" name="form_type" value="general" class="form-control">
        </div>

        <div class="col-md-6 col-sm-6 col-xs-6" style="margin-top: 40px;">
            <input type="submit" name="" value="SAVE" class="general_save general">
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6" style="margin-top: 40px;">
            <input type="button" name="" value="SKIP" class="general_skip" onclick="showcontact_info()">
        </div>

      </form>
      </div>


       <div id="contact-form" style="display: none;">
       <form name="general-form" id="contactForm">
          <div class="col-md-8 col-sm-8 col-xs-12 gerneral-form" style="padding-right: 0;" >
                

                  <div class="errBox">  
                  <div class="alert alert-danger errMsg">
               
                  </div>
                  </div>


                
                 <div class="successBox" >
                 <div class="alert alert-success successMsg">
               
                 </div>
                 </div>
                  <div class="form-group">
                    <div class="label-divarea">
                      <label>Address</label>
                    </div>
                    <div class="field-inputarea">
                      <textarea class="form-control" name="user_address" id="address" maxlength="250"><?php echo $userInfo['user_address']; ?></textarea>
                      <span id="addr_feedback" class="textfeedback"></span>
                    </div>  
                 </div> 
                 


                 <div class="form-group">
                    <div class="label-div">
                      <label>Country</label>
                    </div>
                    <div class="field-input">
                      <!-- <select name="user_country" class="form-control">
                        <option <?php if($userInfo['user_country']=='USA') echo 'selected'; ?> >USA</option>
                        <option <?php if($userInfo['user_country']=='India') echo 'selected'; ?> >India</option>
                      </select> -->
                     <?php $countries=getAllContries($conn); ?>
                      <select data-placeholder="Select Country" class="form-control minimal"  type="text" id="user_country" name="user_country">
                          <option value="" selected disabled>Select Country</option>
                            <?php               
                            foreach($countries['errMsg'] as $countryId=>$countryDetails){
                              $countryName = $countryDetails["name"];
                              $selected = "";
                              if($countryName==$userInfo["user_country"])
                                $selected = "selected";
                              ?>
                              <option data-id="<?php echo $countryId; ?>" value="<?php echo $countryName; ?>" <?php echo $selected; ?>><?php echo $countryName; ?></option>
                              <?php 
                            }
                            ?>
                        </select>
                    </div>  
                 </div> 
                  <div class="form-group">
                    <div class="label-div">
                      <label>State</label>
                    </div>
                    <div class="field-input">
                      <!-- <select name="user_state" class="form-control">
                        <option <?php if($userInfo['user_state']=='California') echo 'selected'; ?> >California</option>
                        <option <?php if($userInfo['user_state']=='Mumbai') echo 'selected'; ?> >Mumbai</option>
                      </select> -->
                      <select data-placeholder="Select state" class="form-control user_state minimal"  type="text" id="user_state" name="user_state">
                              <?php if(empty($userInfo["user_state"]))
                              {?>
                              <option value="" selected disabled>Select State</option>
                              <?php }else{ 
                                ?>
                                <option value="<?php echo $userInfo["user_state"]; ?>" selected><?php echo $userInfo["user_state"]; ?></option>
                                <?php } ?>
                      </select>
                    </div>  
                 </div> 

                  <div class="form-group">
                    <div class="label-div">
                      <label>City</label>
                    </div>
                    <div class="field-input">
                      <!-- <select name="user_city" class="form-control">
                        <option <?php if($userInfo['user_city']=='Los Angeles') echo 'selected'; ?> >Los Angeles</option>
                        <option <?php if($userInfo['user_city']=='Mumbai') echo 'selected'; ?> >Mumbai</option>
                      </select> -->
                      <select data-placeholder="Select city" class="form-control user_city minimal"  type="text" id="user_city" name="user_city">
                              <?php if(empty($userInfo["user_city"]))
                              {?>
                              <option value="" selected disabled>Select City</option>
                              <?php }else{ 
                                ?>
                                <option value="<?php echo $userInfo["user_city"]; ?>" selected><?php echo $userInfo["user_city"]; ?></option>
                                <?php } ?>
                      </select>
                    </div>  
                 </div> 


                 <div class="form-group">
                    <div class="label-div">
                      <label>Zip</label>
                    </div>
                    <div class="field-input">
                      <input type="text" name="user_zip" pattern="[0-9]{6}" placeholder="for ex. 400099" value="<?php echo $userInfo['user_zip']; ?>"  class="form-control">
                    </div>  
                 </div> 

                 <div class="form-group">
                    <div class="label-div">
                      <label>Mobile no</label>
                    </div>
                    <div class="field-input" style="display:inline-block;">

                    <select class="form-control  minimal" name="country_code" style="width:25%;display:inline-block;">
                          <?php               
                        foreach($countries['errMsg'] as $countryId=>$countryDetails){
                          $countryName = $countryDetails["name"];
                          $country_code= $countryDetails["country_code"];
                          $selected = "";
                          if(empty($userInfo['country_code']) && $countryId==101)
                            $selected = "selected";
                          if(!empty($country_code)){
                          ?>
                          <option data-id="<?php echo $country_code; ?>" <?php if($userInfo['country_code']==$country_code) echo 'selected'; ?> value="<?php echo $country_code; ?>" <?php echo $selected; ?>><?php echo $country_code; ?></option>
                          <?php 
                        }}
                        ?> 
                    </select>

                      <input type="text" name="user_mob" value="<?php echo $userInfo['user_mob']; ?>" class="form-control mobwidth" style="display:inline-block;margin-left:0;">
                    </div>  
                 </div> 


                 <div class="form-group">
                    <div class="label-div">
                      <label>Landline no</label>
                    </div>
                    <div class="field-input">
                      <input type="text" pattern="[0-9]{3}[-][0-9]{8}" placeholder="for ex. 022-12312312" name="user_landline_no" value="<?php echo $userInfo['user_landline_no']; ?>" class="form-control">
                    </div>  
                 </div>

                 <div class="form-group">
                    <div class="label-div">
                      <label>Alternative Email</label>
                    </div>
                    <div class="field-input">
                      <input type="email" name="user_alt_email" value="<?php echo $userInfo['user_alt_email']; ?>" class="form-control">
                    </div>  
                 </div> 
                 <input type="hidden" name="firstUpdate" value="1" class="form-control">
                 <input type="hidden" name="form_type" value="contact" class="form-control">
          </div>

          <div class="col-md-6 col-sm-6 col-xs-6">
              <input type="submit" name="" value="SAVE" class="general_save contact">
          </div>
          <div class="col-md-6 col-sm-6 col-xs-6">
              <input type="button" name="" value="SKIP" class="general_skip view-profile">
          </div>
      </form>
      </div>


    </div>

     <!-- ******************** Successful Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="myModel" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
            <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h2>Get free 90-day trial</h2>

                <h4 class="msg"></h4>
                <!-- <input type="hidden" name="" class="resendMsg" > -->
                <button name="Get now" class="gotomailbtn freeTrial"  id="gotomailbtn">Get now</button>
                <!-- <input type="button" name="gotomail" class="gotomailbtn"  data-dismiss="modal" value="Go to Email" onclick="openMailBox()"> -->
            </div>
            </div>

            </div>
        </div>
      </div>
  </section>
  <section name="modal">
      <div class="modal fade" id="successModel" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
            <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h2>90-day free trial is activated!</h2>

                <h4 class="msg">Congratulations. Your 90-day trial is activated now you can go ahead and add your first patient!</h4>
                <!-- <input type="hidden" name="" class="resendMsg" > -->
                <button name="Get now" class="gotomailbtn addPatient"  id="gotomailbtn">Add patients</button>
                <!-- <input type="button" name="gotomail" class="gotomailbtn"  data-dismiss="modal" value="Go to Email" onclick="openMailBox()"> -->
            </div>
            </div>

            </div>
        </div>
      </div>
  </section>
    <section name="modal">
      <div class="modal fade" id="failModel" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
            <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h2>Failed to activate 90-day trial</h2>

                <!-- <h4 class="msg">Congratulations. Your 90-day trial is activated now you can go ahead and add your first patient!</h4> -->
                <!-- <input type="hidden" name="" class="resendMsg" > -->
                <button name="Get now" class="gotomailbtn"  id="gotomailbtn">Try again</button>
                <!-- <input type="button" name="gotomail" class="gotomailbtn"  data-dismiss="modal" value="Go to Email" onclick="openMailBox()"> -->
            </div>
            </div>

            </div>
        </div>
      </div>
  </section>                      
        
      




</main> 
<?php include("../modals.php"); ?> 
<?php  include('../footer.php'); ?>






<script type="text/javascript">
$('.errBox').hide();
$('.successBox').hide();
$('.view-profile').click(function(){
//  var trial=<?php echo $trial;?>;
//  if(trial==1){
//
//    //$("#myModel").modal();
//    window.location.href='../dashboard/doctorsDashboard.php?payuStatus=freeTrial';
//  }else{
    window.location.href='viewProfile.php';
  //}
  });

$('.freeTrial').click(function(){
  var user_id=<?php echo $userInfo['user_id'];?>;
    $.ajax({type: "POST",
            url:"../../controllers/paymentController.php",
            data:{user_id:user_id,
                  type:'freeTrial'},
            dataType:'json',
            /*beforeSend: function () {
              $ele.find('.stopAccess').show();
            }*/
      })
      .done(function(data) {
        console.log(data);
        if(data['errCode']==-1){
          $("#successModel").modal();
        }else{
          $("#failModel").modal();
        }
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        //alert("error");
        console.log('error');
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       });

});
$('.addPatient').click(function(){
  window.location.href='../patient/manage_patient.php';  
});
function showgeneral_info(){
  $('.errBox').hide();
  $('.successBox').hide();
  $("#general-form").css("display","block");
  $("#contact-form").css("display","none");
  $(".generaltab").addClass( "active" );
  $(".coninfo").removeClass( "active" ); 

}


function showcontact_info(){
  $('.errBox').hide();
  $('.successBox').hide();
  $("#general-form").css("display","none");
  $("#contact-form").css("display","block");
  $(".generaltab").removeClass( "active" );
  $(".coninfo").addClass( "active" ); 
  window.scrollTo(1, 1);
  
}


  $(document).ready(function() {
    var text_max = 250;
    $('#textarea_feedback').html(text_max);

    $('#higest-degree').keyup(function() {
        var text_length = $('#higest-degree').val().length;
        var text_remaining = text_max - text_length;

        $('#textarea_feedback').html(text_remaining);
    });



    var text_max1 = 250;
    $('#addr_feedback').html(text_max1);

    $('#address').keyup(function() {
        var text_length1 = $('#address').val().length;
        var text_remaining1 = text_max1 - text_length1;

        $('#addr_feedback').html(text_remaining1);
    });
});


/*   $('.contact').click(function(event) {
    // Act on the event 
    var type="contact"
    var formdata=$('#contactForm').serialize();
    //console.log(formdata);
   // location.href="controllers/signUpController.php";
    ajaxCall('../../controllers/completeProfileController.php',formdata,$(this));
    
    event.preventDefault();
    
  });

  $('.general').click(function(event) {
    // Act on the event 
    var type="general"
    var formdata=$('#generalForm').serialize();
    //console.log(formdata);
   // location.href="controllers/signUpController.php";
    ajaxCall('../../controllers/completeProfileController.php',formdata,$(this));
    
    event.preventDefault();
    
  });*/


  $('form#generalForm').submit(function(event) {
    /* Act on the event */
    var type="general";
    //var formdata=$('#generalForm').serialize();
    var formdata = new FormData($(this)[0]);
      //var formData = new FormData($(this)[0]);
    //console.log(formdata);
   // location.href="controllers/signUpController.php";
    ajaxCall('../../controllers/completeProfileController.php',formdata,$(this));

    event.preventDefault();

     
  });

  $('form#contactForm').submit(function(event) {
    /* Act on the event */
    var type="contact";
    //var formdata=$('#contactForm').serialize();
    var formdata = new FormData($(this)[0]);
    //console.log(formdata);
   // location.href="controllers/signUpController.php";
    ajaxCall('../../controllers/completeProfileController.php',formdata,$(this));

    event.preventDefault();
//$( "#contactForm" ).scrollTop( 0 );
  });

  /*ajax definition starts*/
    function ajaxCall(url,formdata,$ele){
    $.ajax({type: "POST",
            url:url,
            data:formdata,
            dataType:'json',
            cache: false, 
            contentType: false,                
            processData: false,
            beforeSend: function () {
              $ele.find('.stopAccess').show();
            }
      })
      .done(function(data) {
      
        if(data['errCode']==-1){
          $('.successMsg').html(data['errMsg']);
          $('.successBox').show();
          $('.errBox').hide();
         /* window.scrollTo(1, 1);
           setTimeout(function(){
            location.reload(true);
          },3000);*/
          // alert(data['errMsg']);
          
        }
        else if(data['errCode']==6){
          $('.errMsg').html(data['errMsg']);
          $('.successBox').hide();
          $('.errBox').show();
           /*setTimeout(function(){
            location.reload(true);
          },4000);*/
          //alert(data['errMsg']);   
           window.scrollTo(1, 1);
        }else{
          $('.successBox').hide();
          $('.errMsg').html(data['errMsg']);
          $('.errBox').show();
           /*setTimeout(function(){
            location.reload(true);
          },4000);*/
           //alert(data['errMsg']);
            window.scrollTo(1, 1);
        }        
      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        alert("error");
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       })  
       $( "body" ).scrollTop(0);
  }
  /*ajax definition ends*/

    $('#user_country').change(function(){
        //var cId = $('#user_country > option:selected').val();
         var cId = $('#user_country > option:selected').attr("data-id");
       //alert(cId);

          $.ajax({  
            url:"../../controllers/completeProfileController.php",  
            method:"POST",  
            data:{type:'getState' ,
            cId:cId

          },  
          dataType:"json",  
          success:function(data){ 
           // alert(data);
            var dt=data['errMsg']['errMsg'];

             $('.user_state').html("<option>Select State</option>");
             var i=0;
             for(i=0;i<dt.length;i++){
                $('.user_state').append('<option class="" data-id = "'+dt[i]['id']+'" value="'+dt[i]['name']+'">'+dt[i]['name']+'</option>');
              }
            } 

          }); 
        });

        $('#user_state').change(function(){
          var sId = $('#user_state > option:selected').data("id");
          $.ajax({  
            url:"../../controllers/completeProfileController.php",  
            method:"POST",  
            data:{type:'getCity' ,
            sId:sId

          },  
          dataType:"json",  
          success:function(data){ 
            
            var dt=data['errMsg']['errMsg'];
             $('.user_city').html("<option>Select City</option>");
             var i=0;
             for(i=0;i<dt.length;i++){
                $('.user_city').append('<option class=""  value="'+dt[i]['name']+'">'+dt[i]['name']+'</option>');
             }
                
            } 

          }); 
        });


       $('#fc').click(function () {
    $('#select-img').click();
});



$(document).ready(function() {
    $('#datePicker').datepicker({
            format: 'mm/dd/yyyy',
            maxDate: 0,
            changeMonth: true,
            changeYear: true,
            yearRange: '1930:2030'
        })
        .on('changeDate', function(e) {
            $('#eventForm').formValidation('revalidateField', 'date');
        });

    $('#eventForm').formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The name is required'
                    }
                }
            },
            date: {
                validators: {
                    notEmpty: {
                        message: 'The date is required'
                    },
                    date: {
                        format: 'MM/DD/YYYY',
                        message: 'The date is not a valid'
                    }
                }
            }
        }
    });
});


   function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#user-pic').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#select-img").change(function(){
        readURL(this);
    });

</script>

<script type="text/javascript">
    function showcalender(){
      $("#datePicker").focus();             
    }
</script>
</body>
</html>
