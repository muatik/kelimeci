$(document).ready(function(){

})

$(function(){
	var
		// User top menu
		$usrMenu=$('#userTopMenu'),
		// User sub top menu
		$usrSubMenu=$('#userSubTopMenu'),
		// Object to cancel time out 
		timeOutObjForSubMenu=null,
		// Min. width for the user menu and its sub menu if the user logined
		usrMenuMinWid=120;
	
	

	// Set the min. width for the user and its sub menu if the width smaller than minWid
	//	if the user logined
	if(__usrEmail && $usrMenu.outerWidth(true)<usrMenuMinWid){
		$usrMenu.css('width',usrMenuMinWid+'px');	
		$usrSubMenu.css('width',$usrMenu.outerWidth()+'px');	
	}

	// If the user menu clicked, show the sub user menu
	$usrMenu.click(function(){
		var
			$t=$(this);

		// If hidden
		if($usrSubMenu.is(':hidden')){
			// If it has any forms - user not logined/registered,
			// 	toggle forms properly
			if($usrSubMenu.find('form').length>0){
				toggleFormsInUserMenu();
			}

			// If the menu contains any forms,
			// 	focus the first input on the form
			$usrSubMenu.show().find('form :input:first').focus();

			$usrSubMenu.trigger('onShow');
		}
		else{
			$usrSubMenu.hide().trigger('onHide');
			clearTimeout(timeOutObjForSubMenu);
		}

		return false;
	});

	// Hide the user sub menu within 1 second when it is leaved
	$usrSubMenu.mouseleave(function(){
		timeOutObjForSubMenu=setTimeout(
			function(){$usrSubMenu.hide().trigger('onHide');},1000
		);
		return false;
	});

	/**
	 * Do some operations the browser-specified
	 *
	 * Why is this function used?
	 * Because while showing the menu,
	 * if the browser ff with version less than 8
	 * (Safari's some versions as well) the browser shows 
	 * the horizontal scrollbar because of the menu's box-shadow style.
	 * To fix this, before showing the menu, 
	 * the box-shadow style is being changed and while hiding the menu, 
	 */
	$usrSubMenu.bind('onShow',function(){
		var ua=$.browser;
		
		// If the browser ff with the version less than 8,
		// set overflow-x:auto	
		if(ua.mozilla && ua.version.slice(0,3)<8){
			$('html,body').css('overflow-x','hidden');
		}
	});

	/**
	 * Do some operations the browser-specified
	 *
	 * Why is this function used?
	 * The answer is shown at the description for 
	 * the function named userSubTopMenuOnShow
	 */
	$usrSubMenu.bind('onHide',function(){
		var ua=$.browser;
		
		// If the browser ff with the version less than 8,
		// set overflow-x:auto	
		if(ua.mozilla && ua.version.slice(0,3)<8){
			$('html,body').css('overflow-x','auto');
		}
	});

	// Cancel to hide the sub menu when it is entered
	$usrSubMenu.mouseenter(function(){
		clearTimeout(timeOutObjForSubMenu);
	});

	// If no any forms - user logined, don't do anthing for the forms
	if($usrSubMenu.find('form').length<1)
		return;

	// Hide the register form on load
	toggleFormsInUserMenu();

	// Insert a link into the login form to show register form
	$usrSubMenu.find('.loginForm .fInput:last')
		.append('<a href="#" class="showRegisterForm">&#187; Hesap oluştur</a>')
		.find('a.showRegisterForm').click(function(){
			toggleFormsInUserMenu('registerLink');
			return false;
		});

	// Insert a link into the register form to show login form
	$usrSubMenu.find('.registerForm .fInput:last')
		.append('<a href="#" class="showLoginForm">&#187; Giriş formu</a>')
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

	// Reset the forms
	$lFrm.get(0).reset();
	$rFrm.get(0).reset();

	// Hide the form alerts
	$lFrm.find('.frmAlert').hide();
	$rFrm.find('.frmAlert').hide();
	
	if(where=='registerLink'){
		$lFrm.hide();
		$rFrm.show().find(':input:first').focus();
	}
	else{
		$rFrm.hide();
		$lFrm.show().find(':input:first').focus();
	}
}
