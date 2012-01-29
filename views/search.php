<script type="text/javascript" src="js/search.js"></script>
<link rel="stylesheet" type="text/css" href="css/search.css" />

<!-- word page -->
<script tyoe="text/javascript" src="js/words.js"></script>
<script tyoe="text/javascript" src="js/vocabulary.js"></script>
<link rel="stylesheet" type="text/css" href="css/clsBoxes.css" />
<link rel="stylesheet" href="css/word.css" />
<link rel="stylesheet" href="css/animbuttons.css" />
<!-- word page -->

<div id="search">

	<input type="hidden" id="keyword" 
		value="<?php echo $o->word;?>" />

	<div class="leftSide">

	<!-- showing search history -->
	<div id="sHistory" class="box">
	<h4>Arama Geçmişin</h4>
	<ul class="wordList">
	<?php
	foreach($o->history as $i)
		echo '<li title="'.$i['date'].'">
			<a href="search?word='.$i['keyword'].'">'.$i['keyword'].'</a></li>';
	?>
	</ul>
	</div>


	<!-- showing related searchs -->
	<div class="box">
	<h4><?php echo $o->word;?> kelimesinden sonra en sık arananlar</h4>
	<ul class="wordList">
	<?php 
	foreach($o->relatedSearchs as $i)
		echo '<li>'.$i->keyword.'</li>';
	?>
	</ul>
	</div>


	</div> <!-- end of left panel -->


	<div id="result">
	<?php echo $o->result;?>
	</div>

</div>
