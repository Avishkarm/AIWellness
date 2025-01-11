<?php 
function getAllCompassData($conn){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM disease_compass_conclusion ";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["D_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching rubrics data";
    }

    return $returnArr;
  }
    function editCompass($newcompassName,$newcompassurl,$newcompassdescription,$compassId,$conn){
    global $blanks;
    $returnArr = array();

    $updateQuery = "UPDATE disease_compass_conclusion SET title='".$newcompassName."',video_url='".$newcompassurl."',description='".$newcompassdescription."' WHERE D_id='".$compassId."'";
    $result = runQuery($updateQuery, $conn);
    if(noError($result)){
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]="Rubric succesfully updated";
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Rubric update failed";
    }

    return $returnArr;
  }
?>