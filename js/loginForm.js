$(document).ready(function(){
	var 
		$f=$('.loginForm'),
		$err=createFrmErr();
	
	// Add the error elem. to the form
	$f.prepend($err);
	
	$('.loginForm').submit(function(){
		var
			username=$f.find('input[name="username"]').val(),
			password=$f.find('input[name="password"]').val();

		if(username=='' || password==''){

			var alertText='Kullanıcı adını ve şifreyi giriniz!';
			showFrmErr($err,alertText);
			return false;

		}

		var ajax=new simpleAjax();
		ajax.send(
			'?_ajax=users/login',
			'username='+encodeURI(username)+'&'+
				'password='+encodeURI(password),
			{'onSuccess':function(rsp,o){
				
				// Login okay
				if(rsp=='1'){
					window.location.href='/vocabulary';
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
});
