<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<style type="text/css">
.wordFilterForm{
	/*Box Radius*/
	border-radius:5px;
	-moz-border-radius:5px;
	-khtml-border-radius:5px;
	-webkit-border-radius:5px;
	
	border:1px solid #D2D2D2;
	width:380px;
	padding:5px;
	
	/*
		IE İÇİN TEST ET 
	*/
	/* Box Shadow */
	box-shadow:0px 0px 15px #000;
	-moz-box-shadow:0px 0px 15px #000;
	-webkit-box-shadow:0px 0px 15px #000;
	/* For IE 8 */
	-ms-filter:"progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#000000')";
	/* For IE 5.5 - 7 */
	filter:progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#000000');
}
.wordFilterForm label{
	font-weight:normal;
}
.wordFilterForm .bold{
	font-weight:bold;
}
.wordFilterForm div{
	margin-top:10px;
}
.wordFilterForm div:first-child{
	margin-top:0;
}

</style>

<form class="wordFilterForm" method="post" action="">
	<div>
		<span class="bold">Tür:</span>
		<label><input type="checkbox" name="verb" />Fiil</label>
		<label><input type="checkbox" name="noun" />İsim</label>
		<label><input type="checkbox" name="adverb" />Zarf</label>
		<label><input type="checkbox" name="adjective" />Sıfat</label>
		<label><input type="checkbox" name="preposition" />Edat</label>
		<label><input type="checkbox" name="other" />Diğer</label>
	</div>
	<div>
		<label class="bold">Seviye:<----------></label>
		<label class="bold">Süz:<input type="text" name="filter" /></label>
		<select name="orderBy">
			<option value="1">Az seviye</option>
		</select>
	</div>
</form>
