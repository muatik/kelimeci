<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/variationWritingTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/variationWritingTest.js"></script>

<div class="variationWritingTest">
	<?php
	echo  $this->loadView(
		'testPageHeader.php',
		$o,
		false
	);
	echo '<ol class="testPageOl">';
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

