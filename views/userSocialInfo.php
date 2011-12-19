<?php
	// DUMMY DATA - BEGIN
	$o->compatibility=67;
	$o->chatability='Sohbete kapalı';
	$o->chatStatus='Çevrimdışı';
	// DUMMY DATA - END

	$compatibilityInfo=array(
		'percantageVal'=>0,
		'text'=>'UYUMLULUK %0'
	);
	if(isset($o->compatibility) && !empty($o->compatibility)){
		$compatibilityInfo['percantageVal']=$o->compatibility;
		$compatibilityInfo['text']='UYUMLULUK %'.$o->compatibility;
	}

	/**
	 * If not wanted to load css or js file, don't load
	 */
	if(!isset($o->noCss))
		echo '<link rel="stylesheet" type="text/css" href="../css/userInfo/userSocialInfo.css" />';

	if(!isset($o->noJs))
		echo '<script type="text/javascript" src="../js/userInfo/userSocialInfo.js"></script>';
?>


<link rel="stylesheet" type="text/css" href="../css/animbuttons.css" />

<div class="userSocialInfo">
	<p class="compatibility">
		<span class="status">
			<span class="chart" percantageval="<?php echo $compatibilityInfo['percantageVal']; ?>"></span>
			<span class="text"><?php echo $compatibilityInfo['text']; ?></span>
		</span>
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
