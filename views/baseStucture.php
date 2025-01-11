<?php

session_start();

require_once("../utilities/config.php");
require_once("../models/commonModel.php");
require_once("../utilities/dbutils.php");

  // require_once("../controllers/helper.php");

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

$query = "SELECT name FROM country where id=1";
        $result = runQuery($query,$conn);

$row=mysqli_fetch_assoc($result["dbResource"]);
printArr($row['name']);
die;
 function demo($conn)
    {
      global $totalQuestions;
      $query="SELECT * FROM demo";
      $result=runQuery($query,$conn); 
      if(noError($result)){
        $res = array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res[]=$row;
        }
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      }else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching complaints data";
      }

      return $returnArr;
    }

 function demo1($conn,$name)
    {
      global $totalQuestions;
      $res = array();
     $query="SELECT * FROM systems_priority WHERE diagnosis='".stripcslashes($name)."'";
      $result=runQuery($query,$conn); 
      if(noError($result)){
        
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res=$row;
          //array_push($res, $row);
           //printArr($res);
        }
      
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      }else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching complaints data";
      }
//printArr($returnArr);
      return $res;
    }
   // query for utf encode
//UPDATE ohp_posts SET post_content = CONVERT(CAST(CONVERT(post_content USING latin1) AS BINARY) USING utf8)
/*
 function demo2($conn,$name)
    {
      global $totalQuestions;
      $query="SELECT * FROM systems_priority";
      $result=runQuery($query,$conn); 
      if(noError($result)){
        $res = array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res[$row['priority_id']]=$row;
        }
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      }else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching complaints data";
      }

      return $returnArr;
    }*/
    //trim($s, " ");
   /* $check=demo($conn);
     $check2=demo2($conn);*/
     foreach ($check2['errMsg'] as $key => $value) {
       # code...
    /*  echo $updateQuery = "UPDATE systems_priority SET diagnosis='".trim(cleanQueryParameter($conn,utf8_encode(cleanXSS($value['diagnosis']))),' ')."' ,system='".cleanQueryParameter($conn,utf8_encode($value['system']))."',organ='".cleanQueryParameter($conn,utf8_encode($value['organ']))."',subOrgan='".cleanQueryParameter($conn,utf8_encode($value['subOrgan']))."',embryologcial='".cleanQueryParameter($conn,utf8_encode($value['embryologcial']))."',miasm='".cleanQueryParameter($conn,utf8_encode($value['miasm']))."' WHERE priority_id=".$key;
      $result = runQuery($updateQuery, $conn);
       printArr($result);*/
     }
     

    //printArr($check2);
/*    foreach ($check['errMsg'] as $key => $value) {
      # code...
     $commonName=explode(",",$value['Common_name']);
     $dignos=(cleanQueryParameter($conn,utf8_encode($value['Diagnostic_term'])));
     $def=cleanQueryParameter($conn,utf8_encode($value['Definition']));
     $ex=cleanQueryParameter($conn,utf8_encode($value['Expert_Comments']));
     $ps=cleanQueryParameter($conn,utf8_encode($value['Possibility']));
     $cta=cleanQueryParameter($conn,utf8_encode($value['CTA']));
     $dur=cleanQueryParameter($conn,utf8_encode($value['Duration']));
     $ir=cleanQueryParameter($conn,utf8_encode($value['Improvement_rate']));
     //printArr($commonName);
     $check1=demo1($conn,$dignos);
     //printArr($check1);
     $sys=cleanQueryParameter($conn,utf8_encode($check1['system']));
     $org=cleanQueryParameter($conn,utf8_encode($check1['organ']));
     $sorg=cleanQueryParameter($conn,utf8_encode($check1['subOrgan']));
     $em=cleanQueryParameter($conn,utf8_encode($check1['embryologcial']));
     $mis=cleanQueryParameter($conn,utf8_encode($check1['miasm']));

      sizeof($commonName);
     for($i=0;$i<=sizeof($commonName);$i++){
       if(!empty($commonName[$i])){
          $cmn=cleanQueryParameter($conn,utf8_encode($commonName[$i]));
          $insertQuery = "INSERT INTO `demo1` (`priority_id`,`Diagnostic_term`,`Definition`, `Common_name`, `Expert_Comments`, `Possibility`,`CTA`, `Duration`, `Improvement_rate`, `system`, `organ`, `subOrgan`, `embryologcial`, `miasm`) VALUES (".$value['id'].",'".$dignos."', '".$def."', '".$cmn."', '".$ex."','".$ps."','".$cta."','".$dur."','".$ir."','".$sys."','".$org."','".$sorg."','".$em."','".$mis."')";
            $result = runQuery($insertQuery, $conn);
            printArr($result);

       }
     }
      cleanQueryParameter($conn,utf8_encode($value['Diagnostic_term']));
     foreach ($commonName as $key => $value) {
       # code...
     }
    }
die;*/
 /*   foreach ($check['errMsg'] as $key => $value) {
      # code...
     //echo  $value['Common_name'];
  echo  $priority_id=$value['main_complaint_id'];
    echo "<br>";
     $main_complaint_name=cleanQueryParameter($conn,utf8_encode($value['main_complaint_name']));
      $Definition=cleanQueryParameter($conn,utf8_encode($value['Definition']));
   $Common_name=cleanQueryParameter($conn,utf8_encode($value['Common_name']));
        $Expert_Comments=cleanQueryParameter($conn,utf8_encode($value['Expert_Comments']));
         $Duration=cleanQueryParameter($conn,utf8_encode($value['Duration']));
         $Improvement_rate=cleanQueryParameter($conn,utf8_encode($value['Improvement_rate']));
         $Possibility=cleanQueryParameter($conn,utf8_encode($value['Possibility']));
         $CTA=cleanQueryParameter($conn,utf8_encode($value['CTA']));

   echo $updateQuery = "UPDATE complaint_info SET priority_id='".$priority_id."' WHERE Diagnostic_term='".$main_complaint_name."'";
      echo "<br>";
       $result = runQuery($updateQuery, $conn);
    }
    */



$title="knowledgeCenter";
$pathPrefix="../";


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once("metaInclude.php"); ?>
  <style type="text/css">
  .OverlayText{
    bottom: 25%;
    color: #fff;
    left: 15%;
    position: absolute;
    width: 70%;
    text-align: center;
}
.row{
}
img{
    max-height: 300px;
    min-height: 300px;
    object-fit: cover;
    width:100%;
}
.wordpress-data{
  margin-bottom: 30px;
}
.type{
  color:#ff9900;
}
.title{
  font-weight: 300;
  margin-top: 0px;
}
.OverlayMask
{
  background-color: transparent;
  height:250px;
}
.img-overlay{ 
background-color:#000;
height:300px;
margin-top : -300px;
opacity : 0.3;
}

  </style>
  </head>
  <body>
  <main class="container" style="min-height: 100%;">
    <?php  include_once("header.php"); ?> 
    <section>
      <!-- main-container-->
      <div class="main-container">
        <div class="row">
          <div class="col-md-12" style="text-align: center;margin-top:80px;" >
            <!-- <h4 id="errMsg" style="color:red;"></h4> -->
             <input type="button" name="" id="" class="submit-btn" value="SUBMIT">
          </div>
        </div>
      </div>
      <!-- main-container-->
    </section>
  </main> 
  <?php include('footer.php'); ?>
  <!-- footer-->

  <script type="text/javascript">
  $('.submit-btn').click(function (){
                   window.location.href='index.php';
                  });
  </script>
  </body>
  </html>