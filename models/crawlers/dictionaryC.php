<?php
namespace crawlers;
header('Content-Type: text/html; charset=ISO-8859-9');
require_once("dictionaryCrawler.php");
class dictionaryC extends dictionaryCrawler{
	
	public function __construct(){
		
		$this->crwlUrl='http://dictionary.reference.com/browse/';		
		
		/**
		 * Eş anlamlı kelimelerin çekildiği adres.
		 * */
		$this->synUrl='http://thesaurus.com/browse/';
		
		$this->domDoc=new DOMDocument();
	}
	
	public function fetch($word){

		$this->word=$word;
		 		
			$this->word=$word;
			$this->content = file_get_contents($this->crwlUrl.urlencode($word));
			if ($this->content!=false){
				
				$badChars=array('“','”','–');
				$goodChars=array('"','"','-');
				$this->content=str_replace(
					$badChars,$goodChars,$this->content
				);

				@$this->domDoc->loadHTML($this->content);
				@$this->domXPath = new DOMXPath($this->domDoc);
			}else return false;
		
	}
	
	public function parse(){		

		$o=new stdClass;

		$o->word=$this->word;
		
		$o->webPageName='dictionary';
		
		$o->lang='en';

		$o->content=$this->content;

		$o->pronunciation=$this->getPronunciation();

		$synAnt=$this->getSynAnt();
		$synonyms=$synAnt[0];
		$antonyms=$synAnt[1];	
		$o->synonyms=$synonyms;
		$o->antonyms=$antonyms;

		$o->nearbyWords=array();

		$o->etymology=$this->getEtymology();

		$o->partOfSpeech=array($this->getMeans());

		return $o;
	}
	
	/**
	 * etymology bilgisini verir
	 * 
	 * @return string
	 * */
	public function getEtymology(){
		
		preg_match('/<div class="dicTl">Word Origin & History<\/div[\w\s\S]*?([\s\S]*?<\/div>[\s\S]*?){4}/im',$this->content,$m);
		
		if (isset($m[0]))
			preg_match_all('/(div class="body")(.*)(<\/div)/im',$m[0],$k);
		
		if (isset($k[0][0]))
			return str_replace('div class="body">','',strip_tags($k[0][0]));
		else
			return '';
	}
	
	/**
	 * eşanlamlı ve zıt anlamlı kelimeleri verir.
	 * 
	 * @return array
	 * */
	public function getSynAnt(){
		
		$synonyms=array();
		$antonyms=array();
		$content=file_get_contents($this->synUrl.$this->word);		

		if ($content!=false){
			$domDoc=new DOMDocument();
			@$domDoc->loadHTML($content);
			@$domXPath = new DOMXPath($domDoc);
			
			$tables=$domXPath->query("//*[@class='the_content']");			
			
			foreach($tables as $table){
				
				$tbody=$table->childNodes;
				$continue=false;
				$trCount=0;
				$oSyn=new stdClass;
				$oAnt=new stdClass;
				foreach($tbody as $tr){
					$trCount++;
					$td=$tr->childNodes;
					if (!isset($td)) break;
					$tdCount=0;

					foreach($td as $node){
						$tdCount++; // kolon kontrolü için kullanılıyor.
						
						// aranan kelime ile eşanamlılar tablosu 
						//içindeki kelime eşleştiriliyor
						if (trim($node->nodeValue)==$this->word && 
							$tdCount==3 && $trCount==1){
							$continue=true;
						}
						/**
						 * kelime benzeri bulundu ise sonraki satırlar alınıyor
						 * pos,synonyms,antonyms
						 * */
						if ($continue) {
							
							if ($tdCount==3 && $trCount==2){
								$oSyn->pos=trim($node->nodeValue);
								$oAnt->pos=trim($node->nodeValue);
							}
							
							if ($tdCount==3 && $trCount==3){
								$oSyn->definition=trim($node->nodeValue);
								$oAnt->definition=trim($node->nodeValue);
							}
							
							if ($tdCount==3 && $trCount==4)
								$oSyn->synonyms=explode(",",$node->nodeValue);
							
							if ($tdCount==3 && $trCount==5)
								$oAnt->antonyms=explode(",",$node->nodeValue);
						}
					}
				}
				
				if ($continue){
					
					if (isset($oSyn->synonyms))
						foreach($oSyn->synonyms as $k=>$i)
							$oSyn->synonyms[$k]=trim($i);
					
					if (isset($oSyn->antonyms))
						foreach($oAnt->antonyms as $k=>$i)
							$oAnt->antonyms[$k]=trim($i);
							
					$synonyms[]=$oSyn;
					$antonyms[]=$oAnt;
					
				}
			}
		}		
		
		return array($synonyms,$antonyms);
	}
	
	/**
	 * kelimenin okunuşunu metin olarak verir
	 * 
	 * @return string
	 * */
	public function getPronunciation(){

		$structure='';
		$pron=$this->domXPath->query("//*[@class='pron']");		
		if (isset($pron->item(0)->nodeValue))
			return $pron->item(0)->nodeValue;
		else 
			return '';
	}
	
	/**
	 * ilgili kelimeyle anlamları ve türlerini verir.
	 * 
	 * @return array
	 * */
	public function getMeans(){
		$o=new stdclass;
		$o->lang='en';				
		$o->means=array_merge(
			$this->changeMeansFormat($this->getMeansList()),
			$this->changeMeansFormat($this->getMeansBody())
		);		
		return  $o;
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
		// node seçiliyor
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
							$means=$this->getMeansBodyMore($node,$kind,$means);
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
	public function getMeansBodyMore($nodes,$kind,$means){
		
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
	
	/**
	 * gönderilen dizi formatını uygun formata çevirir geri döndürür.
	 * 
	 * @param array $means
	 * 
	 * @return array
	 * */
	public function changeMeansFormat($means){
		$meansTemp=array();
		foreach($means as $k=>$i)
			$meansTemp[]=array($k,$means[$k]);
			
		return $meansTemp;
	}
}
?>
