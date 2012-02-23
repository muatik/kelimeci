<h1>flashcards</h1>
<?php
$fc=$this->loadElement(
	'flashCardScene.php',
	array('car','white','truck','fine','occasion','appropriate','right','god'),
	false
);

echo $fc;

?>
