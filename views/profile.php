<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<style type="text/css">
.profilePage{

}
.profileForm{
	padding:10px;
	width:300px;
	background:#fdebac;
}
.profileForm label{
	display:inline-block;
	width:80px;
}
.profileForm input[type=text],.profileForm input[type=password]{
	width:125px;
}
</style>

<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/createXHR.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

		// Prepare
		var 
			$f=$('.profileForm'),
			$alert=$('<p class="alert"></p>').appendTo($f);

		$('.profileForm').submit(function(){
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
<div class="profilePage">
<form class="profileForm" method="post" action="">
	<p>
		<label for="userName">Kullanıcı adı:</label>
		<?php //echo $o->userName; ?>
	</p>
	<p>
		<label for="email">E-posta adresi:</label>
		<input type="text" name="email" id="email" value="<?php //echo $o->email; ?>" />
	</p>
	<p>
		<label for="password">Mevcut şifre:</label>
		<input type="text" name="currentPassword" id="currentPassword" />
	</p>
	<p>
		<label for="newPassword">Yeni şifre:</label>
		<input type="text" name="newPassword" id="newPassword" />
	</p>
	<p>
		<label for="newPassword2">Yeni şifre(tekrar):</label>
		<input type="text" name="newPassword2" id="newPassword2" />
	</p>
	<p>
		<label for="city">Şehir:</label>
		<select name="city" id="city">
			<option value="Seçiniz" selected="selected">Seçiniz</option>
		</select>
	</p>
	<p>
		<label for="pratic">Pratik yapmak ister misin:</label>
		<input type="checkbox" name="praticYes" id="praticYes" value="yes" />
		<label for="praticYes">Evet</label>
	</p>
	<p>
		<input type="submit" name="profileFormSubmit" value="Güncelle" />
	</p>
</form>
</div>

