<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/testPage.css" />
<style type="text/css">
.voiceTest{
	padding:10px;
	width:600px;
}
.voiceTest ol li img.voice{
	
}
.voiceTest ol li img.playing{
	border:1px dotted gray;
}
/* Speaker image */
.voiceTest ol li *:first-child{
	cursor:pointer;
}
.voiceTest a{
	text-decoration:underline;
}
.voiceTest span.correction span{
	padding-right:15px;
	border-right:1px solid black;
}
.voiceTest span.correction strong:first-child{
	margin-left:0;
}
</style>

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/createXHR.js"></script>
<script type="text/javascript" src="../js/test.js"></script> 
<script type="text/javascript">
	$(document).ready(function(){

		var test=new Test('voiceTest');
		
		test.bindItems=function(){
			
			$('.testPageOl li input[type=text]').focusout(function(){
				
				if($(this).val()!=''){

					// Disable the input that is operated for
					$(this).attr('disabled',true);

					var params={
						wordId:$(this).parent().find('.wordId').val(),
						answer:$(this).val()
					};	

					test.checkAnswers(params);

				}

			});
			
			$('.testPageOl li img.voiceIcon').click(function(){
				
				// CODE FOR PLAYING THE VOICE FILE
				$(this).addClass('playing');
				
			});

		}

		test.afterChecked=function(rsp){
	
			if(rsp!=null){

				var resultInput=$(
					'.testPageOl input[class="wordId"][value="'+rsp.wordId+'"]'
				);
				
				var imgIncorrect='<img src="../images/incorrect.png" alt="" />',
					imgCorrect='<img src="../images/correct.png" alt="" />';
				
				// If the answer is correct
				if(rsp.result){
					$(resultInput).parent().append(imgCorrect);
				}
				// If the answer is incorrect
				else{
					$(resultInput).parent().append(imgIncorrect);
					var incorrect='';
					
					if(rsp.correction)
						incorrect='<strong>Yanlış:</strong>'+
							'<a href="#">'+rsp.correction+'</a>';
							
					var correction=$(
						'<span class="correction">'+
							'<strong>Doğrusu:</strong><span>'+rsp.answer+'</span>'
							+incorrect+							
						'</span>'
					);

					$(resultInput).parent().append(correction);
				}

			}

		}
		
		test.bindItems();
		
		test.startTimer();

	});

</script>

<div class="voiceTest">
	<div class="testPageHeader">
		<h1>Seslendirilen Kelimeyi Yazma Testi</h1>
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
		echo '<li>
			<input class="wordId" type="hidden" value="'.$item->wordId.'" />
			<input class="voiceFile" type="hidden" value="'.$item->voiceFile.'" />
			<img class="voiceIcon" src="../images/speaker.png" />
			<input type="text" />
		</li>';	
	}
	echo '</ol>';
	?>
</div>

