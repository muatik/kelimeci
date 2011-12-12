<div class="testPageHeader">
	<h1><?php echo $o->titleInTr; ?></h1>
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
