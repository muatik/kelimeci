<script type="text/javascript" src="js/wordAdditionForm.js"></script>
<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="css/wordAdditionForm.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.autocomplete.css" />

<form class="wordAdditionForm" method="post" action="">
	<label>Etiket:<input type="text" name="tag" maxlength="50" /></label>
	<label>Kelime:<input type="text" name="word" maxlength="60" /></label>
	<input type="submit" name="wordAdditionFormSubmit" value="Ekle" />
</form>

<script type="text/javascript">
	wordAdditionForm.bind();
</script>
