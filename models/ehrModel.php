<?php 
	function getPatientsAllComplaints($patient_id,$doctor_id,$conn) {
      global $blanks;
      $returnArr = array();

      $query="SELECT * FROM doctors_patient_cases where patient_id=".$patient_id." and doctor_id=".$doctor_id." and status=1 and primary_prescription!=''  order by id desc" ;
      
      $result=runQuery($query, $conn);
      if(noError($result)){
        $res=array();
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

    function getAllFolloups($case_id,$conn) {
      global $blanks;
      $returnArr = array();

      $query="SELECT * FROM follow_up where case_id=".$case_id." order by id desc" ;
      
      $result=runQuery($query, $conn);
      if(noError($result)){
        $res=array();
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
    function getComplaintDetails($complaint,$key,$conn) {
      global $blanks;
      $returnArr = array();

      $query="SELECT * FROM complaint_info where Diagnostic_term='".$complaint."' or Common_name ='".$complaint."'" ;
      
      $result=runQuery($query, $conn);
      if(noError($result)){
        $res=array();
        while($row=mysqli_fetch_assoc($result["dbResource"])){
          $res=$row;          
        }
        $miasm=$res['miasm'];
        $embryologcial=$res['embryologcial'];
        $system=$res['system'];
        $organ=$res['organ'];
        $subOrgan=$res['subOrgan'];
        $res1['m_id']=get2opinionMiasm($conn, $miasm);
        $res1['e_id']=get2opinionEmbryo($conn, $embryologcial);
        $res1['system_id']=get2opinionSystem($conn, $system);
        $res1['o_id']=get2opinionOrgan($conn, $organ);
        $res1['s_id']=get2opinionSuborgan($conn, $subOrgan);
        /* $res1[$key]['m_id']=get2opinionMiasm($conn, $miasm);
        $res1[$key]['e_id']=get2opinionEmbryo($conn, $embryologcial);
        $res1[$key]['system_id']=get2opinionSystem($conn, $system);
        $res1[$key]['o_id']=get2opinionOrgan($conn, $organ);
        $res1[$key]['s_id']=get2opinionSuborgan($conn, $subOrgan);*/
        //printArr($res1);
        $returnArr["errCode"][-1]=-1;
        $returnArr["errMsg"]= $res1;
      } else {
        $returnArr["errCode"][1]=1;
        $returnArr["errMsg"]="Error fetching case sheet data";
      }

      return $res1;
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
      return $res['m_id'];

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
      return $res['e_id'];

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
      return $res['system_id'];

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
      return $res['o_id'];

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
      return $res['s_id'];

    }
?>