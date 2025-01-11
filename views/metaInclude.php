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

<title><?php echo isset($title) ? $title : 'eHeilung'; ?></title>
<!-- <meta charset="utf-8"> -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?php echo $pathprefix;?>assets/images/logo.png" type="image/gif" sizes="16x16">
<link rel="stylesheet" type="text/css" href="<?php echo $pathprefix;?>assets/css/common.css?1=1">

<script src="<?php echo $pathprefix;?>assets/js/jQuery.min.js"></script>
<link rel="stylesheet" href="<?php echo $pathprefix;?>assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $pathprefix;?>assets/css/chosen.css">
<link rel="stylesheet" type="text/css" href="<?php echo $pathprefix;?>assets/css/chosen.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $pathprefix;?>assets/css/new.css?aer=1561">
<link href="<?php echo $pathprefix;?>assets/css/mui.min.css" rel="stylesheet" type="text/css" />

   <!-- <script src="//cdn.muicss.com/mui-0.9.15/js/mui.min.js"></script> -->
      <script src="<?php echo $pathprefix;?>assets/js/mui.min.js"></script>

<!-- <script type = "text/javascript" src= "https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<script type = "text/javascript" src= "https://cdnjs.cloudflare.com/ajax/libs/webshim/1.16.0/dev/polyfiller.js"></script>
 -->
<script type = "text/javascript" src= "<?php echo $pathprefix;?>assets/js/modernizr.min.js"></script>
<script type = "text/javascript" src= "<?php echo $pathprefix;?>assets/js/polyfiller.js"></script>


<script type="text/javascript">
	// Polyfill es5 canvas json-storage ajax geolocation form  unsupported features

	var arrJS = [
					{'url':"<?php echo $pathprefix;?>assets/js/bootstrap.min.js"},
					{'url':"<?php echo $pathprefix;?>assets/js/jstz.min.js"},
					{'url':"<?php echo $pathprefix;?>assets/js/header.js"},
					{'url':"<?php echo $pathprefix;?>assets/js/chosen.jquery.js"},
					{'url':"<?php echo $pathprefix;?>assets/js/validator.js"},
					{'url':"<?php echo $pathprefix;?>assets/js/chosen.jquery.min.js"},
			];
	var cntArrJS = 0;
	document.addEventListener("DOMContentLoaded", function(event) {	
			var url = arrJS[cntArrJS]['url'];
			loadJS(url, document.body);
		
			
	});

	function loadJS(url, loc, callback){
	    var scriptTag = document.createElement('script');
	    scriptTag.src = url;

	    
	    	scriptTag.onload = function(){
	    		if(cntArrJS < arrJS.length-1){
		    		cntArrJS++;
		    		if(callback !==null && callback !== undefined && typeof callback === "function"){
		    			callback();
		    		}
		    		var url = arrJS[cntArrJS]['url'];
		    		if(arrJS[cntArrJS].hasOwnProperty('callback')){
		    			var cb = arrJS[cntArrJS]['callback'];	
		    		}else{
		    			cb = null;
		    		}
		    		loadJS(url, document.body, cb);
		    	}
	    	};
	    	
	    loc.appendChild(scriptTag);
	}


   	
</script>


<link rel="stylesheet" type="text/css" href="<?php echo $pathprefix;?>assets/css/bootstrap-slider.css">
<link rel="stylesheet" href="<?php echo $pathprefix;?>assets/css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="<?php echo $pathprefix;?>assets/css/bootstrap-slider.min.css">
<link rel="stylesheet" href="<?php echo $pathprefix;?>assets/css/datepicker.min.css" />
<link rel="stylesheet" href="<?php echo $pathprefix;?>assets/css/datepicker3.min.css" />
<script src="<?php echo $pathprefix;?>assets/js/bootstrap-datepicker.min.js"></script>


<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.7.2/css/bootstrap-slider.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>

 -->