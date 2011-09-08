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
</style>

<div class="englishWritingTest">
	<div class="testPageHeader">
		<h1>İngilizcesini Yazma Testi</h1>
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
			<p>
				<input type="text" class="incorrect" value="schedule" />
				<span class="categories">[v, n]</span>
				<span class="meanings">
					perfect, elegant, outstanding
				</span>
			</p>
			<p class="incorrect">
				<strong>Doğrusu:</strong><a href="#">doğru kelime</a>
				<span class="seperator">|</span>
				<strong>Yanlış:</strong>
				<span class="meaningOfIncorrect">program</span>
			</p>
		</li>
		<li>
			<p>
				<input type="text" class="correct" value="excellent" />
				<span class="categories">[v, n]</span>
				<span class="meanings">
					perfect, elegant, outstanding
				</span>
			</p>
		</li>
	</ol>
</div>
