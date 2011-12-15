<link rel="stylesheet" type="text/css" href="../css/notification.css" />
<script type="text/javascript" src="../js/notification.js"></script>
<?php
	// If no title, set it as 'Duyuru'
	$o->title=($o->title) ? $o->title : 'Duyuru';

	// Determine if the notification will be hidden or not
	// If not hidable var. or it is true, it is hidable
	$hidable=(!isset($o->hidable) || $o->hidable==true) ? 'true' : 'false';
?>
<div class="notification" hidable="<?php echo $hidable; ?>">
<div class="ntfcontainer">
	<div class="title"><h4><?php echo $o->title; ?></h4></div>
	<div class="body"><?php echo $o->message; ?></div>
</div>
</div>
