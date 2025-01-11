<?php
  
  function getDailyQuote($conn){
    global $blanks;
      $returnArr = array();

      $query="SELECT * FROM quotes ORDER BY id DESC LIMIT 0,1";
    
      $result=runQuery($query, $conn);
      if(noError($result)){
        //$res=array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res=$row;
        }
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching case sheet data";
      }

      return $returnArr;
  }
  function getTemp($user_id,$conn){
    global $blanks;
      $returnArr = array();

      $query="SELECT * from temperature WHERE user_id=".$user_id;
    
      $result=runQuery($query, $conn);
      if(noError($result)){
        //$res=array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res=$row;
        }
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching case sheet data";
      }

      return $returnArr;
  }

	function getDoctorsAllcomplaints($doctor_id,$conn){
	  global $blanks;
      $returnArr = array();

      $query="SELECT *,count(complaint_name) as count FROM doctors_patient_cases where doctor_id=".$doctor_id." and complaint_name <> '' GROUP BY complaint_name ORDER BY count(complaint_name) DESC limit 0,4";
      
      $result=runQuery($query, $conn);
      if(noError($result)){
        //$res=array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res[]=$row;
        }
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching case sheet data";
      }

      return $returnArr;
	}
	function getDoctorsAllCases($doctor_id,$conn){
	  global $blanks;
      $returnArr = array();

     $query="SELECT created_on, YEAR(created_on) AS y, MONTH(created_on) AS m, COUNT(DISTINCT id) as count FROM doctors_patient_cases where doctor_id=".$doctor_id." GROUP BY y, m";
      
      $result=runQuery($query, $conn);
      if(noError($result)){
        //$res=array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res[]=$row;
        }
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error doctor cases data";
      }

      return $returnArr;
	}
    function getDoctorUserCaseMonthlyReport($conn, $user,$year,$month,$date){
    $returnArr = array();
    $res = array();
    $week= array("1"=>0,"2"=>0,"3"=>0,"4"=>0,"5"=>0);
    //$week= array("7"=>0,"14"=>0,"28"=>0,"31"=>0);
   /* $query = sprintf("SELECT * FROM `doctors_patient_cases` WHERE doctor_id = '%s' and created_on >= ((NOW() - INTERVAL 1 MONTH)) ORDER BY created_on ASC", $user);*/
    /*$query = sprintf("SELECT * FROM `doctors_patient_cases` WHERE doctor_id = '%s' and created_on >= ((NOW() - INTERVAL 1 MONTH)) ORDER BY created_on ASC", $user);*/
    $query = sprintf("SELECT * FROM `doctors_patient_cases` WHERE doctor_id = '%s' and YEAR(created_on)=".$year." and MONTH(created_on)=".$month." ORDER BY created_on ASC", $user);

    $query = runQuery($query, $conn);

    if(noError($query)){
      while($row=mysqli_fetch_assoc($query["dbResource"])){
        $res[] = $row;
      }
      if(!empty($res)){

        foreach ($res as $key => $value) {
          /*WEEKOFMONTH($value['created_on']);
          print_r(WEEKOFMONTH($value['created_on']));*/
          $dt = dateDifference($date , $value['created_on']);
           if($dt){
            if($dt < 7 && $dt>0){
              $week["1"] +=1; 
            }else if($dt < 14){
              $week["2"] +=1; 
            }else if($dt<21){
              $week["3"] +=1;
            }else if($dt < 28){
              $week["4"] +=1; 
            }else if($dt >= 28 && $dt<31){
              $week["5"] +=1; 
            }
          }
         /* if($dt){
            if($dt < 7 && $dt>0){
              $week["31"] +=1; 
            }else if($dt < 14){
              $week["28"] +=1; 
            }else if($dt<28){
              $week["14"] +=1;
            }else if($dt < 31){
              $week["7"] +=1; 
            }
          }*/
        }
      }
      $returnArr['errCode'][-1] = -1;
      //$returnArr['res'] = $res;
      $returnArr['errMsg'] = $week;     
    }else{
      $returnArr['errCode'][2] = 2;
      $returnArr['errMsg'] = $query['errMsg'];
    }

    return $returnArr;
  }
   function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
  {
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($differenceFormat);

  }
?>