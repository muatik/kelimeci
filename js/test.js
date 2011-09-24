/**
 * Test class that is used as a object for in all Test pages
 * 
 * If wanted to show time elapsed, must use 
 * the function startTimer() and the function showTime 
 * must be implemented to specify where time elapsed 
 * will be shown on a test page.
 */
function Test(testName){
 	
	/*
	 * THINK OF THIS AGAIN
	 * THINK MAY BE DEFINED IN COMMON JS FILE
	 */
	this.ajaxFile='ajax.php';
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
		this.timeDiff.toUTCString().match(/\d{2}:\d{2}:\d{2}/).toString();

	this.showTime();

}

/**
 * Absract function - must be overwritten(implemented)
 */
Test.prototype.showTime=function(){}

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

	var ajax=new simpleAjax(),that=this;
	
	var answer='&answer='+encodeURI(params.answer);

	// If the answer is a array
	if(typeof(params.answer)=='object' && (params.answer instanceof Array)){
	
		answer='&';
		for(var i in params.answer){
			answer+='answer[]='+encodeURI(params.answer[i])+'&';
		}
		answer=answer.substring(0,answer.length-1);

	}
	// If the answer is undefined or empty
	else if(typeof(params.answer)=='undefined' || params.answer==''){
		answer='';
	}
	else{
		// If params.answer contain data like:
		// &variation[]=noun&answer[]=p&variation[]=verb&answer[]=o...
		if(
			params.answer.indexOf('answer')!=-1 || 
			params.answer.indexOf('answers')!=-1
		)
			answer=params.answer;
	}
	
	ajax.send(
		this.ajaxFile,
		'testName='+encodeURI(this.testName)+
		'&itemId='+encodeURI(params.itemId)+answer,
		{'onSuccess':function(rsp,o){
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

