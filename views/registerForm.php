<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<style type="text/css">
.registerForm{
	padding:10px;
	width:300px;
	background:#fdebac;
}
.registerForm label{
	display:inline-block;
	width:80px;
}
.registerForm input[type=text],.registerForm input[type=password]{
	width:125px;
}
</style>

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/createXHR.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

		// Prepare
		var 
			$f=$('.registerForm'),
			$alert=$('<p class="alert"></p>').appendTo($f);

		$('.registerForm').submit(function(){
			// Prepare
			var
				email=$f.find('input#email').val(),
				userName=$f.find('input#userName').val(),
				password=$f.find('input#password').val(),
				password2=$f.find('input#password2').val();

			// If any inputs is empty
			if(email=='' || userName=='' || password=='' || password2==''){

				var alertText='Tüm bilgileri giriniz!';
				$alert.html(alertText);	
				return false;

			}

			// If not valid email
			if(!validateEmail(email)){

				var alertText='Geçerli bir e-posta adresi giriniz!';
				$alert.html(alertText);
				$f.find('input#email').focus();
				return false;

			}

			// If the user name already in use
			if(!checkUserName(userName)){

				var alertText='Bu kullanıcı adı kullanılıyor. '+
					'Başka bir kullanıcı adı seçiniz.'
				$alert.html(alertText);	
				return false;

			}

			// If the passwords are different
			if(password!=password2){

				var alertText='Şifre ve Şifre(tekrar) birbirinden farklı!';
				$f.find('input#password').focus();
				$alert.html(alertText);	
				return false;

			}

			var ajax=new simpleAjax();
			ajax.send(
				'?_ajax=validateLogin',
				'email='+encodeURI(email)+'&'+
					'userName='+encodeURI(userName)+'&'+
					'password='+encodeURI(password),
				{'onSuccess':function(rsp,o){

					// Error
					if(!rsp.result){
						$alert.html(rsp.error);
						return false;	
					}
					// REDIRECT
					return false;

				}}
			);
			return false;
		});	

	});

	// Check the user name if it is already in use or not
	function checkUserName(userName){

		var ajax=new simpleAjax();
		ajax.send(
			'?_ajax=checkUserName',
			'userName='+encodeURI(userName),
			{'onSuccess':function(rsp,o){
				if(!rsp.result)
					return false;
				else
					return true;

			}}
		);

	}

	// Validate the email
	function validateEmail(data){
		var patt=new RegExp("^[a-zA-Z0-9_\\-.]*@[a-zA-Z0-9_\\-]+.[a-zA-Z]{3,4}.*[a-zA-Z]*$","g"); 
		if(patt.test(data)) return true;
		else false;
	}
</script>
<form class="registerForm" method="post" action="">
	<p><label for="email">E-posta adresi:</label><input type="text" name="email" id="email" /></p>
	<p><label for="userName">Kullanıcı adı:</label><input type="text" name="userName" id="userName" /></p>
	<p><label for="password">Şifre:</label><input type="text" name="password" id="password" /></p>
	<p><label for="password2">Şifre(tekrar):</label><input type="text" name="password2" id="password2" /></p>
	<p><input type="submit" name="registerFormSubmit" value="Kayıt ol" /></p>
</form>


