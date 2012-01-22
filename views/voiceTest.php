<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/voiceTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/voiceTest.js"></script>
<script type="text/javascript" src="../js/jplayer/jquery.jplayer.min.js"></script> 


<div class="voiceTest testPage">
	<div id="voiceTest.jPlayer"></div>
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
			<img class="voiceStatusImg" src="../images/speaker.png" />
			<input type="text" />
		</li>';	
	}
	echo '</ol>';
	?>
</div>

