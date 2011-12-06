<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/turkishWritingTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/turkishWritingTest.js"></script>

<div class="englishWritingTest">
	<div class="testPageHeader">
		<h1>Türkçesini Yazma Testi</h1>
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
		$classes='';
		foreach($item->classes as $c){
			$classes.=$c.', ';
		}
		$classes=substr($classes,0,strlen($classes)-2);
		echo '<li>
			<p>
				<input class="wordId" type="hidden" value="'.$item->wordId.'" />
				<input class="answer" type="text" value="" />
				<span class="categories">['.$classes.']</span>
				<span class="meanings">'.$item->meaning.'</span>
			</p>
		</li>';
		
	}
	echo '</ol>';
	?>
</div>
