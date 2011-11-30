<link rel="stylesheet" type="text/css" href="../css/registerForm.css" />
<script type="text/javascript" src="../js/registerForm.js"></script>
<form class="registerForm" method="post" action="">
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


