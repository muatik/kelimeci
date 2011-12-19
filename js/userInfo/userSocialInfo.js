$(function(){

	/**
	 * Animate for showing the percantage value 
	 * of compatibility between the users
	 */
	var 
		$compStatus=$('.userSocialInfo .compatibility .status'),
		$compChart=$compStatus.find('.chart[percantageVal!=""]');
	
	if($compChart.attr('percantageval')!=0){
		var 
			// Percantage value
			val=parseInt($compChart.attr('percantageVal')),
			// Max width value
			maxWid=$compStatus.width(),
			// Calculated width
			calcWid=Math.floor((maxWid*val)/100);

		// Reset the width
		$compChart.css('width','0px');

		// Hide the text to show after the animation
		$compStatus.find('.text').css('visibility','hidden');
		
		// Animate
		$compChart.animate(
			{'width':calcWid},
			1250,
			function(){
				// Show the text
				$compStatus.find('.text').css('visibility','visible');
			}
		);
	}

});
