/**
 * Test class that is used as a object for in all Test pages
 * 
 * If wanted to show time elapsed, must use 
 * the function startTimer() and the function showTime 
 * must be implemented to specify where time elapsed 
 * will be shown on a test page.
 */
function Test(testName){
 	
	this.ajaxFile='?_ajax=validate';
	this.testName=testName;
	this.correctAnswerCounter=0;
	this.incorrectAnswerCounter=0;
	this.unansweredQuestionCounter=0;

	// Time to be shown in formatted
	this.elapsedTime=null;
	
	// Hold time after test started
	this.initialTime=null;

	// Time difference between initial time and now(time)
	this.timeDiff=null;

	this.setIntervalObjForTimer=null;
	
}

/**
 * Start timer
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
 * Set the timer to be shown
 * and calls the function showTime that will be overwritten(implemented)
 */
Test.prototype.setTimer=function(){

	// Calculate elapsed time 
	this.timeDiff=
		new Date((new Date()).getTime()-this.initialTime.getTime());
	
	this.elapsedTime=
		this.timeDiff.toUTCString().
		match(/\d{2}:\d{2}:\d{2}/).toString();

	this.showElapsedTime();

}

/**
 * Stop timer
 */
Test.prototype.stopTimer=function(){
	if(this.setIntervalObjForTimer)
		clearTimeout(this.setIntervalObjForTimer);
}

/**
 * Show the elapsed time
 */
Test.prototype.showElapsedTime=function(){
	$('.testPageHeader span.spentTime').html(this.elapsedTime);
}

/**
 * Decrease the variable unansweredQuestionCounter
 */
Test.prototype.decreaseUnansweredCounter=function(){
	// If there is any unanswered questions, dec. the counter
	if(this.unansweredQuestionCounter>0){
		this.unansweredQuestionCounter--;

		// If there is no unanswered questions, stop the timer
		if(this.unansweredQuestionCounter==0){
			this.stopTimer();
		}
	}
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
 * Absract function - must be overwritten(implemented)
 */
Test.prototype.prepareTest=function(){}

Test.prototype.checkAnswers=function(params){
	
	var ajax=new simpleAjax(),
	that=this;
	
	var paramCloud='';
	for(var i in params){
		if(params[i] instanceof Array)
			for(var k in params[i])
				paramCloud+=i+'[]='+params[i][k]+'&';
		else
			paramCloud+=i+'='+params[i]+'&';
	}
	
	paramCloud=paramCloud.substr(0,paramCloud.length-1);

	ajax.send(
		this.ajaxFile,
		'testName='+encodeURI(this.testName)+'&'+paramCloud,
		{'onSuccess':function(rsp,o){

			var rsp=jQuery.parseJSON(rsp);

			// If the answer is correct
			if(rsp.result){
				that.incrementCorrectCounter();
				$('.testPageHeader .correctAnswers')
					.html(that.correctAnswerCounter);
			}
			// If the answer is incorrect
			else{
				that.incrementIncorrectCounter();
				$('.testPageHeader .incorrectAnswers')
					.html(that.incorrectAnswerCounter);
			}

			// Decrease unanswered question counter
			// and show it
			that.decreaseUnansweredCounter();
			$('.testPageHeader .unansweredQuestions')
				.html(that.unansweredQuestionCounter);
	
			that.afterChecked(rsp,o);
		}}
	);

}

/**
 * Absract function - must be overwritten(implemented)
 *
 * Called this function to do operations for the test page
 * after answer(s) is checked out
 */
Test.prototype.afterChecked=function(){}

/**
 * Start the test
 *
 * MUST be invoked after overwritten of the function "afterChecked" and
 * "bindItems" on the test page
 */
Test.prototype.start=function(){

	this.bindItems();

	this.startTimer();

	// Get if there is total question information
	var totQues=parseInt($('.testPageHeader .totalQuestions').html());

	this.unansweredQuestionCounter=(totQues>0) ? totQues : 0;
	
	// If there is, update unanswered question information
	if(this.unansweredQuestionCounter>0)
		$('.testPageHeader .unansweredQuestions')
			.html(this.unansweredQuestionCounter);

}

/**
 * Finish the test
 */
Test.prototype.finish=function(){
	this.stopTimer();
}
