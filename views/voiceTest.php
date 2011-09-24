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

		// 'voice' replaced to the test name that
		// comes from the server
		var test=new Test('voiceTest');

		test.showTime=function(){
			$('.spentTime').html(test.elapsedTime);	
		}

		test.bindItems=function(){
			
			$('.testPageOl li input[type=text]').focusout(function(){
				
				if($(this).val()!=''){

					// Disable the input that is operated for
					$(this).attr('disabled',true);

					var params={
						itemId:$(this).parent().attr('itemId'),
						answer:$(this).val()
					};	

					test.checkAnswers(params);

				}

			});
			
			$('.testPageOl li img.voice').click(function(){
				
				// CODE FOR PLAYING THE VOICE FILE
				$(this).addClass('playing');
				
			});

		}

		test.afterChecked=function(rsp){
	
			rsp=eval('('+rsp+')');	

			if(rsp!=''){

				var resultInput=$(
					'.testPageOl li[itemId='+rsp.itemId+'] input[type=text]'
				);
				
				var imgIncorrect='<img src="../images/incorrect.png" alt="" />',
					imgCorrect='<img src="../images/correct.png" alt="" />';
				
				// If the answer is correct
				if(rsp.result){
					$(resultInput).parent().append(imgCorrect);
					test.incrementCorrectCounter();
					$('.testPageHeader .correctAnswers').html(test.correctAnswerCounter);
				}
				// If the answer is incorrect
				else{
					$(resultInput).parent().append(imgIncorrect);
					test.incrementIncorrectCounter();
					$('.testPageHeader .incorrectAnswers').html(test.incorrectAnswerCounter);
					var correction=$(
						'<span class="correction">'+
							'<strong>Doğrusu:</strong><span>'+rsp.answer+'</span>'+
							'<strong>Yanlış:</strong>'+
							'<a href="#">'+rsp.correction+'</a>'+
						'</span>'
					);

					$(resultInput).parent().append(correction);
				}

			}

		}
		
		test.startTimer();

		// DELETE THIS LINE
		test.ajaxFile='../dummyData/voiceTest.php';

		test.bindItems();

	});

</script>

<div class="voiceTest">
	<?php
	require('../dummyData/voiceTest.php');

	echo '<div class="testPageHeader">
		<h1>Seslendirilen Kelimeyi Yazma Testi</h1>
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
		echo '<li itemId="'.$item['id'].'">
			<img class="voice" src="../images/speaker.png" />
			<input type="text" />
		</li>';	
	}
	echo '</ol>';
	?>
	<!--
	<ol class="testPageOl">
		<li>
			<img src="../images/speaker.png" />
			<input type="text" />
			<span class="result">
				<img src="../images/correct.png" />
			</span>
		</li>
		<li>
			<img src="../images/speaker.png" />
			<input type="text" />
			<span class="result">
				<img src="../images/correct.png" />
			</span>
		</li>
	</ol>
	-->
</div>

