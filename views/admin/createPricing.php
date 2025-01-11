<?php
session_start();

require_once("../../controllers/config.php");
require_once("../../controllers/utilities.php");
require_once("../../controllers/dbutils.php");
require_once("../../controllers/accesscontrol.php");
require_once("../../controllers/authentication.php");
require_once("../../controllers/admin/managePricing/list.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();

if(noError($conn)){
	$conn = $conn["errMsg"];
} else {
	printArr("Database Error");
	exit;
}


$user = "";
if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
	$user = $_SESSION["user"];	
} else {
	printArr("You do not have sufficient privileges to access this page");
	exit;
}	


$regionAssign = listManageRegion($conn,1);

if(noError($regionAssign)){
	$regionAssign = $regionAssign['errMsg'];
}else{
	printArr("Error Fetching regionAssign Record $regionAssign");
}



// $currency = listCurrency($conn);

// if(noError($currency)){
// 	$currency = $currency['errMsg'];
// }else{
// 	printArr("Error Fetching Countries Record $countries");
// }

$editflag = false;

if(isset($_GET['priceId']) and !empty($_GET['priceId'])){
	$editflag = true;
	$priceId = cleanQueryParameter($_GET['priceId']);

	$managePricing = getManagePricingById($conn, $priceId);

	if(noError($managePricing)){
		$managePricing = $managePricing['errMsg'][0];
	}else{
		printArr($managePricing['errMsg']);
	}
}

if(isset($_POST['Submit'])){
	$returnArr = array();

	$region_id = cleanQueryParameter($_POST['region_countries']);
    $amount = cleanQueryParameter($_POST['amount']);
	$type = cleanQueryParameter($_POST['type']);
	$status = cleanQueryParameter($_POST['status']);

    $query1 = sprintf("UPDATE pricing SET status = 0 where `region_id`= %s AND type=%s",$region_id,$type);

    $query1 = runQuery($query1, $conn);

    if(noError($query1)){
    	$query = sprintf("INSERT INTO pricing(region_id, amount, type,status,created_date, updated_date) VALUES('%s','%s','%s','%s','%s','%s')", $region_id,$amount,$type, $status, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

    	$query = runQuery($query, $conn);

    	if(noError($query)){
    		$returnArr['errCode'][-1] = -1;
    		$returnArr['errMsg'] = "Successfully Added";
    	}else{
    		$returnArr['errCode'][-1] = -1;
    		$returnArr['errMsg'] = "Error in Adding".$query['errMsg'];
    	}
    }else{
        $returnArr['errCode'][1] = 1;
        $returnArr['errMsg'] = "Error in Updating".$query1['errMsg'];
    }
	printArr($returnArr['errMsg']);
	$redirectUrl = "managePricing.php";
	print_r("<script>setTimeout(function(){window.location.href='".$redirectUrl."';},3000)</script>");
}

if(isset($_POST['Edit'])){
	$returnArr = array();

    $region_id = cleanQueryParameter($_POST['region_countries']);

    $amount = cleanQueryParameter($_POST['amount']);
    $type = cleanQueryParameter($_POST['type']);
    $status = cleanQueryParameter($_POST['status']);
	$priceId = cleanQueryParameter($_POST['priceId']);

	$query = sprintf("UPDATE pricing set region_id='%s',amount='%s',type='%s',status='%s', updated_date='%s' WHERE priceId='%s'", $region_id, $amount,$type, $status, date('Y-m-d H:i:s'),$priceId);


	$query = runQuery($query, $conn);

	if(noError($query)){
		$returnArr['errCode'][-1] = -1;
		$returnArr['errMsg'] = "Successfully Updated";
	}else{
		$returnArr['errCode'][-1] = -1;
		$returnArr['errMsg'] = "Error in Updating";
	}

	printArr($returnArr['errMsg']);
	$redirectUrl = "managePricing.php";
	print_r("<script>setTimeout(function(){window.location.href='".$redirectUrl."';},3000)</script>");
}





?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>eHeilung</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="../css/chosen.min.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style type="text/css">
            .rowTable{
                border: 1px solid rgba(0, 0, 0, 0.17);
                width: 100%;
                margin-top: 20px;
            }
            .rowTable tr:first-child{
                background: rgb(51, 51, 51);
                color: white;
            }
            table.rowTable tr td, table.rowTable tr th {
                padding: 5px;
                border: 1px solid rgba(232, 229, 229, 0.13);
                text-align: center;
            }


        </style>
    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="index.html" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                eHeilung
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                       	
                        <!-- Notifications: style can be found in dropdown.less -->
                     
                        <!-- Tasks: style can be found in dropdown.less -->
                        
                        <!-- User Account: style can be found in dropdown.less -->
                      	Welcome Admin <a href="../../controllers/logout.php">Logout</a><br>
					
						
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">                
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">                    
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                                        <ul class="sidebar-menu">
                        <li class="active">
                            <a href="index.php">
                                <i class="fa fa-check-square"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="manageDoctorsContact.php">
                                <i class="fa fa-check-square"></i> <span>Doctors Contact</span>
                            </a>
                        </li>
                        <li>
                            <a href="manageTreatmentType.php">
                                <i class="fa fa-check-square"></i> <span>Manage Treatment</span>
                            </a>
                        </li>
                      <!--   <li>
                            <a href="manageDiseaseCompassConclusion.php">
                                <i class="fa fa-check-square"></i> <span>Manage Disease Compass</span>
                            </a>
                        </li> -->
                        <li>
                            <a href="manageComplaints.php">
                                <i class="fa fa-check-square"></i> <span>Manage complaints</span>
                            </a>
                        </li>
                         <li>
                            <a href="manage2ndOpinionQuestion.php">
                                <i class="fa fa-check-square"></i> <span>Manage 2nd opinion question</span>
                            </a>
                        </li>
                         <li>
                            <a href="manage2ndOpinionObservation.php">
                                <i class="fa fa-check-square"></i> <span>Manage 2nd opinion observations</span>
                            </a>
                        </li>
                        <li>
                            <a href="dosNdonts.php">
                                <i class="fa fa-check-square"></i> <span>Do's and don't's</span>
                            </a>
                        </li>
                        <li>
                            <a href="personalityFactorMaster.php">
                                <i class="fa fa-check-square"></i> <span>Personality factor master</span>
                            </a>
                        </li>
                        <li>
                            <a href="rubrics.php">
                                <i class="fa fa-check-square"></i> <span>Manage rubrics</span>
                            </a>
                        </li>
                        <li>
                            <a href="remedies.php">
                                <i class="fa fa-check-square"></i> <span>Manage remedies</span>
                            </a>
                        </li>
                        <li>
                            <a href="manageModalities.php">
                                <i class="fa fa-check-square"></i> <span>Manage modalities</span>
                            </a>
                        </li>
                        <li>
                            <a href="manageSensation.php">
                                <i class="fa fa-check-square"></i> <span>Manage sensation</span>
                            </a>
                        </li>
                        <li>
                            <a href="manageRegion.php">
                                <i class="fa fa-check-square"></i> <span>Manage Region</span>
                            </a>
                        </li>
                        <li>
                            <a href="manageCoupon.php">
                                <i class="fa fa-check-square"></i> <span>Manage Coupon</span>
                            </a>
                        </li>
                        <li>
                            <a href="managePlan.php">
                                <i class="fa fa-check-square"></i> <span>Manage Plan</span>
                            </a>
                        </li>
                         <li>
                            <a href="adminSubscribersList.php">
                                <i class="fa fa-check-square"></i> <span>Manage Subscribers</span>
                            </a>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side"> 
                <!-- Main content -->
                <section class="content" style="text-align: center;">
                    <form method="POST" action="createPricing.php" style="width: 80%;text-align: left;">
                        
                        <div class="form-group">
                            <label for="country">Assign Region * :</label>
                            <select name="region_countries" id="country" class="form-control chosen-select" style="width: 100%;" required>
                                    <option value>Select Region Name</option>
                                    <?php
                                        foreach ($regionAssign as $key => $value) { 
                                            $countryArr = explode(",", $managePricing['region_id']);
                                         ?>

                                                <option value="<?php echo $key; ?>" <?php echo ($editflag and in_array($key, $countryArr))? 'selected':'';?>><?php echo $value['region_name']; ?></option> 
                                    <?php   }
                                    ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="purchaseDate">Amount * :</label>
                            <input type="number" class="form-control" name="amount" id="amount" placeholder="amount" value = "<?php echo ($editflag)?$managePricing['amount']:'';?>" required> 
                        </div>
                        <div class="form-group">
                            <label for="type">Type * :</label>
                            <select name="type" id="type" class="form-control" required>
                                   <option value="1" <?php echo ($editflag and $managePricing['type']==1)? 'selected':'';?>>Patient</option> 
                                   <option value="2" <?php echo ($editflag and $managePricing['type']==2)? 'selected':'';?>>Doctor</option> 
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status * :</label>
                            <select name="status" id="status" class="form-control" required>
                                   <option value="1" <?php echo ($editflag and $managePricing['status'])? 'selected':'';?>>Active</option> 
                                   <option value="0" <?php echo ($editflag and !$managePricing['status'])? 'selected':'';?>>In Active</option> 
                            </select>
                        </div>
                        <div>
                        	<?php 
                        		if($editflag){
                        	?>
                        		<input type="hidden" name="priceId" value="<?php echo $priceId;?>">
                            	<input type="submit" class="btn btn-primary" name="Edit" value="Update">
                           	<?php }else{ ?>
                           		<input type="submit" class="btn btn-primary" name="Submit" value="Submit">
                           	<?php } ?>
                        </div>
                    </form>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="../js/bootstrap.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="../js/AdminLTE/app.js" type="text/javascript"></script>
        <script src="../js/chosen.jquery.min.js" type="text/javascript"></script>
        <!-- Include one of jTable styles. -->
        <link href="js/jtableScripts/jtable/themes/metro/darkgray/jtable.min.css" rel="stylesheet" type="text/css" />
        <!-- Include jTable script file. -->
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script>
            $(window).load(function () {
                $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"}); 

            });
            
            function setQuestions(rubricId){
                window.location.href="manageQuestions.php?rid="+rubricId;
            }
        </script>
       
    </body>
</html>