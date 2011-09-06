<?php
#############################
#                                                                                  
#                 DB RECORD CLASS v1.0                        
#              Date: 27.07.2008   Mustafa Atik              
#                                                                                 
#############################
/*
RESTRICTION FORMAT
array
(
	string name		// tablodaki alanın adı
	,string label		// kullanıcının göreceği ad
	[,string hint]		// formlarda bu alan için gözükecek ipucu, açıklama
	,string type		// saklayacağı değerin tipi
	,bool null			// boş geçilebilecek ya da geçilemeyecek mi
	,int minValue		// en küçük/en az saklayabileceği değer
	,int maxValue		// en büyük/en çok saklayabileceği değer
	[,string value]		// varsayılan değeri
	[,bool primary]		// birinci alan mı
	[,array unqiue
		,string table		// kontrolün yapılacağı tablo
		,string field			// kontrolün yapılacağı tablo alanı
		,string msg			// kontrolün geçilmediğinde gözükecek hata mesajı
	]
	[,array fkey
		string table		// kontrolün yapılacağı tablo
		,string field			// kontrolün yapılacağı tablo alanı
		,string name		// değer olarak kullanılabilecek alanın adı
		,string msg			// kontrolün geçilmediğinde gözükecek hata mesajı
	]
	[,array regex
		string pattern		// karşılaştırmada kullanılacak desen
		,string msg			// karşılaştırmada uyumsuzluk olduğunda gözükecekhata mesajı
	]
	[,bool stirptags]		// işaretleme dili içerebilecek mi
	[,bool nouForm		// alan, düzenleme formunda olmayacak mı?
	[,bool noiForm]		// alan, ekleme formunda olmayacak mı?
	,array formElement
		string etype		// alan, hangi form elementiyle gösterilecek
		[,array properties]	// alan pasif mi
		[,array iForm
			string etype
			array properties
		]
		[,array iForm
			string etype
			array properties
		]
)
*/
require_once('dbRecordImg.php');
require_once('dbRecordFile.php');
loadSiteModule(array('dbConnection/dbConnection.php','ho_object/ho_object.php','ho_object/advencedObjects.php','strings/strings.php'));
abstract  class db_record
{
		public $error;			// string	#	en son yapılan işlem sırasında meydana hataları saklar
		public $tbl;				// string	#	işlem yapılacak ana tablonun adını saklar
		public $db;				// object	#	veritabanı işlemlemlerinin yapılacağı bağlantısı nesnesi(class db_connection)
		public $restrictions;	// array		#	tablo alanlarının karakteristiklerini saklayan dizi
		public $values;			// array		#	tablo alanlarının değerini saklayan dizi
		public $form;			// object	#	ekleme ve düzenleme formunu oluşturacak, ho_form sınıfından türetilmiş bir nesne.
		public $fsettings;		// array		#	method,id gibi form parametrelerini saklayan dizi 
		public $uid;			// string	#	sınıftan alınan örneği eşsiz kılan kimlik bilgisi
		
		private $purified=false;
		
		public function __construct($db=null){
			// mevcut bir veritabanı bağlantısı yoksa, yeni bir bağlantı oluşturuluyor. eğer varsa, bu bağlantı kullanılmak için atanıyor.
			if($db==null) $this->db=new dbConnection(); else $this->db=$db;
		}
		
		private function checkValues(){
			// çalışma zamanında doldurulmuş dizinin tüm elemanları için geçerlilik kontrolü yapılıyor.
			foreach($this->values as $ukey=>$uvalue){
				if(!isset($this->restrictions[$ukey])) {$this->error='Eksik değişken tanımlaması : '.$ukey.'=O'; return false;}
				// 1. İşlem = Verinin Karakter uzunluğu kontrolü
				$temp=mb_strlen(trim($uvalue));
				if(!( ($this->restrictions[$ukey]['null']==true && $uvalue=='') || ($this->restrictions[$ukey]['type']!='int' && $temp>=$this->restrictions[$ukey]['minValue'] && $temp<=$this->restrictions[$ukey]['maxValue']) || ($this->restrictions[$ukey]['type']=='int' && is_numeric($uvalue) && $uvalue>=$this->restrictions[$ukey]['minValue'] && $uvalue<=$this->restrictions[$ukey]['maxValue']) )) 
				{
					if($this->restrictions[$ukey]['type']=='int') $this->error.='\''.$this->restrictions[$ukey]['label'].'\' alanının değeri en az '.$this->restrictions[$ukey]['minValue'].', en fazla '.$this->restrictions[$ukey]['maxValue'].' olabilir.';
					else $this->error.='\''.$this->restrictions[$ukey]['label'].'\' alanı en az '.$this->restrictions[$ukey]['minValue'].' harf, en fazla '.$this->restrictions[$ukey]['maxValue'].' harf olabilir.';
				}
				
				// 2. İşlem = Verinin eşsiz olup olmayacağı kontrol ediliyor.
				if(isset($this->restrictions[$ukey]['unique']) && ((!isset($this->values['id']) && $this->db->query('select '.$this->restrictions[$ukey]['unique']['field'].' from '.$this->restrictions[$ukey]['unique']['tablo'].' where '.$this->restrictions[$ukey]['unique']['field'].'=\''.$uvalue.'\' limit 1')) || (isset($this->values['id']) && $this->db->query('select '.$ukey.' from '.$this->tbl.' where id<>'.$this->values['id'].' and '.$this->restrictions[$ukey]['unique']['field'].'=\''.$uvalue.'\' limit 1'))) && $this->db->numRows>0) $this->error.=$this->restrictions[$ukey]['unique']['msg'];
				
				// 3.İşlem = Bağlantılı tabloların olup olmadığı kontrol ediliyor.
				if(isset($this->restrictions[$ukey]['fkey']) && $this->db->query('select '.$this->restrictions[$ukey]['fkey']['field'].' from '.$this->restrictions[$ukey]['fkey']['table'].' where '.$this->restrictions[$ukey]['fkey']['field'].'=\''.$uvalue.'\' limit 1') && $this->db->numRows<1) $this->error.=$this->restrictions[$ukey]['fkey']['msg'];
				
				// 4.İşlem = Verinin desene uygunluğu kontrol ediliyor.
				if(!($this->restrictions[$ukey]['null']==true && $uvalue=='') && (isset($this->restrictions[$ukey]['regex']) && preg_match($this->restrictions[$ukey]['regex']['pattern'],$uvalue)==0)) $this->error.=$this->restrictions[$ukey]['regex']['msg'];
			}

			if($this->error=='') return true; else return false;
		}
		
		public function pickValues($ftype,$restrictions=null,$formMethod=null)
		{
			// yerel değişkenler tanımlanıyor.
			if($restrictions==null) $restrictions=$this->restrictions;
			if($formMethod==null && isset($this->fsettings['method'])) $formMethod=$this->fsettings['method'];
			if($ftype=='uform') $ftype='u'; else $ftype='i';
			
			// verilerin içinde bulunduğu http veri dizisi tespit ediliyor.
			$arr=&$_REQUEST; 
			if(mb_strtoupper($formMethod)=='POST') $arr=&$_POST;
			elseif(mb_strtoupper($formMethod)=='GET') $arr=&$_GET;
			
			if(!$this->purified) {$pArr=purifyInput($arr,'auto',false); $this->purified=true;}
			
			$this->values=array();
			foreach($restrictions as $i)
			{
				$k=$i['name'];
				
				// Eğer tarih seçimi ise; gün, ay, yıl bilgilerini tek bir değer olarak al.
				
				if( (isset($i['formElement'][$ftype.'Form'])  && $i['formElement'][$ftype.'Form'][0]=='dateSelects') || $i['formElement'][0]=='dateSelects')
				{
					$pArr[$k]='';
					if(isset($pArr[$k.'year'],$pArr[$k.'month'],$pArr[$k.'day'])) $pArr[$k]=$pArr[$k.'year'].'-'.$pArr[$k.'month'].'-'.($pArr[$k.'day']+1);
					if(isset($pArr[$k.'hour'],$pArr[$k.'minute'])) $pArr[$k].=' '.$pArr[$k.'hour'].':'.$pArr[$k.'minute'].':00';
				}
				
				if(isset($pArr[$k]))
				{
					$fE=(isset($i['formElement'][$ftype.'Form'])?$i['formElement'][$ftype.'Form']:$i['formElement']);
					if(!isset($fE['disabled']) || $fE['disabled']==false)
					{
						if(isset($i['stripTags']))
						{
							$pArr[$k]=purifyInput($arr[$k],'auto',false,$i['stripTags']);
							$this->values[mb_substr($k,mb_strlen($this->uid))]=$pArr[$k];
						}
						else $this->values[mb_substr($k,mb_strlen($this->uid))]=$pArr[$k];
					}
				}
				elseif(!isset($i['no'.$ftype.'Form'])) $this->error.='\''.$i['label'].'\', ';
			}
			
			if($this->error!='') 
			{
				$this->error=mb_substr($this->error,0,-1).' bilgileri eksik. ';
				return false;
			}
			else return true;
		}
		
		private function prepareSqlFields($to='insert')		// string to
		{
			$_fields='';
			$_values='';
			if($to=='insert')
			{	
				foreach($this->values as $ukey=>$uvalue)
				{
					if(!isset($this->restrictions[$ukey]['primary']))
					{
						$_fields.=$ukey.',';
						$_values.='\''.$uvalue.'\',';
					}
				}
				$_fields=mb_substr($_fields,0,-1);
				$_values=mb_substr($_values,0,-1);
				return array($_fields,$_values);
			}
			else
			{
				foreach($this->values as $ukey=>$uvalue)
				{
					if(!isset($this->restrictions[$ukey]['primary'])) 	$_fields.=$ukey.'=\''.$uvalue.'\',';
				}
				return mb_substr($_fields,0,-1);
			}
			return false;
		}
		
		public function insert()
		{
			$this->error='';
			if(!is_array($this->values) || count($this->values)<1) $this->pickValues('iform');
			$this->checkValues();
			if($this->error!='') return false;
			$field_values=$this->prepareSqlFields();
			if($this->db->query('insert into '.$this->tbl.' ('.$field_values[0].') values('.$field_values[1].')'))
			{
				if($this->db->affectedRows>0) return true;
				else $this->error='Ekleme işlemi tamamlandı fakat veritabanında hiçbir değişiklik olmadı.';
			}
			else $this->error='Ekleme işlemi yapılırken bir hata oluştu.';
			echo $this->db->getError();
			return false;
		}
		
		public function update($primary_field='id')		//string primary_field
		{
			$this->error='';
			if(!is_array($this->values) || count($this->values)<1) $this->pickValues('uform');
			$this->checkValues();
			if($this->error!='') return false;
			$field_values=$this->prepareSqlFields('update');
			if($this->db->query('update '.$this->tbl.' set '.$field_values.' where '.$primary_field.'='.$this->values[$primary_field]))
			{
				$this->error='Değiştirme işlemi tamamlandı fakat veritabanında hiçbir değişiklik olmadı.';
				return true;
			}
			else $this->error='Kayıt değiştirilirken bir hata oluştu.';
			return false;
		}
		
		public function delete($id,$primary_field='id')		// int id
		{
			$this->error='';
			if($this->db->query('delete from '.$this->tbl.' where '.$primary_field.'='.$id)) 
			{
				if($this->db->affectedRows>0)
				{
					if(isset($this->imgSettings)) dbRecordImg::deleteAll($this,$id);
					return true;
				}
				else  $this->error='Kayıt silme işlemi tamamlandı fakat veritabanında hiçbir değişiklik olmadı.';
			}
			else $this->error='Kayıt silinirken bir hata oluştu.';
			return false;
		}
		
		public function fetch($id,$fields='*',$where=null,$vtype='object')	// int id, string fields, string where
		{
			$this->error='';
			if($this->db->query('select '.$fields.' from '.$this->tbl.' where id='.$id.' '.$where.' limit 1'))
			{
				if($this->db->numRows>0)
				{
					if($vtype=='object') $r=$this->db->fetchObject(); else $r=$this->db->fetchArray();
					return $r;
				}
				else $this->error='Kayıt veritabanında bulunamadı.';
			}
			else $this->error='Kayıt veritabanında sorgulanırken bir hata oluştu.';
			return false;
		}
		public function UfetchList($sql,$start=null,$length=null)
		{
			$this->error='';
			if($start!==null && $length!==null) $limit=' limit '.$start.','.$length; else $limit='';
			if(($cs=$this->db->fetchListByQuery($sql.$limit))!==false) return $cs;
			else $this->error='Kayıtlar veritabanından alınırken bir hata oluştu.';
			return false;
		}
		
		public function fetchList($start=null,$length=null,$fields='*',$where=null)		// int id, int length, string fields, string where
		{
			return $this->UfetchList('select '.$fields.' from '.$this->tbl.' '.$where,$start,$length);
		}
		
		public function fetchRecordCount($where=null)	// string where
		{
			$this->error='';
			if( $this->db->query('select count(*) as c from '.$this->tbl.' '.$where) && $this->db->numRows>0)
			{
				$r=$this->db->fetchObject();
				return $r->c;
			}
			$this->error='Toplam kayıt sayısı veritabanından alınırken bir hata oluştu.';
			return false;
		}
		
		public function prepareForm($ftype='insert')
		{
			$this->error='';
			$this->form=new ho_form($this->fsettings['action'],$this->fsettings['method'],$this->fsettings['id']);
			if($ftype=='update') $ftype='u'; else $ftype='i';
			if($this->purified) {$this->values=arrstripslashes($this->values); $this->purified=false;}
			
			foreach($this->restrictions as $k=>$i)
			{
				if(isset($i['no'.$ftype.'Form'])) continue;
				if(isset($this->values[$k])) $i['value']=$this->values[$k];
				if(isset($i['formElement'][$ftype.'Form'])) $elmType=$i['formElement'][$ftype.'Form']; else $elmType=$i['formElement'];
				switch($elmType[0])
				{
					case 'selectbox':
						$this->form->insert(new ho_selectbox($i));
						if(isset($i['fkey'])) $this->fillFromFkey($this->form->els[$i['name']],$i);
						break;
					case 'checkList':
						$this->form->insert(new hoCheckList($i));
						if(isset($i['fkey'])) $this->fillFromFkey($this->form->els[$i['name']],$i);
						break;
					case 'hidden':
						$this->form->insert(new ho_textbox($i));
						$this->form->els[$i['name']]->type='hidden';
						$this->form->els[$i['name']]->show_label=false;
						 break;
					case 'password':
						$this->form->insert(new ho_textbox($i)); $this->form->els[$i['name']]->type='password'; 
						break;
					case 'yesnoOptions':
						$this->form->insert(new ho_yesnoOptions($i,$i['formElement']['params']['value'],(isset($this->values[$k])?$this->values[$k]+1:2)));
						break;
					case 'hoTinyMCE':
						$this->form->insert(new hoTinyMCE($i));
						break;
					case 'dateSelects':$this->form->insert(new ho_dateSelects($i)); break;
					case 'checkbox':$this->form->insert(new ho_checkbox($i)); $this->form->els[$i['name']]->checked=$this->form->els[$i['name']]->value;break;
					case 'optionbutton':$this->form->insert(new ho_optionbutton($i)); break;
					case 'textarea':$this->form->insert(new ho_textarea($i)); break;
					case 'rtextarea':$this->form->insert(new ho_rtextarea($i)); break;
					default : $this->form->insert(new ho_textbox($i));
				}
				if(isset($elmType['properties'])) foreach($elmType['properties'] as $eKey=>$eValue)
				{
					$this->form->els[$i['name']]->$eKey=$eValue;
				}
			}
			$b=new ho_button(); $b->id='submitBtn';$b->class='submit'; $b->value='Kaydet';
			$this->form->insert($b);
			return true;
		}
		
		function fillFromFkey($obj,$i)		// ho_selectbox obj, array i
		{
			if($this->db->query('select '.$i['fkey']['field'].','.$i['fkey']['name'].' from '.$i['fkey']['table'].' where id='.$i['fkey']['field']))
			{
				if($this->db->numRows>0) 
				{
					while($r=$this->db->fetchRow()) $obj->items[$r[0]]=$r[1];
					return true;
				}
				elseif(!$i['null']) $this->error=$i['fkey']['msg'];
			}
			return false;
		}
		
		public function prepareIform()
		{
			if(isset($this->imgSettings)) $this->imgSettings=dbRecordImg::generateNames($this->imgSettings);
			if(isset($this->upFiles)) $this->upFiles=dbRecordFile::generateNames($this->upFiles);
			return $this->prepareForm('insert');
		}
		
		public function prepareUform($id=null)
		{
			$this->error='';
			if($id!=null)
			{
				$r=$this->fetch($id,'*',null,'array');
				if(!is_array($r)) return false;
				$this->values=$r;
				$this->purified=true;
			}
			if(!is_array($this->values)) {$this->error='Değerler dizisi boş. ';return false;}
			
			if(isset($this->imgSettings)) $this->imgSettings=dbRecordImg::generateNames($this->imgSettings,$this->values['id']);
			
			return $this->prepareForm('update');
		}
		
		public function showForm()
		{
			$this->form->show();
			$this->form->getScriptCode();
		}
}
?>
