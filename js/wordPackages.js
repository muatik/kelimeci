var wordPackages={
	onSavedCallback:null
};

wordPackages.init=function(){
	var t=this;
	$('form.wordPackages').live('submit',function(e){

		var ids=new Array();
		$('input:checked',this).each(function(){
			ids.push($(this).val());
		})

		e.preventDefault();
		vocabulary.saveWordPackages(ids,t.onSavedCallback);

		e.preventDefault();
	})
}

wordPackages.init();
