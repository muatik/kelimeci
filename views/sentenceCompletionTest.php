<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/sentenceCompletionTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/sentenceCompletionTest.js"></script>

<div class="sentenceCompletionTest">	
	<div class="testPageHeader">
		<h1>Cümle Tamamlama Testi</h1>
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

	<ol class="testPageOl">
	<?php
	foreach($o->items as $item){
		
		// Replace '[...]' to '<input type="text" />'
		$sentence=preg_replace(
			'/\[\.\.\.\]/',
			'<input type="text" name="answer" />',
			$item->sentence
		);
		
		$clue='';
		foreach($item->clue as $c){
			$clue.=$c.', ';
		}
		$clue=substr($clue,0,strlen($clue)-2);

		echo '<li>
			<input type="hidden" name="wordId" value="'.$item->wordId.'" />
			<input type="hidden" name="quoteId" value="'.$item->quoteId.'" />
			<p>'.$sentence.'</p>
			<p class="clue"><span>İpucu:</span><i>'.$clue.'</i></p>
			</li>';
	}
	echo '</ol>';
	?>
</div>
