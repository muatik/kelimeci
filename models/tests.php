<?php
namespace kelimeci
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
	public static function __construct(){
		
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
		'fetch words of user for the test
		preapre test items by each word
		preapre test info
		return'

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
				$time-(3600*24*$i))
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
		select * from wordClasses 
			where
			wordId='.$word->id.' and

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
	 * @param object $result 
	 * @static
	 * @access public
	 * @return bool
	 */
	public static function verify($result){
	}

	/**
	 * verify a sentence completion test answer that's represented 
	 * by a result object
	 * 
	 * @param object $result 
	 * @access public
	 * @return bool
	 */
	public function verifyInSentComp($result){
	}


	### END OF VERIFICATION METHODS ###


}

?>
