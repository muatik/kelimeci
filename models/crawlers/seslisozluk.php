<?php
header('Content-Type: text/html; charset=utf-8');
require_once("dictionaryCrawler.php");
class seslisozluk extends dictionaryCrawler{
	
	public function __construct(){
		$this->crwlUrl='http://www.seslisozluk.com/?word=';
	}
	
	public function fetch($word){

		$this->word=$word;

		$this->content=file_get_contents($this->crwlUrl.$word);
		if ($this->content!=false){

			$rx='/(<div id="translations">)([\s\S\f\t\r\w.*?]+)(<!-- id=translations -->)/im';
			preg_match($rx,$this->content,$content);			
			$this->content=$content[0];

			return $content[0];
		}else
			return false;
	}
	
	public function parse(){
	
		
		$trWords=$this->getWords('tr');
		$enWords=$this->getWords('en');
		
		$o=new stdClass;
		$o->word=$this->word;
		$o->lang='en';
		$o->content=$this->content;
		$o->pronunciation='';
		$o->synonyms=new stdClass;
		$o->synonyms->verbs=array();
		$o->synonyms->nouns=array();
		$o->synonyms->others=array();
		$o->nearbyWords=array();
		$o->etymology=$this->getEtymology();
		$o->partOfSpeech=array($trWords,$enWords);
	
		return $o;				
	}
	
	public function getEtymology(){
		
		preg_match('/(<div><b>Etymology:<\/b>)([\s\S.]*?)(<\/div>)/i',$this->content,$m);
		return strip_tags($m[0]);
	}
	
	public function getWords($lang){
		
		$domDoc=new DOMDocument();
		@$domDoc->loadHTML($this->content);
		@$domXPath = new DOMXPath($domDoc);
		
		if ($lang=='tr')
			$qId="//*[@id='dc_en_tr']";
		elseif ($lang=='en')
			$qId="//*[@id='dc_en_en']";

		// türler ve anlamları bulunuyor
		$words=array();
		$elements=$domXPath->query($qId); // tr_en en_en
		
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
										if (trim($node->nodeValue)!=$this->word) break;
										
									if ($node->getAttribute('class')=='m'){
										$kind='genel';
										
										$cChildNodes = $node->childNodes;// tür için
										foreach($cChildNodes as $nodeC)	{
											if ($nodeC->nodeName!='#text'){
												$kind=$nodeC->nodeValue;
												
											} 
											if ($nodeC->nodeName=='#text'){	
												$value=$nodeC->nodeValue;
											} 
											$value=trim($value);
											if 	(empty($value)) 
											$value=trim($node->nodeValue);
										}
										$value=trim(str_replace($kind,'',$value));
										$value=str_replace('  ','',$value);
										@$words[$kind].=$this->trConvert($value).'|';
									}
								}
							}
						}
					}
				}
			}
		}
		/**
		 * tür ve anlamlardan oluşan words dizisi nesneye çevriliyor ve geri
		 * döndürülüyor.
		 * */
		$o=new stdClass;
		$o->lang=$lang;
		$o->means=array();
		foreach($words as $k=>$i){
			$partWords=explode('|',$words[$k]);
			array_pop($partWords);
			$o->means[]=array($k,$partWords);
		}
		return $o;
	}
	
	public function trConvert($string){
		
		$string=str_replace(
				array('Ã¶z','Ã§','Ã¼','Å','Ä±','Ä','Ã¢'),
				array('ö','ç','ü','ş','ı','ğ','â'),
				$string
		);
		
		return $string;
	}

}
$s=new seslisozluk();
print_r($s->get("have"));
?>
