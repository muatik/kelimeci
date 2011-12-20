$(document).ready(function(){

	var test=new Test('voiceTest');
	
	test.bindItems=function(){
		
		$('.testPageOl li input[type=text]').focusout(function(){
			
			if($(this).val()!=''){

				// Disable the input that is operated for
				$(this).attr('disabled',true);

				var params={
					wordId:$(this).parent().find('.wordId').val(),
					answer:$(this).val()
				};	

				test.checkAnswers(params);

			}

		});
		
		$('.testPageOl li img.voiceIcon').click(function(){
			
			// CODE FOR PLAYING THE VOICE FILE
			$(this).addClass('playing');
			
		});

	}

	test.afterChecked=function(rsp){

		if(rsp!=null){

			var $resultInput=$(
				'.testPageOl input[class="wordId"][value="'+rsp.wordId+'"]'
			);
			
			var imgIncorrect='<img src="../images/incorrect.png" alt="" />',
				imgCorrect='<img src="../images/correct.png" alt="" />';
			
			// If the answer is correct
			if(rsp.result){
				$resultInput.parent().append(imgCorrect);
			}
			// If the answer is incorrect
			else{
				$resultInput.parent().append(imgIncorrect);
				var incorrect='';
				
				if(rsp.correction)
					incorrect='<strong>Yanlış:</strong>'+
						'<a href="#">'+rsp.correction+'</a>';
						
				var correction=$(
					'<span class="correction">'+
						'<strong>Doğrusu:</strong><span>'+rsp.answer+'</span>'
						+incorrect+							
					'</span>'
				);

				$resultInput.parent().append(correction);
			}

		}

	}
	
	test.start();

});
