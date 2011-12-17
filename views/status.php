<?php

$o2=new stdClass();
$o2->title='Duyuru';
$o2->message='Bu sayfa yapım aşamasındadır.';
$o2->hidable=false;
echo $this->loadElement('notification.php',$o2);

?>

