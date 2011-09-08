<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<style type="text/css">
.wordList{
	padding:10px;
	width:250px;
}
.wordList h2{
	font-family:verdana;
	font-weight:normal;
	font-size:17px;
	color:gray;
}
.wordList .words li{
	margin-bottom:15px;
}
.wordList .words li span{
	margin-right:5px;
}
.wordList .words li .categories span{
	font-family:tahoma,arial,sans-serif;
	display:inline-block;
	padding:2px;
	width:13px;
	margin-right:0px;
	background:#C3C3C3;
	color:white;
	text-align:center;
}
.wordList .words li .categories span.v{
	background:#D72A02;
}
.wordList .words li .categories span.n{
	background:#2A8805;
}
.wordList .words li .categories span.av{
	background:#D72A02;
}
.wordList .words li .categories span.aj{
	background:#17A4ED;
}
.wordList .words li .categories span.pp{
	background:#FFA500;
}
.wordList .words li .level{
	display:inline-block;
	width:10px;
	color:#C3C3C3;
	text-align:center;
}
.wordList .words li .word{
	border:1px solid white;
	padding:1px;
	font-size:16px;
}
.wordList .words li .word:hover{
	/*Box Radius*/
	border-radius:5px;
	-moz-border-radius:5px;
	-khtml-border-radius:5px;
	-webkit-border-radius:5px;
	
	border:1px solid gray;
	background:#FFF7C9;
	padding:1px;
}
</style>

<div class="wordList">
	<h2>KELİMELER LİSTELENİYOR</h2>
	<ul class="words">
		<li>
			<span class="categories">
				<span class="v">v</span>
				<span class="n">n</span>
				<span class="aj">aj</span>
				<span class="av">av</span>
				<span class="pp">pp</span>
			</span>
			<span class="level">9</span>
			<span class="word">car</span>
		</li>
		<li>
			<span class="categories">
				<span>v</span>
				<span>n</span>
				<span>aj</span>
				<span>av</span>
				<span>pp</span>
			</span>
			<span class="level">12</span>
			<span class="word">bike</span>
		</li>
	</ul>
</div>
