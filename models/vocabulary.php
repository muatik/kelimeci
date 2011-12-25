<?php
namespace kelimeci;
use \db,\arrays;
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
		$this->db=new db();
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
	public function getWords($start=0, $length=100, $classes=null
		,$keyword=null,$levelMin=null,$levelMax=null,$orderBy=null){
		
		
		if($classes!=null){
			$classes=dictionary::getClasses($classes);
			$classes=arrays::toArray($classes,'id');
			if(is_array($classes) && count($classes)>0)
				$classes=' and wc.clsId in (\''.
					implode('\',\'',$classes)
					.'\')';
		}
		if(!is_string($classes))
			$classe=null;
		
		if($keyword!=null)
			$keyword=' and w.word like \'%'
				.$this->db->escape($keyword).'%\'';
		
		if($levelMin!=null && $levelMax!=null)
			$level=' and v.level between 
				'.$this->db->escape($levelMin).' 
				and '.$this->db->escape($levelMax);
		else
			$level=null;
		
		switch($orderBy){
			case 'date':
				$orderBy='order by v.crtDate desc'; break;
			case 'alphabetically':
				$orderBy='order by w.word'; break;
			case 'level':
				$orderBy='order by v.level'; break;
			case 'class':
				$orderBy='order by cls.name';break;
			default:
				$orderBy=null;
		}

		$sql='select v.* 
			from 
			vocabulary as v, words as w 
			left join wordClasses as wc on w.id=wc.wId
			left join classes as cls on wc.clsId=cls.id
			where
			v.userId='.$this->userId.' and
			v.wordId=w.id
			'.$keyword.'
			'.$level.'
			'.$classes.'
			group by w.id
			'.$orderBy.'
			limit '.$start.','.$length;

		$rs=$this->db->fetch($sql);
		
		if($rs===false)
			return false;
		
		// converting array to word objects
		$ws=array();
		foreach($rs as $i){
			$k=new words($i->wordId);
			$k->level=$i->level;
			$ws[]=$k;
		}
		
		return $ws;
		
	}
	
	
	/**
	 * returns the vocabulary record corresponded to word
	 * 
	 * @param string $word 
	 * @access public
	 * @return object/false
	 */
	public function getVocabularyByWord($word){
		$sql='select v.* from 
			words as w,vocabulary as v
			where
			v.userId='.$this->userId.' and
			v.wordId=w.id and
			w.word=\''.$this->db->escape($word).'\'
			limit 1';

		return $this->db->fetchFirst($sql);
	}
	
	/**
	 * fills data which is provided by the user into the object word
	 * for example: user quotes
	 *
	 * @param word $word 
	 * @access public
	 * @return object the object word
	 */
	public function fillUserData($word){
		$word->uQuotes=$this->getUserQuotes($word->id);
		return $word;
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


		$sql='select * from vocabulary
			where 
			userId=\''.$this->userId.'\' and
			wordId=\''.$word->id.'\'
			limit 1';

		// if the word is already in the user's vocabulary
		if($this->getVocabularyByWord($word->word)!==false)
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
	 * removes the words from the user's vocabulary
	 * 
	 * @param array $word string array
	 * @access public
	 * @return bool
	 */
	public function rmWord($words){
		if(!is_array($words))
			$words=array($words);
	
		$wordIds=array();
		foreach($words as $word){
			$word=dictionary::getWord($word);
			if(!is_object($word))
				continue;

			$wordIds[]=$word->id;
		}
		
		$sql='delete from vocabulary 
			where 
			userId=\''.$this->userId.'\' and
			wordId in (\''.implode('\',\'',$wordIds).'\')';

		return $this->db->query($sql);
	}


	/**
	 * return true if the word is in the user's vocabulary, 
	 * otherwise returns false
	 * 
	 * @param string $word 
	 * @access public
	 * @return bool
	 */
	public function isExists($word){
		$r=$this->getVocabularyByWord($word);
		if($r!=false)
			return true;

		return false;
	}


	/**
	 * adds a quote for a word of the user
	 * 
	 * @param string $word word itself or words object
	 * @param string $quote
	 * @access public
	 * @return bool
	 */
	public function addQuote($word,$quote){
		$word=dictionary::getWord($word);
		if($word==false)
			return false;
		
		$sql='insert into userQuotes (userId,wordId,quote)
		values(
			\''.$this->userId.'\',
			\''.$word->id.'\',
			\''.$this->db->escape($quote).'\'
		)';
		
		return $this->db->query($sql);
	}

	/**
	 * returns user's quotes
	 * 
	 * @param int $wordId
	 * @access public
	 * @return array
	 */
	public function getUserQuotes($wordId){
		$sql='select * from userQuotes 
			where
			userId='.$this->userId.' and
			wordId=\''.$this->db->escape($wordId).'\' ';
		
		return $this->db->fetch($sql);
	}
	

	/**
	 * returns counts of words in user's vocabulary
	 * 
	 * @param int $userId 
	 * @access public
	 * @static
	 * @return array tags array
	 */
	public static function getCountStats($userId){
		$db=new db();

		$userId=$db->escape($userId);
		
		$sql='select 
			count(v.wordId) as wCount, c.name 
		from 
			vocabulary as v, 
			wordclasses as wc,
			classes as c
		where
		userId=\''.$userId.'\' and
		v.wordId=wc.wordId and
		wc.clsId=c.id
		group by v.clsId';

		return $db->fetch($sql);
	}

	
	/**
	 * calculate compatibility beetwen two vocabulary
	 * 
	 * @param int $withUserId
	 * @access public
	 * @return int
	 */
	public function calcCompatibility($withUserId){
		
		$withUserId=$this->db->escape($withUserId);
		
		$sql='select wordId from vocabulary 
			where 
			userId=\'%d\' and level>12';
		
		$user1=$this->db->fetch(sprintf($sql,$this->userId));
		$user2=$this->db->fetch(sprintf($sql,$withUserId));
		
		$user1=arrays::toArray($user1,'wordId');
		$user2=arrays::toArray($user2,'wordId');
		
		$wIntersect=array_intersect($user1,$user2);
		
		// selecting the greater vocabulary
		$mainSet=( count($user1)>count($user2) ? $user1:$user2 );
		if(count($mainSet)==0)
			return 0;
		
		$percent=100*count($wIntersect)/count($mainSet);
		
		return ceil($perfect);
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
			tags like \'%'.$this->db->escape($keyword).'%\'
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
			w.word like \'%'.$this->db->escape($keyword).'%\' and
			w.id=v.wordId
			limit '.$length;
		
		return $this->db->fetch($sql);
	}
}
?>
