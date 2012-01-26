<?php
if(!isset($o->noScriptStyle)){
	echo '<link rel="stylesheet" type="text/css" href="css/wordList.css" />';
	echo '<link rel="stylesheet" type="text/css" href="css/clsBoxes.css" />';
	echo '<script type="text/javascript" src="js/wordList.js"></script>';
}
?>
<div class="wordList">
	<h2>KELİMELER LİSTELENİYOR</h2>
	
	<div class="wordsForm">
		
		<label class="toggle">
			<input type="checkbox" name="checkAll" />
			Hepsi / Hiçbiri</label>
		<button>Seçili olanları sil</button>
	</div>

	<ul class="words">
	<?php
	$words=$o->words;
	$classList=array(
		'v'=>array('f','Fiil','verb'),
		'n'=>array('i','İsim','noun'),
		'aj'=>array('s','Sıfat','adjective'),
		'av'=>array('z','Zarf','adverb'),
		'pp'=>array('e','Edat','preposition')
	);

	foreach($words as $i){
		$classes=arrays::toArray($i->classes,'name');
		if(!is_array($classes))
			$classes=array();
		echo '
			<li>
				<input type="checkbox" class="wordIds" name="ids[]" 
					value="'.$i->id.'" />
				<span class="clsBoxes">';
				foreach($classList as $abbr=>$ci){
					$classActive=(in_array($ci[2],$classes)?'active':null);

					echo '<abbr class="'.$abbr.' '.$classActive
						.'">'.$ci[0].'</abbr>';
				}
				echo '
				</span>

				<span class="level">'.$i->level.'</span>
				<span class="word">'.$i->word.'</span>
			</li>
		';
	}
	?>
	</ul>
</div>
