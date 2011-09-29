<?php
require_once('ipage.php');
class vocabularyController extends ipage {
	
	public function initialize(){
		parent::initialize();
	}

	public function addWord(){
		$r=$this->r;

		if(!isset($r['word']))
			return 'the parameter "word" is required.';
		$word=$r['word'];

		if(!isset($r['tag']))
			$tag=$r['tag'];
		else
			$tag=$r['tag'];

		$a=$this->vocabulary->addWord($word,$tag);
		if($a==true){

			$t=new stdClass();
			$word=\kelimeci\dictionary::getWord($word);
			$t->classes=arrays::toArray($word->classes,'name');
			$t->id=$word->id;
			$t->word=$word->word;
			$t->level=0;
			return json_encode($t);
		}
		return 0;
	}

	public function viewwordList(){
		$r=$this->r;
		
		// default assaignments for the word list
		$start=0;
		$length=100;
		$levelMin=-20;
		$levelMax=20;
		$keyword=null;
		$orderBy='alphabetically';
		$classes=null;

		if(isset($r['start']) && is_numeric($r['start']))
			$start=$r['start'];
		if(isset($r['length']) && is_numeric($r['length']))
			$length=$r['length'];
		if(isset($r['levelMax']) && is_numeric($r['levelMax']))
			$levelMax=$r['levelMax'];
		if(isset($r['levelMin']) && is_numeric($r['levelMin']))
			$levelMin=$r['levelMin'];
		if(isset($r['keyword']))
			$keyword=$r['keyword'];
		if(isset($r['orderBy']))
			$orderBy=$r['orderBy'];
		if(isset($r['classes']) && is_array($r['classes']))
			$classes=$r['classes'];


		$words=$this->vocabulary->getWords(
			$start,
			$length,
			$classes,
			$keyword,
			$levelMin,
			$levelMax,
			$orderBy
		);
		
		return $this->loadView(
			'wordList.php',
			$words,
			false
		);
	}

}
?>
