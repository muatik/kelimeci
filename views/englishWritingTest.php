<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/englishWritingTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/englishWritingTest.js"></script>

<div class="englishWritingTest testPage">
	<?php
	echo  $this->loadView(
		'testPageHeader.php',
		$o,
		false
	);
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
