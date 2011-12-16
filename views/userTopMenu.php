<link rel="stylesheet" type="text/css" href="../css/userTopMenu.css" />

<?php
// Set the user menu with its sub menu
if(!$o->isLogined){
	$menu='Giriş yap';

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
		<li><a href="#">Ayarlar</a></li>	
		<li><a href="#">Çıkış yap</a></li>	
	</ul>';
}
// Print user menu and its sub menu
echo '<a href="#" id="userTopMenu">'.$menu.'<img src="images/downArrow.png" alt="" /></a>';
echo $subMenu;
?>
<script type="text/javascript">
bindLoginForm($('#userSubTopMenu .loginForm'));
bindRegisterForm($('#userSubTopMenu .registerForm'));
</script>
