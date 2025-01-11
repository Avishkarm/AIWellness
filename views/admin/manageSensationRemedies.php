<?php
session_start();
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php");
require_once("../../utilities/authentication.php");
require_once("../../models/admin/remediesModel.php");
require_once("../../models/admin/sensationModel.php");
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

//get remedies data
$allRemedies = getAllRemedies($conn, 1, "a");
if(noError($allRemedies)){
	$allRemedies = $allRemedies["errMsg"];
} else {
	printArr("Error fetching remedies data");	
	exit;
}

if(isset($_GET["sensationId"]) && !in_array($_GET["sensationId"], $blanks)){
	$sensationId = $_GET["sensationId"];	
} else {
	printArr("Invalid Request");
	exit;
}

if(isset($_GET["type"]) && !in_array($_GET["type"], $blanks)){
	$type = $_GET["type"];	
} else {
	printArr("Invalid Request");
	exit;
}


//save post data if submitted
if(isset($_POST["submit"]) && $_POST["submit"]==1){
	
	
	foreach ($_POST as $sensationId => $sensationRemedies) {
		if($sensationRemedies!=1){
			$query = "UPDATE sensation SET sensationRemedies='".$sensationRemedies."' WHERE sensationId=".$sensationId." ";
		$result = runQuery($query, $conn);
	}
	}
	

		
	if(noError($result)){
		//printArr("Saved");
	} else {
		//printArr($result);
	}
}


//get saved remedies data
if($type=="Sensation"){
	$sensationRemedies = getSensationRemedies($sensationId, $conn);

	if(noError($sensationRemedies)){
		$sensationRemedies = $sensationRemedies["errMsg"];
	} else {
		printArr("Error fetching sensation remedies");
		exit;	
	}
	//get reaction choices
	$sensations = getAllSensations($conn);
	if(noError($sensations)){
		$sensations = $sensations["errMsg"];
	} else {
		printArr("Error fetching causation reactions data");
		exit;
	}
} 
$remediesData = (array)json_decode($sensationRemedies["sensationRemedies"], true);


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
						<form action="" method="post">
					
							
							<?php
							foreach($sensations as $sensationsId=>$sensationDetails){
								
							?>								
								<p>
									<label><?php echo $sensationDetails["sensationName"]; ?> sensation Remedies: </label>

									<?php
									$temp= $sensationDetails["sensationName"];


									//printArr($locationRemedies);
									//printArr($remediesData);
									//printArr($temp);printArr($remediesData);
									 //printArr($remediesData[$temp."ReactionRemedies"]["Hidden"]); 
									 ?>
										<input type="text" value="<?php echo $sensationDetails["sensationRemedies"]; ?>" name="<?php echo $sensationDetails["sensationId"]; ?>" id="<?php echo $sensationDetails["sensationName"]; ?>" /> <button type="button" onclick="manageRemedies('<?php echo $sensationDetails["sensationName"]; ?>')">Manage Remedies</button>
										
									

								</p>
							<?php
							}
							?>
							<p>
								<button name="submit" value="1" type="submit">Save</button>
							</p>
						</form>
					</div>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
		<!-- remedies dialog -->
		<div id="remediesDialog" title="Remedies" style="display:none">
			<p>
				<form action="../../controllers/admin/remediescrud/admin.php" method="post" onsubmit="filterRemedies(); return false;">
					<input type="text" id="remediesSearch" placeholder="Start typing to search" />
				</form>
			</p>
			<div id="remediesList" style="height: 80%; max-height:400px; overflow:auto">
				<?php					
				foreach($allRemedies as $remedyId=>$remedyDetails){
				?>
					<div class="remediesItemRow">
						<input class="remedyCheckbox" type="checkbox" value="<?php echo $remedyId; ?>" remedyNames="<?php echo $remedyDetails["remedy_name"]; ?>"> <?php echo $remedyDetails["remedy_name"]; ?>
					</div>
				<?php	
				}
				?>
			</div>
		</div>
		
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
      	
       
	
			var boxSelector = "";
            $(document).ready(function(){
            	 // $( ".iCheck-helper" ).on( "click", function() {
  //console.log($(this).prev('input').attr('value') );
  //	$(this).prev('input').parent().parent().hide();
//});
            	$("#remediesDialog").dialog({
					modal:true,
					width: 500,
					autoOpen: false,
					buttons: {
						"Add Remedies": function(){
							var remedyIds = "";
							var remedyNames = "";
							$(".remedyCheckbox:checked").each(function(){
								remedyIds += $(this).val()+",,";
								remedyNames += $(this).attr("remedyNames")+",,";
							});
							
							remedyNames = remedyNames.replace(/,,/g, ",");
							remedyNames = remedyNames.substr(0, (remedyNames.length-1));
							
							var existingRemediesInBox="" ;
							existingRemediesInBox += remedyNames;
								existingRemediesInBox += ",";
							
							$("#"+boxSelector).val(existingRemediesInBox);
							$(this).dialog("close");
						},
						
						"Cancel": function(){
							$(this).dialog("close");
						}
					}
				});
			}); 
			
			
			function manageRemedies(selector){
				boxSelector = selector;
				var currValues = $("#"+selector).val();
				currValues = currValues.split(",");
				$(".ui-dialog-content #remediesList .remediesItemRow .remedyCheckbox").each(function(){
					if(currValues.in_array($(this).attr("remedynames"))){
						$(this).iCheck("check");
					} else {
						$(this).iCheck("uncheck");
					}
				});
				$("#remediesDialog").dialog("open");
			}
			
			Array.prototype.in_array = function(p_val) {
				for(var i = 0, l = this.length; i < l; i++) {
					if(this[i] == p_val) {
						return true;
					}
				}
				return false;
			}
			
			function filterRemedies(){
				var searchQuery = $(".ui-dialog-content #remediesSearch").val();
				
				if(searchQuery.length>=1){
					$.ajax({
						type: 'POST',
						url: '../../controllers/admin/remediescrud/list.php',
						data: { q: searchQuery },
						beforeSend:function(){
							// this is where we append a loading image
							$(".ui-dialog-content #remediesList").html("Searching...");
						},
						dataType: "json",
						success:function(data){
							// successful request; do something with the data
							$(".ui-dialog-content #remediesList").empty();
							var remediesHtml = "";
							if(data["Result"]=="OK"){
								for(var i in data["Records"]){
									if(data["Records"][i]["Name"]){
										remediesHtml += '<div class="remediesItemRow"><input class="remedyCheckbox" type="checkbox" value="'+data["Records"][i]["remedyId"]+'" remedyNames="'+data["Records"][i]["Name"]+'"> '+data["Records"][i]["Name"]+'</div>';
									}
								}
							} else {
								remediesHtml = "Error fetching search results";
							}
							$(".ui-dialog-content #remediesList").html(remediesHtml);
						},
						error:function(){
							// failed request; give feedback to user
							$(".ui-dialog-content #remediesList").html('<p class="error"><strong>Oops!</strong> Try that again in a few moments.</p>');
						}
					});
				}
			}
        </script>
    </body>
</html>