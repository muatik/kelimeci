<link rel="stylesheet" href="css/vocabularyPage.css" />

<script src="js/vocabulary.js"></script>
<script src="js/vocabularyPage.js"></script>

<!-- kelime detay kısmı için gerekli betik ve stiller -->
<script tyoe="text/javascript" src="js/words.js"></script>
<link rel="stylesheet" href="css/word.css" />
<link rel="stylesheet" href="css/animbuttons.css" />

<div class="listSide">
<a href="#" class="toggleInsertForm button green small">Kelime Ekle</a>
<a href="#" class="toggleFilterForm button blue small">Kelimeleri Süz</a>
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
