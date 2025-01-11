<?php
session_start();
require_once("../../../utilities/config.php");
require_once("../../../utilities/dbutils.php");
require_once("../../../utilities/authentication.php");
require_once("../../../models/admin/doctorsContactModel.php");

$conn = createDbConnection($servername, $username, $password, $dbname);
$returnArr=array();


if(noError($conn)){
    $conn = $conn["errMsg"];
    $user = "";
    if(isset($_SESSION["admin"]) && !in_array($_SESSION["admin"], $blanks)){
        $user = $_SESSION["user"];  
       
        $path = "../../../assets/uploads/";

    $valid_formats = array("jpg", "png", "gif", "bmp");
    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
        {
            $name = $_FILES['photoimg']['name'];
            $size = $_FILES['photoimg']['size'];
            
            if(strlen($name))
                {
                    list($txt, $ext) = explode(".", $name);
                    if(in_array($ext,$valid_formats))
                    {
                    if($size<(1024*1024))
                        {
                            $actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
                            $tmp = $_FILES['photoimg']['tmp_name'];
                            if(move_uploaded_file($tmp, $path.$actual_image_name))
                                {
                                mysqli_query($conn,"UPDATE admin_contact_doctor SET image='$actual_image_name' WHERE doctorId=".$_POST['userId']);
                                    
                                    echo "<img style='height:100px;'' src='../../assets/uploads/".$actual_image_name."'  class='preview'>";
                                }
                            else
                                echo "failed";
                        }
                        else
                        echo "Image file size max 1 MB";                    
                        }
                        else
                        echo "Invalid file format..";   
                }
                
            else
                echo "Please select image..!";
                
            exit;
        }
    } else {        
        $returnArr["Result"]="ERROR";
        $returnArr["Message"]="You do not have sufficient privileges to access this page";
    }
} else {
    $returnArr["Result"]="ERROR";
    $returnArr["Message"]="Database Error";
}

print(json_encode($returnArr));

       
?>