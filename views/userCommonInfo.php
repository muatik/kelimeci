<?php
	/**
	 * If not wanted to load css or js file, don't load
	 */
	if(!isset($o->noCss))
		echo '<link rel="stylesheet" type="text/css" href="../css/userInfo/userCommonInfo.css" />';

	// Determine the sex and its avatar
	$sexAvatar='/images/';
	if(!isset($o->sex) || $o->sex==''){
		$sexAvatar.='missing.png';
		$o->sex='';
	}
	else{
		$sexAvatar.=($o->sex=='erkek') ? 'male.png' : 'female.png';
	}

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

	//$o->vocabularyStats=array(array('2','fiil'),array('5','sıfat'));

	// Prepare the vocab. info.
	$vocabInfo=array('common'=>'0 kelime biliyor.','detail'=>'');
	
	// Set the common and detail vocab. info, if there is
	if($o->vocabularyStats!==false && count($o->vocabularyStats)>0){
		// Reset the counter
		$vocabInfo['common']=0;

		foreach($o->vocabularyStats as $v){
			if(is_array($v)){
				$vocabInfo['common']+=$v[0];
				$vocabInfo['detail'].=implode(' ',$v).', ';
			}
		}

		// Remove the redundant characters(eg. white space, comma)
		$vocabInfo['detail']=substr(
			$vocabInfo['detail'],
			0,
			strlen($vocabInfo['detail'])-2
		);
		
		// Prepare the info. to show
		$vocabInfo['common']=$vocabInfo['common'].' kelime biliyor.';
		$vocabInfo['detail']='('.$vocabInfo['detail'].')';
	}
?>

<div class="userCommonInfo">
	<img src="<?php echo $sexAvatar ?>" class="avatar" />
	<div class="info">
		<p class="username"><?php echo $o->username; ?></p>
		<p class="fullName"><?php echo $o->fname.' '.$o->lname; ?></p>
		<p class="asl"><?php echo $age.' '.$o->sex.' '.$o->city; ?></p>
		<p class="registerDate"><?php echo 'Kayıt: '.$o->crtDate; ?></p>
		<p class="vocabulary">
			<span class="common"><?php echo $vocabInfo['common']; ?></span>
			<span class="detail"><?php echo $vocabInfo['detail']; ?></span>
		</p>
	</div>
</div>

