<?php

header('content-type:text/html;charset=utf-8');

$kelimeler=file_get_contents('db.txt');
$kelimeler=unserialize($kelimeler);
$kelimeler=array_reverse($kelimeler); // en son eklenen ilk gözüksün
$iC=0;
?>

<form action="?" method="post" onsubmit="return submitWForm();">
<label>Kelime :<input type="text" id="w" name="w" /></label>
<input type="submit" value="Ekle" />
</form>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="createXHR.js"></script>
<script type="text/javascript" src="extfunctions.js"></script>
<script type="text/javascript">

function submitWForm(w){
	insertW(getById('w').value);
	getById('w').value='';
	getById('w').focus();
	return false;
}

function insertW(w){
	
	var x=new simpleAjax()
	x.send('ajax.php','w='+w,
		{'onSuccess':function(rsp){
			rsp=rsp.split('|');
			if(rsp[0]==0) return false;
			var uw=encodeURIComponent(w);
			var ic=getById('ic').value++;
			
			var links=[
			 ['SSZ','http://www.seslisozluk.com/?word='+uw],
			 ['DCT','http://dictionary.reference.com/browse/'+uw],
			 ['EKŞ','http://www.eksisozluk.com/show.asp?t='+uw]
			];
			
			var html='<tr><td>'+(ic+1)+'</td><td>'+w+'</td><td>';
			for(var i=0;i<links.length;i++){
				html+=' <a target="_blank" href="'+links[i][1]+'">'+links[i][0]+'</a> - ';
			}
			html+='<span class="meaning">'+rsp[1]+'</span></td></tr>';
			$('#ws tr:first-child').before(html);
		}}
	);
}
</script>
<style type="text/css">
	tr:hover{background-color:#f2f2e0};
</style>

<?php
echo '<table id="ws">';
foreach($kelimeler as $i){
	$iC++;
	
	$linkler=array(
		'SSZ'=>'http://www.seslisozluk.com/?word='.$i->ekelime,
		'DCT'=>'http://dictionary.reference.com/browse/'.$i->ekelime,
		'EKŞ'=>'http://www.eksisozluk.com/show.asp?t='.$i->ekelime
	);
	
	echo '<tr><th>'.$iC.'</th><td>'.$i->ekelime.'</td><td>';
	
	foreach($linkler as $n=>$h)
		echo ' <a target="_blank" href="'.$h.'">'.$n.'</a> - ';
	
	echo '</td></tr>';
}
echo '</table>
<input type="hidden" id="ic" value="'.$iC.'" />
';


?>
