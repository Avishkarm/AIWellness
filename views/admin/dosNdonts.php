<?php
session_start();

require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php");
require_once("../../utilities/authentication.php");
require_once("../../models/dosNdontsModel.php");

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




 $dos=getdondont($conn);   
//printArr($dos);
 if (isset($_POST['dosndonts'])) {	
    $do=cleanQueryParameter($conn, $_POST['dodnots']);
 	if($_POST['type']=='edit'){
         $query="UPDATE `dos_n_donts` SET doNdont='".$do."' WHERE id=".$_POST['id'];
        $result=runQuery($query, $conn);
        $redirectURL ="dosNdonts.php";
      header("Location:".$redirectURL); 
    }else if($_POST['type']=='save'){
        $query="INSERT INTO `dos_n_donts`(`doNdont`) VALUES ('".$do."')";
        $result=runQuery($query, $conn);
        $redirectURL ="dosNdonts.php";
      header("Location:".$redirectURL); 
    }
/*$do=cleanQueryParameter($conn, $_POST['dodnots']);	
$query="DELETE FROM `dos_n_donts`";
$result=runQuery($query, $conn);										
$query="INSERT INTO `dos_n_donts`(`doNdont`) VALUES ('".$do."')";
$result=runQuery($query, $conn);*/
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
                <section class="content" text align="left" style="float: left; width:100%;height:50%;">
                    <span class="redfamily"></span>
                    <div class="" style="margin:0;float: left; width:100%">
                        
					</div> 
					<div class="questionBox"style="height:500px">
					<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">

								 <textarea style="width:100%;height:450px;" name="dodnots">
								 	<?php if(!empty($dos['errMsg']['doNdont'])){ echo $dos['errMsg']['doNdont']; } ?>
								 </textarea> 
								<div class="box-footer">
                                    <?php if(empty($dos['errMsg']['doNdont'])){ ?>
                                    <input type="hidden" name="type" value='save'>
									<button type="submit" class="btn btn-primary" name="dosndonts" value="remedy" >
										Save
									</button>
                                    <?php }else{ ?>
                                    <input type="hidden" name="type" value='edit'>
                                    <input type="hidden" name="id" value='<?php echo $dos['errMsg']['id']; ?>'>
                                    <button type="submit" class="btn btn-primary" name="dosndonts" value="remedy" >
                                        Edit
                                    </button>
                                    <?php } ?>
						
								</div>
					</form>

					</div>
                </section>
                <!-- /.content -->
            </aside>
            <!-- /.right-side -->
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
		
		<script src="../js/custom.js" type="text/javascript"></script>
        
        <script>
 
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
							
							var existingRemediesInBox = $("#"+selector).val();
							if(existingRemediesInBox.length>0)
								existingRemediesInBox += ",";
							existingRemediesInBox += remedyNames;
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
                        newAnswerOptionHtml+=   '<div><input type="text" name="answerOption['+priQuesIndex+']['+noOfAnswers+'][answerLabel]" required style="width:50%" placeholder="Answer Label"> <button type="button" onclick="removeAnswerOption('+questionId+', '+noOfAnswers+','+quesid+')">Remove</button> <button id="removeFollowUpQuestionButton_'+questionId+'_'+noOfAnswers+'" type="button" onclick="removeFollowUpQuestion('+questionId+', '+noOfAnswers+')" style="display:none">Remove Follow up Question</button><br/><input style="width:50%;" type="text" placeholder="Remedies" id="answerRemedies_'+questionId+'_'+noOfAnswers+'" name="answerOption['+priQuesIndex+']['+noOfAnswers+'][answerRemedies]" value=""> <button type="button" onclick="manageRemedies(0, '+noOfAnswers+')" "="">Manage Remedies</button></div>';
                        newAnswerOptionHtml+= '</li>';
                    $("#answerOptions_"+questionId).append(newAnswerOptionHtml);
                   //window.location.reload();
	        window.location.href=window.location.href;
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