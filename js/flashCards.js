function flashCards(words){

	/**
	 * id of this scene. this differs instaces from an another.
	 * */
	this.id;

	/**
	 * holds the words which will be shown on the scene.
	 * Words will not have any detail by default, but just words themselves.
	 * [{word:'',status:false|fetching|fetched,detail:null}...]
	 * */
	this.words=words;

	/**
	 * specifies time interval to next word
	 * */
	this.interval=8000;
	
	/**
	 * holds the resource of setInterval() function
	 * */
	this.timer;

	/**
	 * holds the array key of the word that is on the stage.
	 * */
	this.pointer=0;

	/**
	 * specifies status of the scene. the value can be 'playing' or 'paused'
	 * */
	this.status='paused';

	this.init();

}

var flscp=flashCards.prototype;

flscp.init=function(){

	/**
	 * it's fetching the detail of the first two words 
	 * for not making the user wait.
	 * */
	for(var i=0; i<2 && i<this.words.length; i++ )
		this.getWordDetail(this.words[i]);
	

	this.play();

	// the form elements are being binding below
	$('.sceneForm .speedSlider').slider({
		range: "min",
		value: 8,
		min: 1,
		max: 20,
		slide: function( event, ui ) {
			$('.sceneForm span.speed').html(ui.value+'sn');
		}
	});

}


flscp.play=function(){

	var t=this;

	// stop and clear for starting a new playing.
	this.pause();

	this.timer=setInterval(
		function(){
			t.goNext();
		},this.interval);


	this.status='playing';
}

flscp.pause=function(){

	if(this.timer)
		clearTimeout(this.timer);

	this.status='paused';

}

flscp.goPrevious=function(){
	
	// if the pointer is on last stage
	if(this.pointer==0)
		return false;

	this.pointer--;
	
	this.show(this.words[this.pointer]);
}

flscp.goNext=function(){

	// if the pointer is on last stage
	if(this.pointer==this.words.length-1)
		return false;

	this.pointer++;
	
	this.show(this.words[this.pointer]);

	if(this.pointer<this.words.length-1)
		this.getWordDetail(this.words[this.pointer+1]);

}

flscp.getWordDetail=function(word){
	
	if(word.status=='fetching' || word.status=='fetched')
		return false;
	
	word.status='fetching';
	
	a=new simpleAjax();
	
	a.send(
		'?_ajax=getWordDetail&word='+word.word, null,
		{onSuccess:function(rsp){

			word.detail=jQuery.parseJSON(rsp);
			
			word.status='fetched';
			word.ajax=null;
		}}
	);

	word.ajax=a;

}

flscp.show=function(word){

	var s=$('#scene'+this.id);

	$('h2',s).html(word.word);
	
	var ms=word.detail.meanings;
	var ms2=new Array();
	for(var i in ms){
		if(!ms2[ms[i].cls.id])
			ms2[ms[i].cls.id]={
				clsId: ms[i].cls.id,
				clsName: ms[i].cls.name,
				meaning:''
			};

		ms2[ms[i].cls.id].meaning+=ms[i].meaning+', ';
	}
	
	var h='';

	var clsAbbr='';

	for(var i in ms2){
		
		switch(ms2[i].clsName){
			case 'noun': clsAbbr='i'; break;
			case 'verb': clsAbbr='f'; break;
			case 'adjective': clsAbbr='s'; break;
			case 'adverb': clsAbbr='z'; break;
			case 'preposition': clsAbbr='e'; break;
			default: clsAbbr='-';
		}

		h+='<div class="meaning">\
			<span class="clsBoxes"> \
				<abbr class="'+ms2[i].clsName+' active" title="">'+clsAbbr+'</abbr>\
			</span> \
			<span class="text">'+ms2[i].meaning+'</span>\
		</div>';
	}
		
	h+='<ul class="quotes">';
	for(var q in word.detail.quotes)
		h+='<li>'+word.detail.quotes[q].quote+'</li>';
	h+='</ul>';

	$('.detail',s).html(h);
}

