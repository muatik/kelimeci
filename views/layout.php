<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr">
<head>
<title><?php echo $this->title;?></title>
<meta http-equiv="content-type" charset="text/html;charset=utf-8" />

<script type="text/javascript" src="js/createXHR.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/feedback.js"></script>

<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/layout.css" />
<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.16.custom.css" />
<link rel="stylesheet" type="text/css" href="css/feedback.css" />

</head>
<body>

<div id="banner">
	<a href="/" id="logo"></a>
	<ul id="topMenu">
		<?php
		$menus=array(
			'vocabulary'=>'Kelimeler',
			'tests'=>'Test',
			'status'=>'Durum',
			'profile'=>'Ayarlar'
		);

		foreach($menus as $k=>$i)
			echo '<li><a href="'.$k.'" 
				'.($this->name==$k?' class="active" ':'').'
				alt="">'.$i.'</a></li>';
		?>
		<?php
			// Add "log out" link if logged in
			if($o->isLogined)
				echo '<li><a href="?_ajax=users/logout" alt="">Çıkış</a></li>';
		?>
	</ul>
	<a href="#" id="feedbackImg"></a>
</div>

<form id="feedbackForm" method="post" action="">
	<h4 class="frmTitle">Görüş Bildirim Formu</h4>
	<p>
		<label for="fbEmail">E-posta:</label>
		<input type="text" name="fbEmail" id="fbEmail" />
	</p>
	<p>
		<label for="fbMessage">Görüş:</label>
		<textarea name="fbMessage" id="fbMessage"></textarea>
	</p>
	<input type="submit" name="submitFeedback" value="Gönder" />
</form>

<?php
echo $this->loadPageLayout();
?>

</body>
</html>
