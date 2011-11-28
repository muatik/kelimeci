resultOfEmailCheck=null;
resultOfUsernameCheck=null;

$(document).ready(function(){

	// Prepare
	var 
		$f=$('.registerForm'),
		checkEmailResult=null,
		checkUsernameResult=null,
		// Error element
		$err=createFrmErr();
	
	// Add the error elem. to the form
	$f.prepend($err);

	$('.registerForm').submit(function(){
		// Prepare
		var
			email=$f.find('input#email').val(),
			username=$f.find('input#username').val(),
			password=$f.find('input#password').val(),
			password2=$f.find('input#password2').val();

		// If any inputs is empty
		if(email=='' || username=='' || password=='' || password2==''){

			var alertText='Tüm bilgileri giriniz!';
			showFrmErr($err,alertText);
			$f.find('input#email').focus();
			return false;

		}

		// If not valid email
		if(!validateEmail(email)){

			var alertText='Geçerli bir e-posta adresi giriniz!';
			showFrmErr($err,alertText);
			$f.find('input#email').focus();
			return false;

		}

		// If the email already in use
		if(!resultOfEmailCheck){
			var alertText='Bu e-posta adresi kullanılıyor. '+
				'Başka bir e-posta adresi seçiniz.'
			showFrmErr($err,alertText);
			$f.find('input#email').focus();
			return false;

		}

		// If the user name already in use
		if(!resultOfUsernameCheck){

			var alertText='Bu kullanıcı adı kullanılıyor. '+
				'Başka bir kullanıcı adı seçiniz.'
			showFrmErr($err,alertText);
			$f.find('input#username').focus();
			return false;

		}

		// The length of the password must be 5
		if(password.length<5){

			var alertText='Şifre en az 5 karakterden oluşmalı!';
			showFrmErr($err,alertText);
			$f.find('input#password').focus();
			return false;

		}

		// If the passwords are not the same
		if(password!=password2){

			var alertText='Şifre ve Şifre(tekrar) bilgileri aynı olmalı!';
			showFrmErr($err,alertText);
			$f.find('input#password').focus();
			return false;

		}

		var ajax=new simpleAjax();
		ajax.send(
			'?_ajax=users/register',
			'email='+encodeURI(email)+'&'+
				'username='+encodeURI(username)+'&'+
				'password='+encodeURI(password),
			{'onSuccess':function(rsp,o){
				
				// Register okay
				if(rsp=='1'){
					window.location.href='/profile?newUser=1';
				}
				else{
					// Alert the error
					showFrmErr($err,rsp);
				}
				return false;

			}}
		);
		return false;
	});	
	
	// If the img checkEmail or checkUsername clicked
	$f.find('input#email,input#username').blur(function(){
	
		var 
			$t=$(this),
			val=$t.val(),
			result;

		if(val=='') return;

		if($t.attr('id')=='email'){
			if(!validateEmail(val)){
				alertText='Önce geçerli bir e-posta adresi giriniz!';
				showFrmErr($err,alertText);
				$f.find('input#email').focus();
				return;
			}
		}
		checkUsability($t);
	});

	// If the email or username focused
	$f.find('input#email,input#username').focus(function(){

		var 
			$t=$(this),
			val=$t.val(),
			img=$t.parent().find('img'),
			label='';
		
		if(val=='') return;
		
		// If has img
		if(img.length>0)
			img.remove();	

	});

});

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
