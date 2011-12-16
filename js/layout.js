$(function(){

	var
		// User top menu
		$usrMenu=$('#userTopMenu'),
		// User sub top menu
		$usrSubMenu=$('#userSubTopMenu');

	// If the user menu clicked, show the sub user menu
	$usrMenu.click(function(){
		var
			$t=$(this);

		// If hidden
		if($usrSubMenu.is(':hidden')){
			// If it has any forms - user not logined/registered,
			// 	toggle forms properly
			if($usrSubMenu.find('form'))
				toggleFormsInUserMenu();

			$usrSubMenu.show();
		}
		else
			$usrSubMenu.hide();

		return false;
	});

	// Hide the register form on load
	toggleFormsInUserMenu();

	// Insert a link into the login form to show register form
	$usrSubMenu.find('.loginForm .fInput:last')
		.append('<a href="#" class="showRegisterForm">Hesap oluştur!</a>')
		.find('a.showRegisterForm').click(function(){
			toggleFormsInUserMenu('registerLink');
			return false;
		});

	// Insert a link into the register form to show login form
	$usrSubMenu.find('.registerForm .fInput:last')
		.append('<a href="#" class="showLoginForm">Giriş formu!</a>')
		.find('a.showLoginForm').click(function(){
			toggleFormsInUserMenu();
			return false;
		});

});

/**
 * Show/hide the form of login or register
 *
 * @param string Where called from
 */
function toggleFormsInUserMenu(where){
	var
		// Login form
		$lFrm=$('#userSubTopMenu .loginForm');
		// Register form
		$rFrm=$('#userSubTopMenu .registerForm');
	
	if(where=='registerLink'){
		$lFrm.hide();
		$rFrm.show();
	}
	else{
		$lFrm.show();
		$rFrm.hide();
	}
}
