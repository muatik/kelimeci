// Hide(and remove) the notifications elements on the page
$(function(){
	var 
		// Notification elements
		$nots=$('.notification'),
		// Time to hide in miliseconds
		clsTime=3200;

	// If there is any notifications
	if($nots.length>0){
		$nots.each(function(){
			var $t=$(this);
			setTimeout(
				function(){
					// Fade out and remove it
					$t.fadeOut('slow',function(){$(this).remove();});
				}
				,clsTime
			);
		});
	}
});
