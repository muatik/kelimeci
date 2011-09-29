<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<style type="text/css">
.wordList{
	padding:10px;
	width:250px;
}
.wordList h2{
	font-family:"cantarell","Trebuchet MS" , Arial, "Bitstream Vera Sans" , sans-serif;
	font-weight:normal;
	font-size:17px;
	color:#444;
}
.wordList .words li{
	margin-bottom:11px;
}
.wordList .words li span{
	margin-right:5px;
}
.wordList .words li .categories span{
	font-family:tahoma,arial,sans-serif;
	font-size:11px;
	display:inline-block;
	padding:1px;
	width:13px;
	margin-right:1px;
	background:#C3C3C3;
	color:white;
	text-align:center;
}
.wordList .categories .active .wordList .categories .v{
	background:#D72A02;
}

.wordList .categories .active.v{background:#D72A02;}
.wordList .categories .active.n{background:#2A8805;}
.wordList .categories .active.aj{background:#17A4ED;}
.wordList .categories .active.av{background:#D72A02;}
.wordList .categories .active.pp{background:#FFA500;}

.wordList .words li .level{
	display:inline-block;
	width:10px;
	color:#C3C3C3;
	text-align:center;
}
.wordList .words li .word{
	border:1px solid white;
	padding:4px 6px;
	font-size:16px;
}
.wordList .words li .word:hover{
	/*Box Radius*/
	border-radius:4px;
	-moz-border-radius:4px;
	-khtml-border-radius:4px;
	-webkit-border-radius:4px;
	
	background-color:#FFF7C9;
	padding:4px 6px;
	cursor:pointer;
}
</style>

<div class="wordList">
	<h2>KELİMELER LİSTELENİYOR</h2>
	<ul class="words">
	<?php
	$words=$o;
	$classList=array(
		'v'=>'verb',
		'n'=>'noun',
		'aj'=>'adjective',
		'av'=>'adverb',
		'pp'=>'prepososition'
	);
	foreach($words as $i){
		$classes=arrays::toArray($i->classes,'name');
		echo '
			<li>
				<span class="categories">';
				foreach($classList as $abbr=>$ci){
					if(in_array($ci,$classes))
						$classActive='active';
					else
						$classActive=null;

					echo '<span class="'.$abbr.' '
						.$classActive.'">'
						.$abbr.'</span>';
				}
				echo '
				</span>

				<span class="level">'.$i->level.'</span>
				<span class="word">'.$i->word.'</span>
					</li>
				';
	}
	?>
		<!--
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
		-->
	</ul>
</div>
