<?php
//$activeHeader = "2opinion"; 
$pathPrefix="../";
$activeHeader = "doctorsArea"; 

session_start();
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php"); 
require_once("../../models/userModel.php");
require_once("../../models/dashboardModel.php");
require_once("../../models/ehrModel.php");
  //database connection handling

$blogurl="http://192.168.1.103/hansinfo_eheilung/wordpress";
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
  //echo "hii";
  if(noError($userInfo)){

    $userInfo = $userInfo["errMsg"];  
  
  } else {
    printArr("Error fetching user info".$userInfo["errMsg"]);
    exit;
  }
} else {
  printArr("You do not have sufficient privileges to access this page.<a href='../../index.php?status=login&user_type=2'> Login </a> to continue ");
  exit;
}
//$patient_id=$_GET['patient_id'];
$patient_id=71;
$doctor_id=$userInfo['user_id'];
$patientInfo=getUserInfoWithUserId1($patient_id,$conn);
$patientInfo=$patientInfo['errMsg'];
$characteristics=json_decode($patientInfo['doctor_8pf'],true);
$data=array();
//printArr($characteristics);
foreach ($characteristics as $key => $value) {
  //printArr($key);
  $data=$value;
}
/*foreach ($data as $key => $value) {
  foreach ($value as $key1 => $value1) {
     printArr($value1['factor_name']);
  }
}*/
$getCases=getPatientsAllComplaints($patient_id,$doctor_id,$conn);
$getCases=$getCases['errMsg'];
//printArr($getCases);

/*foreach ($getCases as $key => $value) {
  # code...
  echo $key;
  $complaint=$value['complaint_name'];
  $case_id=$value['id'];
  $caseDate=$value['created_on'];
  $getAllFolloups=getAllFolloups($case_id,$conn);
  $getAllFolloups=$getAllFolloups['errMsg'];
  $i=sizeof($getAllFolloups);
  foreach ($getAllFolloups as $key1 => $value1) {
      //echo $i."<br>";
      echo $conclusion=$value1['conclusion'];
      echo $followupDate=$value1['date'];
      $i--;
  }
  //printArr($getAllFolloups);
 
}*/

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

  .slider {
        width: 90%;
        margin: 0 auto;
    }

    .slick-slide {
      margin: 0px 20px;
    }

    .slick-slide img {
      width: 100%;
    }

    .slick-prev:before,
    .slick-next:before {
        color: black;
    }
    .slider .item {
      border:1px solid #cab9bd;
      border-radius: 8px;
      /*padding-left: 15px;
      padding-right: 15px;
      width: 20%;*/
      text-align: center;
     cursor: pointer;
     /*width: 250px !important;*/
   /*   margin-left: 30px;*/

    }

   /* .swiper-slide-active{
      background-color: aliceblue !important;
    }*/

    @media(max-width: 500px){
         .slider .item {
      /*width: 50% !important;*/
      height: 132px;
    }
    }
     @media(max-width: 400px){
         .slider .item {
      /*width: 80% !important;*/
     /* margin-left: 10%;
      margin-right: 10%;*/
    }
    }
    @media(max-width: 768px){
  .charts .open-btn{
    float: left;
  }
}


    .swiper-slide{
      display: block !important; 

    }

    .slider .item h2{
      font-size: 20px;
    }

    .slider .item h5{
      font-size: 16px;
      color: #454545;
    }
    .blue{
      color: #1f5dea;
    }
    .cyan{
      color: #43cb83;
    }
    .purple{
      color: #a51fea;
    }
    .orange{
      color: #ff7623;
    }

    .infobox span {
  width: 14px;
  height: 14px;
  display: block;
  float: left;
  margin: 1px 15px 0px 0px;
  border-radius: 10px;
}
.acne span{
  background-color: #f89420 !important;
}
.asthma span{
  background-color: #77c4d3 !important;
}
.arthritis span{
  background-color: #d42565 !important;
}
.hairfall span{
  background-color: #8cc63f !important;
}

.addnew{
  background-color: #0dae04;
  color: #fff;
  border-radius: 8px;
  text-align: center;
  border: none;
  outline: none;
  padding: 10px;
  font-family: Montserrat-Regular;
  min-width: 180px;
  margin-top : 50px;

}

#chartdiv a{
  font-size: 10px !important;
}


.swiper-container {
        /*width: 100%;*/
        height: 100%;
        margin-left: auto;
        margin-right: auto;
    }
    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;

        /* Center slide text vertically */
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
    }

.swiper-container {
  margin-top: 50px;
  position: relative;
  width: 80%;
  margin: 0 auto;
}



.nopatients {
    min-height: 100px;
    box-shadow: none;
    margin-top: 0px;
    padding: 10px;
}
.nopatients a:hover,.addnew:hover{
  color: #fff !important;
}
.addnew{
letter-spacing: 1px;
}

.swiper-button-next{
  position: relative !important;
  left:96% !important;
  margin-bottom: 41px;
}
@media(max-width: 768px){
  .swiper-button-next{
  margin-right: 15px !important;
  }
/*  .swiper-button-prev{
    margin-left: 15px !important;
  }*/
}

.charts .infobox h4{
  margin-left: 15px;
}

.followup{
  height: auto !important;
  padding-bottom: 15px !important;
}

.ehr-right .personal-char h4{
  text-align: left;

}

.ehr-left h1{
    color: #010101;
    font-family: Montserrat-Regular;
    font-size: 24px;
}
.ehr-left h2{
  color: #454545;
    font-family: Montserrat-Regular;
    line-height: 25px;
    font-size: 20px;
}
.ehr-left h3,.ehr-right h3{
  color: #0075c4;
    font-family: Montserrat-Regular;
    line-height: 25px !important;
    font-size: 18px;
}
.ehr-left h3 span ,.ehr-right h3 span{
      color: #454545;
    font-family: Montserrat-Regular;
}

.ehr-left h3 img ,.ehr-right h3 img{
  margin-right: 20px;
  width: 20px;
}
.ehr-left .profile img{
  width: 150px;
  height: 150px;
  object-fit:cover;
  margin-top: 20px;
}
.top-20{
  padding-top: 20px;
}
.top-40{
  padding-top: 40px;
}

.stresscircle{
  background-color: #ff5151;
  width: 200px;
  height: 200px;
  text-align: center;
  border-radius: 500px;
  vertical-align: middle;
  display: table-cell;

}
.stresscircle h1{
font-size: 35px;
 font-family: Montserrat-Regular;
   color: #fff;
}
.stresscircle h2{
font-size: 24px;
 font-family: Montserrat-Regular;
   color: #fff;
   margin-top: 10px;
}

.ehr-right h4{
  color: #454545;
  width: 60%;
  font-size: 16px;
 font-family: Montserrat-Regular;
 text-align: left;
}

.ehr-right cases h4{
  color: #7b7b7b;
  font-size: 16px;
 font-family: Montserrat-Regular;
 padding-top: 20px;
}

  .leftLabel{
 
    -webkit-transform-origin: 0 50%;
    -moz-transform-origin: 0 50%;
    -webkit-transform: rotate(-90deg) translate(-50%, 50%);
    -moz-transform: rotate(-90deg) translate(-50%, 50%);
    padding-right: 55%;
    font-size: 20px;
    letter-spacing: 1px;
    margin-left: -15px;
      }
.shadow{
  margin-top: 15px;
  background-color: #fff;
-webkit-box-shadow: 1px 2px 12px -2px rgba(0,0,0,0.48);
-moz-box-shadow: 1px 2px 12px -2px rgba(0,0,0,0.48);
box-shadow: 1px 2px 12px -2px rgba(0,0,0,0.48);
}

.accordion-toggle:hover {
      text-decoration: none;
    }

.panel {
  -webkit-box-shadow : none !important;
  box-shadow: none !important;
}
.panel-group a{
  text-decoration: none !important;
}
.panel-default{
  border-color: transparent !important;
}
.panel-default>.panel-heading{
  background-color: transparent !important;
  border-color: transparent !important;
  padding: 0 !important;
}

.panel-group .panel-heading+.panel-collapse>.panel-body{
  border-top: none !important;
  max-height: 250px;
  overflow-y: scroll;
}

.ehr-right h3 i {
  color: #888888 !important;
  margin-right: 10px;
}

.fa-sort-asc{
  /*vertical-align: -webkit-baseline-middle;*/
}
.panel-title .date{
  margin-top: 20px;
      margin-right: 39px;
    font-size: 17px;
}
	</style>
	<link rel="stylesheet" type="text/css" href="../../assets/css/home.css?aghrd=r4564298">
  <!-- <link rel="stylesheet" type="text/css" href="http://jivebay.com/calculating-the-moon-phase/"> -->
  

  <link rel="stylesheet" type="text/css" href="../../assets/css/slick.css">
  <link rel="stylesheet" type="text/css" href="../../assets/css/slick-theme.css?a=2">

  <link rel="stylesheet" href="../../assets/css/swiper.min.css">

  <script src="../../assets/js/jquery-2.2.0.min.js" type="text/javascript"></script>
  <script src="../../assets/js/slick.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript">
    $(document).on('ready', function() {
      $(".regular").slick({
        dots: true,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 3
      });
      $(".center").slick({
        dots: true,
        infinite: true,
        centerMode: true,
        slidesToShow: 3,
        slidesToScroll: 3
      });
      $(".variable").slick({
        dots: true,
        infinite: true,
        variableWidth: true
      });
    });
  </script>



</head>

	<main class="container" style="min-height: 100%;">



    <?php  include_once("../header.php"); ?>

    
  
    <div class="row noleft-right"  >  <!-- style="background-color:#f8f8f8;" -->
      <div class="col-md-4 col-sm-4 col-xs-12 ehr-left" >

          <div class="row">
            <div class="col-md-12 profile top-20">
                <?php if(!empty($patientInfo['user_image'])){ ?>
                <img src="<?php echo $patientInfo['user_image']; ?>" class="img-circle" >
                <?php }else { ?>
                <img src="../../assets/images/profilepic.png" class="img-circle" >
                <?php } ?>
            </div>

            <div class="col-md-12">
               <h1><?php echo ucfirst(strtolower($patientInfo['user_first_name'])).' '.ucfirst(strtolower($patientInfo['user_last_name'])); ?></h1>
               <h3>Date of birth: <span><?php if(!empty($patientInfo['user_dob'])) { echo date('d/m/Y', strtotime($patientInfo['user_dob'])); ?> (age <?php echo calcAge($patientInfo['user_dob']).')'; } ?></span></h3>
               <h3>Sex: <span><?php if(!empty($patientInfo['user_gender'])) { echo $patientInfo['user_gender']; } ?></span></h3>
               <h3>Occupation: <span><?php if(!empty($patientInfo['user_occ'])) { echo $patientInfo['user_occ']; } ?></span></h3>
            </div>

            <div class="col-md-12 top-20">
               <h3><img src="../../assets/images/call.png" /><span><?php echo $userInfo['user_mob']; ?></span></h3>
               <h3 style="display: flex;word-break: break-all;"><img src="../../assets/images/contmsg.png" style="height: 18px;margin-top: 10px;" /><span><?php echo $userInfo['user_email']; ?></span></h3>
               <h3><img src="../../assets/images/home.png" /><span><?php echo $userInfo['user_address']; ?></span></h3>
            </div>


            <!-- <div class="col-md-12 top-40">
              <div class="stresscircle">
                <h1>183</h1>
                <h2>Stress Score</h2>
              </div>
            </div>

            <div class="col-md-12 top-20">
              <h2>Divorce</h2>
              <h2>Spouce starts or stop work</h2>
              <h2>Major mortgage</h2>
              
            </div> -->
          </div>
      </div>

      <div class="col-md-8 col-sm-8 col-xs-12 ehr-right" >
        <div class="row">
          <!-- Cases -->
            <div class="col-md-12 shadow">
              <h2>Cases</h2>

             <div class="row cases">
              
          

              <div class="col-md-12 ">

    
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

        <?php 
            $complaintArray=array();
            $array=array();
            $maxComplaint="";
            
            foreach ($getCases as $key => $value) {
              //$getCases[0]['complaint_name'];
              $complaint=$value['complaint_name'];
              $prescription=$value['primary_prescription'];
              $case_id=$value['id'];
              $caseDate=$value['created_on'];
              $getAllFolloups=getAllFolloups($case_id,$conn);
              $getAllFolloups=$getAllFolloups['errMsg'];
              $i=sizeof($getAllFolloups);
              if(!empty($getAllFolloups)){
                array_push($complaintArray, array('case_id'=>$case_id,'complaint'=>$complaint));
              }
              //printArr($getComplaintDetails);
              //printArr($getAllFolloups);
        ?>
              <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingOne">
                      <div class="panel-title">
                          <div  role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne<?php echo $key+1; ?>" aria-expanded="true" aria-controls="collapseOne<?php echo $key+1; ?>">
                            
                               <h3> <i class="fa fa-2x fa-sort-desc"></i> <?php echo $complaint; ?>: <span><?php echo $prescription; ?></span>   <span class="date pull-right"><?php echo date('d/m/Y', strtotime($caseDate));?></span></h3>
                              
                          </div>
                      </div>
                  </div>

                  <?php if(!empty($getAllFolloups)){ ?>
                  <div id="collapseOne<?php echo $key+1; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                      <div class="panel-body">
                        <?php foreach ($getAllFolloups as $key1 => $value1) {
                            $conclusion=$value1['conclusion'];
                            $followupDate=$value1['date'];
                        ?> 
                        <div class="col-md-10 col-xs-12 no-left-padd">
                          <h3>Follow up <?php echo $i; ?>: <span><?php echo $conclusion; ?></span></h3>
                        </div>
                        <div class="col-md-2 col-xs-12 ">
                          <h4><?php echo date('d/m/Y', strtotime($followupDate));?></h4>
                        </div>
                        <?php 
                              $i--;
                            }
                        ?>

                      </div>
                  </div>
                  <?php } ?>
              </div>
        <?php } 
              //printArr($complaintArray);
               $key1=0;
              foreach ($complaintArray as $key => $value) {
                $getComplaintDetails[$key]=getComplaintDetails($value['complaint'],$key,$conn);

                //printArr($getComplaintDetails);
                //greater is bad
                //echo $key;
                if($key==0){
                    if(sizeof($complaintArray)==1){
                      $key1=0;
                    }else {
                      $key1=1;
                    }
                    //$maxComplaint=$complaintArray[$key]['complaint'];
                }else{
                  if($getComplaintDetails[$key]['m_id']>$getComplaintDetails[$key1-1]['m_id']){
                    $key1=$key;
                    //$maxComplaint=$getCases[$key]['complaint_name'];
                  }elseif($getComplaintDetails[$key]['m_id']<$getComplaintDetails[$key1-1]['m_id']){
                    $key1=$key1-1;
                    //$maxComplaint=$getCases[$key-1]['complaint_name'];
                  }else{
                    //$maxComplaint=$getCases[$key]['complaint_name'];
                    if($getComplaintDetails[$key]['e_id']>$getComplaintDetails[$key1-1]['e_id']){
                      $key1=$key;
                    }elseif($getComplaintDetails[$key]['e_id']<$getComplaintDetails[$key1-1]['e_id']){
                      $key1=$key1-1;
                    }else{
                        if($getComplaintDetails[$key]['system_id']>$getComplaintDetails[$key1-1]['system_id']){
                          $key1=$key;
                        }elseif($getComplaintDetails[$key]['system_id']<$getComplaintDetails[$key1-1]['system_id']){
                          $key1=$key1-1;
                        }else{
                            if($getComplaintDetails[$key]['o_id']>$getComplaintDetails[$key1-1]['o_id']){
                              $key1=$key;
                            }elseif($getComplaintDetails[$key]['o_id']<$getComplaintDetails[$key1-1]['o_id']){
                              $key1=$key1-1;
                            }else{
                                if($getComplaintDetails[$key]['s_id']>$getComplaintDetails[$key1-1]['s_id']){
                                  $key1=$key;
                                }elseif($getComplaintDetails[$key]['s_id']<$getComplaintDetails[$key1-1]['s_id']){
                                  $key1=$key1-1;
                                }else{
                                    $key1=$key;
                                }
                            }
                        }
                    }
                    //$key1=$key;
                  }
                }
                //echo $key1;
                //echo $maxComplaint=$complaintArray[$key1]['complaint'];
              }
              //echo $maxComplaint=$complaintArray[$key1]['complaint'];
              $case_id=$complaintArray[$key1]['case_id'];
              $getAllFolloups=getAllFolloups($case_id,$conn);
              $getAllFolloups=$getAllFolloups['errMsg'];
              //printArr($getAllFolloups);
              $count=0;
              $start=0;
              $range=array();
              if($getAllFolloups[0]['conclusion_status']=='bad'){
                $start=10;
              }
              foreach ($getAllFolloups as $key => $value) {
                if($value['conclusion_status']=='good'){
                  //$range[$key]=$start+10;
                  if($key==0){
                    $range[$key]=$start;
                    
                  }else{
                    $range[$key]=$range[$key-1]+10;
                  }
                }else if($value['conclusion_status']=='bad'){
                  //$range[$key]=$start-10;
                  if($key==0){
                    $range[$key]=$start;
                    
                  }else{
                    $range[$key]=$range[$key-1]-10;
                  }
                }else{
                  /*if($getAllFolloups[$key-1]['conclusion_status']=='noChange'){

                  }else{

                  }*/
                  if($key==0){
                    $range[$key]=$start;
                    
                  }else{
                    $range[$key]=$range[$key-1];
                  }
                }
                $count++;
              }
              //echo $count;
              //printArr($range);

        ?>
        
        

        <!-- <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        
                         <h3>  <i class="fa fa-2x fa-sort-desc"></i> Asthma: <span>Bronchodilator</span></h3>
                    </a>
                </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                <div class="panel-body">
                    <div class="col-md-10 col-xs-12 no-left-padd">
               <h3>Follow up 1: <span>Not much progress</span></h3>
              </div>
              <div class="col-md-2 col-xs-12 ">
                <h4>03/06/2017</h4>
              </div>


              <div class="col-md-10 col-xs-12 no-left-padd">
                <h3>Follow up 2: <span>The medicine starts having its positive effect</span></h3>
              </div>
              <div class="col-md-2 col-xs-12">
                <h4>03/06/2017</h4>
              </div>

            
              <div class="col-md-10 col-xs-12 no-left-padd">
                 <h3>Follow up 3: <span>Everything is going great. It would be good to stop taking the medicine for some time, and see how it goes</span></h3>
              </div>
              <div class="col-md-2 col-xs-12">
                <h4>03/06/2017</h4>
              </div>
                </div>
            </div>
        </div> -->

    </div><!-- panel-group -->


            </div>

            </div>
            </div>

             <!-- Health Status -->
            <div class="col-md-12 shadow">
              <h2>Health Status</h2>
                   <div id="myChart1"  ></div>
            </div>



            <!-- Personal Characteristic -->
            <div class="col-md-12 personal-char shadow">
              <h2>Personal characteristics</h2>
              <?php
                foreach ($data as $key => $value) {
                  foreach ($value as $key1 => $value1) {
                     //printArr($value1['factor_name']);
              ?>              
                     <h4><?php echo $value1['factor_name'];?></h4>
              <?php
                  }
                }
              ?>
              
              <!-- <h2>Personal characteristics</h2>

              <h4>Outgoing, warmhearted, easy-going, paricipating (Affectogthymia)</h4>

              <h4>More intelligent, abstract-thinking, bright (Higher scholastic mental capacity</h4>

              <h4>Assertive, aggressive, stuborn, competitive (Dominance)</h4>

              <h4>Happy-go-lucky, enthusiastic (Surgency)</h4>

              <h4>Conscientious, persistent, moralistic, staid (Stronger superego strength)</h4>

              <h4>Venturesome, uninhibited, socialy bold (Permia)</h4>

              <h4>Controlled, exactng wil power, socially precise, compulsive (High strength of self-sentiment)</h4>

              <h4>Tensed, frustrated, drived, overwrought (High ergic tension)</h4>
 -->
            </div>
        </div>
      </div>

    </div>

</div>
  








    
 
   

</main> 
<script src="../../assets/js/highcharts.js"></script>
<?php  include('../footer.php'); ?>

<script type="text/javascript">
var dataArray = new Array();
var start=0;
    <?php foreach($range as $key => $val){ ?>
        /*var key=<?php echo $key; ?>;
        if(key==0){
          dataArray[0]=new Array (0,0);
        }*/
        //start=start+0.5;
        dataArray[<?php echo $key; ?>] = new Array (start,<?php echo $val; ?>);
        start=start+0.5;
    <?php } ?>
    console.log(dataArray);
    

    
//var data1=[[0, 0], [2.5, 30], [5, 0]];

Highcharts.chart('myChart1', {

    xAxis: {
        reversed: false,
        title: {
            enabled: true,
            text: 'Time'
        },
        labels: {
            formatter: function () {
                return this.value + 'yrs';
            }
        }
    
    },
    yAxis: {
        title: {
            text: 'Improvement'
        }
    },
    legend: {
        enabled: false
    },

    plotOptions: {
        spo: {
            marker: {
                radius: 25,
                lineColor: '#666666',
                lineWidth: 2
            }
        }
    },

    series: [{
        name: 'Temperature',
        data: dataArray
    }]

});
$('.collapse').on('shown.bs.collapse', function(){
$(this).parent().find(".fa-sort-desc").removeClass("fa-sort-desc").addClass("fa-sort-asc");
}).on('hidden.bs.collapse', function(){
$(this).parent().find(".fa-sort-asc").removeClass("fa-sort-asc").addClass("fa-sort-desc");
});
</script>
<script src="../../assets/js/amcharts.js"></script>
<script src="../../assets/js/pie.js"></script>
<script src="../../assets/js/serial.js"></script>
<script src="../../assets/js/light.js"></script>
<script src="../../assets/js/amcharts.responsive.min.js"></script>
</body>
</html>
