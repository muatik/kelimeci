<link rel="stylesheet" href="css/vocabularyPage.css" />

<script src="js/vocabulary.js"></script>
<script src="js/vocabularyPage.js"></script>

<!-- kelime detay kısmı için gerekli betik ve stiller -->
<script tyoe="text/javascript" src="js/words.js"></script>
<link rel="stylesheet" href="css/word.css" />

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
