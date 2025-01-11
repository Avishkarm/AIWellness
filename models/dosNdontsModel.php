<?php 
function getdondont($conn){
      global $blanks;
      $returnArr = array();
      

      
      $query="SELECT * FROM `dos_n_donts`";
      $result=runQuery($query, $conn);
      if(noError($result))
      {
        $res=mysqli_fetch_assoc($result["dbResource"]);
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching cases data";
      }
      
      return $returnArr;
      
}
?>