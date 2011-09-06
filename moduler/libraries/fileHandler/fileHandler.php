<?php
class fileHandler
{
	var $current_dir;
	function directories($current_dir='.'){
		$this->current_dir=$current_dir;
	}
	
	function get_files($exts=array('*'=>'*'),$dir=null)
	{
		if($dir==null) $dir=$this->current_dir;
		$dir_files=array();
		if(($odir=opendir($dir))!==false){
			while($dir_item=readdir($odir)){
				if(
					!is_dir($dir.$dir_item) && 
					$dir_item!='.' && 
					$dir_item!='..'
				){
					$ext=explode('.',$dir_item); 
					$ext=mb_strtolower($ext[count($ext)-1]);
					if(isset($exts['*']) || in_array($ext,$exts))
						$dir_files[]=$dir_item;
				}
			}
			return $dir_files;
		}
		else return false;
	}
	
	function getFilesByPrefix($prefix,$dir=null){
		if($dir==null) $dir=$this->current_dir;
		if(substr($dir,0,1)=='/') $dir=substr($dir,1);
		$fList=$this->get_files(array('*'=>'*'),$dir);
		if($fList===false) return false;
		
		$f=array();
		foreach($fList as $i) 
			if(strpos($i,$prefix)===0) $f[]=$i;
		return $f;
	}
	
	function get_dirs($dir=null){
		if($dir==null) $dir=$this->current_dir;
		$dirs=array();
		if(($odir=@opendir($dir))!==false){
			while($dir_item=readdir($odir)){
				if(
					is_dir($dir.'/'.$dir_item) && 
					$dir_item!='.' && 
					$dir_item!='..'
				) $dirs[]=$dir_item;
			}
			return $dirs;
		}
		else return false;
	}
	
	function move_files($source_dir,$destination_dir=null){
		if($destination_dir==null) 
			$destination_dir=$source_dir; 
		else
			$this->current_dir=$source_dir;
		
		if(($s_files=$this->get_files())===false) return false;
		foreach($s_files as $s_file) 
			copy($this->current_dir.$s_file,$destination_dir.'/'.$s_file);
	}
	
	function rmdir($dir=null,$itself=true){
		if($dir==null) $dir=$this->current_dir;
		
		$sdirs=$this->get_dirs($dir);
		if(!is_array($sdirs)) return false;
		foreach($sdirs as $sdir) if($sdir!='.' && $sdir!='..') 
			$this->rmdir($dir.'/'.$sdir);
		
		$sfiles=$this->get_files(array('*'=>'*'),$dir);
		foreach($sfiles as $sfile) unlink($dir.'/'.$sfile);
		
		if($itself) rmdir($dir);
		return true;
	}
	
	public function extractFileExtension($fileName){
		$slices=explode('.',$fileName);
		return $slices[count($slices)-1];
	}
	
	public function deleteFilesByPrefix($prefix,$path=null,$files=null){
		if($path!=null) $this->current_dir=$path;
		if($files==null) $files=$this->getFilesByPrefix($prefix);
		foreach($files as $f) 
			if(strpos($f,$prefix)===0) 
				unlink($this->current_dir.'/'.$f);
	}
}

?>
