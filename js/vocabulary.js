var vocabulary={};

vocabulary.noScriptStyle=1;

vocabulary.filter={
	start:0,
	length:100,
	levelMin:-20,
	levelMax:20,
	orderBy:'alphabetically',
	classes:[]
}

var _vcbp=vocabulary;


_vcbp.add=function(word,tags){

}

_vcbp.get=function(filter){
	
	// updating static filter options
	for(var i in filter)
		vocabulary.filter[i]=filter[i];
	
	var callBack;
	var ajax=new simpleAjax();
	var f=vocabulary.filter;
	var params='start='+f.start+'&length='+f.length
		+'&levelMin='+f.levelMin+'&levelMax='+f.levelMax
		+'&keyword='+f.keyword+'&orderBy='+f.orderBy;

	for(var i in f.classes)
		params+='&classes[]='+f.classes[i];

	if(arguments.length>1)
		callBack=arguments[1];

	if(this.noScriptStyle)
		var noScriptStyle='noScriptStyle=1&';
	else
		var noScriptStyle=null;

	ajax.send(
		'vocabulary?_view=wordList&'+noScriptStyle+params,
		null,
		{onSuccess:function(rsp,o){
			if(callBack)
				callBack(rsp);
		}}
	)
}


_vcbp.addQuote=function(word,quote){
	
	if(arguments.length>2)
		var callBack=arguments[2];
	else	
		var callBack=false;

	var ajax=new simpleAjax();
	ajax.send(
		'vocabulary?_ajax=addQuote&word='+word+'&quote='+quote,
		null,
		{onSuccess:function(rsp,o){
			if(callBack)
				callBack(rsp);
		}}
	);
}


$(function(){
	
	// Close the filter form on the page load
	$('.wordFilterForm').hide();

});
