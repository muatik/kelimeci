<?php
/*
CREATE TABLE `antiflood` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `type` VARCHAR(30) NOT NULL, `crt_date` DATETIME NOT NULL, `ip` VARCHAR(19) NOT NULL, `tag` VARCHAR(50) NOT NULL) ENGINE = MyISAM
*/
class antiflood
{
	public $db;
	public $table_name='antiflood';
	public $type='post';
	public $interval=10;
	public $post_count=2;
	
	function antiflood($db=null)
	{
		if($db==null) $this->db=new dbConnection();
		else $this->db=$db;
		$this->clear();
	}
	
	function add($ip,$tag='')
	{
		if($this->db->query('insert into '.$this->table_name.' (type,crt_date,ip,tag) values(\''.$this->type.'\',now(),\''.$ip.'\',\''.$tag.'\')')) return true;
		return false;
	}
	
	function check_by_ip($ip, $tag=null)
	{
		if($this->db==null) return false;
		if($tag!=null) $tag=' and tag=\''.$tag.'\'';
		$begin_time=time()-$this->interval*60;
		if($this->db->query('select count(id) as c from '.$this->table_name.' where type=\''.$this->type.'\' and ip=\''.$ip.'\' '.$tag.'  and crt_date>\''.date('Y-m-d H:i:s',$begin_time).'\''))
		{
			$c=$this->db->fetchObject();
			if($c->c<$this->post_count)  return true;
		}
		return false;
	}

	function check_by_post($tag)
	{
		if($this->db==null) return false;
		$begin_time=time()-$this->interval*60;
		if($this->db->query('select count(id) as c from '.$this->table_name.' where type=\''.$this->type.'\' and tag=\''.$tag.'\' and crt_date>\''.date('Y-m-d H:i:s',$begin_time).'\''))
		{
			$c=$this->db->fetchObject();
			if($c->c<$this->post_count)  return true;
		}
		return false;
	}
	
	function clear($ip=null,$tag=null)
	{
		if($this->db==null) return false;
		if($ip!=null) $ip=' and ip=\''.$ip.'\'';
		if($tag!=null) $tag=' and tag=\''.$tag.'\'';
		$begin_time=time()-$this->interval*60;
		if($this->db->query('delete from '.$this->table_name.' where type=\''.$this->type.'\' and '.$ip.' '.$tag.' and crt_date<\''.date('Y-m-d H:i:s',$begin_time).'\'')) return true;
		return false;
	}
}

?>