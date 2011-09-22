<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/testPage.css" />
<style type="text/css">
.categorySelectionTest{
	padding:10px;
	width:600px;
}
.categorySelectionTest a{
	text-decoration:underline;
}
.categorySelectionTest li span{
	margin-left:5px;
	cursor:pointer;
	display:inline-block;
	width:100px;
	border:1px solid white;
	padding:2px;
}
.categorySelectionTest li span.selected{
	background:#E5C532;
	border:1px solid #C8AE32;
	padding:2px;
}
.categorySelectionTest li span.correct{
	color:green;
}
.categorySelectionTest li span.incorrect{
	color:red;
	text-decoration:line-through;
}
.categorySelectionTest li span.unselectedCorrect{
	background:green;/*#8AEB6D;*/
	border:1px solid darkgreen;
	padding:2px;
	color:white;
}
.categorySelectionTest input[type=submit]{
	margin-left:20px;
}
</style>

<div class="categorySelectionTest">
	<div class="testPageHeader">
		<h1>Kelimenin Türlerini Seçme Testi</h1>
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
			<strong>access</strong>
			<ul>
				<li>
					<span class="selected correct">Verb</span>
					<img src="../images/correct.png" alt="" />
				</li>
				<li>
					<span class="unselectedCorrect">Noun</span>
					<img src="../images/incorrect.png" alt="" />
				</li>
				<li><span>Adjective</span></li>
				<li><span>Adverb</span></li>
				<li>
					<span class="selected incorrect">Preposition</span>
					<img src="../images/incorrect.png" alt="" />
				</li>
			</ul>
			<input type="submit" value="Tamam" />
		</li>
		<li>
			<strong>hold</strong>
			<ul>
				<li><span class="selected">Verb</span></li>
				<li><span>Noun</span></li>
				<li><span>Adjective</span></li>
				<li><span>Adverb</span></li>
				<li><span>Preposition</span></li>
			</ul>
			<input type="submit" value="Tamam" />
		</li>
	</ol>
</div>
