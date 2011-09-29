function vcbPage(){
	this.list=$('.ul')
	this.bind();
}

var vcbp=vcbPage.prototype;

vcbp.bind=function(){
	$('.toggleInsertForm').click(function(){
		$('.wordAdditionForm').toggle();
	})

	$('.toggleFilterForm').click(function(){
		$('.wordFilterForm').toggle();
	})
}

vcbp.listWords=function(words){
	
}

vcbp.bindList=function(){

}

vcbp.showDetail=function(word){

}

var x=new vcbPage();
