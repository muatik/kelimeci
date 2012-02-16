<?php
/**
 * kelime paketlerini listeler.
 * */
$packages=$o;
echo '<ul class="packages">';
foreach($packages as $p){
	echo '<li '.($p->isInUserVcb?'class="in"':null).' >
		<label><input type="checkbox" class="package" 
		value="'.$p->id.'" '.($p->isInUserVcb?'checked="checked"':null).' />
		'.$p->name.' ('.$p->wordCount.')
		</li>';
}
echo '</ul>';
?>
