/**
 * Bind a login form
 *
 * @param object $frm Explicit login form object
 */
function bindLoginForm($frm){
	
	var $f=$frm;
	
	$f.submit(function(){
		var
			username=$f.find('input[name="username"]').val(),
			password=$f.find('input[name="password"]').val();

		// If there is any empty input elements
		if($f.find(':input[value=""]').length>0){

			var alertText='Kullanıcı adını ve şifreyi giriniz!';
			showFrmAlert($f,alertText);
			// Focus the first empty input element
			$f.find(':input[value=""]:first').focus();
			return false;

		}

		var ajax=new simpleAjax();
		ajax.send(
			'?_ajax=users/login',
			'username='+encodeURI(username)+'&'+
				'password='+encodeURI(password),
			{'onSuccess':function(rsp,o){
				
				// Login okay
				if(rsp=='1'){
					window.location.href='/vocabulary';
				}
				else{
					// Alert the error
					showFrmAlert($f,rsp);
				}
				return false;

			}}
		);
		return false;
	});


}

$(document).ready(function(){
	/* TOOLTIP EXAMPLE */
	/*
	var hideLink='<a href="#" class="qtipHide" onclick="return hideTooltip(this);">[Gizle]</a>';

	var t;

	t={
		target:'.loginForm :input[name=username]',
		options:{
			id:'qt1',
			show:{event:false,ready:true},
			hide:false,
			content:'Kullanıcı adı en az 3 karakter olmalıdır. '+hideLink,
			position:{
				my:'left center',
				at:'right center'
			},
			style:{
				classes:'ui-tooltip-youtube ui-tooltip-shadow'
			},
			events:{
				hide:function(event,api){

					var qtipId=$(this).attr('id').split('-');

					// Get the id of tooltip
					qtipId=qtipId[qtipId.length-1];

					$.ajax({
						type:'POST',
						url:'?_ajax=genel/tooltip',
						data:'id='+qtipId+'&op=hide'
					});
				}
			}
		}
	};

	$(t.target).qtip(t.options);
	*/
	/* TOOLTIP EXAMPLE */
});
