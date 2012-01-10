$(function(){
	window.fbAsyncInit=function(){

		FB.init({
			appId:'293374397375346',
			status:true,
			cookie:true,
			xfbml:true,
			oauth:true
		});

		
		function evalFbLoginStatus(response){
			$('.fbLogin .fbLoginBtn').unbind().on('click',function(){
				var $f=$(this).parent();
				// If not connected to the app. or logged out, login 
				if(!response.authResponse){
					FB.login(
						function(response){
							if(response.authResponse)
								loginViaFb(response);
						},
						{scope:'email,user_birthday,user_hometown,user_about_me'}
					);
				}
				else
					loginViaFb(response,$f);
			
			});
		}

		function loginViaFb(response,$f){
			var 
				// Auth. response
				authRes=response.authResponse,
				accessToken=authRes.accessToken,
				userId=authRes.userID,
				origin='facebook';

				if(!accessToken && !userId)
					return 'Invalid or unspeficied parameters via facebook login!';

				/**
				 * Login request with ajax to the users' register
				 * if unregistired user to register.
				 *
				 * If the user is registered, login via facebook.
				 */
				var ajax=new simpleAjax();
				ajax.send(
					'?_ajax=users/login',
					'origin=facebook&'+
						'userId='+userId+'&'+
						'accessToken='+accessToken,
					{'onSuccess':function(rsp,o){
						
						// Login okay
						if(rsp=='1'){
							//window.location.href='/vocabulary';
							alert('Fb login okay and redirect to vocab. page!');
						}
						else{
							// Alert the error
							//showFrmAlert($f,rsp);
							alert(rsp);
						}
						return false;

					}}
				);

		}

		// Run once with current status and whenever the status changes
		FB.getLoginStatus(evalFbLoginStatus);
		FB.Event.subscribe('auth.statusChange',evalFbLoginStatus);	

	};
});

(function(d, s, id) {

	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;

	// If no element with id "fb-root", insert 
	if($('#fb-root').length==0){
		$('.fbLogin:first').append('<div id="fb-root"></div>');
	}

	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/tr_TR/all.js#xfbml=1&appId=293374397375346";
	fjs.parentNode.insertBefore(js, fjs);

}(document, 'script', 'facebook-jssdk'));
