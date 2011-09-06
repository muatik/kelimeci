<?php
session_start();
Header('Content-Type: image/png'); 
//session_start();
 
$img = imagecreatetruecolor(112, 30);
$white = imagecolorallocate($img, 255, 255, 255); 
$lightblue = imagecolorallocate($img, 165, 203, 209);
$black = imagecolorallocate($img, 0, 0, 0);
$renkler=array(imagecolorallocate($img, 171, 86,184),imagecolorallocate($img, 112, 144,24),imagecolorallocate($img,174,95,31),imagecolorallocate($img,31,122,174));
$code='';
for($i=0;$i<5;$i++) $code.=mt_rand(1,9);
$_SESSION['scode']=$code;
//$_SESSION["sscode"]=$code;
imagefill($img,0,0,$white);
$font=array('Alcohole.ttf','BRADHITC.TTF','acadian.TTF');
//$font=array('/var/www/lctest.com/libs/scode/BRADHITC.TTF','/var/www/lctest.com/libs/scode/acadian.TTF');
$font_count=count($font)-1;
for($i=0;$i<2;$i++) imageline($img,mt_rand(1,69),mt_rand(1,19),mt_rand(1,69),mt_rand(1,19),$lightblue);
imagettftext($img,21,13,4,21,$renkler[mt_rand(0,3)],$font[mt_rand(0,$font_count)],$code[0]);
imagettftext($img,21,-2,22,23,$renkler[mt_rand(0,3)],$font[mt_rand(0,$font_count)],$code[1]);
imagettftext($img,21,5,48,21,$renkler[mt_rand(0,3)],$font[mt_rand(0,$font_count)],$code[2]);
imagettftext($img,21,20,70,25,$renkler[mt_rand(0,3)],$font[mt_rand(0,$font_count)],$code[3]);
imagettftext($img,21,20,94,22,$renkler[mt_rand(0,3)],$font[mt_rand(0,$font_count)],$code[4]);
imagepng($img);
?>
