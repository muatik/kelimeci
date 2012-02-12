<?php
namespace kelimeci;
use \stdClass;
use \arrays,\db,\strings;
/**
 * the class tests prepares and evaluates tests
 * 
 * @copyright copyleft
 * @author Mustafa Atik <muatik@gmail.com>
 * @date 07 Sep 2011 01:01
 */
class tests
{

	/**
	 * id of a user that the tests will be prepared for
	 * 
	 * @var float
	 * @access public
	 */
	public $userId=0;

	/**
	 * the miminum hours that must be passed 
	 * between test for each word
	 * 
	 * @static
	 * @var mixed
	 * @access public
	 */
	public static $minInterval;
	
	/**
	 * consists of levels and day intervals
	 * 
	 * @static
	 * @var array
	 * @access public
	 */
	public static $levels=array();
	
	/**
	 * indicates if this class is initiliazed.
	 * 
	 * @static
	 * @var bool
	 * @access public
	 */
	public static $initialized=false;

	/**
	 * static __construct 
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function init(){
		if(self::$initialized)
			return false;
		
		//level=>interval, specified in days
		$levels=array(
			0=>0,
			1=>1,
			2=>1,
			3=>2,
			4=>2,
			5=>3,
			6=>3,
			7=>5,
			8=>6,
			9=>6,
			10=>6,
			11=>15,
			12=>15,
			13=>15,
			14=>25,
			15=>30,
			16=>30,
			17=>45,
			18=>60,
			19=>150,
			20=>350
		);
		
		self::$levels=$levels;
		
		self::$initialized=true;
	}

	public function __construct(){
		self::init();
		self::$minInterval=0;
		$this->db=new \db();
	}
	
	public static function getIdOfTestType($testType){
		
		$db=new \db();

		$r=$db->fetchFirst('select * from testTypes
			where 
			name=\''.$db->escape($testType).'\'
			limit 1');

		if($r===false)
			return $r;

		return $r->id;
	}

	/**
	 * pepare a test for a user
	 * 
	 * @param string $testType 
	 * @access public
	 * @return object contains test items and other details
	 */
	public function prepare($testType){
		/*
		'fetch words of user for the test
		preapre test items by each word
		preapre test info
		return'
		*/

		$test=new \stdClass();
		$test->items=array();
		$testWords=$this->getWordsForTest($testType);
		foreach($testWords as $i){
			$d=self::getTestData($testType,$i,$this->userId);
			if($d!=false)
				$test->items[$i->id]=$d;
		}

		
		$test->type=$testType;
		$test->userId=$this->userId;
		$test->created=date('Y-m-d H:i:s');
		$test->count=count($test->items);
		$test->estimatedTime=($test->count*30); // in seconds
		
		/**
		 * Turkish meaning of the test type for the title of test page
		 *
		 * TEMPRORY CODES (HARD CODED) 
		 * 	- MUST BE DB-MANAGED
		 */
		switch($testType){
			case 'sentenceCompletion':
				$titleInTr='Cümle Tamamlama Testi';
				break;
			case 'synonymSelection':
				$titleInTr='Eşanlamlıları Seçme Testi';
				break;
			case 'variationWriting':
				$titleInTr='Varyasyonları Yazma Testi';
				break;
			case 'categorySelection':
				$titleInTr='Kategorilerini Seçme Testi';
				break;
			case 'englishWriting':
				$titleInTr='İngilizcesini Yazma Testi';
				break;
			case 'turkishWriting':
				$titleInTr='Türkçesini Yazma Testi';
				break;
			case 'voice':
				$titleInTr='Duyulan Kelimeyi Yazma Testi';
				break;
		}

		$test->titleInTr=$titleInTr;
		
		return $test;
	}
	
	
	/**
	 * returns words which are ready to be tested.
	 *
	 * @param string $testType 
	 * @access public
	 * @return array words
	 */
	public function getWordsForTest($testType){
		
		$time=time();

		$interval=date(
			'Y-m-d H:i:00',
			$time-(self::$minInterval*3600)
		);

		// calculation dates of levels
		$levelConds=array();
		foreach(self::$levels as $level=>$i)
			$levelConds[$level]=date(
				'Y-m-d H:i:00',
				$time-(3600*24*$i)
			);
		
		//preparing condititions of levels
		foreach($levelConds as $level=>$i)
			$levelConds[$level]='(v.level='
				.$level.' and t.crtDate<"'.$i.'")';
		
		$testId=self::getIdOfTestType($testType);
		
		$sql='
		select 
			v.*
		from 
			vocabulary as v
			left join (
				select * from tests
				where
					userId='.$this->userId.' and
					testTypeId='.$testId.'
				group by wordId
				order by crtDate desc
			) as t on
				t.wordId=v.wordId and
				t.userId=v.UserId
		where
		
		v.userId='.$this->userId.' and

		(
			(t.id is null or t.result=0) and 
			v.crtDate<"'.$interval.'"
		)
		or
		(
			t.result=1 and
			(
				'.implode(' or ',$levelConds).'
			)
		)';
		
		$rs=$this->db->fetch($sql);
		$words=array();
		foreach($rs as $i)
			$words[]=dictionary::getWord($i->wordId);
		
		return $words;
	}





	### BEGIN OF TEST DATA METHODS ###
	
	/**
	 * returns data of a word which is required to prepare a test
	 * In other words, returns item of question of a word
	 * 
	 * @param mixed $testType 
	 * @param mixed $word 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getTestData($testType,$word,$userId){
		
		switch($testType){
			case 'sentenceCompletion':
				return self::getItemOfSentenceCompletion($word,$userId);
			case 'synonymSelection':
				return self::getItemOfSynonymSelection($word);
			case 'variationWritingTest':
			case 'categorySelection':
				return self::getItemOfCategorySelection($word);
			case 'englishWriting':
				return self::getItemOfEnglishWriting($word);
			case 'turkishWriting':
				return self::getItemOfTurkishWriting($word);
			case 'voice':
				return self::getItemOfVoiceTest($word);
		}
		return false;
	}

	/**
	 * returns data of a word whic is require to peare a sentence 
	 * completion test 
	 *
	 * @param words $word 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getItemOfSentenceCompletion($word,$userId){
		$vcb=new vocabulary($userId);
		$item=new stdClass();
		$word->uQuotes=$vcb->getUserQuotes($word->id);
		
		// just the first 10 quotes of the word because the quotes are 
		// ordered by length asc, and shorter is better.
		$quotes=array_merge(
			array_slice($word->quotes,0,10), 
			$word->uQuotes
		);
		
		shuffle($quotes);
		
		if(!is_array($quotes) || count($quotes)==0)
			return false;
		

		$selQuote=$quotes[0];
		$selQuote->quote=preg_replace(
			'/'.$word->word.'/i',
			'[...]',
			$selQuote->quote
		);
		

		$clues=dictionary::getRandomWords(4);
		foreach($clues as $k=>$i)
			$clues[$k]=dictionary::getWord($i);

		$clues[]=$word;
		shuffle($clues);

		$item->wordId=$word->id;
		$item->quoteId=$selQuote->id;
		$item->sentence=$selQuote->quote;
		$item->clue=\arrays::toArray($clues,'word');
		return $item;
	}

	/**
	 * getItemOfSynonymSelection 
	 * 
	 * @param mixed $word 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function getItemOfSynonymSelection($word){
		
		$synonyms=$word->synonyms;
		if(count($synonyms)==0)
			return false;
		
		shuffle($synonyms);
		$synonyms=array_slice($synonyms,0,4);
	
		$item=new stdClass();
		$item->wordId=$word->id;
		$item->word=$word->word;
		$item->options=array();
		
		$item->options=arrays::toArray(
			$synonyms, 'word'
		);

		$rws=dictionary::getRandomWords(3);
		foreach($rws as $i){
			$i=dictionary::getWord($i);
			$item->options[]=$i->word;
		}
		
		shuffle($item->options);

		return $item;
	}


	/**
	 * getItemOfSynonymSelection 
	 * 
	 * @param mixed $word 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function getItemOfEnglishWriting($word){
		
		$meanings=dictionary::getMeaningsByLang($word->id,'tr');
		if(count($meanings)==0)
			return false;
		
		if(count($meanings)>0)
			$rndIds[]=0;

		foreach($word->classes as $i)
			foreach($meanings as $k=>$m)
				if($m->clsId==$i->id)
					$rndId[]=$k;

		$rndIds[]=array_rand($meanings);
		$rndIds[]=array_rand($meanings);
		$rndIds[]=array_rand($meanings);
		$rndIds[]=array_rand($meanings);
		$rndIds=array_unique($rndIds);
		$rndIds=array_slice($rndIds,0,4);
		shuffle($rndIds);
		

		$item=new stdClass();
		$item->wordId=$word->id;
		$item->meaning=arrays::concatFields(
			arrays::toArray($meanings,'meaning'),
		       	$rndIds,', '
		);
		$item->classes=arrays::toArray($word->classes,'name');
		return $item;
	}


	/**
	 * getItemOfEnglishWriting 
	 * 
	 * @param mixed $word 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function getItemOfTurkishWriting($word){
		
		$meanings=dictionary::getMeaningsByLang($word->id,'en');
		if(count($meanings)==0)
			return false;
		
		$sel=array_rand($meanings);

		$item=new stdClass();
		$item->wordId=$word->id;
		$item->meaning=$word->word;
		$item->classes=arrays::toArray($word->classes,'name');
		return $item;
	}
	
	/**
	 * getItemOfCategorySelection
	 * 
	 * @param mixed $word 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function getItemOfCategorySelection($word){
		$item=new stdClass();
		$item->wordId=$word->id;
		$item->word=$word->word;
		return $item;
	}
	
	/**
	 * getItemOfVoiceTest
	 * 
	 * @param mixed $word 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function getItemOfVoiceTest($word){
		$item=new stdClass();
		$item->wordId=$word->id;
		$item->mediaFile=$word->word;
		return $item;
	}


	### END OF TEST DATA METHODS ###
	



	
	### BEGIN OF VERIFICATION METHODS ###


	/**
	 * verify a test answer that's represented by a result object
	 *
	 * the parameter test contains at least two properties which are
	 * "name" that specifies the name of the test, and "wordId" that
	 * specifies the word which are being test.
	 *
	 * and the parameter must have various properties corresponded to
	 * the test.
	 *
	 * @param object $test 
	 * @static
	 * @access public
	 * @return bool
	 */
	public function validate($test){
		switch($test->name){
			case 'sentenceCompletionTest':
				return $this->validateSentenceCompletion(
					$test->wordId,
					$test->quoteId,
					mb_strtolower($test->answer)
				);
			case 'variationWritingTest':
				return $this->validateVariationWriting(
					$test->wordId,
					$test->variations
				);
			case 'categorySelectionTest':
				return $this->validatecategorySelection(
					$test->wordId,
					$test->selected
				);
			case 'synonymSelectionTest':
				return $this->validatesynonymSelection(
					$test->wordId,
					$test->selected
				);
			case 'englishWritingTest':
				return $this->validateEnglishWriting(
					$test->wordId,
					$test->answer
				);
			case 'turkishWritingTest':
				return $this->validateTurkishWriting(
					$test->wordId,
					$test->answer
				);
		}

		return false;
	}
	
	/**
	 * validate answer for a sentence completion test
	 * 
	 * @param int $wordId
	 * @param int $quoteId 
	 * @param string $answer 
	 * @access public
	 * @return bool
	 */
	public function validateSentenceCompletion($wordId,$quoteId,$answer){
		$word=dictionary::getWord($wordId);
		
		$r=new \stdClass();
		$r->wordId=$word->id;

		if($word->word==trim($answer))
			$r->result=true;
		else{
			$r->result=false;
			$r->answer=$word->word;
			
			if($cword=dictionary::getword($answer))
				$r->correction=$cword->word;
		}
		
		return $r;
	}


	/**
	 * validate answer for a variation writing test
	 * 
	 * @param int $wordId
	 * @param array $variations array of 
	 * objects which are consist of {object->className, object->answer}
	 * @access public
	 * @return bool
	 */
	public function validateVariationWriting($wordId,$variations){
		
		if($wordId==1){
			/*
			$variation[0]->className='';
			$variation[0]->answer='';
			 */

			$answers='';
			foreach($variations as $i)
				$answers.=$i->answer;
			
			if($answers=='access,access,accessible')
				return '{"wordId":1,"result":true}';
			else
				return '{"wordId":1,"result":false,
					"correction":[
						["noun","access"],
						["verb","access"],
						["adjective","accessible"]
					]}';
		}
		elseif($wordId==2){

			/*
			$variation[0]->className='';
			$variation[0]->answer='';
			 */

			$answers='';
			foreach($variations as $i)
				$answers.=$i->answer;

			if($answers=='noun,verb,adjective')
				return '{"wordId":2,"result":true}';
			else
				return '{"wordId":2,"result":false,
					"correction":[
						["noun","meaning"],
						["verb","mean"],
						["adjective","meaningfull"]
					]}';
		}

	}


	/**
	 * validate answer for a category selection test
	 * 
	 * @param int $wordId
	 * @param array $selected selected categories by the user
	 * @access public
	 * @return bool
	 */
	public function validateCategorySelection($wordId,$selected){

		$word=dictionary::getWord($wordId);
		
		$result=true;
		foreach($word->classes as $c){
			if(!in_array($c->name,$selected)){
				$result=false;
				break;
			}
		}

		$r=new stdClass();
		$r->wordId=$word->id;
		if($result && count($word->classes)==count($selected)){
			$r->result=true;
		}
		else{
			$r->result=false;
			$r->correction=arrays::toArray(
				$word->classes,'name'
			);
		}
		
		return $r;
	}


	/**
	 * validate answer for a category selection test
	 * 
	 * @param int $wordId
	 * @param array $selected selected synonyms by the user
	 * @access public
	 * @return bool
	 */
	public function validateSynonymSelection($wordId,$selected){
		
		
		$word=dictionary::getWord($wordId);
		$r=new \stdClass();
		$r->wordId=$word->id;
		$word=dictionary::getWord($wordId);
		
		$synonyms=arrays::toArray($word->synonyms,'word');
		$intersect=array_uintersect($synonyms,$selected,'strcasecmp');

		// max 8 words can be selected
		if(count($synonyms)>7 && count($intersect)==8)
			$r->result=true;
		elseif(count($synonyms)==count($intersect))
			$r->result=true;
		else
			$r->result=false;


		$r->corrections=arrays::toArray($word->synonyms,'word');
		
		return $r;
	}


	/**
	 * validate answer for a english writing
	 * 
	 * @param int $wordId
	 * @param string $answer
	 * @access public
	 * @return bool
	 */
	public function validateEnglishWriting($wordId,$answer){
		$word=dictionary::getWord($wordId);

		$r=new stdClass();
		$r->wordId=$word->id;
		
		if( mb_strtolower($word->word)==mb_strtolower(trim($answer)) )
			$r->result=true;
		else{
			$r->result=false;
			$r->answer=$word->word;

			if(($cWord=dictionary::getWord($answer))!==false)
				$r->correction=$cWord->word;
		}

		return $r;
	}


	/**
	 * validate answer for a turkish writing
	 * 
	 * @param int $wordId
	 * @param string $answer
	 * @access public
	 * @return bool
	 */
	public function validateTurkishWriting($wordId,$answer){
		
		$word=dictionary::getWord($wordId);
		
		$r=new stdClass();
		$r->wordId=$word->id;
		
		$meanings=dictionary::getMeaningsByLang($word->id,'tr');
		$answer=mb_strtolower(trim($answer));
		$result=false;
		foreach($meanings as $i)
			if(mb_strtolower($i->meaning)==$answer){
				$result=true;
				break;
			}
		
		if($result)
			$r->result=$result;
		else{
			$r->result=false;

			$meanings=arrays::toArray($meanings,'meaning');
			shuffle($meanings);
			$r->answer=implode(' | ',$meanings);
			$r->corrections=array();

			$wmeanings=dictionary::getWordsByMeaning($answer);
			foreach($wmeanings as $i){
				$cWord=dictionary::getWord($i->wId);
				$className=dictionary::getClassById(
					$i->clsId
				);

				$r->corrections[]=$cWord->word
					.' = ('.$className.')'.$i->meaning;
			}
			

			if(($cWord=dictionary::getWord($answer))!==false)
				$r->correction=$cWord->word;
		}

		return $r;
		if($wordId==22){
		if($answer=='mükemmel')
			return '{"wordId":22,"result":true}';
		else
			$h='{"wordId":22,"result":false,
			"answer":"mükemmel"';
			if($answer=='araba')
			$h.=',"correction":"car"';
			$h.='}';
			return $h;
		}
		elseif($wordId==14){
		if($answer=='sürat')
			return '{"wordId":14,"result":true}';
		else
			$h='{"wordId":14,"result":false,
			"answer":"sürat"';
			if($answer=='araba')
			$h.=',"correction":"car"';
			$h.='}';
			return $h;
		}

		return false;
	}

	### END OF VERIFICATION METHODS ###


}

?>
