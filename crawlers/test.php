<?php
header('Content-Type: text/html; charset=utf-8');

//kelime listesi A B C.....
//$content=file_get_contents('http://www.urbandictionary.com/browse.php?character=B');

//kelimenin detayı alınoyr.
$content=file_get_contents('http://www.urbandictionary.com/define.php?term=car');

//populer Words
//http://www.urbandictionary.com/popular.php?character=V
preg_match_all('/<a href="(.*)" class="urbantip">(.*)<\/a>/im',$content,$w);
//$words=$w[2];


/*
preg_match('/<div class="pagination">(.*)<\/div>/im',$content,$pages);
preg_match_all('/href="(.*)">([\w\r\t\s\S]+)<\/a>.*<a/i',$pages[0],$lastPage);
sayfa numarasınu alıyor.
$lastPage[2];*/

// kelimenin detayları alınıyor.
$means=array();
$domDoc=new DOMDocument();
@$domDoc->loadHTML($content);
@$domXPath = new DOMXPath($domDoc);
$elements=$domXPath->query("//*[@id='entries']");
foreach($elements as $nodes){
	
	$trChilds=$nodes->childNodes;
	foreach($trChilds as $tr){
		
		$tdChilds=$tr->childNodes;
		foreach($tdChilds as $td){

			if ($td->nodeName!='#text'){
				//if ($td->getAttribute('class')=='word')
					// $td->nodeValue.  listedeki word değerleri alınmak istenirse kullanılabilir.
				if ($td->getAttribute('class')=='text'){
				
					$definition='';
					$example='';

					$divChilds=$td->childNodes;
					foreach($divChilds as $div){

						if ($div->nodeName!='#text'){
							if ($div->getAttribute('class')=='definition')
							$definition=$div->nodeValue;

							if ($div->getAttribute('class')=='example')
							$example=$div->nodeValue;
						}
					}
				
					if (!empty($definition) || !empty($example))
						$means[].=$definition.' Example: '.$example;
				}
			}
		}	
	}
}
print_r($means);
?>
