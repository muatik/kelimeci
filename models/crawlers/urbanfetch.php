<?php
class urban
{
	public $content;
	public $url='http://www.urbandictionary.com/browse.php?character=A';
	public $words=array();
	
	/**
	 * urban dictionary sayfasındaki kelimeleri ve detaylarını okur.
	 * 
	 * @return array
	 * */
	public function read(){
		
		if ($this->getContent()!=false){
			
			$pageNumber=$this->getLastPageNumber();
			for($i=1;$i<=$pageNumber;$i++){
				set_time_limit(0);
				$this->getContent($this->url.'&page='.$i);
				$this->words=array_merge($this->words,$this->getParseWords());
			}
		}
		/**
		 * kelimerin detaylarını alınması için kullanılır. 
		 * sadece kelimeleri geri döndürmek istiyorsak alttaki satır 
		 * kapatılarak  $this->words geri döndürülebilir.
		 * */ 
		 $words=$this->getWordsDetail();
		return $words;
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

		if ($this->content!=false) return true;
		else return false;
	}
	
	/**
	 * içerikdeki kelimeleri ayırır ve geri döndürür.
	 * 
	 * @return array
	 * */
	public function getParseWords(){
		preg_match_all('/<a href="(.*)" class="urbantip">(.*)<\/a>/im',$this->content,$w);
		$words=$w[2];
		return $words;
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
		
		$words=array();
		$s=0;
		/* kelimelerin detaylarını okur.*/
		foreach($this->words as $word){
			set_time_limit(0);
			$this->getContent('http://www.urbandictionary.com/define.php?term='.$this->clearURL(urlencode($word)));

			// sadece ilk sayfayı almak istersek 1 atayabiliriz.
			$pageNumber=$this->getLastPageNumber();
			$o=new stdClass();
			$o->word=$word;
			$o->means=array();
			for($i=1;$i<=$pageNumber;$i++){
				$this->getContent('http://www.urbandictionary.com/define.php?term='.$this->clearURL(urlencode($word)).'&page='.$i);
				$o->means=array_merge($o->means,$this->getWordsDetailParse());		
			}
			$words[]=$o;			
			$s++;
			if ($s==5) break;
		}
		return $words;
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
						//if ($td->getAttribute('class')=='word')
							// $td->nodeValue.  listedeki word değerleri alınmak istenirse kullanılabilir.
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
$u=new urban();
print_r($u->read());
?>
