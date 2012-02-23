<?php
namespace kelimeci;
use \db,\arrays;
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
		self::$db=new db();
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
		
		return arrays::toArray(
			self::$db->fetch($sql),
			'id'
		);

	}


	/**
	 * getLastWord 
	 * 
	 * @static
	 * @access private
	 * @return void
	 */
	private static function getLastWord(){
		$sql='select * from words order by id desc limit 1';
		return self::$db->fetchFirst($sql);
	}


	/**
	 * getWordsByIds 
	 * 
	 * @param mixed $ids 
	 * @param string $returnType 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function getWordsByIds($ids,$returnType='array'){
		foreach($ids as $k=>$i)
			$ids[$k]=self::$db->escape($i);
		
		$ids=implode('\',\'',$ids);

		$rs=self::$db->fetch('select * from words 
			where id in(\''.$ids.'\')'
		);

		if($returnType=='array')
			return $rs;
		
		foreach($rs as $k=>$i)
			$rs[$k]=self::getWord($i->id);

		return $rs;
	}

	/**
	 * returns records that are related with specified word that are in a table
	 * 
	 * @param int $wordId
	 * @param string $table 
	 * @param string $sqlSuffix default is null
	 * @static
	 * @access public
	 * @return array
	 */
	private static function getWordItemsByTable($wordId,$table,$sqlSuffix=''){
		$sql='select * from '.self::$db->escape($table).'
			where
			wId='.self::$db->escape($wordId). ' '.$sqlSuffix;
		
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
		$word=self::assureWord($word);
		
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
			word=\''.self::$db->escape($word).'\'
			limit 1';

		self::$db->query($sql);
		return ( self::$db->numRows>0 ? true : false );
	}


	/**
	 * getMeaningsByLang 
	 * 
	 * @param mixed $wordId 
	 * @param mixed $meanings 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function getMeaningsByLang($wordId,$langs){
		if(!is_array($langs))
			$langs=array($langs);

		foreach($langs as $k=>$i)
			$langs[$k]=self::$db->escape($i);

		$langs=implode('\',\'',$langs);

		return self::$db->fetch('select * from meanings
			where
			wId=\''.$wordId.'\' and
			lang in (\''.$langs.'\') '
		);
	}
	
	/**
	 * getWordsByMeaning 
	 * 
	 * @param mixed $keyword 
	 * @param mixed $langs 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function getWordsByMeaning($keyword,$langs=null){
		
		if($langs!=null){
			if(!is_array($langs))
				$langs=array($langs);

			$langs=implode('\',\'',$langs);
			$langs='lang in(\''.$langs.'\')';
		}

		$sql='select 
			wId,meaning,lang,clsId 
			from words as w, meanings as m
			where
			'.$langs.'
			m.meaning=\''.$keyword.'\' and
			m.wId=w.id';
		
		return self::$db->fetch($sql);
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
		
		$word=self::assureWord($word);
		$inflections=array();

		// if the word isn't a verb, it has no verb inflections
		if(!self::hasClass($word,'verb'))
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
		self::getInflections($word);
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
		self::getInflections($word);
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
			wc.wId='.self::$db->escape($wordId).' 
			and wc.clsId=c.id';
		
		return self::$db->fetch($sql);
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
	 * returns information about the word
	 * @param int $wordId
	 * @static
	 * @access public
	 * @return array
	 * */
	public static function getInfoOfWord($wordId){
		return self::getWordItemsByTable($wordId,'wordInfo');
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
		
		$set1=self::getWordItemsByTable(
			$wordId,'meanings',
			' and page=\'google\''
		);

		$set2=self::getWordItemsByTable(
			$wordId,'meanings',
			' and page<>\'google\' order by page desc'
		);

		return array_merge($set1,$set2);
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
		$sql='select q.* from wordQuotes as wq,quotes as q
			where wq.wId=\''.$wordId.'\' and wq.quoteId=q.id
			order by length(quote)';
		return self::$db->fetch($sql);
	}
	
	/**
	 * returns quotes of a word
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return array
	 */
	public static function getQuoteById($quoteId){
		$sql='select * from quotes 
		where id=\''.self::$db->escape($quoteId).'\' limit 1';
		return self::$db->fetchFirst($sql);
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
		$rs=self::getWordItemsByTable(
			$wordId,'synonyms',' and page=\'seslisozluk\''
		);
		foreach($rs as $k=>$i){
			$i=self::getWord($i->synId);
			$rs[$k]=$i;
		}
		return $rs;
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
		$rs=self::getWordItemsByTable($wordId,'antonyms');
		foreach($rs as $k=>$i){
			$i=self::getWord($i->antId);
			$rs[$k]=$i;
		}
		return $rs;
	}


	/**
	 * returns a object that contains pronunciation infos of the word
	 * 
	 * @param int $wordId 
	 * @static
	 * @access public
	 * @return object
	 */
	public static function getPronunciationByWordId($wordId){
		$pronunciation=new \stdClass();

		$file='audios/words/'.$wordId.'.mp3';
		if(!file_exists($file))
			return false;
	 
		$pronunciation->file=$file;

		return $pronunciation;
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
		
		if(!is_array($names))
			$names=is_array($names);
		
		if(count($names)==0)
			return array();

		foreach($names as $k=>$i)
			$names[$k]=self::$db->escape($i);

		$names=implode('\',\'',$names);
		$sql='select * from classes
			where
			name in (\''.$names.'\')';
		
		return self::$db->fetch($sql);
	}

	/**
	 * getClassById 
	 * 
	 * @param mixed $clsId 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function getClassById($clsId){
		$sql='select * from classes
			where
			id=\''.self::$db->escape($clsId).'\'
			limit 1';

		return self::$db->fetchFirst($sql);
	}


	/**
	 * suggests words that are matched by the keyword
	 * 
	 * @param string $keyword 
	 * @param int $limit 10
	 * @static
	 * @access public
	 * @return void
	 */
	public static function suggest($keyword,$limit=10){
		
		$keyword=self::$db->escape($keyword);

		$sql='select * from words
			where 
			word like \''.$keyword.'%\'
			order by length(word),word
			limit '.$limit;

		return self::$db->fetch($sql);
	}
}

dictionary::__sconstruct();
?>
