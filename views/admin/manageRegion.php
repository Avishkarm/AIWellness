<?php
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


$status = 1;

if(isset($_GET['status'])){
    $st = $_GET['status'];
    $status = $st;
}
if(isset($_GET['action']) and $_GET['action'] == "delete" and !empty($_GET['region_id'])){
    $region_id = cleanQueryParameter($conn,$_GET['region_id']);
    
    $query = sprintf("UPDATE manageRegion set status = '%s' where region_id='%s'",0,$region_id);

    $query = runQuery($query, $conn);

    if(noError($query)){
        printArr("Successfully Deleted");
        $redirectUrl = "manageRegion.php";
        print_r("<script>setTimeout(function(){window.location.href='".$redirectUrl."';},3000)</script>");
    }else{
        printArr("Error". $query['errMsg']);
    }
}
$manageRegion = listManageRegion($conn,$status);

if(noError($manageRegion)){
    $manageRegion = $manageRegion['errMsg'];
}else{
    printArr("Error Fetching Countries Record $countries");
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
                    <div>
                        <div style="float: left;">
                            <form action="manageRegion.php" method="GET" style="display: inline-flex;">
                                <select name="status" class="form-control">
                                       <option value="1"  <?php echo ($status)? 'selected':'';?>>Active</option> 
                                       <option value="0" <?php echo (!$status)? 'selected':'';?>>In Active</option> 
                                </select>
                                </select>
                                <input type="submit" class="btn btn-primary">
                            </form>
                        </div>
                        <div style="float: right;">
                            <a href="../../views/admin/createRegion.php" class="btn btn-primary">
                                Add new Region
                            </a>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                    <table class="rowTable table table-bordered">
                        <tr>
                            <th>Region Name</th>
                            <th>Region Countries</th>
                            <th>Region Currency</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <?php
                            foreach ($manageRegion as $key => $value) {
                                 //echo $status=$value['status'];
                        ?>
                        <tr>

                            <td><?php echo ($value['region_name']?$value['region_name']:'');?></td>
                            <td><?php 
                                    $country_name = getCountryByID($conn, $value['region_countries'], "name");
                                    if(noError($country_name)){
                                        echo join(',', array_map(function (array $ele) { return $ele['name']; }, $country_name['errMsg']));
                                    }else{
                                        printArr($country_name['errMsg']);
                                    }
                                ?>
                            </td>
                            <td><?php 
                                    $currency_name = getCurrencyByID($conn, $value['region_currency'], "longforms,shortforms");
                                    if(noError($currency_name)){
                                        echo join(',', array_map(function (array $ele) { return $ele['longforms']."(".$ele['shortforms'].")"; }, $currency_name['errMsg']));
                                    }else{
                                        printArr($currency_name['errMsg']);
                                    }
                                ?></td>
                            <td><?php 
                                    
                                    if($status){
                                        echo "Active";
                                    }else{
                                        echo "InActive";
                                    }
                                ?>
                            </td>

                            <td> 
                                <!-- <a href="createRegion.php?region_id=<?php echo $value['region_id'];?>">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a> -->
                                <a href="manageRegion.php?region_id=<?php echo $value['region_id'];?>&action=delete">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                            }
                        ?>
                    </table>
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
        <!-- Include one of jTable styles. -->
        <!-- <link href="js/jtableScripts/jtable/themes/metro/darkgray/jtable.min.css" rel="stylesheet" type="text/css" /> -->
        <!-- Include jTable script file. -->
        <!-- <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script> -->
        <script>
            $(window).load(function () {
               

            });
            
            function setQuestions(rubricId){
                window.location.href="manageQuestions.php?rid="+rubricId;
            }
        </script>
       
    </body>
</html>