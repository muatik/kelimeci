<?php
require_once('ipage.php');
class vocabularyController extends ipage {
	
	public function initialize(){
		$this->title='Kelime dağarcığı';
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
		else{
			if($this->vocabulary->getVocabularyByWord($word))
				return '0Bu kelime zaten ekli.';
		}
		return '0\''.$word.'\' kelimesi bulunamadı.';
	}

	/**
	 * remove the word from the user's vocabulary
	 * 
	 * @access public
	 * @return void
	 */
	public function rmWord($word=null){
		if($word==null && isset($this->r['word']))
			$word=$this->r['word'];

		if($word==null)
			return 'the parameter "word" is required.';

		return $this->vocabulary->rmWord($word);
	}

	/**
	 * belirtilen anahtar kelimeyle uyuşan kelimeleri önerir.
	 * */
	public function suggest(){
		$r=$this->r;
		$words=kelimeci\dictionary::suggest($r['q']);
		return arrays::makeCloud($words,'word',"\n");
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

		$o=new stdClass();
		$o->words=$words;

		if(isset($this->r['noScriptStyle']))
			$o->noScriptStyle=true;

		return $this->loadView(
			'wordList.php',
			$o,
			false
		);
	}

	public function viewword($word=null){
		if(isset($this->r['word']))
			$word=$this->r['word'];

		if($word==null)
			return '0The parameter "word" is required.';

		$word=kelimeci\dictionary::getWord($word);

		if($word===false)
			return '0Word not found!';

		// kullanıcının bu kelime için sağladı verileri
		// çeker
		if($this->isLogined)
			$word=$this->vocabulary->fillUserData($word);

		
		$o=new stdClass();
		$o->word=$word;

		// If the parameter popup=1, won't be shown the status of user info.
		if(isset($this->r['popup']))
			$o->popup=true;

		if(isset($this->r['noScriptStyle']))
			$o->noScriptStyle=true;

		return $this->loadView(
			'word.php',
			$o,
			false
		);

	}

	public function addQuote(){
		$r=$this->r;
		
		if(!isset($r['word'],$r['quote']))
			return 'The parameters \'word\' and \'quote\' are required.';


		return $this->vocabulary->addQuote($r['word'],$r['quote']);
	}

}
?>
