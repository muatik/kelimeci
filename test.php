<?php
header('content-type:text/html;charset=utf-8');
	
$words=file_get_contents('db.txt');
$words=unserialize($words);


for($i=0;$i<count($words)-1;$i++)
	for($j=1;$j<count($words);$j++)
		if($words[$j]->rate<$words[$i]->rate){
			$tmp=$words[$i];
			$words[$i]=$words[$j];
			$words[$j]=$tmp;
		}

echo '<style type="text/css">
.correction{color:#B6B88D;display:none}
.word:hover .correction{display:inline}
</style>';

foreach ($words as $i=>$word){
	$smean=urldecode(str_replace('%C2','',urlencode($word->tkelime)));
	if (
		((time()-$word->udate>3600*5) && $word->rate<=0)
		||
		((time()-$word->udate>3600*20) && $word->rate<=5)
		||
		((time()-$word->udate>3600*35) && $word->rate<=10)
		||
		((time()-$word->udate>3600*60) && $word->rate<=20)
		||
		((time()-$word->udate>3600*24*30) && $word->rate>30)
	)

	/**
	 * kelime anlamları birden fazla kısımlan oluşabilir. her seferinde
	 * bu kısımların sırası değiştirilerek kişinin ilk kısma bakarak
	 * cevap vermesi önlenir böylece diğer kısımlar da akılda kalır.
	 * */
	$smean=explode(',',$smean);
	shuffle(&$smean);
	$smean=implode('--',$smean);
	
	echo '
	<div class="word">
		<input type="hidden" name="id" value="'.$word->id.'"/>
		<input type="text" name="word" 
		value="">-><span class="mean">'
		.$smean.'</span>
		<span>('.$word->rate.')</span>
	</div>';

	
}	

$r=$_REQUEST;
if(isset($r['w']) && mb_strlen(trim($r['w']))>1){
	if(insertW($r['w'])) echo 1; else echo 0;
}

?>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="createXHR.js"></script>
<script type="text/javascript" src="extfunctions.js"></script>
<script type="text/javascript">
$('input[name="word"]').blur(function (){
	var id=$('input[name="id"]',this.parentNode).val();
	var word=$(this).val();
	var mean=$(".mean",this.parentNode).html();
	var t=this;
	if (word!='') {
		$(t).attr('disabled','false');
		var x=new simpleAjax()
		x.send(
			'ajax.php',
			'id='+id+'&word='+encodeURI(word)+'&mean='+encodeURI(mean),
			{'onSuccess':function(rsp){
				rsp=rsp.split('|');
				if(rsp[0]==0){
					
					$(t).val(word+' | '+rsp[1])
					
					if(rsp.length>2){ // kelime anlamı geldiyse
					
						$(t.parentNode).append(
							'<span class="correction">'+
							word+' = '+rsp[2]+'</span>'
						)
					}
					
					$(t).css('color','#b63202');
				}
				if(rsp[0]==1)
					$(t).css('color','#126502');
				}
			}
		);
		
	}
});
</script>
