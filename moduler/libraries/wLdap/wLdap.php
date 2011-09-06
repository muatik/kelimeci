<?php
/**
 * Easy LDAP 
 * 
 * Active Directory(AD) üzerinde basit işlemlerin, basitçe yapılmasında
 * kullanılabilecek bir sınıftır.
 * @author	mustafa atik <muatik@gmail.com>
 * @copyright  there is no spoon.
 * @date	Tuesday, April 06 2010 
 * @version	0.1
 * */
class eLdap{
	/**
     * AD sunucusunun adresi
     *
     * IP veya URL olabilir. Nesne oluşturulurken değeri belirlenir.
     *
     * @var string
    */
	public $host;
	
	/**
	 * AD üzerinde işlem yapacak kullanıcı adı
	 * 
	 * Eğer belirtilmezse, işlemler(sorgulama vb.) anonim kullanıcı
	 * üzerinden yapılır.
	 * 
	 * @var string
	 * */
	public $user;
	
	/**
	 * AD üzerinde işlem yapacak kullanıcının şifresi
	 * 
	 * Eğer anonim olarak işlem yapılacaksa, boş geçilebilir
	 * 
	 * @var string
	 * */
	public $password;
	
	/**
	 * İşlemlerin yapılacağı AD alanadır(dn).
	 * 
	 * Sorgulama gibi işlemlerin yapılacağı yerin ağaç yapısındaki yerini
	 * belirtir. Örneğin "o=My Company, c=US"
	 * 
	 * @var string
	 * */
	protected $baseDn;
	
	/**
	 * AD sunucusun erişilip erişilemediğini belirtir.
	 * 
	 * @var boolean
	 * */
	protected $cnn;
	
	/**
	 * AD sunucusunda bir alana(dn) bağlanılıp bağlanılmadığını belirtir.
	 * 
	 * @var boolean
	 * */
	protected $isBind=false;
	
	/**
	 * Sorgulama işlemlerinden dönen girdi sayısını tutar
	 * 
	 * @var int
	 * */
	public $entryCount;
	
	/**
	 * Yapılandırıcıdır. Öznitelik değerlerini parametre olarak alır.
	 * 
	 * @params	host		host özniteliğini belirtir.
	 * @params	user		user özniteliğini belirtir.
	 * @params	psw			password özniteliğini belirtir.
	 * @params	dn			baseDn özniteliğini belirtir.
	 * */
	public function __construct(
		$host=null,$user=null,$psw=null,$dn=null
		){
		$this->host=$host;
		$this->user=$user;
		$this->password=$psw;
		$this->dn=$dn;
	}
	
	/* öznitelik bilgileriyle AD'ye erişir ve bağlanır
	 * @return	erişir ve bağlanırsa true, aksi halde false
	 * */
	public function connect(){
		$this->cnn=ldap_connect($this->host);
		if($this->cnn!==false){
			return $this->bind();
		}
		$this->isBind=false;
		return false;
	}
	
	/* öznitelik bilgileriyle AD'ye bağlanır
	 * @return	erişirse true, aksi halde false
	 * */
	public function bind(){
		$t=$this;
		if($t->baseDn!=null && $t->password!=null){
			//kimlikli bağlantı
			$r=ldap_bind(
				$t->cnn,$t->user,$t->password
			);
		}
		else{
			// anonymous bağlantı
			$r=ldap_bind($this->cnn);
		}
		
		if($r!==false)
			$t->isBind=true;
		else
			$t->isBind=false;
		return $t->isBind;
	}
	
	/* baz alanadında(baseDN) arama yapar.
	 * @params	statement		aranacak isim ve değer
	 * 							örnek: "sn='Atik'" 
	 * @return	sorgu sonucundan dönen girdiler, hata varsa false dener.
	 * */
	public function search($statement){
		$t=$this;
		if(!$t->isBind)
			if(!$this->connect()) 
				return false;
		
		$rs=ldap_search($t->cnn,$t->baseDn, $statement);
		if($rs!==false){
			$t->entryCount=ldap_count_entries($t->cnn, $rs);
			$info=ldap_get_entries($t->cnn, $rs);
			return $info;
		}
		else{
			$t->entryCount=0;
		}
		return false;
	}
		
}

?>
