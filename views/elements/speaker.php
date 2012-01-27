<?php
	// If there is the media file to play, show the html
	if($o->mediaFile){
	echo '
		<!--<link rel="stylesheet" type="text/css" href="../css/speaker.css" />-->
		<!--<script type="text/javascript" src="../js/speaker.js"></script>-->
		<div class="speaker">
			<a href="#" class="speaker">
				<img src="../images/speaker/speakerOn.png" alt="" />
			</a>
			<input type="hidden" name="mediaFile" value="'.$o->mediaFile.'" />
		</div>';
	}
?>
