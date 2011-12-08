$(function(){

	$('#feedbackForm').submit(function(){
		//alert('Ajax ile görüşün kaydedilmesi yapılacak ve sonra da form kapatılacak!');

		return false;
	});

	var feedbackFrm=
	'<form id="feedbackForm" method="post" action="">'+
		'<p>'+
			'<label for="fbEmail">E-posta (tercihen):</label>'+
			'<input type="text" name="fbEmail" id="fbEmail" />'+
		'</p>'+
		'<p>'+
			'<label for="fbMessage">Görüşün:</label>'+
			'<textarea name="fbMessage" id="fbMessage"></textarea>'+
		'</p>'+
		'<input type="submit" name="submitFeedback" value="Gönder" />'+
	'</form>';

	var $frm=null;

	$('#feedbackImg').qtip({
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
				// Reset the form on every show
				$frm=$('#feedbackForm').get(0).reset();
			}
		}
	});
});
