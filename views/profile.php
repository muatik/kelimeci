<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="../css/reset.css" />
<link rel="stylesheet" type="text/css" href="../css/common.css" />
<link rel="stylesheet" type="text/css" href="../css/profile.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/createXHR.js"></script>
<script type="text/javascript" src="../js/profile.js"></script>
<div class="profilePage">
<form class="profileForm" method="post" action="">
	<p>
		<label for="userName">Kullanıcı adı:</label>
		<?php echo $o->userName; ?>
	</p>
	<p class="personel" style="border-top:1px solid #e2e2e2;">
		<h4>Kişisel Bilgilerini Güncelle</h4>
		<p>
			<label for="name">Ad soyad:</label>
			<input type="text" name="name" id="name" maxlength="50" value="<?php echo $o->name; ?>" />
		</p>
		<p>
			<label for="birthDate">Doğum tarihi:</label>
			<input type="text" name="birthDate" id="birthDate" maxlength="20" value="<?php echo $o->birthDate; ?>" />
		</p>
		<input type="button" name="updatePersonelInfo" value="Güncelle" />
	</p>
	<p class="email" style="border-top:1px solid #e2e2e2;">
		<h4>E-posta adresini Güncelle</h4>
		<p>
			<label for="email">E-posta adresi:</label>
			<input type="text" name="email" id="email" maxlength="50" value="<?php echo $o->email; ?>" />
		</p>
		<input type="button" name="updateEmail" value="Güncelle" />
	</p>
	<p class="password" style="border-top:1px solid #e2e2e2;">
		<h4>Şifreni Güncelle</h4>
		<p>
			<label for="password">Mevcut şifre:</label>
			<input type="text" name="currentPassword" id="currentPassword" maxlength="50" />
		</p>
		<p>
			<label for="newPassword">Yeni şifre:</label>
			<input type="text" name="newPassword" id="newPassword" maxlength="50" />
		</p>
		<p>
			<label for="newPassword2">Yeni şifre(tekrar):</label>
			<input type="text" name="newPassword2" id="newPassword2" maxlength="50" />
		</p>
		<input type="button" name="updatePassword" value="Güncelle" />
	</p>
	<p class="practice" style="border-top:1px solid #e2e2e2;">
		<h4>Pratik</h4>
		<p>
			<label for="practice" style="width:auto;">Pratik yapmak ister misin:</label>
			<?php
				$city='';
				if($o->practice=='yes'){
					echo '<input type="checkbox" name="practiceYes" 
						id="practiceYes" value="yes" checked="checked" />';

					// On page load, set selected city with this input or
					// disable the city select box
					if($o->city)
						$city='<input type="hidden" name="storedCity" id="storedCity" value="'.$o->city.'" />';	
				}
				else{
					echo '<input type="checkbox" name="practiceYes" 
						id="practiceYes" value="yes" />';
				}
			?>
			<label for="practiceYes" style="font-weight:normal;">Evet</label>
		</p>
		<p>
			<?php echo $city; ?>
			<label for="city">Şehir:</label>
			<select name="city" id="city">
				<option value="Seçiniz" selected="selected">Seçiniz</option>
				<option value="Adana">Adana</option>
				<option value="Adıyaman">Adıyaman</option>
				<option value="Afyon">Afyon</option>
				<option value="Ağrı">Ağrı</option>
				<option value="Amasya">Amasya</option>
				<option value="Ankara">Ankara</option>
				<option value="Antalya">Antalya</option>
				<option value="Artvin">Artvin</option>
				<option value="Aydın">Aydın</option>
				<option value="Balıkesir">Balıkesir</option>
				<option value="Bilecik">Bilecik</option>
				<option value="Bingöl">Bingöl</option>
				<option value="Bitlis">Bitlis</option>
				<option value="Bolu">Bolu</option>
				<option value="Burdur">Burdur</option>
				<option value="Bursa">Bursa</option>
				<option value="Çanakkale">Çanakkale</option>
				<option value="Çankırı">Çankırı</option>
				<option value="Çorum">Çorum</option>
				<option value="Denizli">Denizli</option>
				<option value="Diyarbakır">Diyarbakır</option>
				<option value="Edirne">Edirne</option>
				<option value="Elazığ">Elazığ</option>
				<option value="Erzincan">Erzincan</option>
				<option value="Erzurum">Erzurum</option>
				<option value="Eskişehir">Eskişehir</option>
				<option value="Gaziantep">Gaziantep</option>
				<option value="Giresun">Giresun</option>
				<option value="Gümüşhane">Gümüşhane</option>
				<option value="Hakkari">Hakkari</option>
				<option value="Hatay">Hatay</option>
				<option value="Isparta">Isparta</option>
				<option value="Mersin">Mersin</option>
				<option value="İstanbul">İstanbul</option>
				<option value="İzmir">İzmir</option>
				<option value="Kars">Kars</option>
				<option value="Kastamonu">Kastamonu</option>
				<option value="Kayseri">Kayseri</option>
				<option value="Kırklareli">Kırklareli</option>
				<option value="Kırşehir">Kırşehir</option>
				<option value="Kocaeli">Kocaeli</option>
				<option value="Konya">Konya</option>
				<option value="Kütahya">Kütahya</option>
				<option value="Malatya">Malatya</option>
				<option value="Manisa">Manisa</option>
				<option value="K.Maraş">K.Maraş</option>
				<option value="Mardin">Mardin</option>
				<option value="Muğla">Muğla</option>
				<option value="Muş">Muş</option>
				<option value="Nevşehir">Nevşehir</option>
				<option value="Niğde">Niğde</option>
				<option value="Ordu">Ordu</option>
				<option value="Rize">Rize</option>
				<option value="Sakarya">Sakarya</option>
				<option value="Samsun">Samsun</option>
				<option value="Siirt">Siirt</option>
				<option value="Sinop">Sinop</option>
				<option value="Sivas">Sivas</option>
				<option value="Tekirdağ">Tekirdağ</option>
				<option value="Tokat">Tokat</option>
				<option value="Trabzon">Trabzon</option>
				<option value="Tunceli">Tunceli</option>
				<option value="Şanlıurfa">Şanlıurfa</option>
				<option value="Uşak">Uşak</option>
				<option value="Van">Van</option>
				<option value="Yozgat">Yozgat</option>
				<option value="Zonguldak">Zonguldak</option>
				<option value="Aksaray">Aksaray</option>
				<option value="Bayburt">Bayburt</option>
				<option value="Karaman">Karaman</option>
				<option value="Kırıkkale">Kırıkkale</option>
				<option value="Batman">Batman</option>
				<option value="Şırnak">Şırnak</option>
				<option value="Bartın">Bartın</option>
				<option value="Ardahan">Ardahan</option>
				<option value="Iğdır">Iğdır</option>
				<option value="Yalova">Yalova</option>
				<option value="Karabük">Karabük</option>
				<option value="Kilis">Kilis</option>
				<option value="Osmaniye">Osmaniye</option>
				<option value="Düzce">Düzce</option>
			</select>
		</p>
		<input type="button" name="updatePractice" value="Güncelle" />
	</p>
</form>
</div>

