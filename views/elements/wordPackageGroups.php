<?php
/**
 * vocabulary controller içinden kullanıcının seçili paketleri parametrede gelir.
 * */
$h='';
foreach($o->wpGroups as $g){
	$h.='<li class="group"><span class="groupItem">
		<img class="togglePack" src="images/downArrow.png" alt="aç/kapa" />
		<label class="g">
		<input type="checkbox" class="groups" value="'.$g->id.'" '
		.($g->isInUserVcb?'checked="checked"':null).' />'
		.$g->name.'</label></span></li>';
}

if(!isset($o->noScriptStyle))
	echo '<script type="text/javascript" src="js/wordPackages.js"></script>
	<link rel="stylesheet" href="css/wordPackages.css" />';
?>

<form class="frm wordPackageGroups" method="post">
	<h4 class="frmTitle">Kelime Paketleri</h4>
	<ul class="groups"><?php echo $h;?></ul>
	<button type="submit">Kaydet</button>
	<button type="reset">Geri Al</button>	
</form>
