<?php
require_once('ipage.php');
class dashboardController extends ipage {
	
	public function initialize(){
		$this->title='Kullanıcı panosu';
		parent::initialize();
		$this->users=new kelimeci\users;
	}
	
	public function run(){
		parent::run();
	}
	
	public function loadPageLayout(){

		if(isset($this->r['who'])){

			$who=$this->r['who'];
			$u=$this->getUserInfo($who);

			if($u!=false){
				return $this->loadView(
					'dashboard.php',
					$u,
					false
				);
			}
			else{

				$o2=new stdClass();
				$o2->title='KULLANICI BULUNAMADI';
				$o2->message='\''.$who.'\' isimli kullanıcı hesabı silinmiş olabilir veya yanlış isim olabilir.';
				$o2->hidable=false;
				return $this->loadElement('notification.php',$o2);
			}
		}
		
		header('location:/');
	}

	public function getUserInfo($username){
		$o=$this->users->getUserInfoByUsername(
			$username
		);

		if($o==false)
			return false;
		
		$o->vocabularyStats=kelimeci\vocabulary::getCountStats($o->id);
		
		if($this->isLogined){
			$vcb=$this->vocabulary;
			$o->compatibility=$vcb->calcCompatibility($o->id);
		}
		
		return $o;
	}
	
	
}
?>
