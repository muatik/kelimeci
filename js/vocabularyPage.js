function vcbPage(){
	this.list=$('.ul');
	this.bind();
	this.bindList();
	var t=this;
	wordAdditionForm.onAdd=function(rsp,f){
		t.onAddedWord(rsp,f);
	}

	// Variable to cancel the ajax requests that made before
	this.wordDetailAjaxReq=new simpleAjax();
}

var vcbp=vcbPage.prototype;

vcbp.bind=function(){
	var t=this;

	$('.toggleInsertForm').click(function(){
		$('.wordAdditionForm').toggle('fast');
	})

	$('.toggleFilterForm').click(function(){
		$('.wordFilterForm').toggle('fast');
	})

	
	$('.classesCheckList').dropdownchecklist(
	{firstItemChecksAll: true},
	{
		forceMultiple: true, onComplete: function(selector) {
			var values = "";
			for( i=0; i < selector.options.length; i++ ) {
				if (selector.options[i].selected 
					&& (selector.options[i].value != "")) {
					if ( values != "" ) values += ",";
					values += selector.options[i].value;
				}
			}

			t.getWords();
		} 
	}
	);


	$(".levelRange").slider({
		range: true,
		min: -20,
		max: 20,
		values: [ -20, 15 ],
		slide: function( event, ui ) {
			$(".levelRangeInput").val(
				ui.values[0]+':'+ui.values[1]
			);
		},
		change:function(event,ul){
			t.getWords();
		}
	});

	$('.levelRangeInput').val(
		$('.levelRange').slider('values',0)
		+':'+
		$('.levelRange').slider('values',1)
	)

	$('.wordFilterForm .keyword').keyup(function(){
		t.getWords();
	})

	$('.wordFilterForm .orderBy').change(function(){
		t.getWords();
	})

}

vcbp.getWords=function(){
	var t=this;
	var classes=new Array()
	try{
		classes=$('.classesCheckList').val().toString().split(',');
		if(classes[0]=='Hepsi')
			classes.shift();
	}catch(e){}
	var levelRange=$('.levelRangeInput').val().split(':');
	var keyword=$('.wordFilterForm .keyword').val();
	var orderBy=$('.wordFilterForm .orderBy option:selected').val();
	
	vocabulary.get({
			levelMax:levelRange[1],
			levelMin:levelRange[0],
			keyword:keyword,
			classes:classes,
			orderBy:orderBy
		}, function(rsp){
			t.listWords(rsp);
		}
	);

}

vcbp.listWords=function(words){
	words=$(words)[2];
	words=$(words).html();

	$('.wordList').replaceWith(
		'<div class="wordList">'+words+'</div>'
	);
	
	this.bindList();
}

vcbp.bindList=function(){
	var t=this;
	$('ul.words span.word').click(function(){
		t.showDetail($(this).text());
	})
}

vcbp.showDetail=function(word){
	// Cancel old ajax requests
	this.wordDetailAjaxReq.o.abort();

	this.wordDetailAjaxReq.send(
		'vocabulary?_view=word&word='+word+'&noScriptStyle=1',
		null,
		{onSuccess:function(rsp,o){
			$('.detailSide').html(rsp)
				.hide().toggle('slide',{},450);
		}}
	);
}

vcbp.onAddedWord=function(rsp,f){
	
	$('input[name="word"]').focus();

	if(rsp.substr(0,1)==0){
		alert(rsp.substr(1));
		return false;
	}

	$('input[name="word"]').val('');

	var word=jQuery.parseJSON(rsp);
	var classList=['verb','noun','adjective','adverb','preposition'];
	var abbr=['v','n','aj','av','pp'];
	
	var h='<span class="categories">';
	for(var i in classList){

		var cssClass='';
		for(var k in word.classes)
			if(classList[i]==word.classes[k]){
				cssClass='active';
				break;
			}

		h+='<span class="'+abbr[i]+' '+cssClass+'">'+abbr[i]+'</span>'
	}
	h+='</span> ';

	h+='<span class="level">'+word.level+'</span> ';
	h+='<span class="word">'+word.word+'</span> ';
	
	$('<li>'+h+'</li>').prependTo('ul.words').find('span.word')
		.css('background-color','#fffa00')
		.animate({backgroundColor:'#fff'},1800,function(){
			$(this).css('background-color','');
		});

	this.bindList();
}

