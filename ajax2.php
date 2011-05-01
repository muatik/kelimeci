<?php
class kelimeci
{
	
	public function __construct(){
		require_once('db.php');
		$this->db=new db();
		$this->db->database='kelimeci';
		
		$this->r=$_REQUEST;
		$this->testId=1;
		$this->userId=1;
		$this->langMode='tr2eng';
		$this->langFrom='tr';
		$this->langTo='eng';
	}
	
	public function add($word,$tags=''){
		$db=$this->db;
		
		$o= new stdClass;
		$o->word=$word;
		$o->meaing=$this->getMeaning($word);
		$o->tags=$tags;
		if($o->meaing===false) return false;
		
		$sql='insert into vocabulary
		(userId,word,wordLang,meaning,meaningLang,tags)
		values
		(
			\''.$db->escape($this->userId).'\',
			\''.$db->escape($o->word).'\',
			\''.$db->escape($this->langFrom).'\',
			\''.$db->escape($o->meaing).'\',
			\''.$db->escape($this->langTo).'\',
			\''.$db->escape($o->tags).'\'
		)';
		
		if($db->query($sql))
			return $o->meaing;
		
		return false;
	}
	
	/**
	 * belirtilen kelimenin langTo dilindeki karşılığını verir.
	 * */
	public function getMeaning($word){
		require_once('seslisozluk.php');
		$content=file_get_contents(
			'http://www.seslisozluk.com/?word='.urlencode($word)
		);
		$means=getWords($content,$word,'eng');
		$word=str_replace(array("\r","\n","\t"),'',$word);
		$means=str_replace(array("\r","\n","\t"," "),'',$means);
		$means=explode('|',$means,7);
		
		$mean='';
		for($i=0;$i<count($means) && $i<6;$i++)
			$mean.=$means[$i].', ';
		$mean=mb_substr($mean,2,-2);
		if($mean=='') return false;
		
		// bozuk karakterler siliniyor.
		$m2='';
		for($i=0;$i<strlen($mean);$i++){
			if(ord($mean[$i])==32 || ord($mean[$i])==194) 
				$m2.=' ';
			else
				$m2.=$mean[$i];
		}
		$mean=$m2;
		return $mean;
	}
	
	/**
	 * belirtilen id ile eşleşen kelimenin, word ile belirtilen
	 * kelime olup olmadığına bakar. kişiye bir kelimenin anlamı verilir.
	 * Kişi anlama karşılık gelen kelimeyi girer. Bu metod doğru olup 
	 * olmadığını kontrol eder.
	 * */
	public function correctWord($id,$word){
		$sql='select * from vocabulary
		where 
		userId='.$this->userId.' and
		id=\''.$this->db->escape($id).'\' and
		word=\''.$this->db->escape($word).'\'
		limit 1';
		$w=$this->db->fetchFirst($sql);
		
		/** 
		 * eşleşme yoksa, yani yanlış kelime yanlış ise:
		 * */
		if($w===false){
			$correct=false;
			// kelime seviyesi azaltılacak
			$level='level-1';
		}
		else{
			$correct=true;
			// kelime seviyesi arttırılacak
			
			// -2'den daha küçük değerleri daha hızlı yükselt
			if($w->level<-2)
				$level='level+2';
			else
				$level='level+1';
		}
		
		// seviye değişikliği kaydediliyor.
		$sql='update vocabulary set level='.$level.' 
		where id=\''.$id.'\' limit 1';
		$this->db->query($sql);
		
		
		if(!$correct){
			$o1=$this->get($id);
			$currentLeve=$o1->level;
			
			$s='0|'.$word;
			$o2=$this->get($word);
			if($o2!=false)
				$s.='|'.$o2->meaning;
			
			$r=$s;
		}
		else{
			$currentLeve=$w->level;
			$word=$w->word;
			$r=true;
		}
		
		$this->logExercises($id,$word,$correct,$currentLeve);
		
		return $r;
	}
	
	/**
	 * belirtilen kelime kaydını verir.
	 * parametre sayı ise id'ye göre, metin ise sözcüpe göre arama
	 * yapılır.
	 * */
	public function get($o){
		
		if(is_numeric($o))
			$field='id';
		else
			$field='word';
		
		$sql='select * from vocabulary 
		where '.$field.'=\''.$this->db->escape($o).'\'
		limit 1';
		return $this->db->fetchFirst($sql);
	}
	
	public function logExercises($vcbId,$answer,$isCorrect,$level){
		$sql='insert into exercises
		(userId,testId,vcbId,answer,isCorrect,level)
		values
		(
			\''.$this->userId.'\',
			\''.$this->db->escape($this->testId).'\',
			\''.$this->db->escape($vcbId).'\',
			\''.$this->db->escape($answer).'\',
			\''.$this->db->escape($isCorrect).'\',
			\''.$this->db->escape($level).'\'
		)';
		echo $sql;
		$this->db->query($sql);
	}
}

$x=new kelimeci();
$o=$x->correctWord(2,'inevitably');
print_r($o);
?>
