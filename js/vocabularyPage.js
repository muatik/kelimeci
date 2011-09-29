function vcbPage(){
	this.list=$('.ul');
	this.bind();
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
	var classes=$('.classesCheckList').val().toString().split(',');
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
}

vcbp.bindList=function(){

}

vcbp.showDetail=function(word){

}

