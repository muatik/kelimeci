<link rel="stylesheet" type="text/css" href="css/wordList.css" />

<div class="wordList">
	<h2>KELİMELER LİSTELENİYOR</h2>
	<ul class="words">
	<?php
	$words=$o;
	$classList=array(
		'v'=>'verb',
		'n'=>'noun',
		'aj'=>'adjective',
		'av'=>'adverb',
		'pp'=>'prepososition'
	);
	foreach($words as $i){
		$classes=arrays::toArray($i->classes,'name');
		echo '
			<li>
				<span class="categories">';
				foreach($classList as $abbr=>$ci){
					if(in_array($ci,$classes))
						$classActive='active';
					else
						$classActive=null;

					echo '<span class="'.$abbr.' '
						.$classActive.'">'
						.$abbr.'</span>';
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
