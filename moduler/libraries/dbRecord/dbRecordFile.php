<?php
dbRecordFile::$rootPath=$_SERVER['DOCUMENT_ROOT'];
class dbRecordFile
{
	public static $rootPath;
	public function generateNames($fileSettings,$prefix='time()')
	{
		$prefix='_'.time();
		foreach($fileSettings as $k=>$i) $fileSettings[$k]['rename']=$fileSettings[$k]['prefix'].$prefix;
		return $fileSettings;
	}
	public function moveToSaveDir($rObj,$timeValue,$newName)
	{
		$upSett=$rObj->upFiles;
		$rP=self::$rootPath;
		$dir=new directories();
		foreach($upSett as $k=>$i)
		{
			$dir->current_dir=$rP.$upSett[$k]['tmpDir'];
			$tmpFiles=$dir->get_files();
			
			foreach($tmpFiles as $tF) 
			{
				if(($nFName=str_replace($upSett[$k]['prefix'].$timeValue,$upSett[$k]['prefix'].$newName.'_',$tF))!=$tF) rename($rP.$upSett[$k]['tmpDir'].'/'.$tF,$rP.$upSett[$k]['saveDir'].'/'.$nFName);
			}
		}
		return $upSett;
	}
	public function preparePanel($obj,$index,$label=null,$listFiles=null)
	{
		$fileSettings=$obj->upFiles[$index];
		$uSID=$index.$fileSettings['prefix'];
		$_SESSION[$index.$fileSettings['prefix']]=$fileSettings;
		$timeValue=substr($fileSettings['rename'],strlen($fileSettings['prefix']));
		$obj->form->insert(new ho_textbox(null,'uSID','uSID',$timeValue,'','hidden'));
		
		$html='';
		if($label!=null) $html.='<label>'.$label.'</label><div class="field">';
		$html.='<div class="uFPanel" id="pnlUF'.$uSID.'">
		<input type="file" name="uF'.$uSID.'" id="uF'.$uSID.'" size="20" /> <button type="button">Yükle</button>
		<div class="upFiles" >';
		
		if($listFiles!=null)
		{
			$rP=self::$rootPath;
			$rP=$rP.$fileSettings['saveDir'];
			$dir=new directories($rP);
			$uploadedFiles=$dir->get_files();
			
			foreach($uploadedFiles as $i) if(strpos($i,$fileSettings['prefix'].$listImgs.'_')!==false) $html.=$i;
		}
		
		$html.='</div><script type="text/javascript">var uF'.$uSID.'Obj=new smUpFiles(\'pnlUF'.$uSID.'\',\'uF'.$uSID.'Obj\');</script>';
		if($label!=null) $html.='</div>'; 
		return $html;
	}
	public function deleteAll($rObj,$recordId)
	{
		$fSett=$rObj->upFiles;
		$rP=self::$rootPath;
		$dir=new directories();
		foreach($fSett as $iSet)
		{
			$dir->current_dir=$rP.$iSet['saveDir'];
			$dir->deleteFilesByPrefix($iSet['prefix'].$recordId);
		}
	}
}
?>
