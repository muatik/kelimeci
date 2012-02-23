<?php
require_once('../_config.php');
require_once('../moduler/libraries/db/db.php');
$db=new db();

$sql='select * from words limit %d,%d';
$page=0;
$length=5000;

$words=$db->fetch(sprintf($sql,$page,$length));

class quoteFinder{
	
	public function __construct(){
		$this->db=new db();
	}

	public function findForAllWords(){

		$scanList=file_get_contents('/var/www/kelimeci/scripts/scanWord.log');
		$page=0;
		$limit=5000;
		$sql='select * from words 
			where word like \'%% %%\' limit %d,%d';

		$words=$this->db->fetch(sprintf($sql,$page*$limit,$limit));
		
		while(count($words)>0){

			file_put_contents('/var/www/kelimeci/scripts/scanWord.log',"\n".'page='.$page."\n\n",FILE_APPEND);
			foreach($words as $word){
				
				// eğer taranmışsa tekrar tarama
				$isScanned=$this->db->fetchFirst('
					select * from wordQuotes where wId='.$word->id.' limit 1'
				);
				
				if($isScanned) continue;

				if(strpos($scanList,'w:'.$word->word)!==false) continue;
				
				echo 'page='.$page.', '.$word->word."\n";
				$this->scan($word);
			}
			
			$page++;
			$words=$this->db->fetch(
				sprintf($sql,$page*$limit,$limit)
			);
		}
	}

	public function findForWord($word){

		$sql='select * from words 
			where word=\''.$this->db->escape($word).'\' limit 1';

		$word=$this->db->fetchFirst($sql);
		$this->scan($word);
	}

	public function getInflections($word){
		$word=trim($word);
		$suffixes=array();
		$vowels=array('a','e','i','o','u');
		
		$sql='select * from words as w,wordClasses as wc, classes as c 
			where w.word=\''.$word.'\' and
			w.id=wc.wId and
			c.name in (\'fiil\',\'verb\') and
			c.id=wc.clsId limit 1';
		$isVerb=$this->db->fetchFirst($sql);
		
		$lLetter=mb_substr($word,-1,1);
		$l2Letter=mb_substr($word,-2);
		//echo $lLetter.'-'.$l2Letter.' -- ';

		if($isVerb && $lLetter!='o'){
			
			

			if(in_array($lLetter,$vowels)){
				$suffixes[]='d'; // approve+d
			}
			elseif($lLetter=='y'){
				//$suffixes[]='ied'; // cry>cried
				$suffixes[]='ed'; // played
			}
			else{
				if(mb_strlen($word)<5){
					$suffixes[]=$lLetter.'ed'; // plan+ned
					$suffixes[]=$lLetter.'ing'; // running
				}
				
				$suffixes[]='ed'; // work+ed
				
				$suffixes[]='ing'; // jumping
			}

		}

		/*
		$sql='select * from words as w,wordClasses as wc, classes as c 
			where w.word=\''.$word.'\' and
			w.id=wc.wId and
			c.name in (\'isim\',\'ad\',\'noun\') and
			c.id=wc.cId limit 1';
		$isNoun=$this->db->fetchFirst($sql);
		
		if($isNoun){
			// plural inflection is being into $suffixes below	
		}
		
		*/

		// being verb or noun isn't important for these:
		
		if(in_array($l2Letter,array('ch','sh','x','s')))
			$suffixes[]='es';
		else
			$suffixes[]='s';
		

		$sx=implode('|',$suffixes);
		$parts=explode(' ',$word,3); // example: fire up, cut off
		
		if($parts[0]=='be'){
			unset($parts[0]);
			$parts=array_merge($parts,array());
		}

		if(count($parts)>1)
			// matches with "fire+(s/d/ing) up" etc.
			$regex="(^| )$parts[0]($sx)* $parts[1](\\\.| |$)";
		else
			$regex="(^| )$word($sx)*(\\\.| |$)";

		return $regex;
	}

	public function scan($word){
		
		$wordId=$word->id;
		$word=$this->db->escape($word->word);
		$regex=$this->getInflections($word);
		
		$sql='select * from quotes where 
			quote regexp \''.$regex.'\'
			order by length(quote) asc limit 1000';
		$quotes=$this->db->fetch($sql);
		//echo $sql."\n\n";

		if(count($quotes)<1)
			return false;

		$qIds=array();
		foreach($quotes as $q) $qIds[]=$q->id;
		
		$sql='insert ignore into wordQuotes (wId,quoteId) values
		(\''.$wordId.'\',\''.implode('\'),(\''.$wordId.'\',\'',$qIds).'\')';
		$this->db->query($sql);
		
		file_put_contents('/var/www/kelimeci/scripts/scanWord.log','w:'
			.$word.', q:'.count($qIds)."\n",FILE_APPEND);
		

	}

	
}

$q=new quoteFinder();
$q->findForAllWords();
?>
