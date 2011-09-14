<?php
$q=$_REQUEST['q'];

$content=file_get_contents("http://translate.google.com/translate_a/t?client=t&text=$q&hl=en&sl=tr&tl=en");
//$content=file_get_contents("http://translate.google.com/translate_a/t?client=t&text=$q&hl=tr&sl=en&tl=tr");
header('content-type:text/plain;charset=utf-8;');
$content=mb_convert_encoding($content,'UTF-8','ISO-8859-9');
$content=strip_tags(mb_substr($content,0,strpos($content,',"tr"')).']');
//$content=strip_tags(mb_substr($content,0,strpos($content,',"en"')).']');
print_r(json_decode($content));
?>
