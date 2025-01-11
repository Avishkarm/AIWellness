<?php
	function getAllPatientCases($conn,$type,$patient_id,$doctor_id,$sort,$pageNo=1,$limit=3)
                      {
                       // echo $sort;
                        global $blanks;
                        $returnArr = array();
                        $end=$pageNo*$limit;
                      $start=$end-3;
                        $cnt='';
                        $strQuery='';
                       $cnt=getAllPatientCasesCount($conn,$patient_id,$doctor_id);
                        $strQuery .= sprintf("SELECT * FROM doctors_patient_cases WHERE ref_case_id=0 and patient_id='".$patient_id."' and doctor_id='".$doctor_id."'");
                        $strQuery .= sprintf("ORDER BY created_on  %s LIMIT %s,%s",$sort,$start,$limit);
                       $query=runQuery($strQuery,$conn);
                    //  echo  mysqli_num_rows($query);
                       if(noError($query)){
	                        $returnArr['errCode'][-1]=-1;
	                        $result=$query['dbResource'];
	                        $arr=array();
	                        while ($row = mysqli_fetch_assoc($result)) {
	                          $arr[]=$row;
	                        }
	                       // printArr($arr);
		              	
                        $returnArr['errMsg']=$arr;
                        $returnArr['countCases']=$cnt['errMsg'];

            // $returnArr['main_complaint_name']=
                      }else{
                        $returnArr['errMsg']=$query['errMsg'];
                        $returnArr['errCode'][1]=1;
                      }

        
                      return $returnArr;

                    }
                    function getAllPatientCasesSort($conn,$type,$patient_id,$doctor_id,$sort,$pageNo=1,$limit=3)
                      {
                       // echo $sort;
                        global $blanks;
                        $returnArr = array();
                        $end=$pageNo*$limit;
                      $start=$end-3;
                        $cnt='';
                        $strQuery='';
                       $cnt=getAllPatientCasesCount($conn,$patient_id,$doctor_id);
                        $strQuery .= sprintf("SELECT * FROM doctors_patient_cases WHERE ref_case_id=0 and patient_id='".$patient_id."' and doctor_id='".$doctor_id."'");
                        $strQuery .= sprintf("ORDER BY complaint_name  %s LIMIT %s,%s",$sort,$start,$limit);
                       $query=runQuery($strQuery,$conn);
                    //  echo  mysqli_num_rows($query);
                       if(noError($query)){
                          $returnArr['errCode'][-1]=-1;
                          $result=$query['dbResource'];
                          $arr=array();
                          while ($row = mysqli_fetch_assoc($result)) {
                            $arr[]=$row;
                          }
                         // printArr($arr);
                    
                        $returnArr['errMsg']=$arr;
                        $returnArr['countCases']=$cnt['errMsg'];

            // $returnArr['main_complaint_name']=
                      }else{
                        $returnArr['errMsg']=$query['errMsg'];
                        $returnArr['errCode'][1]=1;
                      }

        
                      return $returnArr;

                    }
                    function getAllPatientCasesSearch($conn,$type,$patient_id,$doctor_id,$search,$pageNo=1,$limit=3)
                      {
                       // echo $sort;
                        global $blanks;
                        $returnArr = array();
                        $end=$pageNo*$limit;
                      $start=$end-3;
                        $cnt='';
                        $strQuery='';
                       // $query .= " AND  CONCAT( f_name,  ' ', l_name ) LIKE '%".$search_params['fn']."%'";
                       $cnt=getAllPatientSearchCasesCount($conn,$patient_id,$doctor_id,$search);
                        /*$strQuery .= sprintf("SELECT * FROM doctors_patient_cases WHERE complaint_name like '%".$search."%' and patient_id='".$patient_id."' and doctor_id='".$doctor_id."'");
                         $strQuery .= sprintf("ORDER BY complaint_name  %s LIMIT %s,%s",$search,$start,$limit);*/
                      $strQuery= "SELECT * FROM doctors_patient_cases WHERE ref_case_id=0 and complaint_name like '%".$search."%' or primary_prescription like '%".$search."%'  and patient_id='".$patient_id."' and doctor_id=".$doctor_id." ORDER BY complaint_name  LIMIT ".$start.",".$limit;
                       $query=runQuery($strQuery,$conn);
                    //  echo  mysqli_num_rows($query);
                       if(noError($query)){
                          $returnArr['errCode'][-1]=-1;
                          $result=$query['dbResource'];
                          $arr=array();
                          while ($row = mysqli_fetch_assoc($result)) {
                            $arr[]=$row;
                          }
                         // printArr($arr);
                    
                        $returnArr['errMsg']=$arr;
                        $returnArr['countCases']=$cnt['errMsg'];

            // $returnArr['main_complaint_name']=
                      }else{
                        $returnArr['errMsg']=$query['errMsg'];
                        $returnArr['errCode'][1]=1;
                      }

        
                      return $returnArr;

                    }
                    function getAllPatientSearchCasesCount($conn,$patient_id,$doctor_id,$search)
                      { 

                        global $totalQuestions;
                        /*$query="SELECT COUNT(*) as countCases FROM doctors_patient_cases WHERE patient_id='".$patient_id."' and doctor_id='".$doctor_id."' and status!=2";*/
                      // $query= sprintf("SELECT COUNT(*) as countCases FROM doctors_patient_cases WHERE complaint_name like '%".$search."%'  and patient_id='".$patient_id."' and doctor_id='".$doctor_id."'");
                        $query=" SELECT COUNT(*) as countCases FROM doctors_patient_cases WHERE ref_case_id=0 and complaint_name like '%".$search."%' and patient_id='".$patient_id."' and doctor_id=".$doctor_id;
                        $result=runQuery($query,$conn); 
                        if(noError($result)){
                          $row=mysqli_fetch_assoc($result["dbResource"]);
                          $returnArr["errCode"][-1]=-1;
                          $returnArr["errMsg"]=$row["countCases"];

                        } else {
                          $returnArr["errCode"][1]=1;
                          $returnArr["errMsg"]="Error fetching questions data";
                        }

                        return $returnArr;
                      }



                    function getAllPatientCasesCount($conn,$patient_id,$doctor_id)
                      { 

                        global $totalQuestions;
                        /*$query="SELECT COUNT(*) as countCases FROM doctors_patient_cases WHERE patient_id='".$patient_id."' and doctor_id='".$doctor_id."' and status!=2";*/
                      $query= sprintf("SELECT COUNT(*) as countCases FROM doctors_patient_cases WHERE ref_case_id=0 and patient_id='".$patient_id."' and doctor_id='".$doctor_id."'");

                        $result=runQuery($query,$conn); 
                        if(noError($result)){
                          $row=mysqli_fetch_assoc($result["dbResource"]);
                          $returnArr["errCode"][-1]=-1;
                          $returnArr["errMsg"]=$row["countCases"];

                        } else {
                          $returnArr["errCode"][1]=1;
                          $returnArr["errMsg"]="Error fetching questions data";
                        }

                        return $returnArr;
                      }

                     function getPatientComplaintCount($conn,$complaint_name,$patient_id,$doctor_id)
                      { 

                        global $totalQuestions;
                        /*$query="SELECT COUNT(*) as countCases FROM doctors_patient_cases WHERE patient_id='".$patient_id."' and doctor_id='".$doctor_id."' and status!=2";*/
                       $query= sprintf("SELECT COUNT(*) as countCases FROM doctors_patient_cases WHERE ref_case_id=0 and patient_id='".$patient_id."' and doctor_id='".$doctor_id."' and complaint_name='".$complaint_name."'ORDER BY created_on  DESC ");

                        $result=runQuery($query,$conn); 
                        if(noError($result)){
                          $row=mysqli_fetch_assoc($result["dbResource"]);
                          $returnArr["errCode"][-1]=-1;
                          $returnArr["errMsg"]=$row["countCases"];

                        } else {
                          $returnArr["errCode"][1]=1;
                          $returnArr["errMsg"]="Error fetching questions data";
                        }

                        return $returnArr;
                      }


                      function getRefCases($ref_case_id,$conn)
                      {
                       // echo $sort;
                        global $blanks;
                        $returnArr = array();
                        
                        $strQuery='';
                        $strQuery .= sprintf("SELECT * FROM doctors_patient_cases WHERE ref_case_id=".$ref_case_id);
                       $query=runQuery($strQuery,$conn);
                    //  echo  mysqli_num_rows($query);
                       if(noError($query)){
                          $returnArr['errCode'][-1]=-1;
                          $result=$query['dbResource'];
                          $arr=array();
                          while ($row = mysqli_fetch_assoc($result)) {
                            $arr[]=$row;
                          }
                         // printArr($arr);
                    
                        $returnArr['errMsg']=$arr;

            // $returnArr['main_complaint_name']=
                      }else{
                        $returnArr['errMsg']=$query['errMsg'];
                        $returnArr['errCode'][1]=1;
                      }

        
                      return $returnArr;

                    }



                     
?>