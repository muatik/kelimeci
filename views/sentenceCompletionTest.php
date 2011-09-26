<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/testPage.css" />
<style type="text/css">
.sentenceCompletionTest{
	padding:10px;
	width:600px;
}
.sentenceCompletionTest input{
	margin:0 8px 0 8px;
}
.sentenceCompletionTest input.correct{
	color:green;
}
.sentenceCompletionTest input.incorrect{
	color:red;
	text-decoration:line-through;
}
.sentenceCompletionTest a{
	text-decoration:underline;
}
.sentenceCompletionTest p.correction span{
	padding-right:15px;
	border-right:1px solid black;
}
.sentenceCompletionTest p.correction strong:first-child{
	margin-left:0;
}
.sentenceCompletionTest .testPageOl li p span:first-child{
	margin-left:0px;
}
</style>

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/createXHR.js"></script>
<script type="text/javascript" src="../js/test.js"></script> 
<script type="text/javascript">
	$(document).ready(function(){

		// 'englishWritingTest' replaced to the test name that
		// comes from the server
		var test=new Test('sentenceCompletionTest');

		test.showTime=function(){
			$('.spentTime').html(test.elapsedTime);	
		}

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
		
			rsp=jQuery.parseJSON(rsp);
			
			if(rsp!=''){

				var resultInput=$(
					'input[name="answer"]',
					$('input[value='+rsp.wordId+']').parent()
				);
				
				// If the answer is correct
				if(rsp.result){
					$(resultInput).addClass('correct');
					test.incrementCorrectCounter();
					$('.testPageHeader .correctAnswers')
						.html(test.correctAnswerCounter);
				}
				// If the answer is incorrect
				else{
					$(resultInput).addClass('incorrect');
					test.incrementIncorrectCounter();
					$('.testPageHeader .incorrectAnswers')
						.html(test.incorrectAnswerCounter);
					var correction=$(
						'<p class="correction">'+
							'<strong>Doğrusu:</strong><span>'+rsp.answer+'</span>'+
							'<strong>Yanlış:</strong>'+
							'<a href="#">'+rsp.correction+'</a>'+
						'</p>'
					);

					$(resultInput).parent().parent().append(correction);
				}


			}

		}
		
		test.startTimer();

		// DELETE THIS LINE
		test.ajaxFile='tests/?_ajax=validate';

		test.bindItems();

	});

</script>

<div class="sentenceCompletionTest">	
	<div class="testPageHeader">
		<h1>Cümle Tamamlama Testi</h1>
		<p>
			Toplam soru:<span class="totalQuestions">
				<?php echo count($o->items);?></span>,
			Tahmini süre:<span class="estimatedTime">
				<?php echo $o->estimatedTime;?></span>,
		</p>
		<p>
			Geçen süre:<span class="spentTime">00:00:00</span>,
			Doğru sayısı:<span class="correctAnswers">0</span>,
			Yanlış sayısı:<span class="incorrectAnswers">0</span>,
			Boş:<span class="emptyQuestions">0</span>
		</p>
	</div>

	<ol class="testPageOl">
	<?php
	$i=0;
	foreach($o->items as $item){
		
		// Replace '[...]' to '<input type="text" />'
		$sentence=preg_replace(
			'/\[\.\.\.\]/',
			'<input type="text" name="answer" />',
			$item->sentence
		);
		
		$clue='';
		foreach($item->clue as $c){
			$clue.=$c.', ';
		}
		$clue=substr($clue,0,strlen($clue)-2);

		echo '<li>
			<input type="hidden" name="wordId" value="'.$item->wordId.'" />
			<input type="hidden" name="quoteId" value="'.$item->quoteId.'" />
			<p>'.$sentence.'</p>
			<p class="clue"><span>İpucu:</span><i>'.$clue.'</i></p>
			</li>';

		$i++;
	}
	echo '</ol>';
	?>
</div>
