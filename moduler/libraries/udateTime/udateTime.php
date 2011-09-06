<?php
class udateTime
{
	var $utime;
	function date_time($utime=null){
		if($utime!=null) $this->utime=$utime; else $this->utime=time();
	}
	function convert_utime_of_date($d){
		$this->utime=mktime(substr($d,8,2),substr($d,5,2),substr($d,0,4),substr($d,11,2),substr($d,14,2),substr($d,17,2));
		return $this->utim;
	}
	function get_name_of_month($month=null){
		if($month==null) $month=date('m',$this->utime);
		$month=strtolower($month);
		switch($month){
			case ($month=='january' or $month=='01'):$month='Ocak';break;
			case ($month=='february' or $month=='02'):$month='Şubat';break;
			case ($month=='march' or $month=='03'):$month='Mart';break;
			case ($month=='april' or $month=='04'):$month='Nisan';break;
			case ($month=='may' or $month=='05'):$month='Mayıs';break;
			case ($month=='june' or $month=='06'):$month='Haziran';break;
			case ($month=='july' or $month=='07'):$month='Temmuz';break;
			case ($month=='august' or $month=='08'):$month='Ağustos';break;
			case ($month=='september' or $month=='09'):$month='Eylül';break;
			case ($month=='october' or $month=='10'):$month='Ekim';break;
			case ($month=='november' or $month=='11'):$month='Kasım';break;
			case ($month=='december' or $month=='12'):$month='Aralık';break;
			default:$month='';break;
		}
		return $month;
	}
	function get_name_of_day($day=null){
		if($day==null) $day=date('w',$this->utime);
		$day=strtolower($day);
		switch($day){
			case ($day=='Sunday' or $day=='0'):$day='Pazar';break;
			case ($day=='Monday' or $day=='1'):$day='Pazartesi';break;
			case ($day=='Thuesday' or $day=='2'):$day='Salı';break;
			case ($day=='Wednesday' or $day=='3'):$day='Çarşamba';break;
			case ($day=='Thursday' or $day=='4'):$day='Perşembe';break;
			case ($day=='Friday' or $day=='5'):$day='Cuma';break;
			case ($day=='Saturday' or $day=='6'):$day='Cumartesi';break;
			default:$day='';break;
		}
		return $day;
	}
	function get_month_list(){
		$ms=range(1,12);
		foreach($ms as $k=>$m) $ms[$k]= $this->get_name_of_month($m);
		return $ms;
	}
	function getTurkishFormat($t,$hourFormat=null,$showDay=true){
		$st=strtotime($t);
		$v=date('d',$st).' ';
		$v.=$this->get_name_of_month(date('m',$st)).' ';
		$v.=date('Y',$st).' ';
		$v.=$this->get_name_of_day(date('w',$st));
		if($hourFormat!=null)
			$v.=' '.date($hourFormat,$st);
		return $v; 
	}
}
?>
