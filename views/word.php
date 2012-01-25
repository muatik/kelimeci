<?php
$w=$o->word;
?>
<div class="wordDetails" id="word<?php echo $w->id;?>">
	<input type="hidden" name="word" 
		value="<?php echo $w->word?>" />

	<?php
	/**
	 * hız kazandırması açısından betik ve sitil dosyaları yüklü bir
	 * sayfadan gelen çağrılarda tekrar yüklenmesi istenmeyebilir.
	 * */
	if(!isset($o->noScriptStyle)){
		echo '
		<script tyoe="text/javascript" src="js/words.js"></script>
		<script tyoe="text/javascript" src="js/vocabulary.js"></script>
		<link rel="stylesheet" type="text/css" href="css/clsBoxes.css" />
		<link rel="stylesheet" href="css/word.css" />
		<link rel="stylesheet" href="css/animbuttons.css" />
		
		';
	}
	?>

	<h1><?php 
		echo $w->word;
		if(isset($w->info['pronunciation']))
			echo ' <span class="pronunciation" 
			title="fonetik alfabede telaffuzu">/ 
			'.$w->info['pronunciation']->value
			.'</span>'; 

		// showing classes of the word
		echo '<span class="clsBoxes">';
			$classList=array(
				'v'=>array('f','Fiil','verb'),
				'n'=>array('i','İsim','noun'),
				'aj'=>array('s','Sıfat','adjective'),
				'av'=>array('z','Zarf','adverb'),
				'pp'=>array('e','Edat','preposition')
			);
			$wClasses=arrays::toArray($w->classes,'name');
			
			foreach($classList as $abbr=>$ci){
				$classActive=(in_array($ci[2],$wClasses)?'active':null);

				echo '<abbr class="'.$abbr.' '.$classActive
					.'" title="'.$ci[1].'">'.$ci[0].'</abbr>';
			}
		echo '</span>';
		

		echo (!$w->isInVocabulary || $w->status==0?
			'<a href="#" class="button green small addRemove add"
				title="Kelimeyi kelime dağarcığınıza ekler."
				>Sözlüğüne ekle</a>':
			'<a href="#" class="button gray small addRemove del"
				title="Kelimeyi kelime dağarcığından çıkartır."
				>Sözlüğünden çıkart</a>' );
	?>
	</h1>
	
	<div class="etymology"><?php 
		echo (isset($w->info['etymology'])
			?$w->info['etymology']->value:null);
	?></div>


	<div class="meanings">
	<?php
	
	// dillere göre gruplanarak yazılıyor
	$langMeaning=array();
	foreach($w->meanings as $m){
		$langMeaning[$m->lang][]=$m->meaning;
	}

	foreach($langMeaning as $lang=>$meanings){
		echo '<div class="langGroup lang'.$lang.'">
			<i class="lang '.$lang.'">'.$lang.' : </i>';

		if(count($meanings)>3)
			echo '<a href="#" 
			class="action more dontMove" alt="">hepsi...</a>';

		$pClass='';
		$i=1;
		echo '<ol class="meanings">';
		foreach($meanings as $m){
			if($i==4)
				$pClass='hidden';
			
			echo '<li class="meaning text '.$pClass.'">'.$m.'</li>';

			$i++;
		}
		echo '</ol>';

		echo '</div>';
	}

	?>
	</div>

	<div class="quotes">
		<h4>ALINTILAR</h4>
		
		<ul class="quotes">
		<?php
		// If there is any quotes
		if(isset($w->uQuotes))
			$quotes=array_merge($w->uQuotes,$w->quotes);
		else
			$quotes=$w->quotes;
		
		$i=0;
		$liClass='';
		$h='';
		foreach($quotes as $q){
			if($i>3)
				$liClass='hidden';

			$h.='<li class="'.$liClass.'">
				<blockquote>'
				.$q->quote.'</blockquote></li>';

			$i++;
		}
		
		echo str_replace($w->word,'<b>'.$w->word.'</b>',$h);

		?>
		</ul>
		
		
		<?php
			if($this->isLogined)
				echo '<a href="#" class="action add">Alıntı ekle</a>';
			if(count($quotes)>4)
				echo '<a href="#" class="action more seperator"
					alt="">hepsi...</a>';
			
			if($this->isLogined)
				echo '<div class="addForm">
					<input type="text" />
					<button>Ekle</button>
				</div>';
		?>
		
	</div>

	<!--
	<div class="variations">
		<h4>VARYASYONLAR</h4>
		<ul class="gray">
			<li><i>noun:</i><span>kelimenin isim hali</span></li>
			<li><i>verb:</i><span>kelimenin fiil hali</span></li>
		</ul>
	</div>
	-->

	<div class="synonyms">
		<h4 class="inline">EŞ:</h4>
		<span>
		<?php
		$h='';
		$length=13;
		$synonyms[0]=array_slice($w->synonyms,0,$length);
		
		foreach($synonyms[0] as $i)
			$h.='<a href="#" class="word">'.$i->word.',</a> ';

		//$h=substr($h,0,strlen($h)-2);

		if(count($w->synonyms)>$length){
			$h.='<a href="#" class="action more">hepsi...</a>';
			$synonyms[1]=array_slice($w->synonyms,$length);
			foreach($synonyms[1] as $i)
				$h.='<a href="" class="word hidden">'.$i->word.',</a> ';
		}

		echo $h;
		?> 
		</span>
	</div>
	<div class="antonyms">
		<h4 class="inline">ZIT:</h4>
		<span>
		<?php
		$h='';
		$length=13;
		$antonyms[0]=array_slice($w->antonyms,0,$length);
		
		foreach($antonyms[0] as $i)
			$h.='<a href="" class="word">'.$i->word.',</a> ';

		//$h=substr($h,0,strlen($h)-2);

		if(count($w->antonyms)>$length){
			$h.='<a href="#" class="action more">hepsi...</a>';
			$antonyms[1]=array_slice($w->antonyms,$length);
			foreach($antonyms[1] as $i)
				$h.='<a href="" class="word hidden">'.$i->word.',</a> ';
		}

		echo $h;
		?>
		</span>
	</div>

	<?php
	// If logined and not popup, show the status info.
	if($w->isInVocabulary)
	echo '
	<div>
		<h4>DURUM</h4>
		<div class="gray">
			<i>seviye:</i>
			<span>|-----------[]-----------|</span>
		</div>
		<div class="img">
			<!-- THIS DIV TAG WILL REPLACE TO IMG TAG -->
		</div>
	</div>';
	

	?>

</div>

<script type="text/javascript">
	new words('word<?php echo $w->id;?>');
</script>
