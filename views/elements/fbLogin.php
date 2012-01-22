<?php
	// FACEBOOK LOGIN
?>
<div class="fbLogin" style="display:inline-block;">
	<a href="#" class="fbLoginBtn">
		<img src="../images/fbLoginBtn.png" />
	</a>	
</div>
<?php
	/**
	 * If not wanted to load css or js file, don't load
	 */
	if(!isset($o->noJs))
		echo '<script type="text/javascript" src="../js/fbLogin.js"></script>';
?>
