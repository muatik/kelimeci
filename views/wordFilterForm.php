<script src="js/jquery-ui.custom.min.js"></script>
<script src="js/dropDownChecklist.js"></script>
<link rel="stylesheet" href="css/dropDownChecklist.css" />
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
	box-shadow:0px 0px 7px #555;
	-moz-box-shadow:0px 0px 7px #555;
	-webkit-box-shadow:0px 0px 7px #555;
	/* For IE 8 */
	-ms-filter:"progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='#333333')";
	/* For IE 5.5 - 7 */
	filter:progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='#333333');
}
.wordFilterForm{
	padding-left:10px;
	overflow:hidden;
}
.wordFilterForm .frow{
	overflow:hidden;
	margin-bottom:5px;
}
.wordFilterForm .felement{
	margin-right:10px;
	float:left;
	width:180px;
}
.wordFilterForm .felement .flabel{
	float:left;
	width:44px;
	margin-right:4px;
	margin-top:5px;
	text-align:right;
}
.ui-dropdownchecklist {
	width:120px;
}
.levelRange{
	width:120px;
	display:inline-block;
}
.wordFilterForm .keyword{
	width:95px;
}
</style>

<form class="wordFilterForm" method="post" action="">
	<div class="frow">
		<div class="felement">
		<label class="flabel">Tür:</label>
		<select class="classesCheckList" multiple="6">
			<option value="Hepsi">Hepsi</option>
			<option value="verb">Verb</option>
			<option value="noun">Noun</option>
			<option value"adjective">Adjective</option>
			<option value="adverb">Adverb</option>
			<option value="preposition">Preposition</option>
		</select>
		</div>

		<div class="felement">
		<label class="flabel">Diz:</label>
		<select class="orderBy">
			<option value="alphabetically">Alfabetik</option>
			<option value="level">Seviye göre</option>
			<option value="class">Tür göre</option>
		</select>
		</div>
	</div>
	<div>	

		<div class="felement">
		<label class="flabel">Seviye:</label>
		<div class="levelRange"></div>
		<input type="hidden" class="levelRangeInput" />
		</div>

		<div class="felement">
		<label class="flabel">Ara:</label>
		<input type="input" class="keyword" />
		</div>
	</div>

</form>
