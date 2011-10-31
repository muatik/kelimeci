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
			//$alert.html(alertText);	
			alert(alertText);	
			$f.find('input#email').focus();
			return false;

		}

		// If not valid email
		if(!validateEmail(email)){

			var alertText='Geçerli bir e-posta adresi giriniz!';
			//$alert.html(alertText);
			alert(alertText);	
			$f.find('input#email').focus();
			return false;

		}

		// If the email already in use
		if(!checkEmail(email)){

			var alertText='Bu e-posta adresi kullanılıyor. '+
				'Başka bir e-posta adresi seçiniz.'
			//$alert.html(alertText);	
			alert(alertText);	
			$f.find('input#email').focus();
			return false;

		}

		// If the user name already in use
		if(!checkUserName(userName)){

			var alertText='Bu kullanıcı adı kullanılıyor. '+
				'Başka bir kullanıcı adı seçiniz.'
			//$alert.html(alertText);	
			alert(alertText);	
			$f.find('input#userName').focus();
			return false;

		}

		// If the passwords are different
		if(password!=password2){

			var alertText='Şifre ve Şifre(tekrar) birbirinden farklı!';
			//$alert.html(alertText);	
			alert(alertText);	
			$f.find('input#password').focus();
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
					//$alert.html(rsp.error);
					alert(alertText);	
					return false;	
				}
				// REDIRECT
				return false;

			}}
		);
		return false;
	});	
	
	// If the img checkEmail or checkUserName clicked
	$f.find('img.question').click(function(){
		
		var 
			$t=$(this),
			val=$t.parent().find('input').val(),
			label='',
			result;

		if(val=='') return;

		if($t.attr('id')=='checkEmail'){
			if(!validateEmail(val)){
				alert('Önce geçerli bir e-posta adresi giriniz!');
				return;
			}
			result=checkEmail(val);
			label='e-posta adresi';
		}
		else{
			result=checkUserName(val);
			label='kullanıcı adı';
		}

		// If not in use 
		if(result){
			$t.attr({
				'src':'../images/correct.png',
				'alt':label+' uygun',
				'title':label+' uygun'
			});
		}
		else{
			$t.attr({
				'src':'../images/incorrect.png',
				'alt':'Bu '+label+' kullanılıyor. Başka bir tane seçin.',
				'title':'Bu '+label+' kullanılıyor. Başka bir tane seçin.'
			});
		}

	});

	// If the email or userName focused
	$f.find('input#email,input#userName').focus(function(){

		var 
			$t=$(this),
			val=$t.val(),
			img=$t.parent().find('img'),
			label='';
		
		if(val=='') return;

		if(img.attr('src').indexOf('question')!=-1) return;

		if($t.attr('id')=='email')
			label='E-posta adresi';
		else
			label='Kullanıcı adı';

		img.attr({
			'src':'../images/question.png',
			'alt':label+' kullanılıyor mu?',
			'title':label+' kullanılıyor mu?',
		});

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


// Check the email if it is already in use or not
function checkEmail(email){

	var ajax=new simpleAjax();
	ajax.send(
		'?_ajax=checkEmail',
		'email='+encodeURI(email),
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
