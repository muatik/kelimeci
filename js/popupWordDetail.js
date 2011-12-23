function showWordOnPopup(word){

	if(!word) return;

	var
		$popup=$('#popupWordDetail');

	if($popup.length>0){
		if(!$popup.is(':hidden'))
			$popup.hide();

		var ajax=new simpleAjax();
		ajax.send(
			'?_ajax=vocabulary/viewword',
			'word='+encodeURI(word),
			{'onSuccess':function(rsp,o){
				
				// If the first letter of word is not "0"
				// that means it is a error
				if(rsp.substr(0,1)!='0'){
					_popupWordDetail.show(rsp);
				}
				// If it is a error
				else{
					// Alert the error
					alert(rsp.substr(1,rsp.length-1));
				}
			}}
		);
	}

}

var _popupWordDetail={};

_popupWordDetail.show=function(html){
	var
		$popup=$('#popupWordDetail');

	if($popup.length>0){
		$popup.html(html)
			.show()
			// center
			.css({
				top:'50%',
				left:'50%',
				margin:'-'+($popup.height() / 2)+'px 0 0 -'+($popup.width() / 2)+'px'
			});
	}
}

$(function(){
	var
		$popup=$('#popupWordDetail');

	$('form#wordSearch img').click(function(){
		var $input=$(this).parent().find('input#word');

		if($input.val()!='')
			showWordOnPopup($input.val());
	});

	showWordOnPopup('dummy1');
});
