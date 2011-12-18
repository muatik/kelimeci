<?php
	/**
	 * If not wanted to load css or js file, don't load
	 */
	if(!isset($o->noCss))
		echo '<link rel="stylesheet" type="text/css" href="../css/loginForm.css" />';
	if(!isset($o->noJs))
		echo '<script type="text/javascript" src="../js/loginForm.js"></script>';
?>
<form class="loginForm" method="post" action="">
	<h4 class="frmTitle">Üye Girişi</h4>
	<ul class="frmItems">
		<li>
			<div class="fLabel">
				<label for="lUsername">Kullanıcı adı:</label>
			</div>
			<div class="fInput">
				<input type="text" name="username" id="lUsername" maxlength="50" />
			</div>
		</li>
		<li>
			<div class="fLabel">
				<label for="lPassword">Şifre:</label>
			</div>
			<div class="fInput">
				<input type="password" name="password" id="lPassword" maxlength="50" />
			</div>
		</li>
		<li>
			<div class="fInput">
				<input type="submit" name="loginFormSubmit" value="Giriş yap" />
			</div>
		</li>
	</ul>
</form>

