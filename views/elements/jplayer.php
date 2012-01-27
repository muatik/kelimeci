<?php
	/**
	 * If not wanted to load css or js file, don't load
	 */
	if(!isset($o->noJs)){
		echo '
		<script type="text/javascript" src="../js/jplayer/jquery.jplayer.min.js"></script>
		<script type="text/javascript" src="../js/jplayer/jpInitOps.js"></script>
		';
	}

	$jplayerId=($o->jplayer->id) ? $o->jplayer->id : 'jplayer';
?>
<div id="<?php echo $jplayerId ?>"></div>
