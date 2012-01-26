var wordPackages={
	onSaveCallback:null
};

wordPackages.init=function(){
	var t=this;

	$('form.wordPackages').live('submit',function(e){

		// if there will be a removed package, confirm before action.
		if($('li.in input:not(:checked)').length>0){
			var c=confirm('Kelime dağarcığına eklenmiş bir paketi kaldırmak '
				+'paketteki kelimelerin de kelime dağarcığından kaldırılmasına '
				+'sebep olur. Bunu istiyor musun?');

			if(!c) return false;
		}

		toggleAjaxIndicator($('form.wordPackages'),'','append');

		var ids=new Array();
		$('input:checked',this).each(function(){
			ids.push($(this).val());
		})

		e.preventDefault();
		vocabulary.saveWordPackages(ids,wordPackages.afterSave);

		e.preventDefault();
	})

}

wordPackages.afterSave=function(){
	toggleAjaxIndicator($('form.wordPackages'),'','append');
	wordPackages.markSelectedsAsSaved();
	if(wordPackages.onSaveCallback)
		wordPackages.onSaveCallback();
}

wordPackages.markSelectedsAsSaved=function(){
	$('form.wordPackages li').removeClass('in');
	$('form.wordPackages input:checked').each(function(){
		//li > label > input(this)
		// adding the class "in" to LIs.
		$(this).parent().parent().addClass('in');
	})
}

wordPackages.init();
