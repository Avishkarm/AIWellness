<?php

if($activeHeader=="2opinion" || $activeHeader=='knowledge_center' || $activeHeader=="doctorsArea"){
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
include($pathprefix."models/userModel.php");
include($pathprefix."models/managePatientModel.php");
include($pathprefix."models/notificationModel.php");
include($pathprefix."models/logsModel.php");
require_once($pathprefix."logs/xmlProcessor/xmlProcessor.php");

$logStorePath =$logPath["managePatient"];
$userEmail=$_SESSION["user"];
//for xml writing essential
$xmlProcessor = new xmlProcessor();
$xmlfilename = "managePatient.xml";

/* Log initialization start */
$xmlArray = initializeXMLLog($userEmail);

$xml_data['request']["data"]='';
$xml_data['request']["attribute"]=$xmlArray["request"];


$msg = "User registration process start.";
$xml_data['step'.$i]["data"] = $i.". {$msg}"; 

//database connection
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
  $conn = $conn["errMsg"];
  $msg = "Success : Search database connection";
  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
} else {
  printArr("Erroe : Database connection");
  exit;
}
//printArr($_SESSION);

//get patients info in edit box
 //printArr($_POST); 

//accept request
//validtation for madatory parameters
/*if($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['group']['doctor']==$_SESSION['userInfo']['user_type_id']){
   
    parse_str($_POST['formdata'],$data);
    //echo $data['user_name'];
   if( $data['type'] == 'add'){
      $check=createPatient($conn,$data);
      echo json_encode($check);
   }else if ( $data['type'] == 'update'){
        //$check = updatePatientStatus($conn, $data);
      $check = updateBulkPatientStatus($conn, $data);
      echo json_encode($check);
   }

   
}*/
     // printArr($_POST);
//accept request
if((isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)) || (isset($_POST["user"]) && !in_array($_POST["user"], $blanks))){
  if((isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)))
    $user = $_SESSION["user"];
    $user_type=$_SESSION["user_type"];
    $userMob=$_SESSION["userInfo"]['user_mob'];
    $msg = "Manadatory parameter passed";
    $xml_data['step'.++$i]["data"] = $i.". {$msg}";
    $returnArr["errCode"] =2 ;
    $returnArr["errMsg"] = $msg;
//printArr($_POST);
  if(isset($_POST) && !empty($_POST)){
    if($_POST['type']=='checkNumber'){
      if((isset($_POST['mob_no']) && !empty($_POST['mob_no']))){
        $mob_no=$_POST['mob_no'];
        $checkUser=checkUser($mob_no,$conn);
        //printArr($checkUser);
        if(noError($checkUser)){
          $returnArr["errCode"]=-1;
          $returnArr["errMsg1"]="user found";
          $returnArr["errMsg"]=$checkUser['errMsg'];
          $returnArr["dob"] =date("d/m/Y", strtotime($checkUser['errMsg']['user_dob'])) ;
        }else{
          $returnArr["errCode"]= 1;
          $returnArr["errMsg"]="user not found";
        }
        
      }
    }
    else if($_POST['form_type']=='addPatient' || $_POST['form_type']=='editPatient'){
       /* printArr($_POST);
    printArr($_FILES);*/

      
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
                    /*if(in_array($ext,$valid_formats))
                    {*/
                    if($size<(1024*1024*2))
                        {
                            $actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
                            $tmp = $_FILES['profile_pic']['tmp_name'];
                            if(move_uploaded_file($tmp, $path.$actual_image_name))
                                {
                                  $errCode=-1;
                               /* mysqli_query($conn,"UPDATE admin_contact_doctor SET image='$actual_image_name' WHERE doctorId=".$_POST['userId']);
                                    
                                    echo "<img style='height:100px;'' src='../../assets/uploads/".$actual_image_name."'  class='preview'>";*/
                                    $pic=$rootUrl.'/assets/uploads/'.$actual_image_name;
                                                        }
                            else{
                               $msg="failed image upload";
                                $returnArr["errCode"] =4 ;
                                $returnArr["errMsg"] = $msg;
                                 $errCode=1;
                                 $errMsg= $msg;
                              }
                        }
                        else{
                        $msg="Image file size max 2 MB";
                        $returnArr["errCode"] =3 ;
                        $returnArr["errMsg"] = $msg;
                        $errCode=1;
                        $errMsg= $msg;
                        }                    
                        /*}
                        else{
                        $msg="Invalid file format..";  
                        $returnArr["errCode"] =2 ;
                        $returnArr["errMsg"] = $msg; 
                      }*/
                }
            else{
                $msg="Please select image..!";
               /* $returnArr["errCode"] =9 ;*/
                $returnArr["errMsg"] = $msg;
                //$errCode=1;
                $errCode=-1;            //ALERT-KDR  - If img not uploaded continue.
                $errMsg= $msg;
            }
              
        }else{
           $msg='image upload failed post';
            $returnArr["errCode"] =0 ;
            $returnArr["errMsg"] = $msg;
            $errCode=1;
            $errMsg= $msg;
        }
        
        
            //$errCode = -1;
            $msg='image upload success';
            $returnArr["errCode"] =1 ;
            $returnArr["errMsg"] = $msg;
            $msg = "Success : Mandatory parameters passed";
            $xml_data['step'.++$i]["data"] = $i.". {$msg}";
            $userInfo['user_first_name']=cleanQueryParameter($conn,cleanXSS($_POST["user_first_name"]));
            $userInfo['user_image']=$pic;
            $userInfo['user_last_name']=cleanQueryParameter($conn,cleanXSS($_POST["user_last_name"]));
            $userInfo['user_mob']=cleanXSS($_POST["user_mob"]);
            $userInfo['country_code']=cleanQueryParameter($conn,cleanXSS($_POST["country_code"]));
            $userInfo['user_email']=cleanQueryParameter($conn,cleanXSS($_POST["user_email"]));
            $userInfo['user_dob']=cleanQueryParameter($conn,cleanXSS($_POST["user_dob"]));
            $userInfo['user_gender']=cleanQueryParameter($conn,cleanXSS($_POST["user_gender"]));
            $userInfo['label']=cleanQueryParameter($conn,cleanXSS($_POST["label"]));
            $userInfo['private_notesadd']=cleanQueryParameter($conn,cleanXSS($_POST["private_notesadd"]));
            $userInfo['private_notesedit']=cleanQueryParameter($conn,cleanXSS($_POST["private_notesedit"]));
            $userInfo['doctor_id']=$_SESSION['userInfo']['user_id'];
            $userInfo['doctor_email']=$_SESSION['user'];
            
              if($_POST['form_type'] == 'addPatient' && $_POST['userStatus']==0){ 
                if(isset($_POST['user_first_name']) && !empty($_POST['user_first_name']) && isset($_POST['user_last_name']) && !empty($_POST['user_last_name']) && isset($_POST['user_mob']) && !empty($_POST['user_mob']) && isset($_POST['user_dob']) && !empty($_POST['user_dob']) && isset($_POST['user_gender']) && !empty($_POST['user_gender'])){
                  if(preg_match("/^[a-z0-9 .\-]+$/i", $_POST["user_first_name"])){
                  if(preg_match("/^[a-z0-9 .\-]+$/i", $_POST["user_last_name"])){
                  //if(strlen((string)$_POST["user_mob"])==10){   //ALERT-KDR
                    if($errCode==-1){
                    $addPatient=addPatient($userInfo,$conn);
                      if(noError($addPatient)){
                        $msg = "Success : Patient added successfully";
                        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                        $returnArr["errCode"] =-1;
                        $returnArr["errMsg"] = $addPatient['errMsg'];  
                        //echo json_encode($addPatient);
                      }else{
                        $msg = "Error : Failed adding patient";
                        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                        $returnArr["errCode"] =2 ;
                        $returnArr["errMsg"] = $addPatient['errMsg'];  
                      }
                  }else{
                     $msg = $errMsg;
                      $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                      $returnArr["errCode"] =2 ;
                      $returnArr["errMsg"] = $errMsg;
                  }
//                  }else{                      //ALERT-KDR
//                      $returnArr["errCode"] =2 ;
//                      $returnArr["errMsg"] = "Mobile number must have 10 digits";
//                    }
                    }else{
                    $returnArr["errCode"] =2 ;
                    $returnArr["errMsg"] = "Invalid Lastname";
                  }
                  }else{
                    $returnArr["errCode"] =2 ;
                    $returnArr["errMsg"] = "Invalid Firstname";
                  }
                }else{
                $msg= "Mandatory parameters not passed";
                $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                $returnArr["errCode"] =3 ;
                $returnArr["errMsg"] = $msg;
              }

              }else if($_POST['form_type'] == 'addPatient' && $_POST['userStatus']==1){
                //$userInfo['doctor_id']=$_SESSION['userInfo']['user_id'];
                $userInfo['patient_id']=cleanQueryParameter($conn,cleanXSS($_POST["user_id"]));
                
                $addPatient=addExistingPatient($userInfo,$conn);
                //printArr($addPatient);
                if(noError($addPatient)){
                  $msg = "Success : Patient added successfully";
                  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                  $returnArr["errCode"] =-1;
                  $returnArr["errMsg"] = $addPatient['errMsg'];  
                  //echo json_encode($addPatient);
                }else{
                  $msg = "Error : Failed adding patient";
                  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                  $returnArr["errCode"] =2 ;
                  $returnArr["errMsg"] = $addPatient['errMsg'];  
                }
              }else if($_POST['form_type'] == 'editPatient'){
                $userInfo['doctor_patient_id']=cleanQueryParameter($conn,cleanXSS($_POST["doctor_patient_id"]));
                $userInfo['user_id']=cleanQueryParameter($conn,cleanXSS($_POST["patient_id"]));
                $editPatient=editPatient($userInfo,$conn);
                if(noError($editPatient)){
                  $msg = "Success : Patient updated successfully";
                  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                  $returnArr["errCode"] =$editPatient['errCode'][-1];
                  $returnArr["errMsg"] = $editPatient['errMsg'];  
                  //echo json_encode($editPatient);
                }else{
                  $msg = "Error : Failed updating patient";
                  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                  $returnArr["errCode"] =2 ;
                  $returnArr["errMsg"] = $editPatient['errMsg'];
                }
              }
          
      /*}else{
        $msg= "Mandatory parameters not passed";
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =3 ;
        $returnArr["errMsg"] = $msg;
      }*/
    }else if($_POST['form_type']=='setLabel' || $_POST['form_type']=='setPrivateNote' || $_POST['form_type']=='deletePatient') {
      if((isset($_POST['patientLabel']) && !empty($_POST['patientLabel'])) || (isset($_POST['patientNote']) && !empty($_POST['patientNote'])) || (isset($_POST['deleted_patient_id']) && !empty($_POST['deleted_patient_id']))){
        $msg = "Success : Mandatory parameters passed";
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $userInfo['doctor_patient_id']=cleanQueryParameter($conn,cleanXSS($_POST["doctor_patient_id"]));
        if( $_POST['form_type'] == 'setLabel'){
          $userInfo['label']=cleanQueryParameter($conn,cleanXSS($_POST["patientLabel"])); 
          $setLabel=setPatientLabel($userInfo,$conn);
          if(noError($setLabel)){
            $msg = "Success : Label set successfully";
            $xml_data['step'.++$i]["data"] = $i.". {$msg}";
            $returnArr["errCode"] =$setLabel['errCode'][-1] ;
            $returnArr["errMsg"] = $setLabel['errMsg'];
            //echo json_encode($setLabel);
          }else{
            $msg = "Error : Failed to set label";
            $xml_data['step'.++$i]["data"] = $i.". {$msg}";
            $returnArr["errCode"] =2 ;
            $returnArr["errMsg"] = $msg;
          }
        }else if($_POST['form_type'] == 'setPrivateNote'){
   
          $userInfo['private_notes']=cleanQueryParameter($conn,cleanXSS($_POST["patientNote"]));     
          $userInfo['richPrivateNote']=cleanQueryParameter($conn,cleanXSS($_POST["richPrivateNote"])); 
          $setPrivateNote=setPatientPrivateNote($userInfo,$conn);        
          if(noError($setPrivateNote)){
            $msg = "Success : Private note set successfully";
            $xml_data['step'.++$i]["data"] = $i.". {$msg}";            
            $returnArr["errCode"] =$setPrivateNote['errCode'][-1] ;
            $returnArr["errMsg"] = $setPrivateNote['errMsg'];
            //echo json_encode($setPrivateNote);
          }else{
            $msg = "Error : Failed to set private note";
            $xml_data['step'.++$i]["data"] = $i.". {$msg}";
            $returnArr["errCode"] =2 ;
            $returnArr["errMsg"] = $msg;
          }
        }else if($_POST['form_type'] == 'deletePatient'){
          $userInfo['doctor_patient_id']=cleanQueryParameter($conn,cleanXSS($_POST["doctor_patient_id"]));
          $userInfo['user_id']=cleanQueryParameter($conn,cleanXSS($_POST["patient_id"]));
          $deletePatient=deletePatient($userInfo,$conn);
          if(noError($deletePatient)){
            $msg = "Success : Patient deleted successfully";
            $xml_data['step'.++$i]["data"] = $i.". {$msg}";
            $returnArr["errCode"] =$deletePatient['errCode'][-1] ;
            $returnArr["errMsg"] = $deletePatient['errMsg'];
            //echo json_encode($deletePatient);
          }else{
            $msg = "Error : Failed deleting patient";
            $xml_data['step'.++$i]["data"] = $i.". {$msg}";
            $returnArr["errCode"] =2 ;
            $returnArr["errMsg"] = $msg;
          }
        }
        else{
          $msg= "Something went wrong.Please try again1";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          $returnArr["errCode"] =4 ;
          $returnArr["errMsg"] = $msg;
        }
      }else{
        $msg= "Parameters not passed";
        $xml_data['step'.++$i]["data"] = $i.". {$msg}";
        $returnArr["errCode"] =3 ;
        $returnArr["errMsg"] = $msg;
      }
    }else if($_POST['type']=='edit'){
        $getPatientInfo=getPatientInfo($_POST['patient_id'],$_POST['doctor_patient_id'],$conn);
       // echo $_POST['doctor_patient_id'];
       //printArr($getPatientInfo);
        if(noError($getPatientInfo)){
          $msg= "fetched patient info seccess";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          $returnArr["dbplus_errcode()"] =-1 ;
          $returnArr["errMsg"] = $getPatientInfo['errMsg'];
          $returnArr["dob"] =date("d/m/Y", strtotime($getPatientInfo['errMsg'][0]['user_dob'])) ;
        }else{
          $msg= "failed to fetch patient info";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          $returnArr["errCode"] =2 ;
          $returnArr["errMsg"] = $msg;
        }
    }else if($_POST['type']=='editLabel'){
        $getPatientLabelInfo=getdoctorspatientInfo($_POST['doctor_patient_id'],$conn);
        if(noError($getPatientLabelInfo)){
          $msg= "fetched patient info seccess";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          $returnArr["errCode"] =-1 ;
          $returnArr["errMsg"] = $getPatientLabelInfo['errMsg'];
        }else{
          $msg= "failed to fetch patient info";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          $returnArr["errCode"] =2 ;
          $returnArr["errMsg"] = $msg;
        }
    }else if($_POST['type']=='editNotes'){
        $getPatientNotesInfo=getdoctorspatientInfo($_POST['doctor_patient_id'],$conn);
        if(noError($getPatientNotesInfo)){
          $msg= "fetched patient info seccess";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          $returnArr["errCode"] =-1 ;
          $returnArr["private_notes"] = htmlspecialchars_decode($getPatientNotesInfo['errMsg']['private_notes'], ENT_QUOTES);
          $returnArr["errMsg"] = $getPatientNotesInfo['errMsg'];
        }else{
          $msg= "failed to fetch patient info";
          $xml_data['step'.++$i]["data"] = $i.". {$msg}";
          $returnArr["errCode"] =2 ;
          $returnArr["errMsg"] = $msg;
        }
    }
    else{
      $msg= "Something went wrong.Please try again2";
      $xml_data['step'.++$i]["data"] = $i.". {$msg}";
      $returnArr["errCode"] =2 ;
      $returnArr["errMsg"] = $msg;
    }
  }else{
    $msg= "Parameters not passed";
    $xml_data['step'.++$i]["data"] = $i.". {$msg}";
    $returnArr["errCode"] =1 ;
    $returnArr["errMsg"] = $msg;
  }
}else{
  $msg="You need to login first to access this page";
  $xml_data['step'.++$i]["data"] = $i.". {$msg}";
  $returnArr["errCode"] =1 ;
  $returnArr["errMsg"] = $msg;
}

echo json_encode($returnArr);

?>