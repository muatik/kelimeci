<?php
/**
 * SecureSession
 * 
 * Bu sınıf, php'nin session mekanizması üzerinde oturum yönetimi yapar.
 * Oturum işlemlerini basit ve güvenilir yapabilmesi en önemli özelliğidir.
 * 
 * Bir oturumun başka bir bilgisayara çalınıp çalınmadığını tespit eder,
 * oturum kaydını siler.
 * 
 * 
 * ========ÖRNEKLER========
 * $u->isim='mustafa';
 * $u->gezdigiSayfa='urun.php';
 * 
 * $s=new sSession();
 * 
 * //Oturum kaydı yaratmak için
 * $s->create($u);
 * 
 * // daha sonra başka bir sayfada...
 * 
 * //Oturum kaydını açmak için
 * $u=$s->open();
 * 
 * // oturum açılmışsa ekrana mustafa yazacaktır.
 * echo $u->isim;
 * 
 * // oturum verisi dğeiştiriliyor.
 * $u->isim='ali';
 * ========================
 * 
 * @date		Saturday, May 08
 * @author		Mustafa Atik <muatik@gmail.com>
 * @version		1.5
 * */
class sSession{
	
	/*
	 * $_SESSION değişkene kısa yoldur.
	 * 
	 * @type		array
	 * */
	private $s;
	
	/*
	 * açılmış oturum kaydı bilgilerini tutan nesnedir.
	 * 
	 * @type		object
	 * */
	private $uobj;
	
	/*
	 * açılmış oturum kaydı bilgilerini tutan nesnedir.
	 * 
	 * @type		object
	 * @access		static
	 * */
	static private $_uobj;

	/*
	 * oturum başlıklarının gönderilip gönderilemediği belirtir.
	 * 
	 * @type	boolean
	 * */
	public $isSessionStarted=false;
	
	/*
	 * kayıtlı bir oturumun açılıp açılmadığı belirtir.
	 * 
	 * @type	boolean
	 * */
	public $isOpened=false;
	
	/*
	 * istemciye özgü bilgiler dizisidir. 
	 * detectVars() tarafından doldurulur.
	 * 
	 * @typ	array
	 * */
	protected static $_desired;
	
	/*
	 * $_desired özniteliğinin statik olmayan hali
	 * */
	protected $desired;
	
	/*
	 * istemciye özgü bilgilerden elde edilen kimlik kodu
	 * getIntegrityId() tarafından oluşturulur.
	 * 
	 * @typ	string
	 * */
	protected static $_iid;
	
	/*
	 * $_iid özniteliğinin statik olmayan hali
	 * @type		string
	 * */
	protected $iid;
	
	/*
	 * kullanıcı nesnesini tutacak oturum değişkenini adı
	 * 
	 * @type		string
	 * */
	public $uVarName='sSession';
	
	
	public static function __staticINIT(){
		// zaten daha önceden bu metod çalıştırılmış, 
		// dolayısıyla değişkenler doldurulmuş ise, çık
		if(self::$_iid!=null) return true;
		self::detectVars();
		self::getIntegrityId();
	}
	
	
	public function __construct($uVarName=null){
		self::__staticINIT();
		$this->sendHeaders();
		$this->desired=self::$_desired;
		$this->iid=self::$_iid;
		if($uVarName!=null) 
			$this->uVarName=$uVarName;
	}
	
	/*
	 * eğer oturum kaydı açıksa, oturum nesnesinin son hali
	 * oturum dosyasına kaydediliyor.
	 * */
	public function __destruct(){
		if(!$this->isOpened) return false;
		$_SESSION[$this->uVarName]=self::$_uobj;
	} 
	
	/*
	 * @brief	oturmu başlatacak http başlıklarını gönderir
	 * return	oturum açılırsa true aksi halde false döndürür
	 * */
	public function sendHeaders(){
		if(isset($_SESSION)){
			$this->isSessionStarted=true;
		}
		else{
			if(headers_sent()) return false;
			$this->isSessionStarted=session_start();
		}
		$this->s=$_SESSION;
		return $this->isSessionStarted;
	}
	
	
	/**
	 * yeni bir oturum kaydı yaratır
	 * @params	p			oturuma nesne değişkeni
	 * @return	boolean		yaratıldıysa true, aksi halde false döner
	 * */
	public function create($p){
		if(!$this->isSessionStarted) return false;
		
		// oturum nesnesine gerekli veriler ekleniyor
		$p->_desired=$this->desired;
		$p->_iid=$this->iid;
		
		// oturum nesnesi kaydediliyor
		$_SESSION[$this->uVarName]=$p;
		$this->s[$this->uVarName]=$p;
		$this->uobj=$p;
		self::$_uobj=$p;

		$this->isOpened=true;
		return true;
	}
	
	
	/**
	 * oturumun kaydı varsa, oturumu açar
	 * @return	boolean/object	açılmışsa kullanıcı nesnesini,
	 * 							açılmamışsa false döndürür.
	 * */
	public function open(){
		if(!$this->isSessionStarted) return false;
		
		// oturum nesne değişkeni yoksa
		if(!isset($this->s[$this->uVarName])) return false;
		$_uobj=$this->s[$this->uVarName];
		if($_uobj->_iid!==$this->iid) return false;
		$this->uobj=$_uobj;
		self::$_uobj=$_uobj;
		$this->isOpened=true;
		return $this->uobj;
	}
	
	
	/**
	 * açık olan otorumu kaydını kapatır ve siler
	 * @return	kapatıldıysa true aksi halde false döner
	 * */
	public function kill(){
		if(!$this->isOpened) return false;
		
		// oturum bilgisi siliniyor
		unset($_SESSION[$this->uVarName]);
		unset($this->s[$this->uVarName]);
		$this->uobj=null;
		self::$_uobj=null;

		return true;
	}
	
	
	/*
	 * @brief	parametrede belirtilen isimli bir otorum değişkeninin
	 * 			var olup olmadığını kontrol eder.
	 * @params	v		kontrol edilecek değişkenin adı
	 * @return	eğer değişken varsa true, aksi halde false döndürür
	 * */
	public function iss($v){
		if(!$this->isOpened) return false;
		return isset($this->uobj->$v);
	}
	
	
	/* @brief	oturuma bir değişkeni kaydeder
	 * @return	kaydedilen değişkenin değeri döndürülür
	 * */
	public function set($n,$v){
		if(!$this->isOpened) return false;
		$this->uobj->$n=$v;
		return $v;
	}
	
	
	/* @brief	parametredeki oturum değişkenlerini siler
	 * @params	n	bir tane değişkeni veya dizi halindeki değişkenleri
	 * 			oturumdan siler
	 * @return	true
	 * */
	public function uset($n){
		if(!$this->isOpened) return false;
		$n=(!is_array($n)?array($n):$n);
		foreach($n as $i){
			unset($this->uobj->$i);
			unset(self::$_uobj->$i);
		}
		return true;
	}
	
	
	/* @brief	parametredeki oturum değişkeninin değerini verir
	 * @params	n	değeri döndürelecek oturum değişkeni
	 * @return	otorum değişkeninin değeri
	 * */
	public function get($n){
		if($this->iss($n))
			return $this->uobj->$n;
	}
	
	
	/* @brief 	oturum kaydı nesnesini verir
	 * @return	oturum açık ise veri döndürülür aksi halde false
	 * */
	public function getData(){
		if($this->isOpened) return $this->uobj;
		return false;
	}
	
	
	/*
	 * @brief	oturumun doğruluğu kontrol etmek için kullanılacak 
	 * 			çeşitli verileri yakalar
	 * return	true
	 * */
	protected static function detectVars(){
		$svr=$_SERVER;
		$desired=array(
			'HTTP_USER_AGENT'=>'uagent',
			'HTTP_ACCEPT'=>'uaccept',
			'REMOTE_ADDR'=>'radd',
			'HTTP_ACCEPT_LANGUAGE'=>'acpLang',
			'HTTP_ACCEPT_ENCODING'=>'acpEnc',
			'HTTP_ACCEPT_CHARSET'=>'acpCHR',
			'HTTP_CONNECTION'=>'keepAlive'
		);
		
		
		foreach($desired as $d=>$s)
			$values->$s=(isset($svr[$d])?$svr[$d]:'');
		self::$_desired=$values;
	}
	
	/* @brief	oturum doğrulamalarında kullanılması için istemciye
	 * 			özel bir şifrelenmiş oturum kodu döndürür. 
	 * @return	şifrelenmiş oturum kodunu döndürür.
	 * */
	protected static function getIntegrityId(){
		$s=self::$_desired;
		$v=md5(md5(
			$s->uagent.$s->radd
			.$s->acpLang.$s->acpEnc.$s->acpCHR.$s->keepAlive
		));
		self::$_iid=$v;
		return md5($v);
	}
	
}
?>
