<?php

  session_start();

  require_once("../../controllers/config.php");
  require_once("../../controllers/utilities.php");
  require_once("../../controllers/dbutils.php");
  require_once("../../controllers/accesscontrol.php");
  require_once("../../controllers/authentication.php");

$conn = createDbConnection($servername, $username, $password, $dbname); //$dbname
$returnArr=array();

if(noError($conn)){
  $conn = $conn["errMsg"];
} else {
  printArr("Database Error");
  exit;
}
$user = $_SESSION["user"];


if(isset($_GET['pageNo'])){
	$pageNo=$_GET['pageNo'];
}else{
	$pageNo=1;
}

/*$getCases = get2ndopinionCasesHistory($conn,$user);
if(noError($getCases)){
	$getCases = $getCases["errMsg"];


} else {

	//printArr("Error fetching case detailss");
	$getCases="No Data";
}*/




$getCases=get2ndopinionCasesHistory($conn,$user,$pageNo,$limit=5);

if(noError($getCases)){
	$totalItems=$getCases['countCases'];
	$getCases = $getCases["errMsg"];
	$totalPageNo=ceil($totalItems/5);
//ceil($getCases['countCases']/5);

} else {

	//printArr("Error fetching case detailss");
	$getCases="No Data";
}

?>
<div class="row-fluid"  style="">
		<div class="row" id="clientTab">

		  <div class="col-md-12">
		  		<h4 class="btn-success" style="background-color:#0be1a5;padding:5px;padding-left: 3%;">Your History</h4>
				<table class="table table-responsive" >
				  <tbody>
				    <tr>
				      <th class="col-md-1">Case ID</th>
				      <th class="col-md-2">Created On</th>
				      <th class="col-md-9">Conclusion</th>
				    </tr>
				    <?php 
				    	foreach ($getCases as $key => $value) {
				    ?>	
					    <tr>
					      <td data-th="caseID"><?php echo $value['scaseId'];?></td>
					      <td data-th="createdOn"><?php echo date('M j Y g:i A', strtotime($value['created_on'])) ?></td>									      
					      <td data-th="Conclusion"> <?php echo $value['conclusion']; ?></td>
					    </tr>
				    <?php
						}
				    ?>
				  </tbody>
				  <tfoot>
				  		<tr class="" >
					  			<td style="text-align: center;" colspan="6">
					  				<a href="index.php?pageNo=<?php echo empty($pageNo-1)?$pageNo:$pageNo-1; ?>" style="color:#0be1a5;<?php if($pageNo==1){?>display:none;<?php }?>">Prev</a>
					  				<a href="index.php?pageNo=<?php 
					  					if($pageNo==$totalPageNo){
					  						echo $pageNo;
					  					}else{
					  						echo $pageNo+1; 
					  					}
					  					?>" style="color:#0be1a5;<?php if($pageNo==$totalPageNo){?>display:none;<?php }?> ">Next</a>
					  			</td>
					  		</tr>
				  </tfoot>
				</table>
				<div class="stopAccess">
					<div class="progressBar"></div>
				</div>
		  </div>
		</div>
	</div>
