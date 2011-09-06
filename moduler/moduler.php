<?php
class moduler 
{
	private $dcroot='/';	// projenin kök dizini
	private $_mdldir='moduler';  // moduler mekanizmasını tutan dizin
	private $_libDir='libraries';  // genel kütüphanelerin bulunduğu dizin
	private $_modelDir='../models'; // projeye özgü sınıflarının bulunduğu dizin
	
	public function __construct(){
	}
	
	
	/* @brief verilen mesajı, hata mesajı yapar. bunları günlükler.
	 * @params emsg		hatayı açıklayan metin mesajı
	 * @params log		hata mesajının günlüklenip günlüklenemyeceğini belirtir. 
	 * true ise günlüklenir, false ise günlüklenmez.
	 * @return			işlem başarılıysa true, başarısızsa false döndürür.
	 */
	private function throwError($emsg,$log=true){
		$this->error=$emsg;
		if($log) return $this->sendErrorLog();
		return false;
	}
	
	
	/* @brief 			verilen metin mesajı, hata günlük tablosuna ekler
	 * @params emsg		hatayı açıklayan metin mesajı
	 * @return			işlem başarılıysa true, başarısızsa false döndürür.
	 */
	private function sendErrorLog($emsg){
		// gelen mesajı veritabanına yazan bir kod olacak
	}
	
	
	/* @brief 			proje, modül ve temel dosyaları yükler.
	 * @params m		yüklenecek php dosyasının adı veya dosya adlarından
	 * oluşan bir dizi değişkendir. Dosya adlarında, dosya uzantıları olmaz.
	 * Dosya adında, dosyanın içinde bulunduğu dizin adı da olabilir.
	 * @paarms type		yüklenecek dosyaların türünü belirtir. 
	 * alabileceği değerler: project, base, core	
	 * @return			işlem başarılıysa true, başarısızsa false döndürür.
	 */
	public function importFiles($m,$type='model'){
		if($type=='model')
			$sdir=$this->_modelDir;
		elseif($type=='libraries')
			$sdir=$this->_libDir;

		$path=realpath(dirname(__FILE__)).'/'.$sdir.'/';
		
		if(!is_array($m)){
			$m=array($m);
		}
		
		foreach($m as $f){
			
			if(file_exists($path.$f)){
				if(is_dir($path.$f)) 
					require_once($path.$f.'/'.$f.'.php');
				elseif(is_file($path.$f))
					require_once($path.$f.'/'.$f);
				else
					return $this->throwError(
						'\''.$f
						.'\' isimli modül ne dizin ne de dosya bulundu.');
			}
			elseif(file_exists($path.$f.'.php')){
				require_once($path.$f.'.php');
			}
		}
		return true;
	}
	
	
	/* @brief model dosyasını yükler.
	 * @params string m yüklenecek php dosyasının adı veya dosya adlarından
	 * oluşan bir dizi değişkendir. Dosya adlarında, dosya uzantıları olmaz.
	 * Dosya adında, dosyanın içinde bulunduğu dizin adı da olabilir.
	 * @return işlem başarılıysa true, başarısızsa false döndürür.
	 */
	public function import($m){return $this->importFiles($m,'model');}
	public static function simport($m){	//static kopyası
		$m=new moduler();
		return $m->importFiles($m,'model');
	}


	/* @brief kütüphane dosyası yükler.
	 * @params string m yüklenecek php dosyasının adı veya dosya adlarından
	 * oluşan bir dizi değişkendir. Dosya adlarında, dosya uzantıları olmaz.
	 * Dosya adında, dosyanın içinde bulunduğu dizin adı da olabilir.
	 * @return işlem başarılıysa true, başarısızsa false döndürür.
	 */
	public function importLib($m){return $this->importFiles($m,'libraries');}
	public static function simportLib($m){	//static kopyası
		$o=new moduler();
		return $o->importFiles($m,'libraries');
	}
}
?>
