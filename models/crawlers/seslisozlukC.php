<?php
namespace crawlers;
header('Content-Type: text/html; charset=utf-8');
require_once("dictionaryCrawler.php");
class seslisozlukC extends dictionaryCrawler{
	
	public function __construct(){
		
		$this->crwlUrl='http://www.seslisozluk.com/?word=';
	}
	
	public function fetch($word){

		$this->word=$word;

		$this->content=$this->getContents(urlencode($word));
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
		
		$o=new \stdClass;
		
		$o->word=$this->word;
		
		$o->webPageName='seslisozluk';
		
		$o->lang='en';
		
		$o->content=$this->content;
		
		$o->pronunciation='';
		
		$o->synonyms=array($this->getSynonyms());
		$o->antonyms=array($this->getAntonyms());
		
		$o->nearbyWords=array();
		
		$o->etymology=$this->getEtymology();

		$o->class=$this->getClass($o->etymology);
		
		$o->partOfSpeech=array($trWords,$enWords);
	
		return $o;				
	}

	public function getClass($s){
		preg_match('/\(.*?\)/i',$s,$m);
		return str_replace(array('.','(',')',''),'',$m[0]);
	}

	public function getEtymology(){
		
		//preg_match('/(<div><b>Etymology:<\/b>)([\s\S.]*?)(<\/div>)/i',
		preg_match('/(Etymology:<\/b>)([\r\t\w\s\S.]*?)(<\/td>)/i',
			$this->content,$m);
			
		if (isset($m[2]))
			return strip_tags($m[0]);
		else return '';
	}
	
	public function getSynonyms(){
		
		preg_match('/(Synonyms:<\/b>)([\r\t\w\s\S.]*?)(<\/td>)/i',
			$this->content,$m);
		
		if (isset($m[2])){
			$o=new \stdClass;
			$o->synonyms=explode(',',$m[2]);
			return $o;
		}
		else return '';
	}
	
	public function getAntonyms(){
		
		preg_match('/(Antonyms:<\/b>)([\r\t\w\s\S.]*?)(<\/td>)/i',
			$this->content,$m);
		
		if (isset($m[2])){
			$o=new \stdClass;
			$o->antonyms=explode(',',$m[2]);
			return $o;
		}
		else return '';
	}
	
	public function getWords($lang){
		
		$domDoc=new \DOMDocument();
		@$domDoc->loadHTML($this->content);
		@$domXPath = new \DOMXPath($domDoc);
		
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
										$value='';										
											if ($nodeC->nodeName!='#text'){
												$kind=$this->trConvert($nodeC->nodeValue);
												
											} 
											if ($nodeC->nodeName=='#text'){	
												$value=$nodeC->nodeValue;
											} 
											$value=trim($value);
											if($value=='') 
											$value=trim($node->nodeValue);
										}
										$value=trim(str_replace($kind,'',$this->trConvert($value)));
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
		$o=new \stdClass;
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
				array('Ã¶','Ã§','Ã¼','Å','Ä±','Ä','Ã¢'),
				array('ö','ç','ü','ş','ı','ğ','â'),
				$string
		);
		
		return $string;
	}
	
	public function getContents($word){
		
		$path='/?ssQBy=0&word='.$word;

		$headers="GET ".$path." HTTP/1.1
		Accept:text/plain,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
		Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3
		Accept-Encoding:deflate
		Accept-Language:en-US,en;q=0.8
		Connection:keep-alive
		Cookie:SS_SD=6; PHPSESSID=la9dq9ea3iqnee2hmlttjeqj91; seslisozluk=exact%7Ccomplete%7Ce%C5%9Fit%7Caynen%7Cbirebir%7Cba%C5%9F%C4%B1nda%7Csonunda%7Cbegin%7Celma%7Capple%7Ccar%7Cfast%7Cslw%7Cslow%7Cpotato%7Cpotato+coach%7Ccostumers%7Ccustomers%7Caraba; __utma=243518343.1772327051.1321458461.1322054864.1322058130.6; __utmb=243518343.4.10.1322058130; __utmc=243518343; __utmz=243518343.1321458461.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)
		Host:www.seslisozluk.net
		Referer:http://www.seslisozluk.net/?ssQBy=0&word=araba
		User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.30 (KHTML, like Gecko) Ubuntu/11.04 Chromium/12.0.742.112 Chrome/12.0.742.112 Safari/534.30";

		$headers=str_replace("\t",'',$headers)."\n\n";
		
		$fp=fsockopen('www.seslisozluk.net',80);
		if (!$fp) return false;
		fwrite($fp, $headers);
		
		$kk='';
		while (!feof($fp)){
			
			$kk.=fread($fp, 4096);
		}
		fclose($fp);
		return $kk;
	}
}
?>
