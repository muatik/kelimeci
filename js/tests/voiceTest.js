$(document).ready(function(){

	var 
		test=new Test('voiceTest'),
		// jPlayer used on the voice test page
		$jplayer=$('#voiceTestJplayer');

	// Attach only one jPlayer to the page
	$jplayer.jPlayer(voiceTestPage.jPlayer.ops);
	
	test.bindItems=function(){
		
		$('.testPageOl li input[type=text]').focusout(function(){
			
			if($(this).val()!=''){

				// Disable the input that is operated for
				$(this).attr('disabled',true);

				var params={
					wordId:$(this).parent().find('.wordId').val(),
					answer:$(this).val()
				};	

				test.checkAnswers(params);

			}

		});
		
		$('.testPageOl li img.voiceStatusImg').click(function(){
			var
				$t=$(this),
				$item=$(this).parent(),
				$voiceFile=$item.find('input.voiceFile'),
				// Status of jplayer
				jpStatus=null;

			// If not playing, play
			if($t.attr('src').indexOf('Play')!=-1){
				// Set the voice file to the jplayer to play
				$jplayer.jPlayer('setMedia',{mp3:$voiceFile.val()}).jPlayer('play');
			}
			// If playing or in progress(downloading to play), stop
			else{
				$jplayer.jPlayer('stop');
			}
		});

	}

	test.afterChecked=function(rsp){

		if(rsp!=null){

			var $resultInput=$(
				'.testPageOl input[class="wordId"][value="'+rsp.wordId+'"]'
			);
			
			var imgIncorrect='<img src="../images/incorrect.png" alt="" />',
				imgCorrect='<img src="../images/correct.png" alt="" />';
			
			// If the answer is correct
			if(rsp.result){
				$resultInput.parent().append(imgCorrect);
			}
			// If the answer is incorrect
			else{
				$resultInput.parent().append(imgIncorrect);
				var incorrect='';
				
				if(rsp.correction)
					incorrect='<strong>Yanlış:</strong>'+
						'<a href="#">'+rsp.correction+'</a>';
						
				var correction=$(
					'<span class="correction">'+
						'<strong>Doğrusu:</strong><span>'+rsp.answer+'</span>'
						+incorrect+							
					'</span>'
				);

				$resultInput.parent().append(correction);
			}

		}

	}
	
	test.start();

});

// Voice test page options
var voiceTestPage={};

voiceTestPage.pageOps={
	voiceFilesDir:'../audio/words/normal/',
	$items:null,
	$voiceStatusImgs:null,
	voiceStatusImg:{
		types:{
			progress:'../images/buttonProgress.png',
			play:'../images/buttonPlay.png',
			stop:'../images/buttonStop.png',
		}
	}
};

// Options for jplayer that is used in the voice test page
voiceTestPage.jPlayer={
	// Init. options of jplayer
	ops:{
		swfPath:'../js/jplayer/jplayer.swf',
		supplied:'mp3',
		solution:'flash,html',
		ready:function(){
			voiceTestPage.pageOps.$items=$('.voiceTest .testPageOl li');
			voiceTestPage.pageOps.$voiceStatusImgs=$('.voiceTest .testPageOl li img.voiceStatusImg');
		},
		play:function(e){
			voiceTestPage.jPlayer.methods.updateVoiceStatusImg(e,'play');
		},
		/*
		progress:function(e){
			voiceTestPage.jPlayer.methods.updateVoiceStatusImg(e,'progress');
		},
		*/
		pause:function(e){
			voiceTestPage.jPlayer.methods.updateVoiceStatusImg(e,'pause');
		},
		ended:function(e){
			voiceTestPage.jPlayer.methods.updateVoiceStatusImg(e,'ended');
		}
		,errorAlerts:true
		//,warningAlerts:true
	},
	// Custom methods for jplayer
	methods:{
		updateVoiceStatusImg:function(e,status){
			var 
				curVoiceFile=e.jPlayer.status.src,
				pageOps=voiceTestPage.pageOps,
				$curVoiceImg=pageOps.$items.find('input.voiceFile[value="'+curVoiceFile+'"]').parent().find('img'),
				newStatusImgTypeSrc=null;

			if(status=='play')
				newStatusImgTypeSrc=pageOps.voiceStatusImg.types.stop;
			/*
			else if(status=='progress') 
				newStatusImgTypeSrc=pageOps.voiceStatusImg.types.progress;
			else if(status=='ended') 
				newStatusImgTypeSrc=pageOps.voiceStatusImg.types.play;
			*/
			else{
				newStatusImgTypeSrc=pageOps.voiceStatusImg.types.play;
				$(this).jPlayer('stop');	
			}

			$curVoiceImg.attr('src',newStatusImgTypeSrc);
		}
	}
};

