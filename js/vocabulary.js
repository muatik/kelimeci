var vocabulary={};

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

	
	ajax.send(
		'vocabulary?_view=wordList&'+params,
		null,
		{onSuccess:function(rsp,o){
			if(callBack)
				callBack(rsp);
		}}
	)
}
