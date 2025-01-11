<?php

function listCountries($conn){
	$returnArr = array();
	$res = array();
	$query = "Select * From countries";

	$query = runQuery($query, $conn);

	if(noError($query)){
		 while($row=mysqli_fetch_assoc($query["dbResource"])){
		 	$res[$row['id']] = $row;
		 }
		$returnArr['errCode'][-1] = -1;
		$returnArr['errMsg'] = $res;		 
	}else{
		$returnArr['errCode'][2] = 2;
		$returnArr['errMsg'] = $query['errMsg'];
	}

	return $returnArr;
}


function listCountriesNotUsed($conn){
	$returnArr = array();
	$res = array();
	$query = "SELECT DISTINCT c.country_id, c.name 
						FROM countries c 
					    WHERE c.country_id NOT IN
					    						(SELECT DISTINCT c.country_id 
					                             FROM `countries` c , `manageRegion` mr 
					                             WHERE FIND_IN_SET(c.country_id, mr.region_countries) AND mr.status =1
					                            )";

	$query = runQuery($query, $conn);

	if(noError($query)){
		 while($row=mysqli_fetch_assoc($query["dbResource"])){
		 	$res[$row['country_id']] = $row;
		 }
		$returnArr['errCode'][-1] = -1;
		$returnArr['errMsg'] = $res;		 
	}else{
		$returnArr['errCode'][2] = 2;
		$returnArr['errMsg'] = $query['errMsg'];
	}

	return $returnArr;
}
function getCountryByID($conn, $id, $colName="*"){
	$returnArr = array();
	$res = array();
	$query = "Select ".$colName." From countries Where id IN(".$id.")";

	$query = runQuery($query, $conn);

	if(noError($query)){
		 while($row=mysqli_fetch_assoc($query["dbResource"])){
		 	$res[] = $row;
		 }
		$returnArr['errCode'][-1] = -1;
		$returnArr['errMsg'] = $res;		 
	}else{
		$returnArr['errCode'][2] = 2;
		$returnArr['errMsg'] = $query['errMsg'];
	}

	return $returnArr;
}

function listCurrency($conn){
	$returnArr = array();
	$res = array();
	$query = "Select * From currency_value";

	$query = runQuery($query, $conn);

	if(noError($query)){
		 while($row=mysqli_fetch_assoc($query["dbResource"])){
		 	$res[$row['id']] = $row;
		 }
		$returnArr['errCode'][-1] = -1;
		$returnArr['errMsg'] = $res;		 
	}else{
		$returnArr['errCode'][2] = 2;
		$returnArr['errMsg'] = $query['errMsg'];
	}

	return $returnArr;
}
/*
function listCurrency($conn){
	$returnArr = array();
	$res = array();
	$query = "Select * From countries";

	$query = runQuery($query, $conn);

	if(noError($query)){
		 while($row=mysqli_fetch_assoc($query["dbResource"])){
		 	$res[$row['id']] = $row;
		 }
		$returnArr['errCode'][-1] = -1;
		$returnArr['errMsg'] = $res;		 
	}else{
		$returnArr['errCode'][2] = 2;
		$returnArr['errMsg'] = $query['errMsg'];
	}

	return $returnArr;
}*/


function getCurrencyByID($conn, $id, $colName="*"){
	$returnArr = array();
	$res = array();
	$query = "Select ".$colName." From currency_value Where id IN(".$id.")";


	$query = runQuery($query, $conn);

	if(noError($query)){
		 while($row=mysqli_fetch_assoc($query["dbResource"])){
		 	$res[] = $row;
		 }
		$returnArr['errCode'][-1] = -1;
		$returnArr['errMsg'] = $res;		 
	}else{
		$returnArr['errCode'][2] = 2;
		$returnArr['errMsg'] = $query['errMsg'];
	}

	return $returnArr;
}



function listManageRegion($conn, $status=1){
	$returnArr = array();
	$res = array();
	$query = "Select * From manageRegion WHERE status=".$status;

	$query = runQuery($query, $conn);

	if(noError($query)){
		 while($row=mysqli_fetch_assoc($query["dbResource"])){
		 	$res[$row['region_id']] = $row;
		 }
		$returnArr['errCode'][-1] = -1;
		$returnArr['errMsg'] = $res;		 
	}else{
		$returnArr['errCode'][2] = 2;
		$returnArr['errMsg'] = $query['errMsg'];
	}

	return $returnArr;
}



function getManageRegionById($conn, $region_id){
	$returnArr = array();
	$res = array();
	$query = sprintf("Select * From manageRegion WHERE region_id='%s'",$region_id);

	$query = runQuery($query, $conn);

	if(noError($query)){
		 while($row=mysqli_fetch_assoc($query["dbResource"])){
		 	$res[] = $row;
		 }
		$returnArr['errCode'][-1] = -1;
		$returnArr['errMsg'] = $res;		 
	}else{
		$returnArr['errCode'][2] = 2;
		$returnArr['errMsg'] = $query['errMsg'];
	}

	return $returnArr;
}

?>