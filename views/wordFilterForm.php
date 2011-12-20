<script src="js/ui.dropdownchecklist-1.4-min.js"></script>
<link rel="stylesheet" href="css/dropDownChecklist.css" />
<link rel="stylesheet" href="css/wordFilterForm.css" />

<form class="wordFilterForm frm" method="post" action="">
	<div class="frow">
		<div class="felement">
		<label class="flabel">Tür:</label>
		<select class="classesCheckList" multiple="6">
			<option value="Hepsi" selected="selected">Hepsi</option>
			<option value="verb">Verb</option>
			<option value="noun">Noun</option>
			<option value="adjective">Adjective</option>
			<option value="adverb">Adverb</option>
			<option value="preposition">Preposition</option>
		</select>
		</div>

		<div class="felement">
		<label class="flabel">Diz:</label>
		<select class="orderBy">
			<option value="date">Tarihe Göre</option>
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
