<?php
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
