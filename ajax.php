<?php

function insertW($w){
	
	$content=file_get_contents('http://www.seslisozluk.com/?word='
		.urlencode($w));
	$means=getWords($content,$w,'eng');
		
	$w=str_replace(array("\r","\n","\t"),'',$w);
	$means=str_replace(array("\r","\n","\t"," "),'',$means);
	$means=explode('|',$means,6);
	
	$mean='';
	for($i=0;$i<count($means) && $i<5;$i++)
		$mean.=$means[$i].', ';
	$mean=mb_substr($mean,2,-2);
	if($mean=='') return false;
	
	$i=new stdClass;
	$i->id=time().rand(1,100);
	$i->tkelime=trim($mean);
	$i->ekelime=trim($w);
	$i->date=time();
	$i->udate=time();
	$i->rate=0;
		
	$kelimeler=file_get_contents('db.txt');
	$kelimeler=unserialize($kelimeler);
	$kelimeler[]=$i;
	$kelimeler=serialize($kelimeler);
	
	file_put_contents('db.txt',$kelimeler);
	return $i->tkelime;
}

$r=$_REQUEST;
if(isset($r['w']) && mb_strlen(trim($r['w']))>1){
	$ekelime=insertW($r['w']);
	if($ekelime!==false) echo '1|'.$ekelime; else echo 0;
}

if (isset($r['word'],$r['id'])){

	$word=trim($r['word']);
	$mean=trim($r['mean']);
	$id=trim($r['id']);
	
	
	$words=unserialize(file_get_contents('db.txt'));
	foreach($words as $i=>$w){
		if($w->id==$id){
			if(
				strpos($w->ekelime,$word)!==false &&
				(mb_strlen($w->ekelime)-mb_strlen($word)<3)
			){
				$words[$i]->udate=time();
				$words[$i]->rate++;
				echo 1;
			}
			else{
				$words[$i]->rate--;
				echo '0|'.$words[$i]->ekelime;
			}
		}
	}
	file_put_contents('db.txt',serialize($words));
}

// $content içerik
// $word aranacak kelime
// $lang kelimenin dili
function getWords($content,$word,$lang){
	
	if ($lang=='eng') $tableId='dc_en_tr';
	elseif ($lang=='tr') $tableId='dc_tr_en';
	else $tableId='dc_tr_en';
	
	$domDoc=new DOMDocument();
	@$domDoc->loadHTML($content);
	@$domXPath = new DOMXPath($domDoc);
	$elements=$domXPath->query("//*[@id='".$tableId."']");
	$words='';	
	
	foreach($elements as $nodes){
		
		$childNodes = $nodes->childNodes;
		foreach($childNodes as $nodeTable){
			
			if ($nodeTable->nodeName=='table'){
				
				$tChildNodes = $nodeTable->childNodes;
				foreach($tChildNodes as $nodeTr){
					
					$trChildNodes = $nodeTr->childNodes;
					foreach($trChildNodes as $nodeTd){
						
						$tdChildNodes = $nodeTd->childNodes;
						foreach($tdChildNodes as $node){
							
							if ($node->nodeName!='#text'){
							
								if ($node->getAttribute('class')=='tw')
									if (trim($node->nodeValue)!=$word) break;
									
								if ($node->getAttribute('class')=='m'){
																		
									$cChildNodes = $node->childNodes; // tür için
									$value='';
									foreach($cChildNodes as $nodeC)	{
										if ($nodeC->nodeName=='#text' && empty($value))
										$value=$nodeC->nodeValue;
										
									}
									
									$words.='|'.trim($value);
								} 
							}  
						} 
					} 
				}
			}
		}	
	}
	return $words;
}
?>
