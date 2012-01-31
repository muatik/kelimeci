<?php
if(!isset($o->noScriptStyle)){
	echo '<link rel="stylesheet" type="text/css" href="css/wordList.css" />';
	echo '<link rel="stylesheet" type="text/css" href="css/clsBoxes.css" />';
	echo '<script type="text/javascript" src="js/wordList.js"></script>';
}

if(!isset($o->noAllInterface)){
	echo getAllInterface($o->words);
}
else{
	echo getWordList($o->words);
}

function getAllInterface($words){

	$wList=getWordList($words);

	return '
	<div class="wordList">
		<h2>KELİMELER LİSTELENİYOR</h2>
		
		<div class="wordsForm">
			
			<label class="toggle">
				<input type="checkbox" name="checkAll" />
				Hepsi / Hiçbiri</label>
			<button>Seçili olanları sil</button>
		</div>

		<ul class="words">'.$wList.'</ul>

		<div class="infSclIndicator" style="display:none;">
			<img src="../images/loading.gif" alt="" />
			KELİMELER YÜKLENİYOR...
		</div>

		<div class="wordListNav">
			<a href="?_ajax=vocabular/viewwordList"></a>
		</div>
	</div>';

}

function getWordList($words){

	// If not array, return the error message
	if(!is_array($words));
		echo $words;

	$classList=array(
		'v'=>array('f','Fiil','verb'),
		'n'=>array('i','İsim','noun'),
		'aj'=>array('s','Sıfat','adjective'),
		'av'=>array('z','Zarf','adverb'),
		'pp'=>array('e','Edat','preposition')
	);

	$wList='';

	foreach($words as $i){
		$classes=arrays::toArray($i->classes,'name');
		if(!is_array($classes))
			$classes=array();

		$wList.='
			<li>
				<input type="checkbox" class="wordIds" name="ids[]" 
					value="'.$i->id.'" />
				<span class="clsBoxes">';
				foreach($classList as $abbr=>$ci){
					$classActive=(in_array($ci[2],$classes)?'active':null);

					$wList.='<abbr class="'.$abbr.' '.$classActive
						.'">'.$ci[0].'</abbr>';
				}
				$wList.='
				</span>

				<span class="level">'.$i->level.'</span>
				<span class="word">'.$i->word.'</span>
			</li>
		';
	}

	return $wList;

}

?>
