<?php
dbRecordImg::$rootPath=$_SERVER['DOCUMENT_ROOT'];
class dbRecordImg
{
	public static $rootPath;
	public function generateNames($imgSettings,$prefix='time()')
	{
		$prefix='_'.time();
		foreach($imgSettings as $k=>$i)
		{
			$imgSettings[$k]['rename']=$imgSettings[$k]['prefix'].$prefix;
			if(isset($imgSettings[$k]['thumbnails']))
			foreach($imgSettings[$k]['thumbnails'] as $tKey=>$tImg) $imgSettings[$k]['thumbnails'][$tKey]['rename']=$tImg['prefix'].$prefix;
		}
		return $imgSettings;
	}
	public function moveToSaveDir($rObj,$timeValue,$newName)
	{
		$imgSettings=$rObj->imgSettings;
		$rP=dbRecordImg::$rootPath;
		$dir=new directories();
		foreach($imgSettings as $k=>$i)
		{
			$dir->current_dir=$rP.$imgSettings[$k]['tmpDir'];
			$tmpFiles=$dir->get_files();
			
			foreach($tmpFiles as $tF) 
			{
				if(($nFName=str_replace($imgSettings[$k]['prefix'].$timeValue,$imgSettings[$k]['prefix'].$newName.'_',$tF))!=$tF) rename($rP.$imgSettings[$k]['tmpDir'].'/'.$tF,$rP.$imgSettings[$k]['saveDir'].'/'.$nFName);
			}
			
			if(isset($imgSettings[$k]['thumbnails']))
			foreach($imgSettings[$k]['thumbnails'] as $tKey=>$tImg)
			{
				foreach($tmpFiles as $tF) 
				{
					if(($nFName=str_replace($tImg['prefix'].$timeValue,$tImg['prefix'].$newName.'_',$tF))!==$tF) rename($rP.$imgSettings[$k]['tmpDir'].'/'.$tF,$rP.$tImg['saveDir'].'/'.$nFName);
				}
			}
		}
		return $imgSettings;
	}
	public function prepareImgPanel($obj,$index,$label=null,$listImgs=null)
	{
		$imgSettings=$obj->imgSettings[$index];
		$uSID=$index.$imgSettings['prefix'];
		$_SESSION[$index.$imgSettings['prefix']]=$imgSettings;
		$timeValue=substr($imgSettings['rename'],strlen($imgSettings['prefix']));
		$obj->form->insert(new ho_textbox(null,'uSID','uSID',$timeValue,'','hidden'));
		
		$html='';
		if($label!=null) $html.='<label>'.$label.'</label><div class="field">';
		$html.='<div class="imgPanel" id="pnl'.$uSID.'">
		<button type="button" onclick="window.open(\'uploadPhoto.php?uSID='.$uSID.'&varName=ip'.$uSID.'\',\'\',\'height=230,width=490,resizabled=true,scrollbars=yes\')">Yükle</button><div class="thumbnails" >
		<script type="text/javascript">var ip'.$uSID.'=new iframePhotoUplaod(\''.$uSID.'\',\'ip'.$uSID.'\',\''.$imgSettings['tmpDir'].'\');';
		
		if($listImgs!=null)
		{
			$rP=dbRecordImg::$rootPath;
			$rP=$rP.$imgSettings['saveDir'];
			$dir=new directories($rP);
			$imgFiles=$dir->get_files();
			
			foreach($imgFiles as $i) if(strpos($i,$imgSettings['prefix'].$listImgs.'_')!==false) $html.='ip'.$uSID.'.insert(\''.$i.'\',\'../'.$imgSettings['saveDir'].'\');'; //$html.='<div><img src="../libs/imageFunctions.php?fName=..'.$imgSettings['saveDir'].'/'.$i.'&amp;width=140&amp;height=105&amp;style=fixed" /></div>';
		}
		
		$html.='</script></div>';
		if($label!=null) $html.='</div>'; 
		return $html;
	}
	public function deleteAll($rObj,$recordId)
	{
		$imgSett=$rObj->imgSettings;
		$rP=dbRecordImg::$rootPath;
		$dir=new directories();
		foreach($imgSett as $iSet)
		{
			$dir->current_dir=$rP.$iSet['saveDir'];
			$dir->deleteFilesByPrefix($iSet['prefix'].$recordId);
			if(isset($iSet['thumbnails'])) foreach($iSet['thumbnails'] as $tSet)
			{
				$dir->current_dir=$rP.$tSet['saveDir'];
				$dir->deleteFilesByPrefix($imgFiles,$tSet['prefix'].$recordId);
			}
		}
	}
}
?>
