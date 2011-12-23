<?php
	/**
	 * If not wanted to load css or js file, don't load
	 */
	if(!isset($o->noCss))
		echo '<link rel="stylesheet" type="text/css" href="../../css/popupWordDetail.css" />';
	if(!isset($o->noJs))
		echo '<script type="text/javascript" src="../../js/popupWordDetail.js"></script>';
?>

<div id="popupWordDetail"></div>
