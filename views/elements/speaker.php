<?php
	if(!isset($o->noScriptStyle)){
		echo '
		<script type="text/javascript" src="../js/jplayer/jquery.jplayer.min.js"></script>
		<link rel="stylesheet" type="text/css" href="../css/speaker.css" />
		<script type="text/javascript" src="../js/speaker.js"></script>
		';
	}

	// Generate a unique number for id
	$uniqueId=time();
	$contId='speaker'.$uniqueId;
	$jPlayerId='jplayer'.$uniqueId;

	// If there is the media file to play, show the html
	if($o->mediaFile){
		$autoPlay=(isset($o->autoPlay) && $o->autoPlay) ? 'true' : 'false';

		echo '
		<span id="'.$contId.'" class="speaker">
			<a href="#" class="speaker">
				<img src="../images/speaker/speakerPlay.png" alt="" />
			</a>
			<span id="'.$jPlayerId.'" class="speakerJPlayer"></span>
			<input type="hidden" name="mediaFile" value="'.$o->mediaFile.'" />
			<input type="hidden" name="autoPlay" value="'.$autoPlay.'" />
		</span>';

		echo '
			<script type="text/javascript">
				new Speaker(\''.$contId.'\');
			</script>
		';
	}
?>
