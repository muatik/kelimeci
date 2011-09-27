<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/testPage.css" />
<style type="text/css">
.turkishWritingTest{
	padding:10px;
	width:600px;
}
.turkishWritingTest input.correct{
	color:green;
}
.turkishWritingTest input.incorrect{
	color:red;
	text-decoration:line-through;
}
.turkishWritingTest a{
	text-decoration:underline;
}
.turkishWritingTest p.correction strong.incorrect{
	padding-left:15px;
	border-left:1px solid black;
}
.turkishWritingTest p.correction strong:first-child{
	margin-left:0;
}
</style>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/createXHR.js"></script>
<script type="text/javascript" src="../js/test.js"></script> 
<script type="text/javascript">
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
	
			//rsp=eval('('+rsp+')');	

			if(rsp!=null){

				var resultInput=$(
					'input[class="wordId"][value="'+rsp.wordId+'"]'
				).parent().find('input[class="answer"]');
				
				// If the answer is correct
				if(rsp.result){
					$(resultInput).addClass('correct');
				}
				// If the answer is incorrect
				else{
					$(resultInput).addClass('incorrect');
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
					$(resultInput).parent().append(correction);
				}

			}

		}
		test.bindItems();

		test.startTimer();
	});

</script>
<div class="englishWritingTest">
	<div class="testPageHeader">
		<h1>İngilizcesini Yazma Testi</h1>
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
	<?php
	echo '<ol class="testPageOl">';
	foreach($o->items as $item){
		$classes='';
		foreach($item->classes as $c){
			$classes.=$c.', ';
		}
		$classes=substr($classes,0,strlen($classes)-2);
		echo '<li>
			<p>
				<input class="wordId" type="hidden" value="'.$item->wordId.'" />
				<input class="answer" type="text" value="" />
				<span class="categories">['.$classes.']</span>
				<span class="meanings">'.$item->meaning.'</span>
			</p>
		</li>';
		
	}
	echo '</ol>';
	?>
</div>
