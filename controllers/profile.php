<?php
require_once('users.php');
class profileController extends usersController {
	
	public function initialize(){
		$this->title='Ayarlar & Profil';
		parent::initialize();
	}
}
?>
