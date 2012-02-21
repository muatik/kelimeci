
var vcbHistory={
	
	/**
	 * arrays of words in string type
	 * */
	previousList: new Array(),
	nextList: new Array(),
	
	/**
	 * last modification time for lists
	 * */
	lastModification:null
};


vcbHistory.addTo=function(items,l){

	if(typeof items=='string')
		items=new Array(words);

	if(l=='previous')
		vcbHistory.previousList=vcbHistory.previousList.concat(items);
	else
		vcbHistory.nextList=vcbHistory.nextList.concat(items);
	
	vcbHistory.lastModification=new Date().getTime();

}

vcbHistory.set=function(previous,next){
	vcbHistory.empty();
	vcbHistory.addTo(previous,'previous');
	vcbHistory.addTo(next,'next');
}

/**
 * Adds an item into the previous list.
 * The first parameter can be a string or an array of strings.
 * */
vcbHistory.addToPrevious=function(words){
	vcbHistory.addTo(words,'previous');
}

/**
 * adds an item into the next list
 * */
vcbHistory.addToNext=function(items){
	vcbHistory.addTo(words,'next');
}

/**
 * empties the next and previous list
 * */
vcbHistory.empty=function(){
	vcbHistory.previousList=new Array();
	vcbHistory.nextList=new Array();
	vcbHistory.lastModification=new Date().getTime();
}


