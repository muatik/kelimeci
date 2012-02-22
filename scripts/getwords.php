<?php
namespace crawlers;
$root=realpath(dirname(__FILE__)).'/';
$root=str_replace('scripts/','',$root);
require_once($root.'/_config.php');
require_once($root.'/moduler/libraries/db/db.php');
require_once($root.'/models/crawlers/crawlers.php');
error_reporting(0);
$c=new crawlers();
while (true){
	$r=$c->db->fetch('select * from words where status=0 order by id desc limit 1 ');
	foreach($r as $k){
		$c->db->query('update words set status=\'3\' where id=\''.$k->id.'\'');
		$c->learn($k->word);
		$c->db->query('update words set status=\'4\' where id=\''.$k->id.'\'');
	}
}
?>
