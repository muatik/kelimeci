<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/testPage.css" />
<style type="text/css">
.synonymSelectionTest{
	padding:10px;
	width:600px;
}
.synonymSelectionTest a{
	text-decoration:underline;
}
.synonymSelectionTest span.alternatives span{
	margin-left:5px;
	cursor:pointer;
}
.synonymSelectionTest span.alternatives span.selected{
	background:#E5C532;
	border:1px solid #C8AE32;
	padding:1px;
}
.synonymSelectionTest span.alternatives span.correct{
	background:#8AEB6D;
	border:1px solid green;
	padding:1px;
}
.synonymSelectionTest span.alternatives span.incorrect{
	background:#FF8787;
	border:1px solid red;
	padding:1px;
}
</style>

<div class="synonymSelectionTest">
	<div class="testPageHeader">
		<h1>Eş Anlamlıları Seçme Testi</h1>
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
				<strong>excellent</strong>
				<span>=</span>
				<span class="alternatives">
					<span class="correct">outstanding</span>,
					<span class="incorrect">car</span>,
					<span>elegant</span>,
					<span>perfect</span>
				</span>
			</p>
			<input type="submit" value="Tamam" />
		</li>
		<li>
			<p>
				<strong>excellent</strong>
				<span>=</span>
				<span class="alternatives">
					<span>outstanding</span>,
					<span>car</span>,
					<span class="selected">elegant</span>,
					<span class="selected">perfect</span>
				</span>
			</p>
			<input type="submit" value="Tamam" />
		</li>
	</ol>
</div>
