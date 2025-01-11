<?php
//$activeHeader = "2opinion"; 
$pathPrefix="../";
$activeHeader = "doctorsArea"; 
session_start();
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php"); 
require_once("../../models/userModel.php");
require_once("../../controllers/notification.php");
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
  printArr("You do not have sufficient privileges to access this page");
  exit;
}

$tableName=getTableName($conn,'Notification_');
$tableName=$tableName['errMsg']['tableName'];
$tableName=array_reverse($tableName);
//printArr($tableName);


//printArr($notification);
//print_r (explode("_",$value['tableName']));


/*foreach ($notification as $key => $value) {
  $dateArray=explode("_",$value['tableName']);
  $monthNum  = $dateArray[1];
  $dateObj   = DateTime::createFromFormat('!m', $monthNum);
  $monthName = $dateObj->format('F');
  //printArr($value['errMsg']);
}*/
//printArr($notification['errMsg']);
/*if(noError($notification)){
  $totalPageNo=ceil($notification['countNotify']/10);
  $notification=$notification['errMsg'];

}else{
  $notification[0]="No Data";

}*/
//printArr($notification);




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
.alert-danger{
background color:#f99a9a;
}
.alert-success{
  background color:#87de87;
}
/*#setstatus .checked{
  add-attr(checked="true");
} */
    /*header{
      padding:7px 20px !important;
    }*/
  </style>
  <link rel="stylesheet" type="text/css" href="../../assets/css/home.css?aghrd=r4564298">


  <main class="container" style="min-height: 100%;">
    <?php  include_once("../header.php"); ?>
    <div class="row noleft-right" >
      <div class="col-md-5 col-sm-5 col-xs-10 managepatient" >
        <h2>Notifications<img src="../../assets/images/info.png" style="margin-left: 5px;vertical-align: super;" /></h2>
      </div>
    </div>

    <?php 
       // printArr($notificationdrop);
        if(isset($_GET['pageNo'])){
          $pageNo=$_GET['pageNo'];
        }else{
          $pageNo=1;
        }
        $count=0;
        $notification=getNotifcation($conn,$userInfo['user_email'],$pageNo,$limit=10);
        // printArr($notification['countNotify']);
         $totalPageNo=ceil($notification['countNotify']/10);
        getAllNotificationCount($conn,$userInfo['user_email']);
        $notification1=getNewNotifcation($conn,$userInfo['user_email'],$pageNo,$limit=10);
         
    ?>
    <div class="row noleft-right" >
     <?php /*foreach ($tableName as $key => $value) {
        $dateArray=explode("_",$value);
        $monthNum  = $dateArray[1];
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F');*/
        foreach ($notification1 as $key => $notificationDetails) {
        $dateArray=explode("_",$notificationDetails['tableName']);
        $monthNum  = $dateArray[1];
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F');

        //time_elapsed_string($date);
       // printArr($notificationDetails['errMsg']);
        if(!empty($value)){
          
      ?>
      <!-- <?php if ($count==0){echo $monthName; } ?> -->
      <div class="col-md-12 col-sm-12 col-xs-12 Notifications-div">

        <h4><?php echo $monthName; ?></h4>
          <div class="row" >
            <ul class="Notifications">
            <?php foreach ($notification['errMsg'] as $key => $value){ 
            $monthNum1  = date("m",strtotime($value['time_stamp']));
              $dateObj1   = DateTime::createFromFormat('!m', $monthNum1);
              $monthName1 = $dateObj1->format('F');
              if($monthName == $monthName1){
                $count=1;
                ?>
              <li>
                <div class="col-md-1 col-sm-2 col-xs-3">
                  <img src="../../assets/images/emily.png" class="img-circle">
                </div>
                <div class="col-md-11 col-sm-10 col-xs-9">
                  <h5><?php echo $value['message']; ?></h5>
                  <h6><?php echo  time_elapsed_string($value['time_stamp']); ?></h6>
                </div>
              </li>
              <?php }}?>
            </ul>
          </div>
      </div>
      <?php } $count=$count+10;/*if($count < $notificationDetails['countNotify']){
            break;
        }*/ }?>

  <div class="row noleft-right">
        <div class="col-md-12" style="text-align: centergit clone https://Zaidmir23@bitbucket.org/eheilung/eheilung-web.git;">
          <div class="pagination">

          <?php 
           if ($pageNo > 1) { ?>
                    <a href="viewNotifications.php?pageNo=<?php echo $pageNo - 1; ?>"><i class="fa fa-angle-double-left "></i></a>
          <?php }else if ($pageNo == 1 || $totalPageNo == 1) { ?>
                    <a style="opacity:0.5;"><i class="fa fa-angle-double-left "></i></a>
          <?php }
                if ($pageNo == 1) {
                    $startLoop = 1;
                    $endLoop = ($totalPageNo < 4) ? $totalPageNo : 4;
                } else if ($pageNo == $totalPageNo) {
                    $startLoop = (($totalPageNo - 4) < 1) ? 1 : ($totalPageNo - 4);
                    $endLoop = $totalPageNo;
                } else {
                    $startLoop = (($pageNo - 2) < 1) ? 1 : ($pageNo - 2);
                    $endLoop = (($pageNo + 2) > $totalPageNo) ? $totalPageNo : ($pageNo + 2);
                } 
                $i=0;
                for ($i = $startLoop; $i <= $endLoop; $i++) {
                    if ($i == $pageNo) { ?>
                        <a href="viewNotifications.php?pageNo=<?php echo ($pageNo) ; ?>" class="active"><?php echo $pageNo; ?></a>
           <?php    } else { ?>
                        <a href="viewNotifications.php?pageNo=<?php echo ($i) ; ?>"><?php echo $i; ?></a>
           <?php    }
                }

          ?>
            <?php if ($pageNo < $totalPageNo) {  ?>          
                    <a href="viewNotifications.php?pageNo=<?php echo $pageNo + 1; ?>"><i class="fa fa-angle-double-right"></i></a>
                    &nbsp;
            <?php }else if($pageNo == $totalPageNo){ ?>
                   <a style="opacity:0.5;"><i class="fa fa-angle-double-right"></i></a>
                   &nbsp; 
            <?php } ?>
          </div>    
        </div>
    </div>
   


 


</main> 
<?php  include('..//footer.php'); ?>

</body>
</html>
