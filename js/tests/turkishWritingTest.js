$(document).ready(function(){

	var test=new Test('turkishWritingTest');

	test.bindItems=function(){
		$('input.answer').focusout(function(){
			
			if($(this).val()!=''){

				// Disable the input that is operated for
				$(this).attr('disabled',true);

				var params={
					'wordId':$(this).parent().find('input.wordId').val(),
					'answer':$(this).val()
				};	
				test.checkAnswers(params);

			}

		});

	}

	test.afterChecked=function(rsp){

		if(rsp!=null){

			var $resultInput=$(
				'input[class="wordId"][value="'+rsp.wordId+'"]'
			).parent().find('input[class="answer"]');
			
			// If the answer is correct
			if(rsp.result){
				$resultInput.addClass('correct');
			}
			// If the answer is incorrect
			else{
				$resultInput.addClass('incorrect');
				var incorrect='';
				if(rsp.correction)
					incorrect='<strong class="incorrect">Yanlış:</strong>'+
					'<a href="#">'+rsp.correction+'</a>';
				var correction=$(
					'<p class="correction">'+
						'<strong>Doğrusu:</strong>'+
						'<span>'+rsp.answer+'</span>'
						+incorrect+
					'</p>'
				);
				$resultInput.parent().append(correction);
			}

		}

	}
	test.bindItems();

	test.startTimer();
});
