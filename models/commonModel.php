<?php

  if($activeHeader=="2opinion" || $activeHeader=='knowledge_center' || $activeHeader=="doctorsArea")
  {
    $pathprefix="../../";
    $views =  "../";
    $controllers = "../../controllers/";
  }else if($activeHeader == "index.php"){
    $pathprefix="";
    $views =  "views/";
    $controllers = "controllers/";
  }else {
    $pathprefix="../";
    $views = "";
    $controllers = "../controllers/";
  } 
 require_once($pathprefix."utilities/config.php");  
 require_once($pathprefix."utilities/dbutils.php");

$type=$_POST['type'];

    if($type=='insert')
    {

      $conclusion=$_POST['conclusion'];
      $userName="anonymous";
      $check=saveSecondOpinionUserCase($conn,$userName,$conclusion);
      echo json_encode($check);


    }

    function saveSecondOpinionUserCase($conn,$userName,$conclusion)
    {

     $query = "INSERT INTO `secondopinion`(`name`,`conclusion`,`created_on`,`updated_on`) VALUES ('".$userName."','".$conclusion."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";
      //$query="INSERT INTO `ip_access`(`id`, `ip_address`) VALUES ('','192.168.0.1')";
     
     $result = runQuery($query, $conn);

     if(noError($result)){


      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=mysqli_insert_id();
    }else{
      $returnArr["errCode"][8]= 8;
      $returnArr["errMsg1"]=" Insertion failed".mysqli_error();
    }
    return $returnArr;
  }


  function getSystemComplaint($conn, $priority_id=""){
    $returnArr = array();
    global $blanks; 
    $query="SELECT * FROM complaint_info  WHERE status=1 group by Diagnostic_term ORDER BY `id`";
    $result = runQuery($query, $conn);
        //printArr($result);
      if(noError($result)){
        $res = array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res[$row["id"]]=$row;
        }
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      }else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching complaints data";
      }
      //printArr($returnArr);
    return $returnArr;
  }

  function getSystemCommonNames($conn, $priority_id=""){
    $returnArr = array();
    global $blanks; 
    $query="SELECT * FROM complaint_info  WHERE status=1  ORDER BY `id`";
    $result = runQuery($query, $conn);
        //printArr($result);
      if(noError($result)){
        $res = array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res[$row["id"]]=$row;
        }
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      }else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching complaints data";
      }
      //printArr($returnArr);
    return $returnArr;
  }

  function getAllTreatment($conn,$treatmentStatus=1){
    global $blanks;
    $returnArr = array();

    $query = "SELECT * FROM treatment_type WHERE type_status='".$treatmentStatus."'";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["type_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching rubrics data";
    }

    return $returnArr;
  }

  function getAllTreatmentSubtype($conn,$type_id,$treatmentStatus=1){
    global $blanks;
    $returnArr = array();

     $query = "SELECT * FROM treatment_subtype WHERE type_id='".$type_id."' and subtype_status='".$treatmentStatus."'";
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){
        $res[$row["subtype_id"]]=$row;
      }
      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching treatment subtype data";
    }

    //printArr($returnArr);
    return $returnArr;
  }

     function getDiseaseConclusion($conn,$title)
    {
      global $totalQuestions;
      $query="SELECT * FROM disease_compass_conclusion WHERE title='".$title."'";
      $result=runQuery($query,$conn); 
      if(noError($result)){
        $res = array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res=$row;
        }
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      }else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching complaints data";
      }
      return $returnArr;
    }

    function checkComplaintPriority($complaint,$conn) {

    foreach($complaint as $key=>$value){
      $complaint1[$key]=str_replace("_"," ",$value);

    }

    $complaint2= implode("','", $complaint1);
  /*echo $complaint1;
  $complaint2=str_replace("_", ",", $complaint1);*/
        // echo($complaint2);
  $returnArr = array();
  global $blanks; 
    $query = "SELECT max(priority_id) FROM complaint_info WHERE Diagnostic_term IN ('".$complaint2."') or Common_name IN ('".$complaint2."') "; //select max(priorityid)
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      while($row=mysqli_fetch_assoc($result["dbResource"])){

        $res[$row['id']]=$row;
      }

      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching complaints data";
    }

    return $returnArr;
  }
  function getMainComplaintName($conn,$key)
  {
   global $blanks;
   $returnArr = array();
   $query="SELECT Diagnostic_term,Common_name FROM complaint_info where priority_id=".$key;
   $result1=runQuery($query, $conn);

   if(noError($result1)){
    $res = array();
      while($row=mysqli_fetch_assoc($result1["dbResource"])){

        $res[]=$row;
      }/*
    $res=mysqli_fetch_assoc($result1["dbResource"]);*/
    $returnArr["errCode"][-1]=-1;
    $returnArr["errMsg"]=$res;

  }else{
    $returnArr["errCode"][1]=1;
    $returnArr["errMsg"]="Error fetching question Data";
  }
  //printArr($res);
  return $res;
}

function get2opinioncomplaint($complaint,$prioid,$conn) {

    foreach($complaint as $key=>$value){
      $complaint1[$key]=str_replace("_"," ",$value);

    }

    $complaint2= implode("','", $complaint1);
  /*echo $complaint1;
  $complaint2=str_replace("_", ",", $complaint1);*/
        // echo($complaint2);
  $returnArr = array();
  global $blanks; 
    $query = "SELECT * FROM complaint_info WHERE Diagnostic_term IN ('".$complaint2."') or Common_name IN ('".$complaint2."') "; //select max(priorityid)
    $result = runQuery($query, $conn);

    if(noError($result)){
      $res = array();
      $ndx = 0;
      while($row=mysqli_fetch_assoc($result["dbResource"])){

        if (($ndx == 0) || ($row['priority_id'] == $prioid)) {
            $res = $row;
        }
        $ndx++;
      }

      $returnArr["errCode"][-1]=-1;
      $returnArr["errMsg"]=$res;
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching complaints data";
    }

    return $returnArr;
  }




  function _get2opinioncomplaint($conn, $complaintsId) {

    $returnArr = array();
    global $blanks; 
    $query="SELECT * FROM complaint_info where priority_id=".$complaintsId;
     /* $query = "SELECT * FROM systems_priority";
        if(!in_array($complaint_id, $blanks)){
          $query .= " WHERE priority_id='".$priority_id."'";
        }*/
        $result = runQuery($query, $conn);

        if(noError($result)){
          
          while($row=mysqli_fetch_assoc($result["dbResource"])){
           $res=$row;
         }
         $returnArr["errCode"][-1]=-1;
         $returnArr["errMsg"]=$res;
       } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching complaints data";
      }
      return $returnArr;

    }

       function get2opinionMiasm($conn, $name) {

    $returnArr = array();
    global $blanks; 
    $query="SELECT * FROM miasm where miasm='".$name."'";
     /* $query = "SELECT * FROM systems_priority";
        if(!in_array($complaint_id, $blanks)){
          $query .= " WHERE priority_id='".$priority_id."'";
        }*/
        $result = runQuery($query, $conn);

        if(noError($result)){
          
          while($row=mysqli_fetch_assoc($result["dbResource"])){
           $res=$row;
         }
         $returnArr["errCode"][-1]=-1;
         $returnArr["errMsg"]=$res;
       } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching complaints data";
      }
      return $returnArr;

    }
    function get2opinionEmbryo($conn, $name) {

    $returnArr = array();
    global $blanks; 
    $query="SELECT * FROM embryology where embryology='".$name."'";
     /* $query = "SELECT * FROM systems_priority";
        if(!in_array($complaint_id, $blanks)){
          $query .= " WHERE priority_id='".$priority_id."'";
        }*/
        $result = runQuery($query, $conn);

        if(noError($result)){
          
          while($row=mysqli_fetch_assoc($result["dbResource"])){
           $res=$row;
         }
         $returnArr["errCode"][-1]=-1;
         $returnArr["errMsg"]=$res;
       } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching complaints data";
      }
      return $returnArr;

    }
    function get2opinionSystem($conn, $name) {

    $returnArr = array();
    global $blanks; 
 $query="SELECT * FROM systems where system_name='".$name."'";
     /* $query = "SELECT * FROM systems_priority";
        if(!in_array($complaint_id, $blanks)){
          $query .= " WHERE priority_id='".$priority_id."'";
        }*/
        $result = runQuery($query, $conn);

        if(noError($result)){
          
          while($row=mysqli_fetch_assoc($result["dbResource"])){
           $res=$row;
         }
         $returnArr["errCode"][-1]=-1;
         $returnArr["errMsg"]=$res;
       } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching complaints data";
      }
     // printArr($result);
      return $returnArr;

    }
    function get2opinionOrgan($conn, $name) {

    $returnArr = array();
    global $blanks; 
   $query="SELECT * FROM organ where organ='".$name."'";
     /* $query = "SELECT * FROM systems_priority";
        if(!in_array($complaint_id, $blanks)){
          $query .= " WHERE priority_id='".$priority_id."'";
        }*/
        $result = runQuery($query, $conn);

        if(noError($result)){
          
          while($row=mysqli_fetch_assoc($result["dbResource"])){
           $res=$row;
         }
         $returnArr["errCode"][-1]=-1;
         $returnArr["errMsg"]=$res;
       } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching complaints data";
      }
      return $returnArr;

    }
    function get2opinionSuborgan($conn, $name) {

    $returnArr = array();
    global $blanks; 
   $query="SELECT * FROM suborgan where suborgan='".$name."'";
     /* $query = "SELECT * FROM systems_priority";
        if(!in_array($complaint_id, $blanks)){
          $query .= " WHERE priority_id='".$priority_id."'";
        }*/
        $result = runQuery($query, $conn);

        if(noError($result)){
          
          while($row=mysqli_fetch_assoc($result["dbResource"])){
           $res=$row;
         }
         $returnArr["errCode"][-1]=-1;
         $returnArr["errMsg"]=$res;
       } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching complaints data";
      }
      return $returnArr;

    }

    //conclusion of 2nd opinion general,particular,and elimination section
  function getsecondOpinionGeneralResult($fg_score1,$fg_score2,$fg_score3,$fg_score4,$fg_score5,$fg_score6,$fg_score7,$fg_score8)
  {
    $score=0;
    $g_total=8;

    if($fg_score1 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score1 == 'nochange')
    {
      $g_total=$g_total-1;
    }
    if($fg_score2 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score2 == 'nochange')
    {
      $g_total=$g_total-1;
    }
    if($fg_score3 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score3 == 'nochange')
    {
      $g_total=$g_total-1;
    }
    if($fg_score4 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score4 == 'nochange')
    {
      $g_total=$g_total-1;
    }
    if($fg_score5 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score5 == 'nochange')
    {
      $g_total=$g_total-1;
    }
    if($fg_score6 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score6 == 'nochange')
    {
      $g_total=$g_total-1;
    }
    if($fg_score7 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score7 == 'nochange')
    {
      $g_total=$g_total-1;
    }
    if($fg_score8 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score8 == 'nochange')
    {
      $g_total=$g_total-1;
    }
      if(($score/$g_total*100)>=50)
      {
        return 'Good';
      }
      else
      {
        return 'Bad';
      }
    
  }

  function getsecondOpinionParticularResult($fg_score1,$fg_score2,$fg_score3,$fg_score4,$fg_score5,$fg_score6)
  {
    $score=0;
    $g_total=6;

    if($fg_score1 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score1 == 'nochange')
    {
      $g_total=$g_total-1;
    }
    if($fg_score2 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score2 == 'nochange')
    {
      $g_total=$g_total-1;
    }
    if($fg_score3 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score3 == 'nochange')
    {
      $g_total=$g_total-1;
    }
    if($fg_score4 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score4 == 'nochange')
    {
      $g_total=$g_total-1;
    }
    if($fg_score5 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score5 == 'nochange')
    {
      $g_total=$g_total-1;
    }
    if($fg_score6 == 'good')
    {
      $score=$score+1;
    }
    else if($fg_score6 == 'nochange')
    {
      $g_total=$g_total-1;
    }
    
      if(($score/$g_total*100)>=50)
      {
        return 'Good';
      }
      else
      {
        return 'Bad';
      }
    
  }

function getObservation($fg,$fp,$fe,$fs,$sg,$sp,$se,$ss,$conn) {
    
    global $blanks;
    $returnArr = array();
    
   $query="SELECT * FROM 2opinion_observation  WHERE fh_g='".$fg."'AND fh_p='".$fp."' AND fh_e='".$fe."' AND fh_s='".$fs."' AND sh_g='".$sg."' AND sh_p='".$sp."' AND sh_e='".$se."' AND sh_s='".$ss."'";
    $result=runQuery($query, $conn);
    if(noError($result)){
      $res=array();
      if(mysqli_num_rows($result["dbResource"])>0){
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res=$row;
        }

        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]=$res;
      }
      else
      {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="data not found";
      }
    } else {
      $returnArr["errCode"][1]=1;
      $returnArr["errMsg"]="Error fetching case data";
    }
    $returnArr["query"]=$query;
    return $returnArr;

  }

     function getQuestions2opinion($conn)
    {
      $returnArr = array();
      global $blanks; 
     $query="SELECT * FROM 2opinion_questions where que_status=1";
       /* $query = "SELECT * FROM systems_priority";
          if(!in_array($complaint_id, $blanks)){
            $query .= " WHERE priority_id='".$priority_id."'";
          }*/
          $result = runQuery($query, $conn);

          if(noError($result)){
            
            while($row=mysqli_fetch_assoc($result["dbResource"])){
             $res[]=$row;
           }
           $returnArr["errCode"][-1]=-1;
           $returnArr["errMsg"]=$res;
         } else {
          $returnArr["errCode"][1]=1;
          $returnArr["errMsg"]="Error fetching complaints data";
        }
        //printArr($returnArr);
        return $returnArr;      

    }
    function get2ndOpinionQuestionsConclusion($conn,$quest_id)
    {
      $returnArr = array();
      global $blanks; 
     $query="SELECT * FROM 2opinion_questions where quest_id=".$quest_id;
       /* $query = "SELECT * FROM systems_priority";
          if(!in_array($complaint_id, $blanks)){
            $query .= " WHERE priority_id='".$priority_id."'";
          }*/
          $result = runQuery($query, $conn);

          if(noError($result)){
            
            while($row=mysqli_fetch_assoc($result["dbResource"])){
             $res[]=$row;
           }
           $returnArr["errCode"][-1]=-1;
           $returnArr["errMsg"]=$res;
         } else {
          $returnArr["errCode"][1]=1;
          $returnArr["errMsg"]="Error fetching complaints data";
        }
        //printArr($returnArr);
        return $returnArr;
    }





   
?>