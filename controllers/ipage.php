<?php
require_once('moduler/moduler.php');
moduler::simportLib('controllers');
class ipage extends controllers
{
	public function isSession(){
		if(isset($this->u->id)){
			$this->isLogined=true;
			$this->vocabulary=new kelimeci\vocabulary($this->u->id);
		}
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
			'users'
		));
		$this->isSession();
	}
	
	public function run(){
		parent::run();
	}
	
}
?>
