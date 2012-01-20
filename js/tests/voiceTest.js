$(document).ready(function(){

	var 
		test=new Test('voiceTest'),
		// jPlayer used on the voice test page
		$jplayer=$('.voiceTest voiceTest.jPlayer');

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
				$item=$(this).parent(),
				$voiceFile=$item.find('input.voiceFile');

			// Set the voice file to the jplayer for the current question word
			$jplayer.jPlayer('setMedia',{mp3:$voiceFile.val()});
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
	voiceFilesDir:'../audio/',
	$items:$('.voiceTest .testPageOl li'),
	$voiceStatusImgs:$('.voiceTest .testPageOl li img.voiceStatusImg'),
	voiceStatusImg:{
		dir:'../images/',
		types:{
			progress:dir+'voiceImgProgress.png',
			play:dir+'voiceImgPlay.png',
			stop:dir+'voiceImgStop.png'
		}
	}
};

// Options for jplayer that is used in the voice test page
voiceTestPage.jPlayer={
	// Init. options of jplayer
	ops:{
		swfPath:'jPlayer/jPlayer.swf',
		supplied:'mp3',
		solution:'flash,html',
		progress:function(e){
			voiceTestPage.jPlayer.methods.updateVoiceStatusImg(e,'progress');
		},
		play:function(e){
			voiceTestPage.jPlayer.methods.updateVoiceStatusImg(e,'play');
		},
		ended:function(e){
			voiceTestPage.jPlayer.methods.updateVoiceStatusImg(e,'ended');
		}
	},
	// Custom methods for jplayer
	methods:{
		updateVoiceStatusImg:function(e,status){
			var 
				curVoiceFile=e.jPlayer.status.src,
				$curVoiceImg=$voiceStatusImgs.filter('[src="'+curVoiceFile+'"]'),
				newStatusImgTypeSrc=null;

			if(status=='progress') 
				newStatusImgTypeSrc=voiceTestPage.pageOps.voiceStatusImg.types.progress;
			else if(status=='play') 
				newStatusImgTypeSrc=voiceTestPage.pageOps.voiceStatusImg.types.stop;
			else if(status=='ended') 
				newStatusImgTypeSrc=voiceTestPage.pageOps.voiceStatusImg.types.play;

			$curVoiceImg.attr('src',newStatusTypeSrc);
		}
	}
};

