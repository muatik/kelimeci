<?php
	header('content-type:text/html;charset=utf-8');
	// DUMMY DATA
	$o=new stdClass();
	$o->conformity='Uyumluluk %67';
	$o->chatability='Sohbete kapalı';
	$o->chatStatus='Çevrimdışı';

	/**
	 * If not wanted to load css or js file, don't load
	 */
	if(!isset($o->noCss))
		echo '<link rel="stylesheet" type="text/css" href="../css/userInfo/userSocialInfo.css" />';
	/*
	if(!isset($o->noJs))
		echo '<script type="text/javascript" src="../js/userInfo/userSocialInfo.js"></script>';
	*/
?>


<link rel="stylesheet" type="text/css" href="../css/animbuttons.css" />

<div class="userSocialInfo">
	<p class="conformity">
		<span class="status"><?php echo $o->conformity; ?></span>
		<a href="#" class="whatIsThis">bu nedir?</a>
	</p>
	<p class="chat">
		<a href="#" class="haveChat button blue small">Sohbet et</a>
		<div class="info">
			<span class="chatability"><?php echo $o->chatability; ?></span>
			<span class="status"><?php echo $o->chatStatus; ?></span>
		</div>
	</p>
</div>
