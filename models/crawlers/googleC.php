<?php
namespace crawlers;
header('Content-Type: text/html; charset=utf-8');
require_once("dictionaryCrawler.php");
class googleC extends dictionaryCrawler{
	
	public function __construct(){
		
		$this->crwlUrl='';
		
		/**
		 * ingilizce kelimeleri sorgulama adresi
		 * */
		$this->enUrl='http://translate.google.com/translate_a/t?client=t&hl=en&sl=en&tl=tr&text=';
		
		/**
		 * türkçe kelimeleri sorgulama adresi
		 * */
		$this->trUrl='http://translate.google.com/translate_a/t?client=t&hl=en&sl=tr&tl=en&text=';
	}
	
	public function fetch($word){

		$this->word=$word;
		
		$content=$this->lookFor($word,'tr');
		if($content===false)
			$content=$this->lookFor($word,'en');
		
		return $content;
	}
	
	
	/**
	 * search for a word in specified language
	 * @param string word that is being searching.
	 * @param string $lang the language that the word is beging searching.
	 * @return mixed returns false if there is no result
	 * */
	public function lookFor($word,$lang='tr'){
		
		if($lang=='tr')
			$content=file_get_contents($this->enUrl.urlencode($word));
		else
			$content=$this->fetchTrWord(urlencode($word));
		
		$content=mb_convert_encoding($content,'UTF-8','ISO-8859-9');
		
		if($content!=false &&  $this->getWordLang($content)==$lang){
			$this->content=$content;
			return $content;
		}
		
		return false;
	}
	
	
	public function parse(){		
		
		$o=new \stdClass;
		
		$o->word=$this->word;
		
		$o->webPageName='google';
		
		$o->lang=$this->langSearched;
		
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
		
		if (strpos($content,',"tr"')){
			$this->langSearched='tr';
			return 'tr';
		}
		
		if (strpos($content,',"en"')){
			$this->langSearched='en';
			return 'tr';
		}
		
		return false;
	}
	
	
	public function getWords(){	
		
		$this->content=strip_tags(
			mb_substr(
				$this->content,
				0,
				strpos($this->content,',"'.$this->langSearched.'"')-2
			).']'
		);
		
		$words=json_decode($this->content);
		
		$o=new \stdClass();
		$o->lang=$this->langSearched;
		
		if (isset($words[1]))
			$o->means=$words[1];
		else 	
			$o->means=$words[1];	
		return $o;
	}
	
	public function fetchTrWord($word){
		
		$path='/translate_a/t?client=t&text='.$word.'&hl=en&sl=auto&tl=en&multires=1&prev=btn&ssel=0&tsel=0&uptl=en&sc=1';

		$headers="GET ".$path." HTTP/1.1
		Host: translate.google.com
		User-Agent: Mozilla/5.0 (X11; Linux i686; rv:6.0.2) Gecko/20100101 Firefox/6.0.2
		Accept: text/html
		Accept-Language: en-us,en;q=0.5
		Accept-Encoding: deflate
		Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7
		Connection: keep-alive
		Referer: http://translate.google.com/?sl=auto&tl=en&js=n&prev=_t&hl=en&ie=UTF-8&layout=2&eotf=1&text=&file=
		Cookie: PREF=ID=c4b8828de5cd7d94:TM=1316452212:LM=1316452212:S=47j8nQd8ziFAqyPm";

		$headers=str_replace("\t",'',$headers)."\n\n";
		
		$fp=fsockopen('translate.google.com',80);
		fwrite($fp, $headers);
		$kk='';
		$s=0;
		while (!feof($fp)){
			$s++;
			$uu=fgetss($fp, 4096);
			if ($s==15) break;
		}
		fclose($fp);
		return $uu;
	}
}

?>
