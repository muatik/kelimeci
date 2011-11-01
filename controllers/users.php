<?php
require_once('ipage.php');
class usersController extends ipage {
	
	public function initialize(){
		parent::initialize();
		$this->users=new \kelimeci\users();
	}

	public function register(){
		
		$r=$this->r;

		if(!isset($r['email']) && !isset($r['username']) 
			&& !isset($r['password']))
			return 'kullanıcı bilgileri eksik veya hatalı';

		$c=$this->users->register($r['email'],$r['username'],$r['password']);

		if($c>0)
			return 1;
		return 0;
	}
	
	public function checkUserName(){
		$r=$this->r;
		if (!isset($r['userName'])){
			if ($this->users->checkUserInfo('username',$r['userName']))
				return 1;
			else 
				return 'Kullanıcı adı kullanılıyor.Lütfen değiştiriniz !';
		}
	}
	
	public function checkEmail(){
		$r=$this->r;
		if (isset($r['email'])){
			if ($this->users->checkUserInfo('email',$r['email']))
				return 1;
			else 
				return 'Email adresi kullanılıyor.Lütfen değiştiriniz.';
		}
	}
	
	public function viewProfile(){
		
		if (!$this->isLogined) return false;
		
		$user=$this->users->getUserInfo();
		
		return $this->loadView(
			'profile.php',
			$users,
			false
		);

	}
	
	public function update(){
		$type=$r['type'];
		$uId=$this->u->id;
		if(isset($type) && empty($type)){
			
			if ($type=='personeInfo' && isset($r['fname']) 
				&& isset($r['lname']) && isset($r['birthDate'])){
					
					$this->users->updateUserInfo('fname',$r['fname']);
					$this->users->updateUserInfo('lname',$r['lname']);
					$this->users->updateUserInfo('birthDate',$r['birthDate']);
			}
			
			if ($type=='email' && isset($r['email']))
					$this->users->updateUserInfo('email',$r['email']);
			
			if ($type=='password' && isset($r['currentPassword']) 
				&& isset($r['newPassword']))
					$this->users->updateUserInfo('password',
						array($r['currentPassword'],$r['newPassword']),true);
			
			if ($type=='practice' && isset($r['practice']) && isset($r['city'])){
					$this->users->updateUserInfo('practice',$r['practice']);
					$this->users->updateUserInfo('city',$r['city']);
			}
			
			
		}else return 'Güncelleme işlemi yapılamadı.';
	}	
	
	public function login(){
		$r=$this->r;
		if (isset($r['username']) && isset($r['password'])){
			$r=$this->users->validateLogin($r['username'],$r['password']);
			if ($r>0){
				$this->u->id=$r;
				$this->userId=$r;
			}
			else
				return 'Kullanıcı girişi yapılamadı.Lütfen bilgilerinizi kontrol ediniz.';
		}else return 'Kullanıcı bilgileri eksik yada hatalı.';
	}
}
?>
