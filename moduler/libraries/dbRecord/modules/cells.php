<?php


class cells{
	
	// bir hücre kaydı açar ev detay bilgilerini girer
	public function insertCell($tags,$contents,$events){
		$cc=cellContents();
		$ct=cellContents();
		$ce=cellContents();
		
		$cellId=$this->createCell();
		$ct->insert($cellId,$tags);
		$cc->addContentsToCell($cellId,$contents);
		$ce->insert($cellId,$events);
		return true;
	}
	
	// bir hücre kaydı açar
	public function createCell(){
		$sql='insert into cells (crtDate) values(now())';
		if($this->db->query($sql))
			return $this->db->getInsertId();
		return false;
	}
}

class cellContents{
	
	// belirtilen hücreye ait içerikleri çeker
	public function fetchByCell($cellId){}
	
	// belirtilen içerikleri ekler
	public function addContentsToCell($cellId,$contens){
		$cnt=array('new','update');
		$updateIds=array();
		foreach($contents as $c){
			if(!is_numeric($c->id)) $cnt['new'][]=$c;
			else{
				$cnt['update']=$c;
				$updateIds[]=$c->id;
			}
		}
		
		if(count($updateIds)>0){
			$sql='delete from cellContents where id not in ('.
			implode(',',$updateIds).')';
			$this->db->query($sql);
			
			foreach($cnt['update'] as $c){
				$sql='update cellContents set 
				content=\''.$c->content.'\' 
				where id='.$c->id.' limit 1';
				$this->query($sql);
			}
		}
		
		foreach($cnt['update'] as $c){
			$sql='insert into cellContents (cellId,content)
			values('.$cellId.',\''.$c->content.'\')';
			$this->query($sql);
		}
		
		return true;
	}
	
	// belirtilen numaralı içeriği siler
	public function delete($id){}
	
	// belirtilen numaralı hücrenin içeriklerini siler
	public function deleteBycell($id){
		
	}
}

class cellTags{
	
	// belirtilen hücreye etiketleri ekler ekler
	public function insert($cellId,$tags){
		$r=true;
		foreach($tags as $t){
			$tId=$this->addTag($t);
			
			$sql='select id from cellTags 
			where cellId='.$cellId.' and tagId='.$t;
			$this->db->query($sql);
			if($this->db->numRows>0) continue;
			
			$sql='insert into cellTags (cellId,tagId)
			values('.$cellId.','.$tId.')';
			$r=$r && $this->db->query($sql);
		}
		return $r;
	}
	
	public function addTag($tag){
		$sql='insert into tags value(\''.$tag.'\')';
		if($this->db->query($sql)){
			return $this->db->getInsertId();
		}
		$sql='select id from tags where tag=\''.$tag.'\'';
		$r=$this->db->fetchFirstRecord($sql);
		if($r!==false) return $r->id;
		else return false;
	}
}

class cellLabels{

}

class cellEvents{
	
	// bir hücrenin belirtilen bir olayına kod atar
	public function set($eventId,$cellId,$code){}
	
	// bir hücrenin belirtilen bir olayına kod atar
	public function fetchEventsByCell($cellId){}
	
	// tüm tanımlı olayları çeker
	public function fetchEvents(){}
	
}

class eventRepository{
	
}

class tags{
	
	// parametredeki etiketleri doğrular
	private function validateTags($tags){}
	
	// etiketleri veritabanına ekler
	private function addTags($tags){}
	
	// bir hücreye etiket ekler
	private function addTagToCell($tagId,$cellId){}
	
	// bir hücrenin etiketlerini çeker
	private function fectCellTags($cellId){}
	
	// kayıtlı tüm etikerleri çeker
	private function fectCells(){}
	
	// parametredeki etiketin diğer tüm bilgilerini de çeker ve bir nesne yapar
	private function makeComplete($tag){}
	
	// parametredeki etiketin diğer tüm bilgilerini de çeker ve bir nesne yapar
	private function makeComplete($tag){}
}

?>
