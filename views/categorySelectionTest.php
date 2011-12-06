<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/categorySelectionTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/categorySelectionTest.js"></script>

<div class="categorySelectionTest">
	<div class="testPageHeader">
		<h1>Kategori Seçme Testi</h1>
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
	$categoriesArr=array('verb','noun','adjective','adverb','preposition');
	$categories='';
	foreach($categoriesArr as $c){
			$categories.='<li><span>'.$c.'</span></li>';
	}
	$categories='<ul class="categories">'.$categories.'</ul>';
	foreach($o->items as $item){
		echo '<li>
				<input class="wordId" type="hidden" value="'.$item->wordId.'" /> 
				<strong>'.$item->word.'</strong>
				'.$categories.'
				<input type="submit" value="Tamam" />
		</li>';	
	}
	echo '</ol>';
	?>
</div>
