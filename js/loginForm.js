$(document).ready(function(){
	var 
		$f=$('.loginForm'),
		$alert=$('<p class="alert"></p>').appendTo($f);

	$('.loginForm').submit(function(){
		var
			userName=$f.find('input[name="userName"]').val(),
			password=$f.find('input[name="password"]').val();

		if(userName=='' || password==''){

			var alertText='Kullanıcı adını ve şifreyi giriniz!';
			//$alert.html(alertText);	
			alert(alertText);
			return false;

		}

		var ajax=new simpleAjax();
		ajax.send(
			'?_ajax=validateLogin',
			'userName='+encodeURI(userName)+'&'+
				'password='+encodeURI(password),
			{'onSuccess':function(rsp,o){
				
				if(!rsp.result){
					//$alert.html(rsp.error);
					alert(rsp.error);
					return false;	
				}
				// REDIRECT
				return false;

			}}
		);
		return false;
	});	

});
