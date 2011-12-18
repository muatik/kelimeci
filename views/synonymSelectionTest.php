<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/synonymSelectionTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/synonymSelectionTest.js"></script>
<script type="text/javascript" src="../js/jquery.scrollTo.js"></script>

<div class="synonymSelectionTest testPage">
	<?php
	echo  $this->loadView(
		'testPageHeader.php',
		$o,
		false
	);
	echo '<ol class="testPageOl">';
	foreach($o->items as $item){
		$synonyms='';
		foreach($item->options as $s){
			$synonyms.='<span>'.$s.'</span>, ';
		}
		$synonyms=substr($synonyms,0,strlen($synonyms)-2);
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
