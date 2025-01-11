<?php 
session_start();
require_once("../../utilities/config.php");
require_once("../../utilities/dbutils.php");
require_once("../../utilities/authentication.php");
$conn = createDbConnection($servername, $username, $password, $dbname);
if(noError($conn)){
	$conn = $conn["errMsg"];
} else {
	printArr("Oops! There seems to be something wrong with our servers. Please try again later or contact us if the problem persists.");	
	exit;
}
if (!empty($_REQUEST['data'])) {
       $dt=$_REQUEST['data'];
      
      // printArr($dt);
       if($dt['info']=='AnswerOptionRemove'){
	 $quesId=$dt['Qid'];
        	$ansId=$dt['Aid'];
        	print_r($ques);
        	$query = "DELETE FROM kes_answer_options WHERE qid='".$quesId."' and aid='".$ansId."'";
		$rs1=mysqli_query($query,$conn);
		
		$returnVal['errMsg']['Aid']=$ansId;
			$returnVal['errMsg']['Qid']=0;
			//$returnVal['errMsg']['Qidorig']=$quesId;
			echo json_encode($returnVal);
			
}
        if($dt['info']=='AnswerOption'){
        	$quesId=$dt['Qid'];
        	$ansId=$dt['Aid'];
        	$about_query2="UPDATE kes_questions set has_puzzle=0 where question_id='".$quesId."'";
        	$rs2=mysqli_query($about_query2,$conn);
        	 $about_query1="SELECT max(aid) from kes_answer_options where qid='".$quesId."'";
     		
            $rs1=mysqli_query($about_query1,$conn);
            while($row = mysqli_fetch_assoc($rs1)){
           $temp=($row['max(aid)']);
          $temp1=$temp+1;
           $about_query="INSERT INTO kes_answer_options (qid,aid,ans_label,has_fuq,fuq_id,answerRemedies) VALUES ('".$quesId."', '".$temp1."', '', '0', NULL, NULL)";
			$rs=mysqli_query($about_query,$conn);
			 	  
       } 
        	
        $returnVal['errMsg']['Aid']=$ansId;
			$returnVal['errMsg']['Qid']=0;
			$returnVal['errMsg']['Qidorig']=$quesId;
			
			echo json_encode($returnVal);
			exit; 	      //code
        }
}
		
	/*print("<script>");
		print("var t = setTimeout(\"window.location='".$redirectURL."';\", 3000);");
	print("</script>");
	
	print("<a href=".$redirectURL.">Click here if you are not redirected automatically by your browser within 3 seconds</a>");
	exit;*/


?>