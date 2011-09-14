<?php
header('Content-Type: text/html; charset=utf-8');
class crawler
{
	public $url;
	public $content;
	public $wordId; 
	public $prefix='http://dictionary.reference.com/browse/';
	public $domDoc; 
	public $domXPath; 

	public function __construct(){
		$this->domDoc=new DOMDocument();
		
	}		
	
	public function pageRead($word){
		
		set_time_limit(0);
 		$this->url=$this->prefix.$word;
						
			$this->content = file_get_contents($this->url);
			if ($this->content!=false){
				
				$badChars=array('“','”','–');
				$goodChars=array('"','"','-');
				$this->content=str_replace(
					$badChars,$goodChars,$this->content
				);

				@$this->domDoc->loadHTML($this->content);
				@$this->domXPath = new DOMXPath($this->domDoc);
				$this->parseContent();
			}
	}
	
	
	public function parseContent(){

		print_r($this->getMeans());

		// $this->getEtymology();

		//$this->getSynonyms();

		//$this->getStructure();

	}
	
	public function getEtymology(){

		$elements=$this->domXPath->query("//*[@class='ety']");

		foreach($elements as $nodes)		
			$etymology=$nodes->nodeValue;
		return $etymology;
	}
	
	public function getSynonyms(){
		$elements=$this->domXPath->query("//*[@class='tail']");
	
		foreach($elements as $nodes){
			$divX=$nodes->childNodes;
			
			foreach($divX as $divC)				
				if(strpos($divC->nodeValue,'Synonyms')!=false)					
					$synonyms=$divC->nodeValue;									
		}
		return $synonyms;
	}
	
	public function getStructure(){

		$structure='';
		$elements=$this->domXPath->query("//*[@class='header']");

		foreach($elements as $nodes){

			$divX=$nodes->childNodes;
			foreach($divX as $divC){

				if ($divC->nodeName!='#text'){

					if ($divC->getAttribute('class')!='pronset'){

						$structure.=preg_replace('/[âÂ]/i',''
						,$divC->nodeValue).' ';

					}
				}
			}
		}
		return $structure;
	}
	
	/**
	 * ilgili kelimeyle ilgili sayfadaki türlere göre tüm anlamları çeker
	 * 
	 * @param elements	Görünür olan anlam elementlerinde null'dur.
	 * 					Görünür olmayanlarda ise "moredef" gönderiliyor.
	 * 				
	 * @return 	array
	 * */
	public function getMeans($class="pbk",$kind=null){
		
		$means=array();
		
		$elements=$this->domXPath->query("//*[@class='".$class."']");
		
		// <tür ve anlamları kaydet>	
		foreach($elements as $nodes){
			
			$childNodes = $nodes->childNodes;
			foreach ($childNodes as $node) {
				/**
				 * eğer daha fazla anlam linki varsa o linkin içeresindeki 
				 * kayıtları yakalıyor ve diziye ekliyor
				 * */
				if ($node->getAttribute('class')=='moredef'){
					$moredef=$this->getMeans("moredef",$kind);
					$means[$kind]=array_merge($means[$kind],$moredef[$kind]);
				}
				
				if (!empty($node->nodeValue)){
					
					// tür değeri kontrol ediliyor ve alınıyor
					if ($node->nodeName=='span' && $node->getAttribute('class')=='pg') 
						$kind=str_replace('â','',$node->nodeValue);
					else if ($node->nodeName=='div') {
						
						$dataX=$node->childNodes;
						foreach($dataX as $dataC){
							
							if ($dataC->nodeName!='#text'){
								
								if ($dataC->getAttribute('class')=='dndata'){
									if (empty($means[$kind])) $means[$kind]=array();
									$means[$kind]=array_merge(
											$means[$kind],
											array($dataC->nodeValue)
									);
									
								}
							}	
						}
					}
				}	
			}
		}
		return $means;
	}
	
}
$c= new crawler();
$c->pageRead('have');

?>

