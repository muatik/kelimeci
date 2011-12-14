// Feedbackform
var $fbForm=null;

$(function(){

	// Email to pass into the value of email textbox
	var uEmail=(__usrEmail) ? __usrEmail : '';

	// Feedback form
	var feedbackFrm=
	'<form id="feedbackForm" method="post" action="">'+
		'<p>'+
			'<label for="fbEmail">E-posta (tercihen):</label>'+
			'<input type="text" name="fbEmail" id="fbEmail" maxlength="50" value="'+uEmail+'" />'+
		'</p>'+
		'<p>'+
			'<label for="fbComments">Görüşün:</label>'+
			'<textarea name="fbComments" id="fbComments" maxlength="1000"></textarea>'+
		'</p>'+
		'<input type="submit" name="submitFeedback" value="Gönder" />'+
		'<input type="button" name="resetFeedback" value="Sıfırla" onclick="resetFbForm();" />'+
	'</form>';
	
	// Show the feedback form as tooltip
	$('#feedbackImg').qtip({
		id:'feedbackForm',
		prerender:true,
		show:'click',
		hide:false,
		position:{
			my:'right bottom',
			at:'left top'
		},
		style:{
			classes:'ui-tooltip-youtube ui-tooltip-shadow'
		},
		content:{
			title:{
				text:'Görüşünü yaz',
				button:true
			},
			text:feedbackFrm
		},
		events:{
			show:function(e,api){
				if($fbForm==null){
					// Feedback form
					$fbForm=$('#feedbackForm');
					// Bind the form on submit
					fbFormOnSubmit($fbForm,$(this));
				}
					
				// Reset the form on every show
				$fbForm.get(0).reset();

				// Enable the form button
				$fbForm.find(':submit').removeAttr('disabled');
			},
			hide:function(e,api){
				// Hide the form alert, if there is
				hideFrmAlert($('#feedbackForm'));
			}
		}
	});
});

function fbFormOnSubmit($frm,$tooltip){

	$($frm).submit(function(){
		// Prepare
		var 
			// Form
			$f=$(this),
			alertText=null,
			$email=$f.find(':input[name="fbEmail"]'),
			$comments=$f.find(':input[name="fbComments"]');

		// If email is not valid
		if($email.val()!='' && !validateEmail($email.val())){
			alertText='Geçersiz bir e-posta adresi girdin!';
			showFrmAlert($f,alertText);
			$email.focus();
			return false;
		}

		// If comment is empty
		if($comments.val()==''){
			alertText='Görüşünü gir!';
			showFrmAlert($f,alertText);
			$comments.focus();
			return false;
		}

		// Disable the form button
		$f.find(':submit').attr('disabled','disabled');
		
		var ajax=new simpleAjax();
		ajax.send(
			'?_ajax=users/feedBack',
			'email='+encodeURI($email.val())+'&'+
				'comments='+encodeURI($comments.val()),
			{'onSuccess':function(rsp,o){

				// If okay
				if(rsp=='1'){
					alertText='Görüşün alındı. Teşekkürler.';
					showFrmAlert($f,alertText,1,
						function(){
							// Hide the feedback form
							$tooltip.qtip('api').hide();
						}
					);
				}
				else{
					// Alert the error
					showFrmAlert($f,rsp);

					// Enable the form button
					$f.find(':submit').removeAttr('disabled');
				}

				return false;

			}}
		);
		
		return false;
	});

}

/**
 * Reset the feedback form
 */
function resetFbForm(){
	var $frm=$('#feedbackForm');
	$frm.find(':input[id]').val('');
	$frm.find(':input[name=fbComments]').focus();
}
