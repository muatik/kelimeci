resultOfEmailCheck=null;

$(document).ready(function(){
	// Prepare
	var 
		$f=$('.profileForm'),
		$storedCity=$f.find('input#storedCity'),
		$city=$f.find('select#city');
	
	// When clicked on the calendar icon, show jquery datepicker
	$f.find('img.calendar').click(function(){
		$f.find('input#birthDate').trigger('focus');	
	});

	// Set jquery datepicker for the #birthDate input
	$f.find('input#birthDate').datepicker({
		changeMonth:true,
		changeYear:true
	});

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
			birthDate=$f.find('input#birthDate').val(),
			// Date pattern
			datePatt=/\d{2}\/\d{2}\/\d{4}/,
			// Current form
			$frm=$('.profileForm.personel'),
			infoObj={},
			result=null;
		
		infoObj.fname=fname;
		infoObj.lname=lname;

		// If birth date is valid
		if(datePatt.test(birthDate)){
			var b=birthDate.split('/');
			// Format birth date
			birthDate=b[2]+'-'+b[1]+'-'+b[0];
			// Add birth date to the stack to update
			infoObj.birthDate=birthDate;
		}
		else{
			showFrmAlert($frm,'Doğum tarihini tam olarak şu formata benzer girmelisin: 01/01/1985');
			return;
		}

		updateInformation('personelInfo',infoObj,$frm);

	});

	// Update email
	$f.find('input[name="updateEmail"]').click(function(){
		
		var 
			email=$f.find('input#email').val(),
			// Current form
			$frm=$('.profileForm.email');
		
		// If empty
		if(email=='') return;
		
		// If not valid
		if(!validateEmail(email)){
			showFrmAlert($frm,'Geçerli bir e-posta adresi giriniz!');
			$f.find('input#email').focus();
			return;
		}

		// If the email already in use
		if(!resultOfEmailCheck){
			var alertText='Bu e-posta adresi kullanılıyor. '+
				'Başka bir e-posta adresi seçiniz.'
			showFrmAlert($frm,alertText);
			$f.find('input#email').focus();
			return false;

		}
		
		var 
			infoObj={'email':email},
			result=null;				

		updateInformation('email',infoObj,$frm);

	});

	// Update password
	$f.find('input[name="updatePassword"]').click(function(){
		
		var 
			curPass=$f.find('input#currentPassword').val(),
			newPass=$f.find('input#newPassword').val(),
			newPass2=$f.find('input#newPassword2').val(),
			// Current form
			$frm=$('.profileForm.password');
		
		// If any empty input elements
		if($frm.find(':input[value=""]').length>0){
			showFrmAlert($frm,'Şifre ile ilgili tüm alanları doldurmalısınız!');
			// Focus the first empty input element
			$frm.find(':input[value=""]:first').focus();
			return;
		}

		// The length of the password must be 5
		if(newPass.length<5){
			showFrmAlert($frm,'Şifre en az 5 karakterden oluşmalı!');
			$f.find('input#newPassword');
			return;
		}

		// If the new passwords not the same
		if(newPass!=newPass2){
			showFrmAlert($frm,'Yeni şifre ve Yeni şifre(tekrar) bilgileri aynı değil!');
			$f.find('input#newPassword');
			return;
		}
		
		var 
			infoObj={
				'currentPassword':curPass,
				'newPassword':newPass
			},
			result=null;				

		updateInformation('password',infoObj,$frm);

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
			result=null,
			// Current form
			$frm=$('.profileForm.practice');

		// If checked
		if(isChecked){
			if($city.val()=='0'){
				showFrmAlert($frm,'Pratik yapmak için bir şehir seçmelisiniz!');
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

		updateInformation('practice',infoObj,$frm);

	});

	// If the img checkEmail or checkUsername clicked
	$f.find('input#email').blur(function(){
	
		var 
			$t=$(this),
			val=$t.val(),
			result=null,
			// Current form
			$frm=$('.profileForm.email');

		if(val=='') return;

		if($t.attr('id')=='email'){
			if(!validateEmail(val)){
				alertText='Önce geçerli bir e-posta adresi giriniz!';
				showFrmAlert($frm,alertText);
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
function updateInformation(type,infoObj,$frm){

	// Disable the form button
	$frm.find(':button,:submit').attr('disabled','disabled');
	
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
				showFrmAlert($frm,rsp);
			else
				showFrmAlert($frm,'Güncelleme başarılı.',1);

		}}
	);

	// Enable the form button
	$frm.find(':button,:submit').removeAttr('disabled');

}

