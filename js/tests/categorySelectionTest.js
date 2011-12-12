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
		
			var $selectedItems=$(this).parent()
				.find('ul.categories li span.selected');
			
			if($selectedItems.length>0){

				// Disable the input that is operated for
				$(this).attr('disabled',true);
				
				var selected2=new Array();

				$selectedItems.each(function(){
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

			var 
				imgIncorrect='<img src="../images/incorrect.png" alt="" />',
				imgCorrect='<img src="../images/correct.png" alt="" />',
				$resultSpan=$(
					'input[class="wordId"][value="'+rsp.wordId+'"]'  
				).parent().find('ul.categories li span');

			if(rsp.result){
				$resultSpan.find('.selected').each(function(){
					$(this).addClass('correct');
				});
			}
			else{
				$resultSpan.each(function(){
					var
						$t=$(this),
						val=$t.html();


					// If the current in the correction
					if($.inArray(val,rsp.corrections)!=-1){
						// If not selected
						if(!$t.hasClass('selected')){
							$t.addClass('unselectedCorrect');
							$t.parent().append(imgIncorrect);
						}
						else{
							$t.addClass('correct');
							$t.parent().append(imgCorrect);
						}
					}
					// If the current is a incorrect answer
					else{
						// If not selected
						if($t.hasClass('selected')){
							$t.addClass('incorrect');
							$t.parent().append(imgIncorrect);
						}
					}
				});
			}
			
		}

	}

	test.start();

});
