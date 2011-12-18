<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/categorySelectionTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/categorySelectionTest.js"></script>

<div class="categorySelectionTest testPage">
	<?php
	echo  $this->loadView(
		'testPageHeader.php',
		$o,
		false
	);
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
