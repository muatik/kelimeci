$(document).ready(function(){

	var test=new Test('variationWritingTest');

	test.bindItems=function(){
		
		$('.testPageOl li input[type=submit]').click(function(){
		
			var nonemptyInputs=$(this).parent().
				find('ul.variations li input[type=text][value!=""]');
			var allInputs=$(this).parent().
				find('ul.variations li input[type=text]');
			
			if($(nonemptyInputs).length>0){

				// Disable the input that is operated for
				$(this).attr('disabled',true);

				var wordId=$('input[name="wordId"]',
					$(this).parent()).val();

				var variations=[];
				var answers=[];
				
				$(allInputs).each(function(index){
					
					// Disable the input that is operated for
					$(this).attr('disabled','disabled');
					
					answers.push(encodeURI($(this).val()));
					variations.push(encodeURI(
						$('input[name="variation"]',
							$(this).parent()).val()
					));

				});

				var params={
					'wordId':wordId,
					'variations':variations,
					'answers':answers
				};
				
				test.checkAnswers(params);

			}

			return false;
		
		});

	}

	test.afterChecked=function(rsp){

		if(rsp!=null){

			var wordInput=$('input[value="'+rsp.wordId+'"]'),
				imgIncorrect='<img src="../images/incorrect.png" alt="" />',
				imgCorrect='<img src="../images/correct.png" alt="" />';

			for(var i in rsp.correction){
				var c=rsp.correction[i];
				
				var vryInput=$(
					'input[value="'+c[0]+'"]',
					wordInput.parent()
				);
				
				var answerInput=$('input[name="answer"]',
					vryInput.parent());
				
				// If the answer is correct
				if(answerInput.val()==c[1]){
					$(answerInput).addClass('correct');
					$(answerInput).parent().parent().append(imgCorrect);
				}
				// If the answer is incorrect
				else{
					$(answerInput).addClass('incorrect');
					$(answerInput).parent().parent().append(
						imgIncorrect+
						'<span>'+
							'<b>Doğrusu:</b>'+
							'<span>'+c[1]+'</span>'+
						'</span>'
					);
				}

			} // end of corrections


		}
	
	} // end of function afterCheck
	
	test.start();

});
