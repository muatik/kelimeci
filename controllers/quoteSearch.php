<?php
require_once('ipage.php');
class quoteSearchController extends ipage {
	
	public function initialize(){
		$this->title='Alıntı Sorgulama Yeri';
		$this->autRequired=false;
		parent::initialize();
	}
	
	public function run(){
		$this->search();
		parent::run();
	}

	public function loadPageLayout(){
		$o=new stdClass();
		$r=$this->r;

		if(isset($this->result)){
			$o->result=$this->result;
			$o->keywordTr=stripslashes($r['keywordTr']);
			$o->keywordEng=stripslashes($r['keywordEng']);
			$o->showEng=(isset($r['showEng'])?1:0);
			$o->showTr=(isset($r['showTr'])?1:0);
		}else{
			$o->keywordTr='';
			$o->keywordEng='';
			$o->showEng=1;
			$o->showTr=1;
		}

		return $this->loadView(
			'quoteSearch.php',
			$o,
			false
		);
	}
	
	public function search(){
		
		if(!isset($this->r['keywordTr'],$this->r['keywordEng']))
			return false;

		$db=new \db();
		$r=$this->r;
		$kTr=stripslashes($db->escape($r['keywordTr']));
		$kEng=stripslashes($db->escape($r['keywordEng']));

		$engField='';
		$trField='';
		
		if($kTr!='')
			$trField='and turkish regexp \''.$kTr.'\'';

		if($kEng!='')
			$engField='and english regexp \''.$kEng.'\'';
		
		$sql='select * from quotesEng2Tr where 1
			'.$trField.' '.$engField.'
			limit 300';
		
		$rs=$db->fetch($sql);
		$this->result=$rs;

		return $rs;
	}

}
?>
