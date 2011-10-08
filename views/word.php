<?php
$w=$o;
?>
<div class="wordDetails" id="word<?php echo $w->id;?>">
	<input type="hidden" name="word" 
		value="<?php echo $w->word?>" />
	<script tyoe="text/javascript" src="js/words.js"></script>
	<link rel="stylesheet" href="css/word.css" />
	<h1><?php echo $w->word;?></h1>
	
	<div class="meanings">
	<?php
	$quotes=array_merge($w->uQuotes,$w->quotes);
	$i=0;
	$pClass='';
	foreach($w->meanings as $m){
		if($i>3)
			$pClass='hidden';
		
		echo '<p class="text '.$pClass.'">
			<i class="lang">'.$m->lang.': </i>'
			.$m->meaning.'</p>';

		$i++;
	}
	
	if(count($w->meanings)>3)
		echo '<a href="#" class="action more"
			alt="">hepsi...</a>';

	?>
	</div>

	<div class="classes">
		<h4 class="inline">KATEGORİ:</h4>
		<span>
		<?php
		echo implode(', ',arrays::toArray($w->classes,'name'))
		?>
		</span>
	</div>

	<div class="quotes">
		<h4>ALINTILAR</h4>
		
		<ul class="quotes">
		<?php
		$quotes=array_merge($w->uQuotes,$w->quotes);
		$i=0;
		$liClass='';
		foreach($quotes as $q){
			if($i>3)
				$liClass='hidden';

			echo '<li class="'.$liClass.'">
				<blockquote class="text">'
				.$q->quote.'</blockquote></li>';

			$i++;
		}
		?>
		</ul>
		
		<a href="#" class="action add">Alıntı ekle</a>
		<?php
		if(count($quotes)>4)
			echo '<a href="#" class="action more seperator"
				alt="">hepsi...</a>';
		?>
		
		<div class="addForm">
			<input type="text" />
			<button>Ekle</button>
		</div>
		
	</div>
	
	<div class="variations">
		<h4>VARYASYONLAR</h4>
		<ul class="gray">
			<li><i>noun:</i><span>kelimenin isim hali</span></li>
			<li><i>verb:</i><span>kelimenin fiil hali</span></li>
		</ul>
	</div>
	
	<div class="synonyms">
		<h4 class="inline">EŞ:</h4>
		<span>
		<?php
		$h='';
		$length=13;
		$synonyms[0]=array_slice($w->synonyms,0,$length);
		
		foreach($synonyms[0] as $i)
			$h.='<a href="" class="word">'.$i->word.',</a> ';

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
	<div>
		<h4 class="inline">ZIT:</h4>
		<span>
		<?php
		$h='';
		$length=13;
		$antonyms[0]=array_slice($w->antonyms,0,$length);
		
		foreach($antonyms[0] as $i)
			$h.='<a href="" class="word">'.$i->word.',</a> ';

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
	<div>
		<h4>DURUM</h4>
		<div class="gray">
			<i>seviye:</i>
			<span>|-----------[]-----------|</span>
		</div>
		<div class="img">
			<!-- THIS DIV TAG WILL REPLACE TO IMG TAG -->
		</div>
	</div>
	<div class="delAndTestLinks">
		<a href="#" alt="" >Bu kelimeyi sil</a>
		<a href="#" alt="" class="seperator">Kelime testi yap</a>
	</div>
</div>

<script>
	new words('word<?php echo $w->id;?>');
</script>
