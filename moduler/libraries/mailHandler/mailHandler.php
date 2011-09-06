<?php
class mailHandler
{
	public $error;
	public $type='contact';
	
	public $from;
	public $to;
	public $subject;
	public $message;
	public $content_type='text/plain; charset=utf-8; format=flowed';
	public $ext_headers='';

	public $antiflood=true;
	public $floodobj=null;
	public $interval=10;
	public $post_count=5;
	
	public function send(){
		$this->error='';
		$rmadd=$_SERVER['REMOTE_ADDR'];
		$d=date('D, d M Y H:i:s');
		$headers="Date: ".$d."\n";
		$headers.="From: ".$this->from."\n";
		$headers.="Reply-To:  ".$this->from."\n";
		$headers.="MIME-Version: 1.0\n";
		$headers.="Content-type: ".$this->content_type."\n";
		$headers.="Content-Transfer-Encoding: 8bit\n";
		//$headers.="Organization: ".$page->site_name."\r\n";
		//$headers.="Return-path: ".$page->feedback_mail."\r\n";
		$headers.=$this->ext_headers;
		
		if($this->antiflood && $this->floodobj==null)
			$this->create_antiflood();
		if(!is_array($this->to))
			$this->to=array($this->to);
		
		foreach($this->to as $t){
			if($this->antiflood){
				if($this->floodobj->check_by_ip($rmadd)){
					if(!mail($t,$this->subject,$this->message,$headers)){
						$this->error.='\''.$t.
							'\' alıcısına e-posta iletisi gönderilemedi.';
					}
					else
						$add_to_aflood=true;
				}
				else {
					$this->error=$this->interval
					.' dakika içerisinde '.$this->post_count.
					' adet mesaj göndermişsiniz. Daha sonra tekrar deneyiniz.';
				}
			}
			elseif(!mail($t,$this->subject,$this->message,$headers)){
				$this->error.='\''.$t
				.'\' alıcısına e-posta iletisi gönderilemedi.';
			}
		}
		
		if(isset($add_to_aflood))
			$this->floodobj->add($rmadd);
		if($this->error=='')
			return true;
		return false;
		
	}
	
	protected function create_antiflood(){
		$this->floodobj=new antiflood();
		$this->floodobj->type=$this->type;
		$this->floodobj->interval=$this->interval;
		$this->floodobj->post_count=$this->post_count;
		return true;
	}
}

?>
