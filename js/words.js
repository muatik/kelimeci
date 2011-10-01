function words(pageId){
	this.layer=$('#'+pageId);
	this.bind();
}

words.prototype.bind=function(){
	var t=this;
	
	$('.word',t.layer).unbind('click').click(function(e){
		var word=$(this).text().replace(',','').replace(' ','');
		t.showWord(word);
		e.preventDefault();
	})

	$('a.more',t.layer).unbind('click').click(function(e){
		var p=$(this).parent();
		var hiddens=$('.hidden',p);
		if(hiddens.get(0)){
			$(hiddens).toggleClass('hidden')
				.toggleClass('exp');
			$(this).html('birazı.');
		}
		else{
			$('.exp',$(this).parent()).toggleClass('exp')
				.toggleClass('hidden');
			$(this).html('hepsi...');
		}
		
		$(this).appendTo(p);
	});
}

words.prototype.showWord=function(word){
	var t=this;
	var ajax=new simpleAjax();
	ajax.send(
		'vocabulary?_view=word&word='+word,
		null,
		{onSuccess:function(rsp){
			var h=$(rsp);
			$(h).hide().insertAfter(t.layer)
			$(t.layer).fadeOut('fast').remove();
			$(h).slideDown('nomal');
		}}
	)
}
