<?php
require_once('moduler/moduler.php');
moduler::simportLib('controllers');
class ipage extends controllers
{
	public $autRequired=true;

	public function isSession(){
		if(isset($this->u->id)){
			$this->isLogined=true;
			$this->vocabulary=new kelimeci\vocabulary($this->u->id);
		}
		else{
			$this->isLogined=false;
			$this->openDummySession();
		}
	}
	
	/**
	 * opening a session for whom is not authorized(not logined). 
	 * A session is required for every user to save search history etc.
	 * */
	public function openDummySession(){
		$this->session->open();
	}

	public function initialize(){
		parent::initialize();
		$this->addLib('db');
		$this->addModel(array(
			'dictionary',
			'words',
			'vocabulary',
			'tests',
			'users'
		));

		$this->isSession();


	}

	public function loadSiteLayout(){
		$o=new stdClass();
		$o->isLogined=$this->isLogined;

		$siteLayout='layout.php';
		if(file_exists($this->layoutsPath.$siteLayout))
			return $this->loadView($siteLayout,$o);
	}

	
	public function run(){
		if($this->autRequired && !$this->isLogined)
			header('location:/');
		
		parent::run();
	}
	
}
?>
