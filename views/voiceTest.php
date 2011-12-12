<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/voiceTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/voiceTest.js"></script>


<div class="voiceTest">
	<?php
	echo  $this->loadView(
		'testPageHeader.php',
		$o,
		false
	);
	echo '<ol class="testPageOl">';
	foreach($o->items as $item){
		echo '<li>
			<input class="wordId" type="hidden" value="'.$item->wordId.'" />
			<input class="voiceFile" type="hidden" value="'.$item->voiceFile.'" />
			<img class="voiceIcon" src="../images/speaker.png" />
			<input type="text" />
		</li>';	
	}
	echo '</ol>';
	?>
</div>

