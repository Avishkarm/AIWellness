<?php 
  
$rt = $_SERVER['DOCUMENT_ROOT'];
$controller_rt = $rt."/controllers";

require_once("../controllers/config.php");
require_once("../controllers/utilities.php");
require_once("../controllers/dbutils.php");
require_once("../controllers/accesscontrol.php");
require_once("../controllers/authentication.php");
require_once("../controllers/notification.php");





$conn = createDbConnection($servername, $username, $password, $dbname);
$conn = $conn["errMsg"];
//printArr($conn);
//printArr($_GET);

    

 $updateUser = "UPDATE `users` SET `status`='Active',`pass_code`='*****' where `InvoiceId`='".cleanQueryParameter($_GET["InvoiceId"])."' AND pass_code='".cleanQueryParameter($_GET["com_code"])."'"; 
$resultQuery=runQuery($updateUser,$conn);

if(noError($resultQuery)){
   
    if(mysql_affected_rows($conn) > 0){
        
        printArr("You have Successfully Verified your Account");
        $redirectURL='sign_in.php';
        print("<script>");
                print("var t = setTimeout(\"window.location='".$redirectURL."';\",1000);");
            print("</script>");
        }else{
            printArr("Error Verifying your Account ");
        }
}else{
    printArr("Error Verifying your Account ".$resultQuery['errMsg']);
}

    //printArr($resultQuery);
    die();


?>

<!DOCTYPE html>

<html class="bg-black">

    <head>

        <meta charset="UTF-8">

        <title>KES</title>

        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        <!-- bootstrap 3.0.2 -->

        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        <!-- font Awesome -->

        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />

        <!-- Theme style -->

        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />



        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

        <!--[if lt IE 9]>

          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>

          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>

        <![endif]-->

    </head>

    <body class="bg-black">

        <div class="form-box" id="login-box">

            <div class="header">Sign In</div>
            <div class="header"><a href="login.html"><u>Patients</u></a> | <a href="login_doc.html">Doctors</a></div>

            <form action="<?php echo CONTROLLER; ?>/login.php" method="post">

                <div style="background-color: #eaeaec;padding: 10px 20px">

				<br>

				<br>

                    <div class="form-group">

                        <input type="text" name="username" class="form-control" placeholder="Email Address"/>

                    </div>

					

                    <div class="form-group">

                        <input type="password" name="password" class="form-control" placeholder="Password"/>

                    </div>  

				</div>

                <div class="footer">                    



                    <button type="submit" class="btn bg-olive btn-block">Sign me in</button>  

                    

                    <p><a href="forget.html">I forgot my password</a></p>

                    

                    <a href="register.html" class="text-center">Register a new membership</a>

                </div>

            </form>



            <div class="margin text-center" style="display:none">

                <span style="color:#2F5292;">Sign in using social networks</span>

                <br/>

                <button class="btn bg-light-blue btn-circle"><i class="fa fa-facebook"></i></button>

                <button class="btn bg-aqua btn-circle"><i class="fa fa-twitter"></i></button>

                <button class="btn bg-red btn-circle"><i class="fa fa-google-plus"></i></button>



            </div>

        </div>





        <!-- jQuery 2.0.2 -->

        <script src="<?php echo VIEWS; ?>/js/jquery.min.js"></script>

        <!-- Bootstrap -->

        <script src="<?php echo VIEWS; ?>/js/bootstrap.min.js" type="text/javascript"></script>        



    </body>

</html>