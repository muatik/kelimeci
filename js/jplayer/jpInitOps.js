/**
 * Custom jplayer init. options creator class
 */
function JPInitOps(){}

/**
 * Get a custom type options
 *
 * @param string type ('audio' | 'video')
 */
JPInitOps.prototype.get=function(type){
	var ops=null;
	
	if(type=='video'){

	}
	else{
		// Combine
		ops=$.extend(
			{},
			this.customTypes.common,
			this.customTypes.typeSpec.audio
		);			
	}

	return ops;
}

/**
 * Custom default init. options
 */
JPInitOps.prototype.customTypes={
	common:{
		swfPath:'../js/jplayer/jplayer.swf',
		solution:'flash,html',
		// ADD IF BROWSER FF <= 3.6, wmode:window
		/*
		ready:function(){},
		play:function(e){},
		progress:function(e){},
		pause:function(e){},
		ended:function(e){}
		*/
		,errorAlerts:true
	},
	typeSpec:{
		audio:{	
			supplied:'mp3'
		},
		video:{

		}
	}
};




