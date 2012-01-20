<?php
/**
 * vocabulary controller içinden kullanıcının seçili paketleri parametrede gelir.
 * */
$h='';
foreach($o->packages as $i)
	$h.='<li><label>
		<input type="checkbox" value="'.$i->label.'"
		'.($i->isInUserVcb?'checked="checked"':'').'/>
		'.$i->label.'</label></li>';

if(!isset($o->noScriptStyle))
	echo '<script type="text/javascript" src="js/wordPackages.js"></script>
	<link rel="stylesheet" href="css/wordPackages.css" />';

?>

<form class="wordPackages" method="post">
	<ul class="wordPackages"><?php echo $h;?></ul>
	<button type="submit">Kaydet</button>
	<button type="reset">Geri Al</button>	
</form>
