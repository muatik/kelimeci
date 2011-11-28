$(function(){

	/**
	 * If the form has "frmErr" element, hide the form error element
	 * on its input change
	 */
	$('input[type=text],input[type=password],select,textarea').change(function(){

		var $err=$(this).parents('form').find('.frmErr');

		// If the form has "frmErr" element, hide it
		if($err.length>0){
			hideFrmErr($err);
		}

	});

});

function getWordList(){
	
}

/**
 * Create a form error element
 *
 * css for elem: css/common.css
 *
 * @return object Element object
 */
function createFrmErr(){
	var $e=$('<p class="frmErr"></p>')
		.append('<img src="images/incorrect.png" alt="" />')
		.append('<span class="msg"></span>')
		.css('display','none');
	
	return $e;
}

/**
 * Show the error for form
 *
 * @param object errElem Element object
 * @param string msg Message of error
 */
function showFrmErr(errElem,msg){
	var $e=$(errElem);
	$e.find('.msg').html(msg);
	if(!$e.is(':hidden')){
		$e.hide().fadeOut();
	}
	$e.fadeIn('fast',function(){$(this).show();});
}

/**
 * Hide the error of form
 *
 * @param object errElem Element object
 */
function hideFrmErr(errElem){
	var $e=$(errElem);
	if(!$e.is(':hidden'))
		$e.fadeOut('fast',function(){$(this).hide();});
}
