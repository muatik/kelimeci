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

		
		//print_r($this->getMeans());
		
		//$this->getEtymology();

		echo $this->getSynonyms();

		//$this->getStructure();

	}
	
	public function getEtymology(){
		preg_match('/<div class="dicTl">Word Origin & History<\/div[\w\s\S]*?([\s\S]*?<\/div>[\s\S]*?){4}/im',$this->content,$m);
		preg_match_all('/(div class="body")(.*)(<\/div)/im',$m[0],$k);
		return str_replace('div class="body">','',strip_tags($k[0][0]));
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
	 * ilgili kelimeyle ilgili anlamları ve türlerini verir.
	 * 
	 * @return array
	 * */
	public function getMeans(){
		return	array_merge($this->getMeansList(),$this->getMeansBody());
	}
	
	/**
	 * ilgili kelimeyle ilgili "pbk" classı içindeki  türlere göre tüm anlamları çeker
	 * 
	 * @param string 	$kind	Görünür olan anlam elementlerinde null'dur.
	 * 							Görünür olmayanlarda ise "moredef" gönderiliyor.
	 * @param domobject $node	"more" linkine tıklandığında açılan, 
	 * 							gizli anlam listesini alır.
	 * 				
	 * @return 	array
	 * */
	public function getMeansList($kind=null,$node=null){
		
		// anlam dizisi
		$means=array();
		// node 
		if ($node==null)
			$elements=$this->domXPath->query("//*[@class='pbk']");
		else $elements=$node->childNodes;
		
		// <tür ve anlamları kaydet>	
		foreach($elements as $nodes){
			
			$childNodes = $nodes->childNodes;
			foreach ($childNodes as $node) {
				/**
				 * eğer daha fazla anlam linki varsa o linkin içeresindeki 
				 * kayıtları yakalıyor ve diziye ekliyor
				 * */
				if ($node->nodeName!='#text')
					if ($node->getAttribute('class')=='moredef'){
						$moredef=$this->getMeansList($kind,$nodes);
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
	
	/**
	 * getmeans methodu gibi çalışır.sadece body class'ının bir altındaki
	 * elementleri yaklar oradaki meanleri ve türlerini gönderir.
	 * 			
	 * @return 	array
	 * */
	public function getMeansBody(){
		
		$means=array();	
		
		$elements=$this->domXPath->query("//*[@class='body']");
				
		// <tür ve anlamları kaydet>
		foreach($elements as $nodes){
			
			$childNodes = $nodes->childNodes;
			foreach ($childNodes as $node) {
				
				/**
				 * eğer daha fazla anlam linki varsa o linkin içeresindeki 
				 * kayıtları yakalıyor ve diziye ekliyor
				 * */
				if ($node->nodeName!='#text'){
				if ($node->getAttribute('class')=='moredef'){
							$means=$this->getBodyMeanMore($node,$kind,$means);
						}
				}				
				
				if (!empty($node->nodeValue)){
					
					// tür değeri kontrol ediliyor ve alınıyor
					if ($node->nodeName=='span' && $node->getAttribute('class')=='sectionLabel') 
						$kind=$node->nodeValue;
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
	
	/**
	 * body class'ının bir altındaki "more" linkine tıklandığında açılan, 
	 * sayfaki gizli anlamları listesini alır.
	 * 
	 * @param domobject $nodes 	gizli anlamları tutan dom objesi	 
	 * @param string	$kind	gizli anlamların türü
	 * @param array 	$means	görünen anlamları tutan dizi
	 * 
	 * @return array
	 * */
	public function getBodyMeanMore($nodes,$kind,$means){
		
		$childNodes = $nodes->childNodes;
			foreach ($childNodes as $node) {						
				
				if (!empty($node->nodeValue)){
					
					// tür değeri kontrol ediliyor ve alınıyor
					if ($node->nodeName=='span' && $node->getAttribute('class')=='sectionLabel') 
						$kind=$node->nodeValue;
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
		return $means;
	}
}
$c= new crawler();
$c->pageRead('fast');

?>

