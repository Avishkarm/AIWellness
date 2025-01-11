<?php
session_start();
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php");
require_once("../../utilities/authentication.php");
require_once("../../models/admin/remediesModel.php");
require_once("../../models/admin/rubricsModel.php");

//database connection
$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
} else {
	printArr("Database Error");
	exit;
}

if(isset($_GET['aid'])){
$temp = cleanQueryParameter($conn,$_POST["hasPuzzlesFormElement"]);
$ansid= $_GET['aid'];
$rubricId = $_GET['rid'];

//printArr($_POST["answerOption"]);
if($temp==1){
	$primaryQuestionNoOfPuzzleQuestions = cleanQueryParameter($conn,$_POST["noOfQuestions"]);
	$primaryQuestionPuzzleQuestionsTimer = cleanQueryParameter($conn,$_POST["secondsPerQuestions"]);
	$primaryQuestionLowPuzzleScoreRemedies = cleanQueryParameter($conn,$_POST["lowScoreRemedies"]);
	$primaryQuestionMedPuzzleScoreRemedies = cleanQueryParameter($conn,$_POST["medScoreRemedies"]);
	$primaryQuestionHighPuzzleScoreRemedies = cleanQueryParameter($conn,$_POST["highScoreRemedies"]);

	$primaryQuestionLowPuzzleScoreRemedies=implode(",",array_unique(explode(',',$primaryQuestionLowPuzzleScoreRemedies,-1)));
    $primaryQuestionMedPuzzleScoreRemedies=implode(",",array_unique(explode(',',$primaryQuestionMedPuzzleScoreRemedies,-1)));
    $primaryQuestionHighPuzzleScoreRemedies=implode(",",array_unique(explode(',',$primaryQuestionHighPuzzleScoreRemedies,-1)));  

	$query = "UPDATE kes_questions set has_puzzle=1,noOfPuzzleQuestions='".$primaryQuestionNoOfPuzzleQuestions."' ,secondsPerPuzzleQuestion='".$primaryQuestionPuzzleQuestionsTimer."' ,low_score_remedies='".$primaryQuestionLowPuzzleScoreRemedies."',med_score_remedies='".$primaryQuestionMedPuzzleScoreRemedies."',high_score_remedies='".$primaryQuestionHighPuzzleScoreRemedies."' where question_id='".$ansid."'";
		$rs1=mysqli_query($query,$conn);	
		
		//$updatePrimaryQuestion = updatePrimaryQuestion($rubricId, $primaryQuestionId, $primaryQuestionText, $conn, $target_path, $primaryQuestionHelpText, $primaryQuestionHasPuzzles, $primaryQuestionNoOfPuzzleQuestions, $primaryQuestionPuzzleQuestionsTimer, $primaryQuestionLowPuzzleScoreRemedies, $primaryQuestionMedPuzzleScoreRemedies, $primaryQuestionHighPuzzleScoreRemedies);	
}
else{

	$deleteAnswers = deleteAnswers($rubricId, $ansid, $conn);
	if(noError($deleteAnswers)){
		if(!empty($_POST["answerOption"])){
			$updateAnswers = updateAnswers($rubricId, $ansid, $_POST["answerOption"], $conn, $new);					
			if(noError($updateAnswers)) {
				printArr("Primary Questions updated");
			} else {
				printArr("Error updating answers: ".$updateAnswers["errMsg"]);
			}
		}
	}else{
		printArr("Error deleting answers");
	}
}
$redirectURL = "manageQuestions.php?rid=$rubricId";
print("<script>");
print("var t = setTimeout(\"window.location='".$redirectURL."';\",100000);");
print("</script>");
} 
//admin authentication
$user = "";
if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
	$user = $_SESSION["user"];	
} else {
	printArr("You do not have sufficient privileges to access this page");
	exit;
}

//check for which rubric are questions being set
if(isset($_GET["rid"]) && !in_array($_GET["rid"], $blanks)){
    $rubricId = $_GET["rid"];
} else {
	$rubricId = 3;	
}

//get all rubrics for drop down
$allRubrics = getAllRubrics($conn);
if(noError($allRubrics)){
    $allRubrics=$allRubrics["errMsg"];
} else {
    printArr("Error fetching rubrics data");
    exit;
}

//check if form is submitted
if(isset($_POST["rid"]) && !in_array($_POST["rid"], $blanks)){
	//need to save data
	///////////////////sanitizing inputs//////////////////////////////////////////////////
	$primaryQuesIndex = 0;
	if(array_key_exists("new", $_POST["primaryQuestion"])){
		$primaryQuesIndex = "new";
		if(isset($_POST["answerOption"]) && sizeof($_POST["answerOption"])>0){
			$_POST["answerOption"][0] = $_POST["answerOption"]["new"];
		}
	}
	
    $rubricId = cleanQueryParameter($conn, $_POST["rid"]);
    $primaryQuestionId = cleanQueryParameter($conn, $_POST["primaryQuestion"][$primaryQuesIndex]["questionId"]);
    $primaryQuestionText = cleanQueryParameter($conn, $_POST["primaryQuestion"][$primaryQuesIndex]["questionLabel"]);
	$primaryQuestionHelpText = cleanQueryParameter($conn, $_POST["primaryQuestion"][$primaryQuesIndex]["helpText"]);	
	$primaryQuestionmltiChoice = cleanQueryParameter($conn, $_POST["primaryQuestion"][$primaryQuesIndex]["mltiChoice"]);
	$primaryQuestionHasPuzzles = cleanQueryParameter($conn, $_POST["primaryQuestion"][$primaryQuesIndex]["hasPuzzles"]);
	$primaryQuestionNoOfPuzzleQuestions = cleanQueryParameter($conn, $_POST["primaryQuestion"][$primaryQuesIndex]["noOfQuestions"]);
	$primaryQuestionPuzzleQuestionsTimer = cleanQueryParameter($conn, $_POST["primaryQuestion"][$primaryQuesIndex]["secondsPerQuestions"]);
	$primaryQuestionLowPuzzleScoreRemedies = cleanQueryParameter($conn, $_POST["primaryQuestion"][$primaryQuesIndex]["lowScoreRemedies"]);
	$primaryQuestionMedPuzzleScoreRemedies = cleanQueryParameter($conn, $_POST["primaryQuestion"][$primaryQuesIndex]["medScoreRemedies"]);
	$primaryQuestionHighPuzzleScoreRemedies = cleanQueryParameter($conn, $_POST["primaryQuestion"][$primaryQuesIndex]["highScoreRemedies"]);
	$target_path = cleanQueryParameter($conn, $_POST["primaryQuestion"][$primaryQuesIndex]["videoURL"]);
	///////////////////end sanitizing inputs//////////////////////////////////////////////////
	
	//////////////////////////////////////////upload video file to folder if video is uploaded////////////////////////////////////////
	if(isset($_FILES['primaryQuestion']) && $_FILES['primaryQuestion']['error'][$primaryQuesIndex]["videoUrl"]==0){
		$target_path = "uploads/";
		$target_path = $target_path . basename( $_FILES['primaryQuestion']['name'][$primaryQuesIndex]["videoUrl"]);
		
		if(move_uploaded_file($_FILES['primaryQuestion']['tmp_name'][$primaryQuesIndex]["videoUrl"], $target_path)){
			//echo "The file ".  basename( $_FILES['primaryQuestion']['name'][$primaryQuesIndex]["videoUrl"]). " has been uploaded";
		} else{
			//echo "There was an error uploading the file, please try again!";
		}
	} 
	////////////////////////////////////////end upload video file to folder if video is uploaded//////////////////////////////////////////
	
	//update primary question info
	$updatePrimaryQuestion = updatePrimaryQuestion($rubricId, $primaryQuestionId, $primaryQuestionText, $conn, $target_path, $primaryQuestionHelpText,$primaryQuestionmltiChoice, $primaryQuestionHasPuzzles, $primaryQuestionNoOfPuzzleQuestions, $primaryQuestionPuzzleQuestionsTimer, $primaryQuestionLowPuzzleScoreRemedies, $primaryQuestionMedPuzzleScoreRemedies, $primaryQuestionHighPuzzleScoreRemedies);	
	//printArr($updatePrimaryQuestion);die;
    if(noError($updatePrimaryQuestion)){
		//primary question label, help, video updated
		if($primaryQuestionHasPuzzles==0){
			//no puzzles, lets upload answer options			
			//if new flag is set, then get the primary question details to extract question id
			$new=false;

			if(array_key_exists("new", $_POST["primaryQuestion"])){
				$new=true;
				//get primary question for that rubric
				$primaryQuestionDets = getAllPrimaryQuestions($rubricId, $conn);
				if(noError($primaryQuestionDets)){
					$primaryQuestionDets = $primaryQuestionDets["errMsg"];
					$primaryQuestionId = $primaryQuestionDets[0]["question_id"];
				} else {
					printArr("Error fetching primary question details here: ");	
				}
			}
			//printArr($_POST);
			//delete previous answers
			if(isset($_POST["answerOption"]) && sizeof($_POST["answerOption"])>0){
				$deleteAnswers = deleteAnswers($rubricId, $primaryQuestionId, $conn);
				if(noError($deleteAnswers)) {
					//prev answers deleted, update new ones

					$updateAnswers = updateAnswers($rubricId, $primaryQuestionId, $_POST["answerOption"], $conn, $new);					
					if(noError($updateAnswers)) {
						printArr("Primary Questions updated");
					} else {
						printArr("Error updating answers: ".$updateAnswers["errMsg"]);
					}
				} else {
					printArr("Error deleting answers");
				}
			}
		}
	} else {
		printArr("Error updating primary question info");
	}
} 

//GET question and answer data for this rubric and prepare a friendly array for us to loop through
$primaryQuestionDetails = getAllPrimaryQuestions($rubricId, $conn);
if(noError($primaryQuestionDetails)){
	//got primary question + primary answers
    $primaryQuestionDetails = $primaryQuestionDetails["errMsg"];
	
	$primaryQuestionText =  stripslashes($primaryQuestionDetails[0]["question_name"]);
    $primaryQuestionId = $primaryQuestionDetails[0]["question_id"];
	$primaryQuestionHelpText = $primaryQuestionDetails[0]["help_text"];
	$primaryQuestionmltiChoice = $primaryQuestionDetails[0]["multiChoice"];
	$primaryQuestionVideoUrl = $primaryQuestionDetails[0]["video_url"];
	$primaryQuestionHasPuzzle = $primaryQuestionDetails[0]["has_puzzle"];
	$primaryQuestionNoOfPuzzleQuestions = $primaryQuestionDetails[0]["noOfPuzzleQuestions"];
	$primaryQuestionPuzzleQuestionsTimer = $primaryQuestionDetails[0]["secondsPerPuzzleQuestion"];
	$primaryQuestionLowPuzzleScoreRemedies = $primaryQuestionDetails[0]["low_score_remedies"];
	$primaryQuestionMedPuzzleScoreRemedies = $primaryQuestionDetails[0]["med_score_remedies"];
	$primaryQuestionHighPuzzleScoreRemedies = $primaryQuestionDetails[0]["high_score_remedies"];
	
	if($primaryQuestionHasPuzzle==0){
		$optionsArr = array();
		//loop through all entries to get follow up question+follow up answers
		foreach($primaryQuestionDetails as $key=>$questionDetails){
			$optionsArr[$questionDetails["aid"]]["answerLabel"]=$questionDetails["ans_label"];
			$optionsArr[$questionDetails["aid"]]["answerRemedies"]=$questionDetails["answerRemedies"];
			$optionsArr[$questionDetails["aid"]]["usergroup"]=$questionDetails["usergroup"];
			$optionsArr[$questionDetails["aid"]]["gender"]=$questionDetails["gender"];
			$optionsArr[$questionDetails["aid"]]["age"]=$questionDetails["age"];
			if($questionDetails["has_fuq"]>0){
				//fuq exists, get details and add to our friendly array
				$followupQuestionId = $questionDetails["fuq_id"];
				$followupQuestionDetails = getQuestionDetails($followupQuestionId, 1, $conn);
				if(noError($followupQuestionDetails)){
					$followupQuestionDetails = $followupQuestionDetails["errMsg"];
					$optionsArr[$questionDetails["aid"]]["followupQuesId"]=$followupQuestionId;
					$optionsArr[$questionDetails["aid"]]["followupQuesText"]=$followupQuestionDetails[0]["question_name"];
					foreach($followupQuestionDetails as $key=>$followUpQuestionDetails){
						$optionsArr[$questionDetails["aid"]]["followupQuesAnswers"][$followUpQuestionDetails["aid"]]["answerLabel"]=$followUpQuestionDetails["ans_label"];
						$optionsArr[$questionDetails["aid"]]["followupQuesAnswers"][$followUpQuestionDetails["aid"]]["answerRemedies"]=$followUpQuestionDetails["answerRemedies"];
					}
				} else {
					$optionsArr[$questionDetails["aid"]]["followupQuesId"]="Error fetching follow up question details";
				}
			}
		}
	}
    
    $primaryQuestionDetails = array();
    $primaryQuestionDetails["primaryQuestionText"]=$primaryQuestionText;
    $primaryQuestionDetails["primaryQuestionId"]=$primaryQuestionId;
	$primaryQuestionDetails["primaryQuestionHelpText"]=$primaryQuestionHelpText;
	$primaryQuestionDetails["primaryQuestionmltiChoice"]=$primaryQuestionmltiChoice;
	$primaryQuestionDetails["primaryQuestionVideoURL"]=$primaryQuestionVideoUrl;	
	if($primaryQuestionHasPuzzle==0){
    	$primaryQuestionDetails["primaryQuestionOptions"]=$optionsArr;
		$primaryQuestionDetails["hasPuzzles"]=0;
	} else {
		$primaryQuestionDetails["primaryQuestionOptions"]="puzzles";
		$primaryQuestionDetails["hasPuzzles"]=1;
		$primaryQuestionDetails["primaryQuestionNoOfPuzzleQuestions"]=$primaryQuestionNoOfPuzzleQuestions;
		$primaryQuestionDetails["primaryQuestionSecondsPerQuestion"]=$primaryQuestionPuzzleQuestionsTimer;
		$primaryQuestionDetails["primaryQuestionLowScoreRemedies"]=$primaryQuestionLowPuzzleScoreRemedies;
		$primaryQuestionDetails["primaryQuestionMedScoreRemedies"]=$primaryQuestionMedPuzzleScoreRemedies;
		$primaryQuestionDetails["primaryQuestionHighScoreRemedies"]=$primaryQuestionHighPuzzleScoreRemedies;
	}	
} else{
    printArr("Error fetching primary questions");
    exit;
}

//set new variable in JS
$primaryQuestionIndex=0;
print("<script>var newRubricData='old';</script>");
if(in_array($primaryQuestionDetails["primaryQuestionText"], $blanks)){
	$primaryQuestionIndex="new";
	print("<script>var newRubricData='new';</script>");
}

//get remedies data
$allRemedies = getAllRemedies($conn, 1, "a");

if(noError($allRemedies)){
	$allRemedies = $allRemedies["errMsg"];
} else {
	printArr("Error fetching remedies data");	
	exit;
}

?>
<!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>e-heilung</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
      	<link href="../../assets/admin/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="../../assets/admin/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="../../assets/admin/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="../../assets/admin/css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <link href="../../assets/admin/css/style.css" rel="stylesheet" type="text/css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style>
            .questionBox {
              float: left;
              width: 100%;
              border: solid thin black;
              padding: 10px;
              margin: 10px 0px;
            }
			
			.answerOption{
				list-style-type:none;	
			}
        </style>
    </head>

    <body class="skin-blue">
		<div id="remediesDialog" title="Remedies" style="display:none">
			<p>
				<form action="../../controllers/admin/remediescrud/admin.php" method="post" onsubmit="filterRemedies(); return false;">
					<input type="text" id="remediesSearch" placeholder="Start typing to search" onchange="filterRemedies();"/>
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
                <section class="content" text align="left" style="float: left; width:100%">
                    <span class="redfamily"></span>
                    <div class="" style="margin:0;float: left; width:100%">
                        <form method="post" enctype="multipart/form-data" onSubmit="validateForm()">
                            <div style="float: left; width:100%">
                                Rubric:
                                <?php // print($allRubrics[$rubricId]["rubric_name"]); ?>
                                <button type="button" style="float: right" onclick="$('#rubricsBox').toggle()">Change Rubric</button>
                            </div>
                            <div style="display:none; text-align:right" id="rubricsBox">
                                <select name="rid" id="rubric" required class="" onchange="setQuestions(this)">
                                    <?php
                                        foreach ($allRubrics as $rId=>$rubricDetails) { 
                                            $selected = "";
                                            if($rubricId==$rId)
                                                $selected="selected='selected'";
                                    ?>
                                        <option value="<?php echo $rId; ?>" <?php echo $selected; ?>>
                                            <?php echo $rubricDetails["rubric_name"]; ?>
                                        </option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="questionBox">
								<!-- Primary Question div -->
                                <div>
                                    Primary Question:
                                    <input type="text" name="primaryQuestion[<?php echo $primaryQuestionIndex; ?>][questionLabel]" required style="width:100%" placeholder="Primary question" value="<?php echo $primaryQuestionDetails["primaryQuestionText"]; ?>">
									<p>
										<p>Help Text</p>
										<p><textarea name="primaryQuestion[<?php echo $primaryQuestionIndex; ?>][helpText]" required style="width:100%"><?php echo $primaryQuestionDetails["primaryQuestionHelpText"]; ?></textarea></p>
									</p>
									<p>
										<p>Chose multiple options</p>
										<p>
											<input type="radio" <?php if($primaryQuestionDetails["primaryQuestionmltiChoice"]=="yes"){ ?> checked = "checked" <?php } ?> name="primaryQuestion[<?php echo $primaryQuestionIndex; ?>][mltiChoice]" value="yes"/>Yes
											<input type="radio" <?php if($primaryQuestionDetails["primaryQuestionmltiChoice"]=="no"){ ?> checked = "checked" <?php } ?> name="primaryQuestion[<?php echo $primaryQuestionIndex; ?>][mltiChoice]" value="no"/>No											
										</p>
									</p>
									<p>
										<p>Video Tutorial</p>
										<p>
										<?php
											if(!in_array($primaryQuestionDetails["primaryQuestionVideoURL"], $blanks)){
										?>
												<a href="<?php echo $primaryQuestionDetails["primaryQuestionVideoURL"]; ?>" target="_blank">Video URL</a> <a onClick="$('#primary_question_video_url').val(''); $(this).parent().remove()" style="color:red">Remove</a> 
												<?php
												if(isset($primaryQuestionDetails["primaryQuestionVideoURL"])){
												?>
													<input type="hidden" id="primary_question_video_url" name="primaryQuestion[<?php echo $primaryQuestionIndex; ?>][videoURL]" required style="width:100%" placeholder="VideoUrl" value="<?php echo $primaryQuestionDetails["primaryQuestionVideoURL"]; ?>">
												<?php                                        
												}
												?>												
										<?php
											} 
										?>
										</p>
										<p>
											<input type="file" name="primaryQuestion[<?php echo $primaryQuestionIndex; ?>][videoUrl]" />
										</p>
									</p>
                                    <?php
                                    if(isset($primaryQuestionDetails["primaryQuestionId"])){
                                    ?>
                                        <input type="hidden" name="primaryQuestion[<?php echo $primaryQuestionIndex; ?>][questionId]" required style="width:100%" placeholder="Primary question" value="<?php echo $primaryQuestionDetails["primaryQuestionId"]; ?>">
                                    <?php                                        
                                    }
                                    ?>
                                </div>
                            <div>
                                <button type="submit">Save</button> <button type="reset">Clear</button>
                            </div>
                        </form>
					</div>
					<?php if(!empty($primaryQuestionId)) {	?>
					<div class="questionBox">
						    <form method = "POST" action="manageQuestions.php?rid=<?php echo $rubricId ?>&aid=<?php echo $primaryQuestionDetails["primaryQuestionId"] ?>">                                <!-- Answer Options div -->
                                <div id="answerOptionsDiv" style="float:left; width:100%;<?php if($primaryQuestionDetails["hasPuzzles"]==1) echo "display:none"; ?>">
                                    <?php   $i=0; ?>
                                    Answer Options:<br/>
                                    <ul id="answerOptions_0" class="answerOptionsBox">

                                    <?php
                                    foreach($primaryQuestionDetails["primaryQuestionOptions"] as $optionAnsId=>$optionAnsDetails){
                                    	if($optionAnsId>0){
                                        $hasFup = false;
                                        if(isset($optionAnsDetails["followupQuesId"]) && !in_array($optionAnsDetails["followupQuesId"], $blanks))
                                            $hasFup = true;                                            
                                    ?>
                                        <li id="answerOption_0_<?php echo $optionAnsId; ?>" class="answerOption">
                                            <!-- Answer option div with all options -->
                                            <?php $i=$optionAnsId;

                                              $i;?>
                                            <div>

                                                <!-- Answer Label -->
                                                <input type="text" name="answerOption[<?php echo $primaryQuestionIndex; ?>][<?php echo $optionAnsId; ?>][answerLabel]" required style="width:50%" placeholder="Answer Label" value="<?php echo $optionAnsDetails["answerLabel"]; ?>"> 
                                                <!-- Remove answer button -->
                                                <div>
													<input type="radio" <?php if($optionAnsDetails["usergroup"]=="Patient_Only"){ ?> checked = "checked"<?php } ?>name="answerOption[<?php echo $primaryQuestionIndex; ?>][<?php echo $optionAnsId; ?>][usergroup]" value="Patient_Only"/>Patient Only<br>
													<input type="radio" <?php if($optionAnsDetails["usergroup"]=="Doctor_Only"){ ?> checked = "checked" <?php } ?> name="answerOption[<?php echo $primaryQuestionIndex; ?>][<?php echo $optionAnsId; ?>][usergroup]" value="Doctor_Only"/>Doctor Only<br>
													<input type="radio" <?php if($optionAnsDetails["usergroup"]=="Both"){ ?> checked = "checked" <?php } ?> name="answerOption[<?php echo $primaryQuestionIndex; ?>][<?php echo $optionAnsId; ?>][usergroup]" value="Both"/>Both
													<input type="radio" <?php if($optionAnsDetails["age"]=="Minor"){ ?> checked = "checked" <?php } ?> name="answerOption[<?php echo $primaryQuestionIndex; ?>][<?php echo $optionAnsId; ?>][age]" value="Minor"/>Minor
													<input type="radio" <?php if($optionAnsDetails["age"]=="Both"){ ?> checked = "checked" <?php } ?> name="answerOption[<?php echo $primaryQuestionIndex; ?>][<?php echo $optionAnsId; ?>][age]" value="Both"/>Both
													<input type="radio" <?php if($optionAnsDetails["age"]=="Adult"){ ?> checked = "checked" <?php } ?> name="answerOption[<?php echo $primaryQuestionIndex; ?>][<?php echo $optionAnsId; ?>][age]" value="Adult"/>Adult
													<input type="radio" <?php if($optionAnsDetails["gender"]=="Male"){ ?> checked = "checked" <?php } ?> name="answerOption[<?php echo $primaryQuestionIndex; ?>][<?php echo $optionAnsId; ?>][gender]" value="Male"/>Male
													<input type="radio" <?php if($optionAnsDetails["gender"]=="Both"){ ?> checked = "checked" <?php } ?> name="answerOption[<?php echo $primaryQuestionIndex; ?>][<?php echo $optionAnsId; ?>][gender]" value="Both"/>Both
													<input type="radio" <?php if($optionAnsDetails["gender"]=="Female"){ ?> checked = "checked" <?php } ?> name="answerOption[<?php echo $primaryQuestionIndex; ?>][<?php echo $optionAnsId; ?>][gender]" value="Female"/>Female
												
												</div>
                                                <button type="button" onclick="removeAnswerOption(0, <?php echo $optionAnsId; ?>, <?php echo $primaryQuestionDetails["primaryQuestionId"]; ?>)">Remove</button> 
                                                
                                                <!-- remove follow up question button -->
                                                <button id="removeFollowUpQuestionButton_0_<?php echo $optionAnsId; ?>" type="button" onclick="removeFollowUpQuestion(0, <?php echo $optionAnsId; ?>)" <?php if(!$hasFup){ ?>style="display:none<?php } ?>">Remove Follow up Question</button> 
                                                <br/>
                                                <!-- remedies textbox -->
                                                <input style="width:50%;<?php if($hasFup){ echo "display:none"; } ?>" type="text" placeholder="Remedies" id="answerRemedies_<?php echo $primaryQuestionIndex; ?>_<?php echo $optionAnsId; ?>" name="answerOption[<?php echo $primaryQuestionIndex; ?>][<?php echo $optionAnsId; ?>][answerRemedies]" value="<?php echo $optionAnsDetails["answerRemedies"]; ?>" /> 
                                                <!-- Manage Remedies button -->
                                                <button id="manageRemediesButton_0_<?php echo $optionAnsId; ?>" type="button" onclick="manageRemedies(0, <?php echo $optionAnsId; ?>)" <?php if($hasFup){ ?>style="display:none<?php } ?>">Manage Remedies</button> 
                                            
                                            </div>
                                            <!-- FUQ div -->
                                           
                                            
                                        
                                    <?php
                                    }}
                                    ?>
                                        </li>
                                    
                                    </ul>
                                </div>
                                <!-- End Answer options Div --> 
                                <!-- Puzzle Options div -->
                                <div id="puzzleOptionsDiv" style="float:left; width:100%; <?php if($primaryQuestionDetails["hasPuzzles"]==0) echo "display:none"; ?>">
                                    Puzzle Options:<br/>
                                    <ul id="puzzleOptions_0" class="">
                                        <li class="">
                                            <div>
                                                <!-- No of Questions -->
                                                <p>
                                                    <p>Number of questions to show:</p>
                                                    <p><input type="text" name="noOfQuestions" style="width:50%" placeholder="No Of Questions" value="<?php echo $primaryQuestionNoOfPuzzleQuestions; ?>"> 
                                                    </p>
                                                </p>                                                
                                                <!-- Timer per Question -->
                                                <p>
                                                    <p>Number of Seconds per Question:</p>
                                                    <p>
                                                        <input type="text" name="secondsPerQuestions" style="width:50%" placeholder="Time Per Question in seconds" value="<?php echo $primaryQuestionPuzzleQuestionsTimer; ?>"> 
                                                    </p>
                                                </p>
                                            </div>
                                            <div>
                                                <!-- Low Score Remedies -->
                                                <p>
                                                    <p>Low Score Remedies:</p>
                                                    <p>
                                                        <input type="text" name="lowScoreRemedies" id="lowScoreRemedies" style="width:50%" placeholder="Low score Remedies" value="<?php echo $primaryQuestionLowPuzzleScoreRemedies; ?>"> 
                                                        <button type="button" onClick="managePuzzleRemedies('lowScoreRemedies')">Manage Remedies</button>
                                                    </p>
                                                </p>
                                                <!-- Medium Score Remedies -->
                                                <p>
                                                    <p>Medium Score Remedies:</p>
                                                    <p>
                                                        <input type="text" name="medScoreRemedies" id="medScoreRemedies" style="width:50%" placeholder="Medium score Remedies" value="<?php echo $primaryQuestionMedPuzzleScoreRemedies; ?>"> 
                                                        <button type="button" onClick="managePuzzleRemedies('medScoreRemedies')">Manage Remedies</button>
                                                    </p>
                                                </p>
                                                <!-- High Score Remedies -->
                                                <p>
                                                    <p>High Score Remedies:</p>
                                                    <p>
                                                        <input type="text" name="highScoreRemedies" id="highScoreRemedies" style="width:50%" placeholder="High score Remedies" value="<?php echo $primaryQuestionHighPuzzleScoreRemedies; ?>"> 
                                                        <button type="button" onClick="managePuzzleRemedies('highScoreRemedies')">Manage Remedies</button>
                                                    </p>
                                                </p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End Puzzle options Div -->  
                                <!-- Hidden form submission type field -->
                                <input type="hidden"id="hasPuzzlesFormElement" name="hasPuzzlesFormElement" required style="width:100%" placeholder="Has Puzzles" value="<?php echo $primaryQuestionDetails["hasPuzzles"]; ?>">
                                  
                                 
                                <!-- tab buttons div -->
                                <div>
                                   

                                    <button type="button" onclick="addAnswerOption(<?php echo $primaryQuestionId; ?>,<?php echo $i; ?>)">Add Answer Option</button> 
                                    <button type="button" onclick="addPuzzlesOption()">Set Puzzles Options</button>
                                </div>
                                           
                                            	<div>
                                					<button type="submit">Save</button> <button type="reset">Clear</button>
                            					</div>
                            </form>
					</div>
					<?php } ?>
                </section>
                <!-- /.content -->
            </aside>
            <!-- /.right-side -->
        </div>
        <!-- ./wrapper -->
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
		
		<script src="../../assets/admin/js/custom.js" type="text/javascript"></script>
        
        <script>
            function setQuestions(elem){
                var rubricId = $(elem).find("option:selected").val();
                window.location.href="manageQuestions.php?rid="+rubricId;
            }
			
			function addPuzzlesOption(){
				$("#answerOptionsDiv").hide();
				$("#puzzleOptionsDiv").show();
				$("#hasPuzzlesFormElement").val("1");

			}
            
           /* function addAnswerOption(questionId, answerId, followUpQuesId, newFlag, new2Flag){
            	//console.log(questionId);
            	console.log(answerId);
            	//console.log(newFlag);
            	//console.log(new2Flag);
				$("#answerOptionsDiv").show();
				$("#puzzleOptionsDiv").hide();
				$("#hasPuzzlesFormElement").val("0");
				
				if(answerId===false){
					var priQuesIndex = questionId;
					if(newFlag && newFlag=="")
						priQuesIndex="new";
						//console.log(questionId);
            			//console.log(answerId);
					//ADDING ANSWER OPTION FOR PRIMARY
                    var noOfAnswers = $("#answerOptions_"+questionId+" .answerOption").length+1;
                    //console.log(answerOption);

                    var newAnswerOptionHtml = '<li id="answerOption_'+questionId+'_'+noOfAnswers+'" class="answerOption">';
                        newAnswerOptionHtml+=   '<div><input type="text" name="answerOption['+priQuesIndex+']['+noOfAnswers+'][answerLabel]" required style="width:50%" placeholder="Answer Label"> <button type="button" onclick="removeAnswerOption('+questionId+', '+noOfAnswers+')">Remove</button> <button id="addFollowUpQuestionButton_'+questionId+'_'+noOfAnswers+'" type="button" onclick="addFollowUpQuestion('+questionId+', '+noOfAnswers+', \'new\')">Add Follow up Question</button><button id="removeFollowUpQuestionButton_'+questionId+'_'+noOfAnswers+'" type="button" onclick="removeFollowUpQuestion('+questionId+', '+noOfAnswers+')" style="display:none">Remove Follow up Question</button><br/><input style="width:50%;" type="text" placeholder="Remedies" id="answerRemedies_'+questionId+'_'+noOfAnswers+'" name="answerOption['+priQuesIndex+']['+noOfAnswers+'][answerRemedies]" value=""> <button type="button" onclick="manageRemedies(0, '+noOfAnswers+')" "="">Manage Remedies</button></div>';
                        newAnswerOptionHtml+= '</li>';
                    $("#answerOptions_"+questionId).append(newAnswerOptionHtml);
                } else {
					//ADDING ANSWER OPTION FOR FOLLOW UP
					var followUpQuesIndex = followUpQuesId;
					if(newFlag && newFlag=="new")
						followUpQuesIndex="new";
						
					var priQuesIndex = questionId;					
					if(new2Flag && new2Flag=="new")
						priQuesIndex="new";
                    var noOfAnswers = $("#answerOptions_"+questionId+"_"+answerId+"_"+followUpQuesId+" .followQuesAnswerOption").length+1;					
                    var newAnswerOptionHtml = '<li id="followQuesAnswerOption_'+questionId+'_'+answerId+'_'+noOfAnswers+'" class="followQuesAnswerOption">';
                        newAnswerOptionHtml+=   '<div><input type="text" name="answerOption['+priQuesIndex+']['+answerId+'][followupQuestion]['+followUpQuesIndex+'][answerOptions]['+noOfAnswers+'][answerLabel]" required style="width:50%" placeholder="Answer Label"> <button type="button" onclick="removeAnswerOption('+questionId+', '+answerId+', '+noOfAnswers+')">Remove</button> <br/><input style="width:50%;" type="text" placeholder="Remedies" id="answerRemedies_'+questionId+'_'+answerId+'_'+noOfAnswers+'" name="answerOption['+priQuesIndex+']['+answerId+'][followupQuestion]['+followUpQuesIndex+'][answerOptions]['+noOfAnswers+'][answerRemedies]" value=""> <button type="button" onclick="manageRemedies('+questionId+', '+answerId+', '+noOfAnswers+')">Manage Remedies</button></div>';
                        newAnswerOptionHtml+= "</li>";
                    $("#answerOptions_"+questionId+"_"+answerId+"_"+followUpQuesId).append(newAnswerOptionHtml);
                }
            }*/
            
            function removeAnswerOption(questionId, answerId,quesid){
            	
            	$ques=quesid;
            	$ans=answerId;
            	var data={};
                data['info']='AnswerOptionRemove';
                data['Qid']= quesid;

                data['Aid']= answerId;
                load_code1(data,"answerOption.php");
            	
					
                
              }
           
            
            
			
			//setting some global vars for remedies dialog
			var currQuestionId = "";
			var currAnswerId = "";
			var currFollowupId = "";			
			function manageRemedies(questionId, answerId, followUpId){
				currQuestionId = questionId;
				currAnswerId = answerId;
				currFollowupId = followUpId;
				
				var selector = "answerRemedies_"+currQuestionId+"_"+currAnswerId;				
				if(currFollowupId)
					selector += "_"+currFollowupId;
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
			
			function managePuzzleRemedies(textBoxSelectorId){
				currQuestionId = "puzzle";
				currAnswerId = textBoxSelectorId;
				var selector = textBoxSelectorId;
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
			
			$(document).ready(function(){
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
								//alert(remedyNames);
							});
							
							var selector = "";
							if(currQuestionId=="puzzle"){
								selector = currAnswerId;
							} else {
								selector = "answerRemedies_"+currQuestionId+"_"+currAnswerId;
								if(currFollowupId !== undefined)
									selector += "_"+currFollowupId;
							}
							
							remedyNames = remedyNames.replace(/,,/g, ",");
							remedyNames = remedyNames.substr(0, (remedyNames.length-1));
							//alert(remedyNames);

							/*var remedyArray = new Array();
 							remedyArray = remedyNames.split(",");
 							alert(remedyArray); 							
							return remedyArray.slice().sort(function(a,b){return a > b}).reduce(function(a,b){if (a.slice(-1)[0] !== b) a.push(b);return a;},[]);*/
							//alert(remedyArray); 

							var existingRemediesInBox = $("#"+selector).val();
							if(existingRemediesInBox.length>0)
								existingRemediesInBox += ",";
							existingRemediesInBox += remedyNames;

							/*var remedyArray = new Array();
							
 							remedyArray = existingRemediesInBox.split(",");
 							alert(remedyArray); */
 							/*console.log(remedyArray);							
							 return remedyArray.slice().sort(function(a,b){return a > b}).reduce(function(a,b){if (a.slice(-1)[0] !== b) a.push(b);return a;},[]);
							console.log(remedyArray.slice().sort(function(a,b){return a > b}).reduce(function(a,b){if (a.slice(-1)[0] !== b) a.push(b);return a;},[]));					
							*/
							//var names = ["Mike","Matt","Nancy","Adam","Jenny","Nancy","Carl"];
/*
							 uniq = remedyArray.reduce(function(a,b){
							    if (a.indexOf(b) < 0 ) a.push(b);
							    return a;
							  },[]);

							console.log(uniq)*/

							$("#"+selector).val(existingRemediesInBox);
							$(this).dialog("close");
						},
						"Cancel": function(){
							$(this).dialog("close");
						}
					}
				});
			});
			
			Array.prototype.in_array = function(p_val) {
				for(var i = 0, l = this.length; i < l; i++) {
					if(this[i] == p_val) {
						return true;
					}
				}
				return false;
			}
			
			function jqueryEscape(str) {
				return  str.replace(/([ #;&,.+*~\':"!^$[\]()=>|\/])/g,'\\$1');
			}
			
			jQuery.fn.center = function () {
				this.css("position","absolute");
				this.css("top", (($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop() + "px");
				this.css("left", (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft() + "px");
				return this;
			}
			
			function addAnswerOption(questionId, answerId){
    			//alert("hiii");
    			var data={};
                data['info']='AnswerOption';
                data['Qid']= questionId;

                data['Aid']= answerId;
                load_code(data,"answerOption.php");
                
  			}
	  		var load_code=function(data,url){
	        jQuery.getJSON(url,{'data':data})
	        .done(function(result){
	          var res=result;
	          console.log(res);
	          var dt=res;
	          var questionId=dt['errMsg']['Qid'];
	           var answerId=dt['errMsg']['Aid'];
	           console.log(parseInt(answerId)+1);

	          $("#answerOptionsDiv").show();
				$("#puzzleOptionsDiv").hide();
				$("#hasPuzzlesFormElement").val("0");
				
				
					var priQuesIndex = questionId;
				var quesid=dt['errMsg']['Qidorig'];
						//priQuesIndex="new";

					
            			//console.log(answerId);
					//ADDING ANSWER OPTION FOR PRIMARY
                  
                    //console.log(answerOption);
                    var noOfAnswers1 = $("#answerOptions_"+questionId+" .answerOption").length+1;
                     var noOfAnswers = parseInt(answerId)+1;
                     if(noOfAnswers<noOfAnswers1)
                     {
                     	noOfAnswers++;
                     }
                    console.log(noOfAnswers);
                    console.log(noOfAnswers1);

                    var newAnswerOptionHtml = '<li id="answerOption_'+questionId+'_'+noOfAnswers1+'" class="answerOption">';
                        newAnswerOptionHtml+=   '<div><input type="text" name="answerOption['+priQuesIndex+']['+noOfAnswers+'][answerLabel]" required style="width:50%" placeholder="Answer Label"><div><input type="radio" name="answerOption['+priQuesIndex+']['+noOfAnswers+'][usergroup]" value="Patient_Only"/>Patient Only<br><input type="radio" name="answerOption['+priQuesIndex+']['+noOfAnswers+'][usergroup]" value="Doctor_Only"/>Doctor Only<br><input type="radio" name="answerOption['+priQuesIndex+']['+noOfAnswers+'][usergroup]" value="Both"/>Both</div><button type="button" onclick="removeAnswerOption('+questionId+', '+noOfAnswers+','+quesid+')">Remove</button> <button id="removeFollowUpQuestionButton_'+questionId+'_'+noOfAnswers+'" type="button" onclick="removeFollowUpQuestion('+questionId+', '+noOfAnswers+')" style="display:none">Remove Follow up Question</button><br/><input style="width:50%;" type="text" placeholder="Remedies" id="answerRemedies_'+questionId+'_'+noOfAnswers+'" name="answerOption['+priQuesIndex+']['+noOfAnswers+'][answerRemedies]" value=""> <button type="button" onclick="manageRemedies(0, '+noOfAnswers+')" "="">Manage Remedies</button></div>';
                        newAnswerOptionHtml+= '</li>';
                    $("#answerOptions_"+questionId).append(newAnswerOptionHtml);
                   //window.location.reload();
	       // window.location.href=window.location.href;
	                //Code
	        })
	        .fail(function( jqXHR, textStatus) {
	            console.log( "failed due:"+ textStatus);
	            console.log( "helll");
	          });
	 
	      }
 
 var load_code1=function(data,url){
	        jQuery.getJSON(url,{'data':data})
	        .done(function(result){
	          var res=result;
	          console.log(res);
	          var dt=res;
	          var questionId=dt['errMsg']['Qid'];
	           var answerId=dt['errMsg']['Aid'];
	          
			
					$("#answerOption_"+questionId+"_"+answerId).remove(); 
                   //window.location.reload();
	        
	                //Code
	        })
	        .fail(function( jqXHR, textStatus) {
	            console.log( "failed due:"+ textStatus);
	            console.log( "helll");
	          });
	 
	      }
        </script>

    </body>
</html>