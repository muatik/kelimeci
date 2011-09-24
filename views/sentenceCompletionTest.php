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

					var params={
						itemId:$(this).parent().parent().attr('itemId'),
						answer:$(this).val()
					};	

					test.checkAnswers(params);

				}

			});

		}

		test.afterChecked=function(rsp){
	
			rsp=eval('('+rsp+')');	

			if(rsp!=''){

				var resultInput=$(
					'.testPageOl li[itemId='+rsp.itemId+'] input[type=text]'
				);
				
				// If the answer is correct
				if(rsp.result){
					$(resultInput).addClass('correct');
					test.incrementCorrectCounter();
					$('.testPageHeader .correctAnswers').html(test.correctAnswerCounter);
				}
				// If the answer is incorrect
				else{
					$(resultInput).addClass('incorrect');
					test.incrementIncorrectCounter();
					$('.testPageHeader .incorrectAnswers').html(test.incorrectAnswerCounter);
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
		test.ajaxFile='../dummyData/sentenceCompletionTest.php';

		test.bindItems();

	});

</script>

<div class="sentenceCompletionTest">	
	<?php
	require('../dummyData/sentenceCompletionTest.php');

	echo '<div class="testPageHeader">
		<h1>Cümle Tamamlama Testi</h1>
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
		
		// Replace '[...]' to '<input type="text" />'
		$sentence=preg_replace('/\[\.\.\.\]/','<input type="text" />',$item['sentence']);
		
		$clue='';
		foreach($item['clue'] as $c){
			$clue.=$c.', ';
		}
		$clue=substr($clue,0,strlen($clue)-2);
		echo '<li itemId="'.$item['id'].'">
			<p>'.$sentence.'</p>
			<p class="clue"><span>İpucu:</span><i>'.$clue.'</i></p>
		</li>';
		
	}
	echo '</ol>';
	?>
	<!--
	<div class="testPageHeader">
		<h1>Cümle Tamamlama Testi</h1>
		<p>
			Toplam soru:<span class="totalQuestions">21</span>,
			Tahmini süre:<span class="estimatedTime">10 dakika</span>,
		</p>
		<p>
			Geçen süre:<span class="spentTime">00:00:05</span>,
			Doğru sayısı:<span class="correctAnswers">0</span>,
			Yanlış sayısı:<span class="incorrectAnswers">1</span>,
			Boş:<span class="emptyQuestions">11</span>
		</p>
	</div>
	<ol class="testPageOl">
		<li>
			<p>This is not<input type="text" class="correct" value="go" />of us.</p>
			<p class="hint"><span>ipucu:</span><i>car, bike, go</i></p>
		</li>
		<li>
			<p>Where are<input type="text" class="incorrect" value="I" />going?</p>
			<p class="hint"><span>ipucu:</span><i>I, you, he</i></p>
			<p>Doğrusu:<a href="#">you</a></p>
		</li>
	</ol>
	-->
</div>
