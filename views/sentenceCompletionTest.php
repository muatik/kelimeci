<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/sentenceCompletionTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/sentenceCompletionTest.js"></script>

<div class="sentenceCompletionTest testPage">	
	<?php
	echo  $this->loadView(
		'testPageHeader.php',
		$o,
		false
	);
	echo '<ol class="testPageOl">';
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
