<?php
session_start();

require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php");
require_once("../../utilities/authentication.php");
require_once("../../models/admin/adminDashboardModel.php");

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

?>
<!DOCTYPE html>
<html>
<style type="text/css">
    
</style>
    <head>
        <meta charset="UTF-8">
        <title>eHeilung</title>
        <link rel="stylesheet" property="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<link href="https://fonts.googleapis.com/css?family=Carrois+Gothic" rel="stylesheet">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="../../assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../../assets/admin/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../../assets/admin/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../../assets/admin/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="../../assets/AdminLTE.css" rel="stylesheet" type="text/css" />
        
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
                            <a href="manageDoctorUser.php">
                                <i class="fa fa-check-square"></i> <span>Manage doctors</span>
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
                <section class="content" style="text-align: center;min-height: 1000px;">
                    <div id="RubricsTableContainer">
                            <div class="row">

        
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="icon ion-log-in"></i></span>

                <div class="info-box-content">
                   <span class="info-box-text">Active Doctors</span>
                 <a href="#" data-toggle="tooltip" data-placement="left" title="Total No. of Logged In Users">   <span id="show_logged_in_users" class="info-box-number">
                       <?php
                            $getAllActiveDoctors=getAllActiveDoctors($conn);
                            //printArr($getAllActiveDoctors);
                            echo $getAllActiveDoctors['errMsg'];
                        ?>

                    </span>
                    </a>
                </div>
            </div>
        </div>
        
       <!--  <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="icon ion-log-out"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Inactive doctors</span>
                    <a href="#" data-toggle="tooltip" data-placement="left" title="Total No. of Logged Out Users">
                    <span id="show_logged_out_users" class="info-box-number">
                        <?php
                             $getAllInActiveDoctors=getAllInActiveDoctors($conn);
                              //printArr($getAllInActiveDoctors);
                            echo $getAllInActiveDoctors['errMsg'];
                        ?>
                    </span>
                    </a>
                </div>
            </div>
        </div>
 -->
        <!-- fix for small devices only -->

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                
                <div class="info-box-content">
                    <span class="info-box-text">Total Doctors</span>
                    <a href="#" data-toggle="tooltip" data-placement="left" title="Total No. of Users">
                    <span id="show_total_no_of_registered_user" class="info-box-number">
                        <?php
                             $getAllDoctors=getAllDoctors($conn);
                              //printArr($getAllDoctors);
                            echo $getAllDoctors['errMsg'];
                        ?>
                    </span>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                
                <div class="info-box-content">
                    <span class="info-box-text">Total patients</span>
                    <a href="#" data-toggle="tooltip" data-placement="left" title="Total No. of Users">
                    <span id="show_total_no_of_registered_user" class="info-box-number">
                        <?php
                             $getAllPatients=getAllPatients($conn);
                              //printArr($getAllDoctors);
                            echo $getAllPatients['errMsg'];
                        ?>
                    </span>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                
                <div class="info-box-content">
                    <span class="info-box-text">Total Users</span>
                    <a href="#" data-toggle="tooltip" data-placement="left" title="Total No. of Users">
                    <span id="show_total_no_of_registered_user" class="info-box-number">
                        <?php
                             $getAllUsers=getAllUsers($conn);
                              //printArr($getAllDoctors);
                            echo $getAllUsers['errMsg'];
                        ?>
                    </span>
                    </a>
                </div>
            </div>
        </div>

        
    </div>
                    </div>
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
           /* $(window).load(function () {
                $('#RubricsTableContainer').jtable({
                    title: 'All Rubrics',
                    recordAdded: function(event, data){
                        window.location.href=window.location.href;
                    },
                    actions: {
                        listAction: '<?php echo '../../controllers'; ?>/admin/rubricscrud/list.php',
                        createAction: '<?php echo '../../controllers'; ?>/admin/rubricscrud/create.php',
                        updateAction: '<?php echo '../../controllers'; ?>/admin/rubricscrud/update.php',
                        deleteAction: '<?php echo '../../controllers'; ?>/admin/rubricscrud/delete.php'
                    },
                    fields: {
                        rubricId: {
                            key: true,
                            list: false
                        },
                        Name: {
                            title: 'Rubric Name',
                            width: '40%'
                        },
                        type: {
                            title: 'Type',
                            options: { 'Mental': 'Mental', 'Personal': 'Personal', 'Physical': 'Physical', 'Sensitive': 'Sensitive' }
                           
                        },
                        usergroup: {
                            title: 'Group',
                            options: { 'Patient_Only': 'Patient Only', 'Doctor_Only': 'Doctor Only' , 'Both': 'Both' }
                           
                        },
                        gender: {
                            title: 'Gender',
                            options: {'Male':'Male', 'Female':'Female', 'Both':'Both'}
                        },
                        age: {
                            title: 'Age Restriction',
                            options: {'Adult':'Adult','Minor':'Minor','Both':'Both'}
                        },
                        MyButton: {
                            title: 'Set Questions',
                            width: '40%',
                            display: function(data) {
                                 return '<button type="button" onclick="setQuestions(' + data.record.rubricId + ')">Set Questions</button> ';
                            }
                        },
                    },
                    dialogShowEffect: "slide",
                    dialogShowEffect: "size"                    
                });
                $('#RubricsTableContainer').jtable("load");

            });
            
            function setQuestions(rubricId){
                window.location.href="manageQuestions.php?rid="+rubricId;
            }*/
        </script>
       
    </body>
</html>