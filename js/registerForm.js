resultOfEmailCheck=null;
resultOfUsernameCheck=null;

/**
 * Bind a register form
 *
 * @param object $frm Explicit register form object to bind
 */
function bindRegisterForm($frm){

	// Prepare
	var 
		$f=$frm;
		checkEmailResult=null,
		checkUsernameResult=null;

	$f.submit(function(){
		// Prepare
		var
			$email=$f.find('input#email'),
			$username=$f.find('input#username'),
			$password=$f.find('input#password'),
			$password2=$f.find('input#password2');

		// If there is any empty input elements, focus the first one
		if($f.find(':input[value=""]').length>0){

			var alertText='Tüm bilgileri giriniz!';
			showFrmAlert($f,alertText);
			// Focus the first empty input element
			$f.find(':input[value=""]:first').focus();
			return false;

		}

		// If not valid email
		if(!validateEmail($email.val())){

			var alertText='Geçerli bir e-posta adresi giriniz!';
			showFrmAlert($f,alertText);
			$email.focus();
			return false;

		}

		// If the email already in use
		if(!resultOfEmailCheck){

			var alertText='Bu e-posta adresi kullanılıyor. '+
				'Başka bir e-posta adresi seçiniz.';
			showFrmAlert($f,alertText);
			$email.focus();
			return false;

		}

		// If the user name already in use
		if(!resultOfUsernameCheck){

			var alertText='Bu kullanıcı adı kullanılıyor. '+
				'Başka bir kullanıcı adı seçiniz.';
			showFrmAlert($f,alertText);
			$username.focus();
			return false;

		}

		// The length of the password must be 5
		if($password.val().length<5){

			var alertText='Şifre en az 5 karakterden oluşmalı!';
			showFrmAlert($f,alertText);
			$password.focus();
			return false;

		}

		// If the passwords are not the same
		if($password.val()!=$password2.val()){

			var alertText='Şifre ve Şifre(tekrar) bilgileri aynı olmalı!';
			showFrmAlert($f,alertText);
			$password.focus();
			return false;

		}

		var ajax=new simpleAjax();
		ajax.send(
			'?_ajax=users/register',
			'origin=kelimeci&'+
				'email='+encodeURI($email.val())+'&'+
				'username='+encodeURI($username.val())+'&'+
				'password='+encodeURI($password.val()),
			{'onSuccess':function(rsp,o){

				// Register okay
				if(rsp=='1'){
					window.location.href='/profile?newUser=1';
				}
				else{
					// Alert the error
					showFrmAlert($f,rsp);
				}
				return false;

			}}
		);
		return false;
	});	

	// If the img checkEmail or checkUsername clicked
	$f.find('input#email,input#username').change(function(){
	
		var 
			$t=$(this),
			val=$t.val(),
			result;

		if(val=='') return;

		if($t.attr('id')=='email'){
			if(!validateEmail(val)){
				alertText='Geçerli bir e-posta adresi giriniz!';
				alertText2='Geçersiz e-posta adresi!';
				showFrmAlert($f,alertText);
				$t.parent().find('img')
					.remove().end()
					.append('<img src="../images/incorrect.png" alt="'+alertText2+'" title="'+alertText2+'" />');

				return;
			}
		}
		checkUsability($t);
	});
}

// Check the usability of the email or the username
function checkUsability(inputElem){

	var
		ajax=new simpleAjax(),
		$elem=$(inputElem),
		id=$elem.attr('id'),
		val=$elem.val(),
		_ajax='?_ajax=users/',
		param='',
		resultVar=null;
	
	// If the input is email
	if(id=='email'){
		_ajax+='checkEmail';
		param='email='+encodeURI(val);
	}
	// If the input is username
	else{
		_ajax+='checkUsername';
		param='username='+encodeURI(val);
	}

	ajax.send(
		_ajax,
		param,
		{'onSuccess':function(rsp,o){
			// Okay
			if(rsp=='1'){
				if(id=='email') 
					resultOfEmailCheck=true;
				else
					resultOfUsernameCheck=true;

				$elem.parent().find('img').remove().end()
					.append('<img src="../images/correct.png" alt="Uygun" title="Uygun" />');

			}
			else{
				if(id=='email') 
					resultOfEmailCheck=false;
				else
					resultOfUsernameCheck=false;

				$elem.parent().find('img').remove().end()
					.append('<img src="../images/incorrect.png" alt="Uygun değil!" title="Uygun değil!" />');
			}

		}}
	);


}

// Validate the email
function validateEmail(data){
	var patt=new RegExp("^[a-zA-Z0-9_\\-.]*@[a-zA-Z0-9_\\-]+.[a-zA-Z]{3,4}.*[a-zA-Z]*$","g"); 
	if(patt.test(data)) return true;
	else false;
}
