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
		
		$o->packages=$this->getPackages();

		return $this->loadView(
			'quoteSearch.php',
			$o,
			false
		);
	}
	
	public function getPackages(){
		$db=new \db();
		return $db->fetch('select * from wordPackages');
	}

	public function search(){

		$db=new \db();
		$r=$this->r;
		$engField='';
		$trField='';
		
		if(isset($r['package']) && is_numeric($r['package'])){
			$sql='select w.word from wordPackagesw as wpw, words as w
				where 
				packageId=\''.$db->escape($r['package']).'\' and
				wpw.wordId=w.id';
			
			$words=arrays::toArray($db->fetch($sql),'word');

			$engField='and english regexp \'(^| )'.implode('|( |$)',$words).'\'';

		}
		elseif(isset($r['keywordTr'],$r['keywordEng'])){
			$kTr=stripslashes($db->escape($r['keywordTr']));
			$kEng=stripslashes($db->escape($r['keywordEng']));

			if($kTr!='')
				$trField='and turkish regexp \''.$kTr.'\'';

			if($kEng!='')
				$engField='and english regexp \''.$kEng.'\'';
			
		}
		else
			return false;
		
		$sql='select * from quotesEng2Tr where 1
			'.$trField.' '.$engField.'
			limit 300';
		
		$rs=$db->fetch($sql);
		echo mysqli_error($db->connection);
		$this->result=$rs;

		return $rs;
	}

}
?>
