<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<style type="text/css">
.wordAdditionForm{
	/*Box Radius*/
	border-radius:5px;
	-moz-border-radius:5px;
	-khtml-border-radius:5px;
	-webkit-border-radius:5px;
	
	border:1px solid #D2D2D2;
	width:445px;
	padding:5px;
	
	/*
		IE İÇİN TEST ET 
	*/
	/* Box Shadow */
	box-shadow:0px 0px 10px #333;
	-moz-box-shadow:0px 0px 10px #333;
	-webkit-box-shadow:0px 0px 10px #333;
	/* For IE 8 */
	-ms-filter:"progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='#333333')";
	/* For IE 5.5 - 7 */
	filter:progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='#333333');
}
</style>

<form class="wordAdditionForm" method="post" action="">
	<label>Etiket:<input type="text" name="label" maxlength="100" /></label>
	<label>Kelime:<input type="text" name="word" maxlength="100" /></label>
	<input type="submit" name="wordAdditionFormSubmit" value="Ekle" />
</form>
