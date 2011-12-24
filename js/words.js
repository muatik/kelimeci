/**
 * bir kelimeye ait tüm bilgilerin gösterildiği katman için
 * yazılmıştır.
 * Her katman sayfası, bu words sınıfından bir nesne türetir.
 * */
function words(pageId){
	this.layer=$('#'+pageId);
	this.word=$('input[name="word"]',this.layer).val();
	this.bind();
}

words.prototype.bind=function(){
	var t=this;

	$('.word',t.layer).unbind('click').click(function(e){
		var word=$(this).text().replace(',','').replace(' ','');
		//var word=$(this).text();
		t.showWord(word);
		e.preventDefault();
	})

	// Eğer eş ve zıt anlamı bağlantılı kelime "," içeriyorsa onları temizle
	$('.synonyms span a:last,.antonyms span a:last',t.layer).each(function(){
		var text=$(this).text();

		if(text.substr(text.length-1,1)==','){
			$(this).text(text.substr(0,text.length-1));
		}
	});


	/**
	 * etimoloji bilgisi varsa, hepsi yerine sadece bir kısmı
	 * gösterilir yapılacak.
	 * */
	$('div.etymology',t.layer).each(function(){
		var text=$(this).text();
		if(text.length>170)
			$(this).html(text.substr(0,170)
			+'<span class="hidden">'+text.substr(170)+'</span>'
			+'<a href="#" class="action more">hepsi...</a>');
	});


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
		
		if(!$(this).hasClass('dontMove'))
			$(this).appendTo(p);
		
		e.preventDefault();
	});

	$('.quotes a.add',t.layer).click(function(e){
		if($(this).text()=='Alıntı ekle'){
			$(this).text('kapat');
			$('.addForm',t.layer).slideDown('fast');
			$('.quotes .addForm input',t.layer)
				.focus();
		}
		else{
			$(this).text('Alıntı ekle');
			$('.addForm',t.layer).slideUp('fast');
		}
		e.preventDefault();	
	});

	$('.quotes .addForm button',t.layer).click(function(e){
		var quote=$('.quotes .addForm input').val();
		if(quote.replace(' ','').length<2){
			alert('Alıntı çok kısa');
			return false;
		}
		
		var button=this;
		$(this).html('Ekleniyor...');
		$(this).attr('disabled','disabled');

		vocabulary.addQuote(
			t.word,quote,
			function(rsp){
				t.showAddedQuote(quote,rsp);
				$(button).html('Ekle');
				$(button).removeAttr('disabled');
			}
		);

		e.preventDefault();
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
			$(h).slideDown('fast');
		}}
	)
}

words.prototype.showAddedQuote=function(quote,rsp){
	var t=this;
	if(rsp==1){

		$('.quotes .addForm input[type="text"]').val('');

		var i='<li class="user"><blockquote class="text">'
			+quote+'</blockquote></li>';

		$(i).appendTo('ul.quotes',t.layer)
			.css('background-color','#fffa00')
			.animate({backgroundColor:'#fff'},1800);
	}
	else{
		$('<p class="error">Alıntı eklenemedi. '+rsp+'</p>')
			.insertBefore('.quotes .addForm')
			.delay(2200).hide('fast',function(){
				$(this).remove();
			});
	}
}
