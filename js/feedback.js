$(function(){

	$('#feedbackImg').click(function(){
		var 
			$t=$(this),
			$frm=$('#feedbackForm');

		// If hidden, show it
		if($frm.is(':hidden')){
			$frm.slideDown('slow');
		}
		// Hide it
		else{
			$frm.slideUp('slow');	
		}

		return false;
	});	

	$('#feedbackForm').submit(function(){
		alert('Ajax ile görüşün kaydedilmesi yapılacak ve sonra da form kapatılacak!');

		return false;
	});

});
