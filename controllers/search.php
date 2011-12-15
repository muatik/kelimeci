<?php
require_once('ipage.php');
class searchController extends ipage {
	
	public function initialize(){
		$this->title='Kelime Arama';
		parent::initialize();
	}
	
	public function run(){
		parent::run();
	}

	public function loadPageLayout(){
		return $this->viewword();
	}
	
	public function viewword(){
		main::loadcontroller('vocabulary');
		$vc=new vocabularyController();
		return $vc->viewword();
	}

}
?>
