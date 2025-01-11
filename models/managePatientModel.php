<?php
//include("userModel.php");
/*function getDemo($conn,$userInfo,$pageNo=1,$limit=10){
   global $blanks;
   $returnArr = array();
   $end=$pageNo*$limit;
   $start=$end-10;
   $cnt='';
$cnt=getPatientSortCount($conn,$userInfo);
  $returnArr = array();
  if(!empty($userInfo['filterLabel']) && !empty($userInfo['filterName'])){

    $query = "SELECT users.*,doctors_patient.* FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.status='Active' and doctors_patient.doctor_id=".$userInfo['doctor_id']." and label='".$userInfo['filterLabel']."' ORDER BY users.user_first_name ".$userInfo['filterName']." LIMIT ".$start.",".$limit;
  }
  else if(!empty($userInfo['filterLabel']) && empty($userInfo['filterName'])){
 $query = "SELECT users.*,doctors_patient.* FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.status='Active' and doctors_patient.doctor_id=".$userInfo['doctor_id']." and label='".$userInfo['filterLabel']."' LIMIT ".$start.",".$limit;
  }
  else if(empty($userInfo['filterLabel']) && !empty($userInfo['filterName'])){
 $query = "SELECT users.*,doctors_patient.* FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.status='Active' and doctors_patient.doctor_id=".$userInfo['doctor_id']." ORDER BY users.user_first_name ".$userInfo['filterName']." LIMIT ".$start.",".$limit;
  }
   $result = runQuery($query, $conn);

   if(noError($result)){
    $res = array();

    while ($row = mysqli_fetch_assoc($result["dbResource"]))
    //printArr($row);
      $res[$row['patient_id']] = $row;

    $returnArr["errCode"][-1]=-1;

    $returnArr["errMsg"]=$res;
    $returnArr['countCases']=$cnt['errMsg'];
  } else {

    $returnArr["errCode"][5]=5;

    $returnArr["errMsg"]=$result["errMsg"];

  }
  //printArr($res);
 return $returnArr;
}*/


function getDemo($conn,$userInfo,$pageNo=1,$limit=10){
   global $blanks;
   $returnArr = array();
   $end=$pageNo*$limit;
   $start=$end-10;
   $cnt='';
   $query="";
$cnt=getPatientSortCount($conn,$userInfo);
  $returnArr = array();
  if(!empty($userInfo['filterLabel']) && !empty($userInfo['filterName'])){
    $labelarray=explode("&",$userInfo['filterLabel']);
    //printArr($labelarray);
    $query .= "SELECT users.*,doctors_patient.* FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.status='Active' and doctors_patient.doctor_id=".$userInfo['doctor_id'];
    foreach ($labelarray as $key => $value) {
      # code...
      $label=explode("=",$value);
      //printArr($label);
      if($key==0){
       $query.=" and (label='".$label[1]."'";
      }else{
        $query.=" or label='".$label[1]."'";
      }
    }
   
    $query.=") ORDER BY users.user_first_name ".$userInfo['filterName'];
     $query.=" LIMIT ".$start.",".$limit;
  }
  else if(!empty($userInfo['filterLabel']) && empty($userInfo['filterName'])){
    $labelarray=explode("&",$userInfo['filterLabel']);
    //printArr($labelarray);
    $query = "SELECT users.*,doctors_patient.* FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.status='Active' and doctors_patient.doctor_id=".$userInfo['doctor_id'];
    foreach ($labelarray as $key => $value) {
      # code...
      $label=explode("=",$value);
      if($key==0){
       $query.=" and (label='".$label[1]."'";
      }else{
        $query.=" or label='".$label[1]."'";
      }
    }
    $query.=") LIMIT ".$start.",".$limit;
  }
  else if(empty($userInfo['filterLabel']) && !empty($userInfo['filterName'])){
      $query = "SELECT users.*,doctors_patient.* FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.status='Active' and doctors_patient.doctor_id=".$userInfo['doctor_id'];
      $query.=" ORDER BY users.user_first_name ".$userInfo['filterName'];
       $query.=" LIMIT ".$start.",".$limit;
  }
   $result = runQuery($query, $conn);

   if(noError($result)){
    $res = array();

    while ($row = mysqli_fetch_assoc($result["dbResource"]))
    //printArr($row);
      $res[$row['patient_id']] = $row;

    $returnArr["errCode"][-1]=-1;

    $returnArr["errMsg"]=$res;
     $returnArr['countCases']=$cnt['errMsg'];
  } else {

    $returnArr["errCode"][5]=5;

    $returnArr["errMsg"]=$result["errMsg"];

  }
  //printArr($res);
 // printArr($returnArr);
 return $returnArr;
}

function getSearchPatient($conn,$userInfo,$pageNo=1,$limit=10){
   global $blanks;
   $returnArr = array();
   $end=$pageNo*$limit;
   $start=$end-10;
   $cnt='';
  $cnt=getPatientSearchCount($conn,$userInfo);
  $returnArr = array();
   $query = "SELECT users.*,doctors_patient.* FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.status='Active' and doctors_patient.doctor_id=".$userInfo['doctor_id']." and (user_first_name like '%".$userInfo['search']."%' or user_last_name like '%".$userInfo['search']."%' or user_mob like '%".$userInfo['search']."%'  or user_email like '%".$userInfo['search']."%' or user_gender like '%".$userInfo['search']."%')  LIMIT ".$start.",".$limit;

   $result = runQuery($query, $conn);

   if(noError($result)){
    $res = array();

    while ($row = mysqli_fetch_assoc($result["dbResource"]))
    //printArr($row);
      $res[$row['patient_id']] = $row;

    $returnArr["errCode"][-1]=-1;

    $returnArr["errMsg"]=$res;
    $returnArr['countCases']=$cnt['errMsg'];
  } else {

    $returnArr["errCode"][5]=5;

    $returnArr["errMsg"]=$result["errMsg"];

  }
  
 return $returnArr;
}


function getPatientSortCount($conn,$userInfo)
  { 
     $returnArr = array();
     $query="";
    global $totalQuestions;
    /*if(!empty($userInfo['filterLabel']) && !empty($userInfo['filterName'])){
   $query = "SELECT COUNT(*) as countCases FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.doctor_id=".$userInfo['doctor_id']." and label='".$userInfo['filterLabel']."' ORDER BY users.user_first_name ".$userInfo['filterName'];
  }
  else if(!empty($userInfo['filterLabel']) && empty($userInfo['filterName'])){
 $query = "SELECT COUNT(*) as countCases FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.doctor_id=".$userInfo['doctor_id']." and label='".$userInfo['filterLabel']."'";
  }
  else if(empty($userInfo['filterLabel']) && !empty($userInfo['filterName'])){
 $query = "SELECT COUNT(*) as countCases FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.doctor_id=".$userInfo['doctor_id']." ORDER BY users.user_first_name ".$userInfo['filterName'];
  }*/

  if(!empty($userInfo['filterLabel']) && !empty($userInfo['filterName'])){
    $labelarray=explode("&",$userInfo['filterLabel']);
    //printArr($labelarray);
    $query .= "SELECT COUNT(*) as countCases FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.status='Active' and doctors_patient.doctor_id=".$userInfo['doctor_id'];
    foreach ($labelarray as $key => $value) {
      # code...
      $label=explode("=",$value);
      //printArr($label);
      if($key==0){
       $query.=" and (label='".$label[1]."'";
      }else{
        $query.=" or label='".$label[1]."'";
      }
    }
   
     $query.=") ORDER BY users.user_first_name ".$userInfo['filterName'];
  }
  else if(!empty($userInfo['filterLabel']) && empty($userInfo['filterName'])){
    $labelarray=explode("&",$userInfo['filterLabel']);
    //printArr($labelarray);
    $query = "SELECT COUNT(*) as countCases FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.status='Active' and doctors_patient.doctor_id=".$userInfo['doctor_id'];
    foreach ($labelarray as $key => $value) {
      # code...
      $label=explode("=",$value);
      if($key==0){
       $query.=" and (label='".$label[1]."'";
      }else{
        $query.=" or label='".$label[1]."'";
      }
    }
     $query.=")";
  }
  else if(empty($userInfo['filterLabel']) && !empty($userInfo['filterName'])){
      $query = "SELECT COUNT(*) as countCases FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.status='Active' and doctors_patient.doctor_id=".$userInfo['doctor_id'];
      $query.=" ORDER BY users.user_first_name ".$userInfo['filterName'];
  }

    $result=runQuery($query,$conn); 
    if(noError($result)){
      $row=mysqli_fetch_assoc($result["dbResource"]);
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$row["countCases"];

    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching patient count";
    }

    return $returnArr;
  }


function getPatientSearchCount($conn,$userInfo)
  { 
     $returnArr = array();
    global $totalQuestions;
  /* echo $query = "SELECT COUNT(*) as countCases FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.doctor_id=".$userInfo['doctor_id']." and (user_first_name like '%".$userInfo['search']."%' or user_last_name like '%".$userInfo['search']."%' or user_mob like '%".$userInfo['search']."%'  or user_email like '%".$userInfo['search']."%' or user_gender like '%".$userInfo['search']."%')";*/

     $query = "SELECT COUNT(*) as countCases FROM users INNER JOIN doctors_patient ON users.user_id= doctors_patient.patient_id WHERE doctors_patient.status='Active' and doctors_patient.doctor_id=".$userInfo['doctor_id']." and (user_first_name like '%".$userInfo['search']."%' or user_last_name like '%".$userInfo['search']."%' or user_mob like '%".$userInfo['search']."%'  or user_email like '%".$userInfo['search']."%' or user_gender like '%".$userInfo['search']."%')";
 
    $result=runQuery($query,$conn); 
    if(noError($result)){
      $row=mysqli_fetch_assoc($result["dbResource"]);
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$row["countCases"];

    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching patient count";
    }

    return $returnArr;
  }
 /* else if($_POST['type']=='checkNumber'){
      if((isset($_POST['mob_no']) && !empty($_POST['mob_no'])){
       echo $mob_no=$_POST['mob_no'];
        $checkUser=checkUser($mob_no);
        
      }
  }*/
function checkUser($mob_no,$conn){
  $userInfo['user_email']="dummy".$mob_no."@eHeilung.com";
   $userPatient=getUserInfoWithMobNo($mob_no,3,$conn);

    //printArr($userPatient);
     if(noError($userPatient)){
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg1"]="user found";
        $returnArr["errMsg"]=$userPatient['errMsg'];
      }else{
        $returnArr["errCode"][1]= 1;
        $returnArr["errMsg"]="user not found";
      }
      return $returnArr;
}
function addPatient($userInfo,$conn){
	$returnArr=array();
    $doctor_id=$userInfo['doctor_id'];
    $doctor_email=$userInfo['doctor_email'];
    if(empty($userInfo['user_email'])){
    	$userInfo['user_email']="dummy".$userInfo['user_mob']."@eHeilung.com";
    }
    $userPatient=getUserInfoWithUserType($userInfo['user_email'],3,$conn);
    $userPatientMob=getUserInfoWithMobNo($userInfo['user_mob'],3,$conn);
    //printArr($userPatient);
    if(noError($userPatient)){
    	$patient_id=$userPatient['errMsg']['user_id'];
    	$checkDoctorsPatient=checkDoctorsPatient($doctor_id,$patient_id,$conn);
    	//printArr($checkDoctorsPatient);
    	if(noError($checkDoctorsPatient)){
        if($checkDoctorsPatient['errMsg']['status']=='Active'){
      		$msg= "You have already added patient";
  		    $returnArr["errCode"][1]=1;
  		    $returnArr["errMsg"] = $msg;
        }else if($checkDoctorsPatient['errMsg']['status']=='Deleted'){
          $doctor_patient_id=$checkDoctorsPatient['errMsg']['doctor_patient_id'];
          $userInfo['user_type']=3;
          $userInfo['status']='Active';
          $UpdateDoctorPatientsProfileInfo=UpdateDoctorPatientsProfileInfo($patient_id,$userInfo,$conn);
          $updateDoctorsPatient=updateDoctorsPatient($doctor_patient_id,$userInfo['private_notesadd'],$userInfo['label'], $conn);
          //printArr($addDoctorsPatient);
          if(noError($updateDoctorsPatient)){
            $returnArr['doctor_patient_id']= $doctor_patient_id;
            $msg="Patient added successfully";
            $from="eHeilung <donotreply@eheilung.com>";
            $subject="eHeilung patient account created successfully";;
            $mailMessage=  "<div style='font-family: arial,sans-serif'>"
                              ."<h4 class='btn-common' style='background-color:#0be1a5;padding:5px;margin: 0px;'>
                              <div class='' style='display:inline;color:white;padding: 15px; font-size: 45px;'>
                                <img style='width: 30px; height: 30px; padding-right: 10px; padding-top: 10px;' src='../views/images/logo1.png'>eHeilung
                              </div>
                              </h4>"
                              ."
                              <p style='border:solid thin #0be1a5; padding: 15px;padding-top:30px;padding-bottom:30px; margin: 0px;'>
                                Hi, 
                              <br> 
                              <br> 
                              <span style='color:#0be1a5;'>Congratulations!</span>
                              <br> <br>
                              A new account has been created for you at eHeilung by doctor<span style='color:#0be1a5;!important'>".$userInfo['doctor_email']."</span>
                              </p>
                            </div>";
            $sendMail=sendMail($userInfo['user_email'], $from, $subject, $mailMessage);
            $mobMessage="You are added by doctor:: ".$userInfo['doctor_email'];
            $sendSms=sendSMS($mobMessage,$userInfo['user_mob']);
            $returnArr["errCode"][-1]=-1;
            $returnArr["errMsg"] = $msg;
          }else{
            $returnArr["errCode"][2]=2;
            $returnArr["errMsg"] = $addDoctorsPatient["errMsg"];
          }
        }
    	}else{

    		$addDoctorsPatient=addDoctorsPatient($userInfo['doctor_id'],$patient_id,$userInfo['private_notesadd'],$userInfo['label'], $conn);
    		//printArr($addDoctorsPatient);
    		if(noError($addDoctorsPatient)){
    			$returnArr['doctor_patient_id']= $addDoctorsPatient['errMsg'];
    			$msg="Patient added successfully";
    			$from="eHeilung <donotreply@eheilung.com>";
    			$subject="eHeilung patient account created successfully";;
    			$mailMessage=  "<div style='font-family: arial,sans-serif'>"
			                      ."<h4 class='btn-common' style='background-color:#0be1a5;padding:5px;margin: 0px;'>
			                      <div class='' style='display:inline;color:white;padding: 15px; font-size: 45px;'>
			                        <img style='width: 30px; height: 30px; padding-right: 10px; padding-top: 10px;' src='../views/images/logo1.png'>eHeilung
			                      </div>
			                      </h4>"
			                      ."
			                      <p style='border:solid thin #0be1a5; padding: 15px;padding-top:30px;padding-bottom:30px; margin: 0px;'>
			                        Hi, 
			                      <br> 
			                      <br> 
			                      <span style='color:#0be1a5;'>Congratulations!</span>
			                      <br> <br>
			                      A new account has been created for you at eHeilung by doctor<span style='color:#0be1a5;!important'>".$userInfo['doctor_email']."</span>
			                      </p>
			                    </div>";
			   	$sendMail=sendMail($userInfo['user_email'], $from, $subject, $mailMessage);
			    $mobMessage="You are added by doctor:: ".$userInfo['doctor_email'];
			    $sendSms=sendSMS($mobMessage,$userInfo['user_mob']);
			    $returnArr["errCode"][-1]=-1;
			    $returnArr["errMsg"] = $msg;
    		}else{
    			$returnArr["errCode"][2]=2;
			    $returnArr["errMsg"] = $addDoctorsPatient["errMsg"];
    		}
    	}
    }else if(noError($userPatientMob)){
      $patient_id=$userPatient['errMsg']['user_id'];
      $checkDoctorsPatient=checkDoctorsPatient($doctor_id,$patient_id,$conn);
      //printArr($checkDoctorsPatient);
      if(noError($checkDoctorsPatient)){
        if($checkDoctorsPatient['errMsg']['status']=='Active'){
          $msg= "You have already added patient";
          $returnArr["errCode"][1]=1;
          $returnArr["errMsg"] = $msg;
        }else if($checkDoctorsPatient['errMsg']['status']=='Deleted'){
          $doctor_patient_id=$checkDoctorsPatient['errMsg']['doctor_patient_id'];
          $userInfo['user_type']=3;
          $userInfo['status']='Active';
          $UpdateDoctorPatientsProfileInfo=UpdateDoctorPatientsProfileInfo($patient_id,$userInfo,$conn);
          $updateDoctorsPatient=updateDoctorsPatient($doctor_patient_id,$userInfo['private_notesadd'],$userInfo['label'], $conn);
          //printArr($addDoctorsPatient);
          if(noError($updateDoctorsPatient)){
            $returnArr['doctor_patient_id']= $doctor_patient_id;
            $msg="Patient added successfully";
            $from="eHeilung <donotreply@eheilung.com>";
            $subject="eHeilung patient account created successfully";;
            $mailMessage=  "<div style='font-family: arial,sans-serif'>"
                              ."<h4 class='btn-common' style='background-color:#0be1a5;padding:5px;margin: 0px;'>
                              <div class='' style='display:inline;color:white;padding: 15px; font-size: 45px;'>
                                <img style='width: 30px; height: 30px; padding-right: 10px; padding-top: 10px;' src='../views/images/logo1.png'>eHeilung
                              </div>
                              </h4>"
                              ."
                              <p style='border:solid thin #0be1a5; padding: 15px;padding-top:30px;padding-bottom:30px; margin: 0px;'>
                                Hi, 
                              <br> 
                              <br> 
                              <span style='color:#0be1a5;'>Congratulations!</span>
                              <br> <br>
                              A new account has been created for you at eHeilung by doctor<span style='color:#0be1a5;!important'>".$userInfo['doctor_email']."</span>
                              </p>
                            </div>";
            $sendMail=sendMail($userInfo['user_email'], $from, $subject, $mailMessage);
            $mobMessage="You are added by doctor:: ".$userInfo['doctor_email'];
            $sendSms=sendSMS($mobMessage,$userInfo['user_mob']);
            $returnArr["errCode"][-1]=-1;
            $returnArr["errMsg"] = $msg;
          }else{
            $returnArr["errCode"][2]=2;
            $returnArr["errMsg"] = $addDoctorsPatient["errMsg"];
          }
        }
      }else{

        $addDoctorsPatient=addDoctorsPatient($userInfo['doctor_id'],$patient_id,$userInfo['private_notesadd'],$userInfo['label'], $conn);
        //printArr($addDoctorsPatient);
        if(noError($addDoctorsPatient)){
          $returnArr['doctor_patient_id']= $addDoctorsPatient['errMsg'];
          $msg="Patient added successfully";
          $from="eHeilung <donotreply@eheilung.com>";
          $subject="eHeilung patient account created successfully";;
          $mailMessage=  "<div style='font-family: arial,sans-serif'>"
                            ."<h4 class='btn-common' style='background-color:#0be1a5;padding:5px;margin: 0px;'>
                            <div class='' style='display:inline;color:white;padding: 15px; font-size: 45px;'>
                              <img style='width: 30px; height: 30px; padding-right: 10px; padding-top: 10px;' src='../views/images/logo1.png'>eHeilung
                            </div>
                            </h4>"
                            ."
                            <p style='border:solid thin #0be1a5; padding: 15px;padding-top:30px;padding-bottom:30px; margin: 0px;'>
                              Hi, 
                            <br> 
                            <br> 
                            <span style='color:#0be1a5;'>Congratulations!</span>
                            <br> <br>
                            A new account has been created for you at eHeilung by doctor<span style='color:#0be1a5;!important'>".$userInfo['doctor_email']."</span>
                            </p>
                          </div>";
          $sendMail=sendMail($userInfo['user_email'], $from, $subject, $mailMessage);
          $mobMessage="You are added by doctor:: ".$userInfo['doctor_email'];
          $sendSms=sendSMS($mobMessage,$userInfo['user_mob']);
          $returnArr["errCode"][-1]=-1;
          $returnArr["errMsg"] = $msg;
        }else{
          $returnArr["errCode"][2]=2;
          $returnArr["errMsg"] = $addDoctorsPatient["errMsg"];
        }
      }
    }else{
    	$salt=generateSalt();
	    $userInfo['salt'] = $salt;
	    $password=generatePassword();
	    $userInfo['user_password']=encryptPassword($password,$salt);
	    $userInfo['user_type']=3;
	    $userInfo['status']='Active';

	
	    $insertDoctorPatientsProfileInfo=insertDoctorPatientsProfileInfo($userInfo,$conn);
	    if(noError($insertDoctorPatientsProfileInfo)){
	    	$msg="Patient account created successfully";
			$patient_id= $insertDoctorPatientsProfileInfo['errMsg'];
		    /*$returnArr["errCode"][-1]=-1;
		    $returnArr["errMsg"] = $addDoctorsPatient['errMsg'];*/
		    $addDoctorsPatient=addDoctorsPatient($userInfo['doctor_id'],$patient_id,$userInfo['private_notesadd'],$userInfo['label'], $conn);
		    if(noError($addDoctorsPatient)){
    			$returnArr['doctor_patient_id']= $addDoctorsPatient['errMsg'];
    			$msg="Patient added successfully";
    			$msg="Patient added successfully";
    			$from="eHeilung <donotreply@eheilung.com>";
    			$subject="eHeilung patient account created successfully";;
    			$mailMessage= "<div style='font-family: arial,sans-serif'>"
			                      ."<h4 class='btn-common' style='background-color:#0be1a5;padding:5px;margin: 0px;'>
			                      <div class='' style='display:inline;color:white;padding: 15px; font-size: 45px;'>
			                        <img style='width: 30px; height: 30px; padding-right: 10px; padding-top: 10px;' src='../views/images/logo1.png'>eHeilung
			                      </div>
			                      </h4>"
			                      ."
			                      <p style='border:solid thin #0be1a5; padding: 15px;padding-top:30px;padding-bottom:30px; margin: 0px;'>
			                        Hi, 
			                      <br> 
			                      <br> 
			                      <span style='color:#0be1a5;'>Congratulations!</span>
			                      <br> <br>
			                      A new account has been created for you at eHeilung by doctor<span style='color:#0be1a5;!important'>".$userInfo['doctor_email']."</span><br> <br> 
			                      Your login credentials are:: username- " .$userInfo['user_email']. " password- " .$password."
			                      </p>
		                    </div>";
			   // $sendMail=sendMail($userInfo['user_email'], $from, $subject, $mailMessage);
			    $mobMessage="You are added by doctor:: ".$user."<br><br> Your login credentials are::<br> username- " .$userInfo['user_email']. "<br> password- " .$password;
			    //$sendSms=sendSMS($mobMessage,$userInfo['user_mob']);
			    $returnArr["errCode"][-1]=-1;
			    $returnArr["errMsg"] = $msg;
    		}else{
    			$returnArr["errCode"][3]=3;
			    $returnArr["errMsg"] = $addDoctorsPatient["errMsg"];
    		}
		}else{
			$returnArr["errCode"][4]=4;
		    $returnArr["errMsg"] = $insertDoctorPatientsProfileInfo["errMsg"];
		}
    }
    //printArr($returnArr);
    return $returnArr;
}
/*function checkDoctorsPatient($doctor_id, $patient_id, $conn){

    $returnArr = array();
    global $blanks;
   $query = "SELECT * FROM doctors_patient WHERE patient_id=".$patient_id." and doctor_id=".$doctor_id." and status='Active'";     

    $result = runQuery($query, $conn);

    if(noError($result)){
      if(mysqli_num_rows($result["dbResource"])==0){
              //username does not exist
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"] = "Could not find username: ".$result["errMsg"];
      } else {        
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"] = mysqli_fetch_assoc($result["dbResource"]);
        $returnArr["errMsg"]="user Found" ;   
      }
    } else {
      $returnArr["errCode"][2]=2;
      $returnArr["errMsg"] = "Could not get user info: ".$result["errMsg"];
    }
    //printArr($returnArr);
    return $returnArr;

  }  */

  function addExistingPatient($userInfo,$conn){
    $checkDoctorsPatient=checkDoctorsPatient($userInfo['doctor_id'], $userInfo['patient_id'], $conn);
    if(noError($checkDoctorsPatient)){
      if($checkDoctorsPatient['errMsg']['status']=='Active'){
          $msg= "You have already added patient";
          $returnArr["errCode"][1]=1;
          $returnArr["errMsg"] = $msg;
        }else if($checkDoctorsPatient['errMsg']['status']=='Deleted'){
          $doctor_patient_id=$checkDoctorsPatient['errMsg']['doctor_patient_id'];
          $userInfo['user_type']=3;
          $userInfo['status']='Active';
          //$UpdateDoctorPatientsProfileInfo=UpdateDoctorPatientsProfileInfo($patient_id,$userInfo,$conn);
          $updateDoctorsPatient=updateDoctorsPatient($doctor_patient_id,$userInfo['private_notesadd'],$userInfo['label'], $conn);
          //printArr($addDoctorsPatient);
          if(noError($updateDoctorsPatient)){
            $returnArr['doctor_patient_id']= $doctor_patient_id;            
            $mobMessage="You are added by doctor:: ".$userInfo['doctor_email'];
            $sendSms=sendSMS($mobMessage,$userInfo['user_mob']);
            $returnArr["errCode"][-1]=-1;
            $returnArr["errMsg"] = "patient added successfully";
          }else{
            $returnArr["errCode"][2]=2;
            $returnArr["errMsg"] = $updateDoctorsPatient["errMsg"];
          }
        }
    }else{
        $addDoctorsPatient=addDoctorsPatient($userInfo['doctor_id'], $userInfo['patient_id'],$userInfo['private_notesadd'],$userInfo['label'], $conn);
        //printArr($addDoctorsPatient);
        if(noError($addDoctorsPatient)){
          $returnArr['doctor_patient_id']= $addDoctorsPatient['errMsg'];         
          $mobMessage="You are added by doctor:: ".$userInfo['doctor_email'];
          $sendSms=sendSMS($mobMessage,$userInfo['user_mob']);
          $returnArr["errCode"][-1]=-1;
          $returnArr["errMsg"] = "patient added successfully";
        }else{
          $returnArr["errCode"][2]=2;
          $returnArr["errMsg"] = $addDoctorsPatient["errMsg"];
        }
    }
    return $returnArr;
  }

  function checkDoctorsPatient($doctor_id, $patient_id, $conn){

    $returnArr = array();
    global $blanks;
   $query = "SELECT * FROM doctors_patient WHERE patient_id=".$patient_id." and doctor_id=".$doctor_id;     

    $result = runQuery($query, $conn);

    if(noError($result)){
      if(mysqli_num_rows($result["dbResource"])==0){
              //username does not exist
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"] = "Could not find username: ".$result["errMsg"];
      } else {        
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"] = mysqli_fetch_assoc($result["dbResource"]);
        //$returnArr["errMsg"]="user Found" ;   
      }
    } else {
      $returnArr["errCode"][2]=2;
      $returnArr["errMsg"] = "Could not get user info: ".$result["errMsg"];
    }
    //printArr($returnArr);
    return $returnArr;

  }  
function addDoctorsPatient($doctor_id,$patient_id,$private_notes,$label, $conn)
{
 $insertquery = "INSERT INTO `doctors_patient`(`doctor_id`,`patient_id`,`private_notes`,`label`,`status`) VALUES (".$doctor_id.",".$patient_id.",'".$private_notes."','".$label."','Active')";
  $result = runQuery($insertquery, $conn);
  if(noError($result)){
    $returnArr["errCode"][-1]=-1;
    $returnArr["errMsg1"]="patient added successfully";
    $returnArr["errMsg"]=mysqli_insert_id($conn);
  }else{
    $returnArr["errCode"][1]= 1;
    $returnArr["errMsg"]=" Insertion failed".mysqli_error();
  }
  return $returnArr;
} 

function updateDoctorsPatient($doctor_patient_id,$private_notes,$label, $conn)
{
 
 //$insertquery = "INSERT INTO `doctors_patient`(`doctor_id`,`patient_id`,`private_notes`,`status`) VALUES (".$doctor_id.",".$patient_id.",'".$private_notes."','Active')";
 $insertquery = "UPDATE `doctors_patient` SET `private_notes`='".$private_notes."',`label`='".$label."',`status`='Active' WHERE  doctor_patient_id=".$doctor_patient_id;
  $result = runQuery($insertquery, $conn);
  if(noError($result)){
    $returnArr["errCode"][-1]=-1;
    $returnArr["errMsg1"]="patient added successfully";
    $returnArr["errMsg"]=mysqli_insert_id($conn);
  }else{
    $returnArr["errCode"][1]= 1;
    $returnArr["errMsg"]=" Insertion failed".mysqli_error();
  }
  return $returnArr;
}

  function insertDoctorPatientsProfileInfo($userInfo, $conn){
//printArr($userInfo);
  global $blanks;
  $returnArr = array();
  //initializing the query string variables
  //echo $userInfo["user_dob"]."<br>";
  $query = "INSERT INTO users"; 
  //customizing the values array
  if(isset($userInfo["user_mob"]) && !(in_array($userInfo["user_mob"], $blanks))){
    $values["user_mob"] = $userInfo["user_mob"];
  }
  if(isset($userInfo["country_code"]) && !(in_array($userInfo["country_code"], $blanks))){
    $values["country_code"] = $userInfo["country_code"];
  }
  if(isset($userInfo["user_first_name"]) && !(in_array($userInfo["user_first_name"], $blanks))){
    $values["user_first_name"] = $userInfo["user_first_name"];
  }
  if(isset($userInfo["user_last_name"]) && !(in_array($userInfo["user_last_name"], $blanks))){
    $values["user_last_name"] = $userInfo["user_last_name"];
  }
  if(isset($userInfo["user_type"]) && !(in_array($userInfo["user_type"], $blanks))){
    $values["user_type_id"] = $userInfo["user_type"];
  }
  if(isset($userInfo["salt"]) && !(in_array($userInfo["salt"], $blanks))){
    $values["salt"] = $userInfo["salt"];
  }
  if(isset($userInfo["user_password"]) && !(in_array($userInfo["user_password"], $blanks))){
     $values["user_password"] = $userInfo["user_password"];
  }
  if(isset($userInfo["user_gender"]) && !(in_array($userInfo["user_gender"], $blanks))){
        $values["user_gender"] = $userInfo["user_gender"];
    }
  if(isset($userInfo["user_dob"]) && !(in_array($userInfo["user_dob"], $blanks))){
      $values["user_dob"] = date("Y-m-d", strtotime(str_replace('/','-',$userInfo["user_dob"])));
  }
    if(isset($userInfo["user_image"]) && !(in_array($userInfo["user_image"], $blanks))){
       $values["user_image"] = $userInfo["user_image"];
    }
    /*
    if(isset($userInfo["private_notes"]) && !(in_array($userInfo["private_notes"], $blanks))){
        $values["private_notes"] = $userInfo["private_notes"];
    }*/
    if(isset($userInfo["user_email"]) && !(in_array($userInfo["user_email"], $blanks))){
        $values["user_email"] = $userInfo["user_email"];
    }
    if(isset($userInfo["status"]) && !(in_array($userInfo["status"], $blanks))){
        $values["status"] = $userInfo["status"];
    }

  //looping thru the col names and values arrays to for related query strings
  $colNamesStr = ""; $valuesStr = ""; $updateStr = "";
  foreach($values as $colName=>$val){             
  if(!in_array($colNamesStr, $blanks))
      $colNamesStr .= ",";
    $colNamesStr .= cleanQueryParameter($conn,$colName);
  if(!in_array($valuesStr, $blanks))
      $valuesStr .= ",";
    $valuesStr .= "'".cleanQueryParameter($conn,$val)."'";
  }

   $query .= "(".$colNamesStr.") VALUES (".$valuesStr.")";

  //run the query and return success or failure
  $result = runQuery($query, $conn);
  //  printArr($result);
  if(noError($result)){
    $returnArr["errCode"][-1] = -1;
    $returnArr["errMsg"] = mysqli_insert_id($conn);
  } else {
    $returnArr["errCode"][1] = 1;
    $returnArr["errMsg"] = "Failed to add patient: ".$result["errMsg"]; 
  }
  //printArr($returnArr);
  return $returnArr; 
} 


function UpdateDoctorPatientsProfileInfo($patient_id,$userInfo, $conn){
//printArr($userInfo);
  global $blanks;
  $returnArr = array();
  //initializing the query string variables
  //echo $userInfo["user_dob"]."<br>";
  //$query = "INSERT INTO users"; 
  $query = "UPDATE users SET user_mob='".$userInfo["user_mob"]."', country_code='".$userInfo["country_code"]."', user_first_name='".$userInfo["user_first_name"]."', user_last_name='".$userInfo["user_last_name"]."', user_type_id='".$userInfo["user_type"]."' , user_gender='".$userInfo["user_gender"]."' , user_dob='".date("Y-m-d", strtotime(str_replace('/','-',$userInfo["user_dob"])))."', user_image='".$userInfo["user_image"]."', status='".$userInfo["status"]."'  Where user_id=".$patient_id;
  

  //run the query and return success or failure
  $result = runQuery($query, $conn);
  //  printArr($result);
  if(noError($result)){
    $returnArr["errCode"][-1] = -1;
    //$returnArr["errMsg"] = mysqli_insert_id($conn);
  } else {
    $returnArr["errCode"][1] = 1;
    $returnArr["errMsg"] = "Failed to add patient: ".$result["errMsg"]; 
  }
  //printArr($returnArr);
  return $returnArr; 
} 
function editPatient($userInfo,$conn){
	global $blanks;
	$returnArr = array();
	//$editPatientProfileInfo = editPatientProfileInfo($userInfo,$conn);
	//if(noError($editPatientProfileInfo)){
		$editDoctorsPatient = editDoctorsPatient($userInfo['doctor_patient_id'],$userInfo['private_notesedit'],$userInfo['label'], $conn);

		if(noError($editDoctorsPatient)){
			$returnArr["errCode"][-1] = -1;
			$returnArr["errMsg"] = "Patient edited successfully";
		}else{
			$returnArr["errCode"][2] = 2;
			$returnArr["errMsg"] = "Failed to edit patient info: ".$editDoctorPatientInfo["errMsg"];
		}
	/*}else{
		$returnArr["errCode"][1] = 1;
		$returnArr["errMsg"] = "Failed to edit patient info: ".$editPatientProfileInfo["errMsg"];
	}*/
	return $returnArr;
} 

function editPatientProfileInfo($userInfo, $conn){
  	global $blanks;
  
  	$returnArr = array();
  	$values=array();
//echo $userInfo['user_dob'];
//echo date("Y-m-d", strtotime(str_replace('/','-',$userInfo["user_dob"])));
  //initializing the query string variables
 	 $query = "UPDATE users"; 
  	if(isset($userInfo["user_mob"]) && !(in_array($userInfo["user_mob"], $blanks))){
	$values["user_mob"] = $userInfo["user_mob"];
	}
	if(isset($userInfo["country_code"]) && !(in_array($userInfo["country_code"], $blanks))){
		$values["country_code"] = $userInfo["country_code"];
	}
	if(isset($userInfo["user_first_name"]) && !(in_array($userInfo["user_first_name"], $blanks))){
		$values["user_first_name"] = $userInfo["user_first_name"];
	}
	if(isset($userInfo["user_last_name"]) && !(in_array($userInfo["user_last_name"], $blanks))){
	 	$values["user_last_name"] = $userInfo["user_last_name"];
	}
	if(isset($userInfo["user_email"]) && !(in_array($userInfo["user_email"], $blanks))){
		 $values["user_email"] = $userInfo["user_email"];
	}
	if(isset($userInfo["user_gender"]) && !(in_array($userInfo["user_gender"], $blanks))){
	    $values["user_gender"] = $userInfo["user_gender"];
	}
	if(isset($userInfo["user_image"]) && !(in_array($userInfo["user_image"], $blanks))){
	    $values["user_image"] = $userInfo["user_image"];
	}
	if(isset($userInfo["user_dob"]) && !(in_array($userInfo["user_dob"], $blanks))){
	    $values["user_dob"] = date("Y-m-d", strtotime(str_replace('/','-',$userInfo["user_dob"])));
	}
	  //looping thru the col names and values arrays to for related query strings
	  $colNamesStr = ""; $valuesStr = ""; $updateStr = "";
	  foreach($values as $colName=>$val){
	      if(!in_array($updateStr, $blanks))
	        $updateStr .= ",";
	      $updateStr .= cleanQueryParameter($conn,$colName)."='".cleanQueryParameter($conn,$val)."'";
	  }
	  
	 $query .= " SET ".$updateStr." WHERE user_id=".$userInfo["user_id"];
	  //run the query and return success or failure

	  $result = runQuery($query, $conn);
	 // printArr($result);
	  if(noError($result)){
	    $returnArr["errCode"][-1] = -1;
	    $returnArr["errMsg"] = "User Info Succesfully edited";

	  } else {
	    $returnArr["errCode"][1] = 1;
	    $returnArr["errMsg"] = "User Info edit failed: ".$result["errMsg"]; 
	  }
	  //printArr($returnArr);
	  return $returnArr;
}
function editDoctorsPatient($doctor_patient_id,$private_notes,$label, $conn)
{
	global $blanks;
    $returnArr = array();
   	$updateQuery = "UPDATE doctors_patient SET private_notes='".$private_notes."',label='".$label."' WHERE doctor_patient_id=".$doctor_patient_id;
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="patient edited successfully";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to edit patient".mysqli_error();
    }
  return $returnArr;
}
function setPatientLabel($userInfo,$conn){
	global $blanks;
    $returnArr = array();

    $updateQuery = "UPDATE doctors_patient SET label='".$userInfo['label']."' WHERE doctor_patient_id=".$userInfo['doctor_patient_id'];
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="Label successfully updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to set status";
    }

    return $returnArr;	
}
//htmlspecialchars($userInfo['richPrivateNote'], ENT_QUOTES)
function setPatientPrivateNote($userInfo,$conn){
	global $blanks;
    $returnArr = array();
   // printArr($userInfo);

    $updateQuery = "UPDATE doctors_patient SET private_notes='".$userInfo['richPrivateNote']."' WHERE doctor_patient_id=".$userInfo['doctor_patient_id'];
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="Private note successfully updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed to set private note";
    }

    return $returnArr;
}

function deletePatient($userInfo,$conn){
	global $blanks;
    $returnArr = array();

    $updateQuery = "UPDATE doctors_patient SET status='Deleted' WHERE doctor_patient_id=".$userInfo['doctor_patient_id'];
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="Patient successfully deleted";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Failed deleing patient";
    }

    return $returnArr;
}
function searchPatients($userInfo,$conn){

    $query="SELECT dpc.*,u.* FROM doctors_patient_cases dpc, users u WHERE dpc.patient_id=u.user_id= ( SELECT patient_id FROM doctors_patient WHERE doctor_id=".$userInfo['doctor_id'].")";
  /*if (empty($userInfo['filterLabel']) && !empty($userInfo['filterName'])) {
    $query="SELECT * FROM `admin_contact_doctor` WHERE location LIKE '%$locationName%'";
    $countquery = "SELECT count(*) as totle FROM admin_contact_doctor WHERE location LIKE '%$locationName%'";
  }
  else if(!empty($userInfo['filterLabel']) && empty($userInfo['filterName'])) {
    $query="SELECT * FROM `admin_contact_doctor` WHERE Name LIKE '%$doctorName%'";
    $countquery = "SELECT count(*) as totle FROM admin_contact_doctor WHERE Name LIKE '%$doctorName%'";
  }

  else */if(!empty($userInfo['filterLabel']) && !empty($userInfo['filterName']))
  {
   /* $query="SELECT dp.*,dpc.*, u.* FROM `doctors_patient` dp ,doctors_patient_cases dpc, users u WHERE dp.doctor_id =".$userInfo['doctor_id']."  Name LIKE '%$doctorName%' and location LIKE '%$locationName%'";
    $countquery = "SELECT count(*) as totle FROM admin_contact_doctor WHERE Name LIKE '%$doctorName%' and location LIKE '%$locationName%'";*/
     $query="SELECT dpc.*,u.* FROM doctors_patient_cases dpc, users u WHERE dpc.patient_id=u.user_id= ( SELECT patient_id FROM doctors_patient WHERE doctor_id=".$userInfo['doctor_id'].")";
  }
}


function getDoctorsPatient($conn, $user,$pageNo=1,$limit=10) {


   global $blanks;
   $returnArr = array();
   $end=$pageNo*$limit;
   $start=$end-10;
   $cnt='';
   $strQuery='';/*
   $check=getDoctordetails($conn,$user);*/
   $doctor_id=$user;
   //  echo $cntquery = "SELECT count(*) as countCases FROM secondopinion WHERE name='".$user."'";
   $cnt=getPatientCount($conn,$doctor_id);

   
   $strQuery .= sprintf("SELECT * FROM doctors_patient WHERE doctor_id='".$doctor_id."' and status='Active'");
   $strQuery .= sprintf(" ORDER BY created_on  DESC LIMIT %s,%s",$start,$limit);

   //  printArr($result);
   $query=runQuery($strQuery,$conn);
   if(noError($query)){
    $returnArr['errCode'][-1]=-1;
    $result=$query['dbResource'];
    $arr=array();
    while ($row = mysqli_fetch_assoc($result)) {
      $arr[]=$row;
    }
    $returnArr['errMsg']=$arr;
    $returnArr['countCases']=$cnt['errMsg'];
  }else{
    $returnArr['errMsg']=$query['errMsg'];
    $returnArr['errCode'][1]=1;
  }


  return $returnArr;

  }


    function getPatientCount($conn,$doctor_id)
  { 

    global $totalQuestions;
    $query="SELECT COUNT(*) as countCases FROM doctors_patient WHERE doctor_id='".$doctor_id."' and status='Active'";

    $result=runQuery($query,$conn); 
    if(noError($result)){
      $row=mysqli_fetch_assoc($result["dbResource"]);
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$row["countCases"];

    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching patient count";
    }

    return $returnArr;
  }

    function getdoctorspatientInfo($doctor_patient_id,$conn) {


   global $blanks;
   $returnArr = array();
   
   $strQuery .= sprintf("SELECT * FROM doctors_patient WHERE doctor_patient_id='".$doctor_patient_id."' and status='Active'");

   //  printArr($result);
   $result=runQuery($strQuery,$conn);
   if(noError($result)){
		if(mysqli_num_rows($result["dbResource"])==0){
			//username does not exist
			$returnArr["errCode"][1]=1;
			$returnArr["errMsg"] = "Could not find username: ".$result["errMsg"];
		} else {		
			$returnArr["errCode"][-1]=-1;
			$returnArr["errMsg"] = mysqli_fetch_assoc($result["dbResource"]);	
		}
	} else {
		$returnArr["errCode"][3]=3;
		$returnArr["errMsg"] = "Could not get user info: ".$result["errMsg"];
	}

//printArr($returnArr);
  return $returnArr;

  }
 function getPatientInfo($patient_id,$doctor_patient_id,$conn){
 	$userinfo=array();
 	$returnArr = array();
 	$getPatientInfo=getUserInfoWithUserId($patient_id,3,$conn); 

 	array_push($userinfo,$getPatientInfo['errMsg']);
 	$getdoctorspatientInfo=getdoctorspatientInfo($doctor_patient_id,$conn);
 	array_push($userinfo,$getdoctorspatientInfo['errMsg']);
 	$returnArr['errCode'][-1]=-1;
 	$returnArr['errMsg']=$userinfo;
 	return $returnArr;
 }
/*function getPatientLabelInfo($doctor_patient_id,$conn) {


   global $blanks;
   $returnArr = array();
   
   $strQuery .= sprintf("SELECT * FROM doctors_patient WHERE doctor_patient_id='".$doctor_patient_id."' and status='Active'");

   //  printArr($result);
   $result=runQuery($strQuery,$conn);
   if(noError($result)){
		if(mysqli_num_rows($result["dbResource"])==0){
			//username does not exist
			$returnArr["errCode"][1]=1;
			$returnArr["errMsg"] = "Could not find username: ".$result["errMsg"];
		} else {		
			$returnArr["errCode"][-1]=-1;
			$returnArr["errMsg"] = mysqli_fetch_assoc($result["dbResource"]);	
		}
	} else {
		$returnArr["errCode"][3]=3;
		$returnArr["errMsg"] = "Could not get user info: ".$result["errMsg"];
	}


  return $returnArr;

  }*/
   function getRecentCaseOfUser($patient_id,$doctor_id,$conn)
  { 

    global $totalQuestions;
    /*$query="SELECT COUNT(*) as countCases FROM doctors_patient_cases WHERE patient_id='".$patient_id."' and doctor_id='".$doctor_id."' and status!=2";*/
   $query= sprintf("SELECT * FROM doctors_patient_cases WHERE patient_id='".$patient_id."' and doctor_id='".$doctor_id."' ORDER BY created_on  DESC ");

    $result=runQuery($query,$conn); 
    if(noError($result)){
      $row=mysqli_fetch_assoc($result["dbResource"]);
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]= $row;

    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching questions data";
    }

    return $returnArr;
  }

?>