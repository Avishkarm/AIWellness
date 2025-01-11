<?php

//header('Access-Control-Allow-Methods: GET, POST');  
session_start();
  //$activeHeader="stress_calculater";
  $title="Subscribe";
  $pathPrefix="../";
  require_once("../utilities/config.php");
  require_once("../utilities/dbutils.php");
//include("../models/notificationModel.php");
include("../models/subscribModel.php");
  

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


	if (isset($_POST["submit"])) 
	{
		
				$email = cleanQueryParameter($conn,$_POST['email']);
				$name=cleanQueryParameter($conn,$_POST['name']);
				$mobile=cleanQueryParameter($conn,$_POST['mobile']);
				
				// Check if email has been entered and is valid
				if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
					$errEmail = 'Please enter a valid email address';
				}
				
				
		// If there are no errors, send the email
		if (!$errEmail) 
		{

			 $count=selectSubscriber($conn,$email);

					if($count==0)
					{
						
						$addSubscriber = addSubscriber($conn,$email,$name,$mobile);
					
							if($addSubscriber["errCode"]==-1)
							{	
								$subject="Eheilung subscription";
								$message='<div style="width: 80%;margin: 0 auto;margin-bottom:20px;">
							                  <img src="'.$image.'">
							                  <br>
							                  <h1 style="color: #454545;width: 60%;font-family: arial;font-size: 35px;">Congratulations!</h1>
							                  <br>
							                  <h4 style="width:70%;color: #454545;font-family: arial;letter-spacing: 1px;font-size: 25px;">You have successfully subscribed to eheilung</h4>
							                  
							                  </div>';        
							  $from = "eHeilung <donotreply@eheilung.com>";	

  							  //$sendMail=sendMail($email, $from, $subject, $message);
							  $redirectURL ="../index.php?status=successSubscribe";
							  $result='<div class="alert alert-success">Thank You! I will be in touch</div>';
							}
						 	else 
						 	{
						 		$redirectURL ="../index.php?status=failureSubscribe";
								$result='<div class="alert alert-danger">Sorry there was an error sending your message. Please try again later.</div>';
							}
						
					}
					else
					{
						$redirectURL ="../index.php?status=existSubscribe";
						$result='<div class="alert alert-danger">User Already subscribed</div>';
						
					}
		}

      	header("Location:".$redirectURL); 
	}
?>


<!DOCTYPE html>
  <html lang="en">
  <head>
  <?php include_once("metaInclude.php"); ?>
	<style type="text/css">
	
.btn.btn-common
    {
        background-color:#0be1a5 ;
        border-radius: 15px;
        border: 1px solid transparent;
        padding: 10px;         
        color: #555;
        width:100px;
    }
    .btn:hover, .btn:focus {

       border: 1px solid #999;
       background-color:#fff;
       color: #555;
    }

    .consult-btn{
    	background-color: #0dae04;
    border-radius: 7px;
    color: #fff;
    text-align: center;
    padding: 10px 33px;
    outline: none;
    border: none;
    /* width: 30%; */
    font-size: 23px;
    margin-top: 10px;
    margin-bottom: 20px;
    }

    .height{
    	height: 450px;
    }
    @media(max-width: 768px){
    	.height{
    	height: 600px;
    }
    }
	</style>
<main class="container" style="min-height: 100%;">
     <?php  include_once("header.php"); ?> 

  
  		<div class="row">
  			<div class="col-md-12 height" style="background-image:url(../assets/images/newsletter.png);background-size:cover;">
  				<h1 class=" text-center" style="font-size: 170%;color:#fff;">Subscribe To eHeilung Newsletter</h1>
				<form class="form-horizontal" role="form" method="post" action="subscriber.php">
					
					
						<div class="form-group" style="margin:50px 0 30px 0;padding:0;">
						<label for="name" class="col-sm-2  col-sm-offset-2 control-label" style="color:#fff;text-align:left">Name</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="name" name="name" placeholder="Your name" value="">
								
							</div>
					    </div>

						<div class="form-group" style="margin:0 0 30px 0;padding:0;">
						<label for="email" class="col-sm-2  col-sm-offset-2  control-label" style="color:#fff;text-align:left">Email</label>
							<div class="col-sm-6">
								<input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com" value="<?php 	echo htmlspecialchars($_POST['email']); ?>">
								<h4 class="errMsg" style="text-align: left"><?php echo "<p class='text-danger' style='color:red'>$errEmail</p>";?></h4>
							</div>
				     	</div>

					    <div class="form-group" style="margin:0 0 30px 0;padding:0;">
						<label for="mobile" class="col-sm-2  col-sm-offset-2  control-label" style="color:#fff;text-align:left">Mobile</label>
							<div class="col-sm-6">
								<input type="number" class="form-control" id="mobile" name="mobile" placeholder="mobile number" value="">
								
							</div>
					    </div>


					<div class="form-group">
						<div class="col-sm-2 col-sm-offset-8">
							<input id="submit" name="submit" type="submit" value="Subscribe" class="consult-btn pull-right">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10 col-sm-offset-2">
							<?php echo $result; ?>	
						</div>
					</div>
				</form> 
			</div>
		</div>
	
	 </main> 
	 <?php include('footer.php'); ?>
</body>
</html>