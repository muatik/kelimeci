/**
 * Bu sınıf kelime dağarcığına yeni kelimeler eklemek için
 * kullanılan form için yazılmıştır. 
 * */
wordAdditionForm={};

wordAdditionForm.bind=function(){
	
	$('.wordAdditionForm').unbind('submit')
	.bind('submit',function(e){
		wordAdditionForm.add(this);
		e.preventDefault();
	});


}

wordAdditionForm.add=function(f){
	var tag=$('input[name="tag"]',f).val();
	var word=$('input[name="word"]',f).val();
	vocabulary.add(word,tag, wordAdditionForm.onAdd);
}
