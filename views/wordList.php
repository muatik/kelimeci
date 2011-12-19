<?php
if(!isset($o->noScriptStyle)){
	echo '<link rel="stylesheet" type="text/css" href="css/wordList.css" />';
	echo '<script type="text/javascript" src="js/wordList.js"></script>';
}
?>
<div class="wordList">
	<h2>KELİMELER LİSTELENİYOR</h2>
	<ul class="words">
	<?php
	$words=$o->words;
	$classList=array(
		'v'=>'verb',
		'n'=>'noun',
		'aj'=>'adjective',
		'av'=>'adverb',
		'pp'=>'preposition'
	);
	foreach($words as $i){
		$classes=arrays::toArray($i->classes,'name');
		if(!is_array($classes))
			$classes=array();
		echo '
			<li>
				<span class="categories">';
				foreach($classList as $abbr=>$ci){
					if(in_array($ci,$classes))
						$classActive='active';
					else
						$classActive=null;

					echo '<abbr class="'.$abbr.' '
						.$classActive.'" title="'.$ci.'">'
						.$abbr.'</abbr>';
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
