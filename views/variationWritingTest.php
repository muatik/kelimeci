<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/testPage.css" />
<style type="text/css">
.variationWritingTest{
	padding:10px;
	width:600px;
}
.variationWritingTest a{
	text-decoration:underline;
}
.variationWritingTest label{
	font-weight:normal;
	text-align:right;
	display:inline-block;
	width:225px;
	text-transform:capitalize;
}
.variationWritingTest input[type=submit]{
	margin-left:100px;
}
</style>

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/createXHR.js"></script>
<script type="text/javascript" src="../js/test.js"></script> 
<script type="text/javascript">
	$(document).ready(function(){

		// 'categorySelectionTest' replaced to the test name that
		// comes from the server
		var test=new Test('variationWritingTest');

		test.showTime=function(){
			$('.spentTime').html(test.elapsedTime);	
		}

		test.bindItems=function(){
			
			$('.testPageOl li input[type=submit]').click(function(){
			
				var nonemptyInputs=$(this).parent().find('ul.variations li input[type=text][value!=""]');
				var allInputs=$(this).parent().find('ul.variations li input[type=text]');
				
				if($(nonemptyInputs).length>0){

					var answer2='';
					encodeURI

					// Disable the input that is operated for
					$(this).attr('disabled',true);
					
					$(allInputs).each(function(index){
						
						// Disable the input that is operated for
						$(this).attr('disabled','disabled');
						
						/*
						answer2[index]=[
							$(this).attr('variation'),
							$(this).val()
						];
						*/
						answer2+=
							'&variation[]='+
								encodeURI($(this).attr('variation'))+
							'&answer[]='+
								encodeURI($(this).val());
								
					});		

					var params={
						itemId:$(this).parent().attr('itemId'),
						answer:answer2
					};
					
					test.checkAnswers(params);

				}

				return false;
			
			});

		}

		test.afterChecked=function(rsp){
	
			rsp=eval('('+rsp+')');	

			if(rsp!=''){

				var resultInputs=$(
					'.testPageOl li[itemId='+rsp.itemId+']  ul.variations li input[type=text]'
				);
				
				var hasIncorrects=false,
					correctCounter=0,
					imgIncorrect='<img src="../images/incorrect.png" alt="" />';
					imgCorrect='<img src="../images/correct.png" alt="" />';
				$(resultInputs).each(function(index){
				
					// Define the variation name of  current input and
					// the value of  current input
					var currInputVariation=$(this).attr('variation').toLowerCase();
					var currInputValue=$(this).val();
					
					for(var i in rsp.answer){
						
						// If the input variation equals to 
						// the variation of current answer
						if(currInputVariation==rsp.answer[i][0]){
							
							// If the answer is correct
							if(currInputValue==rsp.answer[i][1]){
								$(this).addClass('correct');
								$(this).parent().parent().append(imgCorrect);
								correctCounter++;
							}
							// If the answer is incorrect
							else{
								$(this).addClass('incorrect');
								$(this).parent().parent().append(
									imgIncorrect+
									'<span>'+
										'<b>Doğrusu:</b>'+
										'<span>'+rsp.answer[i][1]+'</span>'+
									'</span>'
								);
								hasIncorrects=true;
							}
							
						}
						
					}
				
				
				});
				
				// If has not incorrect and the correct count is true
				if(!hasIncorrects && correctCounter==rsp.answer.length){
					test.incrementCorrectCounter();
					$('.testPageHeader .correctAnswers').html(test.correctAnswerCounter);
				}
				// If has any incorrect
				else if(hasIncorrects){
					test.incrementIncorrectCounter();
					$('.testPageHeader .incorrectAnswers').html(test.incorrectAnswerCounter);
				}


			}

		}
		
		test.startTimer();

		// DELETE THIS LINE
		test.ajaxFile='../dummyData/variationWritingTest.php';

		test.bindItems();

	});

</script>

<div class="variationWritingTest">
	<?php
	require('../dummyData/variationWritingTest.php');
	echo '<div class="testPageHeader">
		<h1>Kelimenin Varyasyonlarını Yazma</h1>
		<p>
			Toplam soru:<span class="totalQuestions">'.count($o->items).'</span>,
			Tahmini süre:<span class="estimatedTime">'.$o->estimatedTime.'</span>,
		</p>
		<p>
			Geçen süre:<span class="spentTime">00:00:00</span>,
			Doğru sayısı:<span class="correctAnswers">0</span>,
			Yanlış sayısı:<span class="incorrectAnswers">0</span>,
			Boş:<span class="emptyQuestions">0</span>
		</p>
	</div>
	<ol class="testPageOl">';
	foreach($o->items as $item){
		$variations='';
		foreach($item['variations'] as $v){
			$variations.='<li>
				<label>'.$v.':
					<input type="text" variation="'.$v.'" />
				</label>
			</li>';
		}
		$variations='<ul class="variations">'.$variations.'</ul>';
		
		echo '<li itemId="'.$item['id'].'">
			<strong>'.$item['word'].'</strong>
			'.$variations.'
			<input type="submit" value="Tamam" />
		</li>';
	}
	echo '</ol>';
	?>
</div>

