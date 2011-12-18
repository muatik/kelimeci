<?php
	header('content-type:text/html;charset=utf-8');
	// DUMMY DATA
	$o=new stdClass();
	$o->username='egulhan';
	$o->fname='Erman';
	$o->lname='Gülhan';
	$o->birthDate='1988/01/08';
	$o->sex='erkek';
	$o->city='İstanbul';
	$o->crtDate='2011/11/11';

	/**
	 * If not wanted to load css or js file, don't load
	 */
	if(!isset($o->noCss))
		echo '<link rel="stylesheet" type="text/css" href="../css/userInfo/userCommonInfo.css" />';
	/*
	if(!isset($o->noJs))
		echo '<script type="text/javascript" src="../js/userInfo/userCommonInfo.js"></script>';
	*/
	
	// Determine the avatar of sex
	$sexAvatar='/images/';
	$sexAvatar.=($o->sex=='erkek') ? 'male.png' : 'female.png';

	// Determine the exact age of user
	$bDateDiff=array(
		'year'=>date('Y')-date('Y',strtotime($o->birthDate)),
		'month'=>date('m')-date('m',strtotime($o->birthDate)),
		'day'=>date('d')-date('d',strtotime($o->birthDate))
	);
	if($bDateDiff['day']<0 || $bDateDiff['month']<0)
		$age=$bDateDiff['year']--;
	else
		$age=$bDateDiff['year'];
?>

<div class="userCommonInfo">
	<img src="<?php echo $sexAvatar ?>" class="avatar" />
	<div class="info">
		<p class="username"><?php echo $o->username; ?></p>
		<p class="fullName"><?php echo $o->fname.' '.$o->lname; ?></p>
		<p class="asl"><?php echo $age.' '.$o->sex.' '.$o->city; ?></p>
		<p class="registerDate"><?php echo $o->crtDate; ?></p>
		<p class="vocabulary">
			<span class="common"><?php echo '101 tane kelime biliyor.' ?></span>
			<span class="detail"><?php echo '(50 fiil, 1 zarf, 50 isim)' ?></span>
		</p>
	</div>
</div>

