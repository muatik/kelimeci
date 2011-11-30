resultOfEmailCheck=null;

$(document).ready(function(){
	// Prepare
	var 
		$f=$('.profileForm'),
		$storedCity=$f.find('input#storedCity'),
		$city=$f.find('select#city'),
		// Alert elements for forms
		$alertPersonel=$('.profileForm.personel'),
		$alertEmail=$('.profileForm.email'),
		$alertPassword=$('.profileForm.password'),
		$alertPractice=$('.profileForm.practice');
	
	// Add the alert elements to the form
	$alertPersonel=$alertPersonel.append(createFrmAlert()).find('p.frmAlert');
	$alertEmail=$alertEmail.append(createFrmAlert()).find('p.frmAlert');
	$alertPassword=$alertPassword.append(createFrmAlert()).find('p.frmAlert');
	$alertPractice=$alertPractice.append(createFrmAlert()).find('p.frmAlert');

	
	// When clicked on the calendar icon, show jquery datepicker
	$f.find('img.calendar').click(function(){
		$f.find('input#birthDate').trigger('focus');	
	});

	// Set jquery datepicker for the #birthDate input
	$f.find('input#birthDate').datepicker();

	// If there is storedCity
	if($storedCity.length>0 && $storedCity.val()!=''){
		// Select user's city
		$city.val($storedCity.val());
	}
	else{	
		// If no city selected, select İstanbul as default
		$city.val('İstanbul');
		// Disable the city select box
		$city.attr('disabled','disabled');
	}

	// Update personel information
	$f.find('input[name="updatePersonelInfo"]').click(function(){
		
		var 
			fname=$f.find('input#fname').val(),
			lname=$f.find('input#lname').val(),
			birthDate=$f.find('input#birthDate').val();

		var 
			infoObj={
				'fname':fname,
				'lname':lname,
				'birthDate':birthDate
			},
			result=null;				

		updateInformation('personelInfo',infoObj,$alertPersonel);

	});

	// Update email
	$f.find('input[name="updateEmail"]').click(function(){
		
		var email=$f.find('input#email').val();
		
		// If empty
		if(email=='') return;
		
		// If not valid
		if(!validateEmail(email)){
			showFrmAlert($alertEmail,'Geçerli bir e-posta adresi giriniz!');
			$f.find('input#email').focus();
			return;
		}

		// If the email already in use
		if(!resultOfEmailCheck){
			var alertText='Bu e-posta adresi kullanılıyor. '+
				'Başka bir e-posta adresi seçiniz.'
			showFrmAlert($alertEmail,alertText);
			$f.find('input#email').focus();
			return false;

		}
		
		var 
			infoObj={'email':email},
			result=null;				

		updateInformation('email',infoObj,$alertEmail);

	});

	// Update password
	$f.find('input[name="updatePassword"]').click(function(){
		
		var 
			curPass=$f.find('input#currentPassword').val(),
			newPass=$f.find('input#newPassword').val(),
			newPass2=$f.find('input#newPassword2').val();
		
		// If empty
		if(curPass=='' || newPass=='' || newPass2==''){
			showFrmAlert($alertPassword,'Şifre ile ilgili tüm alanları doldurmalısınız!');
			$f.find('input#currentPassword');
			return;
		}

		// The length of the password must be 5
		if(newPass.length<5){
			showFrmAlert($alertPassword,'Şifre en az 5 karakterden oluşmalı!');
			$f.find('input#newPassword');
			return;
		}

		// If the new passwords not the same
		if(newPass!=newPass2){
			showFrmAlert($alertPassword,'Yeni şifre ve Yeni şifre(tekrar) bilgileri aynı değil!');
			$f.find('input#newPassword');
			return;
		}
		
		var 
			infoObj={
				'currentPassword':curPass,
				'newPassword':newPass
			},
			result=null;				

		updateInformation('password',infoObj,$alertPassword);

	});

	
	// On change for practice checkbox
	$f.find('input#practiceYes').change(function(){
		var
			$t=$(this),
			// Is checkbox checked
			isChecked=$t.is(':checked'),
			// the <p> cityForPractice
			$city=$f.find('select#city');

		if(isChecked){
			$city.removeAttr('disabled');
		}
		else{
			$city.attr('disabled','disabled');
		}	
	});

	// Update practice information
	$f.find('input[name="updatePractice"]').click(function(){
		
		var 
			// Is checkbox checked
			isChecked=$f.find('input#practiceYes').is(':checked'),
			practice='',
			$city=$f.find('select#city'),
			infoObj={},
			result=null;

		// If checked
		if(isChecked){
			if($city.val()=='0'){
				showFrmAlert($alertPractice,'Pratik yapmak için bir şehir seçmelisiniz!');
				$city.focus();
				return;
			}
			practice='1';
			infoObj.city=$city.val();
		}
		else{
			practice='0';
			infoObj.city='';
		}
		
		infoObj.practice=practice;

		updateInformation('practice',infoObj,$alertPractice);

	});

	// If the img checkEmail or checkUsername clicked
	$f.find('input#email').blur(function(){
	
		var 
			$t=$(this),
			val=$t.val(),
			result;

		if(val=='') return;

		if($t.attr('id')=='email'){
			if(!validateEmail(val)){
				alertText='Önce geçerli bir e-posta adresi giriniz!';
				showFrmAlert($alertEmail,alertText);
				$f.find('input#email').focus();
				return;
			}
		}
		checkUsability($t);
	});

	// If the email focused
	$f.find('input#email').focus(function(){

		var 
			$t=$(this),
			val=$t.val(),
			img=$t.parent().find('img'),
			label='';
		
		if(val=='') return;
		
		// If has img
		if(img.length>0)
			img.remove();	

	});


});

// Validate the email
function validateEmail(data){
	var patt=new RegExp("^[a-zA-Z0-9_\\-.]*@[a-zA-Z0-9_\\-]+.[a-zA-Z]{3,4}.*[a-zA-Z]*$","g"); 
	if(patt.test(data)) return true;
	else false;
}

// Check the usability of the email
function checkUsability(inputElem){

	var
		ajax=new simpleAjax(),
		$elem=$(inputElem),
		id=$elem.attr('id'),
		val=$elem.val(),
		_ajax='?_ajax=users/checkEmail',
		param='email='+encodeURI(val),
		resultVar=null;

	ajax.send(
		_ajax,
		param,
		{'onSuccess':function(rsp,o){
			// Okay
			if(rsp=='1'){
				resultOfEmailCheck=true;

				$elem.parent().find('img').remove().end()
					.append('<img src="../images/correct.png" alt="Uygun" title="Uygun" />');

			}
			else{
				resultOfEmailCheck=false;

				$elem.parent().find('img').remove().end()
					.append('<img src="../images/incorrect.png" alt="Uygun değil!" title="Uygun değil!" />');
			}

		}}
	);


}

// Update information according to type(such email, password)
function updateInformation(type,infoObj,$frmAlert){
	
	var
		data='',
		ajax=null;

	for(var i in infoObj)
		data+=i+'='+encodeURI(infoObj[i])+'&';

	data=data.substring(0,data.length-1);
	
	ajax=new simpleAjax();
	ajax.send(
		'?_ajax=users/update',
		'type='+type+'&'+
			data,
		{'onSuccess':function(rsp,o){

			if(rsp!='1')
				showFrmAlert($frmAlert,rsp);
			else
				showFrmAlert($frmAlert,'Güncelleme başarılı.',1);

		}}
	);

}

