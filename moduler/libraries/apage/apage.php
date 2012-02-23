<?php
abstract class apage{
	public $charset='utf-8';
	public $siteUrl='domain.com';
	public $pageName='a page';
	public $title='Page Title';
	public $siteMail='mail@domain.com';
	public $siteDescription='';
	public $extHeaders;
	
	/**
	 * is headers already generated?
	 * */
	public $isHeadersPrepared=false;
	
	public $stylePath='css/';
	public $scriptPath='js/';
	public $imagePath='img/';
	public $modulerPath='moduler/';
	public $configFile='_config.php';
	public $viewsPath='views/';
	public $elementsPath='views/elements/';
	public $layoutsPath='views/';
	
	public $styles=array();
	public $scripts=array();
	public $readyFunctions=array();
	
	/*
	 * çalışmakta olan betiğin dosya adıdır. 
	 * değeri sınıf tarafından atanır.
	 * */
	private $fileName;
	
	/*
	 * oturum mekanizması nesnesdir.
	 * */
	protected $session;
	
	/*
	 * açık oturum nesnesidir
	 * */
	protected $u;
	
	/*
	 * sınıfın oluşturduğu tüm çıktıları içinde tutan değişkendir.
	 * */
	protected $generatedOutput;
	
	/* gösterilecek sayfanın adı, örn: index, login, ürünler...
	 * aktif menü bu parametreye göre tespit edilir.  
	 * */
	protected $name;


	/**
	 * bu sınıftan türetilen nesenin ne şekilde çalışmış olduğunu söyler.
	 * Bu sınıftan alınan nesne; 
	 *  - ajax = sadece ajax işlemini sonlandıracak kadar işlem yapılır.
	 *  - view = sadece istenen görüntüyü sunacak kadar işlem yapılır.
	 *  - element = sadece istenen elementi sunacak kadar işlem yapılır.
	 *  - page = nesnenin normal işlem akışıdır, tüm genel işlemler yapılır.
	 * olarak dört niyetle çağrılabilir.
	 * */
	protected $runFor='page';
	
	
	public static $initial=null;
	
	
	
	public function __construct($autoRun=true){
		
		$this->autoRun=$autoRun;
		$this->__initialize();
		
		if(apage::$initial==''){
			
			apage::$initial=get_class($this);
			
			if(isset($this->r['_ajax'])){
				$this->runFor='ajax';
				$this->generatedOutput=$this->invokeAjaxAction();
			}
			elseif(isset($this->r['_view'])){
				$this->runFor='view';
				$this->generatedOutput=$this->invokeView('view');
			}
			elseif(isset($this->r['_element'])){
				$this->runFor='element';
				$this->generatedOutput=$this->invokeView('element');
			}
			elseif($this->autoRun){
				$this->runFor='page';
				$this->run();
			}
		
		}
		
	}
	
	/*
	 * @brief	yapılandırıcı tarafında çağrılan genel hazırlıkları, 
	 * yüklemeri ve ayarları yapan bir metotdur. Kullanıcı tarafından 
	 * değiştirilmesi tavsiye edilmez.
	 * */
	final private function __initialize(){
		
		header('content-type:text/html;charset=utf-8');
		
		/*php yorumlayacı yönergeleri belirtiliyor */
		if(file_exists($this->configFile))
			require_once($this->configFile);
		
		// çalışan betiğin adı bulunuyor.
		$sname=explode('/',$_SERVER['PHP_SELF']);
		$this->fileName=$sname[count($sname)-1];
		
		/*modül mekanizması yükleniyor*/
		$this->initializeModuler();
		
		// oturum mekanizması yükleniyor
		$this->initializeSession();
		
		// global değişkenler kontrol ediliyor 
		$this->__checkParams();
		
		// user initialization
		$this->initialize();
	}
	
	
	/*
	 * moduler mekanizması yükleniyor. bu betiğin çalışmasına eklenecek
	 * tüm kütüphane dosyaları bu mekanizma ile yapılacaktır.
	 * 
	 * İstenirse, moduler'in dizin ayarları; apage mirasçıları tarafından
	 * değiştirilebilir.
	 * */
	final private function initializeModuler(){
		$this->_mdl=new moduler();
		$this->libs=array('sSession','strings','arrays');
		$this->_mdl->importLib($this->libs);
		return true;
	}
	
	/*
	 * Oturum yönetim mekanizmasını yükler. Açık olan oturumu tespit
	 * eder ve bu oturum nesnesini değişkene atar.
	 * */
	final private function initializeSession(){
		$this->session=new sSession();
		$this->u=$this->session->open();
	}
	
	/*
	 * Global değişkenleri sınıfın değişkenlerine atar. Atanan değişkenler
	 * "sql injection" ve "xss" saldırılarına karşı temizlenir; kaçış 
	 * karakterleri eklenir ve "tag"lar silinir.
	 * 
	 * Eğer kullanıcının yazdığı javascript veya html işaretlemerinin
	 * silinmemiş halleri isteniyorsa, ilgili global değişkenler 
	 * kullanılmalıdır.
	 * */
	final private function __checkParams(){
		$this->r=(isset($_REQUEST)?strings::purifyInput($_REQUEST):null);
		$this->g=(isset($_GET)?strings::purifyInput($_GET):null);
		$this->p=(isset($_POST)?strings::purifyInput($_POST):null);
		$this->f=(isset($_FILES)?strings::purifyInput($_FILES):null);
	}
	
	/*
	 * sayfanın sitil dosyalarını yükler.
	 * */
	final public function addCss($s){
		if(!is_array($s)) $s=array($s);
		$this->styles=array_merge($this->styles,$s);
		
		if($this->isHeadersPrepared || $this->runFor!='page')
			return $this->getCSSHeaders($s);
	}
	
	/*
	 * sayfanın javascript betik dosyalarını yükler.
	 * */
	final public function addJs($s){
		if(!is_array($s)) $s=array($s);
		$this->scripts=array_merge($this->scripts,$s);
		
		if($this->isHeadersPrepared || $this->runFor!='page')
			return $this->getJSHeaders($s);
	}
	
	/*
	 * moduler mekanizması vasıtasıyla kütüphane dosyası ekler.
	 * */
	final public function addLib($s){
		$this->_mdl->importLib($s);
		if(!is_array($s)) $this->libs[]=$s;
		else $this->libs=array_merge($this->libs,$s);
	}
	
	/*
	 * moduler mekanizması vasıtasıyla proje dosyası ekler
	 * */
	final public function addModel($s){
		$this->_mdl->import($s);
		if(!is_array($s)) $this->libs[]=$s;
		else $this->libs=array_merge($this->libs,$s);
	}
	
	
	
	/**
	 * extract (if there is a) controller name and method name
	 * 
	 * @param string $s 
	 * @access public
	 * @return array
	 */
	public function extractControllerAndMethod($s){

		$action=explode('/',$s,2);
		if(count($action)==2){
			$className=$action[0].'Controller';
			main::loadController(self::stripView($action[0]));
			$controller=new $className();
			$methodName=$action[1];
		}else{
			$controller=$this;
			$methodName=$action[0];
		}

		$r=array(
			$controller,$methodName,
			'controller'=>$controller,'method'=>$methodName
		);
		return $r;
	}

	/**
	 * clear malicious chars in the name of a view
	 * allowed= a-z, 0-9, slash(/), dot(.), underscore(_), dash(-)
	 * @return string cleared view name
	 * */
	final static public function stripView($name){
		return preg_replace(
			'/([^a-z0-9\/._-])|[.]{2,}/i',
			'',
			$name
		);
	}

	
	/**
	 * detects ajax request and invokes the corresponded action(method)
	 * */
	final public function invokeAjaxAction($action=null){
		
		// the parameter _ajax indicates that there is an request and
		// contains the action which should be executed.
		if($action==null && isset($this->r['_ajax']))
			$action=$this->r['_ajax'];
		
		if($action==null)
			return false;

		// a controller(class) name isn't required but if there is, 
		// the controller and the action are separated by a slash.
		// exp: nameOfController/nameOfAction
		$action=$this->extractControllerAndMethod($action);
		$controller=$action['controller'];
		$methodName=$action['method'];
		
		if(method_exists($controller,$methodName))
			return call_user_func(array($controller,$methodName));

		else
			die('The action "'.$methodName.'" not found in the 
				controller "'.get_class($controller).'".');

	}

	/**
	 * görüntü dosyası çağrılarını izler ve eğer çağrı varsa
	 * görüntü dosyasının çıktısını istemciye gönderir.
	 * Görüntü dosyası gönderildiği için tüm sayfa(getBody()) işlemez
	 * @param string $vClass görüntü sınıfını belirtir. 
	 * alabileceği değerler: view, element
	 * 
	 * */
	final public function invokeView($vClass='view'){
		
		$vs=(
			is_array($this->r['_'.$vClass])?
			$this->r['_'.$vClass]:
			array($this->r['_'.$vClass])
		);
		
		/**
		 * sınıf görüntü metoduna sahipse, bu metod çalıştırılır.
		 * Aksi halde görüntü dizinindeki görüntü dosyası doğrudan
		 * yüklenir.
		 * */
		$h='';
		foreach($vs as $v){

			
			/**
			 * başka bir controller sınıfı üzerinden view çağırımı yapmak
			 * için controller, view adından hemen önce gelir ve araya
			 * \ işareti ayraç olarak konur.
			 * */
			$action=$this->extractControllerAndMethod($v);
			$controller=$action['controller'];

			// exp: element+SignupForm>elementSignupForm,
			// exp: view+Chart>viewChart
			// exp: controllerA>viewChart = controllerA\chart
			$methodName='view'.$action['method'];

			// exp: element+sPath>elementsPath, view+sPath>viewsPath
			$pathName=$vClass.'sPath';
			
			if(method_exists($controller,$methodName)){
				$h.= call_user_func( array($controller,$methodName) );
			}
			elseif( ($c=$this->loadViewFile(
					$this->$pathName.self::stripView($v).'.php',
					null
				) )!==false )
			{
				$h.=$c;
			}else{
				die('The '.$vClass.' "'.$v.'" not found in \''
					.__CLASS__. '\' nor the path of 
					'.$vClass
				);
			}
		}
		
		return $h;
	}
	
	
	/*
	 * belirtilen görüntü dosyasını yükler.
	 * loadViewFile() metodunun kısa yolu.
	 * @param string $view görüntü dosyasının adı
	 * @param mixed $params gönderilecek parametre nesnesi
	 * @return string görüntünün(genellikle html) çıktısı
	 * */
	public function loadView($view,$params=null,$callMethod=true){

		$viewName=explode('.',$view,2);
		$action=$this->extractControllerAndMethod($viewName[0]);

		$controller=$action['controller'];
		$methodName='view'.$action['method'];

		if($callMethod && method_exists($controller,$methodName))
			return $controller->$methodName($params);
		else
			return $this->loadViewFile(
				$this->viewsPath.self::stripView($view),
				$params
			);
	}


	/**
	 * görüntülerin bir alt sınıfı olan, belirtilen elementi yükler.
	 * loadViewFile() metodunun kısa yolu.
	 * @param string $element yüklenecek element
	 * @param mixed $params elemente aktarılacak parametre(ler)
	 * @return string elementin(genellikle html) çıktısı
	 * */
	public function loadElement($element,$params=null){
		return $this->loadViewFile(
			$this->elementsPath.self::stripView($element),
			$params
		);
	}
	
	/**
	 * görüntü dosyasını işleyerek yükler.
	 * @param string $viewFile görüntü dosyası(göreceli yoluyla)
	 * @param mixed $params görüntüye aktarılacak parametre(ler)
	 * */
	final public function loadViewFile($viewFile,$params){
		
		if(!file_exists($viewFile))
			die('The view file "'.$viewFile.'" not found. 
				controller= '.get_class($this));
		
		$o=$params;
		
		ob_start();
		require($viewFile);
		$output=ob_get_contents();
		ob_end_clean();
		
		return $output;
	}
	
	

	/*
	 * oluşturulmuş çıktıyı verir.
	 * @return	string	çıktı
	 * */
	public function getOutput(){
		return $this->generatedOutput;
	}
	
	/*
	 * hem ana _initialize işlemleri tamamlandıktan sonra
	 * mirasçıya özgü yüklemeleri yapacak olan bu metod çalışır.
	 * 
	 * Bu sınıfın mirasçıları tarafından hayata geçirilecektir.
	 * */
	protected function initialize(){}
	
	/*
	 * hem ana hem de mirasçının initialize işlemleri tamamlanıp
	 * sayfa çalışmaya hazır hale gelince bu metod çalışır.
	 * 
	 * Bu sınıfın mirasçıları tarafından hayata geçirilecektir.
	 * */
	public function run(){}
	
	
	/**
	 * belirtilen javascript'leri html'e ekleyen kodları oluşturup verir.
	 * <script src="..." ... /> satırlarını oluşturur.
	 * @param string $jsFile='added' js dosyalarını belirtir
	 * eğer 'added' ise, addJS metoduyla belirtilmiş js'lerin
	 * tümü için html kodunu üretir.
	 * @return string html satırları
	 * */
	protected function getJSHeaders($jsFile='added'){
		
		if($jsFile=='added')
			$jsFiles=$this->scripts;
		elseif(is_array($jsFile))
			$jsFiles=$jsFile;
		else
			$jsFiles=array($jsFile);
		
		$h='';
		foreach($jsFiles as $i) {
			$h.='<script type="text/javascript" language="javascript" src="'
				.(strpos($i,'://')!==false?$i:$this->scriptPath.$i)
				.'"></script>';
		}
		
		return $h;
	}
	
	
	/**
	 * belirtilen css'leri html'e ekleyen kodları oluşturup verir.
	 * <link .... /> satırlarını oluşturur.
	 * @param string $cssFile='added' css dosyalarını belirtir
	 * eğer 'added' ise, addCss metoduyla belirtilmiş css'lerin
	 * tümü için html kodunu üretir.
	 * @return string html satırları
	 * */
	protected function getCSSHeaders($cssFile='added'){
		
		if($cssFile=='added')
			$cssFiles=$this->styles;
		elseif(is_array($cssFile))
			$cssFiles=$cssFile;
		else
			$cssFiles=array($cssFile);
		
		$h='';
		foreach($cssFiles as $i) {
			$h.='<link rel="stylesheet" type="text/css" href="'
				.(strpos($i,'://')!==false?$i:$this->stylePath.$i)
				.'" />';
		}
		
		return $h;
	}
	
	
	
	/*
	 * Sayfanın html başlık satırlarını oluşturur. 
	 * Sunucu taraflı dosyaları (css, js) yükler.
	 * 
	 * return string	oluşturulan başlık kısmı
	 * */
	protected function getHeaders(){
		$h='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" ';
		$h.='"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		$h.='<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">';
		$h.='<head>';
		$h.='<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />';
		$h.='<title>'.$this->siteTitle.'</title>';
		$h.='<meta http-equiv="Content-Type" content="text/html; ';
		$h.='charset='.$this->charset.'" />';

		$h.=$this->getCSSHeaders();
		$h.=$this->getJSHeaders();
		
		if(count($this->readyFunctions)>0){
			$h.='<script type="text/javascript" language="javascript">window.onload=function(){';
			foreach($this->readyFunctions as $i) $h.=$i;
			$h.='};</script>';
		}
		
		$h.=$this->extHeaders;
		$h.='</head><body>';
		
		$this->isHeadersPrepared=true;
		return $h;
	}

}
?>
