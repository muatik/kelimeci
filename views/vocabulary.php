<?php
echo '
<button class="toggleInsertForm">Ekleme Formu</button>
<button class="toggleFilterForm">Süzgeç Formu</button>
';
echo $this->loadView('wordAdditionForm.php');
echo $this->loadView('wordFilterForm.php');
echo $this->loadView('wordList.php');
?>

<link rel="stylesheet" href="css/jquery-ui.custom.css" />
<style>
.wordAdditionForm{margin:14px 5px;}
.wordFilterForm{margin:14px 5px;}
</style>

<script src="js/vocabulary.js"></script>
<script src="js/vocabularyPage.js"></script>
<script>
var x=new vcbPage();
</script>
