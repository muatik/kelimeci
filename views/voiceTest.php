<link rel="stylesheet" type="text/css" href="../css/tests/testPage.css" />
<link rel="stylesheet" type="text/css" href="../css/tests/voiceTest.css" />

<script type="text/javascript" src="../js/tests/test.js"></script> 
<script type="text/javascript" src="../js/tests/voiceTest.js"></script>
<script type="text/javascript" src="../js/jplayer/jquery.jplayer.min.js"></script>
<link rel="stylesheet" type="text/css" href="../css/speaker.css" />
<script type="text/javascript" src="../js/speaker.js"></script>


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

		// Speaker
		$o2=new stdClass();
		$o2->mediaFile='../audio/words/1.mp3';
		$o2->autoPlay=false;
		$o2->noScriptStyle=true;
		$speaker=$this->loadElement('speaker.php',$o2);

		echo '<li>
			<input class="wordId" type="hidden" value="'.$item->wordId.'" />
			'.$speaker.'
			<input type="text" />
		</li>';	
	}
	echo '</ol>';
	?>
</div>

