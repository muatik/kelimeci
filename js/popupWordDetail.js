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
			'popup=1&word='+encodeURI(word),
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
		$popup
			.find('.content').html(html).end()
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

	// Close button on the popup word detail
	$popup.find('> .close').click(function(){
		$popup.hide();
		return false;
	});

	/**
	 * Bind automatically the a.word that is on the database's word
	 * dictionary on the page.
	 *
	 * This is new automatic elements binding mechanism.
	 * (For more information: http://api.jquery.com/on/)
	 *
	 * How to work:
	 * 	Eg. When wanted to be binded some elements that
	 * 	inserted into the page by ajax system, must be called a function
	 * 	that binds the elements.
	 *
	 * 	Instead of this elements bind mechanism, by the on function of
	 * 	jQuery, this binding mechanism is done automatically.
	 */
	/*
	$popup.find('> .content > .wordDetails').on('click','a.word',function(){
		showWordOnPopup($(this).text());
		return false;
	});
	*/
	
	// Show the word details of selected text via double click
	$(document)
		.on(
			'dblclick',
				// Allowed places to double click
				'.wordDetails .langGroup.langen *,'+
				'.wordDetails div.quotes ul.quotes li *,'+
				'.wordDetails .synonyms a.word,'+
				'.wordDetails .antonyms a.word'
			,
			function(e){
				// Selected text
				var selText=getSelected();

				if(selText!=''){
					showWordOnPopup(selText);
				}
				return false;
			}
		);

	/**
	 * Bind the correction words on the test pages
	 */
	$('.testPage').on('click','.testPageOl .correction a',function(){
		showWordOnPopup($(this).text());
		return false;
	});

	// COMMON WORD SEARCH DOES NOT WORK WITH POPUP - CANCELED
	/*
	// Search form on the banner to the popup word detail
	// on the search button click
	$('form#wordSearch img').click(function(){
		var $input=$(this).parent().find('input#word');

		if($input.val()!='')
			showWordOnPopup($input.val());
	});

	// Search form on the banner to the popup word detail
	// on the keypress ("enter")
	$('form#wordSearch input#word').keyup(function(e){
		// If pressed the key enter
		if($(this).val()!='' && e.keyCode==13)
			showWordOnPopup($input.val());
	});
	*/
});
