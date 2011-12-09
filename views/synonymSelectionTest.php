<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/synonymSelectionTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/synonymSelectionTest.js"></script>

<div class="synonymSelectionTest">
	<div class="testPageHeader">
		<h1>Eş Anlamlıları Seçme Testi</h1>
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
		$synonyms='';
		foreach($item->options as $s){
			$synonyms.='<span>'.$s.'</span>, ';
		}
		$synonyms=substr($synonyms,0,strlen($synonyms)-1);
		echo '<li>
			<input class="wordId" type="hidden" value="'.$item->wordId.'" />
			<p>
				<strong>'.$item->word.'</strong>
				<span>=</span> 
				<span class="synonyms">'.$synonyms.'</span>
			</p>
			<input type="submit" value="Tamam" />
		</li>';
		
	}
	echo '</ol>';
	?>
</div>
