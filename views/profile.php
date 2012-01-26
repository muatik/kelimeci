<link rel="stylesheet" type="text/css" href="../css/profile.css" />
<script type="text/javascript" src="../js/profile.js"></script>

<?php
	$r=$this->r;

	// If the user's just registered and redirected here
	if(isset($r['newUser']) && $r['newUser']=='1'){
		
		$o2=new stdClass();
		$o2->title='Duyuru';
		$o2->message='Kullanıcı bilgilerinizi güncelleyebilirsiniz.';
		echo $this->loadElement('notification.php',$o2);

	}
?>
<div class="profilePage">

<form class="frm profileForm personel" method="post" action="">
	<h4 class="frmTitle">Kişisel Bilgilerini Güncelle</h4>
	<ul class="frmItems">
		<li>
			<div class="fLabel">
				<label for="userName">Kullanıcı adı:</label>
			</div>
			<div class="fInput">
				<?php echo $o->username; ?>
			</div>
		</li>
		<li>
			<div class="fLabel">
				<label for="fname">Ad:</label>
			</div>
			<div class="fInput">
				<input type="text" name="fname" id="fname" maxlength="50" value="<?php echo $o->fname; ?>" /div>
		</li>
		<li>
			<div class="fLabel">
				<label for="lname">Soyad:</label>
			</div>
			<div class="fInput">
				<input type="text" name="lname" id="lname" maxlength="50" value="<?php echo $o->lname; ?>" />
			</div>
		</li>
		<li>
			<div class="fLabel">
				<label for="birthDate">Doğum tarihi:</label>
			</div>
			<div class="fInput">
				<input type="text" name="birthDate" id="birthDate" maxlength="20" 
					value="<?php if($o->birthDate) echo date('d/m/Y',strtotime($o->birthDate)); ?>" />
				<img src="images/calendar.png" alt="" class="calendar" />
			</div>
		</li>
		<li>
			<div class="fInput">
				<input type="button" name="updatePersonelInfo" value="Güncelle" />
			</div>
		</li>
	</ul>
</form>

<form class="frm profileForm password" method="post" action="">
	<h4 class="frmTitle">Şifreni Güncelle</h4>
	<ul class="frmItems">
		<li>
			<div class="fLabel">
				<label for="password">Mevcut şifre:</label>
			</div>
			<div class="fInput">
				<input type="password" name="currentPassword" id="currentPassword" maxlength="50" />
			</div>
		</li>
		<li>
			<div class="fLabel">
				<label for="newPassword">Yeni şifre:</label>
			</div>
			<div class="fInput">
				<input type="password" name="newPassword" id="newPassword" maxlength="50" />
			</div>
		</li>
		<li>
			<div class="fLabel">
				<label for="newPassword2">Yeni şifre(tekrar):</label>
			</div>
			<div class="fInput">
				<input type="password" name="newPassword2" id="newPassword2" maxlength="50" />
			</div>
		</li>
		<li>
			<div class="fInput">
				<input type="button" name="updatePassword" value="Güncelle" />
			</div>
		</li>
	</ul>
</form>

<form class="frm profileForm email" method="post" action="">
	<h4 class="frmTitle">E-posta Adresini Güncelle</h4>
	<ul class="frmItems">
		<li>
			<div class="fLabel">
				<label for="email">E-posta adresi:</label>
			</div>
			<div class="fInput">
				<input type="hidden" id="usrEmailOnProfilePage" value="<?php echo $o->email; ?>" />
				<input type="text" name="email" id="email" maxlength="50" value="<?php echo $o->email; ?>" />
			</div>
		</li>
		<li>
			<div class="fInput">
				<input type="button" name="updateEmail" value="Güncelle" />
			</div>
		</li>
	</ul>
</form>

<form class="frm profileForm practice" method="post" action="">
	<h4 class="frmTitle">Pratik</h4>
	<ul class="frmItems">
		<li>
			<div class="fLabel" style="width:130px;">
				<label for="practice">Pratik yapmak ister misin:</label>
			</div>
			<div class="fInput" style="width:210px;">
				<?php
					$city='';
					if($o->practice=='1'){
						echo '<input type="checkbox" name="practiceYes" 
							id="practiceYes" value="1" checked="checked" />';

						// On page load, set selected city with this input or
						// disable the city select box
						if($o->city)
							$city='<input type="hidden" name="storedCity" id="storedCity" value="'.$o->city.'" />';	
					}
					else{
						echo '<input type="checkbox" name="practiceYes" 
							id="practiceYes" value="1" />';
					}
				?>
				<label for="practiceYes" style="font-weight:normal;">Evet</label>
			</div>
		</li>
		<li>
			<div class="fLabel">
				<label for="city">Şehir:</label>
			</div>
			<div class="fInput">
				<?php echo $city; ?>
				<select name="city" id="city">
					<option value="0" selected="selected">Seçiniz</option>
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
			</div>
		</li>
		<li>
			<div class="fInput">
				<input type="button" name="updatePractice" value="Güncelle" />
			</div>
		</li>
	</ul>
</form>

</div>

