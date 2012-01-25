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
	 * @param string $origin
	 * @param object $fields
	 * @access public
	 * @return int
	 */
	public function register($origin,$fields){
		if($origin=='facebook'){

			$hometown=($fields->user_hometown===NULL) ? 'null' : '\''.$fields->user_hometown.'\'';
			$birthday=($fields->user_birthday===NULL) ? 'null' : '\''.$fields->user_hometown.'\'';

			$sql='insert into users(email,city,birthDate,origin,metadata) 
				values(
				\''.$this->db->escape($fields->email).'\',
				'.$hometown.',	
				'.$birthday.',	
				\''.$this->db->escape($origin).'\',
				\''.$this->db->escape(serialize($fields)).'\')';

		}
		elseif($origin=='twitter'){
			$sql='';
		}
		else{

			$sql='insert into users(email,username,password,origin) 
				values(
				\''.$this->db->escape($fields->email).'\',
				\''.$this->db->escape($fields->username).'\',
				\''.md5($this->db->escape($fields->password)).'\',
				\''.$this->db->escape($origin).'\')';

		}

		if ($this->db->query($sql))
			return true;
		else
			return false;
	}

	/**
	*login kontrolü yapar.
	*
	*@param string $origin
	*@param string $field1
	*@param string $field2
	*
	* Giriş kontrolü $origin'e göre değişiklik gösterir;
	* eğer $origin "kelimeci" ise kullanıcı adı ve şifresi alanları ile kontrol edilir,
	* eğer $origin "facebook" ise e-posta ve origin alanları ile kontrol edilir,
	* eğer $origin "twitter" ise e-posta ve origin alanları ile kontrol edilir
	* 
	*return bool
	*/
	public function validateLogin($origin,$field1,$field2=null){

		$sql='select * from users where ';

		if($origin=='kelimeci'){
			$sql.='username=\''.$this->db->escape($field1).'\' and 
			password=\''.md5($this->db->escape($field2)).'\' and origin=\'kelimeci\'';
		}
		elseif($origin=='facebook'){
			$sql.='email=\''.$this->db->escape($field1).'\' and origin=\'facebook\'';
		}
		elseif($origin=='twitter'){
			$sql='';
		}
		

		$r=$this->db->fetchFirst($sql);

		if ($r!==false)
			return $r;
		else
			return false;
	}

	/**
	 * returns information of the given user
	 * 
	 * @param string $username
	 * @access public
	 * @return int
	 */
	public function getUserInfoByUsername($username){
		
		$username=$this->db->escape($username);
		$sql='select * from users where 
			username=\''.$username.'\'
			limit 1';
		
		return $this->db->fetchFirst($sql);
	}

	/**
	 * kullanıcı id sine bağlı bilgileri verir.
	 * 
	 * 
	 * @return object
	 * */
	public function getUserInfo($userId){
	
		$sql='select * from users where id=\''.$userId.'\' limit 1';
		return $this->db->fetchFirst($sql);
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

		$sql='insert into feedbacks(email,comments) values(\''.$email.'\',\''.$comments.'\')';

		if($this->db->query($sql))
			return true;
		else
			return false;

	 }
	
	
}
?>
