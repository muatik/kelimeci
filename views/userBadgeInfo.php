<?php
	// DUMMY DATA
	$o=new stdClass();

	$badge=new stdClass();
	$badge->name='Lololooo';
	$badge->image='badge1.png';
	$badge->description='Lololooo is the best badge ever!';

	$o->badges=array();
	$o->badges[]=$badge;
	$o->badges[]=$badge;
	$o->badges[]=$badge;
	$o->badges[]=$badge;
	$o->badges[]=$badge;
	$o->badges[]=$badge;

	//print_r($o);

	/**
	 * If not wanted to load css or js file, don't load
	 */
	if(!isset($o->noCss))
		echo '<link rel="stylesheet" type="text/css" href="../css/userInfo/userBadgeInfo.css" />';
?>

<div class="userBadgeInfo">
	<h4>ROZETLERİ</h4>
	<div class="userBadges">
	<?php
	// If no badges
	if(count($o->badges)==0){
		echo '<div class="noBadges">Kullanıcının henüz hiç rozeti yok.</div>';
	}
	else{
		foreach($o->badges as $badge){
			echo '
			<div class="badge">
				<p class="name">'.$badge->name.'</p>
				<p class="image"><img src="../images/badges/'.$badge->image.'" /></p>
				<p class="description">'.$badge->description.'</p>
			</div>';
		}
	}
	?>
	</div>
</div>
