$(document).ready(function(){
	
	$('.quotes li img').click(function(){
		
		var o=$('span',$(this).closest('p'));

		if($(o).css('visibility')=='hidden')
			var val='visible';
		else
			var val='hidden';

		$(o).css('visibility',val);

	});

})
