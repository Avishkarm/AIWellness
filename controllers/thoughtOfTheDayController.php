<?php


require_once("../utilities/config.php");
require_once("../utilities/dbutils.php"); 
include_once("../views/metaInclude.php");

//database connection
$conn = createDbConnection($servername, $username, $password, $dbname);
$user = "";
$returnArr=array();
$userInfo=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
  $msg = "Success : Search database connection";
  //$xml_data['step'.++$i]["data"] = $i.". {$msg}";
} else {
	printArr("Database Error");
	exit;
}


function httpGet($url)
{
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//  curl_setopt($ch,CURLOPT_HEADER, false); 
 
    $output=curl_exec($ch);
    //print_r(curl_exec($ch));
 
    curl_close($ch);
    return $output;
}
 
$data = httpGet("http://quotes.rest/qod.json");
$data = json_decode($data, TRUE);
//printArr($data);
$quote=$data['contents']['quotes'][0]['quote'];

$query = "INSERT INTO quotes (`quote`,`created_on`,`updated_on`) VALUES('".$quote."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
							
	$result = runQuery($query, $conn);

	if(noError($result)){
		echo "SUCCESS";

	}else{
		echo "ERROR";
	}

?>
