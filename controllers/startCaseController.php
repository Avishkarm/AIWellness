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
include($pathprefix."models/startCaseModel.php");
include($pathprefix."models/notificationModel.php");
include($pathprefix."models/logsModel.php");
include($pathprefix."models/userModel.php");
require_once($pathprefix."logs/xmlProcessor/xmlProcessor.php");

$logStorePath =$logPath["userProfile"];
$userEmail=$_POST['user_email'];
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
  //printArr($_SESSION);die;
 /* printArr($_FILES);
 parse_str($_POST['formdata'],$_POST);*/

//accept request
if((isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)) || (isset($_POST["user"]) && !in_array($_POST["user"], $blanks))){
  if((isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)))
    $user = $_SESSION["user"];
    $user_type=$_SESSION["user_type"];
    $userMob=$_SESSION["userInfo"]['user_mob'];
    $msg = "Manadatory parameter passed";
    $xml_data['step'.++$i]["data"] = $i.". {$msg}";
    /*if($_POST['type']=='step1'){
        $userInfo['case_id']=cleanQueryParameter($conn,cleanXSS($_POST["case_id"]));
        $userInfo['step_no']=cleanQueryParameter($conn,cleanXSS($_POST["step_no"])); 
        $addStepNo=updateStepNo($userInfo['case_id'],$userInfo['step_no'],$conn);
        if(noError($addStepNo)){
          $returnArr['errCode']=-1;
          $returnArr['errMsg']=$addStepNo['errMsg'];
        }else{
         $returnArr['errCode']=1;
         $returnArr['errMsg']="Failed to add stepno";
        }
        $msg=$addStepNo['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";

    }else */if($_POST['type']=='step1' || $_POST['type']=='step2' || $_POST['type']=='step3' || $_POST['type']=='step4' || $_POST['type']=='step6'){
        $userInfo['case_id']=cleanQueryParameter($conn,cleanXSS($_POST["case_id"]));
        $userInfo['step_no']=cleanQueryParameter($conn,cleanXSS($_POST["step_no"])); 
        //printArr($_POST);
        $addStepNo=updateStepNo($userInfo['case_id'],$userInfo['step_no'],$conn);
       // printArr();
        if(noError($addStepNo)){
          $returnArr['errCode']=-1;
          $returnArr['errMsg']=$addStepNo['errMsg'];
        }else{
         $returnArr['errCode']=1;
         $returnArr['errMsg']="Failed to add stepno";
        }
     
        $msg=$addStepNo['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        /*$returnArr["errCode"] =-1 ;
        $returnArr["errMsg"] = $msg;*/     
    }else if($_POST['type']=='textans_step1'){
      $column_name=$_POST['column_name'];
      $val=$_POST['val'];
      $patient_id=$_POST['patient_id'];
      $val=$_POST['val'];
      $udateUserInfoStep1=udateUserInfoStep1($column_name,$val,$patient_id,$conn);
      if(noError($udateUserInfoStep1)){
        $msg=$udateUserInfoStep1['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =-1 ;
        $returnArr["errMsg"] = $msg;
      }else{
        $msg=$udateUserInfoStep1['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =1 ;
        $returnArr["errMsg"] = $msg;
      }
    }else if($_POST['type']=='textans_steps'){
      $column_name=$_POST['column_name'];
      $val=$_POST['val'];
      $case_id=$_POST['case_id'];
      //printArr($_POST);

      $udateStepsCaseInfo=udateStepsCaseInfo($column_name,$val,$case_id,$conn);
      if(noError($udateStepsCaseInfo)){
        $msg=$udateStepsCaseInfo['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =-1 ;
        $returnArr["errMsg"] = $msg;
      }else{
        $msg=$udateStepsCaseInfo['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =1 ;
        $returnArr["errMsg"] = $msg;
      }
    }else if($_POST['type']=='saveComplaint'){
      $column_name=$_POST['column_name'];
      $complaintName="";
      $val=implode(",",$_POST['val']);
      $case_id=$_POST['case_id'];
      //printArr($val);
      /*foreach ($_POST['val'] as $key => $value) {
        echo $key;
        $getComplaintDetails[$key]=getComplaintDetailsById($complaint,$conn)
      }*/
      
      $udateStepsCaseInfo=udateStepsCaseInfo($column_name,$val,$case_id,$conn);
      if(noError($udateStepsCaseInfo)){
        $msg=$udateStepsCaseInfo['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =-1 ;
        $returnArr["errMsg"] = $msg;
      }else{
        $msg=$udateStepsCaseInfo['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =1 ;
        $returnArr["errMsg"] = $msg;
      }
    }/*else if($_POST['type']=='step2'){

      $path = "../assets/uploads/";
      $pic="";
      $valid_formats = array("jpg", "png", "gif", "bmp", 'pdf');
      if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
          {
              $msg='image upload success post';
              $returnArr["errCode"] =1 ;
              $returnArr["errMsg"] = $msg;
              $name = $_FILES['path_pros_image']['name'];
              $size = $_FILES['path_pros_image']['size'];
              
              if(strlen($name))
                  {
                      list($txt, $ext) = explode(".", $name);
                      if(in_array($ext,$valid_formats))
                      {
                      if($size<(1024*1024))
                          {
                              $actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
                              $tmp = $_FILES['path_pros_image']['tmp_name'];
                              if(move_uploaded_file($tmp, $path.$actual_image_name))
                                  {
                                    
                                 //mysqli_query($conn,"UPDATE admin_contact_doctor SET image='$actual_image_name' WHERE doctorId=".$_POST['userId']);
                                      
                                      //echo "<img style='height:100px;'' src='../../assets/uploads/".$actual_image_name."' class='preview'>";
                                      $pic=$actual_image_name;
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
          $userInfo['case_id']=cleanQueryParameter($conn,cleanXSS($_POST["case_id"])); 
            $addStepNo=updateStepNo($userInfo['case_id'],2,$conn);
            if(noError($addStepNo)){
              $returnArr['errCode']=-1;
              $returnArr['errMsg']=$addStepNo['errMsg'];
            }else{
             $returnArr['errCode']=1;
             $returnArr['errMsg']="Failed to add stepno";
            }
            $msg=$addStep1Form['errMsg'];
            $xml_data['step'.++$i]["data"] = $i.". {$msg}";

    }*/else if($_POST['type']=='ans_step3' || $_POST['type']=='ans_step4' || $_POST['type']=='ans_step5' || $_POST['type']=='ans_step6'){
      $ansDetails['case_id']=$_POST['case_id'];
      $ansDetails['patient_id']=$_POST['patient_id'];
      $ansDetails['answerRemedies']=$_POST['answerRemedies'];
      $ansDetails['q_id']=$_POST['q_id'];
      $ansDetails['a_id']=$_POST['a_id'];
      $ansDetails['ans_label']=$_POST['ans_label'];
      $updateStep3Ans=updateStepAns($ansDetails,$conn);
      if(noError($updateStep3Ans)){
        $msg=$updateStep3Ans['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =-1 ;
        $returnArr["errMsg"] = $msg;
      }else{
        $msg=$updateStep3Ans['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =1 ;
        $returnArr["errMsg"] = $msg;
      }
    }else if($_POST['type']=='step5'){
      $path = "../assets/uploads/";
      $close_img_name="";
      $full_img_name="";
      $nose_name="";
      $nails_name="";
      $fingers_name="";
      $toes_name="";
      $valid_formats = array("jpg", "png", "gif", "bmp", 'pdf');
      if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
      {
              $msg='image upload success post';
              $returnArr["errCode"] =1 ;
              $returnArr["errMsg"] = $msg;
              $close_img = $_FILES['close_img']['name'];
              $close_img_size = $_FILES['close_img']['size'];
              $full_img = $_FILES['full_img']['name'];
              $full_img_size = $_FILES['full_img']['size'];
              $nose = $_FILES['nose']['name'];
              $nose_size = $_FILES['nose']['size'];
              $nails = $_FILES['nails']['name'];
              $nails_size = $_FILES['nails']['size'];
              $fingers = $_FILES['fingers']['name'];
              $fingers_size = $_FILES['fingers']['size'];
              $toes = $_FILES['toes']['name'];
              $toes_size = $_FILES['toes']['size'];
              if(strlen($close_img))
                  {
                      list($txt, $ext) = explode(".", $close_img);
                      if(in_array($ext,$valid_formats))
                      {
                          if($close_img_size<(1024*1024))
                          {
                              $actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
                              $tmp = $_FILES['close_img']['tmp_name'];
                              if(move_uploaded_file($tmp, $path.$actual_image_name))
                                  {
                                      $close_img_name=$actual_image_name;
                                      $msg='image upload success';
                                      $returnArr["errCode"] =1 ;
                                      $returnArr["errMsg"] = $msg;
                                  }
                              else{
                                 $msg="failed image upload1";
                                  $returnArr["errCode"] =5 ;
                                  $returnArr["errMsg"] = $msg;
                                }
                          }
                          else{
                          $msg="Image file size max 1 MB";
                          $returnArr["errCode"] =5 ;
                          $returnArr["errMsg"] = $msg; 
                          }                   
                      }else{
                          $msg="Invalid file format..";  
                          $returnArr["errCode"] =5 ;
                          $returnArr["errMsg"] = $msg; 
                      }    
                  }else{
                  $msg="Please select image..!";
                  $returnArr["errCode"] =5 ;
                  $returnArr["errMsg"] = $msg;
                  }
                  if(strlen($full_img))
                  {
                      list($txt, $ext) = explode(".", $full_img);
                      if(in_array($ext,$valid_formats))
                      {
                      if($full_img_size<(1024*1024))
                          {
                              $actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
                              $tmp = $_FILES['full_img']['tmp_name'];
                              if(move_uploaded_file($tmp, $path.$actual_image_name))
                                  {
                                      $full_img_name=$actual_image_name;
                                      $msg='image upload success';
                                      $returnArr["errCode"] =1 ;
                                      $returnArr["errMsg"] = $msg;
                                  }
                              else
                                 $msg="failed image upload2";
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
                  }else{
                  $msg="Please select image..!";
                  $returnArr["errCode"] =5 ;
                  $returnArr["errMsg"] = $msg;
                  }
                  if(strlen($nose))
                  {
                      list($txt, $ext) = explode(".", $nose);
                      if(in_array($ext,$valid_formats))
                      {
                      if($nose_size<(1024*1024))
                          {
                              $actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
                              $tmp = $_FILES['nose']['tmp_name'];
                              if(move_uploaded_file($tmp, $path.$actual_image_name))
                                  {
                                      $nose_name=$actual_image_name;
                                      $msg='image upload success';
                                      $returnArr["errCode"] =1 ;
                                      $returnArr["errMsg"] = $msg;
                                  }
                              else
                                 $msg="failed image upload3";
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
                  }else{
                  $msg="Please select image..!";
                  $returnArr["errCode"] =5 ;
                  $returnArr["errMsg"] = $msg;
                  }
                  if(strlen($nails))
                  {
                      list($txt, $ext) = explode(".", $nails);
                      if(in_array($ext,$valid_formats))
                      {
                      if($nails_size<(1024*1024))
                          {
                              $actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
                              $tmp = $_FILES['nails']['tmp_name'];
                              if(move_uploaded_file($tmp, $path.$actual_image_name))
                                  {
                                      $nails_name=$actual_image_name;
                                      $msg='image upload success';
                                      $returnArr["errCode"] =1 ;
                                      $returnArr["errMsg"] = $msg;
                                  }
                              else
                                 $msg="failed image upload4";
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
                  }else{
                  $msg="Please select image..!";
                  $returnArr["errCode"] =5 ;
                  $returnArr["errMsg"] = $msg;
                  }
                  if(strlen($fingers))
                  {
                      list($txt, $ext) = explode(".", $fingers);
                      if(in_array($ext,$valid_formats))
                      {
                      if($fingers_size<(1024*1024))
                          {
                              $actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
                              $tmp = $_FILES['fingers']['tmp_name'];
                              if(move_uploaded_file($tmp, $path.$actual_image_name))
                                  {
                                      $fingers_name=$actual_image_name;
                                      $msg='image upload success';
                                      $returnArr["errCode"] =1 ;
                                      $returnArr["errMsg"] = $msg;
                                  }
                              else
                                 $msg="failed image upload5";
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
                  }else{
                  $msg="Please select image..!";
                  $returnArr["errCode"] =5 ;
                  $returnArr["errMsg"] = $msg;
                  }
                  if(strlen($toes))
                  {
                      list($txt, $ext) = explode(".", $toes);
                      if(in_array($ext,$valid_formats))
                      {
                      if($toes_size<(1024*1024))
                          {
                              $actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
                              $tmp = $_FILES['toes']['tmp_name'];
                              if(move_uploaded_file($tmp, $path.$actual_image_name))
                                  {
                                      $toes_name=$actual_image_name;
                                      $msg='image upload success';
                                      $returnArr["errCode"] =1 ;
                                      $returnArr["errMsg"] = $msg;
                                  }
                              else
                                 $msg="failed image upload6";
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
                  }else{
                  $msg="Please select image..!";
                  $returnArr["errCode"] =5 ;
                  $returnArr["errMsg"] = $msg;
                  }
      }else{
         $msg='image upload failed post';
          $returnArr["errCode"] =1 ;
          $returnArr["errMsg"] = $msg;
      }
      $userInfo['case_id']=cleanQueryParameter($conn,cleanXSS($_POST["case_id"]));
      $userInfo['step_no']=cleanQueryParameter($conn,cleanXSS($_POST["step_no"])); 
      $userInfo["face_type"]=cleanQueryParameter($conn,cleanXSS($_POST["face_type"]));
      $userInfo['height']=cleanQueryParameter($conn,cleanXSS($_POST["height"]));
      $userInfo['height_unit']=cleanQueryParameter($conn,cleanXSS($_POST["height_unit"]));
      $userInfo['feel_strange_mind_body']=cleanQueryParameter($conn,cleanXSS($_POST["feel_strange_mind_body"]));
      $userInfo['material_attach']=cleanQueryParameter($conn,cleanXSS($_POST["material_attach"]));
      $userInfo['friends_attach']=cleanQueryParameter($conn,cleanXSS($_POST["friends_attach"]));
      $userInfo['family_attach']=cleanQueryParameter($conn,cleanXSS($_POST["family_attach"]));
      $userInfo['colleagues_attach']=cleanQueryParameter($conn,cleanXSS($_POST["colleagues_attach"]));
      $userInfo['people_attach']=cleanQueryParameter($conn,cleanXSS($_POST["people_attach"]));
      $userInfo['describe_yourself']=cleanQueryParameter($conn,cleanXSS($_POST["describe_yourself"]));
      $userInfo['mental_status']=cleanQueryParameter($conn,cleanXSS($_POST["mental_status"]));
      $userInfo['close_img']=$close_img_name;
      $userInfo['full_img']=$full_img_name;
      $userInfo['nose']=$nose_name;
      $userInfo['nails']=$nails_name;
      $userInfo['fingers']=$fingers_name;
      $userInfo['toes']=$toes_name;
      $step5CaseData=json_encode($userInfo);
      $addStep5FormInfo=addStep5FormInfo($userInfo,$step5CaseData,$conn);
      if(noError($addStep5FormInfo)){
        $addStepNo=updateStepNo($userInfo['case_id'],5,$conn);
        if(noError($addStepNo)){
          $returnArr['errCode']=-1;
          $returnArr['errMsg']=$addStepNo['errMsg'];
        }else{
         $returnArr['errCode']=1;
         $returnArr['errMsg']="Failed to add stepno";
        }
        $msg=$addStep5FormInfo['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
      }else{
        $msg=$addStep5FormInfo['errMsg'];
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =1 ;
        $returnArr["errMsg"] = $msg;
      }
    }
  //printArr($_POST);
 //printArr($_FILES);

}else{
  $msg="You need to login first to access this page";
  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
  $returnArr["errCode"] =1 ;
  $returnArr["errMsg"] = $msg;
}
//$xmlProcessor->writeXML($xmlfilename, $logStorePath, $xml_data, $xmlArray["activity"]);
echo json_encode($returnArr);
?>