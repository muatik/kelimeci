var setTimeoutObjFrmAlert=null;

$(function(){

	// Autocomplete plugin for the general word search input(on the banner)
	$('input[name="word"]').autocomplete(
		'vocabulary?_ajax=vocabulary/suggest',{
			minChars: 1,
			max: 10,
			autoFill: true,
			mustMatch: false,
			matchContains: true,
			selectFirst: false,
			scrollHeight: 220,
		}
	).result(function(){
		$($(this).get(0).form).submit();
	});

});

// Validate the email
function validateEmail(data){
	var patt=new RegExp("^[a-zA-Z0-9_\\-.]*@[a-zA-Z0-9_\\-]+.[a-zA-Z]{3,4}.*[a-zA-Z]*$","g"); 
	if(patt.test(data)) return true;
	else false;
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
 * @param object where Where alert element inserts to
 * @param string msg Message
 * @param string type Type of error(1 or 0)
 * 	1: successful
 * 	0: unsuccessful
 * 	if not specified, means 0
 * @param function callBack Function called after alert message disappeared
 */
function showFrmAlert(where,msg,type,callBack){
	// Prepare
	var 
		$e=$(where),
		sucImg='images/correct.png',
		unSucImg='images/incorrect.png',
		type=type || 0;

	// If already inserted frmAlert element
	if($e.find('.frmAlert').length>0){
		// Use the inserted one
		$e=$e.find('.frmAlert');
	}
	// If no inserted frmAlert element
	else{
		// Insert new one
		$e=$e.append(createFrmAlert()).find('.frmAlert');
	}
	
	// Show the new message
	$e.find('.msg').html(msg);

	// Remove classes
	$e.removeClass('successful unsuccessful');

	// Set the img. and the message text color according to message type
	if(type==0){
		$e.addClass('unsuccessful');
		$e.find('.icon').attr('src',unSucImg);
	}
	else{
		$e.addClass('successful');
		$e.find('.icon').attr('src',sucImg);
	}

	// Fade in the alert icon
	$e.find('.icon').fadeTo('fast',0.8);

	// If the form alert element is being animated, stop all and the ones in the queue
	$e.stop(true,true);

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

			// Duration for hiding
			var hideDur=(type==1) ? 2000 : 2500;

			if(setTimeoutObjFrmAlert)
				clearTimeout(setTimeoutObjFrmAlert);

			// Hide it in the duration according to the type of message
			setTimeoutObjFrmAlert=setTimeout(
				function(){hideFrmAlert($e,callBack);},
				hideDur
			);
		}
	);
}

/**
 * Hide the alert of form
 *
 * @param object elem Form element or alert element
 * @param function callBack Function called after alert message disappeared
 */
function hideFrmAlert(elem,callBack){
	// Get if elem a form element or .frmAlert
	var $e=($(elem).hasClass('frmAlert')) ? $(elem) : $(elem).find('.frmAlert');
	if(!$e.is(':hidden'))
		$e.fadeOut('fast',
			function(){
				$(this).hide();
				// If there is callback, call it
				if(callBack){
					callBack();
				}
			}
		);
}

/**
 * Get selected (text) on the page
 *
 * @return string
 */
function getSelected(){
	var t='';

	if(window.getSelection)
		t=window.getSelection();
	else if(document.getSelection)
		t=document.getSelection();
	else if(document.selection)
		t=document.selection.createRange().text;
	
	t=$.trim(t);
	
	return t;
}

function toggleAjaxIndicator(){
	if(arguments.length<1)
		return false;

	var target=arguments[0];
	var msg='';
	var pos='append';
	var fadeOnHide=true;
	var classSuffix='';

	if(arguments.length>1)
		msg=arguments[1];
	
	if(arguments.length>2)
		pos=arguments[2];
	
	if(arguments.length>3)
		classSuffix=arguments[3];

	if(arguments.length>4)
		fadeOnHide=arguments[4];
	
	if($('.ajaxIndicator',$(target).parent()).length>0){
		$('.ajaxIndicator',$(target).parent())
			.fadeOut(
				(fadeOnHide) ? 750 : 0,
				function(){$(this).remove()}
			);
		return true;
	}

	var h='<span class="ajaxIndicator '+classSuffix+'">'
		+msg+'<img src="images/loading.gif" '
		+'alt="Yükleniyor..." /></span>';
	
	h=$(h);

	switch(pos){
		case 'after': $(h).insertAfter(target);break;
		case 'before': $(h).insertBefore(target);break;
		case 'prepend': $(target).prepend(h);break;
		case 'append': $(target).append(h);break;
	}

	return h;
}

/**
 * Set a cookie
 * 
 * @param string name
 * @param string value
 * @param string exDays Experation days (ex: 5)
 */
function setCookie(name,value,exDays){
	var 
		cVal='',
		exDate=new Date();

	exDate.setDate(exDate.getDate() + exDays);
	cVal=escape(value)+((exDays==null) ? '' : '; expires='+exDate.toUTCString());
	document.cookie=name+'='+cVal;
}

/**
 * Get cookie
 * 
 * @param string name
 * @return string If the cookie exists, return string;
 * 	otherwise return null
 */
function getCookie(name){
	var 
		cookies=document.cookie.split(';'),
		c,cName,cValue;

	for(var i in cookies){
		c=cookies[i];
		cName=c.substr(0,c.indexOf('='));
		cValue=c.substr(c.indexOf('=')+1);
		cName=cName.replace(/^\s+|\s+$/g,'');
		if(cName==name)
			return unescape(cValue);
	}
	return null;
}

