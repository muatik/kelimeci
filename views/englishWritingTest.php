<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/testPage.css" />
<style type="text/css">
.englishWritingTest{
	padding:10px;
	width:600px;
}
.englishWritingTest input.correct{
	color:green;
}
.englishWritingTest input.incorrect{
	color:red;
	text-decoration:line-through;
}
.englishWritingTest a{
	text-decoration:underline;
}
.englishWritingTest p.correction span{
	padding-right:15px;
	border-right:1px solid black;
}
.englishWritingTest p.correction strong:first-child{
	margin-left:0;
}
</style>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/createXHR.js"></script>
<script type="text/javascript" src="../js/test.js"></script> 
<script type="text/javascript">
	$(document).ready(function(){

		var test=new Test('englishWritingTest');

		test.bindItems=function(){
			$('input.answer').focusout(function(){
				
				if($(this).val()!=''){

					// Disable the input that is operated for
					$(this).attr('disabled',true);

					var params={
						'wordId':$(this).parent().find('input.wordId').attr('itemId'),
						'answer':$(this).val()
					};	
					test.checkAnswers(params);

				}

			});

		}

		test.afterChecked=function(rsp){
	
			rsp=eval('('+rsp+')');	

			if(rsp!=''){

				var resultInput=$(
					'.input[class=wordId][value='+rsp.wordId+']'
				);
				
				// If the answer is correct
				if(rsp.result){
					$(resultInput).addClass('correct');
					$('.testPageHeader .correctAnswers').html(test.correctAnswerCounter);
				}
				// If the answer is incorrect
				else{
					$(resultInput).addClass('incorrect');
					$('.testPageHeader .incorrectAnswers').html(test.incorrectAnswerCounter);
					var correction=$(
						'<p class="correction">'+
							'<strong>Doğrusu:</strong><span>'+rsp.answer+'</span>'+
							'<strong>Yanlış:</strong>'+
							'<a href="#">'+rsp.correction+'</a>'+
						'</p>'
					);

					$(resultInput).parent().append(correction);
				}

			}

		}
		test.bindItems();

		test.startTimer();
	});

</script>
<div class="englishWritingTest">
	<?php

	echo '<div class="testPageHeader">
		<h1>İngilizcesini Yazma Testi</h1>
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
		$classes='';
		foreach($item->classes as $c){
			$classes.=$c.', ';
		}
		$classes=substr($classes,0,strlen($classes)-2);
		echo '<li>
			<input class="wordId" type="hidden" value="'.$item->wordId.'" />
			<p>
				<input class="answer" type="text" value="" />
				<span class="categories">['.$classes.']</span>
				<span class="meanings">'.$item->defination.'</span>
			</p>
		</li>';
		
	}
	echo '</ol>';
	?>
</div>
