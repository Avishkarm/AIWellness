<?php
 require_once("../utilities/config.php");
 require_once("../utilities/dbutils.php");

 function getAllDoctorsContact($conn) {

    $returnArr = array();
    global $blanks; 

   
   /**/$res = array();
   $countquery = "SELECT count(*) as totle FROM admin_contact_doctor where doc_status=1";
    $countresult = runQuery($countquery, $conn);
     $countDoctors=mysqli_fetch_assoc($countresult["dbResource"]);
     //printArr($countDoctors);
    $query = "SELECT * FROM admin_contact_doctor where doc_status=1";
    $result = runQuery($query, $conn);
    if(noError($result)){
     
      while($row=mysqli_fetch_assoc($result["dbResource"])){
       $res[]=$row;
     }
     $returnArr["errCode"][-1]=-1;
     $returnArr["errMsg"]=$res;
     $returnArr["totleDoctors"]=$countDoctors['totle'];
   } else {
    $returnArr["errCode"][1]=1;
    $returnArr["errMsg"]="Error fetching doctors contact data";
  }
  //printArr($returnArr);
  return $returnArr;
  }

  function getDoctorsContact($doctorName,$locationName,$conn){
  
  if (empty($doctorName) && !empty($locationName)) {
    $query="SELECT * FROM `admin_contact_doctor` WHERE doc_status=1 and location LIKE '%$locationName%'";
    $countquery = "SELECT count(*) as totle FROM admin_contact_doctor WHERE doc_status=1 and location LIKE '%$locationName%'";
  }
  elseif (!empty($doctorName) && empty($locationName)) {
    $query="SELECT * FROM `admin_contact_doctor` WHERE doc_status=1 and Name LIKE '%$doctorName%'";
    $countquery = "SELECT count(*) as totle FROM admin_contact_doctor WHERE doc_status=1 and Name LIKE '%$doctorName%'";
  }
  else
  {
    $query="SELECT * FROM `admin_contact_doctor` WHERE doc_status=1 and Name LIKE '%$doctorName%' and location LIKE '%$locationName%'";
    $countquery = "SELECT count(*) as totle FROM admin_contact_doctor WHERE doc_status=1 and Name LIKE '%$doctorName%' and location LIKE '%$locationName%'";
  }

    $countresult = runQuery($countquery, $conn);
    $countDoctors=mysqli_fetch_assoc($countresult["dbResource"]);
  $result=runQuery($query,$conn);
  if(noError($result)){
    $res=array();
    while($row=mysqli_fetch_assoc($result['dbResource'])){
      $res[]=$row;
    }
    $row_cnt = mysqli_num_rows($result['dbResource']);  
    $returnArr['errCode']=-1;
    if($row_cnt!=0){    
      $returnArr['errMsg']=$res;
        $returnArr["totleDoctors"]=$countDoctors['totle'];
    }else{
      $returnArr['errMsg']="No matched data found";
      $returnArr["totleDoctors"]=$countDoctors['totle'];
    }
    
  }else{
    $returnArr['errCode']=1;
    $returnArr['errMsg']="Error fetching data".mysqli_error();
  }  
  //printArr($res);
  return $returnArr;

}


?>