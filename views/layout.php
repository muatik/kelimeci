<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/testPage.css" />
<style type="text/css">
#banner{
	margin:0;
	padding:0;
	width:1000px;
	height:50px;
	background:url(../images/bannerBg.png) #fff repeat-x;
	border-bottom:8px solid #525252;
	overflow:hidden;
}
#logo{
	float:left;
	background-image:url(../images/logo.png);
	margin-left:50px;
	width:131px;
	height:49px;
}
#topMenu{
	float:left;
	overflow:hidden;
	margin:0 0 0 50px;
	width:500px;
	height:49px;
	color:#fff;
}
#topMenu li{
	float:left;
	padding:25px 0 0px 0;
}
#topMenu a,a:link,a:visited,a:hover,a:active{
	padding:25px 12px 0px 12px;
	font:normal 18px sans-serif;
	color:#fff;
}
#topMenu a:hover{
	background:url(../images/topMenuHoverBg.png) repeat-x;
}
</style>

<script type="text/javascript" src="js/createXHR.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>

<div id="banner">
	<div id="logo"></div>
	<ul id="topMenu">
		<li><a href="vocabulary" alt="">Kelimeler</a></li>
		<li><a href="tests" alt="">Test</a></li>
		<li><a href="status" alt="">Durum</a></li>
		<li><a href="settings" alt="">Ayarlar</a></li>
	</ul>
</div>

<?php
echo $this->loadPageLayout();
?>
