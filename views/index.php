<link rel="stylesheet" type="text/css" href="css/index.css" />

<div class="index centerer">

<div class="whatis">
<?php
echo $this->loadView('introduction.php');
echo $this->loadView('todos.php');
?>
</div>

<div class="forms">
<?php

$o2=new stdClass();
$o2->noJs=1;

echo $this->loadView('loginForm.php',$o2);
echo $this->loadView('registerForm.php',$o2);
?>
<script type="text/javascript">
bindLoginForm($('.forms .loginForm'));
bindRegisterForm($('.forms .registerForm'));
</script>
</div>

</div>
