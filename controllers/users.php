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
		
		$err=$this->checkUsername($r['username']);
		if($err!==true)
			return $err;
		
		$err=$this->checkEmail($r['email']);
		if($err!==true)
			return $err;
		
		$c=$this->users->register(
			$r['email'],
			$r['username'],
			$r['password']
		);

		if($c>0)
			return 1;
		return 0;
	}
	
	public function checkUserName($username=null){
		$r=$this->r;
		if($username!=null)
			$r['username']=$username;
		
		if (isset($r['username'])){
			if ($this->users->checkUserInfo(
					'username',
					$r['username'])
				)
				return 'Kullanıcı adı kullanılıyor.Lütfen değiştiriniz !';
			else 
				return true;
		}
	}
	
	public function checkEmail($email=null){
		$r=$this->r;
		if($email!=null)
			$r['email']=$email;
		
		if (isset($r['email'])){
			if ($this->users->checkUserInfo('email',$r['email']))
				return 'Email adresi kullanılıyor.Lütfen değiştiriniz.';
			else 
				return true;
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
			if ($r!==false){
				$this->u=$r;
				$this->session->create($r);
				return true;
			}
			else
				return 0;
		}
		
		return 'Kullanıcı bilgileri eksik yada hatalı.';
	}
}
?>
