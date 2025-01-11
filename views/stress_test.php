<?php 
session_start();
	require_once("../utilities/config.php");
  	require_once("../utilities/dbutils.php");
	require_once("../models/userModel.php");
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
	   $redirectURL ="../index.php?luser=doctor";
  	   header("Location:".$redirectURL); 
	  exit;
	}
/*
	  $user = "";
if(isset($_SESSION["user"]) && !in_array($_SESSION["user"], $blanks)){
  $user = $_SESSION["user"];
  $user_type=$_SESSION["user_type"];
  $patient_id=$_GET['patient_id'];
  $session=true;
} else {
	$session=false;
}  */


	$type=$_GET['type'];
	
	if($type=="adult"){
		
		$causationsQuery = "SELECT * FROM causation_master_adults";
	} 
	else if($type=="minor") {
		$causationsQuery = "SELECT * FROM causation_master_minors";
	}
	$causationsResults = runQuery($causationsQuery, $conn);
	//printArr($causationsResults);
	if(noError($causationsResults)){
		$causationsData = array();
		while($row = mysqli_fetch_assoc($causationsResults["dbResource"])){
			$causationsData[$row["causation_id"]]=$row;

		}
	} else {
		printArr("Error fetching causation data");
		exit;
	}



?>
<style type="text/css">
input[type="checkbox"] {
  display: none;
}

label {
  	cursor: pointer;
	display: flex;
	max-width: 100%;
	margin-bottom: 21px;
	font-size: 22px;
	word-spacing: 4px;
	letter-spacing: 1px;
	font-weight: normal!important;
}
input[type="checkbox"] + label:before {
	border: 1px solid #555;
	content: "\2713";
	display: inline-block;
	font: 25px/1em sans-serif;
	height: 23px;
	margin: 0 1.75em 0 0;
	padding: 0;
	vertical-align: top;
	width: 23px;
	border-radius: 3px;
	color: #ffffff;
	margin-top: 6px;
}
input[type="checkbox"]:checked + label:before {
  background: #0dae04;
  color: #ffffff;
  content: "\2713";
  text-align: center;
  border: transparent;
  width: 23px;
  padding: 1px;
  margin-top: 6px;
}

input[type="checkbox"]:checked + label:after {
  font-weight: bold;
}
.submit-btn{
    background-color: #0dae04;
    border-radius: 7px;
    color: #fff;
    text-align: center;
    padding: 15px 55px;
    outline: none;
    border: none;
    font-size: 23px;
    margin-top: 10px;
    margin-bottom: 20px;

}
.login-btn{
    background-color: #0dae04;
    border-radius: 7px;
    color: #fff !important;
    text-align: center;
    padding: 15px;
    outline: none;
    border: none;
    font-size: 23px;
    margin-top: 10px;
    margin-bottom: 20px;

}
.close{
	outline:none;
	border:none;
	opacity: 1;
}
#score-result h3{
	font-family: Montserrat-Regular !important;
    font-weight: 100;
    line-height: 1.3;
}
/*@media(max-width: 360px){
.highcharts-container
{
	right:20px;
}
}*/    
@media(max-width: 375px){
	.highcharts-container
	{
		right:0px;
	}
}

@media(max-width: 320px){
	.highcharts-container
	{
		right:5px;
	}
}
@media(max-width: 786px){
	.text h3{
		font-size: 19px
	}
	.event{
		font-size: 15px
	}
	input[type="checkbox"] + label:before {
		margin-top: 0px!important;
	}
	input[type="checkbox"]:checked + label:before {
		margin-top: 0px!important;
	}
}

</style>

<div class="row text" style="margin-bottom: 6%;">
	<h3 style="letter-spacing: 1px;">Select events that have occured in your life or ones that you have experienced.</h3>
</div>
<form  name="casesheetform" id="casesheetform" method="POST">
<input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
<?php
$totalScore=0;
foreach($causationsData as $causationId => $causationDetails){
          $causationScore = $causationDetails["causation_score"];
          $totalScore+=$causationScore;
?>
	<div class="form-group" style="border-bottom: 1px solid #BFBFBF; margin: 1.5em 0;
  padding: 10px;">
		<input class="logedOutCause" id="score_<?php echo $causationId; ?>"  value="<?php echo $causationScore; ?>" data-score="<?php echo $complaintsScore; ?>" causationId="<?php echo $causationId; ?>" type="checkbox" />
		<label class="event" for="score_<?php echo $causationId; ?>">	
		<?php echo $causationDetails["causation_text"]; ?>
		</label>
	</div>

<?php
}
?>	

	<div class="form-group" style="text-align: center;margin-top:80px;" >
	<h4 id="errMsg" style="color:red;"></h4>
	<input type="button" name="" id="" class="submit-btn" value="SUBMIT">
	</div>

</form>
<!--Modal start-->
     <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
           <div class="modal-header" style="border:none;">
            <button type="button" class="close" data-dismiss="modal"><img style="width: 70%;" src="../assets/images/close.png"></button>
            <h1 class="text-center">Your stress score</h1>
           </div>
          <div class="modal-body" style="padding:0px;">
          	<!-- <div class="text-center" id="score" style="font-size:40px;">
          	</div> -->
          	<div id="score" class="text-center" style="width: 300px; height: 300px; margin: 0 auto">
			</div>
          	<div id="score-result" style="line-height: 31px;letter-spacing: 1px;">
          	</div> 
          </div>
          <div class="modal-footer" style="border:none;">
          </div> 
        </div>
      </div>
     </div>

<script type="text/javascript">
  		var allVals = [];
  		var totalScore=<?php echo $totalScore;?>;
  	$('input[type="checkbox"]').click(function(){
  	 
        if($(this).prop("checked") == true){
           allVals.push($(this).val());
           $("#errMsg").hide();
        }
        else if($(this).prop("checked") == false){
            allVals.splice($.inArray($(this).val(), allVals), 1);
        }
    });

	$(".submit-btn").click(function(){
		var score=0;
		if(allVals==""){
			$("#errMsg").html("please select an event");
			$("#errMsg").show();
		}
		else{
			$("#errMsg").empty("");
			var allVals_array = allVals.map(Number);
			score=allVals_array.reduce(function(a, b){return a+b;})
			
           	if(score<=100){
           		var y_value=34;
	            var result="<div class='col-md-12' style='padding: 0 40px 40px 40px;'><h3>You seem fine and may not need medical help.</h3></div>"
	            //$('#score').html(score);
	          	$('#score-result').html(result);
           }
           else if(score<300)
           {
           		var y_value=68;
           		var result="<div class='col-md-12' style='padding: 0 40px 40px 40px;'><h3>Your health seems to have deviated from the normal curve and you seem to be a bit stressed. You may need medical help in the near future if not now.</h3><div>"
           		//$('#score').html(score);
          		$('#score-result').html(result);
           }
           else if(score>=300)
           {
           	 var y_value=100;
           	 var result="<div class='col-md-12' style='padding: 0 40px 40px 40px;'><h3>You've undergone and have endured a lot. Body is now showing patological effects of stress from your life experiences. In allopathic system of medicine theres not much that can be offerred apart from some mood elevator or dpressor that only works superficially and temporarily making you dependent and addicted to drugs. Get a homeopathic consultation now. Please login to continue</h3></div>"
           	 //$('#score').html(score);
          	$('#score-result').html(result);
           }

           /*var session=<?php echo $session; ?>;
           if(session){
	           	var formdata=$("#casesheetform").serialize();
	            $.ajax({type: "POST",
			            url:"../controllers/stressCausationsController",
			            data:{'formdata':formdata},
			            dataType:'JSON',
			            beforeSend: function () {
			              $('.load').show();
			              $('.signup').hide();
			            }
			      })
			      .done(function(data) {
			      	console.log("causation data saved successfully");
			      })    
			      .fail(function(jqXHR, textStatus, errorThrown) {
			       console.log("fail to save causation data");
			        console.log(jqXHR.responseText);
			       })  
			       .error(function(jqXHR, textStatus, errorThrown) { 
			        console.log(jqXHR.responseText);
			       });
			}  */
			// Uncomment to style it like Apple Watch

			if (!Highcharts.theme) {
			    Highcharts.setOptions({
			        chart: {
			            backgroundColor: 'none'
			        },
			        colors: ['#0dae04','#ffb600','#bf1a00'],
			        /*title: {
			            style: {
			                color: 'silver'
			            }
			        },
			        tooltip: {
			            style: {
			                color: 'silver'
			            }
			        }*/
			    });
			}
			var ColorIndex=0;
           	if(Highcharts.numberFormat(score, 0)>0 && Highcharts.numberFormat(score, 0)<=100)
           	{
           		ColorIndex=0;
           	}else if(Highcharts.numberFormat(score, 0)>100 && Highcharts.numberFormat(score, 0)<300)
           	{
           		ColorIndex=1;
           	}
           	else if(Highcharts.numberFormat(score, 0)>=300)
           	{
           		ColorIndex=2;
           	}
           	else
           	{
           		ColorIndex=2;
           	}


			Highcharts.chart('score', {

			    chart: {
			        type: 'solidgauge',
			        marginTop: 50
			    },

			    title: {
			        text: ''
			    },
			     credits: {
			        enabled: false,
			        href:''
			    },
			    tooltip: { enabled: false },

			   

			    pane: {
			        startAngle: 0,
			        endAngle: 360,
			        background: [{ // Track for Move
			            outerRadius: '100%',
			            innerRadius: '90%',
			            backgroundColor: Highcharts.Color("#ccc").setOpacity(0.3).get(),
			            borderWidth: 0
			        }/*, { // Track for Exercise
			            outerRadius: '75%',
			            innerRadius: '70%',
			            backgroundColor: Highcharts.Color(Highcharts.getOptions().colors[2]).setOpacity(0.3).get(),
			            borderWidth: 0
			        }*/]
			    },

			    yAxis: {
			        min: 0,
			        max: 100,
			        lineWidth: 0,
			        tickPositions: []
			    },

			    plotOptions: {
			        solidgauge: {
			           dataLabels: {
			          enabled: true,
			          y: -20,
			          borderWidth: 0,
			          backgroundColor: 'none',
			          color:Highcharts.getOptions().colors[ColorIndex],
			          useHTML: true,
			          shadow: false,
			          style: {
			            fontSize: '40px'
			          },
			           formatter: function() {
			           	
			            return '<div style="width:100%;text-align:center;font-size: 33px;margin-left: 2px;"><span color:' + Highcharts.getOptions().colors[ColorIndex] + '!important;font-weight:bold;font-size: 33px!important;margin-left: 18px;">' + Highcharts.numberFormat(score, 0) + '/'+<?php echo $totalScore; ?>+'</span>';
			          },
			        	 positioner: function (labelWidth) {
			            return {
			                x: 200 - labelWidth / 2,
			                y: 180
			            };
			        }
			        },
			       
			            linecap: 'round',
			            stickyTracking: false,
			            rounded: true
			        }
			    },
			    

			    series: [{
			        name: 'Move',
			        borderColor: Highcharts.getOptions().colors[ColorIndex],
			        data: [{
			            color: Highcharts.getOptions().colors[ColorIndex],
			            radius: '100%',
			            innerRadius: '90%',
			            y: y_value
			        }]
			    }/*, {
			        //name: 'stress score',
			        //borderColor: Highcharts.getOptions().colors[1],
			        data: [{
			            color: Highcharts.getOptions().colors[1],
			            radius: '75%',
			            innerRadius: '70%',
			            y: y_value,
			        }]
			    }*/]
			},			
			function callback() {

			    // Move icon
			    /*this.renderer.path(['M', -8, 0, 'L', 8, 0, 'M', 0, -8, 'L', 8, 0, 0, 8])
			        .attr({
			            'stroke': '#303030',
			            //'stroke-linecap': 'round',
			            //'stroke-linejoin': 'round',
			            //'stroke-width': 2,
			            'zIndex': 10
			        })
			        .translate(26, 190)
			        .add(this.series[2].group);

			    // Exercise icon
			   this.renderer.path(['M', -8, 0, 'L', 8, 0, 'M', 0, -8, 'L', 8, 0, 0, 8, 'M', 8, -8, 'L', 16, 0, 8, 8])
			        .attr({
			            'stroke': '#303030',
			            'stroke-linecap': 'round',
			            'stroke-linejoin': 'round',
			            'stroke-width': 2,
			            'zIndex': 10
			        })
			        .translate(190, 61)
			        .add(this.series[2].group);
*/
			   
			});



			$("#myModal").modal();
		}

    });
</script>
