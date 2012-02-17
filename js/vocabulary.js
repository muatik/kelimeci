var vocabulary={};

vocabulary.onAddCallback=null;
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
	var ajax=new simpleAjax();
	
	if(arguments.length>2)
		callback=arguments[2];
	else
		callback=this.onAddCallback;

	ajax.send(
		'vocabulary?_ajax=vocabulary/addWord&word='
		+word+'&tag='+tags,
		null,
		{onSuccess:function(rsp){
			/**
			 * is there a callback function, call it.
			 * */
			if(callback)
				callback(rsp)
		}}
	);
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

_vcbp.rmWord=function(words){

	for(var i in words)
		words[i]=encodeURIComponent(words[i]);
	
	var params='word[]='+words.join('&word[]=');
	
	if(arguments.length>1)
		var callBack=arguments[1];
	else	
		var callBack=false;

	var sa=new simpleAjax();
	sa.send(
		'vocabulary?_ajax=vocabulary/rmWord&'+params,null,
		{onSuccess:function(rsp,o){

			// if there is a callback function, it's calling
			if(callBack)
				callBack(rsp);
		}}
	)
}

_vcbp.saveWordPackages=function(selPackages,uselPackages){

	var params='sel[]='+selPackages.join('&sel[]=')
		+'&usel[]='+uselPackages.join('&usel[]=');
	
	if(arguments.length>2)	var callBack=arguments[2]; else	var callBack=false;

	var sa=new simpleAjax();
	sa.send(
		'vocabulary?_ajax=vocabulary/saveWordPackages&'+params,null,
		{onSuccess:function(rsp,o){

			// if there is a callback function, it's calling
			if(callBack)
				callBack(rsp);
		}}
	);

}

_vcbp.getWordPackagesByGroup=function(groupId){
	
	if(arguments.length>1)	var callBack=arguments[1]; else	var callBack=false;

	var params='vocabulary?_view=wordPackages&wpgId='+groupId;

	var ajax=new simpleAjax();
	ajax.send(
		params,null,{'onSuccess':function(rsp){
			if(callBack)
				callBack(rsp);
		}}
	);
}
