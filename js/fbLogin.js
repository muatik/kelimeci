$(function(){
	window.fbAsyncInit=function(){

		FB.init({
			appId:'293374397375346',
			status:true,
			cookie:true,
			xfbml:true,
			oauth:true
		});

		$('.fbLogin .fbLoginBtn').on('click',function(){
			var 
				$t=$(this);
				// Auth. response
				authRes=null;

			// If not connected to the app. or logged out, login 
			FB.login(
				function(response){
					if(reponse.authResponse){
						authRes=response.authResponse;
						var 
							accToken=authRes.accessToken,
							uId=authRes.userID;

						window.location.href='/?type=fb&userId='+uId+'&accToken='+accToken;

					}
				},
				{
					scope:'email,user_birthday,user_hometown,user_about_me'
				}
			);
		});

		/*
		// Run once with current status and whenever the status changes
		FB.getLoginStatus(doOnStatusChange);
		FB.Event.subscribe('auth.statusChange',doOnStatusChange);	
		*/

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
