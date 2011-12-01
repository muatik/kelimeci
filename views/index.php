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
echo $this->loadView('loginForm.php');
echo $this->loadView('registerForm.php');
?>
</div>

</div>
