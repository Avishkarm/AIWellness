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
  ?>
<style>
.footer{
  background-color: #1c3144;
  margin-left: 0 !important;
  margin-right: 0 !important;
  margin-top: 150px;
  padding: 30px;
}

.footer .title h1{
  color: #fff;
  font-size: 24px;
  font-weight: 600;
  letter-spacing: 2px;
}

.footer .text h1{
  color: #fff;
  font-size: 22px;
  line-height: 30px;
  margin-top: 5px;

}

.footer .text h2{
  color: #fff;
  font-size: 18px;
  line-height: 30px;
  margin-top: 5px;

}
.footer .text a{
  color: #fff;
  font-size: 18px;
  line-height: 30px;
  display: inherit;

}

.footer .text img{
  margin-top: 10px;
  min-height: 0px;
  max-width: 46px;
  margin-bottom: 20px;
}
.footer h3{
  font-size : 16px;
}
.footer h3 img{
  margin-left: 10px;
  min-height: 0px;
    max-width: 111px; 
}


@media(max-width: 768px){
  .footer{
    text-align: center;
  }

   .footer .text img{
    display: inline-block;
    margin-left: 10px;
  }
}
.text{
  line-height: 30px;
}
.title{
  padding-bottom: 20px;
}

</style>


  <!--  <img src="../../assets/images/download.png" class="img-responsive" style="cursor:pointer;top:80vh;position: fixed;
    right: 5%;" onclick="scrolldown()"> -->


<img src="<?php echo $pathprefix; ?>assets/images/download.png" class="img-responsive pull-right" style="cursor:pointer;position: fixed;cursor: pointer;top: 80vh;right: 10%;" onclick="scrolldown()">

<footer class="container"> 
	     <!-- Footer -->
                <div class="row footer">
                  
                  <div class="col-md-6">
                    <div class="title">
                <h1>About us</h1>                   
                    </div>
                    <div class="text">
                      <h1>
                          Homeopathy at its scientific best.
                      </h1>
                      <h2>
                          We are the world’s first Homeopathic healing portal that provides credible evaluation, diagnoses, education, information, prescriptions and treatment, using next generation technology and the classical principles of Dr.&nbsp;Hahnemann.
                      </h2>
                      <a href="#" style="margin-top:5px;">E: eHeilungexpert@gmail.com</a>

                    </div>
                  </div> 

                  <div class="col-md-6 col-md-push-2">
                    <div class="title">
                <h1>Our services</h1>                   
                    </div>
                    <div class="text">
                    <a href="<?php echo $views; ?>diseaseCompass/disease_compass.php">Disease Compass</a>
                    <a href="<?php echo $views; ?>2opinion">Case Checker</a>
                    <a href="<?php echo $views; ?>stress_calculator.php">Stress Calculator</a>
                    <a href="<?php echo $views; ?>KnowledgeCenter">Knowledge Center</a>
                    <?php if(!isset($_SESSION["user"])){ ?>
                    <a href="<?php echo $rootUrl; ?>/index.php?luser=doctor">Doctors Area</a>
                    <?php }else{ ?>                    
                    <a href="<?php echo $views;?>dashboard/doctorsDashboard.php" class="<?php if($activeHeader=="doctorsArea"){ echo "";}?>">Doctors Area</a>
                    <?php } ?>
                    <!--<a >Patients Area </a>-->
                    </div>
                  </div> 

                  <!-- <div class="col-md-3 col-md-push-4">
                    <div class="title">
                <h1>Follow us</h1>                    
                    </div>
                    <div class="text">
                    <a href="<?php echo $footerSocialLinks['fb']; ?>" target="_blank" ><img src="<?php echo $pathprefix;?>assets/images/fb.png" class="img-responsive" /></a>
                    <a href="<?php echo $footerSocialLinks['gplus']; ?>" target="_blank"><img src="<?php echo $pathprefix;?>assets/images/google.png" class="img-responsive" /></a>
                    <a href="<?php echo $footerSocialLinks['tweet']; ?>" target="_blank"><img src="<?php echo $pathprefix;?>assets/images/twit.png" class="img-responsive" /></a>
                    </div>
                  </div> -->
                     <div style="clear: both;"></div>
                  <h3 style="color: #fff;text-align: center;">All rights reserved by eheilung <img src="<?php echo $pathprefix;?>assets/images/secured.png"></h3>          
                </div>
                <!-- Footer End -->
	</footer>


<!-- INFO MODAL -->
<div id="infoModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      
    </div>

  </div>
</div>

	<!-- container -->
  <script type="text/javascript">
    $('.viewpro').click(function(){
        window.location.href='<?php echo $views;?>'+'profile/viewProfile.php';
    });

function scrolldown(){
  $("html, body").animate({ scrollTop: $(document).height() }, 3000);


}


$(document).on('keyup',function(evt) {
  if (evt.keyCode == 27) {
    $('.modal').modal('hide');
  }   
});


  </script>
   
