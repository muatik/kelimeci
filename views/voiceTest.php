<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/testPage.css" />
<style type="text/css">
.voiceTest{
	padding:10px;
	width:600px;
}
.voiceTest ol li .result{
	/*display:none;*/
}
/* Speaker image */
.voiceTest ol li *:first-child{
	cursor:pointer;
}
</style>

<div class="voiceTest">
	<div class="testPageHeader">
		<h1>Seslendirilen Kelimeyi Yazma Testi</h1>
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
		<li>
			<img src="../images/speaker.png" />
			<input type="text" />
			<span class="result">
				<img src="../images/correct.png" />
			</span>
		</li>
	</ol>
</div>

