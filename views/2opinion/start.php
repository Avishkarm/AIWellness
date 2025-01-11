  <?php
  session_start();

  
  $activeHeader="2opinion";
  $title="DiseaseCompass";
  $activeHeader1="disease_compass";

  require_once("../../utilities/config.php");
  require_once("../../utilities/dbutils.php");
  require_once("../../models/commonModel.php");
  $_SESSION['flag']=false;  

  //database connection handling
  $conn = createDbConnection($servername, $username, $password, $dbname);
  $returnArr=array();
  if(noError($conn)){
    $conn = $conn["errMsg"];
  }
  else{
    printArr("Database Error");
    exit;
  }

  $complaintsQuery=getSystemComplaint($conn,'');
  
  if(noError($complaintsQuery)){    
    $complaintsQuery=$complaintsQuery['errMsg'];
  }            
  else{
    $msg= printArr("Error Fetching Complaints Data".$complaintsQuery['errMsg']);  
  }

   //printArr($complaintsQuery);
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <?php include_once("../metaInclude.php"); ?>
    <style type="text/css">
      
      .chosen-container .chosen-results {
        font-size: 19px !important;
      }
      .chosen-container-multi .chosen-choices li.search-field input[type=text] {
        color:#777;
      }
      .chosen-container-active .chosen-choices {
            border: 1px solid #aaa !important;
            box-shadow: 0 0 5px rgba(0,0,0,.3);
        }
      .chosen-container-multi .chosen-choices {
          font-size: 19px !important;
          font-weight: 500 !important;
          padding: 15px 15px !important;
          background: #fff !important;
         /* border-radius: 4px !important;*/
      }
      .chosen-container-multi .chosen-choices li.search-choice {
        padding: 10px 20px 10px 10px !important;
      }
      .chosen-single {
        height: 62px !important;
          padding: 15px 15px !important;
          /*color: #444 !important;*/
          font-size: 100% !important;
          font-family: sans-serif;
          line-height: normal;
          border: 1px solid #aaa !important;
          background: #fff !important;
          -webkit-transition: all .2s linear !important;
          -webkit-transition-property: border,background,color,box-shadow,padding !important;
          transition: all .2s linear !important;
          transition-property: border,background,color,box-shadow,padding !important;
          border-radius: 0!important;
         /* border-radius: 4px !important;*/
      }
      .chosen-container-single .chosen-single div b {
          margin-top: 15px;
      }
      .chosen-container {         
          padding: 20px 0px 0px 0px;
          font-size: 19px !important;
      }
     
      
.chosen-container-multi .chosen-choices li.search-choice .search-choice-close{
        background: url('../../assets/images/error.png')  no-repeat !important;
        background-size: contain !important;
  }

  #conclusion{
        width:97%;
      }
      /*margin: top right bottom left*/
      #complaint1,#complaint2{ 
        margin:30px 0px 0px 0px;
        /*margin:30px 0px 30px 0px;*/
      } 
      .bullets {
          padding-left:20px;
          position:relative;
          /*margin-left:0.6em;*/
          box-sizing:border-box;
      }
      .bullets:not(.end) {    
          /*border-left:1px solid #0dae04;        */
      }
      .bullets:before
      {
          background-color:#0dae04;
          width:1.2em;
          height:1.2em;
          content:'';    
          border-radius:50%;
          position:absolute;
          left:-0.6em;
          top:0;

      }
      .red:before{
        background-color:red!important;
      }
      .yellow:before{
        background-color:#ffb431!important;
      }
      .display
      {
        display: inline-block!important;
      }
      #result1,#result2
      {
        border-left: 1px solid gray;
        margin-left: 10px;
        /*padding-top: 40px;*/
        margin-top: 0;
      }
      #result1 h4,#result2 h4{
        text-align: left;
      }
      .padding
      {
        padding-top: 40px;
      }
      .content{
        /*margin-left: 30px;*/
        font-size: 15px;
      }

  #statusquo,#good,#bad {
          background-repeat: no-repeat!important;
          margin-left: 15px;
          margin-top: 80px;
          padding: 60px 20px 60px 20px;         
      }
      .head
      {
        font-size:50px !important;
        font-weight: 700 !important;
        color:#fff;
      }
      .conclusionContent
      {
        font-size:30px !important;
        font-weight: 600 !important;
        color:#fff;
        padding: 20px;
      }


    </style>
    <main class="container" style="min-height: 100%;">
    <link rel="stylesheet" type="text/css" href="../../assets/css/chosen.min.css">
      <?php  include_once("../header.php"); ?> 
      <section>
      <div class="main-container">
       
          <div class="col-md-12 banner" style="margin: 0px 0px 30px 0px;" >
               <img src="../../assets/images/compass.png" class="img-responsive">
               <!-- <h1><span style="color:#ffb600;">Let's see how your treatment goes! </span><span style="color:#ffffff;">Tell us your original problem and how you feel now</span></h1> -->

              </div>
              <div class="col-md-12" style="" >
                   <h3 class="bannerContent" style=""> Do you know which way your treatment is headed? Use the eHeilung app to know the right way. 
                        </h3>
                         <h3 style="color: red;">This is a quick guide based on scientific principles of Embryology, Genetics, and laws of cure given by C. Hering and adopted by German scientist S. Hahnemann</h3>
                  </div>
                      <!-- Dropdown Starts -->
              <div class="col-md-12">
               <div class="col-md-6 col-sm-6" id="dropdown1" >
            <h3 for="complaint1">Original Complaint</h3>
            <select data-placeholder="Select" id="complaint1" type="text" class="form-control chosen-select"  value="drop1" >
              <option></option>
              <?php
              foreach($complaintsQuery as $complaintsId=>$complaintsDetails)
              {


                $complaintsName=utf8_encode($complaintsDetails["Common_name"]);
                $complaintsName1=utf8_encode($complaintsDetails["Diagnostic_term"]);
                $definition=cleanQueryParameter($conn,$complaintsDetails["Definition"]);
                $System=$complaintsDetails["system"];
                $Organ=$complaintsDetails["organ"];
                $Suborgan=$complaintsDetails["subOrgan"];
                $Embryological=$complaintsDetails["embryologcial"];
                $Miasm=$complaintsDetails["miasm"];
                 $definition = str_replace('"', '', $definition);
                $selected = "";
                if($complaintsName==$caseDetails["Common_name"])
                  $selected = "selected";

                ?>  

                <option data-opt="drop1" <?php echo $selected; ?> data-sys="<?php echo $System;?>" data-organ="<?php echo $Organ;?>" data-suborgan="<?php echo $Suborgan;?>" data-embbryo="<?php echo $Embryological;?>" data-miasm="<?php echo $Miasm;?>" data-def="<?php echo utf8_encode($definition);?>"  data-name="<?php echo $complaintsName1;?>" >

                  <?php echo ucfirst(strtolower($complaintsName1));?>
                </option>
                <?php if($complaintsName1!='Eczema' && $complaintsName1!='Dermatitis'){ ?>
                <option data-opt="drop1" <?php echo $selected; ?> data-sys="<?php echo $System;?>" data-organ="<?php echo $Organ;?>" data-suborgan="<?php echo $Suborgan;?>" data-embbryo="<?php echo $Embryological;?>" data-miasm="<?php echo $Miasm;?>" data-def="<?php echo utf8_encode($definition);?>"  data-name="<?php echo $$complaintsName;?>" >

                  <?php echo ucfirst(strtolower($complaintsName));?>

                </option>                  

                <?php
              }}
              ?>                       
            </select>

            <div id="result1">
                <div id="title1" style="margin-top:20px;color:#080808;">

                </div>
                <div id="name1">

                </div>
                <div id="definition1">
                
                </div>
                <div id="System1">
                </div>  
                <div id="Organ1">
                </div>
                <div id="Suborgan1">
                </div>
                <div id="Embryological1">
                </div>
                <div id="Miasm1">
                </div>
              </div>
          </div>


          
          <!--Complaint2 -->
          <div class="col-md-6 col-sm-6" id="dropdown2">
            <h3 for="complaint2">Present Complaint</h3>
            <select data-placeholder="Select" id="complaint2" class="form-control chosen-select"  value="drop2">
              <option></option>
              <?php
              foreach($complaintsQuery as $complaintsId=> $complaintsDetails)
              {

                $complaintsName=utf8_encode($complaintsDetails["Common_name"]);
                $complaintsName1=utf8_encode($complaintsDetails["Diagnostic_term"]);
                $definition=cleanQueryParameter($conn,$complaintsDetails["Definition"]);
                $System=$complaintsDetails["system"];
                $Organ=$complaintsDetails["organ"];
                $Suborgan=$complaintsDetails["subOrgan"];
                $Embryological=$complaintsDetails["embryologcial"];
                $Miasm=$complaintsDetails["miasm"];
                 $definition = str_replace('"', '', $definition);
                $selected = "";
                if($complaintsName==$caseDetails["Common_name"])
                  $selected = "selected";
                ?>  
                <!-- <option data-opt="drop2" <?php echo $selected; ?> data-sys="<?php echo $System;?>" data-organ="<?php echo $Organ;?>" data-suborgan="<?php echo $Suborgan;?>" data-embbryo="<?php echo $Embryological;?>" data-miasm="<?php echo $Miasm;?>" data-def="<?php echo $definition;?>"  data-name="<?php echo $complaintsName1;?>" >

                  <?php echo $complaintsName1;?>

                </option> -->
                <option data-opt="drop2" <?php echo $selected; ?> data-sys="<?php echo $System;?>" data-organ="<?php echo $Organ;?>" data-suborgan="<?php echo $Suborgan;?>" data-embbryo="<?php echo $Embryological;?>" data-miasm="<?php echo $Miasm;?>" data-def="<?php echo utf8_encode($definition);?>"  data-name="<?php echo $complaintsName1;?>" >

                  <?php echo ucfirst(strtolower($complaintsName1));?>

                </option> 
                <?php if($complaintsName1 !='Eczema' && $complaintsName1!='Dermatitis'){ ?>
                <option data-opt="drop1" <?php echo $selected; ?> data-sys="<?php echo $System;?>" data-organ="<?php echo $Organ;?>" data-suborgan="<?php echo $Suborgan;?>" data-embbryo="<?php echo $Embryological;?>" data-miasm="<?php echo $Miasm;?>" data-def="<?php echo utf8_encode($definition);?>"  data-name="<?php echo $$complaintsName;?>" >

                  <?php echo ucfirst(strtolower($complaintsName));?>

                </option> 


                <?php
              }}
              ?>                       
            </select>

            <div id="result2" style="">
                <div id="title2" style="margin-top:20px;color:#080808;">

                </div>
                <div id="name2">

                </div>
                <div id="definition2">
                </div>
                <div id="System2">
                </div>
                <div id="Organ2">
                </div>
                <div id="Suborgan2">
                </div>
                <div id="Embryological2">
                </div>
                <div id="Miasm2">
                </div> 
              </div>
          </div>

                    
          <!--Conclusion -->
        <div class="row" >
          <div id="conclusion" class="text-center">
            <div class="col-md-12 good"  id="good" style="background: url(../../assets/images/good.png);">
              <div class="text-center" >
                <div class="row"> <h1 class="head">Conclusion</h1></div>
              </div>
              <div>
                <p class="conclusionContent">Well done! Your treatment is on the right path.</p>
              </div>
            </div>
            <div class="col-md-12 bad"  id="bad" style="background: url(../../assets/images/bad.png);" >
              <div class="text-center" >
                <div class="row"> <h1 class="head">Conclusion</h1></div>
              </div>
              <div>
                <p class="conclusionContent">Your disease is most likely being suppressed. Your treatment may be on the wrong track.</p>
              </div>
            </div>
            <div class="col-md-12 statusquo"  id="statusquo" style="background: url(../../assets/images/statusquo.png);">
              <div class="text-center" >
                <div class="row"> <h1 class="head">Conclusion</h1></div>
              </div>
              <div>
                <p class="conclusionContent">Your disease is being suppressed. Your treatment is on the wrong track.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Conclusion ends -->



              </div>
      </section>
    </main> 
    <?php include('../modals.php'); ?>
    <?php include('../footer.php'); ?>
<script type = "text/javascript" src= "../../assets/js/chosen.jquery.min.js"></script>
<script type="text/javascript">
  $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"});


  $("#conclusion").hide();
    
      var  complaintDieases = [
        {'Syphilis':3,'Sycosis':2, 'Psora':1},
        {'Genetic Mutations':11, 'Neuroderm':10, 'Endocrine':9,'Mesoderm Endocrine': 8, 'Mesoderm' : 7, 'Specialized Connective Tissue':6, 'Connective Tissue':5, 'Musculo-skeletal':4,'Endoderm Mesoderm':3, 'Endoderm':2,'Ectoderm':1  },
        {'Multifactoral Inherited or acquired Diseases (Poly..':13,'Neuro-Psychiatry':12,'Nervous System':11,'Endocrine':10, 'Cardiovascular System':9, 
          'Urinary System':8,'Immune System':7, 'Hematopoitic System':6,'Reproductive Male/Female':5,'Musculo-skeletal':4,'Gastro-intestinal':3, 'Respiratory':2,'Integumentry':1},
        {'Skin and Joints':25,'Multi-systemic':24,'Muscles and Skin':23,'Blood Vessels':22,'Arteries':21,'Bones':20,'Joints':19,'Spleen':18,'Blood Oragnelles':17,'Internal Gentitals':16,'Female Reproductive':15,'External Female Organ':14,'Internal Female Reproductive Organ':13,'External Genitals':12,'Muscles':11,'Cartilage':10,'Bones and Joints':9,'Liver':8,'Lower GIT':7,'Upper GIT':6,'Gall Bladder':5,'Pancreas':4,'Lower Respiratory':3,'Upper Respiratory':2,'Skin':1},
        {'subOrgan': 1, 'Epidermis': 2, 'Dermis': 3, 'Pharynx': 4, 'Nose': 5, 'Larynx': 6, 'Trachea': 7,'Bronchus': 8,'Lungs': 9,'Pancreas': 10,'Gall Bladder': 11,'Duodenum': 12,'Oesophagus': 13,'Stomach': 14,'Colon': 15,'Rectum': 16,'Anus': 17,'Liver': 18,'Vertebra': 19,'Bones': 20,'Joints': 21,'Cartilage': 22,'Muscle': 23,'Systemic': 24,'Exocrine + Joints': 25,'Knees or Hips': 26}
                  ];
      function checkComplaint(comp1, comp2,i,data_miasm1_def,data_miasm2_def){

        if(complaintDieases[i][comp1[i]] < complaintDieases[i][comp2[i]]){
          return ["BAD",[data_miasm1_def,data_miasm2_def]];
        }else if(complaintDieases[i][comp1[i]] > complaintDieases[i][comp2[i]]){
          return ["GOOD",[data_miasm1_def,data_miasm2_def]];
        }else{
          if(i>=comp1.length-1 && i>=comp2.length-1){
            return ["STATUS QUO",[data_miasm1_def,data_miasm2_def]];
          }else{
            return checkComplaint(comp1, comp2, ++i,'All three Syphilis,Sycosis,Psora are types of miasm','All three Syphilis,Sycosis,Psora are types of miasm');
          }
        }
      }
      $('.good').hide();
      $('.bad').hide();
      $('.statusquo').hide();
      $('#mainConclusion').hide();
      $('#conclusion').hide(); 



        $("#complaint2").change(function(){
          calConclusion();
        });
        $("#complaint1").change(function(){
          calConclusion();
        });

        function calConclusion(){


          var selectedText1 = $("#complaint1").find("option:selected").text();
          var selectedValue1 = $("#complaint1").val();
          var selectedText2 = $("#complaint2").find("option:selected").text();
          var selectedValue2 = $("#complaint2").val();

          var data_opt1="Name : "+$("#complaint1").find(':selected').attr('data-opt');
          var data_opt2="Name : "+$("#complaint1").find(':selected').attr('data-opt');


          var data_name1=$("#complaint1").find(':selected').attr('data-name');
          var data_name2=$("#complaint2").find(':selected').attr('data-name');
          var data_def1=  ($("#complaint1").find(':selected').attr('data-def'));
          var data_def2=($("#complaint2").find(':selected').attr('data-def')); 
          var data_sys1=$("#complaint1").find(':selected').attr('data-sys');
          var data_sys2=$("#complaint2").find(':selected').attr('data-sys');
          var data_organ1=$("#complaint1").find(':selected').attr('data-organ');
          var data_organ2=$("#complaint2").find(':selected').attr('data-organ');
          var data_suborgan1=$("#complaint1").find(':selected').attr('data-suborgan');
          var data_suborgan2=$("#complaint2").find(':selected').attr('data-suborgan');
          var data_embbryo1=$("#complaint1").find(':selected').attr('data-embbryo');
          var data_embbryo2=$("#complaint2").find(':selected').attr('data-embbryo');
          var data_miasm1=$("#complaint1").find(':selected').attr('data-miasm');
          var data_miasm2=$("#complaint2").find(':selected').attr('data-miasm');
          var comp1=[data_miasm1,data_embbryo1,data_sys1,data_organ1,data_suborgan1];
          var comp2=[data_miasm2,data_embbryo2,data_sys2,data_organ2,data_suborgan2];
          var res;
          if(selectedValue1 && selectedValue2){

            var newComp1=[];
            var newComp2=[];
            for(var j=0;j<comp1.length;j++){
              if (comp1[j].indexOf(',') > -1) { 
                var str = comp1[j].split(',');
                var arr = Object.keys( complaintDieases[j] ).map(function ( key ) { return complaintDieases[j][key]; });
                var max = Math.max.apply( null, arr );
                var key = Object.keys(complaintDieases[j]).filter(function(key) {return complaintDieases[j][key] === max})[0];
                newComp1[j]=key;
              }else{
                newComp1[j] = comp1[j];
              }


              if (comp2[j].indexOf(',') > -1) { 
                var str1 = comp2[j].split(',');
                var arr1 = Object.keys( complaintDieases[j] ).map(function ( key ) { return complaintDieases[j][key]; });
                var max1 = Math.max.apply( null, arr1 );
                var key1 = Object.keys(complaintDieases[j]).filter(function(key) {return complaintDieases[j][key] === max1})[0];
                newComp2[j]=key1;
              }else{
                newComp2[j] = comp2[j];
              }
            }
            console.log(newComp1, newComp2);
            var dt= checkComplaint(newComp1, newComp2,0,data_miasm1,data_miasm2);
            res = dt[0];
            data_miasm1_def = dt[1][0];
            data_miasm2_def = dt[1][1];
            console.log(dt);
              if((data_opt1!="drop1" || data_opt2!="drop2") && (selectedValue1==1 || selectedValue2==1))
              { 


                    //alert("please select Both complaints");
                    $("#mainConclusion").hide();
                    $("#conclusion").hide();
                    $("#notify").show(); 
                    $('.good').hide();
                    $('.bad').hide();
                    $('.statusquo').hide(); 
                    
                  }else {
                    $("#mainConclusion").show();
                    $("#conclusion").show();
                    $("#notify").hide();
                    $("#result2").addClass("padding");
                    $("#result1").addClass("padding");
                    //$('#title1').html('ORIGIN:');
                    //$('#title2').html('ORIGIN:');
                    //$('#name1').html("<h4>Name : "+data_name1);
                    //$('#name2').html("<h4>Name : "+data_name2);
                    $('#definition1').html("<div class='bullets bullets1'><h4> Definition</h4><p class='content'>"+data_def1+"</p></div>");
                    $('#definition2').html("<div class='bullets bullets2'><h4> Definition</h4><p class='content'>"+data_def2+"</p></div>");
                    $('#System1').html("<div class='bullets bullets1'><h4> System</h4><p class='content'>"+data_sys1+"</p></div>");
                    $('#System2').html("<div class='bullets bullets2'><h4> System</h4><p class='content'>"+data_sys2+"</p></div>");
                    $('#Organ1').html("<div class='bullets bullets1'><h4> Organ</h4><p class='content'>"+data_organ1+"</p></div>");
                    $('#Organ2').html("<div class='bullets bullets2'><h4> Organ</h4><p class='content'>"+data_organ2+"</p></div>");
                    $('#Suborgan1').html("<div class='bullets bullets1'><h4> Suborgan</h4><p class='content'>"+data_suborgan1+"</p></div>");
                    $('#Suborgan2').html("<div class='bullets bullets2'><h4> Suborgan</h4><p class='content'>"+data_suborgan2+"</p></div>");
                    $('#Embryological1').html("<div class='bullets bullets1'><h4> Embryological</h4><p class='content'>"+data_embbryo1+"</p></div>");
                    $('#Embryological2').html("<div class='bullets bullets2'><h4> Embryological</h4><p class='content'>"+data_embbryo2+"</p></div>");
                    $('#Miasm1').html("<div class='bullets bullets1'><h4> Miasm</h4><p class='content'>"+data_miasm1_def+"</p></div>");
                    $('#Miasm2').html("<div class='bullets bullets2'><h4> Miasm</h4><p class='content'>"+data_miasm2_def+"</p></div>");
                    
                  //$("#result2").CSS("padding-top","20px");
                  

                    if(res=='GOOD')
                    {
                      $('.good').show();
                      $('.good').addClass("display");
                      $('.bad').hide();
                      $('.statusquo').hide(); 
                      $('.bad').removeClass("display");
                      $('.statusquo').removeClass("display");
                      $('.bullets2').removeClass("red");
                      $('.bullets2').removeClass("yellow");
                      
                    }
                    else if(res=='BAD')
                    {
                      $('.good').hide();
                      $('.bad').show();
                      $('.bad').addClass("display");
                      $('.bullets2').addClass("red");
                      $('.statusquo').hide();
                      $('.good').removeClass("display");
                      $('.statusquo').removeClass("display");
                      $('.bullets2').removeClass("yellow");
                    }
                    else if(res=='STATUS QUO')
                    {
                      $('.good').hide();
                      $('.bad').hide();
                      $('.statusquo').show();
                      $('.statusquo').addClass("display");
                      $('.bad').removeClass("display");
                      $('.good').removeClass("display");
                      $('.bullets2').removeClass("red");
                      $('.bullets2').addClass("yellow");
                    }
              }
            }
    
          }




  /*$(".default").val("Select");*/

  $('#errorMsg').hide();
  $('#errorMsg1').hide();
  $("#complaint").change(function(){
       $('#errorMsg').hide();
              setDurationBoxes();
        });

  function setDurationBoxes()
        {
          var val = $("#complaint").val();
          if(val!== null){
            $("#duration").show();
              var vals = val.toString();
            
              var values = vals.split(",");
           
              var complaintName = "";
              var durationHTML = "";
              var validation

              for(var i in values)
              {
              
                complaintName = $("option[value="+values[i]+"]").text();
                durationHTML += '<div class="col-md-12 timeDuration" style="padding-right: 0px;padding-left:0px;margin-bottom:30px" id="durationsBox_'+i+'"><div style="font-size:20px;padding-bottom:10px;">'+complaintName+'</div><div class="col-md-3" style="padding-left:0px"><input class="form-control durationInput" type="number" placeholder="Year" name="probDuration['+values[i]+'][years]" id="year_'+i+'" value="" min="1" required/><label for="probDurationYears"></label></div><div class="col-md-3 month" style=""><input class="form-control durationInput" placeholder="Month" type = "number" name="probDuration['+values[i]+'][months]"  id="month_'+i+'" min="1" max="12" value="" required /><label for="probDurationMonths"></label></div><div class="col-md-3"><input class="form-control durationInput" type="number" placeholder="Day" name="probDuration['+values[i]+'][days]"  id="day_'+i+'" value="" min="1" max="31"  required/><label for="probDurationDays"></label></div></div>';
              }

              $("#duration").html(durationHTML);
          }else{
            $("#duration").hide();
          }
        }

          
         $('.submit-btn').click(function() {
        $('#errorMsg1').hide();
          var vals = $("#complaint").val(); 
          var options = $('#complaint > option:selected');                       
           var treatment=$("#treatment_type").val();
          var options1 = $('#treatment_type > option:selected');
          //alert(options1.length+".."+options.length+"--");
          console.log(vals);
          var complaint;
           if(options.length == 0)
          {
             $('#errorMsg').show();
            $('#errorMsg').html("Please select your complaint('s)");
            $('#complaint').focus();
            return false;
            
          }
          else if(treatment.length == 0)
          {
            $('#errorMsg1').show();
            $('#errorMsg1').html("please select your treatment");
            $('#treatment_type').focus();
           return false;
          }
        else{
              if(vals)
              {
                complaint= vals.pop();
              }
              complaint=complaint.replace(/_/g, ' ');
                 var duration=[];
                    var flag = 1;
                $(".timeDuration").each(function(i){
                 // console.log("inside timeDuration"+i);
                   var year,month,day;
                   year = $(".timeDuration").find('input#year_'+i).val();
                   month = $(".timeDuration").find('input#month_'+i).val();
                   day = $(".timeDuration").find('input#day_'+i).val();
                 
                 // console.log(year+':'+month+':'+day);
                  var total=(year*365+month*30+day*1);
                  //alert(total);
                  
                  duration.push(total);
                 
                    if(!$(this).find('input').filter(function(){ return $(this).val(); }).length)
                    
                    {
                      flag++;
                      $(this).focus();
                      $('#errorMsg').show();
                      
                      $('#errorMsg').html("please enter complaint duration");

                    }
                 });
                     //alert(duration);
                     var max = Math.max(...duration);
              /*    Math.floor(1.6);
                   Math.ceil(1.4)
                   Math.round(2.5);*/
                     max1=max/2;
                      //alert(max1);
                     year1=Math.floor(max1/365);
                     month1=Math.floor(Math.floor(max1%365)/30);
                     day1=(Math.floor(max1%365)%30);
                     //day1=Math.floor((max1%365));
                   // alert(year1+':'+day1);
                 



                    if (flag == 1)
                    {
                       $('#errorMsg').hide();
                       if(treatment == "Modern")
                       {
                          window.location.href = 'modern.php?complaint='+complaint;
                       }
                       else
                       {
                        window.location.href = 'firstHalf.php?year='+year1+'&month='+month1+'&day='+day1+'&complaint='+complaint;
                       }
                    }
                        
                        

                    else
                    {
                       
                       
                      return false;
                    }

                    var datastring = $("#startform").serialize();
                    localStorage.setItem('startform', datastring);
                    localStorage.setItem('start_flag', true);
                  /* var name = localStorage.getItem('startform');

                    if (name != "undefined" || name != "null") {
                      $("#welcomeMessage").html(name);
                   
                    } else
                    {
                       $("#welcomeMessage").html("Hello!");
                    }*/
      }
      

    });

/*

  $('.submit-btn').click(function() {
        window.location.href = 'modern.php';
    });*/
</script>
</body>

</html>
