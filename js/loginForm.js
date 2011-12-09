$(document).ready(function(){
	var 
		$f=$('.loginForm');
	
	$('.loginForm').submit(function(){
		var
			username=$f.find('input[name="username"]').val(),
			password=$f.find('input[name="password"]').val();

		// If there is any empty input elements
		if($f.find(':input[value=""]').length>0){

			var alertText='Kullanıcı adını ve şifreyi giriniz!';
			showFrmAlert($f,alertText);
			// Focus the first empty input element
			$f.find(':input[value=""]:first').focus();
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
					showFrmAlert($f,rsp);
				}
				return false;

			}}
		);
		return false;
	});
});
