<?php

session_start();

require_once("../utilities/config.php");
require_once("../utilities/dbutils.php");

  //database connection handling

$blogURL ="ehblog/index.php";



$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
  $conn = $conn["errMsg"];
} else {
      //printArr("Database Error");
  exit;
}


$pathPrefix="../";


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once("metaInclude.php"); ?>
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
    h4{
          width: 100%;
    }
    .tablet
    {
      height:100px;
      width: 100px;
    }
    #tour a{
      color: #22d09b !important;
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
    /*header{
      padding:7px 20px !important;
    }*/
  </style>
  <link rel="stylesheet" type="text/css" href="../assets/css/home.css?ahsfd=r44244">
 <!--   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.min.css"> -->
  <!-- header-->


  <main class="container" style="min-height: 100%;">
    <?php  include_once("header.php"); ?>     
    <section>
      <div class="main-container">

        <div class="col-md-12 bannerdiv">
          <div class="drop">
              <!-- <select class="wht-bother" id="abc">
                <option selected disable value="">What bothers you ?</option>
                <option>122</option>
                <option>dgfhgjhg</option>
              </select> -->
              <input type="text" name="suggetionDest" class="wht-bother" id="abc" placeholder="What bothers you ?"> 
              <input type="button" value="GO"  class="drop-search-btn" id="diseaseBox" >
               
          </div>
          <div  id="errMsg" style="position: relative;top: 76%;left: 4%;width: 80%;color:red;font-size: 17px;">
          </div>

          <!-- <div class="drop">
          <input type="text" name="suggestion-box" class="suggestion-box" value="">
          <img src='../../assets/images/ajax-loader.gif' class="img-responsive ajax-loader" />
          </div> -->
        </div>





            <div class="row offering">

       <h1>What we offer</h1>
<!-- 1st block -->
                <div class="col-md-4 col-xs-12">
                  <div class="col-md-3 col-xs-3">
                  <img src="../assets/images/recommend.png" class="img-responsive">
                  </div>
                  <div class="right col-md-9 col-xs-9 no-left-padd">
                      <div class="title">
                      <h2>Medicine recommendation</h2>
                      </div>

                      <div class="desc">
                       <h3>Let our expert system recommend you the right</h3>
                      </div>

                      <div class="go-button-div">
                      <input type="button" class="btn-go" value="Go" id="getMedicine" data-toggle="modal" data-target="#myModal" aria-hidden="true">w
                    </div>
                  </div>
                </div>

<!-- 2nd block -->
                <div class="col-md-4 col-xs-12">
                  <div class="col-md-3 col-xs-3">
                  <img src="../assets/images/opinion.png" class="img-responsive">
                  </div>
                  <div class="right col-md-9 col-xs-9 no-left-padd">
                      <div class="title">
                      <h2>Second<br>opinion</h2>
                      </div>

                      <div class="desc">
                       <h3>Get an expert 2nd opinion from Dr. Khedekar for FREE</h3>
                      </div>

                      <div class="go-button-div">
                      <input type="button" class="btn-go" value="Go" id="2nd_Opinion" data-toggle="modal" data-target="#myModal" aria-hidden="true">

                    </div>

                  </div>

                </div>



<!-- 3rd block -->
                 <div class="col-md-4 col-xs-12">
                  <div class="col-md-3 col-xs-3">
                  <img src="../assets/images/consult.png" class="img-responsive">
                  </div>
                  <div class="right col-md-9 col-xs-9 no-left-padd">
                      <div class="title">
                      <h2>Consult<br>doctor</h2>
                      </div>

                      <div class="desc">
                       <h3>Consult our experts for your condition where reasearch has been already done</h3>
                      </div>

                      <div class="go-button-div">
                      <input type="button" class="btn-go" value="Go" id="consult_doctor" data-toggle="modal" data-target="#myModal" aria-hidden="true">

                    </div>

                  </div>

                </div>          

            </div>
            <!-- OFFERING END -->


            <!-- what can find here -->

            <div class="row findhere">

            <h1>What you can find here</h1>


              <div class="col-md-6 ">
              <div class="main-block">

                <img src="../assets/images/forstudents.png" class="img-responsive" />
                <div class="find-text">
                
                <h4>
                Get the best videos, case papers and PPTs. Add to our knowledgebase as long as the content you provide meets our standards. Subscribe to be up to date with the latest info in homeopathy.
                </h4>
                </div>
                </div>
                </div>


                <div class="col-md-6 ">
                <div class="main-block">
                <img src="../assets/images/fordoctors.png" class="img-responsive" id="forDoctors" data-toggle="modal" data-target="#myModal" aria-hidden="true" />
                <div class="find-text">
                <h4>
                eH wil help you make the best prescription. A very intelligent tool that will keep all your cases in one place. eH will help you cure your patients in the most scientific way and according to Hahnemannian way. 
                </h4>
                </div>
                </div>
                </div>

            </div>
     

            <!-- what can find here end-->



           



        

        <div class="row knowlege-center">
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
              <img class="img-responsive" src="../assets/images/banner2.png" alt="New York"/>
            </div>
            <div class="item "> 
              <img class="img-responsive" src="../assets/images/slider233.jpg" alt="New York"/>
            </div>
            <div class="item"> 
              <img class="img-responsive" src="../assets/images/slider222.jpg" alt="New York"/>
            </div>
            <div class="item"> 
              <img class="img-responsive" src="../assets/images/slider211.jpg" alt="New York"/>
            </div>
            <div class="item"> 
              <img class="img-responsive" src="../assets/images/Imperial.jpg" alt="New York"/>
            </div>


          </div>

          <a class="left carousel-control" href="#secondslider" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="right carousel-control" href="#secondslider" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>  

        </div>
        <!-- slider2-->

                 <div class="knowlege-center">
                  <h6>You may contribute and let others learn from your experiences as well. Whatever small or big with your ideas, videos (highly recommended), case papers or PPTs. Publishing subject to approval from the supervising panel of doctors of our team. This is as good as sharing your heard earned research with the world (no ads). We promise you the deserved exposure.</h6>
                 </div>


                
      


        <div class="row featured">
          
          <h1>Featured videos</h1>
          <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
            <div class="videos">
                <div class="image">
                <img src="../assets/images/video1.png" class="img-responsive">
                </div>
                
                <div class="video-info">
                <h4>Homopathy helps fight cancer</h4>
                <h5>Tue, 26 Dec, 2016</h5>
                <div class="row WordPressData" style="">
                   <div class="col-md-6 col-sm-6 col-xs-6">
                  <a href=#>WATCH</a>
                  </div>
                   <div class="col-md-6 col-sm-6 col-xs-6">
                  <a href="#">SHARE</a>
                  </div>
                </div>
                </div>
            </div>
          </div>



               <!-- <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                <div class="videos">
                    <div class="image">
                    <img src="../assets/images/video2.png" class="img-responsive">
                    </div>
                    
                    <div class="video-info">
                    <h4>Homopathy helps fight cancer</h4>
                    <h5>Tue, 26 Dec, 2016</h5>
                    <div class="row WordPressData" style="">
                       <div class="col-md-6 col-sm-6 col-xs-6">
                      <a href=#>WATCH</a>
                      </div>
                       <div class="col-md-6 col-sm-6 col-xs-6">
                      <a href="#">SHARE</a>
                      </div>
                    </div>
                    </div>
                </div>
              </div>



               <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                <div class="videos">
                    <div class="image">
                    <img src="../assets/images/video3.png" class="img-responsive">
                    </div>
                    
                    <div class="video-info">
                    <h4>Homopathy helps fight cancer</h4>
                    <h5>Tue, 26 Dec, 2016</h5>
                    <div class="row WordPressData" style="">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                      <a href=#>WATCH</a>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                      <a href="#">SHARE</a>
                      </div>
                    </div>
                    </div>
                </div>
              </div> -->


        </div>



        <div  id="tour" class="bg-1" style="margin-top: 50px;">
          
        </div>
        <!-- knowledge center-->


                <!-- Consult doctor -->
                <div class="row meet-experts">
                <h1>Meet our expert doctor</h1>

                <div class="col-md-6 left-meet">
                <h4>
                Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you. You may consult them online or at their own clinics. And you can be assured that they use the best technology and knowhow in Healthcare that exists to cure you. There are not many because experts are few. 
                </h4>

                <input type="button" name="" id="consult" class="consult-btn" value="Consult">
                </div>

                <div class="col-md-6 right-meet">
                <img src="../assets/images/doctor.png" class="img-responsive" />
                </div>
                </div>
                <!-- end Consult doctor -->


         
                <div class="row location-map">
                  <h1>Global reach</h1>
                  <h4>Tell us your location and we will find the nearest Homeopathic pharmacy or service provider for you.</h4>
                  <div class="col-md-12">
                    <div id="map" style="z-index: 1;"></div>
                    
                      <div class="search-div">
                      
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="" class="search-input form-control" placeholder="Search location">
                      </div>

                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="col-md-7 col-sm-7 col-xs-7">
                          <div class="range-slider">
                          <label class="rangelable" id="ex6CurrentSliderValLabel">Radius :&nbsp;<span id="ex6SliderVal" style="color:#000;">0</span><span style="color:#000;">km</span></label>
                          
<input id="ex1" data-slider-id='ex1Slider' type="text" data-slider-min="0" data-slider-max="20" data-slider-step="1" data-slider-value="0"/>
              
              </div>
              </div>
              <div class="col-md-5 col-sm-5 col-xs-5">
                             <input type="button" value="Search" class="search-btn">
              </div>  
            </div>
                      </div>

                  </div>

                </div>
  
        
        

      


                 <!-- subscription -->
                <div class="row meet-experts">
                <h1>Democratising homeopathy</h1>

                <div class="col-md-6 left-meet">
                <h4>
                Homeopathic remedies are derived from substances that come from plants, minerals, or animals. All of this is completely natural, and we believe it will have the positive impact on human health. Subsribe to our newsletter to receive news on homeopathy.
                </h4>

                <input type="button" name="" class="consult-btn" value="Subscribe">
                </div>

                <div class="col-md-6 right-meet">
                <img src="../assets/images/homeopathy.png" class="img-responsive" />
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
                    <img src="../../assets/images/fb.png" class="img-responsive" />
                    <img src="../../assets/images/google.png" class="img-responsive" />
                    <img src="../../assets/images/twit.png" class="img-responsive" />
                    </div>
                  </div>
                     <div style="clear: both;"></div>
                  <h3 style="color: #fff;text-align: center;">All rights reserved by eheilung <img src="../../assets/images/secured.png"></h3>          
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
          <button type="button" class="close" data-dismiss="modal"><img style="width: 70%;" src="../assets/images/close.png"></button>
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
  <?php include('footer.php'); ?>
  
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDoRMxiPsqJ9SUuaK1KsCAjd3gqnecjlBw&libraries=places"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/bootstrap-slider.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <!-- footer-->

<script type="text/javascript">

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

 $('#errMsg').hide();
$('.wht-bother').on('keyup', function(){
$('#errMsg').hide();
 var q=$(this).val();
 $.ajax({
        type: "POST",
        url:"<?php echo '../controllers'; ?>/homeController.php",
        data:{data:q},
        dataType:'JSON',
    beforeSend: function(){
        $('.wht-bother').addClass('ajax-loader');
    },
    complete: function(){
        $('.wht-bother').removeClass('ajax-loader');
    },
    success: function(data){
     // console.log(data['main_complaint_name']);

       $(".wht-bother").autocomplete({
      source: data
    });

    }
});

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
                url:"<?php echo '../controllers'; ?>/homeController.php",
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

                  /*style="height: 100px; overflow-y: scroll;"*/
                $('.modal-body').html('<div><h3 class="modal-head">What is it?<h3><div style="overflow-y: auto;"><p class="modal-data">'+data['Definition']+'</p></div></div><div ><h3 class="modal-head">Expert opinion<h3><div style="height: 300px;overflow-y: auto;"><p class="modal-data"><img class="img-responsive" src="../assets/images/searchModalImg.png" align="right">'+data['Expert_Comments']+'</p></div></div>');
                $(".modal-footer").html('<div class="col-md-4"><a href="" style="'+getMedicine+'" class="modal-link" value="Get medicine">Get medicine</a></div><div class="col-md-4"><a href="" class="modal-link" value="Second opinion">Second opinion</a></div><div class="col-md-4"><a href="http://imperialclinics.com/" target="_blank" style="'+consultDoctor+'" class="modal-link" value="Consult doctor">Consult doctor</a></div>');
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
            //$.extends(mapLoader.config,option);
            if (Modernizr.geolocation){
                navigator.geolocation.getCurrentPosition(mapLoader.loadMap, mapLoader.errorFunction);
            } else {
                alert('It seems like Geolocation, which is required for this page, is not enabled in your browser. Please use a browser which supports it.');
            }
          },
          loadMap:function(pos){
            var lat=pos.coords.latitude;
            var lon = pos.coords.longitude; 
            latlng = new google.maps.LatLng(lat,lon);
            mapLoader.config.map = map;
            marker.setPosition(latlng);
            map.setCenter(latlng);


            mapLoader.config.infowindow = new google.maps.InfoWindow();
            var myPlace={lat: pos.coords.latitude, lng: pos.coords.longitude};
            mapLoader.config.service = new google.maps.places.PlacesService(mapLoader.config.map);
            mapLoader.config.service.nearbySearch({
                      location : myPlace,
                      radius : 5500,
                      keyword: 'homeopathy pharmacy near'
                    }, mapLoader.onSuccess);
          },
          onSuccess:function(results, status) {
                  if (status === google.maps.places.PlacesServiceStatus.OK) {
                      results.forEach(mapLoader.createMarker);               
                  }
                  
              },
              createMarker:function(place) {
                  var placeLoc = place.geometry.location;
                  var marker = new google.maps.Marker({
                      map : mapLoader.config.map,
                      position : place.geometry.location,
                      
                  });
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
        scrollwheel:false,
        draggable:false,
        mapTypeId:google.maps.MapTypeId.ROADMAP
      };

      map = new google.maps.Map(document.getElementById("map"),mapProp);

      marker = new google.maps.Marker({
        position:myCenter,
      });
      marker.setMap(map);
    }
    google.maps.event.addDomListener(window, 'load', initialize); 

      
    $(document).ready(function () {

      $('.select').scrollTop(0);

      wordPressLoad();


      $('#consult').click(function(){
        location.href="contactDoctors.php";
      });

      $('#getMedicine').click(function(){

        $('.modal-title').html("Get Medicine");
         $('.modal-body').html("1. Get the Right remedy for you and your condition.<br> 2. Has your doctor told you what you should be taking or are already taking? <br>3.Is your doctor prescribing you placebo?<br>4. Know the right price of your correct treatment, is your doctor charging you more than he should.<br>5. Are you being cheated?<br>6. Keep a tab on your doctor<br>7. We can also deliver the medicine at your doorstep if feasible.<br>");

         $(".modal-footer").html('<div class="col-md-2"><a href="views/sign_in.php" class="modal-link" value="Login">Login</a></div><div class="col-md-2" style="width: 34%;"><a href="views/sign_in.php"  class="modal-link" value="Sign up">Sign up</a></div>');
      });







      $('#2nd_Opinion').click(function(){

          $('.modal-title').html("2nd Opinion");
            $('.modal-body').html("1. 2nd Opinion is a brain child of the master: Dr. Khedekar He has been using this in his international practice world over abd is highly respected for it worldwide.<br> 2. His 2nd Opinion is based on several advanced scientific factors like Human Embryology, Medical Genetics, environmental genetics, Pathophysiology, Pathology, knowlede of drug dynamics, proteomics, deep understanding of body's basic physiology and the ancient homistic concepts of Ayurveda.<br>3. 2nd Opinion offered will always be a very honest feedback. We in no way wish to harm your relation with your existing doctors or physician or surgeon. We only wish to give you the right power of knowledge about your condition.<br>4. You may be taking any line of treatment from any pathy viz: Allopathy, Homeopathy, Ayurveda, Quantum medicine, Unani, Siddha, Physiotherapy, Telepathy, Magnetic therapy, etc but the physics of the human body remains the same and this is where we see if the chosen line of treatment is corect or not!<br>5. If the treatement is not suiting or helping you, you may choose to take a remedy from our expert and intelligent software or consult one of our expert doctors.<br>8. Irrespective of the treatment you are currently taking. We are scientific, we are neutral and we are Honest! If your line of treatment is correct, we will say so. See the results yourself.");
             
            $(".modal-footer").html('<div class="col-md-2"><a href="views/sign_in.php" class="modal-link" value="Login">Login</a></div><div class="col-md-2" style="width: 34%;"><a href="views/sign_in.php"  class="modal-link" value="Sign up">Sign up</a></div>');
        });
      $('#consult_doctor').click(function(){

        $('.modal-title').html("Counsult Doctor");
        $('.modal-body').html("1.The mentioned doctor are hand picked by Dr. Khedekar himself who he thinks can represent him without bringing the quality and his reputation down.<br> 2. The doctors recommended also use the eHeilung platform to prescribe <br>3. Individual results may vary. <br>4. Please take a look at our quality policy. <br>5. Please recommend to us Doctors that you think deserve to be on this elite list.<br>6.Our software doesnt handle very difficult pathologies yet (the ones not mentioned in our list). ");
         
        $(".modal-footer").html('<div class="col-md-2 col-sm-3 col-xs-3"><a href="views/sign_in.php" class="modal-link" value="Login">Login</a></div><div class="col-md-2 col-sm-3 col-xs-3" style="width: 45%;"><a href="views/sign_in.php"  class="modal-link" value="Sign up">Sign up</a></div>');
        });

      
        $('#forDoctors').click(function(){

              $('.modal-title').html("For Doctors");
               $('.modal-body').html('<div><h3 class="modal-head">Benefits<h3><div style="" class="modal-list"><ul class="modal-data"><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.</span></li><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.</span></li><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.</span></li><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.</span></li></ul></div></div><div><h3 class="modal-head">We offer<h3><div style="" class="modal-list"><ul class="modal-data"><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.</span></li><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.</span></li><li style=""><span>Please meet our list of elite panel doctors. These are highly recommended the world over and we have chosen them to serve you.Please meet our list of elite panel doctors.</span></li></ul></div></div><div><h3 class="modal-head">Annual fee<h3><div style=""><p class="price" style="font-size: 25px;padding: 10px 0px 10px 0px;">&#x20b9;1,000</p><input type="button" class="btn-buy" value="Buy" ></div></div>');
               $(".modal-footer").html('<div class="col-md-2 col-sm-6 col-xs-6" style="width:40%"><a href="views/sign_in.php" class="modal-link" value="Login">Login</a></div><div class="col-md-2 col-sm-6 col-xs-6" style="width: 60%;"><a href="views/sign_in.php"  class="modal-link" value="Sign up">Sign up</a></div>');

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
slider.on("slide", function(sliderValue) {
  document.getElementById("ex6SliderVal").textContent = sliderValue;
});
</script>
</body>
</html>
