/**
 * Show a word detail on the popup
 *
 * @param string word 
 */
function showWordOnPopup(word){

	if(!word) return;

	if(_popupWordDetail.$elem.length==0)
		_popupWordDetail.$elem=$('#popupWordDetail');

	if(_popupWordDetail.$elem.length>0){

		var $popup=_popupWordDetail.$elem;
		
		// Show AI (ajax indicator)
		toggleAjaxIndicator($popup.find('.content'),'','before');

		// Show the popup
		_popupWordDetail.show();

		var ajax=new simpleAjax();
		ajax.send(
			'?_ajax=vocabulary/viewword',
			'popup=1&word='+encodeURI(word),
			{'onSuccess':function(rsp,o){
				
				// Remove AI
				toggleAjaxIndicator($popup);
				
				// If the first letter of word is not "0"
				// that means it is a error
				if(rsp.substr(0,1)!='0'){
					_popupWordDetail.showContent(rsp);
				}
				// If it is a error
				else{
					// Alert the error
					_popupWordDetail.showContent(rsp.substr(1,rsp.length-1));
				}
			}}
		);
	}

}

/**
 * The object of popup
 */
var _popupWordDetail={};

/**
 * The element of popup word detail div
 */
_popupWordDetail.$elem=$popup=$('#popupWordDetail');

/**
 * Show the popup
 */
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

/**
 * Hide the popup
 */
_popupWordDetail.hide=function(){
	this.$elem.hide();	
}

/**
 * Show the content of popup
 */
_popupWordDetail.showContent=function(html){
	$content=this.$elem.find('.content');

	if($content.length>0){
		$content.html(html);
	}
}

$(function(){

	// If not catched the element of popup, catch it
	if(_popupWordDetail.$elem.length==0)
		_popupWordDetail.$elem=$('#popupWordDetail');
	
	var $elem=_popupWordDetail.$elem;

	// Hide the popup
	$elem.hide();

	// Close button on the popup word detail
	$elem.find('> .close').click(function(){
		_popupWordDetail.hide();
		return false;
	});

	// Show the word details of selected text via double click
	$(document)
		.on(
			'dblclick',
				// Allowed places for double click to select texts
				'.wordDetails .langGroup.langen *,'+
				'.wordDetails div.quotes ul.quotes li *,'+
				'.wordDetails .synonyms a.word,'+
				'.wordDetails .antonyms a.word'
			,
			function(e){
				// Get selected text
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

});
