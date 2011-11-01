$(document).ready(function(){
	var 
		$f=$('.loginForm');

	$('.loginForm').submit(function(){
		var
			username=$f.find('input[name="username"]').val(),
			password=$f.find('input[name="password"]').val();

		if(username=='' || password==''){

			var alertText='Kullanıcı adını ve şifreyi giriniz!';
			alert(alertText);
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
					alert('Giriş yapıldı.');
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

});
