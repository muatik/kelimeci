/**
 * Speaker class
 *
 * @date 29.01.2012 02:26
 */
function Speaker(speakerContId){

	this.speakerContId=speakerContId;
	// Container speaker
	this.$cont=$('#'+speakerContId);
	// Player container for the speaker
	this.$player=this.$cont.find('.speakerPlayer');
	// Player object
	this.player=null;

	this.setPlayer();
}

Speaker.prototype.setPlayer=function(){
	var 
		t=this,
		$mediaFile=t.$cont.find(':input[name="mediaFile"]'),
		$autoPlay=t.$cont.find(':input[name="autoPlay"]'),
		$autoBuffering=t.$cont.find(':input[name="autoBuffering"]'),
		autoPlay=null,
		autoBuffering=null;

	// Determine whether auto-play or not
	if($autoPlay.val()=='false')
		autoPlay=false;
	else
		autoPlay=true;

	// Determine whether auto-buffering or not
	if($autoBuffering.val()=='false')
		autoBuffering=false;
	else
		autoBuffering=true;

	this.player=flowplayer(
		this.$player.attr('id'),
		{
			src:'../js/flowplayer/flowplayer-3.2.7.swf',
			onFail:function(){
				console.log('Speaker: The flash player could not loaded!');	
			}
		},
		{
			debug:false,
			clip:{
				url:$mediaFile.val(),
				autoPlay:autoPlay,
				autoBuffering:autoBuffering,
				// Clip events
				onBegin:function(){
					t.updateSpeakerImg('begin');
				},
				onStart:function(){
					t.updateSpeakerImg('play');
				},
				onPause:function(){
					t.updateSpeakerImg('pause');
				},
				onStop:function(){
					t.updateSpeakerImg('stop');
				},
				onFinish:function(){
					t.updateSpeakerImg('finish');
				},
				onResume:function(){
					t.updateSpeakerImg('resume');
				}
			},
			// Player events
			onLoad:function(){
				var _player=this;
				// Bind speakerBtn to play
				t.$cont.find('a.speakerBtn').click(function(){
					_player.play();
					return false;
				});
			}
		}
	);
}

Speaker.prototype.updateSpeakerImg=function(status){
	var
		// The current image assosioted with the current media file
		$curSpeakerImg=this.$cont.find('img');

	if(status=='play')
		$curSpeakerImg.attr('src','../images/speaker/speakerPlaying.png');
	else
		$curSpeakerImg.attr('src','../images/speaker/speakerPlay.png');
}
