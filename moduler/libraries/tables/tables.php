<?
class simple_table
{
	
	public $id;
	public $class;
	public $summary;
	public $fields;
	public $values;
	public $row_zebra_class='r2';
	
	function simple_table($id=null,$class=null,$summary=null)
	{
		$this->id=$id;
		$this->class=$class;
		$this->summary=$summary;
	}
	
	function get_html()
	{
		$html='<table';
		if($this->id!='') $html.=' id="'.$this->id.'"';
		if($this->class!='') $html.=' class="'.$this->class.'"';
		if($this->summary!='') $html.=' summary="'.$this->summary.'"';
		$html.='><thead><tr>';
		foreach($this->fields as $f)
		{
			$html.='<th';
			if($f[1]!='') $html.=' class="'.$f[1].'"';
			$html.='>'.$f[0].'</th>';
		}
		$html.='</tr></thead><tbody>';
		
		if($this->row_zebra_class=='')
		{
			$temp=$this->row_zebra_class;
			foreach($this->values as $_vs)
			{
				$html.='<tr>';
				foreach($_vs as $v_k=>$v) $html.='<td class="'.$this->fields[$v_k][1].'">'.$v.'</td>';
				$html.='</tr>';
			}
		}
		else
		{
			$temp=' class="'.$this->row_zebra_class.'" ';
			foreach($this->values as $_vs)
			{
				$html.='<tr '.$temp.'>';
				foreach($_vs as $v_k=>$v) $html.='<td class="'.$this->fields[$v_k][1].'">'.$v.'</td>';
				$html.='</tr>';
				if($temp=='') $temp='class="'.$this->row_zebra_class.'"'; else $temp='';
			}
		}
		$html.='</tbody></table>';
		return $html;
	}
}
/*
$x=new simple_table('liste1','tablolar','falan da filan');
$x->fields=array(array('no',''),array('tarih',''),array('fiyat','float'));
$x->values=array( array(14,'2007 ekim',473.32), array(35,'2002 şubat',363.72), array(56,'2004 ocak',157.42), array(47,'2006 nisan',421.62), array(28,'2007 mart',622.92));
echo $x->get_html();
*/
?>