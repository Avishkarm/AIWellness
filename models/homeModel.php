<?php
require_once("../utilities/config.php");
require_once("../utilities/dbutils.php");
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
} else {
	    //printArr("Database Error");
	exit;
}

function getDiseaseNameSuggetion($name,$conn){
	$query="SELECT * FROM `complaint_info` WHERE Common_name LIKE '%$name%' or Diagnostic_term LIKE '%$name%'";
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
		}else{
			$returnArr['errMsg']="No matched data found";
		}
		
	}else{
		$returnArr['errCode']=1;
		$returnArr['errMsg']="Error fetching data".mysqli_error();
	}	 
	//printArr($res);
	return $returnArr;

}
//htmlspecialchars_decode($name, ENT_QUOTES);
function getHomeopathyModalInfo($name,$conn){
	$query='SELECT * FROM `complaint_info` WHERE Common_name="'.htmlspecialchars_decode($name, ENT_QUOTES).'" or Diagnostic_term = "'.htmlspecialchars_decode($name, ENT_QUOTES).'"';
	$result=runQuery($query,$conn);
	if(noError($result)){
		$res=array();
		while($row=mysqli_fetch_assoc($result['dbResource'])){
			$res=$row;
			//printArr($row);
		}	
		$returnArr['errCode']=-1;
		$returnArr['errMsg']=$res;
	}else{
		$returnArr['errCode']=1;
		$returnArr['errMsg']="Error fetching data".mysqli_error();
	}	 
	//printArr($returnArr);
	return $returnArr;

}

?>