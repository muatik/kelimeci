$(document).ready(function(){

	var test=new Test('sentenceCompletionTest');

	test.bindItems=function(){
		$('.testPageOl li input[type=text]').focusout(function(){
			
			if($(this).val()!=''){

				// Disable the input that is operated for
				$(this).attr('disabled',true);

				var item=$(this).parent().parent();
				var wordId=$('input[name="wordId"]',item).val();
				var quoteId=$('input[name="quoteId"]',item).val()

				var params={
					'wordId':wordId,
					'quoteId':quoteId,
					answer:$(this).val()
				};

				test.checkAnswers(params);

			}

		});

	}

	test.afterChecked=function(rsp){
	
		if(rsp!=null){

			var $resultInput=$(
				'input[name="answer"]',
				$('input[value='+rsp.wordId+']').parent()
			);
			
			// If the answer is correct
			if(rsp.result){
				$resultInput.addClass('correct');
			}
			// If the answer is incorrect
			else{
				$resultInput.addClass('incorrect');
				
				var incorrect='';
				if(rsp.correction){
					incorrect='<strong class="incorrect">Yanlış:</strong>'+
						'<a href="#">'+rsp.correction+'</a>';
				}
				var correction=$(
					'<p class="correction">'+
						'<strong>Doğrusu:</strong><span>'+rsp.answer+'</span>'
						+incorrect+
					'</p>'
				);

				$resultInput.parent().parent().append(correction);
			}


		}

	}
	
	test.start();

});
