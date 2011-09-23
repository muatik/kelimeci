<?php
header('Content-Type: text/html; charset=utf-8');
require_once("dictionaryCrawler.php");
class urbanC extends dictionaryCrawler{
	
	public function __construct(){
		
		$this->crwlUrl='http://www.urbandictionary.com/define.php?term=';
	}
	
	public function fetch($word){

		$this->word=$word;	
			
		$this->getContent($this->crwlUrl.$this->clearURL(urlencode($word)));
		if ($this->content!=false)
			return $this->content;
		else 
			return false;
		
	}
	
	public function parse(){
		
		$pos=$this->getWordsDetail();
		
		$o=new stdClass;
		
		$o->word=$this->word;
		
		$o->webPageName='urban';
		
		$o->lang='en';
		
		$o->content=$this->content;
		
		$o->pronunciation='';
		
		$o->synonyms=array();		
		$o->antonyms=array();
		
		$o->nearbyWords=array();
		
		$o->etymology='';
		
		$o->partOfSpeech=array($pos);
				
		return $o ;
	}
	
	/**
	 * gönderilen url'deki yada sınıfın özelliği olarak 
	 * tanımlanmış url'deki içeriği çeker.
	 * 
	 * @return bool 
	 * */
	public function getContent($url=null){

		if ($url==null)
			$this->content = file_get_contents($this->url);
		else 
			$this->content = file_get_contents($url);

		if ($this->content!=false) 
			return true;
		else
			return false;
	}
	
	/**
	 * sayfada bulunan sayfalamadan son sayfa numarasını alır.
	 * 
	 * @return int
	 * */
	public function getLastPageNumber(){
		
		@preg_match('/<div class="pagination".*>(.*)<\/div>/im',$this->content,$pages);
		@preg_match_all('/href="(.*)">([\w\r\t\s\S]+)<\/a>.*<a/i',$pages[0],$lastPage);
		if (isset($lastPage[2][0])) return $lastPage[2][0];
		else return 1;
	}

	/**
	 * çekilen kelimeler ve detaylarını birleştirerek geri döndürür.
	 * 
	 * @return array
	 * */
	public function getWordsDetail(){	

			// sadece ilk sayfayı almak istersek 1 atayabiliriz.
			$pageNumber=$this->getLastPageNumber();	
			$tempContent='';
			$means=array();
			for($i=1;$i<=$pageNumber;$i++){				
				$this->getContent($this->crwlUrl.$this->clearURL(urlencode($this->word)).'&page='.$i);
				$tempContent=$tempContent.$this->content;
				$means=array_merge($means,$this->getWordsDetailParse());
			}			
			$this->content=$tempContent;

		$o=new stdClass;
		$o->lang='en';
		$o->means=array(array('sentence',$means));
		return $o;
	}

	/**
	 * içerik içinden kelimenin detayını(anlamlarını ve örnek cümleleri) 
	 * parse eder.
	 * 
	 * @return array
	 * */
	public function getWordsDetailParse(){
		// kelimenin detayları alınıyor.
		$means=array();
		$domDoc=new DOMDocument();
		@$domDoc->loadHTML($this->content);
		@$domXPath = new DOMXPath($domDoc);
		$elements=$domXPath->query("//*[@id='entries']");
		foreach($elements as $nodes){

			$trChilds=$nodes->childNodes;
			foreach($trChilds as $tr){
				
				$tdChilds=$tr->childNodes;
				foreach($tdChilds as $td){

					if ($td->nodeName!='#text'){
						
						if ($td->getAttribute('class')=='text'){
							// değerler temizleniyor.
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
								$means[].=$definition.$example;
						}
					}
				}
			}
		}
		return $means;
	}
	
	/**
	 * url içindeki bozuk verileri temizler.
	 * 
	 * @return string
	 * */
	public function clearURL($string){ 
		$string = str_replace(
				array("&lt;", "&gt;", '&amp;', '&#039;', '&quot;','&lt;', '&gt;','%3B','%26','quot'),
				array("<", ">",'&','\'','"','<','>','','','"'),
				htmlspecialchars_decode($string, ENT_NOQUOTES)
		); 
		return $string; 
	}
}
?>
