$(function(){

	/**
	 * If the form has "frmAlert" element, hide the form alert element
	 * on its input change
	 */
	$(':input').change(function(){

		var $alert=$(this).parents('form').find('.frmAlert');

		// If the form has "frmAlert" element, hide it
		if($alert.length>0){
			hideFrmAlert($alert);
		}

	});

});

function getWordList(){
	
}

/**
 * Create a alert element in the form
 *
 * css for elem: css/common.css
 *
 * @return object Element object
 */
function createFrmAlert(){
	var $e=$('<p class="frmAlert"></p>')
		.append('<img src="images/incorrect.png" alt="" class="icon" />')
		.append('<span class="msg"></span>')
		.css('display','none');
	
	return $e;
}

/**
 * Show the alert for form
 *
 * @param object alertElem Element object
 * @param string msg Message of error
 * @param string type Type of error(1 or 0)
 * 	1: successful
 * 	0: unsuccessful
 * 	if not specified, means 0
 */
function showFrmAlert(alertElem,msg,type){
	var 
		$e=$(alertElem),
		sucImg='images/correct.png',
		unSucImg='images/incorrect.png';
	
	// Show the new message
	$e.find('.msg').html(msg);

	// Remove classes
	$e.removeClass('successful unsuccessful');

	// Set the img. and the message text color according to message type
	if(!type || type==0){
		$e.addClass('unsuccessful');
		$e.find('.icon').attr('src',unSucImg);
	}
	else{
		$e.addClass('successful');
		$e.find('.icon').attr('src',sucImg);
	}

	// If visible, hide it
	if(!$e.is(':hidden')){
		$e.hide().fadeOut();
	}

	// Show it, and if it is a successful message,
	// hide it width 2 seconds
	$e.fadeIn(
		'fast',
		function(){
			$(this).show();
			// Hide it, if successful message
			if(type==1)
				setTimeout(function(){hideFrmAlert($e);},2000);
		}
	);
}

/**
 * Hide the alert of form
 *
 * @param object alertElem Element object
 */
function hideFrmAlert(alertElem){
	var $e=$(alertElem);
	if(!$e.is(':hidden'))
		$e.fadeOut('fast',function(){$(this).hide();});
}
