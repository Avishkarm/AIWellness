<?php
session_start();

require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/promocodeModel.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
    $user = "";

    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){

        if($_POST['type'] == "dashboard_reports"){

        $columnHeaders=array();
        $dashboard_details=array();
        $total_dashboard_details=array();
        /*$columnHeaders[0] = "Logged In Users";
        $columnHeaders[1] = "Logged Out Users";
        $columnHeaders[2] = "Total Users";
        $dashboard_details[0] = $_SESSION['total_no_of_logged_in_user'];
        $dashboard_details[1] = $_SESSION['total_no_of_logged_out_user'];
        $dashboard_details[2] = $_SESSION['total_users'];
        $total_dashboard_details[0] = $columnHeaders;
        $total_dashboard_details[1]=$dashboard_details;*/
        $couponId=$_POST['couponId'];
        $getExportPromocode = getExportPromocode($couponId,$conn);
        foreach ($getExportPromocode['errMsg'] as $key => $value) {
            # code...
            if($key==0){
                $columnHeaders[0] = 'Title';
                $columnHeaders[1] = 'Promocode';
                $columnHeaders[2] = 'Percent Discount';
                $columnHeaders[3] = 'Expiry Date';
            }
                $dashboard_details[0] = $value['title'];
                $dashboard_details[1] = $value['code'];
                $dashboard_details[2] = $value['percent_discount'];
                $dashboard_details[3] = $value['expiry_date'];
            $total_dashboard_details[0] = $columnHeaders;
            $total_dashboard_details[$key+1]=$dashboard_details;

        }
        
        
        $dashboard = 'promocodes.csv';
         header( "Content-Type: text/csv;charset=utf-8" );
         header( "Content-Disposition: attachment;filename=\"$dashboard\"" );
         header("Pragma: no-cache");
         header("Expires: 0");

         $fp= fopen('php://output', 'w');
//printArr($total_dashboard_details);die;
         foreach ($total_dashboard_details as $fields) 
         {
            fputcsv($fp, $fields);
         }
         fclose($fp);
         exit();

    
    }


    } else {        
        $returnArr["Result"]="ERROR";
        $returnArr["Message"]="You do not have sufficient privileges to access this page";
    }
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Database Error";
}

       
?>