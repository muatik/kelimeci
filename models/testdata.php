<?php
namespace crawlers;
require_once('crawlers.php');
error_reporting(0);
$c=new crawlers();
$c->learn("fast");
while (true){
	$r=$c->db->fetch('select * from words where status=0 limit 1');
	foreach($r as $k){
		$c->db->query('update words set status=\'1\' where id=\''.$k->id.'\'');
		$c->learn($k->word);
		$c->db->query('update words set status=\'2\' where id=\''.$k->id.'\'');
	}
}
?>
