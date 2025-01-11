<?php
	  function selectSubscriber($conn,$email)
  {


    $returnArr = array();

    $query="select email from subscriber WHERE email='".$email."'";

    


    $result = runQuery($query, $conn);

    $row=mysqli_num_rows($result["dbResource"]);


    if(noError($result)){


      $returnArr["errCode"][-1]=-1;

      $returnArr["errMsg"]=$row;

    } else {

      $returnArr["errCode"][5]=5;

      $returnArr["errMsg"]=$result["errMsg"];

    }

    
    return $row;
  }


  function addSubscriber($conn,$email,$name,$mobile)
  {


    $query = "INSERT INTO `subscriber`(`email`,`name`,`mobile`) VALUES ('".$email."','".$name."','".$mobile."')";
      //$query="INSERT INTO `ip_access`(`id`, `ip_address`) VALUES ('','192.168.0.1')";

    $result = runQuery($query, $conn);



    if(noError($result)){


      $returnArr["errCode"]=-1;
      $returnArr["errMsg"]="Insertion successful";
    }else{
      $returnArr["errCode"] = 8;
      $returnArr["errMsg"]=" Insertion failed".mysqli_error();
    }
    return $returnArr;

  }
?>