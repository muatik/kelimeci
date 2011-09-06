<?php
#############################
#                                                                                         #
#                 Advenced Objects  v1.5                        #
#              Date: 08.10.2008   Mustafa Atik           #
#                                                                                         #
#############################
class  ho_dateSelects extends  htmlObject
{
	public $dSelects=array();
	public $tSelects=array();
	public $showTimeSelect=true;
	public $monthName;
	public $dateSeparator='/';
	public $timeSeparator=':';
	public $value;
	public $minTime;
	public $maxTime;
	
	function __construct($restrictions=null,$id=null,$name=null,$label=null,$value=null)
	{
		$this->minTime=date('Y-m-d H:is',time()-(3600*24*365));
		$this->maxTime=date('Y-m-d H:is',time()+(3600*24*365));
		$this->value=date('Y-m-d',time());
		
		parent::__construct($restrictions);
		if($restrictions==null) // eğer tanımlamalar yoksa, parametreden gelenleri kullan.
		{
			$this->name=$name;
			$this->id=$id;
			$this->label=$label;
			$this->value=$value;
		}
		elseif(isset($restrictions['value'])) $this->value=$restrictions['value'];
		
		$this->monthName['turkish']=array(1=>'Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık');
		$this->setDefaultTime($this->value);
		$this->createSelects();
	}
	public function setDefaultTime($t)
	{
		$this->default['time']=strtotime($t);
		$this->default['day']=date('d',$this->default['time'])-1;
		$this->default['month']=date('m',$this->default['time']);
		$this->default['year']=date('Y',$this->default['time']);
		$this->default['hour']=date('H',$this->default['time']);
		$this->default['minute']=date('i',$this->default['time']);
		$this->default['second']=date('s',$this->default['time']);
	}
	public function createSelects($dayRange=null,$monthRange=null,$yearRange=null,$hourRange=null,$minuteRange=null,$secondRange=null)
	{
		if($dayRange==null) $dayRange=range(1,31);
		if($monthRange==null) $monthRange=$this->monthName['turkish'];
		if($yearRange==null)
		{
			$maxYear=substr($this->maxTime,0,4);$minYear=substr($this->minTime,0,4);
			for($i=$minYear;$i<$maxYear+1; $i++) $yearRange[$i]=$i;
		}
		
		$r=array('day','month','year');
		foreach($r as $i)
		{
			$v=$i.'Range'; $v=$$v;
			$dSelects[$i]=new ho_selectbox(null,$this->id.$i,$this->name.$i,$v,$this->default[$i]);
		}
		$this->dSelects=$dSelects;
		
		if($this->showTimeSelect)
		{
			if($hourRange==null) $hourRange=range(0,23);
			if($minuteRange==null) $minuteRange=range(0,59);
			if($secondRange==null) $secondRange=range(0,59);
			
			$r=array('hour','minute','second');
			foreach($r as $i)
			{
				$v=$i.'Range'; $v=$$v;
				$tSelects[$i]=new ho_selectbox(null,$this->id.$i,$this->name.$i,$v,$this->default[$i]);
			}
			$this->tSelects=$tSelects;
		}
	}
	
	public function getHtml()
	{
		$this->createSelects();
		$i=$this->dateSeparator;
		$sBox=$this->dSelects;
		$html=$this->prefixHtml.$sBox['day']->getHtml().$i.$sBox['month']->getHtml().$i.$sBox['year']->getHtml();
		
		if($this->showTimeSelect)
		{
			$i=$this->timeSeparator;
			$sBox=$this->tSelects;
			$html.=' '.$sBox['hour']->getHtml().$i.$sBox['minute']->getHtml().$i.$sBox['second']->getHtml();	
		}
		
		$html.=$this->suffixHtml;
		return $html;
	}
}


class  ho_yesnoOptions extends htmlObject
{
	public $r1;
	public $r2;
	public $labels;
	public $values;
	public function __construct($restrictions=null,$value=array(0,1),$checked=1,$labels=array('Hayır','Evet'),$id=null)
	{
		parent::__construct($restrictions);
		$this->r1=new ho_optionbutton($restrictions);
		$this->r2=new ho_optionbutton($restrictions);
		
		if($restrictions==null) // eğer tanımlamalar yoksa, parametreden gelenleri kullan.
		{
			$this->r1->name=$name;
			$this->r1->id=$id[0];
			$this->r2->id=$id[1];
			$this->label=$label;
		}
		else
		{
			$this->r1->id.='0';
			$this->r2->id.='1';
		}
		
		switch($checked)
		{
			case 1:$this->r1->checked=true;break;
			case 2:$this->r2->checked=true;break;
			default:$this->r1->checked=false;$this->r2->checked=false;
		}
		
		$this->labels=$labels;
		$this->r2->name=$this->r1->name;
		$this->label=$this->r1->label;
		$this->restrictions['null']=$this->r1->restrictions['null'];
		$this->r1->value=$value[0];
		$this->r2->value=$value[1];
		$this->values=$value;
	}
	public function getHTML()
	{
		$html='<span class="yesnoOptions"><label class="slabel">';
		$html.=$this->r1->getHTML();
		$html.=' '.$this->labels[0].'</label><label class="slabel">';
		$html.=$this->r2->getHTML();
		$html.=' '.$this->labels[1].'</label>';
		$html.='<span>';
		return $html;
	}
}

class  hoTinyMCE extends ho_textarea
{
	public $uploadPlugin=false;
	public $tablePlugin=true;
	public $jsDir='../js/';
	public $height=300;
	function getScriptCode()
	{
		$buttons=array();
		if($this->uploadPlugin) $buttons[]=array('image');
		if($this->tablePlugin) $buttons[]=array('tablecontrols');
		
		$js='tinyMCE.init({
		mode : "exact",
		elements:"'.$this->id.'",
		theme : "advanced",
		height:'.$this->height.',
		theme_advanced_buttons1 : "undo,redo,cut,copy,paste,|,formatselect,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,blockquote,|,link",
		theme_advanced_buttons2 : "';
		foreach($buttons as $p){
			foreach($p as $i) $js.=$i.',';
			$js.='|,';
		}
		$js.='",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		plugins : "safari,advlink,imagemanager,table,advimage,advlink,xhtmlxtras",
		language : "tr",
		file_browser_callback : "tinyBrowser"
		';
		
		$js.='});';
		return $js;
	}
}

class  hoCheckList extends ho_selectbox
{
	public function getHtml()
	{
		$i=0;
		$html=$this->prefixHtml.'<ul id="'.$this->id.'">';
		if($this->disabled!=false) $disabledAtt=' disabled="disabled"'; else $disabledAtt='';
		foreach($this->items as $i_key=>$i_value)
		{
			if(is_array($this->value) && isset($this->value[$i_key])) 
			$html.='<li><input type="checkbox" class="checkbox" name="'.$this->name.'[]" id="'.$this->id.$i.'" value="'.$i_key.'" '.$disabledAtt.' checked="checked" /><label for="'.$this->id.$i.'">'.$i_value.'</label></li>';
			else
			$html.='<li><input type="checkbox" class="checkbox" name="'.$this->name.'[]" id="'.$this->id.$i.'" value="'.$i_key.'" '.$disabledAtt.' /><label for="'.$this->id.$i.'">'.$i_value.'</label></li>';
			$i++;
		}
		$html.='</ul>'.$this->suffixHtml;
		return $html;
	}
}

?>
