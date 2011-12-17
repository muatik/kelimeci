<link rel="stylesheet" type="text/css" href="../css/userTopMenu.css" />

<?php
// Set the user menu with its sub menu
if(!$o->isLogined){
	$menu='Giriş yap / Hesap oluştur';

	$o2=new stdClass();
	$o2->noCss=1;

	$subMenu='<div id="userSubTopMenu">';
	$subMenu.=$this->loadView('loginForm.php',$o2);
	$subMenu.=$this->loadView('registerForm.php',$o2);
	$subMenu.='</div>';
}
else{
	$menu=$this->u->email;
	$subMenu='
	<ul id="userSubTopMenu">
		<li><a href="#"><img src="images/settings.png" alt="" />Ayarlar</a></li>	
		<li><a href="profile"><img src="images/profile.png" alt="" />Profil</a></li>	
		<li><a href="?_ajax=users/logout"><img src="images/logout.png" alt="" />Çıkış yap</a></li>	
	</ul>';
}
// Print user menu and its sub menu
echo '<a href="#" id="userTopMenu">'.$menu.'<img src="images/downArrow.png" alt="" /></a>';
echo $subMenu;

echo '<script type="text/javascript">';
if(!$o->isLogined){
	echo "
		bindLoginForm($('#userSubTopMenu .loginForm'));
		bindRegisterForm($('#userSubTopMenu .registerForm'));

		// Set the font uppercase for the login and register form
		$('#userSubTopMenu .loginForm .frmTitle').text('ÜYE GİRİŞİ');
		$('#userSubTopMenu .registerForm .frmTitle').text('HESAP OLUŞTUR');
	";
}
else{
	echo "$('#userSubTopMenu').css('width',$('#userTopMenu').outerWidth()+'px')";
}
echo '</script>';
?>
