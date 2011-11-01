$(document).ready(function(){

	// Prepare
	var 
		$f=$('.registerForm'),
		checkEmailResult=null,
		checkUsernameResult=null;

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
			alert(alertText);	
			$f.find('input#email').focus();
			return false;

		}

		// If not valid email
		if(!validateEmail(email)){

			var alertText='Geçerli bir e-posta adresi giriniz!';
			alert(alertText);	
			$f.find('input#email').focus();
			return false;

		}
		if(!checkEmailResult){
			var alertText='Bu e-posta adresi kullanılıyor. '+
				'Başka bir e-posta adresi seçiniz.'
			alert(alertText);	
			$f.find('input#email').focus();
			return false;

		}

		// If the user name already in use
		if(!checkUsernameResult){

			var alertText='Bu kullanıcı adı kullanılıyor. '+
				'Başka bir kullanıcı adı seçiniz.'
			alert(alertText);	
			$f.find('input#username').focus();
			return false;

		}

		// If the passwords are not the same
		if(password!=password2){

			var alertText='Şifre ve Şifre(tekrar) bilgileri aynı olmalı!';
			alert(alertText);	
			$f.find('input#password').focus();
			return false;

		}

		var ajax=new simpleAjax();
		ajax.send(
			'?_ajax=users/register',
			'email='+encodeURI(email)+'&'+
				'username='+encodeURI(userName)+'&'+
				'password='+encodeURI(password),
			{'onSuccess':function(rsp,o){
				
				// Register okay
				if(rsp=='1'){
					alert('Kullanıcı kaydınız gerçekleşti.');
					// DELETE
					return false;
				}
				else{
					// Alert the error
					alert(rsp);
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
				alert('Önce geçerli bir e-posta adresi giriniz!');
				return;
			}
			checkEmailResult=checkEmail(val);
		}
		else{
			checkUsernameResult=checkUsername(val);
		}

		if($t.parent().find('img').length>0)
			$t.parent().find('img').remove();

		// If not in use 
		if(result){
			$t.parent()
				.append('<img src="../images/correct.png" alt="Uygun" title="Uygun"');
		}
		else{
			$t.parent()
				.append('<img src="../images/incorrect.png" alt="Uygun değil!" title="Uygun değil!"');
		}

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

// Check the user name if it is already in use or not
function checkUsername(username){

	var ajax=new simpleAjax();
	ajax.send(
		'?_ajax=users/checkUsername',
		'username='+encodeURI(username),
		{'onSuccess':function(rsp,o){

			// Okay
			if(rsp=='1'){
				return true;
			}
			else{
				return false;
			}

		}}
	);
}


// Check the email if it is already in use or not
function checkEmail(email){

	var ajax=new simpleAjax();
	ajax.send(
		'?_ajax=users/checkEmail',
		'email='+encodeURI(email),
		{'onSuccess':function(rsp,o){
			// Okay
			if(rsp=='1'){
				return true;
			}
			else{
				return false;
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
