<?php
class kelimeci
{
	
	public function __construct(){
		$this->db=new db();
		$this->r=$_REQUEST;
		$this->userId=1;
		$this->langMode='tr2eng';
		$this->langFrom='tr';
		$this->langTo='eng';
	}
	
	public function addWord($word){
		
		$o= new stdClass;
		$o->user=$this->userId;
		$o->wordLang=$this->langFrom;
		$o->word=$word;
		$o->meaing=$this->getMeaning($word);
		$o->meaingLang=$this->langTo;
		if($o->meaing===false) return false;
		
		$sql='insert into vocabulary
		(userId,word,wordLang,meaing,meaingLang,tags)
		values
		(
			\''.$db->escape($o->userId).'\',
			\''.$db->escape($o->word).'\',
			\''.$db->escape($o->wordLang).'\',
			\''.$db->escape($o->meaing).'\',
			\''.$db->escape($o->meaingLang).'\',
			\''.$db->escape($o->tags).'\'
		)';
		
		if($db->query($sql))
			return $o->meaing;
		
		return false;
	}
	
	/**
	 * belirtilen kelimenin langTo dilindeki karşılığını verir.
	 * */
	public function getMeaining($word){
		
		$meaing=file_get_contents(
			'http://www.seslisozluk.com/?word='.urlencode($word)
		);
		$means=getWords($content,$w,'eng');
		$w=str_replace(array("\r","\n","\t"),'',$w);
		$means=str_replace(array("\r","\n","\t"," "),'',$means);
		$means=explode('|',$means,6);
		
		$mean='';
		for($i=0;$i<count($means) && $i<5;$i++)
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
			$w->rate--;
		}
		else{
			$correct=true;
			// kelime seviyesi arttırılacak
			
			// -2'den daha küçük değerleri daha hızlı yükselt
			if($w->rate<-2)
				$w->rate+=2;
			else
				$w->rate++;
		}
		
		// seviye değişikliği kaydediliyor.
		$sql='update vocabulary set rate=\''.$w->rate.'\' 
		where id=\''.$w->id.'\' limit 1';
		$this->db->query($sql);
		
		if(!$correct){
			
			$s='0|'.$word;
			$o=$this->get($word);
			if($o!=false)
				$s.='|'.$o->meaing;
			
			return $s;
		}
		else{
			$word=$w;
			return true;
		}
		
		
	}
	
	/**
	 * belirtilen kelime kaydını verir.
	 * */
	public function get($word){
		$sql='select * from vocabulary 
		where word=\''.$this->db->escape($word).'\'
		limit 1';
		return $this->db->fetchFirst($sql);
	}
}

function insertW($w,$tags){
	
	$content=file_get_contents('http://www.seslisozluk.com/?word='
		.urlencode($w));
	$means=getWords($content,$w,'eng');
	
	$w=str_replace(array("\r","\n","\t"),'',$w);
	$means=str_replace(array("\r","\n","\t"," "),'',$means);
	$means=explode('|',$means,6);
	
	$mean='';
	for($i=0;$i<count($means) && $i<5;$i++)
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
	
	$i=new stdClass;
	$i->id=time().rand(1,100);
	$i->tkelime=trim($mean);
	$i->ekelime=trim($w);
	$i->tags=stripslashes($tags);
	$i->date=time();
	$i->udate=time();
	$i->rate=0;
		
	$kelimeler=file_get_contents('db.txt');
	$kelimeler=unserialize($kelimeler);
	$kelimeler[]=$i;
	$kelimeler=serialize($kelimeler);
	file_put_contents('db.txt',$kelimeler);
	return $i->tkelime;
}

$r=$_REQUEST;
if(isset($r['w'],$r['tags']) && mb_strlen(trim($r['w']))>1){
	$ekelime=insertW($r['w'],$r['tags']);
	if($ekelime!==false) echo '1|'.$ekelime; else echo 0;
}

if (isset($r['word'],$r['id'])){

	$word=trim($r['word']);
	$mean=trim($r['mean']);
	$id=trim($r['id']);
	
	
	$words=unserialize(file_get_contents('db.txt'));
	foreach($words as $i=>$w){
		if($w->id==$id){
			if(
				strpos($w->ekelime,$word)!==false &&
				(mb_strlen($w->ekelime)-mb_strlen($word)<3)
			){
				$words[$i]->udate=time();
				
				// -2'den daha küçük değerleri daha hızlı yükselt
				if($words[$i]->rate<-2)
					$words[$i]->rate+=2;
				else
					$words[$i]->rate++;
				
				echo 1;
				break;
			}
			else{
				
				echo '0|'.$words[$i]->ekelime;
				$words[$i]->rate--;
				
				/**
				 * tamam, girilen kelime sorulan ile eşleşmiyor
				 * peki girilen hangi kelime ile eşleşiyor? işte bunu
				 * buluyoruz.
				 * */
				foreach($words as $i=>$w){
					if(
						strpos($w->ekelime,$word)!==false &&
						(mb_strlen($w->ekelime)-mb_strlen($word)<3)
					){
						echo '|'.$w->tkelime;
						break;
					}
				}
				
				
				break;
			}
		}
	}
	file_put_contents('db.txt',serialize($words));
}

// $content içerik
// $word aranacak kelime
// $lang kelimenin dili
function getWords($content,$word,$lang){
	
	if ($lang=='eng') $tableId='dc_en_tr';
	elseif ($lang=='tr') $tableId='dc_tr_en';
	else $tableId='dc_tr_en';
	
	$domDoc=new DOMDocument();
	@$domDoc->loadHTML($content);
	@$domXPath = new DOMXPath($domDoc);
	$elements=$domXPath->query("//*[@id='".$tableId."']");
	$words='';	
	
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
									if (trim($node->nodeValue)!=$word) break;
									
								if ($node->getAttribute('class')=='m'){
																		
									$cChildNodes = $node->childNodes; // tür için
									$value='';
									foreach($cChildNodes as $nodeC)	{
										if ($nodeC->nodeName=='#text' && empty($value))
										$value=$nodeC->nodeValue;
										
									}
									
									$words.='|'.trim($value);
								} 
							}  
						} 
					} 
				}
			}
		}	
	}
	return $words;
}
?>
