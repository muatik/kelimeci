<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr" lang="tr" xmlns:fb="http://ogp.me/ns/fb#">
<head>
<!--<link 
	href="http://fonts.googleapis.com/css?family=Overlock+SC|Overlock&subset=latin-ext"
	rel="stylesheet" type="text/css" 
/>-->

<title><?php echo $this->title;?></title>
<meta http-equiv="content-type" charset="text/html;charset=utf-8" />

<script type="text/javascript" src="js/createXHR.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.qtip.min.js"></script>
<script type="text/javascript" src="js/jquery.center.js"></script>
<script type="text/javascript" src="js/tooltip.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/layout.js"></script>
<script type="text/javascript" src="js/feedback.js"></script>
<script type="text/javascript" src="js/jquery.autocomplete.js"></script>

<link rel="stylesheet" type="text/css" href="css/reset.css" />
<link rel="stylesheet" type="text/css" href="css/common.css" />
<link rel="stylesheet" type="text/css" href="css/layout.css" />
<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.16.custom.css" />
<link rel="stylesheet" type="text/css" href="css/feedback.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.qtip.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="css/tooltip.css" />

</head>
<body>
<div id="banner">
	<a href="/" id="logo"></a>
	<?php
		echo '<ul id="topMenu">';

		$menus=array(
			'vocabulary'=>'Kelimelerim',
			'tests'=>'Testlerim',
			'status'=>'Durumum'
		);

		foreach($menus as $k=>$i)
			echo '<li><a href="'.$k.'" 
				'.($this->name==$k ? ' class="active" ' : '').'
				alt="">'.$i.'</a></li>';
		
		echo '</ul>';

		// Set the top user menu with its sub menu
		$o2=new stdClass();
		$o2->isLogined=$o->isLogined;
		echo $this->loadView('userTopMenu.php',$o2);
	?>
	<form id="wordSearch" method="get" action="search">
		<input type="text" name="word" id="word" placeholder="kelime ara" 
			accesskey="w" />
		<img src="images/search.png" class="searchBtn" alt="" />
	</form>
	<a href="#" id="feedbackImg">Görüş bildir</a>
</div>

<?php
	// Insert the popup word detail div into the DOM
	echo $this->loadElement('popupWordDetail.php');

	/**
	 * If the user logined, 
	 *	store the email in a global var. for js
	 * 	to add it into email textbox on feedback form
	 */
	$usrEmail='null';
	if($o->isLogined){
		$usrEmail='\''.$this->u->email.'\'';
	}
	echo '<script type="text/javascript">var __usrEmail='.$usrEmail.';</script>';

echo $this->loadPageLayout();
?>

</body>
</html>
