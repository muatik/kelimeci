<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/variationWritingTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/variationWritingTest.js"></script>

<div class="variationWritingTest">
	<div class="testPageHeader">
		<h1>Kelimenin Varyasyonlarını Yazma</h1>
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
		$variations='';
		foreach($item->variations as $v){
			$variations.='<li>
				<label>'.$v.':
					<input name="variation" value="'.$v.'" 
						type="hidden" />
					<input type="text" name="answer" />
				</label>
			</li>';
		}
		$variations='<ul class="variations">'.$variations.'</ul>';
		
		echo '<li>
			<input name="wordId" value="'.$item->wordId.'" type="hidden" />
			<strong>'.$item->word.'</strong>
			'.$variations.'
			<input type="submit" value="Tamam" />
		</li>';
	}
	echo '</ol>';
	?>
</div>

