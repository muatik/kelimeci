<?php
namespace kelimeci;
/**
 * This dictionary class presents general lexical operations.
 *
 * @copyright copyleft
 * @author Mustafa Atik<muatik@gmail.com> 
 */
class dictionary
{

	/**
	 * database object
	 * 
	 * @static
	 * @var db
	 * @access private
	 */
	private static $db;

	public static function __sconstruct(){
		self::$db=new \db();
	}

	public function __construct(){
		self::__sconstruct();	
	}

	/**
	 * returns random word IDs 
	 * 
	 * @param int $length (default:10) how many words will be returned
	 * @static
	 * @access public
	 * @return array words Ids
	 */
	public static function getRandomWords($length=10){
		
		$maxId=self::getLastWord();
		$maxId=$maxId->id;
		
		// this might look strange but more robust random method for sql.
		$randomIds=array();
		for($i=0; $i<$length*100; $i++)
			$randomIds[]=rand(1,$maxId);
		
		$sql='select id from words 
			where id in ('.implode(',',$randomIds).')
			limit '.$length;
		
		return \arrays::convertToArray(
			self::$db->fetch($sql),
			'id'
		);

	}
	
	private static function getLastWord(){
		$sql='select * from words order by id desc limit 1';
		return self::$db->fetchFirst($sql);
	}

	/**
	 * returns records that are related with specified word that are in a table
	 * 
	 * @param int $wordId
	 * @param string $table 
	 * @static
	 * @access public
	 * @return array
	 */
	private static function getWordItemsByTable($wordId,$table){
		$sql='select * from '.self::$db->escape($table).'
			where
			wId='.self::$db->escape($wordId);
		
		return self::$db->fetch($sql);
	}

	/**
	 * validates and fixes if neccesary the word variable
	 * 
	 * @param mixed $word 
	 * @static
	 * @access public
	 * @return words
	 */
	private static function assureWord($word){
		if(is_numeric($word))
			return self::getWord($word);
		elseif(is_object($word) && get_class($word)=='words')
			return $word;

		return false;
	}

	/**
	 * checks if a word has specified word class 
	 * 
	 * @param words $word 
	 * @param string $wClass 
	 * @static
	 * @access public
	 * @return bool
	 */
	public static function hasClasses($word,$wClass){
		$word=$this->assureWord($word);
		
		foreach($word->classes as $ic)
			if($ic==$wClass)
				return true;

		return false;
	}


	/**
	 * checks if the specified word is exists
	 * 
	 * @param string $word 
	 * @static
	 * @access public
	 * @return bool
	 */
	public static function isWordExists($word){
		$sql='select id from dictionary 
			where 
			word=\''.$this->db->escape($word).'\'
			limit 1';

		$this->db->query($sql);
		return ( $this->db->numRows>0 ? true : false );
	}


	/**
	 * Gives -ing, -s/-es, -ed forms of a word
	 * 
	 * @param words $word the object word or id of a word
	 * @static
	 * @access public
	 * @return array inflections
	 */
	public static function getInflections($word){
		// just for now, we produce basic inflections in primitive ways
		
		$word=$this->assureWord($word);
		$inflections=array();

		// if the word isn't a verb, it has no verb inflections
		if(!$this->hasClass($word,'verb'))
			return $inflections;

		// -ed inflection
		if(mb_substr($word->word,-1,1)=='e')
			$inflections=$word->word.'d';
		else
			$inflections=$word->word.'ed';

		// -ing inflection
		if(mb_substr($word->word,-1,1)=='e')
			$inflections=mb_substr($word->word,0,-1).'ing';
		else
			$inflections=$word->word.'ing';

		// -s/es inflection
		if(mb_substr($word->word,-1,1)=='s')
			$inflections=$word->word.'\'';
		elseif(mb_substr($word->word,-1,1)=='x'
			or in_array(mb_substr(
				$word->word,-1,2), array('sh','ch')
			) )
				$inflections=$word->word.'es';
		else
			$inflections=$word->word.'s';
		
		return $inflections;
		
	}
	
	/**
	 * Gives -ing, -s/-es, -ed forms of a word
	 * 
	 * @param words $word class words instance or word id
	 * @static
	 * @access public
	 * @return array inflections
	 */
	public static function getInflectionsOfVerb($word){
		// for the time being, no difference at inflections of verb and noun
		$this->getInflections($word);
	}
	
	/**
	 * Gives -ing, -s/-es, -ed forms of a word
	 * 
	 * @param words $word class words instance or word id
	 * @static
	 * @access public
	 * @return array inflections
	 */
	public static function getInflectionsOfNoun($word){
		// for the time being, no difference at inflections of verb and noun
		$this->getInflections($word);
	}
	
	/**
	 * returns classes(verb, noun, adjective, adverb, preposition, other) 
	 * of a word
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return array word classes
	 */
	public static function getClassesOfWord($wordId){
		$sql='select c.* from wordClasses as wc, classes as c
			where 
			wc.wId='.$this->db->escape($wordId).' 
			and wc.clsId=c.id';
		
		return $this->db->fetch($sql);
	}
	
	/**
	 * returns language of a word
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return string
	 */
	public static function getLangOfWord($wordId){
		$sql='select * from wordInfo
			where 
			wId='.self::$db->escape($wordId).' and
			name="lang"
			limit 1';
		
		$r=self::$db->fetchFirst($sql);
		return ($r==false? false : $r->value);
	}
	
	/**
	 * returns meainings of word 
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getMeaningsOfWord($wordId){
		return $this->getWordItemsByTable($wordId,'meanings');
	}

	/**
	 * returns quotes of a word
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getQuotesOfWord($wordId){
		return self::getWordItemsByTable($wordId,'quotes');
	}
	
	/**
	 * returns synonyms of a word 
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getSynonymsOfWord($wordId){
		return $this->getWordItemsByTable($wordId,'synonyms');
	}

	/**
	 * returns antonyms of a word 
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getAntonymsOfWord($wordId){
		return $this->getWordItemsByTable($wordId,'antonyms');
	}
	
	/**
	 * return the word object which is corresponded to word
	 * 
	 * @param string $word id of word or word itself 
	 * @static
	 * @access public
	 * @return words the object word
	 */
	public static function getWord($word){
		$w=new words($word);
		if(is_numeric($w->id))
			return $w;
		
		return false;
	}
	
	/**
	 * returns the word class objects which are corresponded to the names
	 * 
	 * @param array $names array of class names which are
	 * @static
	 * @access public
	 * @return array the array of the word classes
	 */
	public static function getClasses($names){
		
		$names=$this->db->escape($names);
		
		$sql='select * from wordClasses
			where
			name in (\''.$names.'\')';
		
		return $this->db->fetch($sql);
	}

}

dictionary::__sconstruct();
?>
