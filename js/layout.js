$(function(){

	// If the user menu clicked, show the sub user menu
	$('#userMenu').click(function(){
		var
			$t=$(this),
			$subMenu=$('#userSubMenu');

		// If hidden
		if($subMenu.is(':hidden')){
			// If it has any forms - user not logined/registered,
			// 	toggle forms properly
			if($subMenu.find('form'))
				toggleFormsInUserMenu();

			$subMenu.show();
		}
		else
			$subMenu.hide();

		return false;
	});

	// Hide the register form on load
	toggleFormsInUserMenu();
	
	// If the link register clicked,
	// 	hide the login form and hide the register form
	$('#userSubMenu .loginForm a.register').click(function(){
		toggleFormsInUserMenu('registerLink');
		return false;
	});
	
	// If the button cancel clicked,
	// 	hide the register form and show the login form
	$('#userSubMenu .registerForm a.cancel').click(function(){
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
		$lFrm=$('#userSubMenu .loginForm');
		// Register form
		$rFrm=$('#userSubMenu .registerForm');
	
	if(where=='registerLink'){
		$lFrm.hide();
		$rFrm.show();
	}
	else{
		$lFrm.show();
		$rFrm.hide();
	}
}
