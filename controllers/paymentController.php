<?php

require_once("../utilities/config.php");
require_once("../utilities/dbutils.php");
include("../models/paymentModel.php");
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
} else {
	    //printArr("Database Error");
	exit;
}

if(isset($_POST) && !empty($_POST)){
	if($_POST['type']=='freeTrial'){
		$user_id=cleanQueryParameter($conn,$_POST['user_id']);
		$plan_id=0;
		$plan_name='trial';
		$date = strtotime("+30 day");
    	$expiry_date= date('Y-m-d H:i:s', $date);
    	//on active plan then plan_staus=1 and if not on plan or plan expired plan_status=0
    	$plan_status=cleanQueryParameter($conn,$_POST['plan_status']);;
    	$updatePlan=updatePlan($user_id,$plan_id,$plan_status,$expiry_date,$conn);
    	if(noError($updatePlan)){
    		$returnArr['errCode']=-1;
    		$returnArr['errMsg']="Success";

    	}else{
    		$returnArr['errCode']=2;
    		$returnArr['errMsg']=$updatePlan['errMsg'];
    	}
		echo json_encode($returnArr);	
	}else if($_POST['type']=='getPlanPrice'){
         $plan_id=cleanQueryParameter($conn,$_POST['plan_id']);
         $country_id=cleanQueryParameter($conn,$_POST['country_id']);
        $region_id=1;
        $getRegion=getRegionDetails($conn, $status=1);
        //printArr($getRegion); die;
        foreach ($getRegion['errMsg'] as $key => $value) {
            $countries=explode(",", $value['region_countries']);
            if (in_array($country_id, $countries)){
                 $returnArr['region_id']=$value['region_id'];
                //$returnArr['currency_symbol']=$value['currency_symbol'];
                 $returnArr['currency_symbol']='&#8377;';
                 $returnArr['region_name']=$value['region_name'];
                $returnArr['currency_name']=$value['longforms']."(".$value['shortforms'].")";
                $returnArr['current_price']=$value['current_price'];
                $region_id=$value['region_id'];
                break;  
            }
        }
       // printArr($returnArr);
        $getPlanPrice=getPlanPrice($plan_id,$region_id,$conn);
        $returnArr['price']= $getPlanPrice['errMsg'];
        echo json_encode($returnArr);
    }else if($_POST['type']=='getDiscount'){
        $price=cleanQueryParameter($conn,$_POST['price']);
        $promocode=cleanQueryParameter($conn,$_POST['promocode']);
        $getDiscount=getDiscount($promocode,$conn);
        if(noError($getDiscount)){
            $expiry=$getDiscount['errMsg']['expiry'];
            if($expiry<0){
                $returnArr['errCode']=2;
                $returnArr['errMsg']="Expired pcode";
            }else if($getDiscount['errMsg']['status']==0){
                $returnArr['errCode']=4;
                $returnArr['errMsg']="Used promocode";
            }else{
                $returnArr['errCode']=-1;
                $discount=$getDiscount['errMsg']['percent_discount'];
                if($discount==0 || $discount==Null || $discount==""){
                    $returnArr['amount']=$price;
                    $returnArr['discount']=0;
                }else{
                    $returnArr['discount']=round(($price*$discount)/100);
                    $returnArr['amount']=$price-$returnArr['discount'];
                }
                
            }
            
        }else{
            $returnArr['errCode']=3;
            $returnArr['errMsg']=$getDiscount['errMsg'];
        }
       echo json_encode($returnArr);
    }
}
?>