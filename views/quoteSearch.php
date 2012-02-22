<link href="css/quoteSearch.css" rel="stylesheet" />
<script type="text/javascript" src="js/quoteSearch.js"></script>

<div id="quoteContainer">

<h1>Alıntı Arama Formu</h1>

<form class="quoteForm" action="?" method="post">
<div class="field">
	<label>İngilizce Alanında</label>
	<input type="text" name="keywordEng" 
		value="<?php echo $o->keywordEng;?>" />
</div>
<div class="field">
	<label>Türkçe Alanında</label>
	<input type="text" name="keywordTr" 
		value="<?php echo $o->keywordTr;?>" />
</div>
<input type="submit" class="submit" value="Ara" />

<label>
	<input type="checkbox" name="showEng" value="1"
		<?php echo ($o->showEng?'checked="checked"':'');?> />
	İngilizcesini Göster
</label>
<label>
	<input type="checkbox" name="showTr" value="1"
		<?php echo ($o->showTr?'checked="checked"':'');?> />
	Türkçesini Göster
</label>
</form>

<?php
	
if(isset($o->result) && is_array($o->result) ){

	$rs=$o->result;

	echo '<h6>'.count($rs).' adet sonuç listeleniyor.</h6>';

	echo '<style type="text/css">';
	if(!$o->showTr)	echo 'li p.tr span{visibility:hidden;}';
	if(!$o->showEng) echo 'li p.eng span{visibility:hidden;}';
	echo '</style>';

	echo '<ul class="quotes">';
	foreach($rs as $i){
		echo '<li>
				<p class="tr"><img alt="" /><span>'.$i->turkish.'</span></p>
				<p class="eng"><img alt="" /><span>'.$i->english.'</span></p>
			</li>';
	}
	echo '</ul>';
}

?>


</div> <!-- end of quoteContainer -->
