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
			$('.fbLogin .fbLoginBtn').on('click',function(){
				// If not connected to the app. or logged out, login 
				if(!response.authResponse){
					FB.login(
						function(response){
							if(response.authResponse)
								//window.location.href='/?type=fb&userId='+uId+'&accToken='+accToken;
								loginViaFb(response);
						},
						{scope:'email,user_birthday,user_hometown,user_about_me'}
					);
				}
				else
					loginViaFb(response);
					//window.location.href='/?type=fb&userId='+uId+'&accToken='+accToken;
			
			});
		}

		function loginViaFb(response){
			var 
				// Auth. response
				authRes=response.authResponse,
				accToken=authRes.accessToken,
				uId=authRes.userID;

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
