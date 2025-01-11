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
    include($pathprefix."models/userModel.php");
    include($pathprefix."models/managePatientModel.php");
    include($pathprefix."models/notificationModel.php");

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
    
    parse_str($_POST['formdata'],$_POST);
    //printArr($_POST['formdata']);

    if(isset($_POST) && !empty($_POST)){
	$msg = "Post array not empty";
	$xml_data['step'.++$i]["data"] = $i.". {$msg}";
        if(isset($_POST['user_first_name']) && !empty($_POST['user_first_name']) && 
           isset($_POST['user_last_name']) && !empty($_POST['user_last_name']) && 
           isset($_POST['fdbk_msg']) && !empty($_POST['fdbk_msg'])){
            $msg="All mandatory parameters are passed.";		
            $xml_data['step'.++$i]["data"] = $i.". {$msg}";
            $usrInfo=array();
            $usrInfo['user_first_name']=cleanQueryParameter($conn,cleanXSS($_POST["user_first_name"]));
            $usrInfo['user_last_name']=cleanQueryParameter($conn,cleanXSS($_POST["user_last_name"]));
            $usrInfo['fdbk_msg']=cleanQueryParameter($conn,cleanXSS($_POST["fdbk_msg"]));
            if (filter_var($FeedbackEmail, FILTER_VALIDATE_EMAIL)){
                $msg="Valid email format";
                $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                
                $msg="eHeilung Feedback";
                $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                $image=$rootUrl."/assets/images/logo.png";
                $subject="eHeilung Feedback";
                
                $message=   '<div style="width: 95%; margin: 0 auto;margin-bottom:20px;">
                                <img src="'.$image.'">
                                <br>
                                <h1 style="margin-top: 0px; margin-bottom:0px; color: #454545; font-family: arial;font-size: 35px;">Feedback!</h1>
                                <br>
                                <h4 style="margin-top: 0px; margin-bottom:0px; color: #454545;font-family: arial;letter-spacing: 1px;font-size: 25px;">User  : '.$usrInfo['user_first_name']." ". $usrInfo['user_last_name'] .'<br></h4>
                                <h4 style="margin-top: 0px; margin-bottom:0px; color: #454545;font-family: arial;letter-spacing: 1px;font-size: 25px;">Email : '.$userInfo['user_email'].'<br></h4>
                                <h2 style="color: #454545; font-family: arial;letter-spacing: 1px;font-size: 18px;">Message : '.$usrInfo['fdbk_msg'] .'</h2>     
                            </div>';                     
                $from = "eHeilung <donotreply@eheilung.com>";
                $activationMail=sendMail($FeedbackEmail, $from, $subject, $message);
                if(noError($activationMail)){
                    $msg= "Your Feedback has been Sent";
                    $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                    $returnArr["errCode"] =-1 ;
                    $returnArr["errMsg"] = $msg;
                } else{
                    $msg= "Failed to send Feedback";
                    $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                    $returnArr["errCode"] =8 ;
                    $returnArr["errMsg"] = $msg;
                }
                
            } 
            else{
                $msg= "Invalid email format!";
                $xml_data['step'.++$i]["data"] = $i.". {$msg}";
                $returnArr["errCode"] =2 ;
                $returnArr["errMsg"] = $msg;
                $resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
                $returnArr['resendMsg']=$resendMsg;
            }                    
        }
        else{
            $msg= "Mandatory parameters not passed";
            $xml_data['step'.++$i]["data"] = $i.". {$msg}";
            $returnArr["errCode"] =1 ;
            $returnArr["errMsg"] = $msg;
            $resendMsg="<a style='color:#0dae04' data-dismiss='modal'>Try again</a>";
            $returnArr['resendMsg']=$resendMsg;
        }
        
    }
    else{
	$msg= "Parameters not passed";
	$xml_data['step'.++$i]["data"] = $i.". {$msg}";
	$returnArr["errCode"] =1 ;
  	$returnArr["errMsg"] = $msg;
  	$resendMsg="<a style='color:#0dae04' data-dismiss='modal' >Try again</a>";
	$returnArr['resendMsg']=$resendMsg;
    }

    echo json_encode($returnArr);
?>
