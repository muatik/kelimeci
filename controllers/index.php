<?php
require_once('ipage.php');
class indexController extends ipage {
	public function initialize(){
		parent::initialize();
		if($this->isLogined)
			header('location: profil');
	}
}
?>
