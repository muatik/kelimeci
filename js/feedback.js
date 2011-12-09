// Feedbackform
var $fbForm=null;

$(function(){

	// Feedback form
	var feedbackFrm=
	'<form id="feedbackForm" method="post" action="">'+
		'<p>'+
			'<label for="fbEmail">E-posta (tercihen):</label>'+
			'<input type="text" name="fbEmail" id="fbEmail" maxlength="50" />'+
		'</p>'+
		'<p>'+
			'<label for="fbComments">Görüşün:</label>'+
			'<textarea name="fbComments" id="fbComments" maxlength="1000"></textarea>'+
		'</p>'+
		'<input type="submit" name="submitFeedback" value="Gönder" />'+
	'</form>';
	
	// Show the feedback form as tooltip
	$('#feedbackImg').qtip({
		id:'feedbackForm',
		prerender:true,
		hide:false,
		position:{
			my:'right top',
			at:'left bottom'
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

		var ajax=new simpleAjax();
		ajax.send(
			'?_ajax=users/sendFeedback',
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
				}
				return false;

			}}
		);
		
		return false;
	});

}
