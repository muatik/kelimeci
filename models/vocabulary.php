<?php
namespace kelimeci
/**
 * vocabulary class contains operations that perform for only one user
 * 
 * @copyright copyleft
 * @author Mustafa Atik <muatik@gmail.com>
 * @date 07 Sep 2011 01:42
 */
class vocabulary
{

	/**
	 * the initial level of a word which is being learned by a user
	 * 
	 * @var int
	 * @access private
	 */
	private $level=0;
	
	/**
	 * indicates the user for whom operations perform for
	 * 
	 * @var int
	 * @access public
	 */
	private $userId;
	
	

	public function __construct($userId){
		if(!is_numeric($userId))
			die('You cannot create a vocabulary' 
			.' instance without a user');

		$this->userId=$userId;
	}

	/**
	 * returns the words array which is in the user's vocabulary
	 * 
	 * @param int $start 
	 * @param int $length 
	 * @param array $classes 
	 * @access public
	 * @return array the array of the words instances
	 */
	public function getWords($start=0, $length=100, $classes=array()){

		$classes=dictionary::getClasses($classes);

		$sql='select v.* from vocabulary as v, wordClasses as wc
			where
			v.userId='.$this->userId.'

			'.(count($classes)>0?
				implode('\',\'',$classes)
				:null
				).'

			limit '.$start.','.$length;
		
		$rs=$this->db->fetch($sql);
		if($rs==false)
			return false;
		
		// converting array to word objects
		$ws=array();
		foreach($rs as $i)
			$ws[]=new words($i->wId);
		
		return $ws;
		
	}
	
	
	/**
	 * adds a word into the user's vocabulary
	 * 
	 * @param string $word word itself or words object
	 * @param string $tags tag of the word
	 * @access public
	 * @return bool
	 */
	public function addWord($word, $tags=null){
		
		if(!is_object($word))
			$word=dictionary::getWord($word);

		if($word==false)
			return false;

		$sql='insert into vocabulary (userId,wordId,level,tags)
			values(
				\''.$this->userId.'\',
				\''.$word->id.'\',
				\''.$this->level.'\',
				\''.$this->db->escape($tags).'\'
			)';
		
		$this->db->query($sql);

		if($this->db->affectedRows>0)
			return true;

		return false;
	}
	
	
	/**
	 * suggests tags which contain the keyword
	 * 
	 * @param string $keyword 
	 * @param int $length default 15
	 * @access public
	 * @return array tags array
	 */
	public function suggestTags($keyword,$length=15){
		$sql='select * from vocabulary
			where
			tags like \'%'.$this->db->escape($keyword.'%\'
			limit '.$length;

		return $this->db->fetch($sql);
	}
	
	/**
	 * suggests words which contain the keyword
	 * 
	 * @param string $keyword
	 * @param int $length default 15
	 * @access public
	 * @return array words array
	 */
	public function suggestWords($keyword,$length=15){
		$sql='select v.*,w.word from vocabulary as v,words as w
			where
			w.word like \'%'.$this->db->escape($keyword.'%\' and
			w.id=v.wordId
			limit '.$length;
		
		return $this->db->fetch($sql);
	}
}
?>
