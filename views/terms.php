<?php

session_start();

require_once("../utilities/config.php");
require_once("../utilities/dbutils.php");

	//database connection handling

$blogurl="http://192.168.1.103/hansinfo_eheilung/wordpress";
$conn = createDbConnection($servername, $username, $password, $dbname);

$returnArr=array();
if(noError($conn)){
	$conn = $conn["errMsg"];
} else {
	    //printArr("Database Error");
	exit;
}


$activeHeader = "";
$pathPrefix="../";


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include_once("metaInclude.php"); ?>
	<style type="text/css">
    

  .social-icon ul li a i {
    color: #666;
    font-size: 20px;
    text-align: center;
    background-color: bisque;
   
}
 .social-icon ul li {
   display: inline-block;
}

.social-icon ul li a {
   padding: 3px 9px;
}

		/*header{
			padding:7px 20px !important;
		}*/
	</style>
	<link rel="stylesheet" type="text/css" href="../assets/css/home.css?aghrd=r4564298">
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.min.css">
	<!-- header-->

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">



	<main class="container" style="min-height: 100%;">

     


		<?php  include_once("header.php"); ?>     
	
         


    <div class="row noleft-right" >
      <div class="col-md-12 col-sm-12 col-xs-12 managepatient" >
        <h2 style="text-align: center;margin-top: 0px;">Terms and Conditions</h2>
      </div>

      <div class="col-md-12 col-sm-12 col-xs-12 conditionsterms">
      <h4>
          
            The remedial suggestions made to the user / patient is made by / based on [●] (“Software”), software owned and operated by Khedekar Expert System Private Limited, a company incorporated under the laws of India (“Company”). The domain name <a href="<?php echo $rootUrl; ?> ">eHeilung.com</a> (“Website”) is also registered in the name of and owned by the Company. Use of the Software and access to the Website is offered to the user / accessor of the Website and/or the Software including the patients (hereinafter referred to as “User”), subject to acceptance of all the terms, conditions and notices contained herein including applicable policies which are incorporated herein by reference, along with any amendments / modifications made by the Company at its sole discretion and posted on the Website. By (i) using the Software and/or accessing the Website or any facility or service provided by the Software / Website in any way; or (ii) merely navigating through / browsing on the Website, the User agrees to have read, understood and agreed to be bound by the terms and conditions set out herein and the privacy policy available at the relevant link on the Website.
             
            <br><br>
            
                 By accessing the Software / Website, the User hereby agrees and acknowledges that (i) medicines or homeopathy are not perfect sciences and therefore there is no assurance that the patient shall be cured by following the remedial suggestions suggested by the Software; (ii) the Avogadro number concept doesn’t apply to homeopathy and (iii) homeopathy doesn’t have any adverse effects when taken in doses specified by the Software. However, if there are any adverse effects /side effects, the Company cannot be held liable for the same. Excessive intake of the homeopathy medicines in violation of the instructions specified by the Software can be harmful to the patient and the Company shall not be responsible for any harm / damage caused to the patient as a result of intake of excessive medicines.
             
            <br><br>
             
                 The remedial suggestion made by the Software is based on an algorithm developed considering the remedy generally prescribed by doctors in relation to the homoeopathic cures for illnesses / ailments, symptoms of which are similar to the symptoms that are provided by the User. Please note that the remedial suggestion made by the Software is based solely on the symptoms provided by the User and the remedial suggestion shall vary based on the symptoms fed on the Software / Website. The information or advice provided by the Software should be used merely as a guide rather than a definitive recommendation to adopt a specific course of action or treatment for curing the illness / ailment. Nothing transmitted to or from the Software and/or the Website constitutes the establishment of a doctor-patient relationship between the User and the Company / any professional providing information or advice through the Software / Website. The Software is not intended to diagnose a medical condition. 
              
            <br><br>
             
                If the Company feels that the User is in a difficult condition, which cannot be solved by using the algorithm and needs a physical consultation with a doctor, then the Company may refer such User to a doctor who the patient may approach for a physical consultation. The Company is in no way responsible for the actions of the doctor whom the patient approaches for a consultation and cannot be held liable if complications arise pursuant to the consultation with the doctor. 
              
            <br><br>
             
                The Software only seeks to suggest remedies for a specific list of ailments. If the problems being faced by the patients do not fall within the parameters of the algorithm, then the User should consult a doctor.  
              
            <br><br>
              
                The Company or the Software does not guarantee that the illness / ailment shall be cured if the remedial suggestion made by the Software is followed by the User in accordance with the instruction provided by the Software. The possibility of the illness / ailment aggravating or the condition of the User deteriorating even after following the remedy suggested by the Software cannot be ruled out.  The Company shall not be liable or responsible in any manner whatsoever if the patient is not cured even after following the remedy suggested by the Software or if his condition further deteriorates. 
              
            <br><br>
             
                The remedial suggestions made by the Software have not been invented or discovered by the Company but is based on remedy generally prescribed by homeopathy doctors for similar illnesses / ailments or based on the research / theories of certain doctors / experts / authors. The Company hereby informs that it does not certify the correctness / accuracy of remedy / medicines suggested nor does it certify / authenticate the research / theories of certain doctors / experts / authors. No claim shall be made by the user / patient against the Company, if the remedy suggested by the Software is incorrect or the research / theories of the said doctors / experts / authors is found to be wrong / misleading. 
              
           <br><br>
              
                The remedy suggested by the Software is guided by the symptoms fed on the Software. While care and precautions are taken to ensure that the remedy suggested relate to the illness / ailment, the possibility of the Software recommending a wrong remedy due to some errors / virus or other defects or otherwise, cannot be ruled out. The User hereby agrees and acknowledges that the Company shall not be held responsible and no claim shall be made against the Company for any wrong remedy / medicines suggested by the Software and the User hereby expressly relinquishes all his right, whether available under law or otherwise, to sue or make a claim against the Company for any loss or damage caused to User as a result of such wrong remedy suggested by the Software.
            
            <br><br>
             
                The Software is not a substitute for a doctor / physician nor is it a substitute for emergency medical care. It is advisable to avoid the remedy suggested by the Software and visit a doctor immediately in case of a medical emergency or if the condition of the patient does not improve or further deteriorates even after following the remedy suggested by the Application.  The remedial suggestion on the Software is not (nor is it intended to be) a substitute to physical consultation with a doctor or to hospital services. The Software offers medical advice on various homoeopathic remedies and it does not include a direct medical diagnosis, treatment or prescription.
              
            <br><br>
              
                The information on the Software and/or the Website (including but not limited to video consult, live chat, emails, phone support) is provided for general informational purposes only and should not be relied upon as a substitute for sound professional medical advice, evaluation or care from your physician or other qualified healthcare provider. The User shall never disregard medical advice or delay in seeking it because of something he/she has read on the Software / the Website (including but not limited to video consult, live chat, emails, phone support). No medications, diet supplements or treatments as may be described on the Software / Website shall be taken or begun without first consulting a physician or other healthcare provider.
             
            <br><br>
             
                The User also absolves professionals engaged by the Company, who offer services through the Software / Website from any responsibility or liability whatsoever, for any medical, legal or financial events or outcomes related to services attained by the User through the use of online video consultation. The User also understands and uses the Software accepting that it is being provided on an as is basis.
              
            <br><br>
              The Company connects Users to the nearest pharmacies through google maps. The Company does not in any manner whatsoever guarantee the quality of medicines that are purchased by the Users from such retail pharmacies nor does it take any responsibility for any wrong or delayed supply of medicines. The contract for sale of medicines is directly between the User and the retail pharmacy and the Company shall not be involved or connected in such sale.
              
            <br><br>
            The User understands and agrees that the Company makes no claim that the contents of the Software / Website, such as text, graphics, images and information obtained from service providers and any other material contained on the Software / Website is appropriate or correct or may be downloaded in a particular jurisdiction. Access to the Software / Website and the content thereon may not be legal by certain persons or in certain states or countries. If you access the Software / Website from outside India, you do so at your own risk and are responsible for compliance with the laws of your jurisdiction.
            
          <br><br>
            Doctors who are accessing the Software / Website need to take into account that the medical advice provided herein is purely indicative and is meant to be used as a guide to help the doctor prescribe medicines for the symptoms entered into the software. The final decision is to be taken by the doctor based on physical consultation and the Company is in no way liable for incorrect medicines being suggested to their patients by the doctor using the Software / Website as a tool.
          <br><br>
            The information provided to students using the Website / Software as a studying tool in the form of texts and videos are authored/created by third parties and the Company acts only as an aggregator for such information. The Company is not the author of such material and cannot be held liable if the information provided is not accurate or correct. 
          <br><br>
            The Company views protection of privacy of the User as a very important principle. The Company stores and processes User information including their sensitive personal / financial information collected (as defined under the Information Technology Act, 2000), if any, on computers that may be protected by physical as well as reasonable technological security measures and procedures in accordance with Information Technology Act 2000 and rules there under. The Company’s current Privacy Policy is available on the Website. If the User objects to his information being transferred or used in this way, please do not use the Software / Website. 
          <br><br>
            The User hereby agrees and acknowledges that by accessing the Software / Website and by providing symptoms of the illness / ailment suffered by the User on the Software / Website, the User expressly consents to the Company collecting, receiving and storing his sensitive personal data and information. Further, the information provided by Users would be analysed, and/or handled by the Company and/or transferred by the Company to other third parties for further analysis so as to improve the efficacy of the Software and/or the Website and to keep a record and the User hereby agrees and acknowledges that such third parties may further share, disclose or transfer such information. The User agrees to let the Company store confidential medical information for such purposes as deemed fit by the Company including for the betterment of the Software/algorithm, which will enable it to give better medical advice. 
          <br><br>
            The Company provides free electronic health records to Users for their perusal. These records that are stored by the Company and are generated pursuant to the inputs of the User and shall remain with the Company.
          <br><br>
            The Company reserves the right to terminate access of people to the Software and/or the Website without any warning, if the Company is of the opinion that the Software and/or Website is being misused by such persons in anyway. The Company also reserves the right to unilaterally increase or decrease the price of the service being provided through the Software / Website without taking the consent of any User or any third party.
          <br><br>
            The Company shall not be required to notify any person of any changes made to the terms mentioned aforesaid. The revised terms shall be made available on the Website. The use by the user / patient of the Software / Website and the services is subject to the most current version of the terms made available on the Website the time of such use. The user / patient is requested to regularly visit the Website to view the most current terms. It shall be the responsibility of the user to check the terms periodically for changes. The Company may require the user to provide its consent to the updated terms in a specified manner prior to any further use of the Software / Website and the services provided on the Software / Website. If no such separate consent is sought, the continued use of the Software / Website by the user, following changes to the terms, will constitute express acceptance by the user of those changes.
          
          <br><br>
            These terms and conditions and disclaimers shall be governed by the laws of India and the courts of Mumbai, India shall have exclusive jurisdiction in relation to the interpretation of and/or any dispute arising out of terms and conditions and disclaimers.
          

      </h4>
      </div>

    </div>

</main>
<?php  include('modals.php'); ?> 
<?php  include('footer.php'); ?>


</body>
</html>
