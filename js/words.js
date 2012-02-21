/**
 * bir kelimeye ait tüm bilgilerin gösterildiği katman için
 * yazılmıştır.
 * Her katman sayfası, bu words sınıfından bir nesne türetir.
 * */
function words(pageId){
	this.layer=$('#'+pageId);
	this.word=$('input[name="word"]',this.layer).val();
	this.bind();
	this.removeCallback;
	this.addCallback;
	this.showCallback;
}

words.prototype.bind=function(){
	var t=this;

	$('.word',t.layer).unbind('click').click(function(e){
		var word=$(this).text().replace(',','').trim();
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
	

	/**
	 * when add/remove button is clicked, adds or removes the word from vocabulary
	 * */
	$('a.addRemove',t.layer).click(function(){
		
		toggleAjaxIndicator($('a.addRemove',t.layer),'','after');

		if($(this).hasClass('del')){
			t.remove(t.word);
			$(this).removeClass('del gray')
			.addClass('add green')
			.attr('title','Kelimeyi kelime dağarcığından çıkartır.')
			.html('Sözlüğüne ekle');
		}
		else{
			t.add(t.word,null);
			$(this).removeClass('add geen')
			.addClass('del gray')
			.attr('title','Kelimeyi kelime dağarcığınıza ekler.')
			.html('Sözlüğünden çıkart');
		}
		
		return false;
	});

}

/**
 * adds the word into vocabulary
 * */
words.prototype.add=function(word,tags){
	var t=this;
	vocabulary.add(word,tags,function(){

		if(words.addCallback)
			words.addCallback(word,tags);

		toggleAjaxIndicator(t.layer);
	})
}

/**
 * removes the showing word from vocabulary
 * */
words.prototype.remove=function(word){
	var t=this;
	vocabulary.rmWord([this.word],function(){
		
		if(words.removeCallback)
			words.removeCallback(word);
		
		toggleAjaxIndicator(t.layer);
	});
}

words.prototype.showWord=function(word){
	var t=this;
	var ajax=new simpleAjax();

	var indc=toggleAjaxIndicator(
		$(t.layer).html(''),
		'"'+word+'" yükleniyor... <a href="#" class="abort">iptal et</a>',
		'before',
		'wordShowingIndc'
	);
	
	$('a.abort',indc).click(function(){		
		ajax.o.abort();
		$(indc).remove();
	});

	ajax.send(
		'vocabulary?_view=word&word='+word+'&noScriptStyle=1',
		null,
		{onSuccess:function(rsp){

			$(indc).remove();
			var h=$(rsp);
			$(h).hide().insertAfter(t.layer)
			$(t.layer).fadeOut('fast').remove();
			

			if(words.showCallback)
				words.showCallback(word);
			

			/**
			 * Temprory solution
			 */
			if(typeof vcbp!=='undefined'){
				$(window).trigger('resize');
				$(h).slideDown('fast',function(){
					vcbp.setSclBar('.detailSide .wordDetails','i');	
				});
			}
			else	
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
