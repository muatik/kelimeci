<?php
namespace kelimeci;
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
	public static $level=array();
	


	/**
	 * static __construct 
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function tests(){
		
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
	}


	public static function getIdOfTestType($testType){
		
		$db=new db();

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
	 * @static
	 * @access public
	 * @return object contains test items and other details
	 */
	public static function pepare($testType){
		/*
		'fetch words of user for the test
		preapre test items by each word
		preapre test info
		return'
		*/

		$test=new stdClass();
		$test->items=array();
		$testWords=$this->getWordsForTest($testType);

		foreach($testWords as $i)
			$test->items[$i->id]=self::getTestDate($testType,$i);

		
		$test->type=$testType;
		$test->userId=$this->userId;
		$test->created=date('Y-m-d H:i:s');
		$test->count=count($test->items);
		$test->estimatedTime=($test->count*30); // in seconds
		
		return $test;
	}
	
	
	/**
	 * returns words which are ready to be tested.
	 *
	 * @param string $testType 
	 * @static
	 * @access public
	 * @return array words
	 */
	public static function getWordsForTest($testType){
		
		$time=time();

		$interval=date(
			'Y-m-d H:i:00',
			$time-(self::minInterval*3600)
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
				.$level.' and t.date<"'.$i.'")';
		
		$testId=self::getIdOfTestType($testType);
		
		$sql='
		select 
			v.*
		from 
			vocabulary as v
			left join (
				select * from tests
				group by wordId
				where
					userId='.$this->userId.' and
					testTypeId='.$testId.'
				order by crtDate desc
			) as t on
				t.wordId=v.wordId
				t.userId=v.UserId
		where
		
		
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
	public static function getTestData($testType,$word){
		switch($testType){
			case 'sentenceCompletion':
				return self::getItemOfSentComp($word);
			case 'writingVariations':
			case 'writingClasses':
			case 'choosingSynonyms':
			case 'writingInEnglish':
			case 'writingInTurkish':
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
	public static function getItemOfSentComp($word){
		$item=new stdClass();
		$quotes=$word->quotes;
		shuffle($quotes);

		foreach($quotes as $k=>$quote)
			$quotes[$k]=str_replace(
				$word->word,
				'.....',
				$quote
			);
		
		
		$clues=dictionary::getRandomWords(6);
		foreach($clues as $k=>$i)
			$clues[$k]=dictionary::getWord($i);

		$clues[]=$word;
		shuffle($clues);
		
		$item->quotes=$quotes;
		$item->clues=$clues;
		return $item;
	}

	public static function getItemOfWritingVariations($word){
		/*select * from wordClasses 
			where
			wordId='.$word->id.' and
		 */
		/*catastrophic
		catastrophic?lly
		catastrophic?ly
		catastrophiced
		catastrophicd
		catastropcing
		catastrophics

		arrangem[ea]{1}nt
		a

		 */
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
					$test->answer
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
		if($wordId==1){
			if($answer=='ever')
				return '{"wordId":1,"result":true}';
			else
				return '{"wordId":1,"result":false,"answer":"ever","correction":"good"}';
		}
		elseif($wordId==2){
			if($answer=='car')
				return '{"wordId":2,"result":true}';
			else
					return '{"wordId":2,"result":false,"answer":"car","correction":"yaRRak"}';
		}
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

		if($wordId==51){
		if(implode(',',$selected)=='adjective')
			return '{"wordId":51,"result":true}';
		else
			return '{"wordId":51,"result":false,
			"correction":["adjective"]}';
		}
		elseif($wordId==86){
		if(implode(',',$selected)=='verb')
			return '{"wordId":86,"result":true}';
		else
			return '{"wordId":86,"result":false,
			"correction":["verb"]}';
		}

		return false;

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
		if($wordId==42){
		if(implode(',',$selected)=='excellent,elegant')
			return '{"wordId":42,"result":true}';
		else
			return '{"wordId":42,"result":false,
			"correction":["excellent","elegant"]}';
		}
		elseif($wordId==62){
		if(implode(',',$selected)=='nefarious')
			return '{"wordId":62,"result":true}';
		else
			return '{"wordId":62,"result":false,
			"correction":["nefarious"]}';
		}
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
		if($wordId==4){
		if($answer=='perfect')
			return '{"wordId":4,"result":true}';
		else
			$h='{"wordId":4,"result":false,
			"answer":"perfect"';
			if($answer=='car')
			$h.=',"correction":"araba"';
			$h.='}';
			return $h;
		}
		elseif($wordId==7){
		if($answer=='fast')
			return '{"wordId":7,"result":true}';
		else
			$h='{"wordId":7,"result":false,
			"answer":"fast"';
			if($answer=='car')
			$h.=',"correction":"araba"';
			$h.='}';
			return $h;
		}
		return false;
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
