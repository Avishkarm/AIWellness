$(document).ready(function(){
	setTimeout(function(){
	$("form input:not(:checkbox):not(:button):not(:submit),.input").each(function(){
			if($(this).val()){
				$(this).siblings('label').addClass('active');
			}
		});
	$("form input[type='password'").val('');
	},100);
	
	$('form :input:not(:checkbox):not(:submit):not(:button) ,.input').on('focus',function(){
		$(this).siblings('label').addClass('active');
	});
	$('form :input:not(:checkbox):not(:button) ,.input').on('blur',function(){
		if(!$(this).val()){
			$(this).siblings('label').removeClass('active');
		}
	});

	/*$('form input[type="submit"]').hover(function(){
		$(this).toggleClass('btnActive');
	})*/
});
