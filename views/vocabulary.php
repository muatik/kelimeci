<link rel="stylesheet" href="css/jquery-ui.custom.css" />
<link rel="stylesheet" href="css/vocabularyPage.css" />

<script src="js/vocabulary.js"></script>
<script src="js/vocabularyPage.js"></script>

<div class="listSide">
<button class="toggleInsertForm">Ekleme Formu</button>
<button class="toggleFilterForm">Süzgeç Formu</button>
<?php
echo $this->loadView('wordAdditionForm.php');
echo $this->loadView('wordFilterForm.php');
echo $this->loadView('wordList.php');
?>
</div>

<div class="detailSide"></div> 

<script>
var x=new vcbPage();
</script>
