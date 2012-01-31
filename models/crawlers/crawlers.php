<?php
namespace crawlers;
class crawlers
{		
	public $db;
	
	public $wordId;

	public function __construct(){
		$this->db=new \db();
	}	

	/**
	 * gönderilen kelimeye ait bilgileri toplar ve kaydetme sınıfına gönderir
	 * dictionary,seslisozluk,urban,google
	 * @param string $word	öğrenilecek kelime
	 * 
	 * @return bool
	 * */
	public function learn($word){
		
		if(mb_strlen($word)<2)
			return false;
		
		require_once("googleC.php");
		require_once("urbanC.php");
		require_once("seslisozlukC.php");
		require_once("dictionaryC.php");
		
		header('content-type:text/html;charset=utf-8');
		
		$this->queriedWord=$word;

		$this->wordId=$this->insertWord($word);
		
		$crwl1=$this-getContent('dictionary');
		$crwl2=$this-getContent('google');
		$crwl3=$this-getContent('seslisozluk');
		
		// eğer kelime için herhangi bir anlam bulundu ise
		// alıntı alım fonksiyonuna gönderiliyor. Yok ise siliniyor.
		if ($crwl1 || $crwl2 || $crwl3){
			$this->findQuotesInDb($data);
			
			// aranan kelimenin kendisinin statusu değiştiriliyor.
			// yeni eklenen kelimeler için tekrar crawl edilecektir.
			$sql='update words set status=\'1\' where 
				id=\''.$this->wordId.'\')';
			$this->db->query($sql);
		}
		else {
			$sql='delete from words where id=\''.$this->wordId.'\')';
			$this->db->query($sql);
		}		
	}
	
	public function getContent($page){
		
		$content=false;	
		$content=$this->isWebPageCrawled($this->wordId,$page);
		
		switch($page){
			case 'dictionary': $pageC=new dictionaryC(); break;
			case 'google':  $pageC=new googleC(); break;
			case 'seslisozluk': $pageC=new seslisozlukC(); break;			
		}
			
		// eğer content var ise o content gönderiliyor.
		if ($content) 
			$data=$pageC->get($word,$content);
		else 
			$data=$pageC->get($word);
		
		if ($data && (count($data->partOfSpeech[0]->means)>0 ||
			count($data->partOfSpeech[1]->means)>0)){
			$this->save($data);
			return true;
		}
		else 
			return false;

	}
	
	
	/**
	 * gelen word objesi içindeki verileri uygun 
	 * methotlara göndererek kayıt işlemini yapar.
	 * 
	 * @param object	$o
	 * @return bool
	 * */
	public function save($o){
		
		$wordId=$this->wordId;	
		
		if (isset($o->class))
			$this->insertWordOfClass($wordId,$o->class);
		$this->insertWordInfo($wordId,'lang',$o->lang);

		$this->insertWordInfo($wordId,'pronunciation',
			$o->pronunciation);

		$this->insertWordInfo($wordId,'etymology',
			$o->etymology);


		$this->insertContent($wordId,$o->webPageName,$o->content);
		$this->insertSynonyms($wordId,$o->synonyms,$o->webPageName);
		$this->insertAntonyms($wordId,$o->antonyms,$o->webPageName);
		
		$this->insertMeans(
			$wordId,
			$o->partOfSpeech,
			$o->webPageName
		);

	}
	
	/**
	 * gönderilen kelimeyi veritabanına kaydeder,
	 * kelime var ise id'sini geri döndürür.
	 * 
	 * @param string word
	 * 
	 * @return int
	 * */
	public function insertWord($word){

		$word=$this->db->escape(trim($word));

		$sql='select id from words where word=\''.$word.'\'';		
		$r=$this->db->fetchFirst($sql);
		
		if ($r) 
			return $r->id;
		else {
			$sql='insert into words(word) values(\''.$word.'\')';
			$this->db->query($sql);
			return $this->db->getInsertId();
		}
	}
	
	/**
	 * gelen classname'i kaydeder id'sini geri döndürür.
	 * var ise o kaydın id'sini geri döndürür.
	 * 
	 * @param string $clsName
	 * 
	 * @return int
	 * */
	public function insertClass($clsName){
		
		$clsName=$this->db->escape(trim($clsName));
		$sql='select * from classes where name=\''.$clsName.'\' ';
		$r=$this->db->fetchFirst($sql);
		
		if (!$r){
			$sql='insert into classes(name) values(\''.$clsName.'\')';
			$this->db->query($sql);
			$clsId=$this->db->getInsertId();
		} else $clsId=$r->id;
		
		return $clsId;
	}
	
	/**
	 * wordId için çekilen content kaydedilir.
	 * 
	 * @param int $wordId
	 * @param string $webPageName google,urban,dictionary...
	 * @param text $content
	 * 
	 * @return int
	 * */
	public function insertContent($wordId,$webPageName,$content){
		
		$sql='select * from wordContents where wId=\''.$wordId.
			'\' and webPageName=\''.$webPageName.'\'';
		$contentHave=$this->db->fetchFirst($sql);
		
		if (!$contentHave){	
			$sql='insert into wordContents(wId,webPageName,content) 
				values(\''.$wordId.'\',
				\''.$webPageName.'\',
				\''.$this->db->escape($content).'\')';
			return $this->db->query($sql);
		}
		
		return false;
	}
	
	
	/**
	 * kelime ile className ilişkilendirilerek kaydedilir.
	 * 
	 * @param int $wordId
	 * @param string $clsName
	 * 
	 * @return bool
	 * */
	public function insertWordOfClass($wordId,$clsName){
		
		$clsId=$this->insertClass($clsName);
		
		$sql='select * from wordClasses where wId=\''.$wordId.
			'\' and clsId=\''.$clsId.'\'';
		$clsHave=$this->db->fetchFirst($sql);
		if (!$clsHave){
			$sql='insert into wordClasses(wId,clsId) values(\''.
				$wordId.'\',\''.$clsId.'\')';
			return $this->db->query($sql);
		} 
		
		return true;			
	}
	
	
	/**
	 * kelimeyle ilgili etimology,okunuşu vb bilgileri tutar
	 * 
	 * @param int $wordId
	 * @param string name
	 * @param string value
	 * 
	 * @return bool
	 * */
	public function insertWordInfo($wordId,$name,$value){

		$name=$this->db->escape(trim($name));
		$value=$this->db->escape(trim($value));

		$sql='select id,value from wordInfo where wId=\''.
			$wordId.'\' and name=\''.$name.'\' limit 1';
		$r=$this->db->fetchFirst($sql);

		if (!$r && !empty($value)){			
			$sql='insert into wordInfo (wId,name,value) values(
				\''.$wordId.'\',\''.$name.'\',\''.$value.'\')';
			return  $this->db->query($sql);
		}
		
		return true;
	}	
	
	/**
	 * kelimenin eş anlamlarını kaydeder
	 * 
	 * @param int $wordId
	 * @param array synonyms
	 * 
	 * @return bool
	 * */
	public function insertSynonyms($wordId,$synonyms,$page){
		
		if (count($synonyms)==0) return false;
		
		foreach($synonyms as $in){
			
			if(!isset($in->synonyms)) 
				continue;

			foreach($in->synonyms as $syn){
					
					if (empty($syn)) continue;
					
					$synId=$this->insertWord($syn);
					
					$sql='select wid from synonyms where wId=\''.
						$wordId.'\' and synId=\''.$synId.'\' limit 1';
				
					$r=$this->db->fetchFirst($sql);
					
					if (!$r){
						$sql='insert into synonyms(wId,synId,page) 
							values(
								\''.$wordId.'\',
								\''.$synId.'\',
								\''.$page.'\')';
						$this->db->query($sql);
					} else continue;
			}
		}
		return true;
	}
	
	
	/**
	 * kelimenin zıt anlamlarını kaydeder
	 * 
	 * @param int $wordId
	 * @param array antonyms
	 * 
	 * @return bool
	 * */
	public function insertAntonyms($wordId,$antonyms,$page){
		
		if (count($antonyms)==0) return false;
		
		foreach($antonyms as $in){
			
			if(!isset($in->antonyms)) 
				continue;				
				
			foreach($in->antonyms as $ant){
				
					if (empty($ant)) continue;
					
					$antId=$this->insertWord($ant);
						
					$sql='select wid from antonyms where wId=\''.
						$wordId.'\' and antId=\''.$antId.'\' limit 1';
					$r=$this->db->fetchFirst($sql);
					
					if (!$r){
						$sql='insert into antonyms(wId,antId,page) 
							values(
							\''.$wordId.'\',
							\''.$antId.'\',
							\''.$page.'\')';
						$this->db->query($sql);

					} else continue;
			}
		}
		return true;
	}
	
	/**
	 * kelimenin anlamlarını kaydeder
	 * 
	 * @param int $wordId
	 * @param array means
	 * @param string webPageName
	 * 
	 * @return bool
	 * */
	public function insertMeans($wordId,$partOfSpeech,$webPageName){		
		if (count($partOfSpeech)==0) return true;
		
		foreach($partOfSpeech as $in){
					
			if (count($in->means)==0) return true;
			
			foreach($in->means as $mean){

				$clsId=$this->insertClass($mean[0]);
				foreach ($mean[1] as $k){
					
					$sql='select id from meanings where  
						wId=\''.$wordId.'\',lang=\''.$in->lang.
						'\',clsId=\''.$clsId.'\',meaning=\''.
						$this->db->escape(trim($k)).'\'';						
					if (!$this->db->fetchFirst($sql)){
						$sql='
							insert into 
								meanings(wId,lang,clsId,meaning,page) 
							values(\''.$wordId.'\',
								\''.$in->lang.'\',
								\''.$clsId.'\',
								\''.$this->db->escape(trim($k)).'\',
								\''.$webPageName.'\') ';
						$this->db->query($sql);
					}
				}
			}
		}
		
		return true;
	}		
	
	
	/**
	 * gönderilen kelime id'sine ait kelime 
	 * google,urban,dictionary,seslisozluk sayfalarında tarandımı kontrol eder.
	 * eğer tarandı ise contenti gönderir.
	 * 
	 * @param int $wordId
	 * @param string $pageName
	 * 
	 * @return string
	 * */
	public function isWebPageCrawled($wordId,$pageName){

		$sql='select id,content from wordContents where wId=\''.$wordId.'\' 
			and webPageName=\''.$pageName.'\' limit 1';
		$r=$this->db->fetchFirst($sql);
		
		if ($r) 
			return $r->content;
		else 
			return false;		
	}


	/**
	 * findQuotesInDb 
	 * 
	 * @access public
	 * @return void
	 */
	public function findQuotesInDb($word){
		$flacs='';
		
		switch($word->class){
			case 'noun': $flacs='(isim|ist|ment|tion|er|or|s|es)?'; break;
			case 'verb': 
			default:$flacs='(s|ing|ed|d)?'; 
		}
		
		$sql='insert into wordQuotes select '.$this->wordId.' as wId, id as quoteId 
			from quotes where
			quote regexp \'(^| )'.$this->queriedWord.''.$flacs.'($| )\'
			order by length(quote) asc
			limit 100';

		return $this->db->query($sql);
	}

}
?>
