<link rel="stylesheet" type="text/css" href="../css/loginForm.css" />
<script type="text/javascript" src="../js/loginForm.js"></script>
<form class="loginForm" method="post" action="">
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

