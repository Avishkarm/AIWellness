<?php

function listCountries($conn){
	$returnArr = array();
	$res = array();
	$query = "Select * From countries";

	$query = runQuery($query, $conn);

	if(noError($query)){
		 while($row=mysql_fetch_assoc($query["dbResource"])){
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

function getCountryByID($conn, $id, $colName="*"){
	$returnArr = array();
	$res = array();
	$query = "Select ".$colName." From countries Where id IN(".$id.")";

	$query = runQuery($query, $conn);

	if(noError($query)){
		 while($row=mysql_fetch_assoc($query["dbResource"])){
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

// function listManagePricing($conn, $status=1){
// 	$returnArr = array();
// 	$res = array();
// 	$query = "Select * From pricing WHERE status=".$status;

// 	$query = runQuery($query, $conn);

// 	if(noError($query)){
// 		 while($row=mysql_fetch_assoc($query["dbResource"])){
// 		 	$res[$row['priceId']] = $row;
// 		 }
// 		$returnArr['errCode'][-1] = -1;
// 		$returnArr['errMsg'] = $res;		 
// 	}else{
// 		$returnArr['errCode'][2] = 2;
// 		$returnArr['errMsg'] = $query['errMsg'];
// 	}

// 	return $returnArr;
// }
// function listManageRegion($conn, $status=1){
// 	$returnArr = array();
// 	$res = array();
// 	$query = "Select * From manageRegion WHERE status=".$status;

// 	$query = runQuery($query, $conn);

// 	if(noError($query)){
// 		 while($row=mysql_fetch_assoc($query["dbResource"])){
// 		 	$res[$row['region_id']] = $row;
// 		 }
// 		$returnArr['errCode'][-1] = -1;
// 		$returnArr['errMsg'] = $res;		 
// 	}else{
// 		$returnArr['errCode'][2] = 2;
// 		$returnArr['errMsg'] = $query['errMsg'];
// 	}

// 	return $returnArr;
// }

function getManagePricingById($conn, $priceId){
	$returnArr = array();
	$res = array();
	$query = sprintf("Select * From pricing WHERE priceId='%s'",$priceId);

	$query = runQuery($query, $conn);

	if(noError($query)){
		 while($row=mysql_fetch_assoc($query["dbResource"])){
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
function getManageRegionById($conn, $region_id){
	$returnArr = array();
	$res = array();
	$query = sprintf("Select * From manageRegion WHERE region_id IN (%s)",$region_id);

	$query = runQuery($query, $conn);

	if(noError($query)){
		 while($row=mysql_fetch_assoc($query["dbResource"])){
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

// function getCurrencyByID($conn, $id, $colName="*"){
// 	$returnArr = array();
// 	$res = array();
// 	$query = "Select ".$colName." From currency_value Where id IN(".$id.")";


// 	$query = runQuery($query, $conn);

// 	if(noError($query)){
// 		 while($row=mysql_fetch_assoc($query["dbResource"])){
// 		 	$res[] = $row;
// 		 }
// 		$returnArr['errCode'][-1] = -1;
// 		$returnArr['errMsg'] = $res;		 
// 	}else{
// 		$returnArr['errCode'][2] = 2;
// 		$returnArr['errMsg'] = $query['errMsg'];
// 	}

// 	return $returnArr;
// }
?>