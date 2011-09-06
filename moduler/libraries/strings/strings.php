<?php
class strings{
	
	/*
	 * yazıyı küçük harflere çevirir
	 * @params	string	s			yazı
	 * @return	string	küçük harfli yazı
	 * */
	public static function uStrToLower($str){
		if(!is_array($str)) 
			$str=mb_strtolower(
				str_replace(array('I','İ'),array('ı','i'),$str)
			);
		else
			foreach($str as $key=>$value) 
				$str[$key]=mb_strtolower(
					str_replace(array('I','İ'),array('ı','i'),$value)
				);
		return $str;
	}
	
	/*
	 * parametredeki değişkeni veya dizi değişkenlerini "sql injection" 
	 * ve "xss" saldırılarına karşı temizler.
	 * 
	 * @params	string	str			temizlenecek değişken veya
	 * değişkenler dizisi
	 * @params	string	slash		'auto' ise kaçış karakterlerini
	 * konulup konulmayacağına kendisi karar verir
	 * @params	boolean	html		html ve betik kodlamalarının
	 * silinip silinmeyeceğini belirtir.
	 * @params	array	allow_tags	izin verilen html ve betik işaretleri
	 * 
	 * @return	string	temizlenmiş değişken ve değişkenler dizisi
	 * */
	public static function purifyInput($str,$slash='auto',$html=true,$allow_tags=null){
		if(gettype($str)=='array'){
			
			if(($slash=='auto' && !get_magic_quotes_gpc()) || ($slash===true)){
				foreach($str as $key=>$value){
					if(is_array($value)){
						$str[$key]=strings::purifyInput($value);
						continue;
					}
					if($html) $str[$key]=strip_tags($value,$allow_tags);
					$str[$key]=addslashes($value);
				}
			}
			else{
				foreach($str as $key=>$value){
					if($html) {
						if(is_array($value)){
							$str[$key]=strings::purifyInput($value);
							continue;
						}
						$str[$key]=strip_tags($value,$allow_tags);
					}
				}
			}
			
		}
		else{
			if(($slash=='auto' && !get_magic_quotes_gpc()) || ($slash===true)){
				if($html) 
					$str=strip_tags($str,$allow_tags); 
				$str=addslashes($str);	
			}
			elseif($html) $str=strip_tags($str,$allow_tags);
		}
		return $str;
	}
	
	/*
	 * verilen metin içindeki kelimeleri sayar.
	 * 
	 * @params	string	str			metin
	 * @params	boolean	sensitive	büyük küçük harfe duyarlı olacak mı?
	 * @return	array	kelime dizisi
	 * */
	public static function countWords($str='',$sensitive=false){
		$arr=preg_split('/[ !\.\t\r\n,;\?]+/i',$str);
		if(!$sensitive) $arr=ustrtolower($arr);
			foreach($arr as $key=>$value) if($value=='')
				unset($arr[$key]);	
		$arr=array_count_values($arr);
		arsort($arr);
		return $arr;
	}

	/*
	 * verilen değişkeni ve dizi deişkenlerini doğrular.
	 * 
	 * @params	string	str			değişken veya dizi değişkenleri
	 * @params	int		max			değişkenin maksimum uzunluğu/büyüklüğü
	 * @params	int		min			değişkenin minimum uzunluğu/küçüklüğü
	 * @paras	boolean	numeric		değişken sayısal mı dğeil mi?
	 * @params	string	regex		kontrol edilecek düzenli ifade deseni
	 * @return	boolean		doğrulanırsa true, aksi halde false döner.
	 * */
	public static function validate($str,$max,$min=-1,$numeric=false,$regex=null){
		if(gettype($str)=='array'){	
			foreach($str as $key->$value){
				$str_length=mb_strlen($value);
				if(($str_length>$max || $str_length<$min) || 
					($numeric && ((int)$str!=$str)) || 
					($regex!=null && !preg_match($regex,$value)))
						return false;
			}
		}
		else{
			$str_length=mb_strlen($str);
			if(($str_length>$max || $str_length<$min ||
			 ($numeric && ((int)$str!=$str))) || 
			 ($regex!=null && !preg_match($regex,$str)))
				return false;
		}
		return true;
	}
	
	public static function arrStripslashes($arr){
		if(!is_array($arr)) return $arr;
		foreach($arr as $k=>$r) $arr[$k]=stripslashes($r);
		return $arr; 
	}
	
	public static function insertArray($arr1,$arr2,$offset){
		$n=array();
		$c=count($arr1);
		$i=0;
		foreach($arr1 as $k=>$v){
			if($k===$offset){
				if(!is_array($arr2)) $arr2=array($arr2);
				$n=array_merge($n,$arr2,array_slice($arr1,$i));
				break;
			}
			$n[$k]=$v;
			$i++;
		}
		return $n;
	}
	
	public static function convertObjectToArray($records){
		$new_list=array();
		foreach($records as $i=>$j){
			$new_list[$j->id]=$j->name;
		}
		return $new_list;
	}
}

?>
