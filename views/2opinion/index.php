	<?php
	session_start();

	//$title="2nd-opinion";
        $title="Case Checker";
 	$activeHeader="2opinion"; 	
 	$activeHeader1="2opinion";

	require_once("../../utilities/config.php");
	require_once("../../utilities/dbutils.php");
	require_once("../../models/commonModel.php");
	require_once("../../models/userModel.php");
	$_SESSION['flag']=false;  

	//database connection handling
	$conn = createDbConnection($servername, $username, $password, $dbname);
	$returnArr=array();
	if(noError($conn)){
		$conn = $conn["errMsg"];
	}
	else{
		printArr("Database Error");
		exit;
	}

	$user = "";
	if(isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)){
	  $user = $_SESSION["user"];
	  $user_type=$_SESSION["user_type"];

	  $userInfo = getUserInfoWithUserType($user,$user_type,$conn);
	  if(noError($userInfo)){
	    $userInfo = $userInfo["errMsg"];  
	  
	  } else {
	    printArr("Error fetching user info".$userInfo["errMsg"]);
	    exit;
	  }
	} else {
	   $redirectURL ="../../index.php?luser=doctor";
  	   header("Location:".$redirectURL); 
	  exit;
	}

	$complaintsQuery=getSystemComplaint($conn,'');
	
	if(noError($complaintsQuery)){		
		$complaintsQuery=$complaintsQuery['errMsg'];
	}            
	else{
		$msg= printArr("Error Fetching Complaints Data".$complaintsQuery['errMsg']);	
	}

	$complaintsQuery1=getSystemCommonNames($conn,'');
  	
	if(noError($complaintsQuery1)){    
	   $complaintsQuery1=$complaintsQuery1['errMsg'];
    }            
	else{
	   $msg= printArr("Error Fetching Complaints Data".$complaintsQuery1['errMsg']);  
	}

	$TreatmentQuery= getAllTreatment($conn);
	 // printArr($TreatmentQuery);
	if(noError($TreatmentQuery)){
	  $TreatmentQuery=$TreatmentQuery['errMsg'];
	} else {
	    printArr("Error Fetching Complaints Data".$TreatmentQuery['errMsg']);
	}

	 //printArr($complaintsQuery);
	?>

	<!DOCTYPE html>
	<html lang="en">
	<head>
		<?php include_once("../metaInclude.php"); ?>
		<style type="text/css">
			.complaintsDetails{
				padding-left: 0px;
				padding-right: 0px;
			}
			.submit-btn{
			    background-color: #0dae04;
			    border-radius: 7px;
			    color: #fff;
			    text-align: center;
			    padding: 10px 50px;
			    outline: none;
			    border: none;
			    font-size: 23px;
			    margin-top: 10px;
			    margin-bottom: 20px;
			}
			.heading-info {
			    margin-left: 5px;
			    vertical-align: super;
			    cursor: pointer;
			    height: 20px;
			}
			.chosen-container .chosen-results {
				font-size: 19px !important;
			}
			.chosen-container-multi .chosen-choices li.search-field input[type=text] {
				color:#777;
			}
			.chosen-container-active .chosen-choices {
				    border: 1px solid #aaa !important;
				    box-shadow: 0 0 5px rgba(0,0,0,.3);
				}
			.chosen-container-multi .chosen-choices {
			    font-size: 19px !important;
			    font-weight: 500 !important;
			    padding: 15px 15px !important;
			    background: #fff !important;
			   /* border-radius: 4px !important;*/
			}
			.chosen-container-multi .chosen-choices li.search-choice {
				padding: 10px 20px 10px 10px !important;
			}
			.chosen-single {
				height: 62px !important;
    			padding: 15px 15px !important;
    			/*color: #444 !important;*/
			    font-size: 100% !important;
			    font-family: sans-serif;
			    line-height: normal;
			    border: 1px solid #aaa !important;
			    background: #fff !important;
			    -webkit-transition: all .2s linear !important;
			    -webkit-transition-property: border,background,color,box-shadow,padding !important;
			    transition: all .2s linear !important;
			    transition-property: border,background,color,box-shadow,padding !important;
			    border-radius: 0!important;
			   /* border-radius: 4px !important;*/
			}
			.chosen-container-single .chosen-single div b {
			    margin-top: 15px;
			}
			.chosen-container {			    
			    padding: 20px 0px 0px 0px;
			    font-size: 19px !important;
			}
			.head{
				margin-bottom: 30px;
    			margin-top: 15%;
			}
			.timeDuration .month{
			    margin-left: 19px;
			    margin-right: 19px;
			}
			@media(max-width: 786px){
				.head{
					font-size: 25px;
				}	
				.timeDuration .month{
				    margin-left: 0px;
				    margin-right: 0px;
				}
			}	
			@media screen and (min-width: 992px) and (max-width: 1200px) {
				.timeDuration .month{
				    margin-left: 15px;
				    margin-right: 15px;
				}
			}
			@media(max-width: 435px){
				.bannerContent{
				font-size: 17px;		
				}
			}
			.durationInput{
				height: 55px;
    			border-radius: 0;
			}
			.timeDuration .col-md-3{
				width:31%;
				padding-right: 0px;
				padding-left: 0px;
			}
			.bannerContent{
				color:#555;
				line-height: 1.4em;
				word-spacing: 1px;
				letter-spacing: 1px;				
			}
			.banner{
					position: relative; 
   					width: 100%;
			}
			.banner h1 {
			    position: absolute;
			    top: 2px;
			    left: 18px;
			    width: 90%;
			    padding: 40px;
			    font-size: 55px;
			    letter-spacing: 1px;
			    word-spacing: 3px;
			    line-height: 100%;
			}			
			@media(max-width: 1024px){
				.banner h1 {
				    position: absolute;
				    top: -4px;
				    left: 11px;
				    width: 90%;
				    padding: 40px;
				    font-size: 40px;
				    letter-spacing: 4px;
				    word-spacing: 0px;
				    font-weight: 600;
				}
			}
			@media(max-width: 786px){
				#dropdown1,#dropdown2{
					width:100%;
				}
				.banner h1 {
				    position: absolute;
				    top: -12px;
				    left: 9px;
				    width: 90%;
				    padding: 32px;
				    font-size: 32px;
				    font-weight: 600;
				    letter-spacing: 1px;
				    word-spacing: 8px;
				}
			}
			@media(max-width: 435px){
				.banner h1 {
				    position: absolute;
				    top: -40px;
				    left: -12px;
				    width: 100%;
				    padding: 40px;
				    font-size: 12px;
				    letter-spacing: 1px;
				    word-spacing: 4px;
				    font-weight: 600;
				    line-height: 1.3em;
				}
			}	
			
.chosen-container-multi .chosen-choices li.search-choice .search-choice-close{
        background: url('../../assets/images/error.png')  no-repeat !important;
        background-size: contain !important;
  }

  .chosen-container-single .chosen-single span {
      margin-top: 3px !important;    
      color: #999;
      font-family: inherit !important;
  }


		</style>
		<main class="container" style="min-height: 100%;">
		<link rel="stylesheet" type="text/css" href="../../assets/css/chosen.min.css">
			<?php  include_once("../header.php"); ?> 
			<section>
			<div class="main-container">
				<form name="startform" method="post" id="startform" action="">
					<div class="row">
		              <div class="col-md-12 banner" style="margin: 0px 0px 30px 0px;" >
		             	 <img src="../../assets/images/2opinionbanner.png" class="img-responsive">
		             	 <h1><span style="color:#ffb600;">Let's see whether your treatment is on track or off the rails! </span><span style="color:#ffffff;">Introducing a mathematical application built by the researcher Dr.&nbsp;Khedekar</span></h1>
		              </div>
		            </div>
		            <div class="row">
		              <div class="col-md-12" style="" >
		             	 <h3 class="bannerContent" style="color: rgba(0,0,0,.87);">Is your treatment on track?</h3>
                                 <h3>Introducing the most scientifically accurate software that can tell if your diagnosis and treatment are correct.</h3>
                                 <h3>Every Doctor has a right to double check their line of treatment. The slightest mistake can have serious consequences for the patient. So it’s crucial to ensure the diagnosis and treatment is correct. eHeilung is a foolproof program that checks if your treatment is on track or on the wrong path. The software is built by the some of the best brains in technology and medicine, who applied the classical principles of Hahnemann, pathology, bio-energetics, embryology and genetics to this groundbreaking software.</h3>
		              </div>
		            </div>
		            <div class="row">
		              <div class="col-md-12 complaintsDetails" style="" >
		              	<div class="col-md-6 col-sm-6" id="dropdown1 mainComplaint" >
		              	<h2 class="head" >Main Complaint <img src="../../assets/images/info.png" class="heading-info"  /></h2>
		              	<div class="complaintBox">
			                  <select data-placeholder="Select" class="chosen-select form-control" required="" type="text" id="complaint" name="complaint[]" multiple="" style="padding: 5px;">
			                  <option></option>     
									<?php
									foreach($complaintsQuery as $complaintsId=>$complaintsDetails)
									{
										//$complaintsName1=cleanQueryParameter($conn,utf8_encode($complaintsDetails["Common_name"]));
										$complaintsName=cleanQueryParameter($conn,utf8_encode($complaintsDetails["Diagnostic_term"]));
										$selected = "";
										if($complaintsName==$caseDetails["Common_name"])
											$selected = "selected";
										?>  

									<option <?php echo $selected; ?> value="<?php echo $complaintsName_new=preg_replace('/\s+/', '_', $complaintsName);?>">
		                            <?php echo ucfirst(strtolower(stripcslashes(str_replace("\u00a0", "",$complaintsName))));?>
		                            </option>
		                           	<?php }
					                foreach($complaintsQuery1 as $complaintsId1=>$complaintsDetails1)
					                {
					                    $complaintsName1=cleanQueryParameter($conn,utf8_encode($complaintsDetails1["Common_name"]));
					                    if($complaintsName1 !='Eczema' && $complaintsName1!='Dermatitis'){ ?>
		                            <option <?php echo $selected; ?> value="<?php echo $complaintsName_new=preg_replace('/\s+/', '_', $complaintsName1);?>">
		                            <?php echo ucfirst(strtolower(stripcslashes(str_replace("\u00a0", "",$complaintsName1))));?>
		                            </option>                  

										<?php
									}}
									?> 
			                  </select>
			            </div>
			            <div class="" id="errorMsg" style="color:red;"></div>	
			            <div class="" id="duration" style="margin-top: 20px"></div>
		              	</div>

		              	   	<div class="col-md-6 col-sm-6" id="dropdown2 mainComplaint" >
		              	<h2 class="head">Type of treatment taken</h2>
		              	<div class="complaintBox" style="padding-top: 3px;">
			                  <select data-placeholder="Select" class="chosen-select form-control" required="" type="text" id="treatment_type" name="treatment"  style="padding: 5px;">
			                        <option></option>                        
			                         <?php
			                            foreach($TreatmentQuery as $key=>$value){
			                         ?>
			                                <optgroup label="<?php echo $value['type_name'];?>">

			                          <?php
			                          $TreatmentQuery1= getAllTreatmentSubtype($conn,$key);
			                         
			                            foreach($TreatmentQuery1['errMsg'] as $key1=>$value1){
			                         ?>
			                            <option <?php echo $selected; ?> value="<?php echo $value['type_name'];?>">
			                            <?php echo $value1['subtype_name'];?>
			                            </option>
			                            <?php } ?>
			                            </optgroup>
			                          <?php
			                            }
			                          ?>
			                  </select>
			            </div>
			            <div class="" id="errorMsg1" style="color:red;"></div>
		              	</div>
		              </div>
					</div>
					<div class="row">
		              <div class="col-md-12" style="text-align: center;margin-top:80px;" >
		              	<!-- <h4 id="errMsg" style="color:red;"></h4> -->
						<input type="button" name="" id="" class="submit-btn" value="SUBMIT">
		              </div>
		            </div>
				</form>
			</section>
		</main> 
		<?php include('../modals.php'); ?>
		<?php include('../footer.php'); ?>
<script type = "text/javascript" src= "../../assets/js/chosen.jquery.min.js"></script>
<script type="text/javascript">
	$(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});
	/*$(".default").val("Select");*/


	$(".heading-info").click(function(){		
		$('#infoModal').modal();
		$('.modal-body').html('<div style="" class="modal-list"><ul class="modal-data"><li style="list-style-type:none;"><span>Please enter the ‘Chronic disease’ diagnosis here. Not for actuates but can be used for ‘Acute Exacerbation’ of Chronic like in Asthma, etc.</span></li></ul></div>')
     });
	$('#errorMsg').hide();
 	$('#errorMsg1').hide();
	$("#complaint").change(function(){
       $('#errorMsg').hide();
              setDurationBoxes();
        });


/*function ucFirstAllWords( str )
{
	str=str.toLowerCase()
    var pieces = str.split(" ");
    for ( var i = 0; i < pieces.length; i++ )
    {
        var j = pieces[i].charAt(0).toUpperCase();
        pieces[i] = j + pieces[i].substr(1);
    }
    return pieces.join(" ");
}*/
function stripslashes (str) {

  return (str + '').replace(/\\(.?)/g, function (s, n1) {
    switch (n1) {
    case '\\':
      return '\\';
    case '0':
      return '\u0000';
    case '':
      return '';
    default:
      return n1;
    }
  });
}

function titleCase(str) {
	string=str.toLowerCase()
    return string.charAt(0).toUpperCase() + string.slice(1);
}

	function setDurationBoxes()
        {
        	var val = $("#complaint").val();
        	//console.log(val);
        	if(val!== null){
        		$("#duration").show();
	            var vals = val.toString();
	        	
	            var values = vals.split(",");
	         
	            var complaintName = "";
	            var durationHTML = "";
	            var validation

	            for(var i in values)
	            {
	            	//console.log(values[i]);
	              complaintName = $("option[value="+values[i]+"]").text();//console.log(complaintName);
	              if(complaintName==""){
	              		complaintName=titleCase(stripslashes(values[i].replace(/_/g, ' ')));
	              }
	              durationHTML += '<div class="col-md-12 timeDuration" style="padding-right: 0px;padding-left:0px;margin-bottom:30px" id="durationsBox_'+i+'"><div style="font-size:20px;padding-bottom:10px;">'+complaintName+'</div><div class="col-md-3" style="padding-left:0px"><input class="form-control durationInput" type="number" placeholder="Year" name="probDuration['+values[i]+'][years]" id="year_'+i+'" value="" min="1" required/><label for="probDurationYears"></label></div><div class="col-md-3 month" style=""><input class="form-control durationInput" placeholder="Month" type = "number" name="probDuration['+values[i]+'][months]"  id="month_'+i+'" min="1" max="12" value="" required /><label for="probDurationMonths"></label></div><div class="col-md-3"><input class="form-control durationInput" type="number" placeholder="Day" name="probDuration['+values[i]+'][days]"  id="day_'+i+'" value="" min="1" max="31"  required/><label for="probDurationDays"></label></div></div>';
	            }

	            $("#duration").html(durationHTML);
        	}else{
        		$("#duration").hide();
        	}
        }

         	
         $('.submit-btn').click(function() {
        $('#errorMsg1').hide();
          var vals = $("#complaint").val(); 
          var options = $('#complaint > option:selected');                       
           var treatment=$("#treatment_type").val();
          var options1 = $('#treatment_type > option:selected');
          //alert(options1.length+".."+options.length+"--");
          console.log(vals);
          var complaint;
           if(options.length == 0)
          {
             $('#errorMsg').show();
            $('#errorMsg').html("Please select your complaint('s)");
            $('#complaint').focus();
            return false;
            
          }
          else if(treatment.length == 0)
          {
            $('#errorMsg1').show();
            $('#errorMsg1').html("please select your treatment");
            $('#treatment_type').focus();
           return false;
          }
        else{
	        	  if(vals)
		          {
		            complaint= vals.pop();
		          }
		          complaint=complaint.replace(/_/g, ' ');
                 var duration=[];
                    var flag = 1;
                $(".timeDuration").each(function(i){
                 // console.log("inside timeDuration"+i);
                   var year,month,day;
                   year = $(".timeDuration").find('input#year_'+i).val();
                   month = $(".timeDuration").find('input#month_'+i).val();
                   day = $(".timeDuration").find('input#day_'+i).val();
                 
                 // console.log(year+':'+month+':'+day);
                  var total=(year*365+month*30+day*1);
                  //alert(total);
                  
                  duration.push(total);
                 
                    if(!$(this).find('input').filter(function(){ return $(this).val(); }).length)
                    
                    {
                      flag++;
                      $(this).focus();
                      $('#errorMsg').show();
                      
                      $('#errorMsg').html("please enter complaint duration");

                    }
                 });
                     //alert(duration);
                     var max = Math.max(...duration);
              /*    Math.floor(1.6);
                   Math.ceil(1.4)
                   Math.round(2.5);*/
                     max1=max/2;
                      //alert(max1);
                     year1=Math.floor(max1/365);
                     month1=Math.floor(Math.floor(max1%365)/30);
                     day1=(Math.floor(max1%365)%30);
                     //day1=Math.floor((max1%365));
                   // alert(year1+':'+day1);
                 



                    if (flag == 1)
                    {
                       $('#errorMsg').hide();
                       if(treatment == "Modern")
                       {
                          window.location.href = 'modern.php?complaint='+complaint;
                       }
                       else
                       {
                        window.location.href = 'firstHalf.php?year='+year1+'&month='+month1+'&day='+day1+'&complaint='+complaint;
                       }
                    }
                        
                        

                    else
                    {
                       
                       
                      return false;
                    }

                    var datastring = $("#startform").serialize();
                    localStorage.setItem('startform', datastring);
                    localStorage.setItem('start_flag', true);
                  /* var name = localStorage.getItem('startform');

                    if (name != "undefined" || name != "null") {
                      $("#welcomeMessage").html(name);
                   
                    } else
                    {
                       $("#welcomeMessage").html("Hello!");
                    }*/
      }
      

    });

/*

	$('.submit-btn').click(function() {
        window.location.href = 'modern.php';
    });*/
</script>
</body>

</html>
