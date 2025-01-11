<?php
session_start();

session_start();
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php");
require_once("../../utilities/authentication.php");
require_once("../../controllers/admin/manageRegion/list.php");

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


$countries = listCountriesNotUsed($conn);

if(noError($countries)){
	$countries = $countries['errMsg'];
}else{
	printArr("Error Fetching Countries Record $countries");
}


$currency = listCurrency($conn);

if(noError($currency)){
	$currency = $currency['errMsg'];
}else{
	printArr("Error Fetching Countries Record $countries");
}

$editflag = false;

if(isset($_GET['region_id']) and !empty($_GET['region_id'])){
	$editflag = true;
	$region_id = cleanQueryParameter($conn,$_GET['region_id']);

	$manageRegion = getManageRegionById($conn, $region_id);

	if(noError($manageRegion)){
		$manageRegion = $manageRegion['errMsg'][0];
	}else{
		printArr($manageRegion['errMsg']);
	}
}

if(isset($_POST['Submit'])){
	$returnArr = array();

	$region_name = cleanQueryParameter($conn,$_POST['region_name']);
	$region_countries = cleanQueryParameter($conn,implode(",",$_POST['region_countries']));
   // printArr($region_countries);die;
	$region_currency = cleanQueryParameter($conn,$_POST['region_currency']);
	$status = cleanQueryParameter($conn,$_POST['status']);

    $selectCurrency="SELECT cv.current_price,c.currency_symbol,cv.shortforms from currency_value cv INNER JOIN countries c ON c.currency_code=cv.shortforms where cv.id=$region_currency";
    $result = runQuery($selectCurrency, $conn);
        $res=array();
         while($row=mysqli_fetch_assoc($result["dbResource"])){
            $res = $row;
        }
        //printArr($res); die;
    $currency_code=$res['shortforms'];
    $currency_symbol=$res['currency_symbol'];
    $current_price=$res['current_price'];
    $query1 = sprintf("UPDATE manageRegion SET status = 0 where LOWER(`region_name`) = LOWER('%s')",$region_name);

    $query1 = runQuery($query1, $conn);
    //printArr($query1);
    if(noError($query1)){

    	 $query = sprintf("INSERT INTO manageRegion(region_name, region_countries, region_currency,currency_code,currency_symbol,current_price, status, created_date, updated_date) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s')", $region_name, $region_countries, $region_currency,$currency_code,$currency_symbol,$current_price, $status, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));

    	$query = runQuery($query, $conn);
        //printArr($query);
    	if(noError($query)){
    		$returnArr['errCode'][-1] = -1;
    		$returnArr['errMsg'] = "Successfully Added";
    	}else{
    		$returnArr['errCode'][1] = 1;
    		$returnArr['errMsg'] = "Error in Adding";
    	}
    }else{
        $returnArr['errCode'][1] = 1;
        $returnArr['errMsg'] = "Error in Updating".$query1['errMsg'];
    }
    //die;
	printArr($returnArr['errMsg']);
	$redirectUrl = "manageRegion.php";
	print_r("<script>setTimeout(function(){window.location.href='".$redirectUrl."';},3000)</script>");
}

if(isset($_POST['Edit'])){
	$returnArr = array();

	$region_name = cleanQueryParameter($conn,$_POST['region_name']);
	$region_countries = cleanQueryParameter($conn,implode(",",$_POST['region_countries']));
	$region_currency = cleanQueryParameter($conn,$_POST['region_currency']);
	$status = cleanQueryParameter($conn,$_POST['status']);
	$region_id = cleanQueryParameter($conn,$_POST['region_id']);

     $selectCurrency="SELECT cv.current_price,c.currency_symbol,cv.shortforms from currency_value cv INNER JOIN countries c ON c.currency_code=cv.shortforms where cv.id=$region_currency";
    $result = runQuery($selectCurrency, $conn);
        $res=array();
         while($row=mysqli_fetch_assoc($result["dbResource"])){
            $res = $row;
        }
        //printArr($res); die;
    $currency_code=$res['shortforms'];
    $currency_symbol=$res['currency_symbol'];
    $current_price=$res['current_price'];
        

	$query = sprintf("UPDATE manageRegion set region_name='%s', region_countries='%s', region_currency='%s',currency_code='%s', currency_symbol='%s', current_price='%s',, status='%s', updated_date='%s' WHERE region_id='%s'", $region_name, $region_countries, $region_currency,$currency_code,$currency_symbol,$current_price, $status, date('Y-m-d H:i:s'),$region_id);

	$query = runQuery($query, $conn);

	if(noError($query)){
		$returnArr['errCode'][-1] = -1;
		$returnArr['errMsg'] = "Successfully Updated";
	}else{
		$returnArr['errCode'][-1] = -1;
		$returnArr['errMsg'] = "Error in Updating";
	}

	printArr($returnArr['errMsg']);
	$redirectUrl = "manageRegion.php";
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
        <link href="../../assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../../assets/admin/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../../assets/admin/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../../assets/admin/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
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
                    <form method="POST" action="createRegion.php" style="width: 80%;text-align: left;">
                        <div class="form-group">
                            <label for="regionName">Region Name * :</label>
                            <input type="text" class="form-control" name="region_name" id="regionName" placeholder="Region Name" value = "<?php echo ($editflag)?$manageRegion['region_name']:'';?>" required> 
                        </div>
                        <div class="form-group">
                            <label for="status">Status * :</label>
                            <select name="status" id="status" class="form-control" required>
                                   <option value="1" <?php echo ($editflag and $manageRegion['status'])? 'selected':'';?>>Active</option> 
                                   <option value="0" <?php echo ($editflag and !$manageRegion['status'])? 'selected':'';?>>In Active</option> 
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="country">Assign Countries * :</label>
                            <select name="region_countries[]" id="country" class="form-control chosen-select" multiple style="width: 100%;" required>
                                  	<?php
                                  		foreach ($countries as $key => $value) { 
                                  			$countryArr = explode(",", $manageRegion['region_countries']);
                                  		 ?>
                                  				<option value="<?php echo $key; ?>" <?php echo ($editflag and in_array($key, $countryArr))? 'selected':'';?>><?php echo $value['name']; ?></option> 
                                  	<?php	}
                                  	?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="currency">Currency * :</label>
                            <select name="region_currency" id="currency" class="form-control chosen-select" style="width: 100%;" required>
                            		<option value="">Select Currency</option>
                                 	<?php
                                 		foreach ($currency as $key => $value) { 
                                 			$currencyArr = explode(",", $manageRegion['region_currency']);
                                 			?>
                                 				<option value="<?php echo $key; ?>" <?php echo ($editflag and in_array($key, $currencyArr))? 'selected':'';?>><?php echo $value['longforms']."(".$value['shortforms'].")"; ?></option> 	
                                 	<?php	} ?>
                            </select>
                        </div>
                        <div>
                        	<?php 
                        		if($editflag){
                        	?>
                        		<input type="hidden" name="region_id" value="<?php echo $region_id;?>">
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="../../assets/admin/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="../../assets/admin/js/AdminLTE/app.js" type="text/javascript"></script>
        <!-- Include one of jTable styles. -->
        <link href="../../assets/admin/js/jtableScripts/jtable/themes/metro/darkgray/jtable.min.css" rel="stylesheet" type="text/css" />
        <!-- Include jTable script file. -->
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src="../../assets/admin/js/jtableScripts/jtable/jquery.jtable.min.js" type="text/javascript"></script>
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