 <?php header('Content-Type: text/html; charset=utf-8');?>
<form method="post">
	Paket Adı :<input type="text" name="label" /><br/>
	Kelimeler :(virgül ile ayırarak yazınız)<br/>
	<textarea rows="12" cols="60" name="words"></textarea>
	<br/>
	<input type="submit" name="add" value="ekle">
</form>

  <?php
 require_once('../../_config.php');
 require_once('../../moduler/libraries/db/db.php');
 $db=new db();
 
 if (!isset($_POST['add'])) die();
 $label=$_POST['label'];
 $words=$_POST['words'];
  
 $words=explode(',',$words);
  
 if (count($words)==0)die('kelime bulunamadı');
 echo 'Eklenen Kelimeler :<br />';
 foreach($words as $k){
          
          $word=str_replace('ı','i',mb_strtolower($k));  
          if (empty($word)) continue;
  
          $r=$db->fetchFirst('select * from words where word=\''.$word.'\'');
          
          if (!$r) {
                  echo $k.'<br>';
          }
  
          if ($r)
                  $wordId=$r->id;
          else {
                  $db->query('insert ignore into words(word,status) values(\''
                          .$word.'\',\'0\')');
                  $wordId=$db->getInsertId();
          }
          $db->query('insert into wordPackages(wordId,label) values(\''
                  .$wordId.'\',\''.$db->escape($label).'\')');
  }
 ?>
