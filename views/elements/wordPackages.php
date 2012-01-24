<?php
/**
 * vocabulary controller içinden kullanıcının seçili paketleri parametrede gelir.
 * */
$h='';
foreach($o->packages as $i)
	$h.='<li class="'.($i->isInUserVcb?'in':'').'"><label>
		<input type="checkbox" value="'.$i->label.'"
		'.($i->isInUserVcb?'checked="checked"':'').'/>
		'.$i->label.'('.$i->wordCount.') </label></li>';

if(!isset($o->noScriptStyle))
	echo '<script type="text/javascript" src="js/wordPackages.js"></script>
	<link rel="stylesheet" href="css/wordPackages.css" />';

?>

<form class="frm wordPackages" method="post">
	<h4 class="frmTitle">Kelime Paketleri</h4>
	<ul class="wordPackages"><?php echo $h;?></ul>
	<button type="submit">Kaydet</button>
	<button type="reset">Geri Al</button>	
</form>
