$(function(){
	// Scroll to fixed for vcbForms
	$('#vcbContainer .listSide .vcbForms').scrollToFixed();
	
	// Scroll to fixed for detailSide
	$('#vcbContainer .detailSide').scrollToFixed({
		preFixed:function(){
			$(this).css('margin-left','0px');
		},
		postFixed:function(){
			$(this).css('margin-left','20px');
		}
	});

	// Infinite-scrolling for word list
	$('.wordList ul.words').infinitescroll({
		navSelector:'.wordList div.wordListNav',
		nextSelector:'.wordList div.wordListNav a:first',
		itemSelector:'li',
		//contentSelector:'.wordList ul.words',
		debug:true,
		dataType:'html',
		extraScrollPx:200,
		loading:{
			finishedMsg:'<em>Tüm kelimeler yüklendi.</em>',
			img:'../images/loading.gif',
			msgText:'<em>Kelimeler yükleniyor...</em>',
			speed:'slow',
			class:'infSclIndicator'
		},
		pathParse:function(){
			return ['?_ajax=vocabulary/viewwordList',''];
		},
		setDestUrl:vcbp.getInfSclReqUrl,
	});
	
});

function vcbPage(){
	this.list=$('.ul');
	this.bind();
	this.bindList();
	var t=this;

	wordAdditionForm.onAdd=function(rsp,f){
		t.onAddedWord(rsp,f);
	}

	// Variable to cancel the ajax requests that was made before
	this.wordDetailAjaxReq=new simpleAjax();


	// Close the filter form on the page load
	$('.wordFilterForm').hide();

	$('.selectPackages').click(function(){	
		$('form.wordPackages').toggle('fast');
	});

}

var vcbp=vcbPage.prototype;

/**
 * Return a object of the parameters that is used
 * with the url "?_ajax=vocabulary/viewwordList" for ajax requests
 *
 * @return object
 */
vcbp.getRequestParams=function(){
	var classes=new Array()
	try{
		classes=$('.classesCheckList').val().toString().split(',');
		if(classes[0]=='Hepsi')
			classes.shift();
	}catch(e){}
	var levelRange=$('.levelRangeInput').val().split(':');
	var keyword=$('.wordFilterForm .keyword').val();
	var orderBy=$('.wordFilterForm .orderBy option:selected').val();

	return {
		levelMax:levelRange[1],
		levelMin:levelRange[0],
		keyword:keyword,
		classes:classes,
		orderBy:orderBy
	};
}

/**
 * Update the href information of anchor for infinite scrolling
 */
vcbp.getInfSclReqUrl=function(){
	var 
		url='?_ajax=vocabulary/viewwordList',
		start=$('.wordList ul.words li').length,
		length=5,
		reqParams=vcbp.getRequestParams(),
		params='&';

	for(var i in reqParams){
		if(!$.isArray(reqParams[i]))
			params+=i+'='+reqParams[i]+'&';
		else
			for(var j in reqParams[i])
				params+=i+'[]='+reqParams[i][j]+'&';
	}

	params+=
		'start='+start+
		'&length='+length+
		'&noScriptStyle=true'+
		'&noAllInterface=true';

	// Update the anchor href information for infinite scrolling
	//$('.wordList div.wordListNav a:first').attr('href',url+params);
	return url+params;
}

vcbp.bind=function(){
	var t=this;
	
	// when user submit word package form, refresh word list
	wordPackages.onSaveCallback=function(){
		t.onWordPackageSaved();
	}
	
	// when a showing word is removed, remove it in list too 
	words.removeCallback=function(word){
		t.showingWordRemoved(word);
	}
	
	words.addCallback=function(word,tags){
		t.showingWordAdded(word,tags);
	}

	$('.toggleInsertForm').click(function(){
		$('.wordAdditionForm').toggle('fast');
		return false;
	})

	$('.toggleFilterForm').click(function(){
		$('.wordFilterForm').toggle('fast');
		return false;
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

	t.showTooltips();

	$('form.wordAdditionForm').submit(function(){
		toggleAjaxIndicator($('.toggleFilterForm'),'','after');
	})

}

vcbp.getWords=function(){
	var t=this;

	var reqParams=t.getRequestParams();
	
	toggleAjaxIndicator($('.toggleFilterForm'),'','after');

	vocabulary.get(
		reqParams,
		function(rsp){
			toggleAjaxIndicator($('.toggleFilterForm').parent());
			t.listWords(rsp);
		}
	);

}

vcbp.listWords=function(words){
	words=$(words).html();

	$('.wordList').replaceWith(
		'<div class="wordList">'+words+'</div>'
	);
	
	this.bindList();
}

vcbp.bindList=function(){
	var t=this;

	var toggleRmButton=function(){
		if($('input.wordIds:checked').get(0))
			$('.wordsForm button').show();
		else
			$('.wordsForm button').hide();
	}

	$('.wordsForm button').unbind('click').bind('click',function(){
		t.rmWords();
	});

	$('.wordsForm input[name="checkAll"]').unbind('change').bind('change',function(){

		var wIds=$('ul.words input.wordIds');

			wIds.attr('checked',null)
			.each(function(){
				$(this).parent().removeClass('selected');
			});

		if($(this).attr('checked'))
			wIds.attr('checked','checked')
			.each(function(){
				$(this).parent().toggleClass('selected');
			});

		toggleRmButton();
	});

	$('ul.words').on('change','li input.wordIds',function(){
		$(this).parent().toggleClass('selected');
		$('.wordsForm input[name="checkAll"]').attr('checked',null);
		toggleRmButton();
	});

	$('ul.words').on('click','li span.word',function(){
		t.showDetail($(this).text());
	});
}

vcbp.showDetail=function(word){
	var t=this;
	// Cancel old ajax requests
	this.wordDetailAjaxReq.o.abort();

	var indc=toggleAjaxIndicator(
		$('.detailSide').html(''),
		'"'+word+'" yükleniyor... <a href="#" class="abort">iptal et</a>',
		'prepend',
		'wordShowingIndc'
	);
	
	$('a.abort',indc).click(function(){		
		t.wordDetailAjaxReq.o.abort();
		$(indc).remove();
	});

	this.wordDetailAjaxReq.send(
		'vocabulary?_view=word&word='+word+'&noScriptStyle=1',
		null,
		{onSuccess:function(rsp,o){
			
			$(indc).remove();
			$('.detailSide').html(rsp)
				.hide().toggle('slide',{},450);
		}}
	);

}

vcbp.rmWords=function(){

	toggleAjaxIndicator($('div.wordsForm button'),'','after');

	var selWords=new Array();
	var selected=$('ul.words input.wordIds:checked');

	selected.each(function(){
		selWords.push(
			$('span.word',$(this).parent()).text()
		);
	});
	
	vocabulary.rmWord(selWords,function(r){
		selected.each(function(){
			$(this).parent().remove();
			toggleAjaxIndicator($('div.wordsForm'));
		});
	})
	
}


vcbp.showingWordAdded=function(word,tags){
	$('.wordList .words li span.word').filter(function(){
		return $(this).text()==word
	}).removeClass('removed')
	.css('background-color','#fffa00')
	.animate({backgroundColor:'#fff'},1800,function(){
			$(this).css('background-color','');
	});
}

vcbp.showingWordRemoved=function(word){
	$('.wordList .words li span.word').filter(function(){
		return $(this).text()==word
	}).addClass('removed')
	.css('background-color','#FF9986')
	.animate({backgroundColor:'#fff'},1400,function(){
		$(this).css('background-color','');
	});
}

vcbp.onAddedWord=function(rsp,f){
	
	toggleAjaxIndicator($('.toggleFilterForm').parent());

	$('input[name="word"]').focus();

	if(rsp.substr(0,1)==0){
		alert(rsp.substr(1));
		return false;
	}

	$('input[name="word"]').val('');

	var word=jQuery.parseJSON(rsp);
	var classList=['verb','noun','adjective','adverb','preposition'];
	var abbr=['v','n','aj','av','pp'];
	var abbrTr=['f','i','s','z','e'];
	var h='';

	h='<input type="checkbox" class="wordIds" name="ids[]" value="'+word.id+'" />';
	
	h+='<span class="clsBoxes">';
	for(var i in classList){

		var cssClass='';
		for(var k in word.classes)
			if(classList[i]==word.classes[k]){
				cssClass='active';
				break;
			}

		h+='<abbr class="'+abbr[i]+' '+cssClass+'">'+abbrTr[i]+'</abbr>'
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
	this.showDetail(word.word);
}


vcbp.onWordPackageSaved=function(rsp){
	$('form.wordPackages').toggle('normal');
	this.getWords();
}

vcbp.showTooltips=function(){
	/*
	var qtipHideBtn='<a href="#" class="qtipHide" onclick="hideTooltip(this)">[Gizle]</a>';

	// Tooltip for word addition form
	$wFrm.find(':input[name="word"]').qtip(qtipDefs).qtip(
		'option','content.text',
		'Kelime dağarcığınıza buradan kelime ekleyebilirsiniz. '+qtipHideBtn
	);
	*/
	$('.wordAdditionForm :input[name="word"]').qtip({
		show:{
			event:false,
			ready:true
		},
		hide:false,
		style:{
			classes:'ui-tooltip-youtube'
		},
		position:{
			my:'top center',
			at:'bottom center'
		},
		content:{
			text:'Kelime dağarcığınıza buradan kelime ekleyebilirsiniz.',
			title:{text:'Bildirgeç',button:true}
		},
		events:{
			show:function(){
				var 
					cookieName='tooltips.wordAdditionForm.addWord',
					cookie=getCookie(cookieName);

				// If the cookie exists for the tooltip and
				// value of it "0", don't show the tooltip;
				// otherwise show it
				if(cookie && cookie=='0')
					return false;
			},
			hide:function(e,api){
				var 
					cookieName='tooltips.wordAdditionForm.addWord';

				// Set a cookie with value "0" for the tooltip
				setCookie(cookieName,'0');	
			}
		}
	});
}

