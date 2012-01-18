<?php
	// FACEBOOK LOGIN
?>
<div class="fbLogin" style="display:inline-block;">
	<fb:login-button show-faces="false" width="400" 
		max-rows="1" class="fbLoginBtn" scope="email,user_hometown,user_birthday">
	</fb:login-button>
</div>
<?php
	/**
	 * If not wanted to load css or js file, don't load
	 */
	if(!isset($o->noJs))
		echo '<script type="text/javascript" src="../js/fbLogin.js"></script>';
?>
