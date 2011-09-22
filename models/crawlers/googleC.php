<?php
header('Content-Type: text/html; charset=ISO-8859-9');
require_once("dictionaryCrawler.php");
class googleC extends dictionaryCrawler{
	
	public function __construct(){
		
		$this->crwlUrl='';
		
		/**
		 * ingilizce kelimeleri sorgulama adresi
		 * */
		$this->enUrl='http://translate.google.com/translate_a/t?client=t&hl=tr&sl=en&tl=tr&text=';
		
		/**
		 * türkçe kelimeleri sorgulama adresi
		 * */
		$this->trUrl='http://translate.google.com/translate_a/t?client=t&hl=tr&sl=tr&tl=en&text=';
	}
	
	public function fetch($word){

		$this->word=$word;

		// ingilizce kelime için içerik alınıyor.
		$contentTR=file_get_contents($this->enUrl.urlencode($word));

		// türkçe kelime için içerik alınıyor.
		$contentEN=file_get_contents($this->trUrl.urlencode($word));

		$contentTR=mb_convert_encoding($contentTR,'UTF-8','ISO-8859-9');
		$contentEN=mb_convert_encoding($contentEN,'UTF-8','ISO-8859-9');

		// kelime ingilizce mi ? değil mi ? kontrol ediliyor. İçerik geri döndürülüyor
		if ($contentTR!=false && $this->getWordLang($contentTR)=='en'){

			$this->content=$contentTR;						
			return $this->content;
		
		// kelime türkçe mi ? değil mi ? kontrol ediliyor. İçerik geri döndürülüyor	
		}elseif ($contentEN!=false && $this->getWordLang($contentEN)=='tr'){
		echo $contentEN;
			
			$this->content=$contentEN;					
			return $this->content;				
		}else
			return false;
	}
	
	public function parse(){		
		
		$o=new stdClass;
		
		$o->word=$this->word;
		$o->lang=$this->getWordLang($this->content);
		$o->content=$this->content;		
		
		$o->pronunciation='';
		
		$o->synonyms=array();
		$o->antonyms=array();
		
		$o->nearbyWords=array();
		
		$o->etymology='';
		
		$o->partOfSpeech=array($this->getWords());
	
		return $o;
	}
	
	/**
	 * gönderilen içeriğe göre sorgulanan kelimenin türünü belirler.
	 * @param string $content
	 * 
	 * @return string (tr-en)
	 * */
	public function getWordLang($content){		
		
		// kelime türkçe mi ?
		if (strpos($content,',"tr"')){
			
			$content=strip_tags(mb_substr($content,0,strpos($content,',"tr"')).']');			
			$words=json_decode($content);
			if (is_array($words)) return 'tr';
		}
		
		// kelime ingilizce mi ?
		if (strpos($content,',"en"')){
			
			$content=strip_tags(mb_substr($content,0,strpos($content,',"en"')).']');			
			$words=json_decode($content);
			if (is_array($words)) return 'en';
		}		
	}
	
	public function getWords(){	
		$lang='';	
		// kelime türkçe mi ?
		if (strpos($this->content,',"tr"')){
			
			$this->content=strip_tags(mb_substr($this->content,0,strpos($this->content,',"tr"')).']');
			$lang='en';
		}
		// kelime ingilizce mi ?
		if (strpos($this->content,',"en"')){
			
			$this->content=strip_tags(mb_substr($this->content,0,strpos($this->content,',"en"')).']');
			$lang='tr';
		}
		
		$words=json_decode($this->content);
		$o=new stdClass();
		$o->lang=$lang;
		$o->means=$words[1];
				
		return $o;
	}
	
}

$g=new googleC();
print_r($g->get("kar"));
?>
