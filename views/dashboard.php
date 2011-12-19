<link rel="stylesheet" type="text/css" href="../css/dashboard.css" />

<div class="dashboard centerer">

	<div class="commonAndSocialInfo">
		<?php
			echo $this->loadView('userCommonInfo.php',$o);
			echo $this->loadView('userSocialInfo.php',$o);
		?>
	</div>

	<div class="badgeInfo">
		<?php
			echo $this->loadView('userBadgeInfo.php',$o);
		?>
	</div>

</div>
