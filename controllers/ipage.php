<?php
require_once('moduler/moduler.php');
moduler::simportLib('controllers');
class ipage extends controllers
{
	public function isSession(){
		if(isset($this->u->email))
			$this->isLogined=true;
		else
			$this->isLogined=false;
	}
	
	public function initialize(){
		$this->addLib('db');
		$this->addModel(array(
			'dictionary',
			'words',
			'vocabulary',
			'tests',
		));

		$userId=1;
		$this->vocabulary=new kelimeci\vocabulary($userId);
	}
	
	public function run(){
		$this->isSession();
		parent::run();
	}
	
}
?>
