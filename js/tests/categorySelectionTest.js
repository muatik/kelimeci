$(document).ready(function(){

	var test=new Test('categorySelectionTest');

	test.bindItems=function(){
		$('.testPageOl li ul.categories li span').click(function(){
			// If the question answered
			if($(this).parent().parent().parent().
				find('input[type="submit"]').attr('disabled'))
					return;
			// If not selected
			if(!$(this).hasClass('selected'))
				$(this).addClass('selected');
			// If selected
			else
				$(this).removeClass('selected');
			

		});

		$('.testPageOl li input[type=submit]').click(function(){
		
			var selectedItems=$(this).parent()
				.find('ul.categories li span.selected');
			
			if($(selectedItems).length>0){

				// Disable the input that is operated for
				$(this).attr('disabled',true);
				
				var selected2=new Array();

				$(selectedItems).each(function(){
					selected2.push($(this).html());
				});

				var params={
					"wordId":$(this).parent().find('.wordId').val(),
					"selected":selected2
				};
				
				test.checkAnswers(params);

			}

			return false;
		
		});

	}

	test.afterChecked=function(rsp){

		if(rsp!=null){

			var imgIncorrect='<img src="../images/incorrect.png" alt="" />',
				imgCorrect='<img src="../images/correct.png" alt="" />';

			if(rsp.result){
				var resultSpan=$(
					'input[class="wordId"][value="'+rsp.wordId+'"]'  
				).parent().find('ul.categories li span.selected');

				$(resultSpan).each(function(){
					$(this).addClass('correct');
				});
			}
			else{
				var resultSpan=$(
					'input[class="wordId"][value="'+rsp.wordId+'"]'  
				).parent().find('ul.categories li span');
				
				$(resultSpan).each(function(index){

					// If the synonym in the answer
					if($.inArray($(this).html(),rsp.correction)!=-1){
						// If the synonym not selected
						if(!$(this).hasClass('selected')){
							$(this).addClass('unselectedCorrect');
							$(this).parent().append(imgIncorrect);
						}
						// If the synonym selected
						else{
							$(this).addClass('correct');
							$(this).parent().append(imgCorrect);
						}	

					}
					// If the synonym not in the answer
					else{
						// If the synonym selected
						if($(this).hasClass('selected')){
							$(this).addClass('incorrect');
							$(this).parent().append(imgIncorrect);
						}
					}		
				
				});
			}
			
		}

	}
	
	test.bindItems();

	test.startTimer();

});
