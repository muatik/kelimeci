<div id="popupWordDetail">
	<a href="#" class="close" title="Kapat">X</a>
	<div class="ai"><img src="../images/loading.gif" alt="" /></div>
	<div class="content"></div>
</div>

<?php
	/**
	 * If not wanted to load css or js file, don't load
	 */
	if(!isset($o->noCss))
		echo '<link rel="stylesheet" type="text/css" href="../../css/popupWordDetail.css" />';
	if(!isset($o->noJs))
		echo '<script type="text/javascript" src="../../js/popupWordDetail.js"></script>';
?>

