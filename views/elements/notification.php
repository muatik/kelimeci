<link rel="stylesheet" type="text/css" href="../css/notification.css" />
<script type="text/javascript" src="../js/notification.js"></script>
<?php
	// If no title, set it as 'Duyuru'
	$o->title=($o->title) ? $o->title : 'Duyuru';
?>
<div class="notification">
	<div class="title"><h4><?php echo $o->title; ?></h4></div>
	<div class="body"><?php echo $o->message; ?></div>
</div>
