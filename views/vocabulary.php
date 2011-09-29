<?php
echo '
<button class="toggleInsertForm">Ekleme Formu</button>
<button class="toggleFilterForm">Süzgeç Formu</button>
';
echo $this->loadView('wordAdditionForm.php');
echo $this->loadView('wordFilterForm.php');
echo $this->loadView('wordList.php');
?>
<script src="js/vocabularyPage.js"></script>

