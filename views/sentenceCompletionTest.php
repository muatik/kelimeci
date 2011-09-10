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
.sentenceCompletionTest .testPageOl li p span:first-child{
	margin-left:0px;
}
</style>

<div class="sentenceCompletionTest">
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
</div>
