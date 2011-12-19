$(function(){

	// Show a tooltip for the abbr. of categories by using their title attr.
	$('.wordList .words li .categories abbr').qtip({
		show:{
			solo:true
		},
		style:{
			classes:'ui-tooltip-youtube'
		}
	});

});
