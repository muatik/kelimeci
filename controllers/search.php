<?php
require_once('ipage.php');
class searchController extends ipage {
	
	public function initialize(){
		$this->title='Kelime Arama';
		$this->autRequired=false;
		parent::initialize();
	}
	
	public function run(){
		
		if(isset($this->r['word'])){
			$this->title='Kelimeci sözlük: '.stripslashes($this->r['word']);
			$this->searchResult=$this->search($this->r['word']);

			if(!isset($this->u->searchHistory))
				$this->u->searchHistory=array();

			$this->saveSearchHistory($this->r['word']);
		}
		
		parent::run();
	}

	public function loadPageLayout(){
		$o=new stdClass();
		$o->word=null;

		if(isset($this->searchResult)){
			$o->word=stripslashes($this->r['word']);
			$o->result=$this->searchResult;
			$o->relatedSearchs=$this->getRelatedSearchs($this->r['word']);
		}

		$o->history=$this->getSearchHistory();
		return $this->loadView(
			'search.php',
			$o,
			false
		);
	}
	
	public function search($word){
		
		main::loadcontroller('vocabulary');
		$vc=new vocabularyController();
		$r=$vc->viewword($word);
		
		if(mb_strpos($r,'not found')===false)
			$this->noResult=false;
		else
			$this->noResult=true;
		
		return $r;
	}
	
	public function saveSearchHistory($word){
		
		$i=array(
			'date'=>date('Y-m-d H:i:s'),
			'keyword'=>stripslashes($word),
			'result'=>!$this->noResult
		);

		// inserting the search into the begin of the history list
		array_unshift($this->u->searchHistory,$i);

		// save only naximum 100 the search keywords
		$this->u->searchHistory=array_slice(
			$this->u->searchHistory
			,0,50
		);

		$db=new db();
		$sql='insert into searchHistory (session,keyword,isFound)
			values(
				\''.$this->u->_iid.'\',
				\''.$db->escape($i['keyword']).'\',
				\''.$i['result'].'\'
			)';
		$db->query($sql);
	}

	public function getSearchHistory(){
		if(!isset($this->u->searchHistory)) return array();
		return $this->u->searchHistory;
	}

	public function getRelatedSearchs($word){
		$db=new db();
		$word=$db->escape($word);
		$sql='select 
			sh.keyword, count(sh.id) as searchCount 
		from (
				select session,crtDate FROM searchHistory 
				where keyword=\''.$word.'\' group by session
			) as w, searchHistory as sh
		where
			sh.keyword<>\''.$word.'\' and
			w.session=sh.session and
			sh.crtDate>w.crtDate
		group by sh.session
		order by searchCount desc
		limit 10';


		return $db->fetch($sql);
	}
}
?>
