<?php
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
session_start();
//prepare for request  
require_once($pathprefix."utilities/config.php");
require_once($pathprefix."utilities/dbutils.php");
require_once($pathprefix."utilities/authentication.php");
include($pathprefix."models/completeProfileModel.php");
include($pathprefix."models/notificationModel.php");
include($pathprefix."models/logsModel.php");
require_once($pathprefix."logs/xmlProcessor/xmlProcessor.php");

$logStorePath =$logPath["userProfile"];
$userEmail=$_SESSION["user"];
//for xml writing essential
$xmlProcessor = new xmlProcessor();
$xmlfilename = "completeProfile.xml";

/* Log initialization start */
$xmlArray = initializeXMLLog($userEmail);

$xml_data['request']["data"]='';
$xml_data['request']["attribute"]=$xmlArray["request"];


$msg = "User registration process start.";
$xml_data['step'.$i]["data"] = $i.". {$msg}"; 

//database connection
$conn = createDbConnection($servername, $username, $password, $dbname);
$user = "";
$returnArr=array();
$userInfo=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
  $msg = "Success : Search database connection";
  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
} else {
	printArr("Database Error");
	exit;
}
  /*printArr($_POST);
  printArr($_FILES);
 parse_str($_POST['formdata'],$_POST);*/

//accept request
if((isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)) || (isset($_POST["user"]) && !in_array($_POST["user"], $blanks))){
  if((isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)))
    $user = $_SESSION["user"];
    $user_type=$_SESSION["user_type"];
    $userMob=$_SESSION["userInfo"]['user_mob'];
    $msg = "Manadatory parameter passed";
    $xml_data['step'.++$i]["data"] = $i.". {$msg}";
     
   
    /*$user = $_POST["user"];
    $user_type=$_POST["user_type"];
    $userMob=$_POST['user_mob'];*/
    //$pic=$_POST['user_image'];
  //validtation for madatory parameters
  if(isset($_POST) && !empty($_POST)){
    $type=cleanQueryParameter($conn,cleanXSS($_POST['type']));
    if($type=='getState')
    {
        $country_id=$_POST['cId'];
      //echo $link;
      $check=getAllStates($conn,$country_id);
       $returnArr["errCode"] =1 ;
       $returnArr["errMsg"] = $check;
      //echo json_de($check);
    }
    if($type=='getCity')
    {
        $state_id=$_POST['sId'];
      //echo $link;
      $check=getAllCities($conn,$state_id);
      $returnArr["errCode"] =1 ;
      $returnArr["errMsg"] = $check;
      //echo json_encode($check);
    }
    $subject="Profile edited successfully";
    $from = "eHeilung <donotreply@eheilung.com>";
    $userInfo['user_type']=$user_type;
    $userInfo['user_email']=$user;
    $formName=cleanQueryParameter($conn,cleanXSS($_POST['form_type']));
    if($formName=='general'){      
      $email=$userInfo['user_email']; 
      /*$profilePicTempFileName = $_FILES['profile_pic']['tmp_name'];
      $ext = end((explode(".", $_FILES['profile_pic']['name']))); # extra () to prevent notice
      $pic = $email."_profile_pic".rand().".".$ext;
                
      $target_path = "../../assets/uploads/";
      $tp = "../../assets/uploads/";
      if(!is_dir($target_path)) mkdir($target_path);
      $uploadfile = $target_path . basename($pic);
      //Move the uploaded file to $target_path            
      $moved = move_uploaded_file($profilePicTempFileName, $uploadfile);
      if($_FILES["profile_pic"]["error"] == 4) {
        $pic = "";
      }else{
        $pic = $tp.$pic;
      }
      if((isset($_POST["user"]) && !in_array($_POST["user"], $blanks))){
        if((isset($_POST["img"]) && !in_array($_POST["img"], $blanks))){  
          $data1 = $_POST['img']; 
          //echo $data1;

          //$data1  = substr($data1,9);
          $ext = $_POST['ext'];
         // echo $ext;
          $data1= str_replace('data:image/png;base64,', '', $data1);
          $data1 = str_replace(' ', '+', $data1);
          //echo $data1;
          $data1 = base64_decode($data1);
          $pic = $email."_profile_pic".rand().".".$ext;
          $success = file_put_contents($target_path.$pic, $data1);  
          
         // $pic = $target_path.$pic;

        }else{
          $pic = $userInfo['user_image'];
        }
      }*/

    $path = "../assets/uploads/";
    $pic="";
    $valid_formats = array("jpg", "png", "gif", "bmp");
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
        {
            $msg='image upload success post';
            $returnArr["errCode"] =1 ;
            $returnArr["errMsg"] = $msg;
            $name = $_FILES['profile_pic']['name'];
            $size = $_FILES['profile_pic']['size'];
            
            if(strlen($name))
                {
                    list($txt, $ext) = explode(".", $name);
                    if(in_array($ext,$valid_formats))
                    {
                    if($size<(1024*1024))
                        {
                            $actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
                            $tmp = $_FILES['profile_pic']['tmp_name'];
                            if(move_uploaded_file($tmp, $path.$actual_image_name))
                                {
                                  
                               /* mysqli_query($conn,"UPDATE admin_contact_doctor SET image='$actual_image_name' WHERE doctorId=".$_POST['userId']);
                                    
                                    echo "<img style='height:100px;'' src='../../assets/uploads/".$actual_image_name."'  class='preview'>";*/
                                    $pic=$rootUrl.'/assets/uploads/'.$actual_image_name;
                                    $msg='image upload success';
                                    $returnArr["errCode"] =1 ;
                                    $returnArr["errMsg"] = $msg;
                                }
                            else
                               $msg="failed image upload";
                                $returnArr["errCode"] =5 ;
                                $returnArr["errMsg"] = $msg;
                        }
                        else
                        $msg="Image file size max 1 MB";
                        $returnArr["errCode"] =5 ;
                        $returnArr["errMsg"] = $msg;                    
                        }
                        else
                        $msg="Invalid file format..";  
                        $returnArr["errCode"] =5 ;
                        $returnArr["errMsg"] = $msg; 
                }
                
            else
                $msg="Please select image..!";
                $returnArr["errCode"] =5 ;
                $returnArr["errMsg"] = $msg;
              
        }else{
           $msg='image upload failed post';
            $returnArr["errCode"] =1 ;
            $returnArr["errMsg"] = $msg;
        }

      //$userInfo["firstUpdate"]=cleanQueryParameter($conn,cleanXSS($_POST["firstUpdate"]));
      $userInfo['user_first_name']=cleanQueryParameter($conn,cleanXSS($_POST["user_first_name"]));
      $userInfo['user_last_name']=cleanQueryParameter($conn,cleanXSS($_POST["user_last_name"]));
      $userInfo['user_reg_no']=cleanQueryParameter($conn,cleanXSS($_POST["user_reg_no"]));
      $userInfo['user_gender']=cleanQueryParameter($conn,cleanXSS($_POST["user_gender"]));
      $userInfo['title']=cleanQueryParameter($conn,cleanXSS($_POST["title"]));
      $userInfo['user_nationality']=cleanQueryParameter($conn,cleanXSS($_POST["user_nationality"]));
      $userInfo['user_marital_status']=cleanQueryParameter($conn,cleanXSS($_POST["user_marital_status"]));
      $userInfo['height']=cleanQueryParameter($conn,cleanXSS($_POST["height"]));
      $userInfo['weight']=cleanQueryParameter($conn,cleanXSS($_POST["weight"]));
      $userInfo['height_unit']=cleanQueryParameter($conn,cleanXSS($_POST["height_unit"]));
      $userInfo['weight_unit']=cleanQueryParameter($conn,cleanXSS($_POST["weight_unit"]));
      $userInfo['user_dob']=cleanQueryParameter($conn,cleanXSS($_POST["user_dob"]));
      $userInfo['highest_degree']=cleanQueryParameter($conn,cleanXSS($_POST["highest_degree"]));
      $userInfo['user_image']=$pic;
      if(!empty($userInfo['user_gender']) && !empty($userInfo['user_dob'])){
        $editProfile=editGeneralProfileInfo($userInfo, $conn);
        if(noError($editProfile)){
          $message = "Profile edited successfully ".$user;
          $msg = "General profile edited successfully";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          //$sendNotification=sendMail($user, $from, $subject, $message);
          //$sendNotification=sendNotifications($conn, 3, $user, $from, $message, $subject, $userMob);
         //if(noError($sendNotification)){
            $returnArr["errCode"] =-1 ;
            $returnArr["errMsg"] = $msg;
            $msg = "Notification sent";
            $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          /*}else{
            $msg = "Failed to send notification";
            $xml_data['step'.++$i]["data"] = $i.". {$msg}";
            $returnArr["errCode"] =5 ;
            $returnArr["errMsg"] = $msg;
          }*/
        }else{
          $msg = "Error updating profile. Please try again";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          $returnArr["errCode"] =4 ;
          $returnArr["errMsg"] = $msg;
        }
      }else{
        $msg="Mandatory Parameters not passed";
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =3 ;
        $returnArr["errMsg"] = $msg;
      }
    }else if($formName=='contact'){      
      $userInfo["firstUpdate"]=cleanQueryParameter($conn,cleanXSS($_POST["firstUpdate"]));
      $userInfo['user_address']=cleanQueryParameter($conn,cleanXSS($_POST["user_address"]));
      $userInfo['user_country']=cleanQueryParameter($conn,cleanXSS($_POST["user_country"]));
      $userInfo['user_state']=cleanQueryParameter($conn,cleanXSS($_POST["user_state"]));
      $userInfo['user_city']=cleanQueryParameter($conn,cleanXSS($_POST["user_city"]));
      $userInfo['user_zip']=cleanQueryParameter($conn,cleanXSS($_POST["user_zip"]));
      $userInfo['user_mob']=cleanQueryParameter($conn,cleanXSS($_POST["user_mob"]));
      $userInfo['country_code']=cleanQueryParameter($conn,cleanXSS($_POST["country_code"]));
      $userInfo['user_landline_no']=cleanQueryParameter($conn,cleanXSS($_POST["user_landline_no"]));
      $userInfo['user_alt_email']=cleanQueryParameter($conn,cleanXSS($_POST["user_alt_email"]));
      $editProfile=editContactProfileInfo($userInfo, $conn);
      if(noError($editProfile)){
        $message = "Profile edited successfully ".$user;
        $msg = "Contact profile edited successfully";
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        //$sendNotification=sendMail($user, $from, $subject, $message);
        //$sendNotification=sendNotifications($conn, 3, $user, $from, $message, $subject, $userMob);
        //if(noError($sendNotification)){
          $returnArr["errCode"] =-1 ;
          $returnArr["errMsg"] = $msg;
          $msg = "Notification sent";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        /*}else{
          $msg = "Failed to send notification";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          $returnArr["errCode"] =5 ;
          $returnArr["errMsg"] = $msg;
        }*/
      }else{
        $msg = "Error updating profile. Please try again";
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =4 ;
        $returnArr["errMsg"] = $msg;
      }
    }
  }else{
    $msg="Parameters not passed";
    $xml_data['step'.++$i]["data"] = $i.". {$msg}";
    $returnArr["errCode"] =2 ;
    $returnArr["errMsg"] = $msg;

  }
}else{
  $msg="You need to login first to access this page";
  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
  $returnArr["errCode"] =1 ;
  $returnArr["errMsg"] = $msg;
}


//printArr($returnArr);

// create or update xml log Files
//$xmlProcessor->writeXML($xmlfilename, $logStorePath, $xml_data, $xmlArray["activity"]);
echo json_encode($returnArr);

?>