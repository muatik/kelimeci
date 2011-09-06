<?php
#############################
#                                                                                        #
#                 HTML OBJECT CLASS v1.0                        #
#              Date: 27.07.2008   Mustafa Atik               #
#                                                                                        #
#############################
require_once('advencedObjects.php');
abstract class htmlObject
{
	public $id;
	public $name;
	public $class;
	public $title;
	public $accessKey;
	public $tabIndex;
	public $restrictions;
	public $disabled=false;
	
	/* Label  properties  */
	public $showLabel=true;
	public $label;
	public $hint;
	public $prefixHtml;
	public $suffixHtml;
	public $extAttrs;
	
	public function __construct($restrictions=null)
	{
		if($restrictions!=null) $this->getLimitations($restrictions);
	}
	public function getLimitations($restrictions)
	{
		$this->restrictions=$restrictions;
		$this->label=$restrictions['label'].' :';
		$this->name=$restrictions['name'];
		if(isset($restrictions['id'])) $this->id=$restrictions['id']; else $this->id=$restrictions['name']; 
		if(isset($restrictions['hint'])) $this->hint=$restrictions['hint'];
	}
	public function getStndAttrbs() 
	{
		$html=' ';
		if($this->id!='') $html.=' id="'.$this->id.'"';
		if($this->name!='') $html.=' name="'.$this->name.'"';
		if($this->class!='') $html.=' class="'.$this->class.'"';
		if($this->title!='') $html.=' title="'.$this->title.'"';
		if($this->accessKey!='') $html.=' accesskey="'.$this->accessKey.'"';
		if($this->tabIndex!='') $html.=' tabindex="'.$this->tabIndex.'"';
		if($this->extAttrs!='') $html.=' '.$this->extAttrs;
		return $html;
	}
}


class ho_textbox extends htmlObject
{
	public $type='text';		//{text,hidden}
	public $readonly=false;
	public $value;
	public $minLength=-1;
	public $maxLength=-1;
	
	public function __construct($restrictions=null,$id=null,$name=null,$value=null,$label=null,$type='text')
	{
		parent::__construct($restrictions);
		if($restrictions==null)	// eğer tanımlamalar yoksa, parametreden gelenleri kullan.
		{
			$this->id=$id;
			$this->name=$name;
			$this->value=$value;
			$this->label=$label;
			$this->type=$type;
		}
		$this->class='textbox';
	}
	
	public function getLimitations($restrictions)
	{
		parent::getLimitations($restrictions);
		$this->minLength=$restrictions['minValue'];
		$this->maxLength=$restrictions['maxValue'];
		if(isset($restrictions['value'])) $this->value=$restrictions['value'];
	}
	
	function getHtml()
	{
		$html=$this->prefixHtml.'<input type="'.$this->type.'"';
		$html.=$this->getStndAttrbs();
		if($this->disabled!=false) $html.=' disabled="disabled"';
		if($this->readonly!=false) $html.=' readonly="readonly"';
		$html.=' value="'.htmlspecialchars($this->value).'"';
		if($this->maxLength!=-1) $html.=' maxlength="'.$this->maxLength.'"';
		$html.=' />'.$this->suffixHtml;
		return $html;
	}
	function getScriptCode($fornName)
	{
		if($this->restrictions['name']=='' || $this->restrictions['label']=='') return false; else $jsCode='{name:\''.$this->restrictions['name'].'\',label:\''.$this->restrictions['label'].'\',';
		if(isset($this->restrictions['regex'])) 
		{
			$first_pos=strpos($this->restrictions['regex']['pattern'],'/')+1;
			$last_pos=strrpos($this->restrictions['regex']['pattern'],'/');
			$jsCode.='regex:{pattern:\''.(str_replace('\d','[0-9]',mb_substr($this->restrictions['regex']['pattern'],$first_pos,$last_pos-$first_pos))).'\',msg:\''.$this->restrictions['regex']['msg'].'\'},';
		}
		if(isset($this->restrictions['unique'])) $jsCode.='unique:{uKey:\''.base64_encode(base64_encode($this->restrictions['unique'][0].','.$this->restrictions['unique'][1])).'\'},';
		if(isset($this->restrictions['fk'])) $jsCode.='fk:{fKey:\''.base64_encode(base64_encode($this->restrictions['fk'][0].','.$this->restrictions['fk'][1])).'\',msg:\''.$this->restrictions['fk']['msg'].'\'},';
		echo $fornName.'.addElement('.$jsCode.'notnull:'.($this->restrictions['null']?1:0).',minLength:'.$this->restrictions['minValue'].',type:\''.$this->restrictions['type'].'\',maxLength:'.$this->restrictions['maxValue'].',onkeyup:true});';
	}
}


class ho_textarea extends ho_textbox
{
	public $cols=10;
	public $rows=10;
	function __construct($restrictions=null,$id=null,$name=null,$value=null,$label=null)
	{
		parent::__construct($restrictions,$id,$name,$value,$label);
		$this->class='textarea';
	}
	function getHtml()
	{
		$html=$this->prefixHtml.'<textarea cols="'.$this->cols.'" rows="'.$this->rows.'" ';
		$html.=$this->getStndAttrbs();
		if($this->disabled!=false) $html.=' disabled="disabled"';
		if($this->readonly!=false) $html.=' readonly="readonly"';
		$html.='>'.$this->value.'</textarea>'.$this->suffixHtml;
		return $html;
	}
}

class ho_rtextarea extends ho_textarea
{
	public $buttons=array(array('K','ibold','Kalın Yazı'),array('I','iitalic','Eğik Yazı'),array('A','ilink','Bağlantı'),array('AL','ibquote','Alıntı'),array('H2','ih2','Büyük Başlık'),array('H3','ih3','Küçük Başlık'));
	public $img_mng;
	function __construct($restrictions=null,$img_mng=null)
	{
		parent::__construct($restrictions);
		$this->img_mng=$img_mng;
	}
	function getHtml()	
	{
		if($this->id!='') $html='<div id="rct_'.$this->id.'" class="rtextarea">'; else $html='<div class="rtextarea">';
		$html.='<div class="toolbar">';
		foreach($this->buttons as $b) $html.='<img src="../images/panel/toolbar/'.$b[1].'.png" alt="'.$b[0].'" class="'.$b[1].'" title="'.$b[2].'" onmouseover="this.src=\'../images/panel/toolbar/'.$b[1].'_h.png\'" onmouseout="this.src=\'../images/panel/toolbar/'.$b[1].'.png\'" />';
		$html.='</div>';
		if(isset($this->img_mng->params[$this->name]))
		{
			$html.='<div class="img_panel"><input type="button" class="upload_btn" value="Resim Yükle" /><div class="imgs">';
			$p=$this->img_mng->params[$this->name];
			$d=new directories($p['save_path']);
			$d=$d->get_files(array('png','jpg'));
			foreach($d as $f) $html.='<div><img src="getthumbnail.php?file='.$p['save_path'].'/'.$f.'&amp;w=100&amp;h=90" alt="'.$p['save_path'].'/'.$f.'" /><a href="#" class="_rc_'.$this->id.'" onclick="insertImage(this)">Ekle</a><a href="#" class="_rc_'.$this->id.'" onclick="removeImage(this)">Sil</a></div>';
			$html.='</div></div>';
		}
		$html.='<textarea cols="'.$this->cols.'" rows="'.$this->rows.'" ';
		$html.=$this->getStndAttrbs();
		if($this->disabled!=false) $html.=' disabled="disabled"';	
		if($this->readonly!=false) $html.=' readonly="readonly"';	
		$html.='>'.$this->value.'</textarea>'.$this->suffixHtml.'</div>';
		return $html;		
	}
	function getScriptCode($fornName)
	{
		parent::getScriptCode($fornName);
		echo 'var _rc_'.$this->id.'=new rctbox("'.$this->id.'");';
		if($this->img_mng)
		{
			$js=$this->img_mng->get_jsparams();
			echo '_rc_'.$this->id.'.img_mng='.$js.';';
		}
	}
}

class ho_button extends  htmlObject
{
	public $type='submit';
	public $disabled=false;
	public $value='Tamam';
	public $showLabel=false;
	
	function __construct($restrictions=null,$type=null,$value=null)
	{
		parent::__construct($restrictions);
		if($restrictions==null) // eğer tanımlamalar yoksa, parametreden gelenleri kullan.
		{
			if($type!=null) $this->type=$type;
			$this->value=$value;
		}
	}
	function getHtml()
	{
		$html=$this->prefixHtml.'<button type="'.$this->type.'"';
		$html.=$this->getStndAttrbs();
		if($this->disabled!=false) $html.=' disabled="disabled"';
		$html.='>'.$this->value;
		$html.='</button> '.$this->suffixHtml;
		return $html;
	}
}


class ho_optionbutton extends  htmlObject
{
	public $type='radio';
	public $disabled=false;
	public $value;
	public $checked=false;
	
	function __construct($restrictions=null,$name=null,$value=null,$checked=false,$id=null)
	{
		parent::__construct($restrictions);
		if($restrictions==null) // eğer tanımlamalar yoksa, parametreden gelenleri kullan.
		{
			$this->name=$name;
			$this->value=$value;
			$this->checked=$checked;
			$this->id=$id;
		}
	}
	function getHtml()
	{
		$html=$this->prefixHtml.'<input type="'.$this->type.'"';
		$html.=$this->getStndAttrbs();
		if($this->disabled!=false) $html.=' disabled="disabled"';
		if($this->checked!=false) $html.=' checked="checked"';
		$html.=' value="'.$this->value.'"';
		$html.=' /> '.$this->suffixHtml;
		return $html;
	}
}


class ho_checkbox extends  ho_optionbutton
{
	public $type='checkbox';
	public $value;
	public function getLimitations($restrictions)
	{
		parent::getLimitations($restrictions);
		if(isset($restrictions['value'])) $this->value=$restrictions['value'];
	}
}


class ho_selectbox extends  htmlObject
{
	public $disabled=false;
	public $size;
	public $multiple=false;
	public $items=array();
	public $value;
	function __construct($restrictions=null,$id=null,$name=null,$items=null,$value=null)
	{
		parent::__construct($restrictions);
		if($restrictions==null) // eğer tanımlamalar yoksa, parametreden gelenleri kullan.
		{
			$this->name=$name;
			$this->id=$id;
			$this->items=$items;
			$this->value=$value;
		}
	}
	public function getLimitations($restrictions)
	{
		parent::getLimitations($restrictions);
		if(isset($restrictions['value'])) $this->value=$restrictions['value'];
	}
	function getHtml()
	{
		$html=$this->prefixHtml.'<select ';
		if($this->disabled!=false) $html.=' disabled="disabled"';
		if($this->size!=null) $html.=' size="'.$this->size.'"';
		if($this->multiple!=false) $html.=' multiple="multiple"';
		$html.=$this->getStndAttrbs();
		$html.='>';
		foreach($this->items as $i_key=>$i_value) if(($this->value==null && $i_key===$this->value) || ($this->value!=null && $i_key==$this->value)) $html.='<option value="'.$i_key.'" selected="selected">'.$i_value.'</option>'; else $html.='<option value="'.$i_key.'">'.$i_value.'</option>';		
		$html.='</select>'.$this->suffixHtml;
		return $html;
	}
}

class ho_form
{
	public $id;
	public $class='form';
	public $action;
	public $method='get';
	public $enctype='application/x-www-form-url-encoded';	// multipart/form-data   
	public $target;
	public $extAttrs;
	public $els=array();
	public $suffixHtml;
	public $prefixHtml;
	public $suffixElements;
	public $prefixElements;
	public $notNullLabel=true;
	
	function __construct($action=null,$method=null,$id=null,$title=null)
	{
		$this->action=$action;
		$this->id=$id;
		$this->title=$title;
		if($method!=null) $this->method=$method;
	}
	
	function insert($obj)
	{
		$this->els[$obj->id]=$obj;
		return true;
	}
	
	function remove($index)
	{
		unset($this->els[$index]);
		return true;
	}
	
	function getHtml()
	{
		$html='<form  ';
		$html.=' action="'.$this->action.'" method="'.$this->method.'"';
		if($this->id!='') $html.=' id="'.$this->id.'"';
		if($this->class!='') $html.=' class="'.$this->class.'"';
		if($this->enctype!='') $html.=' enctype="'.$this->enctype.'"';
		if($this->target!='') $html.=' target="'.$this->target.'"';
		if($this->extAttrs!='') $html.=' '.$this->extAttrs;
		$html.='>'.$this->prefixHtml;
		$html.='<ul>'.$this->prefixElements;
		$html.=$this->generateUlItems();
		$html.=$this->suffixElements.'</ul>'.$this->suffixHtml.'</form>';
		return $html;
	}
	function generateUlItems()
	{
		$html='';
		$rowClass='';
		$eList=$this->prepareElements();
		foreach($this->els as $i_key=>$i_value) 
		{
			$eHtml=$eList[$i_value->id];
			if(isset($i_value->type) && $i_value->type=='hidden') $html.='<li class="hidden_li '.$rowClass.'">'.$eHtml.'</li>';
			else $html.='<li class="'.$rowClass.'">'.$eHtml.'</li>';
			if($rowClass=='r2') $rowClass=''; else $rowClass='r2';
		}
		return $html;
	}
	function prepareElements()
	{
		$eList=array();
		foreach($this->els as $i_key=>$i_value) 
		{
			$eHtml='';
			if($i_value->showLabel!==false)
			{
				if($i_value->hint!='') $eHint='<div class="hint">'.$i_value->hint.'</div>'; else $eHint='';
				if($i_value->showLabel) $eHtml.= '<label for="'.$i_value->id.'">'.$i_value->label.'</label><div class="field">'.$eHint;
				$eHtml.=$i_value->getHtml();
				if($this->notNullLabel==true && $i_value->restrictions['null']==false) $eHtml.='<span class="not_null">*</span>';
				$eHtml.='<span class="jsmsgbox"></span></div>';
			}
			else $eHtml.=$i_value->getHtml();
			$eList[$i_value->id]=$eHtml;
			//elseif(isset($i_value->type) && $i_value->type=='hidden') $html.=$i_value->getHtml();
		}
		return $eList;
	}
	function show()
	{
		echo $this->getHtml();
	}
	function getScriptCode()
	{
		if($this->id!='') $objName=$this->id.'scnt'; else $objName='r_'.rand(1,100).rand(1,100).'scnt'; 
		echo '<script type="text/javascript">';
		echo 'var '.$objName.'=new FORMCONTROL(\''.$objName.'\');';
		foreach($this->els as $i_value) if(get_class($i_value)=='ho_textbox' || get_class($i_value)=='ho_textarea' || get_class($i_value)=='hoTinyMCE' || get_class($i_value)=='ho_rtextarea') $i_value->getScriptCode($objName);
		echo '</script>';
	}
}
?>
