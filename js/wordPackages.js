var wordPackages={
	onSaveCallback:null
};

wordPackages.init=function(){
	var t=this;
	
	$('img.togglePack').live('click',function(){
		t.togglePackageList($(this).closest('li'));
	})

	$('.packages input.package').live('click',function(){
		t.refreshSelectedList(this);
	});

	$('.wordPackageGroups input.groups').live('click',function(){
		t.toggleSelect($(this).closest('li.group'));
	});

	$('form.wordPackageGroups').live('submit',function(e){

		// if there will be a removed package, confirm before action.
		if($('li.in input:not(:checked)').length>0){
			var c=confirm('Kelime dağarcığına eklenmiş bir paketi kaldırmak '
				+'paketteki kelimelerin de kelime dağarcığından kaldırılmasına '
				+'sebep olur. Bunu istiyor musun?');

			if(!c) return false;
		}

		toggleAjaxIndicator($('form.wordPackages'),'','append');

		var uids=new Array(); // unselected ids
		var ids=new Array();

		$('input.package:checked',this).each(function(){
			ids.push($(this).val());
		});
		
		$('input.package',this).not(':checked').each(function(){
			uids.push($(this).val());
		});

		vocabulary.saveWordPackages(ids,uids,wordPackages.afterSave);

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

wordPackages.togglePackageList=function(group){
	// group is the element LI
	
	if($('ul.packages',group).get(0))
		return $('ul.packages',group).toggle('fast');

	
	var groupId=$('input.groups',group).val();
	
	vocabulary.getWordPackagesByGroup(groupId,function(rsp){
		$(rsp).hide().appendTo(group).show('fast');
	});

}


wordPackages.refreshSelectedList=function(i){
	var group=$(i).closest('li.group');
	if($('input.package:checked',group).get(0))
		group.find('input.groups').attr('checked','checked');
	else
		group.find('input.groups').removeAttr('checked');

}

wordPackages.toggleSelect=function(group){
	
	var toggleCheckboxes=function(){
		if($('input.groups:checked',group).get(0))
			$('input.package',group).attr('checked','checked');
		else
			$('input.package',group).removeAttr('checked');
	}

	if($('ul.packages',group).get(0))
		toggleCheckboxes();
	else{
		var groupId=$('input.groups',group).val();
		vocabulary.getWordPackagesByGroup(groupId,function(rsp){
			$(rsp).hide().appendTo(group);
			toggleCheckboxes();
		});
	}
	
}

wordPackages.init();
