/**
 * Speaker class
 *
 * @date 29.01.2012 02:26
 */
function Speaker(speakerContId){

	this.speakerContId=speakerContId;
	// Container speaker
	this.$cont=$('#'+speakerContId);
	// jplayer container for the speaker
	this.$jp=this.$cont.find('.speakerJPlayer');

	this.setJPlayer();
	this.bindElements();

}

Speaker.prototype.setJPlayer=function(){

	this.$jp.jPlayer({
		swfPath:'../js/jplayer/jplayer.swf',
		solution:'html,flash',
		supplied:'mp3',
		// If ua is ff with vers. 3.6, set the wmode "window" (required)
		wmode:($.browser.mozilla && $.browser.version.slice(0,3)=='3.6')
			? 'window' : 'opaque'
		/*
		ready:function(){},
		play:function(e){},
		progress:function(e){},
		pause:function(e){},
		ended:function(e){}
		*/
		//,errorAlerts:true
	});

}

Speaker.prototype.bindElements=function(){

	var t=this;

	this.$cont.find('a.player').click(function(){
		var
			$t=$(this),
			$mediaFile=t.$cont.find(':input[name="mediaFile"]'),
			$autoPlay=t.$cont.find(':input[name="autoPlay"]');

		t.$jp.jPlayer('setMedia',{mp3:$mediaFile.val()});

		if($autoPlay.val()==='true')
			t.$jp.jPlayer('play');

		return false;
	});

	/**
	 * On play
	 */
	t.$jp.bind($.jPlayer.event.play,function(e){
		t.updateSpeakerImg(e,'play');
	});

	/**
	 * On ended
	 */
	t.$jp.bind($.jPlayer.event.ended,function(e){
		t.updateSpeakerImg(e,'ended');
	});

}

Speaker.prototype.updateSpeakerImg=function(e,status){
	var
		// The current media file in progress
		curMediaFile=e.jPlayer.status.src,
		// The current image assosioted with the current media file
		$curSpeakerImg=this.$cont.find('img');

	if(status=='play')
		$curSpeakerImg.attr('src','../images/speaker/speakerPlaying.png');
	else
		$curSpeakerImg.attr('src','../images/speaker/speakerPlay.png');
}
