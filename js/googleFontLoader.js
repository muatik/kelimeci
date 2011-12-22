/**
 * If wanted to be loaded some google fonts by Web Font Loader Api,
 * import this js file into <head> as the first element.
 */

WebFontConfig={
	google:{families:['Flamenco','Fondamento']}
};
(function(){
	var wf=document.createElement('script');
	wf.src=('https:'==document.location.protocol ? 'https' : 'http') +
	    '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
	wf.type='text/javascript';
	wf.async='true';
	var s=document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(wf,s);
})();

