<?php
	/*
	 * If not wanted to load css and js file, don't load
	 */
	if(!isset($o->noCss))
		echo '<link rel="stylesheet" type="text/css" href="../css/registerForm.css" />';
	if(!isset($o->noJs))
		echo '<script type="text/javascript" src="../js/registerForm.js"></script>';
?>
<form class="registerForm frm" method="post" action="">
	<h4 class="frmTitle">Kullanıcı Kaydı</h4>
	<ul class="frmItems">
		<li>
			<div class="fLabel">
				<label for="email">E-posta adresi:</label>
			</div>
			<div class="fInput">
				<input type="text" name="email" id="email" maxlength="50" />
			</div>
		</li>
		<li>
			<div class="fLabel">
				<label for="username">Kullanıcı adı:</label>
			</div>
			<div class="fInput">
				<input type="text" name="username" id="username" maxlength="50" />
			</div>
		</li>
		<li>
			<div class="fLabel">
				<label for="password">Şifre:</label>
			</div>
			<div class="fInput">
				<input type="password" name="password" id="password" maxlength="50" />
			</div>
		</li>
		<li>
			<div class="fLabel">
				<label for="password2">Şifre(tekrar):</label>
			</div>
			<div class="fInput">
				<input type="password" name="password2" id="password2" maxlength="50" />
			</div>
		</li>
		<li>
			<div class="fInput">
				<input type="submit" name="registerFormSubmit" value="Kayıt ol" />
			</div>
		</li>
	</ul>
</form>


