<?php

session_start();

require_once("../utilities/config.php");
require_once("../models/commonModel.php");
require_once("../utilities/dbutils.php");
require_once("../models/contactDoctorsModel.php");

//database connection handling

$blogURL ="../ehblog/index.php";
$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();
if(noError($conn)){
  $conn = $conn["errMsg"];
} else {
      //printArr("Database Error");
  exit;
}

$getDoctors = getAllDoctorsContact($conn);
//printArr($getDoctors);

$pathPrefix="../";


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once("metaInclude.php"); ?>
  <style type="text/css">
  .bannerdiv{
  border-top:1px solid grey;  
  margin-bottom: 45px;
  margin-top: 30px;
  padding-top: 100px;
  padding-bottom: 35px;
} 

.wht-bother{
  background: #fff;
  height: 55px;
  width : 44%;
  font-family: Montserrat-Regular;
  color: #333;
  font-size: 20px;
  padding-left:20px;
  border: 1px solid grey;
  border-right: 0;
}
.drop-search-btn {
    background-color: #0dae04;
    color: #fff;
    text-align: center;
    padding: 0%;
    border: none;
    outline: none;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    width: 20%;
    /*font-size: 25px;*/
    font-size: 20px;
    font-weight: 500;
    font-family: Montserrat-Regular;   
}

@media(max-width: 360px){
 .bannerdiv{
  height: 150px;
  margin-bottom: 45px;
}

.wht-bother{
 
  height: 30px !important;
  width : 70%;
  font-size: 15px !important;
}
.drop-search-btn{
  width: 20% !important;
  font-size: 15px !important;
  padding: 5px !important;
}
}
@media(max-width: 786px){
 .preContent,.postContent{
  font-size: 36px!important;
  }
}
@media(max-width: 435px){
 .preContent,.postContent{
  font-size: 28px!important;
  }
}

@media(max-width: 320px){
 .bannerdiv{
  height: 150px;
  margin-bottom: 45px;
}

.wht-bother{
 
  height: 30px !important;
  width : 70%;
  font-size: 12px !important;
}
.drop-search-btn{
  width: 20% !important;
  font-size: 12px !important;
  padding: 5px !important;
}
}
.preContent{
  color:#ffb600;
  font-weight: 500;
  /*font-size:55px;*/
  font-size: 55px;
  letter-spacing: 2px;
  word-spacing: 3px;
}
.postContent{
  color:#454545;
  font-weight: 500;
  /*font-size:55px;*/
  font-size: 55px;
  letter-spacing: 2px;
  word-spacing: 3px;
}
.featuredDoctors .name
{
  font-size:20px;
  letter-spacing: 1px;
  color: #454545;
  white-space: nowrap;
  width: 8em;
  /*overflow: hidden;
  text-overflow: ellipsis;*/
}
.featuredDoctors .location
{
  font-size:20px;
  letter-spacing: 1px;
  color: #454545;
  white-space: nowrap;
  width: 8em;/*
  overflow: hidden;
  text-overflow: ellipsis;*/
}
.featuredDoctors .price
{
  font-size:16px;
  letter-spacing: 1px;
  color:#696868;
  margin-bottom: 12px;
}
.featuredDoctors p{

}
.featuredDoctors .head
{
    margin-top: 100px;
    /*font-size:50px !important;*/
    font-size: 4vh !important;
    font-weight: 500 !important;
    color:black;
    margin-bottom: 50px;
    letter-spacing: 1px;
}
.featuredDoctors .not-found
{
    font-size:30px;
    /*text-align:center;*/
    color:#ddd;
    opacity: 0.7;
}
.featuredDoctors .main-block{
  background-color: #fff;
  box-shadow: 0 0 15px #b9b9b9;
  margin-top: 10px;
  margin-bottom: 40px;
  /*max-height: 714px;*/
}

.featuredDoctors .doctorImg img{
  width: 100%;
  max-height: 500px !important;
  object-fit: cover;
  min-height: 500px;
 
}
.featuredDoctors .star img{
  display:inline;
  margin-right: 10px;
  width: 10%;
 
}
.featuredDoctors h4{
  color: #454545;
  font-family: Montserrat-Regular;
  font-size: 2vh;
  padding:10px 15px 10px 15px;
  line-height: 35px;
  text-align: left;
}
.featuredDoctors .find-text{
  min-height: 100px;
}
.searchBox{
      margin-top: 15px;
}


  </style>
  
  <main class="container" style="min-height: 100%;">
    <?php  include_once("header.php"); ?> 
    <section>
      <!-- main-container-->
      <div class="main-container">
        <div class=" row" >
            <div class="col-md-10 col-sm-10" style="margin-bottom: 45px;">

                <h1><span class="preContent">Our Doctors are here to help
                you!</span><span class="postContent"> Consult featured doctors 
                or search one in your area</span></h1>
            </div>     
        </div>
        <div class=" row searchBox" >
          <div class="col-md-12" style="display:flex;">
            <input class="wht-bother" type="text" id="doctorSearch" name="doctorSearch" placeholder="Doctor">
            <input class="wht-bother" type="text" id="locationSearch" name="locationSearch" placeholder="Location">
            <input type="button" value="Search" class="drop-search-btn">
          </div>
        </div>  

<!-- doctors details -->
        <div class="row featuredDoctors">
            <p class="head" style="padding-left: 15px;">Featured doctors</p>
            <div class="search-result">
            <?php foreach ($getDoctors['errMsg'] as $key => $value) {
              ?>
              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
              <div class="main-block">
              <div class="doctorImg">
                <img src="../assets/uploads/<?php echo $value['image'];?>" class="img-responsive " />
              </div>  
                <div class="find-text">
                <h4>
                  <p class="name"><?php echo $value["Name"];?></p>
                  <p class="price">&#x20b9;<?php echo $value["price"];?> per/h</p>
                  <p class="location"><?php echo $value["location"];?></p>
                  <div class="star">
                  <p><?php $totleRating=5;
                      for($i=0;$i<$value["rating"];$i++)
                      {?>
                        <span><img src="../assets/images/redStar.png" class="img-responsive " />
                    <?php  }
                      for($i=0;$i<$totleRating-$value["rating"];$i++)
                      {?>
                        <span><img src="../assets/images/whiteStar.png" class="img-responsive " />
                         <?php  }
                  ?></p>
                  </div>
                  </h4>
                </div>
              </div>
            </div>
             <?php
            } ?>
           </div>
        </div>
      </div>
      <!-- main-container-->
    </section>
  </main>
  <?php include("modals.php"); ?> 
  <?php include('footer.php'); ?>
  
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDoRMxiPsqJ9SUuaK1KsCAjd3gqnecjlBw&libraries=places"></script>
  <!-- footer-->

  <script type="text/javascript">
   
    
    $(document).ready(function () {

      $(".drop-search-btn").click(function(){
        var doctorName=$("#doctorSearch").val();
        var locationName=$("#locationSearch").val();
        //alert(doctorName+".."+locationName);
        $(".search-result").empty();

        $.ajax({
          type: "POST",
          url:"<?php echo '../controllers'; ?>/contactDoctorsController.php",
          data:{doctorName:doctorName,locationName:locationName},
          dataType:'JSON',
          success: function(data){
            //console.log(data['errMsg'][0]);
            if(data['totleDoctors']!=0){
             // console.log(data['errMsg']);
              var totleRating=5;
              var i=0,j=0;             
              for(i=0;i<data['totleDoctors'];i++){
                //$(".star").empty();
                 
                 $(".search-result").append('<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><div class="main-block"><div class="doctorImg"><img src="../assets/uploads/'+data["errMsg"][i]["image"]+'" class="img-responsive " /></div><div class="find-text"><h4><p class="name">'+data["errMsg"][i]["Name"]+'</p><p class="price">&#x20b9;'+data["errMsg"][i]["price"]+' per/h</p><p class="location">'+data["errMsg"][i]["location"]+'</p><div class="star" id="star_'+i+'"></div></h4></div></div></div>');

                 for(j=0;j<data["errMsg"][i]["rating"];j++){

                   $("#star_"+i).append('<span><img src="../assets/images/redStar.png" class="img-responsive "/>');
                 }
                 for(j=0;j<totleRating-data["errMsg"][i]["rating"];j++){
                  $("#star_"+i).append('<span><img src="../assets/images/whiteStar.png" class="img-responsive "/>')
                 }
              }
            }
            else{
              //console.log(data['totleDoctors']);
              $(".search-result").html('<div class="not-found col-lg-12 col-md-12 col-sm-12 col-xs-12">'+data["errMsg"]+'</div>');

            }

          }
        });
      });

    }); 

  
</script>
</body>
</html>


 