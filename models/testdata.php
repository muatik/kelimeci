<?php
namespace crawlers;
require_once('crawlers.php');

$c=new crawlers();
$c->learn('fast');
$r=$c->db->fetch('select * from words');
foreach($r as $k){
	//echo $k->word.'....<br>';
	//$c->learn($k->word);
}

?>
