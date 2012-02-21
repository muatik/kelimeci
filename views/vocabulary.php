<link rel="stylesheet" href="css/vocabularyPage.css" />

<script type="text/javascript" src="js/vocabulary.js"></script>
<script type="text/javascript" src="js/vocabularyPage.js"></script>
<script type="text/javascript" src="js/wordHistory.js"></script>
<script type="text/javascript" src="js/jquery-scrolltofixed-min.js"></script>
<script type="text/javascript" src="js/jquery.infinitescroll.js"></script>
<script type="text/javascript" src="js/jquery.jscrollpane.min.js"></script>
<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
<link rel="stylesheet" href="css/jscrollpane/jquery.jscrollpane.css" />

<!-- kelime detay kısmı için gerekli betik ve stiller -->
<script type="text/javascript" src="js/words.js"></script>
<link rel="stylesheet" href="css/word.css" />
<link rel="stylesheet" href="css/animbuttons.css" />

<!-- speaker -->
<script type="text/javascript" src="js/flowplayer/flowplayer-3.2.6.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/speaker.css" />
<script type="text/javascript" src="js/speaker.js"></script>
<!-- /speaker -->

<div id="vcbContainer">

<div class="listSide">
	<div class="vcbForms">
		<a href="#" class="toggleInsertForm button green small">Kelime Ekle</a>
		<a href="#" class="selectPackages button green small">Paket Ekle</a>
		<a href="#" class="toggleFilterForm button blue small">Kelimeleri Süz</a>
		<?php
		echo $this->loadView('wordPackageGroups.php');
		echo $this->loadView('wordAdditionForm.php');
		echo $this->loadView('wordFilterForm.php');
		?>
	</div><!-- /vcbForms  -->
	<?php
	echo $this->loadView('wordList.php');
	?>
</div><!-- /listSide -->

<div class="detailSide"></div><!-- /detailSide -->

</div><!-- /vcbContanier -->

<script type="text/javascript">
var x=new vcbPage();
</script>
