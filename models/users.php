<?php
namespace kelimeci;
use \db;
/**
 * kullanıcı işlemlerini yapan sınıftır.
 * 
 * @copyright copyleft
 * @author Alpay ÖNAL
 * @date 31 Oct 2011 23:39
 */
class users
{

	/**
	 * login olunduğunda kullanıcı ids'si saklanıyor.
	 * 
	 * @var int
	 * @access public
	 */
	public $userId;
	
	public function __construct(){

		$this->db=new db();
	}

	/**
	 * kullanıcı tanımlama işlemini yapar.
	 * 
	 * @param string $email
	 * @param string $username
	 * @param string $password
	 * @access public
	 * @return int
	 */
	public function register($email,$username,$password){
		$sql='insert into users(email,username,password) 
			values(
			\''.$this->db->escape($email).'\',
			\''.$this->db->escape($username).'\',
			\''.md5($this->db->escape($password)).'\')';
		
		if ($this->db->query($sql))
			return true;
		else
			return false;
	}

	/**
	*login kontrolü yapar.
	*
	*@param string $username
	*@param string $password
	* 
	*return bool
	*/
	public function validateLogin($username,$password){

		$sql='select * from users 
			where username=\''.$this->db->escape($username).'\' and 
			password=\''.md5($this->db->escape($password)).'\'';
		
		$r=$this->db->fetchFirst($sql);

		if ($r!==false)
			return $r;
		else
			return false;
	}
	
	/**
	 * kullanıcı id sine bağlı bilgileri verir.
	 * 
	 * 
	 * @return object
	 * */
	public function getUserInfo($userId){
	
		$sql='select * from users where id=\''.$userId.'\' limit 1';
		return $this->db->fetchFirstRecord($sql);
	}
	
	/**
	 * gönderilen alana ait kullanıcı bilgisini günceller.
	 * 
	 * @param string $field email..
	 * @param string $value xx@bbb.com...
	 * @param string $pass bu değer şifre bilgisi gönderildiğinde 
	 * 						true olarak gönderilmeli.
	 * return bool
	 * */
	public function  updateUserInfo($userId,$field,$value,$pass=null){

		$field=$this->db->escape($field);
		if ($pass==null){
			$value=$this->db->escape($value);
		}
		elseif ($pass){
			$value=md5($this->db->escape($value));
		}
			
		$sql='update users set '.$field.'=\''.$value.'\' where id=\''
			.$userId.'\'';

		return $this->db->query($sql);	
	}
	
	/**
	 * gönderilen alanla ilgili kullanıcı var mı yok mu kontrol eder.
	 * 
	 * @param string $field
	 * @param string $value
	 * 
	 * @return bool
	 * */
	public function checkUserInfo($field,$value){
		
		$sql='select * from  users where '.$this->db->escape($field).'=\''.
			$this->db->escape($value).'\'';
		
		if (count($this->db->fetch($sql))>0)
			return true;
		else 
			return false;
	}

	/**
	 * Geribildirimleri kayıt eder.
	 *
	 * @param string $email
	 * @param string $comments
	 *
	 * @return bool
	 */
	 public function feedBack($email,$comments){
		
		$email=$this->db->escape($email);
		$comments=$this->db->escape($comments);

		$sql='insert into feedbacks(email,comments) values(\''+$email+'\',\''+$comments+'\')';
		
		if($this->db->query($sql))
			return true;
		else
			return false;

	 }
	
	
}
?>
