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
		
		$classesq=null;
		if($classes!=null){
			$classesq=dictionary::getClasses($classes);
			$classesq=arrays::toArray($classesq,'id');
			
			if(is_array($classesq) && count($classesq)>0) {
				$classesq=' wc.clsId in (\''.
					implode('\',\'',$classesq)
					.'\')';

				if(in_array('unknown',$classes))
					$classesq='('.$classesq.' or wc.clsId is null )';

				$classesq=' and '.$classesq;
			}

		}
		
		if(!is_string($classesq))
			$classes=null;
		else
			$classes=$classesq;

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
			v.status=1 and
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
	 * returns the word packages list and indicates packages 
	 * which are in the user's vocabulary
	 * @param bool $isAll
	 * @access public
	 * @return array
	 * */
	public function getWordPackages($isAll){
		
		$sql='select 
				wp.*,userId as isInUserVcb, count(wp.wordId) as wordCount 
			from 
				wordPackages as wp left join userWordPackages as uwp
				on wp.label=uwp.label and
				uwp.userId=\''.$this->userId.'\' 
			group by wp.label
			order by isInUserVcb desc';
		
		return $this->db->fetch($sql);
	}
	
	/**
	 * save given packages into the user's package list
	 * */
	public function saveWordPackages($packages){

		foreach($packages as $k=>$i)
			$packages[$k]=$this->db->escape($i);

		$packagesi=implode('\',\'',$packages);

		// fetching omitted packages
		$sql='select * from userWordPackages 
			where userId=\''.$this->userId.'\' and
			label not in (\''.$packagesi.'\')';

		$omitteds=$this->db->fetch($sql);
		$omitteds=arrays::toArray($omitteds,'label');


		// inserts words of selected packages into the user's vocabulary.
		$sql='insert ignore into vocabulary (userId,wordId,tags) 
			select '.$this->userId.' as userId, wordId, label as tags 
			from wordPackages as wp
			where wp.label in (\''.$packagesi.'\')';
		$this->db->query($sql);
		// marks words of selected packages as inuse
		$sql='update vocabulary as v, wordPackages as wp 
				set status=1 
			where
				wp.label in (\''.$packagesi.'\') and
				wp.wordId=v.wordId';
		$this->db->query($sql);


		// inserting selected packages into the user's package list
		$sql='insert ignore into userWordPackages (userId,label) 
			values (\''.$this->userId.'\',\''.
				implode('\'),(\''.$this->userId.'\',\'',$packages).'\')';
		$this->db->query($sql);
		
		// deleting words of the omitted packages's from the users's vocabulary.
		if(count($omitteds)>0){
			
			$sql='delete from userWordPackages
				where userId=\''.$this->userId.'\' and 
				label in (\''.implode('\',\'',$omitteds).'\')';
			$this->db->query($sql);
			
			// marks words as removed in vocabulary
			$sql='update 
					vocabulary as vcb, wordPackages as owp
				set
					vcb.status=0
				where 
					vcb.wordId=owp.wordId and 
					owp.label in (\''.implode('\',\'',$omitteds).'\') and
					owp.wordId not in (
						select wordId from wordPackages as iwp 
						where iwp.label in (\''.$packagesi.'\')
					)';
			$this->db->query($sql);
		}
		
		return true;
			
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
		$v=$this->getVocabularyByWord($word->word);
		$word->level=$v->level;
		$word->status=$v->status;
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
		if($this->getVocabularyByWord($word->word)!==false){
			// if the word is already exists in vocabulary as removed word,
			// the word is going to be marked as inuse
			return $this->markWordAsInuse($word->word);
		}

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
	 * mark the removed word as inuse in vocabulary
	 * @param string $word
	 * @access public
	 * @return bool
	 * */
	public function markWordAsInuse($word){
			$sql='update vocabulary as v, words as w set v.status=1
				where v.userId=\''.$this->userId.'\' and
				v.wordId=w.id and w.word=\''.$word.'\'';
			$this->db->query($sql);
			return $this->db->affectedRows;
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

		//0=removed, 1=inuse for status
		$sql='update vocabulary set	status=0 where 
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
