<?php
require_once('ipage.php');
class statusController extends ipage {

	public function initialize(){
		$this->title='Durum';
		$this->autRequired=true;
		parent::initialize();

	}

	public function run(){

		if(!$this->isLogined){
			
			header('location:/');

		}

		parent::run();
	}
}
?>

