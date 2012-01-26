function showWordOnPopup(word){

	if(!word) return;

	if(_popupWordDetail.$elem.length==0)
		_popupWordDetail.$elem=$('#popupWordDetail');

	if(_popupWordDetail.$elem.length>0){
		/*
		if(!$popup.is(':hidden'))
			$popup.hide();
		*/

		_popupWordDetail.setAI('s');
		_popupWordDetail.show();

		var ajax=new simpleAjax();
		ajax.send(
			'?_ajax=vocabulary/viewword',
			'popup=1&word='+encodeURI(word),
			{'onSuccess':function(rsp,o){
				
				_popupWordDetail.setAI('h');
				
				// If the first letter of word is not "0"
				// that means it is a error
				if(rsp.substr(0,1)!='0'){
					_popupWordDetail.showContent(rsp);
				}
				// If it is a error
				else{
					// Alert the error
					//alert(rsp.substr(1,rsp.length-1));
					_popupWordDetail.showContent(rsp.substr(1,rsp.length-1));
				}
			}}
		);
	}

}

var _popupWordDetail={};

_popupWordDetail.$elem=$popup=$('#popupWordDetail');

_popupWordDetail.show=function(){
	var
		$popup=this.$elem;

	if($popup.length>0){
		$popup
			.find('.content').html('').end()
			// center
			.css({
				top:'50%',
				left:'50%',
				margin:'-'+($popup.height() / 2)+'px 0 0 -'+($popup.width() / 2)+'px'
			})
			.show();
	}
}

_popupWordDetail.hide=function(){
	this.$elem.hide();	
}

_popupWordDetail.showContent=function(html){
	$content=this.$elem.find('.content');

	if($content.length>0){
		$content.html(html);
	}
}

/**
 * Set AI(Ajax Indicator)
 *
 * @param string status ('s' || 'h')
 * 	default 'h'
 */
_popupWordDetail.setAI=function(status){
	var
		$ai=this.$elem.find('.ai');
	
	if($ai.length>0){
		// Show AI
		if(status=='s')
			$ai.show();
		// Hide AI
		else
			$ai.hide();
	}
	else
		console.log('No ajax indicator in the popup word detail!');
}

$(function(){

	if(_popupWordDetail.$elem.length==0)
		_popupWordDetail.$elem=$('#popupWordDetail');
	
	var $elem=_popupWordDetail.$elem;

	// Hide
	$elem.hide();

	// Close button on the popup word detail
	$elem.find('> .close').click(function(){
		_popupWordDetail.hide();
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
	$elem.find('> .content > .wordDetails').on('click','a.word',function(){
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
