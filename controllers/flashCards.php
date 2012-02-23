<?php
require_once('ipage.php');
class flashCardsController extends ipage {
	
	public function initialize(){
		$this->title='Kelime Şeridi';
		$this->autRequired=false;
		parent::initialize();
	}

	public function getWordDetail(){
		$r=$this->r;
		
		$this->addModel('dictionary');

		if(!isset($r['word']))
			return false;

		$w=new kelimeci\words($r['word']);
		if(!is_numeric($w->id))
			return false;

		$rw=new stdClass();
		$rw->meanings=array();

		foreach($w->meanings as $i)
			if($i->lang=='tr' && $i->page=='google' && $i->clsId!=0){
				$m=new stdClass();
				$m->meaning=$i->meaning;
				$m->cls=kelimeci\dictionary::getClassById($i->clsId);
				$rw->meanings[]=$m;
			}

		if(count($rw->meanings)==0)
			foreach($w->meanings as $i)
				if($i->lang=='tr' && $i->page=='seslisozluk' && $i->clsId<>0){
					$m=new stdClass();
					$m->meaning=$i->meaning;
					$m->cls=kelimeci\dictionary::getClassById($i->clsId);
					$rw->meanings[]=$m;
				}
		
		$rw->quotes=array_slice($w->quotes,0,4);

		return json_encode($rw);

	}
}
?>
