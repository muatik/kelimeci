$(function(){
	$('.wordList .words li .categories').qtip({
		show:{solo:true},
		content:{
			text:
			'<ul class="wordCategories">'+
				'<li><abbr class="v active">f</abbr> - <span>Fiil</span></li>'+
				'<li><abbr class="n active">i</abbr> - <span>İsim</span></li>'+
				'<li><abbr class="aj active">s</abbr> - <span>Sıfat</span></li>'+
				'<li><abbr class="av active">z</abbr> - <span>Zarf</span></li>'+
				'<li><abbr class="pp active">e</abbr> - <span>Edat</span></li>'+
			'</ul>'
		},
		position:{
			my:'left center',
			at:'right center'
		},
		style:{
			classes:'ui-tooltip-youtube'
		}

	});

	/*
	// Show a tooltip for the abbr. of categories by using their title attr.
	$('.wordList .words li .categories abbr').qtip({
		show:{
			solo:true
		},
		style:{
			classes:'ui-tooltip-youtube'
		}
	});
	*/

});
