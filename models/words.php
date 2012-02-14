<?php
namespace kelimeci;
use \db,\arrays,\strings;
/**
 * the class words represents a word with it's all details
 * 
 * @date 08 Sep 2011 08:18
 * @copyright copyleft
 * @author Mustafa Atik <muatik@gmail.com>
 */
class words
{

	/**
	 * methods list for the __get()  method
	 * 
	 * @static
	 * @var mixed
	 * @access public
	 */
	static private $var2methods;

	/**
	 * id of the word
	 * 
	 * @var string
	 * @access public
	 */
	private $id;
	
	/**
	 * word
	 * 
	 * @var string
	 * @access public
	 */
	private $word;

	/**
	 * information about the word
	 * 
	 * @var array
	 * @access private
	 */
	private $info;

	/**
	 * language of the word
	 * 
	 * @var string
	 * @access private
	 */
	private $lang;

	/**
	 * meaingings of the word
	 * 
	 * @var array
	 * @access private
	 */
	private $meanings;

	/**
	 * classes of the word
	 * 
	 * @var array
	 * @access private
	 */
	private $classes;

	/**
	 * quotes of the word
	 * 
	 * @var array
	 * @access private
	 */
	private $quotes;

	/**
	 * synonyms of the word
	 * 
	 * @var array
	 * @access private
	 */
	private $synonyms;

	/**
	 * antonyms of the word
	 * 
	 * @var array
	 * @access private
	 */
	private $antonyms;

	/**
	 * pronunciation file of the word
	 * 
	 * @var object
	 * @access private
	 */
	private $pronunciation;

	/**
	 * database object 
	 * 
	 * @static
	 * @var db
	 * @access private
	 */
	private static $db;
	

	/**
	 * static __construct initializes static properties
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
	public static function init(){
		if(is_array(self::$var2methods) && count(self::$var2methods)>0)
			return false;
		
		self::$db=new db();
		self::$var2methods=array(
			'classes'=>'getClassesOfWord',
			'quotes'=>'getQuotesOfWord',
			'meanings'=>'getMeaningsOfWord',
			'synonyms'=>'getSynonymsOfWord',
			'antonyms'=>'getAntonymsOfWord',
			'pronunciation'=>'getPronunciationByWordId',
		);
	}

	/**
	 * __construct starts to represent a word if the word argument is passed
	 * 
	 * @param mixed $word id or string
	 * @access public
	 * @return void
	 */
	public function __construct($word=null){
		self::init();
		$this->bind($word);
	}
	
	/**
	 * binds to a word
	 * 
	 * @param mixed $word id or string
	 * @access public
	 * @return bool
	 */
	public function bind($word){
		$word=self::$db->escape($word);
		
		$sql='select * from words
			where '.
			(is_numeric($word)?'id':'word')
			.'=\''.$word.'\'
			limit 1';
		
		$r=self::$db->fetchFirst($sql);

		if($r!==false){
			$this->id=$r->id;

			$this->info=array();
			$info=dictionary::getInfoOfWord($r->id);
			foreach($info as $k=>$i)
				$this->info[$i->name]=$i;
			
			if(isset($this->info['lang']))
				$this->lang=$this->info['lang']->value;

			$this->word=$r->word;
			return true;
		}

		return false;
	}


	/**
	 * the magic method __get 
	 * 
	 * @param string $var 
	 * @access private
	 * @return void
	 */
	public function __get($var){
		// is getting the array vars of the word, at first fill them
		if(isset(self::$var2methods[$var])){
			
			if($this->$var==null){
				$method=self::$var2methods[$var];
				$this->$var=dictionary::$method($this->id);
			}
			return $this->$var;
		}

		if($var=='info')
			return $this->info;

		if($var=='lang')
			return $this->lang;

		if($var=='word')
			return $this->word;

		if($var=='id')
			return $this->id;
	}
}

?>
