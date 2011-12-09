<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/voiceTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/voiceTest.js"></script>


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

