<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />

<div class="dashboard centerer">

	<div class="commonAndSocialInfo">
		<?php
			echo $this->loadView('userCommonInfo.php');
			echo $this->loadView('userSocialInfo.php');
		?>
	</div>

	<div class="badgeInfo">
		<?php
			echo $this->loadView('userBadgeInfo.php');
		?>
	</div>

</div>
