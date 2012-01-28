(function(){

	var $jp;
	
	if($('jplayer').length==0){
		$('<div id="jplayer"></div>').prependTo('body');
		$jp=$('#jplayer');
	}

	if(!$jp.jPlayer.event.ready){
		var ops=getJPlayerOps('audio');		
		$jp.jPlayer(ops);
	}

	$('span.speaker:not(:hasClass(binded)) a.speaker').click(function(){
		var
			$t=$(this),
			$mediaFile=$t.parent().find(':input[name="mediaFile"]'),
			$autoPlay=$t.parent().find(':input[name="autoPlay"]');

		$jp.jPlayer('setMedia',{mp3:$mediaFile.val()});

		if($autoPlay.val()==='true')
			$jp.jPlayer('play');

		// Set as binded
		$t.parent().addClass('binded');
	});

	/**
	 * On play
	 */
	$jp.bind($.jPlayer.event.play,function(e){
		setSpeakerImg(e,'play');
	});

	/**
	 * On ended
	 */
	$jp.bind($.jPlayer.event.ended,function(e){
		setSpeakerImg(e,'ended');
	});
});

function setSpeakerImg(e,status){
	var
		// The current media file in progress
		curMediaFile=e.jPlayer.status.src,
		// The current image assosioted with the current media file
		$curSpeakerImg=
			$('span.speaker :input[name="mediaFile"][value="'+curMediaFile+'"]')
			.parent().find('img');

	if(status=='play')
		$curSpeakerImg.attr('src','../images/speaker/speakerPlaying.png');
	else
		$curSpeakerImg.attr('src','../images/speaker/speakerPlay.png');
}

function getJPlayerOps(type){

	var jpInitOps={
		swfPath:'jplayer/jplayer.swf',
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
	};

	if(type=='video')
		jpInitOps.supplied='mp4';
	else
		jpInitOps.supplied='mp3';

	return jpInitOps;

}
