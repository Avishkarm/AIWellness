$(document).ready(function(){
	$('#search').keyup(function(){
			filterorder();
		});
	$('.search-form').submit(function(e){
		filterorder();
		e.preventDefault();
	});
	$('.bulkAct div').click(function(){
		var self=$(this);
		self.addClass('bulkActive');
		self.siblings().removeClass('bulkActive');

	})
	$('.tag').click(function(){
		$('.tagList').show();
		
      
	});
   $(document).click(function(e) {
	   if (!$(e.target).is('.tag,.tag *,.tagList *, .delete , .add ')) {
	    	$(".tagList").hide();
	   }
	});
});


/*
 
*/
function filterorder()
	{
	
	  var kwd = $("#search").val().toLowerCase();
	   if(kwd=="" || kwd.length==0) {
	    $(".rwd-table tbody tr").show();
	   }
	   $(".rwd-table:visible tr:not(:first)").each(function(){
			var flag=false;
			var str='';
	   		var cols = $(this).find("td");

	    	for(var i = 0; i < cols.length; i++){
	    		var txt=$.trim($(cols[i]).text()).replace(/\n|\r/g, "");
	    		str+=txt.toLowerCase()+' ';
	     	}
	     	
	     	if(str.indexOf(kwd)>-1){
	    		$(this).show();
	    	}else{
	     		$(this).hide();
	     	}
	   });
	   if(!$(".rwd-table:visible tr:not(:first):visible").length){
	   		$('.noData').show();
	   }else{
	   		$('.noData').hide();
	   }
	}