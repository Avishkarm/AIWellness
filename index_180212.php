<?php

    session_start();

require_once("utilities/config.php");
require_once("utilities/dbutils.php");
require_once("utilities/authentication.php");
	//database connection handling

$blogurl="https://eheilung.com/ehblog";

$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
} else {
	    //printArr("Database Error");
	exit;
}

function httpGet($url)
{
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//  curl_setopt($ch,CURLOPT_HEADER, false); 
 
    $output=curl_exec($ch);
 
    curl_close($ch);

    //print_r($output);
    return $output;
}

/*curl_errno($ch);
$error_message = curl_strerror($errno);
echo "cURL error ({$errno}):\n {$error_message}";*/
/*echo "hii";*/
$categoryPost = httpGet($blogurl."/?json=get_category_posts&slug=featured-video");
$categoryPost = json_decode($categoryPost, TRUE);
/*echo "<pre>";
print_r($categoryPost);
echo "</pre>";
*/

/*if(isset($_COOKIE["eheilung_username"])){
  echo "checked";
}else{
  echo "notChecked";
}*/
$activeHeader = "index.php";
$pathPrefix="../";


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once("views/metaInclude.php"); ?>  
	<style type="text/css">
    div.knowledgeCont{
      justify-content: space-between;
      align-items: stretch;
    }
    .knowledgeCont > div{
      display: flex;
    }
    #tour .thumbnail{
      border: 1px solid #ddd;
      border-radius: 9px;
      padding: 10px;
    }
     h2
     {
       font-size: 21px;
     }
     /*h4{
       width: 100%;
     }*/
     .tablet
     {
       height:100px;
       width: 100px;
     }
     #tour a{
       color: #22d09b !important;
     }
     a:hover{
      color:#ffb600;
      outline: 0;
     }
     .carousel-caption
     {
       text-shadow: none;
     }
     #map{
       width: 100%;
       min-height: 200px;
     }
     #map1{
       width: 100%;
       min-height: 200px;
     }
     .googlemap{
       position: relative;
       display: flex;
       flex-wrap: wrap;
       flex-direction: row;
       align-items: stretch;
       align-content: stretch;
       justify-content: flex-start;
     }
     .googlemap > div{
       padding: 5px;
       align-self: auto;
       flex: 1 0 50%;
     }
     @media screen and (max-width: 420px){
       .googlemap > div{
        flex: 1 0 100%;
      }
    }
    @media screen and (max-width: 700px){
     div.knowledgeCont > div{
      flex-basis: 100%;
      display: block;
    }
    #tour .thumbnail > img{
      width: 100%;
    }
    }
    .slider-handle{
      background-color: #ffb600 !important;
      background-image: none !important;
    }

    #ex1Slider .slider-selection {
      background: #ffb600;

    }
   ::-webkit-input-placeholder { /* Chrome/Opera/Safari */
  color: #ffffff;
  }
  ::-moz-placeholder { /* Firefox 19+ */
    color: #ffffff;
  }
  :-ms-input-placeholder { /* IE 10+ */
    color: #ffffff;
  }
  :-moz-placeholder { /* Firefox 18- */
    color: #ffffff;
  }

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
.go-button-div a{
  background-color: #0dae04;
    border-radius: 7px;
    color: #fff;
    text-align: center;
    padding: 7px 30px 7px 30px;
    outline: none;
    border: none;
    /* width: 40%; */
    font-size: 23px;
    margin-top: 10px;
    margin-bottom: 20px;
}



.top35{
  margin-top: 35px;
}
		/*header{
			padding:7px 20px !important;
		}*/
	</style>
	<link rel="stylesheet" type="text/css" href="assets/css/home.css?c=2">
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.min.css">
	<!-- header-->

	<main class="container" style="min-height: 100%;">
		<?php  include_once("views/header.php"); ?>     
		<section>
			<div class="main-container">
<!-- <h3>Enter your problem here and find the most natural, safe and effective cure right now.</h3> -->

        <div class="col-md-12 bannerdiv">
          <div class="home-banner-supertitle">
            <h1>Find the Homeopathic Cure</h1>
          </div>
          <div class="home-banner-title">
            <h2>Enter the 'Chronic problem' here and find the most natural, safe and effective cure right now.</h2>
          </div>


          <div class="drop fake-input">       
              <input type="text" name="suggetionDest" style="color:#ffffff;font-size: 16px;" class="wht-bother diseaseBox" id="abc" placeholder="Enter disease diagnosis here"> 
              <img src="assets/images/drop.png">
              <!-- <h3>Please be precise about your ailment. Confirm with your Doctor if possible.</h3> -->
              <input type="button" value="GO"  class="drop-search-btn diseaseBox" id="diseaseBox" >
          </div>

            <div  id="errMsg" style="">
            </div>
<!--            <div class="home-banner-tagline">
            <h6>Confirm with your family doctor if possible.</h6>
            </div>-->
        </div>    


      <div class="row offering top35" >
       <h1>What we do</h1>
       <!-- 1st block -->
       <div class="col-md-4 col-xs-12">
        <div class="col-md-3 col-xs-3">
          <img src="assets/images/recommend.png" class="img-responsive">
        </div>
        <div class="right col-md-9 col-xs-9 no-left-padd">
          <div class="title">
            <h2>Medicine recommendation</h2>
          </div>


         <div class="desc">
           <h3>Let our Expert System recommend the right medicine for you.</h3>
         </div>
        
        </div>
       </div>

    <!-- 2nd block -->
    <div class="col-md-4 col-xs-12">
      <div class="col-md-3 col-xs-3">
        <img src="assets/images/opinion.png" class="img-responsive">
      </div>
      <div class="right col-md-9 col-xs-9 no-left-padd">
        <div class="title">
          <h2>Case Checker</h2>
        </div>

        <div class="desc">
         <h3>Get a FREE check from a highly intelligent and futuristic medical software programme, developed by Dr. Khedekar.</h3>
        </div>
      </div>
    </div>



  <!-- 3rd block -->
  <div class="col-md-4 col-xs-12">
    <div class="col-md-3 col-xs-3">
      <img src="assets/images/consult.png" class="img-responsive">
    </div>
    <div class="right col-md-9 col-xs-9 no-left-padd">
      <div class="title">
        <h2>Connect with Leading Doctors</h2>
      </div>

      <div class="desc">
       <h3>Consult our experts for conditions where advanced research has been already done.</h3>
      </div>
    
    </div>
  </div>  
      </div>
<!-- OFFERING END -->


<!-- what can find here -->

<div class="row findhere top35">
  <h1>What you can find here</h1>
  
  <div class="col-md-6 ">
  <div class="main-block">
    <img style="cursor: pointer;" src="assets/images/fordoctors.png" class="img-responsive" id="forDoctors" data-toggle="modal" data-target="#myModal" aria-hidden="true" />
    <div class="find-text">
        <h2>
            Boost your success rate.
      </h2>
      <h4>
        eH is an advanced programme will help you make the best prescription. A highly intelligent tool, that will keep all your cases in one place. eH will help you cure your patients in the most scientific way, based on Hahnemannian principles. It has been tested thoroughly and is already being used by leading Doctors and Practitioners.
      </h4>
    </div>
  </div>
</div>
  
  <div class="col-md-6 ">
   <div class="main-block">
    <img style="cursor: pointer;" src="assets/images/forstudents.png" class="img-responsive" id="forStudents" data-toggle="modal" data-target="#myModal" aria-hidden="true" />
    <div class="find-text"> 
      <h2>
        Strengthen your knowledge and increase your chances of success.
      </h2>
      <h4>
        Access our extensive knowledge base. Get the best videos, case papers, presentations and latest news. Subscribe and be up to date with the latest in homeopathy.
      </h4>
    </div>
   </div>
  </div>



</div>


<!-- what can find here end-->

<div class="row knowlege-center top35">
 <h1>Knowledge center</h1>
</div>

<!-- slider2 -->
<div class=" carousel " id="secondslider"  data-ride="carousel">
					<!-- <ol class="carousel-indicators">
						<li data-target="#secondslidersecondslidersecondslider" data-slide-to="0" class="active"></li>
						<li data-target="#secondslidersecondslider" data-slide-to="1"></li>
						<li data-target="#secondslider" data-slide-to="2"></li>
					</ol> -->
					<div class="carousel-inner" id="slide2" role="listbox" >
            <div class="item active"> 
             <img class="img-responsive" src="assets/images/banner2.png" alt="New York"/>
           </div>           
          </div>
<!-- 
        <a class="left carousel-control" href="#secondslider" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#secondslider" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>	 -->
</div>
      <!-- slider2-->

      <div class="knowlege-center">
        <!-- <h6>Contribute and share your experiences with the world, however small or big your ideas. Send us videos (highly recommended), case papers and research. We promise you the deserved exposure. Publishing is subject to approval from the supervising panel of doctors of our team.</h6> -->
        <h6>Contribute and share your experiences with the world, however small or big your ideas. Send us videos (highly recommended), case papers and research. We promise you the deserved exposure.  Publishing is subject to approval from the supervising panel of doctors of our team.</h6>
      </div>


      <?php if(!empty($categoryPost['posts'])){?>
  <div class="row featured top35">
        <h1>Featured videos</h1>
        <?php 

        foreach ($categoryPost['posts'] as $key => $value) {
        if($key<=2){
        ?>
        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
          <div class="videos">
            <div class="image">
             <!--  <img src="assets/images/video1.png" class="img-responsive"> -->
             <?php

              foreach ($value['attachments'] as $key2 => $value2) {
                  $mime_type=substr($value2['mime_type'], 0,5);
                  if($mime_type=='video'){ 
                      if (array_key_exists('thumbnail',$value)) {?>
                        <video style="width: 100%;" poster="<?php echo $value['thumbnail'] ;?>" controls src="<?php echo $value2['url'] ;?>" ></video>
                      
                      <?php }
                      else{
                      ?> 
                         <video style="width: 100%;"  controls src="<?php echo $value2['url'] ;?>" ></video>
                      <?php }   break;
                 }
                }
              ?> 
            </div>

            <div class="video-info">
              <h4><?php echo $value['title']; ?></h4>
              <h5><?php echo date("D, j F, Y", strtotime($value['date']));?></h5>
              <div class="row WordPressData" style="">
               <div class="col-md-6 col-sm-5 col-xs-6">
                <a style="cursor: pointer;" href="views/KnowledgeCenter/post.php?id=<?php echo $value['id'];?>">WATCH</a>
              </div>
              <div class="col-md-6 col-sm-5 col-xs-6">
                <!-- <a href="">SHARE</a> -->
              <div class=" social-icon dropdown">
                <a style="cursor: pointer;" class="dropdown-toggle" type="" data-toggle="dropdown">SHARE</a>
                <ul class="dropdown-menu">
                  <li><a href="http://www.facebook.com/sharer.php?u=<?php echo $rootUrl; ?>/views/KnowledgeCenter/post.php?id=<?php echo $value['id'];?>" title="Share on Facebook."  onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank"><i class="fa fa-facebook"></i></a></li>
                  <li><a href="https://twitter.com/share?url=<?php echo $rootUrl; ?>/views/KnowledgeCenter/post.php?id=<?php echo $value['id'];?>" onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"  target="_blank"><i class="fa fa-twitter"></i></a></li>
                  <li><a href="https://plus.google.com/share?url=<?php echo $rootUrl; ?>/views/KnowledgeCenter/post.php?id=<?php echo $value['id'];?>" onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                  <li><a href="http://pinterest.com/pin/create/button/?url=<?php echo $rootUrl; ?>/views/KnowledgeCenter/post.php?id=<?php echo $value['id'];?>" onclick="javascript:window.open(this.href,
                              '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank"><i class="fa fa-pinterest-p"></i></a></li>
                </ul>
              </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php }} ?>
  </div>
  <?php } ?>


  <!-- <div class="share" id="share">
                        <div class="dropdown">
  <button class="dropbtn"><img src="../../img/share-icon.jpg"></button>
  <div class="dropdown-content">
  <a href="">Share</a>
    <a href="http://www.facebook.com/sharer.php?url=<?php echo $baseUrl;  ?>views/home/home.php" target="_blank"><i class="fa fa-facebook-official" style="color: #3b5998;font-size: 20px;"></i></a>
    <a href="https://twitter.com/share?url=<?php echo $baseUrl;  ?>views/home/home.php" target="_blank"><i class="fa fa-twitter" style="color:#1da1f2;font-size: 20px;"></i></a>
    <a href="https://plus.google.com/share?url=<?php echo $baseUrl;  ?>views/home/home.php" target="_blank"><i class="fa fa-google-plus" style="color:#dd4b39;font-size: 20px;"></i></a>
  </div>
  <div style="clear: both;"></div>
</div>
</div> -->



  <div  id="tour" class="bg-1" style="margin-top: 50px;">

  </div>
  <!-- knowledge center-->


  <!-- Consult doctor -->
  <div class="row meet-experts top35">
    <h1>Meet our Expert Team</h1>
    <div class="col-md-6 left-meet">
        <h2>
        Expertise for future experts.
      </h2>
      <h4>
        Meet our elite panel of doctors. They are highly acclaimed the world over and are handpicked to provide the best solutions. Consult them online or at their own clinics. And you can be assured that they use the best technology and know how to devise a treatment. 
      </h4>

      <input type="button" name="" id="consult" class="consult-btn" value="Explore">
    </div>
    <div class="col-md-6 right-meet">
      <img src="assets/images/consultDoctors2.png" class="img-responsive" />
    </div>
  </div>
  <!-- end Consult doctor -->


  <div class="row location-map top35">
    <h1>Global reach</h1>
    <h4>Tell us your location and we will find the nearest Homeopathic pharmacy or service provider for you.</h4>
    <div class="col-md-12">
     <div id="map" style="z-index: 1;"></div>
      <div class="search-div">
        <div class="col-md-6 col-sm-6 col-xs-12">
          <input type="text" name="" class="search-input form-control" onkeyup="showSearchLocationSuggestions(this)" id="places" placeholder="Search location">

           <p id="locationError" style="color:red;margin-left: 10px;margin-top: 10px;display: none;">Please enter location.<p>
          <div id="searchLocationSuggestionBox">
            <ul>
              <li><a href="javascript:;" onclick="setCurrentLocation(1)">Search for homeopathy pharmacies near <span id="enteredSearchLocation"></span></li>
              <li><a href="javascript:;" onclick="setCurrentLocation(2)">Use my current location</a></li>
            </ul>
          </div>
        </div>
        <style>
        #searchLocationSuggestionBox{
          display: none;
          border: 1px solid #ccc;
          margin-left: 10px;
          width: 100%;
          border-radius: 4px;
          position: absolute;
          background-color: white;
          width: 94%;
          z-index: 2;
        }
        #searchLocationSuggestionBox ul{
          list-style: none;
          padding: 0px;
        }
        #searchLocationSuggestionBox ul li{
          border-bottom: 1px solid #ccc;
          padding: 10px;
        }
        #searchLocationSuggestionBox ul li a{
          color: #808080; 
        }
        #searchLocationSuggestionBox ul li a: hover{
          color: #808080; 
        }
        </style>
        <script>
        function setCurrentLocation(type){
          if(type==1){
            
            var location=$('#places').val();
            //alert(location);
            if(location==""){
                 //alert('hii');
                 $('#locationError').show();
            }else{
              $('#locationError').hide();
            mapLoader.searchBox();
            }
            $("#searchLocationSuggestionBox").hide();
          }
          else if(type==2){
             if (Modernizr.geolocation){
                navigator.geolocation.getCurrentPosition(mapLoader.loadMap, mapLoader.errorFunction);
              } else {
                alert('It seems like Geolocation, which is required for this page, is not enabled in your browser. Please use a browser which supports it.');
              }
            $("#searchLocationSuggestionBox").hide();
          }
        }
        $('#places').keypress(function(e){
            if(e.which == 13){//Enter key pressed
                setCurrentLocation(1);//Trigger search button click event
            }
        });
        $('.searchLocation').keypress(function(e){
            if(e.which == 13){//Enter key pressed
                setCurrentLocation(1);//Trigger search button click event
            }
        });
        
        function showSearchLocationSuggestions(element){
          $('#locationError').hide();
          $("#searchLocationSuggestionBox").show();
          $("#enteredSearchLocation").html($(element).val());
        }
        </script>

        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="col-md-7 col-sm-7 col-xs-7">
            <div class="range-slider">
              <label class="rangelable" id="ex6CurrentSliderValLabel">Radius :&nbsp;<span id="ex6SliderVal" style="color:#000;">0</span><span style="color:#000;">km</span></label>
              <input id="ex1" data-slider-id='ex1Slider' type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="0"/> 
            </div>
          </div>
          <div class="col-md-5 col-sm-5 col-xs-5">
           <input type="button" value="Search" class="search-btn searchLocation">
          </div>  
        </div>
     </div>
   </div>
 </div>

 <!-- subscription -->
 <div class="row meet-experts top35">
  <h1>Join the revolution in democratising homeopathy</h1>
  <div class="col-md-6 left-meet">
    <h2 style="margin-top:0px;">
      Be part of the future of medicine.
    </h2>
    <h4>
      Homeopathy is holistic and each person is treated individually, especially in our methodology. Your body, mind, spirit and emotions, along with diet, genetic disposition and social conditioning are all considered in the management and prevention of disease. Taking all these factors into account our Doctors will prescribe the perfect medicines for your symptoms and personal level of health to stimulate your own healing ability. Homeopathic medicines are natural, gentle, effective and inexpensive
    </h4>
    <input type="button" name="" class="subscribe-btn" id="subscribe-btn" value="Subscribe">
  </div>
  <div class="col-md-6 right-meet">
    <img src="assets/images/homeopathy.png" class="img-responsive" />
  </div>
 </div>
<!-- end subscription -->


<!-- Footer -->
               <!--  <div class="row footer">
                 	
                 	<div class="col-md-2">
                 	 	<div class="title">
  							<h1>About us</h1>                	 	
                 	 	</div>
                 	 	<div class="text">
                 	 		<h2>We are supportes of alternative medicine - homeopathy, and are doing our best to help people.</h2>
                 	 		<a href="#">E:eheilung@gmail.com</a>

                 	 	</div>
                 	</div> 

                 	<div class="col-md-2 col-md-push-3">
                 		<div class="title">
  							<h1>Our Services</h1>                	 	
                 	 	</div>
                 	 	<div class="text">
                 	 	<a href="#">Disease Compass</a>
                 	 	<a href="#">2nd Opinion</a>
                 	 	<a href="#">Stress Calculator</a>
                 	 	<a href="#">Knowledge Center</a>
                 	 	<a href="#">Doctors Area</a>
                 	 	<a href="#">Patients Area</a>
                 	 	</div>
                 	</div> 

                 	<div class="col-md-2 col-md-push-6">
                 		<div class="title">
  							<h1>Follow us</h1>                	 	
                 	 	</div>
                 	 	<div class="text">
                 	 	<img src="../assets/images/fb.png" class="img-responsive" />
                 	 	<img src="../assets/images/google.png" class="img-responsive" />
                 	 	<img src="../assets/images/twit.png" class="img-responsive" />
                 	 	</div>
                 	</div>
                     <div style="clear: both;"></div>
                 	<h3 style="color: #fff;text-align: center;">All rights reserved by eheilung <img src="../assets/images/secured.png"></h3>          
                </div>
                <!-- Footer End --> 


              </div>
              <!-- main-container-->
            </section>
            <!--Modal start-->
            <div class="modal fade" id="myModal" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                 <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 70%;" src="assets/images/close.png"></button>
                 </div>
                  <div class="modal-body"> 
                </div>
                <div class="modal-footer" style="bottom: 30px">
                </div>  
                </div>
              </div>
            </div>


  <!--  <div class="modal1 fade" id="myModal1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title"></h3>
        <div class="modal-body">
          <div>
            <h3>What we offer<h3>
            <p>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you. You may consult them online or at their own clinics. And you can be assured that they use the best technology and knowhow in Healthcare that exists to cure you. There are not many because experts are few.</p>
          </div>  
        </div>         
      </div>
    </div>
  </div> -->
  <!--Modal End-->

</main> 
<?php include("views/modals.php"); ?>
<?php include('views/footer.php'); ?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDoRMxiPsqJ9SUuaK1KsCAjd3gqnecjlBw&libraries=places"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/bootstrap-slider.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- footer-->

<script type="text/javascript">

/*var status=<?php echo $status;?>
if(status=='forgotPass')
{
  $('#resetpass').modal();
}*/

$('.subscribe-btn').click(function(){
  window.location.href='views/subscriber.php';
});
$('#subscribe-btn').keypress(function(e){
    if(e.which == 13){//Enter key pressed
        $('.subscribe-btn').click();//Trigger search button click event
    }
});

var rangeSlider = function(){
  var slider = $('.range-slider'),
  range = $('.range-slider__range'),
  value = $('.range-slider__value');

  slider.each(function(){

    value.each(function(){
      var value = $(this).prev().attr('value');
      $(this).html(value);
    });

    range.on('input', function(){
      $(this).next(value).html(this.value);
    });
  });
};

rangeSlider();
</script>


<script type="text/javascript">
$(window).load(function(){
  var flag=0;
  var status='<?php echo $_GET['status'];?>';
  
  var email='<?php echo $_GET['email'];?>';
  var user_type='<?php echo $_GET['user_type'];?>';
  var luser="<?php echo $_GET['luser'];?>";
  var user="<?php echo $_GET['user']; ?>";
  //alert($user);
 /* if(status=='login'){
    //alert(status);
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    $('#loginmodal #user_type').val(user_type);

    $('#loginmodal').modal();
  }*/
  if(user=="exist"){
    $("#failed-soacial-signup #user_type").val(user_type);
     $("#failed-soacial-signup").modal();
  }else if(user=="failed"){
    $("#failed-signup #user_type").val(user_type);
     $("#failed-signup").modal();
  }
  if(status=='forgotPass'){   
    flag=1;
  }else if(status=='verifysuccess'){
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    $('#signup-verified #user_type').val(user_type);
    $("#signup-verified").modal();
  }else if(status=='verifyfailed'){
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    var resendMsg="<a style='color:#0dae04' href='controllers/resendVerificationController.php?user_type="+user_type+"&email="+email+"' >Resend activation link</a>";
    $("#signup-not-verified .resendbtn").html(resendMsg);    
    $("#signup-not-verified").modal();
  }else if(status=='alreadyVerified'){
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    var resendMsg="<a style='color:#0dae04' onclick='gotologin()'>Login</a>";
    $('#signup-not-verified #user_type').val(user_type);
    $("#signup-not-verified .resendMsg").html("Your account is already active.Please login to continue"); 
    $("#signup-not-verified .resendbtn").html(resendMsg);    
    $("#signup-not-verified").modal();
  }else if(status=='verifymailsuccess'){
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    $('#link-sent .msg').html("Please check your email and verify your account");
    $("#link-sent").modal();
  }else if(status=='verifymailfailed'){
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    $("#link-sent-failed .msg").html("Something went wrong! Failed to send activation mail");
    $("#link-sent-failed .resendMsg").html("<a style='color:#0dae04' href='controllers/resendVerificationController.php' >Resend activation link</a>");
    $("#link-sent-failed").modal();
  }else if(status=='login'){
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    $('#loginmodal #user_type').val(user_type);
    $('#loginmodal').modal();
  }else if(status=='successSubscribe'){
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    $('#successsubscribemodal').modal();
  }else if(status=='failureSubscribe'){
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    $('#failuresubscribemodal').modal();
  }else if(status=='existSubscribe'){
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    $('#existsubscribemodal').modal();
  }
  if(flag==1){
    flag=0;
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    $('#resetpass .username').val(email);
    $('#resetpass #user_type').val(user_type);
    $("#resetpass").modal();
  }
  if(luser=='patient'){
    document.getElementById("user_type").value = 3;
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    //$('#signmodal #user_type').val('3');
    $("#pat_btn").css("display","none");
    $("#pat_btnactive").css("display","block");
    $("#doc_btn").css("display","block");
    $("#doc_btnactive").css("display","none");
    $('#signmodal').modal();
  }
  /*if(luser=='doctor'){
    document.getElementById("user_type").value = 2;
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    //$('#signmodal #user_type').val('3');
    $("#doc_btn").css("display","none");
    $("#doc_btnactive").css("display","block");
    $("#pat_btn").css("display","block");
    $("#pat_btnactive").css("display","none");
    $('#signmodal').modal();
  }*/
  if(luser=='doctor'){
    document.getElementById("user_type").value = 2;
    var newUrl = refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
    //$('#signmodal #user_type').val('3');
    $("#doc_btn").css("display","none");
    $("#doc_btnactive").css("display","block");
    $("#pat_btn").css("display","block");
    $("#pat_btnactive").css("display","none");
    $('#loginmodal').modal();
  }
});



$('#errMsg').hide();
$('.wht-bother').on('input', function(){
  $('#errMsg').hide();
  var q=$(this).val();
  $.ajax({
    type: "POST",
    url:"<?php echo 'controllers'; ?>/homeController.php",
    data:{data:q},
    dataType:'JSON',
    beforeSend: function(){
        $('.wht-bother').addClass('ajax-loader');
        $('.fake-input img').hide();
    },
    complete: function(){
        $('.wht-bother').removeClass('ajax-loader');
         $('.fake-input img').show();
    },
    success: function(data){
     // console.log(data['main_complaint_name']);

     $(".wht-bother").autocomplete({
      source: data
    });

      /*$(".wht-bother").autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.drop.autocomplete.filter(
            data, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, drop ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( drop.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          return false;
        }
      });*/


   },
   error: function(){
        /*$('.wht-bother').removeClass('ajax-loader');
         $('.fake-input img').show();*/
         $('#errMsg').show();
    },
 });

});
$('.diseaseBox').keypress(function(e){
    if(e.which == 13){//Enter key pressed
        $('.diseaseBox').click();//Trigger search button click event
    }
});
$('.diseaseBox').keypress(function(e){
    if(e.which == 13){//Enter key pressed
        $('#diseaseBox').click();//Trigger search button click event
    }
});
$('#diseaseBox').click(function(){
  var diseaseName=$('.wht-bother').val();

  if(diseaseName=="")
  {
    $('#errMsg').html("Please enter your complaint first.");
    $('#errMsg').show();
    return false;
  }
  else
  {
    $('#errMsg').hide();
    $.ajax({
      type: "POST",
      url:"<?php echo 'controllers'; ?>/homeController.php",
      data:{diseaseName:diseaseName},
      dataType:'JSON',
      beforeSend: function(){
        $('.wht-bother').addClass('ajax-loader');
      },
      complete: function(){
        $('.wht-bother').removeClass('ajax-loader');
      },
      success: function(data){
        console.log(data);
        if(data!=""){
          $('#errMsg').hide();

          if(data['CTA']!="Get Remedy"){
            var getMedicine="display:none"
          }
          if(data['CTA']!="Consult a doctor")
          {
            var consultDoctor="display:none";
          }
          if(data['Improvement_rate']=="")
          {
           var Improvement_rate="display:none;";
         }
         if(data['Expert_Comments']=="")
         {
          var Expert_Comments="display:none;";
        }
        if(data['Duration']=="")
        {
          var Duration="display:none;";
        }
        if(data['Definition']=="")
        {
          var Definition="display:none;";
        }

        /*style="height: 100px; overflow-y: scroll;"*/
        $('.modal-body').html('<div style="'+Definition+'"><h3 class="modal-head">What is it?<h3><div style=""><p class="modal-data">'+data['Definition']+'</p></div></div><div style="'+Expert_Comments+'"><h3 class="modal-head">Expert opinion<h3><div style=""><p class="modal-data"><img class="img-responsive" src="assets/images/searchModalImg.png" align="right">'+data['Expert_Comments']+'</p></div></div><div style="'+Duration+'"><h3 class="modal-head">Duration<h3><div style=""><p class="modal-data">'+data['Duration']+'</p></div></div><div style="'+Improvement_rate+'"><h3 class="modal-head">Improvement rate<h3><div style=""><p class="modal-data">'+data['Improvement_rate']+'</p></div></div>');
        $(".modal-footer").html('<div class="col-md-6"><a href="views/2opinion/" class="modal-link" value="Case Checker">Case Checker</a></div><div class="col-md-6"><a href="http://imperialclinics.com/" target="_blank" style="'+consultDoctor+'" class="modal-link" value="Consult doctor">Consult doctor</a></div>');
        $("#myModal").modal();
      }
      else{
        $('#errMsg').html("please select valid name");
        $('#errMsg').show();
        return false;
      }

    }
  });
}

});


 //$('.suggestion-box').show();


 $('#getMedicine').click(function(){

   var url1="get_medicine.php";
   localStorage.setItem('url1', url1);
 });

 $('#2nd_Opinion').click(function(){

   var url1="2opinion/";
   localStorage.setItem('url1', url1);
 });
 $('#consult_doctor').click(function(){

   var url1="contactDoctors.php";
   localStorage.setItem('url1', url1);
 });



    var gmarkers = [];
    var myCenter = new google.maps.LatLng(52.5200, 13.4050);
    var map;
    var marker;
    var mapLoader={
      config:{
        'map':{},
        'infowindow':{},
        'service':{},
        'zoom':12,
        'container':'map'
      },
      initMap:function(option){
        console.log('hiii');
                //$.extends(mapLoader.config,option);
               
              },
              loadMap:function(pos){
                var lat=pos.coords.latitude;
                var lon = pos.coords.longitude; 
                var radius= document.getElementById("ex1").value;
                console.log(radius);
                if(radius==0)
                {
                  radius=1;
                }
                latlng = new google.maps.LatLng(lat,lon);
                mapLoader.config.map = map;
                marker.setPosition(latlng);
                map.setCenter(latlng);

                mapLoader.config.infowindow = new google.maps.InfoWindow();
                var myPlace={lat: pos.coords.latitude, lng: pos.coords.longitude};
                mapLoader.config.service = new google.maps.places.PlacesService(mapLoader.config.map);
                mapLoader.config.service.nearbySearch({
                  location : myPlace,
                  radius : radius*1000,
                  keyword: 'homeopathy pharmacy near'
                }, mapLoader.onSuccess);
              },
              searchBox: function(){
                // Create the search box and link it to the UI element.
                var input = document.getElementById('places').value;
                geocoder = new google.maps.Geocoder();
                if (geocoder) {
                  geocoder.geocode( { 'address': input}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                      if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
                  //map.setCenter(results[0].geometry.location);
                  var pos={};
                  pos['coords']={};
                  pos['coords'].latitude=results[0].geometry.location.lat();
                  pos['coords'].longitude=results[0].geometry.location.lng();
                  mapLoader.initMap();
                  mapLoader.loadMap(pos);
                } else {
                  alert("No results found");
                }
              } else {
                alert("Geocode was not successful for the following reason: " + status);
              }
            });
                }
              },
              onSuccess:function(results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                 mapLoader.removeMarkers();
                 results.forEach(mapLoader.createMarker);               
               }

             },
             removeMarkers:function(){
              for(i=0; i<gmarkers.length; i++){
                gmarkers[i].setMap(null);
              }
            },
            createMarker:function(place) {
              var placeLoc = place.geometry.location;
              var marker = new google.maps.Marker({
                map : mapLoader.config.map,
                position : place.geometry.location,

              });

                    // Push your newly created marker into the array:
                    gmarkers.push(marker);
                    google.maps.event.addListener(marker, 'click', function() {

                      var request = {
                        reference: place.reference
                      };
                      mapLoader.config.service.getDetails(request, function(details, status) {

                        mapLoader.config.infowindow.setContent([
                          details.name,
                          details.formatted_address,
                          details.website,
                          details.rating,
                          details.formatted_phone_number].join("<br />"));
                        mapLoader.config.infowindow.open(mapLoader.config.map, marker);
                      });


                    });
                  },
                  errorFunction:function(pos) {
                    alert('It seems like your browser or phone has blocked our access to viewing your location. Please enable this before trying again.');
                  }
                };



                function initialize() {
                  var mapProp = {
                    center:myCenter,
                    zoom:12,
                    mapTypeId:google.maps.MapTypeId.ROADMAP
                  };

                  map = new google.maps.Map(document.getElementById("map"),mapProp);

                  marker = new google.maps.Marker({
                    position:myCenter,
                  });
                  marker.setMap(map);
                }
                google.maps.event.addDomListener(window, 'load', initialize); 
                $(window).load(function(){
                  $(".searchLocation").on('click',function(){

                    $("#searchLocationSuggestionBox").hide();
                    var location=$('#places').val();
                    //alert(location);
                    if(location==""){
                         $('#locationError').show();
                    }else{
                          $('#locationError').hide();
                      //alert(radius);
                        mapLoader.searchBox();
                    }

                 });
                });

                $(document).ready(function () {

                 $('.select').scrollTop(0);

                 wordPressLoad();

                 $('#consult').click(function(){
                  location.href="views/contactDoctors.php";
                });


                 $('#getMedicine').click(function(){

                  $('.modal-title').html("Get Medicine");
                  $('.modal-body').html("Let our Expert System recommend the best medicine for you.<br> 1. Get an effective remedy for your condition.<br> 2. Check if your current medicines are right for you.<br> 3. Know what the cost of your treatment should be and if you are paying too much.<br> 4. Make sure your doctor is being fair with you.<br> 5. We can deliver medicine at your doorstep when possible.");

                  $(".modal-footer").html('<div class="col-md-2 col-sm-6 col-xs-6" style="width:40%"><a href="views/sign_in.php" class="modal-link" value="Login">Login</a></div><div class="col-md-2 col-sm-6 col-xs-6" style="width: 60%;"><a href="views/sign_in.php"  class="modal-link" value="Sign up">Sign up</a></div>');
                });


                 $('#2nd_Opinion').click(function(){
                   $('.modal-title').html("2nd Opinion");
                   $('.modal-body').html("Get a FREE second opinion from eHeilung, a highly intelligent and futuristic medical software, developed for advanced predictive medicine.<br> 1. 2nd Opinion is the brainchild of Dr. Khedekar, one of the most respected and renowned Homeopaths in the world today.<br> 2. Our 2nd Opinion is based on several advanced scientific factors like Human Embryology, Medical Genetics, Environmental Genetics, Pathophysiology, Pathology, knowledge of drug dynamics, Proteomics, deep understanding of body's basic physiology and the ancient holistic concepts of Homeopathy and Ayurveda.<br> 3. The 2nd Opinion is transparent and honest. The aim is to find you the best solution to your problem and empower you with knowledge. This may or may not be in line with what your existing line of treatment. <br> 4. This software functions like artificial intelligence and is developed and run by a team of leading Doctors, Researchers and Engineers.<br> 5. You may be taking any form of treatment: Allopathy, Homeopathy, Ayurveda, Quantum medicine, Unani, Siddha, Physiotherapy, Telepathy, Magnetic therapy, etc but the physics of the human body remains the same and this is where we see if the chosen line of treatment is correct or not.<br> 6. Irrespective of the treatment you are undergoing, we will be completely transparent and honest. We are scientific, we are neutral. If your line of treatment is correct, we will say so. See the results yourself. ");

                   $(".modal-footer").html('<div class="col-md-2 col-sm-6 col-xs-6" style="width:40%"><a href="views/sign_in.php" class="modal-link" value="Login">Login</a></div><div class="col-md-2 col-sm-6 col-xs-6" style="width: 60%;"><a href="views/sign_in.php"  class="modal-link" value="Sign up">Sign up</a></div>');
                 });


$('#consult_doctor').click(function(){
  $('.modal-title').html("Counsult Doctor");
  $('.modal-body').html("Consult leading experts and specialists who can help  <br> 1. The team of expert doctors are carefully chosen and trained. <br> 2. The Doctors use the eHeilung platform to accurately diagnose and prescribe. <br> 3. Please recommend to us Doctors that you think should be on this elite panel.");

  $(".modal-footer").html('<div class="col-md-2 col-sm-6 col-xs-6" style="width:40%"><a href="views/sign_in.php" class="modal-link" value="Login">Login</a></div><div class="col-md-2 col-sm-6 col-xs-6" style="width: 60%;"><a href="views/sign_in.php"  class="modal-link" value="Sign up">Sign up</a></div>');
});

/*
$('#forDoctors').click(function(){

  $('.modal-title').html("For Doctors");
  $('.modal-body').html('<div><h3 class="modal-head">Benefits<h3><div style="" class="modal-list"><ul class="modal-data"><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.</span></li><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.</span></li><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.</span></li><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.</span></li></ul></div></div><div><h3 class="modal-head">We offer<h3><div style="" class="modal-list"><ul class="modal-data"><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.</span></li><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.   </span></li><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.</span></li></ul></div></div>');
  $(".modal-footer").html('<div class="col-md-2 col-sm-6 col-xs-6" style="width:40%"><a href="<?php echo $rootUrl; ?>/index.php?luser=doctor" class="modal-link" value="Login">Login</a></div><div class="col-md-2 col-sm-6 col-xs-6" style="width: 60%;"><a href="<?php echo $rootUrl; ?>/index.php?luser=doctor"  class="modal-link" value="Sign up">Sign up</a></div>');

});*/

$('#forStudents').click(function(){

  $('.modal-title').html("For Doctors");
  $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>eHeilung is derived from the word Heilung which means Healing in German. eHeilung is ‘expert Healing’ or ‘electronic Healing’</span></li></ul></div><div><h3 class="modal-head">What we offer:</h3><div style="" class="modal-list"><ul class="modal-data"><li style=""><span>The most advanced software to solve your cases Homeopathically.</span></li></ul></div><div><div><h3 class="modal-head">What you can find here:</h3><div style="" class="modal-list"><ul class="modal-data"><li style=""><span>Take the 30 day challenge. Use eHeilung to solve any Chronic Psoric condition and get results in 30 days. Who said Homeopathy is slow?</span></li></ul></div></div><div><div><h3 class="modal-head">Benefits<h3><div style="" class="modal-list"><ul class="modal-data"><li style="" ><span>eH can be accessed from any personal computer, laptop, tablet or mobile phone on android as well as iOS.</span></li><li style="" ><span>Doesn’t need installation and hence doesn’t take up memory space on your HD.</span></li><li style="" ><span>Saves your patient info confidentially and in one place that can be accessed from anywhere with your login id.</span></li><li style="" ><span>Analyses your follow-ups according to Hering’s Law of cure and Kents 12 observations.</span></li><li style="" ><span>Makes you prescribe like the masters in Classical Homeopathy.</span></li><li style="" ><span>Finds the most probable cause of disease.</span></li><li style="" ><span>Needs minimum internet speed and is fast.</span></li><li style="" ><span>Tells you the miasmatic background behind every pathology of your patient.</span></li><li style="" ><span>Is extremely simple to use, thanks to the great user interface and experience.</span></li><li style="" ><span>Gives remedy differentiation at the end of every case with clinical tips to ‘Rule in’ or ‘Rule out’ remedies.</span></li><li style="" ><span>In each case, tells you whether the patient is being ‘Palliated’ or ‘Suppressed’ or ‘Cured’.</span></li><li style="" ><span>Will soon refer patients who need your help to your registered clinic.</span></li><li style="" ><span>Makes Case taking extremely simple and accurate.</span></li><li style="" ><span>Saves time by half and increases productivity by double.</span></li><li style="" ><span>Sends follow-up reminders to you and your patient. So no need of a secretary.</span></li><li style="" ><span>Does personality profiling of every patient (mentals), so that you can arrive at the Constitutional Similimum every time.</span></li><li style="" ><span>Tells you the chances of Cure in every case.</span></li><li style="" ><span>We are continuously improving eH every day so that you stay updated with the latest technological advancements in Homeopathy.</span></li><li style="" ><span>In our ‘Recommended Doctors’ we promote you online, so no need to pay extra to listing sites.</span></li><li style="" ><span>The knowledge area can post your research articles and papers to other homeopaths.</span></li><li style="" ><span>Brings out the scientist in you by analyzing all your cases graphically.</span></li></ul></div></div>');
  $(".modal-footer").html('<div class="col-md-2 col-sm-6 col-xs-6" style="width:40%"><input type="button" class="modal-link" value="Login" data-dismiss="modal" onclick="gotologin()"/></div><div class="col-md-2 col-sm-6 col-xs-6" style="width: 60%;"><input type="button" onclick="gotosignup()" class="modal-link" data-dismiss="modal" value="Sign up"/></div>');
  //$(".modal-footer").html('<div class="col-md-2 col-sm-6 col-xs-6" style="width:40%"><a href="<?php echo $rootUrl; ?>/index.php?luser=doctor" class="modal-link" value="Login">Login</a></div><div class="col-md-2 col-sm-6 col-xs-6" style="width: 60%;"><a href="<?php echo $rootUrl; ?>/index.php?luser=doctor"  class="modal-link" value="Sign up">Sign up</a></div>');

});

$('#forDoctors').click(function(){

  $('.modal-title').html("For Doctors");
  $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>eHeilung is derived from the word Heilung which means Healing in German. eHeilung is ‘expert Healing’ or ‘electronic Healing’</span></li></ul></div><div><h3 class="modal-head">What we offer:</h3><div style="" class="modal-list"><ul class="modal-data"><li style=""><span>The most advanced software to solve your cases Homeopathically.</span></li></ul></div><div><div><h3 class="modal-head">What you can find here:</h3><div style="" class="modal-list"><ul class="modal-data"><li style=""><span>Take the 30 day challenge. Use eHeilung to solve any Chronic Psoric condition and get results in 30 days. Who said Homeopathy is slow?</span></li></ul></div></div><div><div><h3 class="modal-head">Benefits<h3><div style="" class="modal-list"><ul class="modal-data"><li style="" ><span>eH can be accessed from any personal computer, laptop, tablet or mobile phone on android as well as iOS.</span></li><li style="" ><span>Doesn’t need installation and hence doesn’t take up memory space on your HD.</span></li><li style="" ><span>Saves your patient info confidentially and in one place that can be accessed from anywhere with your login id.</span></li><li style="" ><span>Analyses your follow-ups according to Hering’s Law of cure and Kents 12 observations.</span></li><li style="" ><span>Makes you prescribe like the masters in Classical Homeopathy.</span></li><li style="" ><span>Finds the most probable cause of disease.</span></li><li style="" ><span>Needs minimum internet speed and is fast.</span></li><li style="" ><span>Tells you the miasmatic background behind every pathology of your patient.</span></li><li style="" ><span>Is extremely simple to use, thanks to the great user interface and experience.</span></li><li style="" ><span>Gives remedy differentiation at the end of every case with clinical tips to ‘Rule in’ or ‘Rule out’ remedies.</span></li><li style="" ><span>In each case, tells you whether the patient is being ‘Palliated’ or ‘Suppressed’ or ‘Cured’.</span></li><li style="" ><span>Will soon refer patients who need your help to your registered clinic.</span></li><li style="" ><span>Makes Case taking extremely simple and accurate.</span></li><li style="" ><span>Saves time by half and increases productivity by double.</span></li><li style="" ><span>Sends follow-up reminders to you and your patient. So no need of a secretary.</span></li><li style="" ><span>Does personality profiling of every patient (mentals), so that you can arrive at the Constitutional Similimum every time.</span></li><li style="" ><span>Tells you the chances of Cure in every case.</span></li><li style="" ><span>We are continuously improving eH every day so that you stay updated with the latest technological advancements in Homeopathy.</span></li><li style="" ><span>In our ‘Recommended Doctors’ we promote you online, so no need to pay extra to listing sites.</span></li><li style="" ><span>The knowledge area can post your research articles and papers to other homeopaths.</span></li><li style="" ><span>Brings out the scientist in you by analyzing all your cases graphically.</span></li></ul></div></div>');
  $(".modal-footer").html('<div class="col-md-2 col-sm-6 col-xs-6" style="width:40%"><input type="button" class="modal-link" value="Login" data-dismiss="modal" onclick="gotologin()"/></div><div class="col-md-2 col-sm-6 col-xs-6" style="width: 60%;"><input type="button" onclick="gotosignup()" class="modal-link" data-dismiss="modal" value="Sign up"/></div>');
  //$(".modal-footer").html('<div class="col-md-2 col-sm-6 col-xs-6" style="width:40%"><a href="<?php echo $rootUrl; ?>/index.php?luser=doctor" class="modal-link" value="Login">Login</a></div><div class="col-md-2 col-sm-6 col-xs-6" style="width: 60%;"><a href="<?php echo $rootUrl; ?>/index.php?luser=doctor"  class="modal-link" value="Sign up">Sign up</a></div>');

});

$('#4').click(function(){

  $('.modal-title').html("Global Reach");
  $('.modal-body').html("1. Let us find a homeopathy pharmacy or supplier for you. <br> 2. At the right price  <br>3. Fastest delivery <br>4. Best quality medicines");

});

$('.location').click(function(){
  mapLoader.initMap();

});


$(".navbar-toggle").on("click", function () {
  $(this).toggleClass("active");
});

			/*$("#btn").click(function () {
				var selectedText = $("select").find("option:selected").text();
				var selectedValue = $("select").val();
				$('#defShow').html(selectedValue);
			});*/
/*
			$('.def').click(function(){
				var selectedText = $("select").find("option:selected").text();
				var selectedValue = $("select").val();
						
		        $('.modal-title').html("Homeopathy Can Cure");
		        $('.modal-body').html(selectedText+" :: "+selectedValue);


		    });

			$("#diseaseBox").change(function(){
					var selectedText = $("select").find("option:selected").text();
				var selectedValue = $("select").val();
				$('#defShow').html(selectedValue);
			});
			
});*/	
$('.def').click(function(){
  var selectedText = $("select").find("option:selected").text();
  var selectedValue = $("select").val();

  $('.modal-title').html("Homeopathy Can Cure");
  $('.modal-body').html(selectedText+" :: "+selectedValue);
  var link = `<a  href="get_medicine.php" id="mLogin" class="btn btn-revert all ">
  Get Medicine
  </a>
  <a  href="2opinion/" class="btn btn-revert all">
  2nd Opinion
  </a>
  <a  href="contactDoctors.php"  class="btn btn-revert all">
  Consult Doctor
  </a>`;
  $('.modal-footer').html(link);

  $("#mLogin").click(function(){

   var url1="get_medicine.php";
   localStorage.setItem('url1', url1);
 });

});

			/*$("#diseaseBox").change(function(){
					var selectedText = $("select").find("option:selected").text();
				var selectedValue = $("select").val();
				$('#defShow').html(selectedValue);
			});*/

});	




  /*
    To Load WordPress Page
    */
    function wordPressLoad(){
    	
    	var formData={};
    	formData['url']="<?php echo $blogURL; ?>?json=1";
    	formData['category']=0;
    	formData['categoryTitle']='allCondition';
    	ajaxLoad(formData,updateUI);

    }

  /*
    ajax Load
    */
    function ajaxLoad(formData,callback){
    	$.ajax({
    		url: formData['url'],
    		dataType: 'jsonp',
    		beforeSend: function( xhr ) {
        //xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
      }
    })
    	.done(function( data ) {
    		
    		callback(data,formData);
    	})
    	.fail(function() {
    		//alert( "error" );
    	});
    }

  /*
  Update UI 
  */
  function updateUI(data,formData){
  	
  	
  	if (data['posts'] instanceof Array){
      //Sort the Post in descending Order
      data['posts'].sort(function(a,b){
      	var c = new Date(a.date);
      	var d = new Date(b.date);
      	return d-c;
      });
    }
    obj[formData['categoryTitle']](data,formData);
  }

  function pretifyDate(dateData){
   dateObject = new Date(Date.parse(dateData));

   dateReadable = dateObject.toDateString();
   return dateReadable;
 }
  /*
    Category Object Function
    */
    var obj={
    	allCondition:function(data,formData){
    		obj.globalFunc(formData['categoryTitle']);
    		console.log(data);
    		var $tour=$('#tour');
    		var str='<div class="knowledgeCont flexContainer"><h4 class="text-center">Knowledge Center</h4>';
    		str+='<p class="text-center" style="margin-bottom:30px">“Knowledge is Power” at whatever age or situation that you may be in. You may contribute and let others learn from your experiences as well. Whatever small or big with your ideas, videos (highly recommended), case papers or PPTs. Publishing subject to approval from the supervising panel of doctors of our team. This is as good as sharing your heard earned research with the world (no ads). We promise you the deserved exposure.</p>';
    		var set={'Upcoming Webinar':0,
    		'Feature Specialist':0,
    		'Featured Papers':0,
    		'Featured Video':0,
    		'Featured Presentation':0
    	}
    	var dt=data['posts'];
    	for(var i=0;i<dt.length;i++){

    		str+='<div class="">\
       <div class="thumbnail">\
       <img class="img-responsive" src="'+data['posts'][i]['thumbnail']+'" alt="'+data['posts'][i]['categories'][0]['title']+'">\
       <div class="slide text-center">Category: '+data['posts'][i]['categories'][0]['title']+'</div>\
       <div class="slide text-center"><a href="'+views+'/knowledge_center1.php?data_url='+encodeURIComponent(data['posts'][i]['url'])+'" target="_blank">Title: '+data['posts'][i]['title']+'</a></div>\
       <div class="slide1 text-center">\
       <p>'+pretifyDate(data['posts'][i]['date'])+'</p>\
       </div>\
       </div>\
       </div>';
     }
     str+="</div>";
     $tour.append(str);
   },
   category:function(data,formData){
     obj.globalFunc(formData['categoryTitle']);
     $("#category").empty();
     obj.globalFunc(formData['categoryTitle']);
     console.log(data);
     for(var i in data['posts']){

      var htm=[
      '<div class="col-sm-12 animate">',
      '<div class="well">',
      '<div class="flex-container layt" >',

      '<div class="col-sm-7">',
      '<h5 class="text-warning title">',  
      '<a data-url="'+data['posts'][i]['url']+'" href="javascript:;" class="anch">'+data['posts'][i]['title']+'</a>',
      '</h5>',
      '<p class="content">'+data['posts'][i]['content']+'</p>',
      '</div>',
      '<div class=" col-sm-5 col-xs-10" style="">',
      '<div class="well" style="height: 166px;"></div>',
      '</div>',
      '</div>',
      '</div>',
      '</div>'].join('');
      $("#category").append(htm);
    } 

  },
  article:function(data,formData){
   obj.globalFunc(formData['categoryTitle']);
   var str="";
   for(var j in data['post']['categories']){
    str+=data['post']['categories'][j]['title'];
  }
  $("#artText").find('.webTitle').text(str);
  $("#artText").find('.title').text(data['post']['title']);
  $("#artText").find('.content').html(data['post']['content']);
},
globalFunc:function(id){
	$('#'+id).show(function(){
		$('#'+id).siblings('.act').fadeOut();
	});
	
}
};
var slider = new Slider("#ex1");
slider.on("change", function(sliderValue) {

  document.getElementById("ex6SliderVal").textContent = sliderValue.newValue;
});



/*slider.on("click", function(sliderValue) {
  document.getElementById("ex6SliderVal").textContent = sliderValue;
});*/
</script>
</body>
</html>
