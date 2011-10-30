<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<style type="text/css">
.loginForm{
	padding:10px;
	width:300px;
	background:#fdebac;
}
.loginForm label{
	display:inline-block;
	width:75px;
}
.loginForm input[type=text],.loginForm input[type=password]{
	width:125px;
}
</style>

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/createXHR.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var 
			$f=$('.loginForm'),
			$alert=$('<p class="alert"></p>').appendTo($f);

		$('.loginForm').submit(function(){
			var
				userName=$f.find('input#userName').val(),
				password=$f.find('input#password').val();

			if(userName=='' || password==''){

				var alertText='Kullanıcı adını ve şifreyi giriniz!';
				$alert.html(alertText);	
				return false;

			}

			var ajax=new simpleAjax();
			ajax.send(
				'?_ajax=validateLogin',
				'userName='+encodeURI(userName)+'&'+
					'password='+encodeURI(password),
				{'onSuccess':function(rsp,o){
					
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

</script>
<form class="loginForm" method="post" action="">
	<p><label for="userName">Kullanıcı adı:</label><input type="text" name="userName" id="userName" /></p>
	<p><label for="password">Şifre:</label><input type="text" name="password" id="password" /></p>
	<p><input type="submit" name="loginFormSubmit" value="Giriş yap" /></p>
</form>

