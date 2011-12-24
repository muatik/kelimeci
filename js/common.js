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
 * Create a tooltip
 *
 * @param array || object tooltip
 * 	1) array:
 * 		[
 *			{'target':'','options':''},
 *			{'target':'','options':''},
 *			...
 * 		]
 *
 * 	2) object:
 *		{'target':'','options':''}
 */
function createTooltip(tooltip){
	// If array, call self in a loop
	if($.isArray(tooltip)){
		for(var i in tooltip)
			createTooltip(tooltip[i]);
	}
	// If not array
	else{
		// If there is target element
		if($(tooltip.target).length>0){
			// Create tooltip
			$(tooltip.target).qtip(tooltip.options);
		}
	}
}

/**
 * Hide the tooltip 
 * 	by clicking the hide link inside the tooltip
 */
function hideTooltip(elem){
	var $e=$(elem);

	// If invoked by a qtip hide link inside a tooltip
	if($e.hasClass('qtipHide')){
		var qtipId=$e.parents('.ui-tooltip').attr('id');
		$('#'+qtipId).qtip('api').hide();
		return false;
	}
}

/**
 * Custom defined tooltips
 *
 * 	-result: Shown after the result of question
 */
/*
Tooltips={
	defs:{
		tClass:'customTooltip',
		nameSpaces:['test']
	},
	show:function(target,type,nameSpace){
		if(nameSpace){
		}
		else{
		if(this.types[type])
			$(target).qtip(this.types[type]);
		}
	},
	types:{
		nameSpaced:{
			test:{
				result:{
					show:{ready:true},
					content:
					'<ul class="'+this.defs.tClass+'">'+
						'<li><b>Y: </b><span>Doğru seçilen</span></li>'+
						'<li><b>K: </b><span>Yanlış seçilen</span></li>'+
						'<li><b>Ü: </b><span>Seçilmeyen doğru</span></li>'+
					'</ul>',
					position:{
						my:'left center',
						at:'right center'
					},
					style:{
						classes:'ui-tooltip-youtube ui-tooltip-shadow'
					}
				}
			}
		}
	}
}
*/

/**
 * Customized tooltips
 */
function ToolTips(){}

/**
 * Defaults
 */
ToolTips.prototype.defs={
	tClass:'customToolTip'
}

/**
 * Show the tooltip with the type speficied
 *
 * @param string target
 * @param string type ToolTip type
 * @param string nameSpace
 */
ToolTips.prototype.show=function(target,type,nameSpace){
	// If no target like this, return
	if($(target).length==0)
		return;
	
	var tTip=null;

	// If correct namespace and type
	if(nameSpace && this.types.nameSpaced[nameSpace][type])
		tTip=this.types.nameSpaced[nameSpace][type];		
	// If correct type
	else if(type && this.types[type])
		tTip=this.types[type];
	
	if(tTip!=null)
		$(target).qtip(tTip);
}
	
/**
 * Types of tooltips
 */
ToolTips.prototype.types={
	nameSpaced:{
		test:{
			result:{
				show:{ready:true},
				hide:false,
				content:{
					title:{
						text:'Sonuç İşaretleri',
						button:true
					},
					text:
					'<ul class="questionResult">'+
						'<li><b class="c">D</b> - <span>Doğru seçilen</span></li>'+
						'<li><b class="i">Y</b> - <span>Yanlış seçilen</span></li>'+
						'<li><b class="l">Ç</b> - <span>Seçilmeyen doğru</span></li>'+
					'</ul>'
				},
				position:{
					my:'left center',
					at:'right center'
				},
				style:{
					classes:'ui-tooltip-youtube ui-tooltip-shadow'
				}
			}
		}
	}
}

// ToolTips object
var toolTips=new ToolTips();

