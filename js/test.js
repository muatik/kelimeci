/**
 * Test class that is used as a object for in all Test pages
 * 
 * If wanted to show time elapsed, must use 
 * the function startTimer() and the function showTime 
 * must be implemented to specify where time elapsed 
 * will be shown on a test page.
 */
function Test(testName){
 	
	this.ajaxFile='tests/?_ajax=validate';
	this.testName=testName;
	this.correctAnswerCounter=0;
	this.incorrectAnswerCounter=0;

	// Time to be shown in formatted
	this.elapsedTime=null;
	
	// Hold time after test started
	this.initialTime=null;

	// Time difference between initial time and now(time)
	this.timeDiff=null;

	this.setIntervalObjForTimer=null;
	
}
/**
 * Starts timer
 */
Test.prototype.startTimer=function(){
	// Create initial time
	this.initialTime=new Date();

	var that=this;
	this.setIntervalObjForTimer=setInterval(
		function(){that.setTimer()},1000
	);
}

/**
 * Sets the timer to be shown
 * and calls the function showTime that will be overwritten(implemented)
 */
Test.prototype.setTimer=function(){

	// Calculate elapsed time 
	this.timeDiff=
		new Date((new Date()).getTime()-this.initialTime.getTime());
	
	this.elapsedTime=
		this.timeDiff.
			toUTCString().match(/\d{2}:\d{2}:\d{2}/).toString();

	this.showElapsedTime();

}

/**
 * Shows the elapsed time on the test page
 */
Test.prototype.showElapsedTime=function(){
	document.getElementByClass('elapsedTime')
		.innerHTML(this.elapsedTime);
}

/**
 * Increments the variable correctAnswerCounter
 */
Test.prototype.incrementCorrectCounter=function(){
	this.correctAnswerCounter++;
}

/**
 * Increments the variable incorrectAnswerCounter
 */
Test.prototype.incrementIncorrectCounter=function(){
	this.incorrectAnswerCounter++;
}

/**
 * Absract function - must be overwritten(implemented)
 */
Test.prototype.bindItems=function(){}

/**
 * Send requests to the server to check answers for 
 * the test pages
 */
Test.prototype.checkAnswers=function(params){

	var ajax=new simpleAjax(),
		that=this
		parameters='';

	for(var i in params.answer){

		var p=params.answer[i];

		// If the answer is a array
		if(typeof(p)=='object' && (p instanceof Array)){
			for(var j in p)
				parameters+=j+'[]='+p[i]+'&';
		}
		else
			parameters+=i+'='+p+'&';
			
	}

	/**
	* Remove the character '&' 
	* that is the last character of the variable parameters
	*/
	parameters.substr(0,parameters.length-1);
	
	alert(parameters);

	ajax.send(
		this.ajaxFile,
		'testName='+encodeURI(this.testName)+'&'+parameters,
		{'onSuccess':function(rsp,o){

			var rsp=eval('('+rsp+')');
			//var rsp=jQuery.parseJSON(rsp);

			// If the answer is correct
			if(rsp.result)
				incrementCorrectAnswer();
			// If the answer is incorrect
			else
				incrementIncorrectAnswer();
				
			that.afterChecked(rsp,o);
		
		}}
	);

}

/**
 * Absract function - must be overwritten(implemented)
 *
 * Called this function to do operations for (SAYFAYA ÖZEL)
 * after answer(s) is checked out
 */
Test.prototype.afterChecked=function(){}

