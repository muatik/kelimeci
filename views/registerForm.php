<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/registerForm.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/createXHR.js"></script>
<script type="text/javascript" src="../js/registerForm.js"></script>
<form class="registerForm" method="post" action="">
	<p>
		<label for="email">E-posta adresi:</label>
		<input type="text" name="email" id="email" maxlength="50" />
	</p>
	<p>
		<label for="userName">Kullanıcı adı:</label>
		<input type="text" name="userName" id="userName" maxlength="50" />
	</p>
	<p>
		<label for="password">Şifre:</label>
		<input type="text" name="password" id="password" maxlength="50" />
	</p>
	<p>
		<label for="password2">Şifre(tekrar):</label>
		<input type="text" name="password2" id="password2" maxlength="50" />
	</p>
	<input type="submit" name="registerFormSubmit" value="Kayıt ol" />
</form>


