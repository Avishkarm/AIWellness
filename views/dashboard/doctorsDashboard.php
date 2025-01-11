<?php
//$activeHeader = "2opinion"; 
$pathPrefix="../";
$activeHeader = "doctorsArea"; 

session_start();
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php"); 
require_once("../../models/userModel.php");
require_once("../../models/dashboardModel.php");
require_once("../../models/admin/planModel.php");
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
$now=time();
if (isset($_SESSION['discard_after']) && $now > $_SESSION['discard_after']) {
    // this session has worn out its welcome; kill it and start a brand new one
   /* session_unset();
    session_destroy();
    session_start();*/
    printArr("You do not have sufficient privileges to access this page<br>Login to continue <a href='".$rootUrl."/controllers/logout.php'>Home</a> ");
  exit;
  //echo "bye";
}else{
  //echo "hiii";
}
$payuStatus=$_GET['payuStatus'];
//$payuStatus='success';

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

if(isset($_GET['pageNo'])){
  $pageNo=$_GET['pageNo'];
}else{
  $pageNo=1;
}

//printArr($userInfo);
if($userInfo['firstUpdate']==0){
$redirectURL ="../profile/completeProfile.php";
      header("Location:".$redirectURL); 
      // Redirect browser 
exit();
}

if(!empty($userInfo['user_country'])){
  $countryId=getUserCountryId($userInfo['user_country'],$conn);
}else{
  $countryId=getUserCountryId('India',$conn);
}

$getDoctorsAllCases=getDoctorsAllCases($userInfo['user_id'],$conn);

//$getDoctorsAllCases=array_reverse($getDoctorsAllCases['errMsg']);
$getDoctorsAllCases=$getDoctorsAllCases['errMsg'];
//printArr($getDoctorsAllCases);
//$emptyStatus=0;
if(!empty($getDoctorsAllCases)){
   $emptyStatus=1;
}else
{
   $emptyStatus=0;
}

if($emptyStatus!=0){
 sizeof($getDoctorsAllCases); 
  $startDate=$getDoctorsAllCases[0]['created_on'];
  $endDate=$getDoctorsAllCases[sizeof($getDoctorsAllCases)-1]['created_on'];
$start    = (new DateTime($startDate))->modify('first day of this month');
$end      = (new DateTime($endDate))->modify('first day of next month');
$interval = DateInterval::createFromDateString('1 month');
$period   = new DatePeriod($start, $interval, $end);
//printArr($period);

$end1 =(new DateTime($endDate))->modify('first day of this month');
//printArr($end1 );
$array1 =  (array) $end1;
$defaultYear=$getDoctorsAllCases[sizeof($getDoctorsAllCases)-1]['y'];
$deafaultMonth=$getDoctorsAllCases[sizeof($getDoctorsAllCases)-1]['m'];
$deafaultDate=$array1['date'];
$dateObj1  = DateTime::createFromFormat('!m', $deafaultMonth);
/*printArr($dateObj1);
echo "httfjdghfji";*/
$defaultMonthName=$dateObj1->format('F');
$defaultMonthName1=$dateObj1->format('M');
// $array =  (array) $fistDay;
/*foreach ($period as $dt) {
    $dt->format("Y-m") . "<br>\n";
    $dateObj   = DateTime::createFromFormat('!m', $dt->format("m"));
      echo  $monthName = $dateObj->format('F');
}*/
$getDoctorsAllcomplaints=getDoctorsAllcomplaints($userInfo['user_id'],$conn);

if(noError($getDoctorsAllcomplaints)){
  $getDoctorsAllcomplaints=$getDoctorsAllcomplaints['errMsg'];
}else{
  printArr('error fetching doctors cases');
}
//printArr($getDoctorsAllcomplaints);
 $complaint1=cleanQueryParameter($conn,$getDoctorsAllcomplaints[0]['complaint_name']);
 $countComplaint1=$getDoctorsAllcomplaints[0]['count'];
 $complaint2=cleanQueryParameter($conn,$getDoctorsAllcomplaints[1]['complaint_name']);
 $countComplaint2=$getDoctorsAllcomplaints[1]['count'];
$complaint3=cleanQueryParameter($conn,$getDoctorsAllcomplaints[2]['complaint_name']);
$countComplaint3=$getDoctorsAllcomplaints[2]['count'];
$complaint4=cleanQueryParameter($conn,$getDoctorsAllcomplaints[3]['complaint_name']);
$countComplaint4=$getDoctorsAllcomplaints[3]['count'];
 $totalCount=$countComplaint1+$countComplaint2+$countComplaint3+$countComplaint4;
 $complaint1_percent=round(($countComplaint1*100)/$totalCount);
 $complaint2_percent=round(($countComplaint2*100)/$totalCount);
$complaint3_percent=round(($countComplaint3*100)/$totalCount);
$complaint4_percent=round(($countComplaint4*100)/$totalCount);
$complaint1=cleanQueryParameter($conn,$complaint1);
//echo $complaint1_percent+$complaint2_percent+$complaint3_percent+$complaint4_percent

/* $monthlyReport = getDoctorUserCaseMonthlyReport($conn, $userInfo['user_id']);

  if(noError($monthlyReport)){
    $monthlyReport = $monthlyReport['errMsg'];
  }else{
    printArr("Error in Fetching Monthly Report". $monthlyReport['errMsg']);
  }*/
//printArr($monthlyReport);
}


/*
function moon_phase($year, $month, $day)

{

 

 // modified from http://www.voidware.com/moon_phase.htm

 

  $c = $e = $jd = $b = 0;

  if ($month < 3)

  {

    $year--;

    $month += 12;

  }

  ++$month;

  $c = 365.25 * $year;

  $e = 30.6 * $month;

  $jd = $c + $e + $day - 694039.09; //jd is total days elapsed

  $jd /= 29.5305882;          //divide by the moon cycle

  $b = (int) $jd;           //int(jd) -> b, take integer part of jd

  $jd -= $b;              //subtract integer part to leave fractional part of original jd

  $b = round($jd * 8);        //scale fraction from 0-8 and round

  if ($b >= 8 )

  {

    $b = 0;//0 and 8 are the same so turn 8 into 0

  }

  switch ($b)

  {

    case 0:

      return 'New Moon';

      break;

    case 1:

      return 'Waxing Crescent Moon';

      break;

    case 2:

      return 'Quarter Moon';

      break;

    case 3:

      return 'Waxing Gibbous Moon';

      break;

    case 4:

      return 'Full Moon';

      break;

    case 5:

      return 'Waning Gibbous Moon';

      break;

    case 6:

      return 'Last Quarter Moon';

      break;

    case 7:

      return 'Waning Crescent Moon';

      break;

    default:

      return 'Error';

  }

}

$timestamp = time();

echo moon_phase(date('Y', $timestamp), date('n', $timestamp), date('j', $timestamp));*/


include('moonPhase.php');

$date = time();

$year = date('Y', $date);

$month = date('n', $date);

$day = date('j', $date);

$moon = moon_posit($month, $day, $year);
$getQuote=getDailyQuote($conn);
//printArr($getQuote);
$quote=$getQuote['errMsg']['quote'];
//printArr($moon);


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
      $user_ip = $_SERVER['REMOTE_ADDR'];
      $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
      //printArr($geo);
      //$country = $geo["geoplugin_countryName"];
      $city = $geo["geoplugin_city"];
      $getUser="SELECT * from temperature WHERE user_id=".$userInfo['user_id'];
      $getUser_result=runQuery($getUser, $conn);
      $getUser_row=mysqli_fetch_assoc($getUser_result["dbResource"]);
       //printArr($getdateQuery_row);
      if($getUser_result["dbResource"]->num_rows==0){
        if(!empty($city)){
         
          $categoryPost = httpGet("http://api.openweathermap.org/data/2.5/weather?APPID=c70efb30aac3e5898e15ffcdcb49425a&units=metric&q=".$city);
        }else if(!empty($userInfo['user_city'])){
         
          //$userInfo['user_city'];
          $categoryPost = httpGet("http://api.openweathermap.org/data/2.5/weather?APPID=c70efb30aac3e5898e15ffcdcb49425a&units=metric&q=".$userInfo['user_city']);
        }
        $categoryPost = json_decode($categoryPost, TRUE);
        //echo $categoryPost['main']['temp'];
        $query="INSERT INTO temperature (user_id,temp,city,created_on) values(".$userInfo['user_id'].",".$categoryPost['main']['temp'].",'".$categoryPost['name']."','".date("Y-m-d H:i:s")."')";
        $query_result=runQuery($query, $conn);
        //printArr($query_result);

      }else{
        
         $getUser1="SELECT * from temperature WHERE user_id=".$userInfo['user_id']." and created_on >= DATE_SUB( NOW() ,INTERVAL 10 MINUTE)";
        $getUser1_result=runQuery($getUser1, $conn);
        $getUser1_row=mysqli_fetch_assoc($getUser1_result["dbResource"]);
        if($getUser1_result["dbResource"]->num_rows==0){
          if(!empty($city)){
          $categoryPost = httpGet("http://api.openweathermap.org/data/2.5/weather?APPID=c70efb30aac3e5898e15ffcdcb49425a&units=metric&q=".$city);
          }else if(!empty($userInfo['user_city'])){
            //echo $userInfo['user_city'];
            $categoryPost = httpGet("http://api.openweathermap.org/data/2.5/weather?APPID=c70efb30aac3e5898e15ffcdcb49425a&units=metric&q=".$userInfo['user_city']);
          }
          $categoryPost = json_decode($categoryPost, TRUE);
           $query="UPDATE temperature SET temp='".$categoryPost['main']['temp']."',city='".$categoryPost['name']."',created_on='".date("Y-m-d H:i:s")."' WHERE user_id=".$userInfo['user_id'];
          $query_result=runQuery($query, $conn);
        }
      }

/*$getUser="SELECT * from temperature WHERE user_id=".$userInfo['user_id'];
$getUser_result=runQuery($getUser, $connAdmin);
$getUser_row=mysqli_fetch_assoc($getUser_result["dbResource"]);*/
$getTemp=getTemp($userInfo['user_id'],$conn);
//printArr($getTemp);
$temp=$getTemp['errMsg']['temp'];
$cityName=$getTemp['errMsg']['city'];

/*echo "hii";*/
//$categoryPost = httpGet("http://api.openweathermap.org/data/2.5/weather?APPID=c70efb30aac3e5898e15ffcdcb49425a&q=mumbai&units=metric");

//printArr($categoryPost);
//echo $categoryPost['main']['name'];
 //$getPlanExpiring=getPlanExpiring($userInfo['user_id'],$conn);  //ALERT-KDR
      //printArr($getPlanExpiring);
      //echo $getPlanExpiring['diff'];
 $access=1;     
//if($getPlanExpiring['diff']<=0 || $getPlanExpiring['diff']==""){  //ALERT-KDR
//  $access=0;
//}
//$access=1;
$_SESSION['payu']=true;

//printArr($getPlanExpiring);
$posted = array();

/*
if(isset($_POST['paySubmit'])){
    $posted['email'] = cleanQueryParameter($_POST['userEmail']);
    $posted['firstname'] = cleanQueryParameter($_POST['fname']);
    $posted['lastname'] = cleanQueryParameter($_POST['lname']);
    $posted['phone'] = cleanQueryParameter($_POST['phone_no']);
    $posted['region_id'] = cleanQueryParameter($_POST['region_name']);
    $posted['currencyId'] = cleanQueryParameter($_POST['currency']);
  $posted['amount'] = cleanQueryParameter($_POST['amount']);
    $posted['productinfo'] = cleanQueryParameter($_POST['productinfo']);
    $posted['promoCode'] = cleanQueryParameter($_POST['promoCode']);

    $posted['display'] = $_POST['display'];
    $posted['payData'] = $_SESSION['payData'];

    //if(isset($_POST['paySubmit'])){
      //$posted['PAYU_BASE_URL'] = "https://secure.payu.in";
    //}else{
      $posted['PAYU_BASE_URL'] = "https://test.payu.in";
    //}

    //verify pricing 
    //$priceFlag = verifypricing($conn,$posted['region_id'],$posted['amount']);
    //if(noError($priceFlag)){
      //$priceFlag = $priceFlag['errFlag'];
      //if(empty($priceFlag)){
        //printArr("Price Amount Invalid");
      //}else{
        $_SESSION['postedArr'] = $posted;

        //if($posted['promoCode'] == 'eh0001'){
         // $redirectURL = "payment_success.php";
        //}else{
          $redirectURL = "payment.php";
        //}
        print("<script>");
        print("var t = setTimeout(\"window.location='".$redirectURL."';\",1000);");
      print("</script>");
      //}
   // }else{
      printArr("Error in veryifying Price Details".$priceFlag['errMsg']);
   // }

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

/*@media(min-width: 1200px){
  .swiper-button-next, .swiper-container-rtl .swiper-button-prev {
    right: 91px !important;
}
}
@media(max-width: 1000px){
  .swiper-button-next, .swiper-container-rtl .swiper-button-prev {
    right: 0px !important;
}
}*/

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


@media(max-width: 600px){
  .charts .infobox {
    margin-top: 0px;
}
}

.quotes{
  min-height: 130px;
  display: grid;
  vertical-align: bottom;
  align-items : flex-end;
}

.quotes h3{
  margin-top: 0;
}

.quotes h3 i{
margin: 0;
line-height: 25px;
font-size: 16px;
color: #000;
}

.quotes h3 span{
  font-size: 30px;

}
.quotes h6{
 margin-top: 0;
 text-align: right;
}

.temperature h3{

text-align: left;
font-weight: 800;
}


.temperature h4{
 text-align: left;
 color: #107dc7; 
}

.temperature h6{
  
}

.apply {
    border: 2px solid #0dae04;
    background-color: transparent;
    text-align: center;
    padding: 8px 30px 8px 30px;
    outline: none;
    letter-spacing: 1px;
    font-size: 16px;
    border-radius: 5px;
    color: #0dae04;
    float: left;
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
<!-- 
    <div class="row noleft-right" style="">
      <div class="col-md-12"> 
        <div class="followup">
          <h4><?php echo "Moon phase: ".$moon['phase'];?></h4>
        </div>
      </div>
    </div>
    <div class="row noleft-right" style="">
      <div class="col-md-12">
        <div class="followup" style="background-color: #b2fec5;">
          <h4><?php echo $quote;?></h4>
        </div>
      </div>
    </div> -->

    <?php
/* //ALERT-KDR
      if($getPlanExpiring['diff']==""){
    ?>
    <div class="row noleft-right"  >
    <div class="col-md-12">
      <div class="followup">
        <h4 style="cursor:pointer;">You dont have any plan.<a class="freePlan"> Get free trial now!</a></h4>
      </div>
    </div>
  </div> 
   <?php
  }
  else if($getPlanExpiring['diff']<=0 && $getPlanExpiring['plan_status']==1){
?>
  <div class="row noleft-right"  >
    <div class="col-md-12">
      <div class="followup">
        <h4 style="cursor:pointer;">Your free trial has expired.<a class="freePlanExtend1"> Extend it now!</a></a></h4>
      </div>
    </div>
  </div> 
<?php    
  }
  else if($getPlanExpiring['diff']<=0 && $getPlanExpiring['plan_status']==2){
?>
  <div class="row noleft-right"  >
    <div class="col-md-12">
      <div class="followup">
        <h4 style="cursor:pointer;">Your free trial has expired.<a class="freePlanExtend2"> Extend it now!</a></a></h4>
      </div>
    </div>
  </div> 
<?php    
  }
  else if($getPlanExpiring['diff']<=0){
?>
  <div class="row noleft-right"  >
    <div class="col-md-12">
      <div class="followup">
        <h4 style="cursor:pointer;">Your plan has expired.<a class="payu"> Extend it now!</a></a></h4>
      </div>
    </div>
  </div> 
<?php    
  }
  else if($getPlanExpiring['diff']<4 && $getPlanExpiring['diff']>0){
?>
  <div class="row noleft-right"  >
    <div class="col-md-12">
      <div class="followup">
        <h4 style="cursor:pointer;">Your plan will end in <?php echo $getPlanExpiring['diff']; ?> days.<a class="payu"> Extend it now!</a></a></h4>
      </div>
    </div>
  </div> 
<?php    
  }
  */
?>

 <?php 
 if($access==1) { 
     ?>
    <div class="row noleft-right" >
      <div class="col-md-8 col-sm-7 col-xs-12 managepatient" >
        <h2>Dashboard<img src="../../assets/images/info.png" class="heading-info dashboard" /></h2>
      </div>


       <div class="col-md-4 col-sm-5 col-xs-12 managepatient" >
        
        <div class="row">          
          <div class="col-md-6 col-xs-6 temperature">
          <?php if(!empty($temp) && !empty($cityName)) {?>
            <h3>Temperature</h3>
            <h4><?php echo $temp;?> &deg; C</h4>
            <h6><?php echo $cityName; ?></h6>
            <?php } ?>
          </div>
          <div class="col-md-6 col-xs-6 temperature">
            <h3>Moon Phase</h3>
            <h4><?php echo $moon['phase'];?></h4>
            <!-- <h6>illumination: 86%</h6> -->
          </div>

        </div>
        <div class="row">
           
          <div class=" quotes">
          
            <!-- <h3><span>&ldquo;</span></h3> -->
            <h3><sub><span>&ldquo;</span></sub> <i><?php echo $quote;?></i>
              <sub><span>&bdquo;</span></sub> </h3>      
            <!-- <h6 class="pull-right">Earl Wilson</h6> -->       
          </div>
        </div>
      </div>


    </div>


<?php if(!empty($getDoctorsAllcomplaints)){ ?>

    <div class="row noleft-right charts" >
      <div class="col-md-8 col-sm-8 col-xs-12">
        <h2>Diseases treatment</h2>
      </div>

      <div class="col-md-4 col-sm-4 col-xs-12">
        <input type="button" value="OPEN MY PATIENTS" class="open-btn myPatient pull-right">
      </div>
      
      
    </div>

    <div class="row noleft-right charts">
    

      
    <div class="col-md-5 col-sm-6 col-xs-12">
      <div id="chartdiv" style="height: 500px;width: 100%;"></div>
    </div>
    <div class="col-md-5 col-md-offset-1  col-sm-6 col-xs-12 infobox">
      <?php if(!empty($complaint1)){ ?>
        <h4 class="acne"><span></span> <?php echo $complaint1; ?></h4>
      <?php } ?>
      <?php if(!empty($complaint2)){ ?>
      <h4 class="hairfall"><span></span> <?php echo $complaint2; ?></h4>
      <?php } ?>
      <?php if(!empty($complaint3)){ ?>
      <h4 class="arthritis"><span></span> <?php echo $complaint3; ?></h4>
      <?php } ?>
      <?php if(!empty($complaint4)){ ?>
      <h4 class="asthma"><span></span> <?php echo $complaint4; ?></h4>
      <?php } ?>
    </div>

    </div>
<?php } ?>
<?php if(!empty($getDoctorsAllCases)){ ?>
    <div class="row noleft-right charts" style="margin-bottom:30px;" >
      <div class="col-md-6 col-sm-6 col-xs-6">
        <h2>Cases</h2>
      </div>
         <div class="col-md-6 col-sm-6 col-xs-6">
        
      </div>
    </div>

<div class="row noleft-right">
     <div class="swiper-container">
        <div class="swiper-wrapper slider">
        <?php 
        foreach ($getDoctorsAllCases as $key => $value) {
          # code...
          //printArr($value);
        
          foreach ($period as $dt) {
              $dt->format("Y-m") . "<br>\n";
              $dateObj   = DateTime::createFromFormat('!m', $dt->format("m"));
                $monthName = $dateObj->format('F');
                $monthName1 = $dateObj->format('M');
                if($dt->format("Y")==$value['y'] && $dt->format("m")==$value['m']){
                $fistDay    = (new DateTime( $value['created_on']))->modify('first day of this month');
                  //printArr($fistDay);
                  $array =  (array) $fistDay;
                  //echo $monthName;

        ?>
                  <div class="swiper-slide item " onclick="displayWeeklyGraph('<?php echo $monthName1; ?>',<?php echo $dt->format("Y");?>,<?php echo $dt->format("m");?>,'<?php echo  $array['date']; ?>')">
                    <h2 class="blue"><?php echo $value['count']; ?></h2>
                    <h5><?php echo $monthName; ?> <?php if($dt->format("m")==12) echo $dt->format("Y");?></h5>
                  </div>
            <?php }else{ ?>
             <?php }} } ?>
           
          
        </div>
       
        <!-- Add Arrows -->
        

    </div>
<div class="swiper-button-next"></div>
<div class="swiper-button-prev"></div>
</div>


<div class="row noleft-right charts" style="margin-top:50px;">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div id="areachart" style="height: 500px;width: 100%;"></div>
      <div style="clear:both;"></div>
    </div>


      <!-- <div class="col-md-12"  style="text-align:center" >
        <input type="button" value="ADD NEW CASE" class="addnew myPatient">
      </div> -->
 </div>
<?php } if(empty($getDoctorsAllCases) && empty($getDoctorsAllcomplaints)) { ?>
      <!-- <div class="noDataFound" style="text-align: center;color:grey;margin-top:10%;letter-spacing: 2px;word-spacing: 1px;">
        <h1>No cases found</h1>
      </div>
       <div class="col-md-12"  style="text-align:center" >
        <input type="button" value="ADD NEW CASE" class="addnew myPatient">
      </div> -->

      <div class="nopatients">
          <div class="row noleft-right">      
            
            <h1>Welcome to Dashboard</h1>
            
            <h4>In this section you will find the graphs of the current diseases being treated, as well as patients’ cases history</h4>


            <div class="col-md-12"  style="text-align:center;margin-top:20px;" >
              <!-- <input type="button" value="ADD NEW CASE" class="addnew myPatient"> -->
              <a href="../patient/manage_patient.php" class="addnew myPatient">START CASE</a>
            </div>

          </div>

      </div>

 <?php } }?>


      <div class="modal fade" id="payumodal" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
                </div><!-- ../payu/payment.php ../../payumoney/PayUMoney_form.php  ../payu/payment_success.php-->
            <form class="mui-form error-messaging" id="payuForm" action="../payu/payment.php" method="POST" data-toggle="validator" role="form">
              
              <div class="row">

                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <div class="mui-select " style="display:inline-block;width:100%;margin-bottom:10px;padding-top: 35px;">
                 <select required="true" name="duration" id="duration" onchange="getAmount(this)" >
                  <option selected disabled="true">Select Plan</option>
                  <?php $getPlans=getAllPlans($conn);
                          foreach ($getPlans['errMsg'] as $key => $value) {
                  ?>
                           <option value="<?php echo $value['id']; ?>"><?php echo $value['duration'].' months';?></option>
                  <?php
                          }
                  ?>
                  </select>
                  <!-- <label></label> -->
                </div>
                <div class="error error-duration" style="color:red;"></div>
                
                <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                  <h4 style="text-align: left;color:#bfbfc7;padding-top: 20px;">Have a coupon code?</h4>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="text" name="promocode" class="promocode" onkeypress="promocodeAction();" value="" style="height:45px">
                     <div class="error error-promocode" style="padding-top:10px;color:red;"></div>
                  </div>
                  <div class="col-md-4 col-sm-4 col-xs-12">
                    <input type="button" value="Apply" class="apply applybtn"  onclick="getDiscount()">
                    <!-- <input type="button" > -->
                    <button button type=""  value="Apply" class="apply applyload" style="display:none" >Loding...<img style="height:20px" src="../../assets/images/ajax-loader.gif"></button>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12 ">
                        <div class="amount pricediv" style="text-align: right;font-size:25px;color:black"></div>
                        <p class="priceload" style="display:none">Loding...<img style="height:20px" src="../../assets/images/ajax-loader.gif"></p>
                    </div>


                </div>
               <!--  <div class="error error-promocode" style="color:red;"></div> -->
                <div class="hiddeni-inputs">
                  <input placeholder="User Email" required class="form-control" type="hidden" id="userEmail" name="email" value="<?php echo $userInfo['user_email'];?>" />
                  <input placeholder="First Name" required class="form-control" type="hidden" id="fname" name="firstname" value="<?php echo $userInfo['user_first_name'];?>" />
                  <input placeholder="Last Name" required class="form-control" type="hidden" id="lname" name="lastname" value="<?php echo $userInfo['user_last_name'];?>"/>
                  <input placeholder="Mobile Number" required class="form-control" type="hidden" id="phone_no" name="phone" value="<?php echo $userInfo['user_mob'];?>" readonly/>
                  <input type="hidden" name="region_name" id="region" value="">
                  <input type="hidden" class="form-control" id="curr" name="curr" value="" readonly required>
                  <input type="hidden" name="currency" id="currency" value="">
                  <input type="hidden" name="region_id" id="region_id" value="">
                  <input type="hidden" name="discount" id="discount" value="0">
                  <input type="hidden" id="amount" name="amount" value=""  readonly />
                   <input  type="hidden" id="mainAmount" name="mainAmount" value=""  readonly />
                  <input type="hidden" name="productinfo" value="eheilung">
                  <input  type="hidden" name="surl" value="<?php echo $rootUrl;?>/views/payu/payment_success.php" size="64" />
                  <input  type="hidden" name="furl" value="<?php echo $rootUrl;?>/views/payu/payment_error.php" size="64" />
                  <input type="hidden" name="service_provider" value="payu_paisa" size="64" /><input type="hidden" name="type" value="getpayu" size="64" />
                </div>
                <div class="row" style="padding-top:9%;padding-bottom:5%;">
                    <button button type="submit"  onClick="" name="paySubmit" style="line-height:29px; " value="submit" class="signupbtn paybtn" >Pay</button>
                    <button button type=""  class="signupbtn payload" style="display:none" >Loading...<img style="height:25px" src="../../assets/images/ajax-loader.gif"></button>
                </div>
                <!--  <button type="submit" class="signupbtn" id="signup" >Sign up</button> -->
               <!--  <button type="button" class="signupbtn" onclick="signup()">Sign up</button> -->
                
                </div>

              </div>

            </form>
             
            </div>
          </div>
    </div>
    
   <!-- ******************** signup failed Modal  ************************* -->
 

<section name="modal">
      <div class="modal fade" id="payufailuremodal" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

              <div class="row">
                <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
              </div>

              <div class="row">
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <h2>Payment error!</h2>
                      

                    <h4 class="msg">It is not possible to extend your account. Please, try again</h4>
                
                    <h1><img src="<?php echo $pathprefix; ?>assets/images/failed.png" onclick="restpass()"></h1>

                    <button name="getstarted" class="getstarted payu"  id="payu" data-dismiss="modal">Try again</button>
                </div>
              </div>

            </div>
        </div>
      </div>
  </section>

 <!-- ******************** link sent Modal  ************************* -->
  <section name="modal">
      <div class="modal fade" id="payusuccessmodal" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

              <div class="row">
                <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
              </div>

              <div class="row">
                <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                    <h2>Successful payment !</h2>
                      

                    <h4 class="msg">Congratulations! You have extended your account usage period</h4>
                
                    <h1><img src="<?php echo $pathprefix; ?>assets/images/success.png" onclick="restpass()"></h1>

                    <button name="getstarted" class="getstarted myPatient"  id="resendbtn" data-dismiss="modal">Go to my patients</button>
                </div>
              </div>

            </div>
        </div>
      </div>
  </section>
  
   <!-- Free trial Modals -->
   <section name="modal">
      <div class="modal fade" id="myModel" role="dialog">
        <div class="modal-dialog" >
            <div class="modal-content successful-signup">

            <div class="row">
                  <button type="button" class="close" data-dismiss="modal"><img style="width: 45%;margin-right:6px;" src="<?php echo $pathprefix; ?>assets/images/close.png"></button>
            </div>

            <div class="row">
            <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
                <h2 id="trialHead">Get free 30-day trial</h2>

                <h4 id="trialMsg" class="msg" style="text-align: left;">Don't miss your chance to use the free 30 day trial, with an opportunity to open 50 cases during that period.<br>Get the best recommendations without referring your cases to experts. Using this software you become the expert yourself. Your patients will take up less of your time now so that you can become more productive. We maintain all your cases in one place. We make your job and practice extremely simple.You will save a lot of time and money using our software.</h4>
                <input type="hidden" name="trial" id="trial" value="1" >

                <button name="Get now" class="gotomailbtn freeTrial" data-dismiss="modal" id="gotomailbtn">Get now</button>
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
                <h2>30-day free trial is activated!</h2>

                <h4 class="msg">Congratulations. Your 30-day trial is activated now you can go ahead and add your first patient!</h4>
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
                <h2>Failed to activate 30-day trial</h2>

                <!-- <h4 class="msg">Congratulations. Your 30-day trial is activated now you can go ahead and add your first patient!</h4> -->
                <!-- <input type="hidden" name="" class="resendMsg" > -->
                <button name="Get now" class="gotomailbtn"  id="gotomailbtn">Try again</button>
                <!-- <input type="button" name="gotomail" class="gotomailbtn"  data-dismiss="modal" value="Go to Email" onclick="openMailBox()"> -->
            </div>
            </div>

            </div>
        </div>
      </div>
  </section>                      
        <!-- Free trial modal ends -->

</main> 
<?php include('../modals.php');  ?>
<?php  include('../footer.php'); ?>

<script src="../../assets/js/amcharts.js"></script>
<script src="../../assets/js/pie.js"></script>


<script src="../../assets/js/serial.js"></script>
<script src="../../assets/js/light.js"></script>
<!-- <script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
 -->
<!-- <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script> -->
<!-- <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" /> -->




<script>
var rooturl='<?php echo $rootUrl; ?>';

$('.payu').click(function(){
  $('#payuForm')[0].reset();
  $("#payufailuremodal").removeClass("fade").modal("hide");
  $('#payumodal').modal();
 
});
$(".dashboard").click(function(){    
    $('#infoModal').modal();
    $('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Your personal Dashboard. For all the latest updates and statistical data of  all your patients.</span></li></ul></div>')
  });

function promocodeAction(){
  $('.error-promocode').text('');
}

$('.freePlan').click(function(){
  $('#myModel').modal(); 
});
$('.freePlanExtend1').click(function(){ 
  $('#trial').val(2);
  $('#trialHead').text('Extend trial to 30 days');
  $('#trialMsg').text('Your getting the free trial for another 30 days');
  $('#myModel').modal(); 
});
$('.freePlanExtend2').click(function(){ 
  $('#trial').val(3);
  $('#trialHead').text('Extend trial to 30 days');
  $('#trialMsg').text('Your getting the free trial for another 30 days');
  $('#myModel').modal(); 
});


 $( window ).load(function() {
     var payuStatus='<?php echo $payuStatus; ?>';
//payuStatus='success';

if(payuStatus=='failure'){
    var newUrl = 'views/dashboard/'+refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
   $('#payufailuremodal').modal();

}else if(payuStatus=='success'){
   //alert("hii");
 var newUrl = 'views/dashboard/'+refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
  $('#payusuccessmodal').modal();

}else if(payuStatus=='payment'){
    var newUrl = 'views/dashboard/'+refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
  $('#payumodal').modal();
}else if(payuStatus=='freeTrial'){
  var newUrl = 'views/dashboard/'+refineUrl();
    window.history.pushState("object or string", "Title",rooturl+"/"+newUrl );
  $('#myModel').modal();
}

});


$('.freeTrial').click(function(){
  var plan_status= $('#trial').val();
  var user_id=<?php echo $userInfo['user_id'];?>;
    $.ajax({type: "POST",
            url:"../../controllers/paymentController.php",
            data:{user_id:user_id,
                  plan_status:plan_status,
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


function getAmount(ele){
  var plan_id=$(ele).val();
  var country_id=<?php echo $countryId; ?>;
  $('.error-duration').text("");
  $('.error-promocode').text('');
  $.ajax({type: "POST",
            url:"../../controllers/paymentController.php",
            data:{plan_id:plan_id,
                  country_id:country_id,
                  type:'getPlanPrice'},
            dataType:'json',
            beforeSend: function () {
              $('.priceload').show();
              $('.pricediv').hide();
              
            }
      })
      .done(function(data) {
        $('.priceload').hide();
        $('.pricediv').show();
            
        console.log(data);
        var amount="<p style='font-size:25px;color:black'>Total</p><div class=''><span class='currency_symbol'>"+data['currency_symbol']+"</span><span class='price'>"+data['price']+"</span></div>";
        $('.amount').html(amount);
        $('#amount').val(data['price']);
        $('#mainAmount').val(data['price']);
        $('#curr').val(data['currency_name']);
        $('#currency').val(data['region_id']);
        $('#region_id').val(data['region_id']);
         $('#region').val(data['region_name']);

      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        //alert("error");
        console.log('error');
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       });

}
/*TB2DIUW8*/
function getDiscount(){
  $('.error-promocode').text('');
  var promocode= $('.promocode').val();
  var user_id=<?php echo $userInfo['user_id']; ?>;
  var duration=$('#duration').val();
  var currency_symbol=$('.currency_symbol').text();
   //var price=$('.price').text();
   var price=$('#mainAmount').val();
 // alert(price);
  if(duration==null){
    $('.error-duration').text('Please select plan');
  }else if(promocode==""){
    $('.error-promocode').text('Please enter promocode.');
  }else{
    $.ajax({type: "POST",
            url:"../../controllers/paymentController.php",
            data:{promocode:promocode,
                  price:price,
                  type:'getDiscount'},
            dataType:'json',
            beforeSend: function () {
              $('.applybtn').hide();
              $('.applyload').show();
              $('.priceload').show();
              $('.pricediv').hide();
            }
      })
      .done(function(data) {
         $('.applybtn').show();
         $('.applyload').hide();
         $('.priceload').hide();
        $('.pricediv').show();
        console.log(data);
        if(data['errCode']==-1){
        /*Math.round(data['amount'])*/
        var amount="<div class='' style='font-size:20px;color:red'>Discount- <span class=''>"+currency_symbol+"</span><span class=''>"+data['discount']+"</span></div><p style='font-size:25px;color:black'>Total</p><div class=''><span class='currency_symbol'>"+currency_symbol+"</span><span class='price'>"+data['amount']+"</span></div>";
        $('.amount').html(amount);
         $('#discount').val(data['discount']);
         $('#amount').val(data['amount']);
         //alert(data['amount']);
       }else{
         $('.error-promocode').text(data['errMsg']);
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
  }
}


$(window).load(function() {
 // executes when complete page is fully loaded, including all frames, objects and images
 var emptyStatus="<?php echo $emptyStatus; ?>";
 if(emptyStatus!=0){
  //alert('hiii');
    displayWeeklyGraph('<?php echo $defaultMonthName1;?>','<?php echo $defaultYear; ?>','<?php echo $deafaultMonth; ?>','<?php echo $deafaultDate;?>');
  }else{
    //alert('else');
  }
});

 $('.myPatient').click(function(){
    window.location.href='../patient/manage_patient.php';
  });

var chart = AmCharts.makeChart("chartdiv", {
  "type": "pie",
  "labelRadius": -60,
  autoMargins: true,
  marginTop: 0,
  marginBottom: 0,
  marginLeft: 2,
  marginRight: 2,
  pullOutRadius: 0,
  "labelText": "[[percents]]%",
  "dataProvider": [{

    "country": "<?php echo $complaint4; ?>",
    "litres": '<?php echo $complaint4_percent; ?>'
  }, {
    "country": "<?php echo $complaint3; ?>",
    "litres": '<?php echo $complaint3_percent; ?>'
  }, {
    "country": "<?php echo $complaint2; ?>",
    "litres": '<?php echo $complaint2_percent; ?>'
  }, {
    "country": "<?php echo $complaint1; ?>",
    "litres": '<?php echo $complaint1_percent; ?>'

  }],
  "valueField": "litres",
  "titleField": "country"
});


// Areachart

function displayWeeklyGraph(monthname,year,month,date){
  //alert(year+'-'+month);
  $.ajax({type: "POST",
            url:"../../controllers/dashboardController.php",
            data:{year:year,
                  month:month,
                  date:date,
                  type:'display_graph'},
            dataType:'JSON',
            /*beforeSend: function () {
              $ele.find('.stopAccess').show();
            }*/
      })
      .done(function(data) {
        console.log(data);
dt=data['errMsg'];
  var chart = AmCharts.makeChart("areachart", {

    "type": "serial",
    "theme": "light",
    "marginRight": 40,
    "marginLeft": 40,
    "autoMarginOffset": 20,
    "mouseWheelZoomEnabled":true,

    "dataDateFormat": "YYYY-MM-DD",
    "valueAxes": [{
        "id": "v1",
        "axisAlpha": 0,
        "position": "left",
        "ignoreAxisWidth":false
    }],
    "balloon": {
        "borderThickness": 1,
        "shadowAlpha": 0
    },
    "graphs": [{
        "bulletSize": 14,
        "customBullet": "../../assets/images/dot.png",
        "customBulletField": "customBullet",
        "valueField": "value",
        "balloonText":"<div style='margin:10px; text-align:left;'><span style='font-size:13px'>[[category]]</span><br><span style='font-size:18px'>Value:[[value]]</span>",
    }],
    "chartScrollbar": {
        "graph": "g1",
        "oppositeAxis":false,
        "offset":30,
        "scrollbarHeight": 80,
        "backgroundAlpha": 0,
        "selectedBackgroundAlpha": 0.1,
        "selectedBackgroundColor": "#888888",
        "graphFillAlpha": 0,
        "graphLineAlpha": 0.5,
        "selectedGraphFillAlpha": 0,
        "selectedGraphLineAlpha": 1,
        "autoGridCount":true,
        "color":"#AAAAAA"
    },
    "chartCursor": {
        "pan": false,
        "valueLineEnabled": true,
        "valueLineBalloonEnabled": true,
        "cursorAlpha":1,
        "cursorColor":"#258cbb",
        "limitToGraph":"g1",
        "valueLineAlpha":0.2,
        "valueZoomable":false
    },
    // "valueScrollbar":{
    //   "oppositeAxis":false,
    //   "offset":50,
    //   "scrollbarHeight":10
    // },
    "categoryField": "date",
    "categoryAxis": {
        "parseDates": false,
        "dashLength": 1,
        "minorGridEnabled": false
    },

    "export": {
        "enabled": false
    },
     "responsive": {
    "enabled": true
  },
    "dataProvider": [{
        "date": "1-6 "+monthname,
        "value": dt[0]
    }, {
        "date": "7-13 "+monthname,
        "value": dt[1]
    }, {
        "date": "14-20 "+monthname,
        "value": dt[2]
    }, {
        "date": "21-27 "+monthname,
        "value": dt[3]
    }, {
        "date": "28-31 "+monthname,
        "value": dt[4]
    }]
});


      })    
      .fail(function(jqXHR, textStatus, errorThrown) {
        //alert("error");
        console.log('error');
        console.log(jqXHR.responseText);
       })  
       .error(function(jqXHR, textStatus, errorThrown) { 
        console.log(jqXHR.responseText);
       }) 

}



</script>
   <script src="../../assets/js/swiper.min.js"></script>
 <script>
    var swiper = new Swiper('.swiper-container', {


        pagination: '.swiper-pagination',
        slidesPerView: 4,
        paginationClickable: true,
        spaceBetween: 20,
        keyboardControl: true,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
         breakpoints: {
        1181: {
            slidesPerView: 4
        },
        1180: {
            slidesPerView: 3
        },
        1020: {
            slidesPerView: 3
        },
        700: {
            slidesPerView: 2
        }
    }
    });

//     $(window).resize(function(){
//       // alert();
//   var ww = $(window).width()
//   if (ww>1000){
//     var swiper = new Swiper('.swiper-container', {


//         pagination: '.swiper-pagination',
//         slidesPerView: 4,
//         paginationClickable: true,
//         spaceBetween: 20,
//         keyboardControl: true,
//         nextButton: '.swiper-button-next',
//         prevButton: '.swiper-button-prev',
//     });
//   }
//   if (ww>468 && ww<=1000){
//     var swiper = new Swiper('.swiper-container', {


//         pagination: '.swiper-pagination',
//         slidesPerView: 3,
//         paginationClickable: true,
//         spaceBetween: 20,
//         keyboardControl: true,
//         nextButton: '.swiper-button-next',
//         prevButton: '.swiper-button-prev',
//     });
//   }
//   if (ww<=468){
//     var swiper = new Swiper('.swiper-container', {


//         pagination: '.swiper-pagination',
//         slidesPerView: 2,
//         paginationClickable: true,
//         spaceBetween: 20,
//         keyboardControl: true,
//         nextButton: '.swiper-button-next',
//         prevButton: '.swiper-button-prev',
//     });
//   }
  
// })
// $(window).trigger('resize');
    </script>

    
       <script src="../../assets/js/amcharts.responsive.min.js"></script>
</body>
</html>
