function createXHR()
{
	var xmlHttp;
	try
  	{ xmlHttp=new XMLHttpRequest(); }
	catch (e)
  	{
		 try
    		{xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");}
  		catch (e)
    		{	
    			try
      			{xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");}
    			catch (e)
      			{alert("Tarayıcısınız AJAX'ı deskteklemiyor!");return false;}
    		}
	}
	return xmlHttp;
}

function simpleAjax()
{
	this.timeout=8;			// sunucu yanıtı için kaç saniye bekleneceğini belirtir.
	this.o=createXHR();			// veri iletişiminde kullanılacak httprequest nesnesi
	this.asynchronously=true;	// sunucu ile iletişimin eş zamanlı olup olmayacağını belirtir. 
	this.noCache=true;			// önbellek dosyalarının kullanılıp kullanılmayacağını belirtir.
	
	function send(url,data,events)
	{
	}
}
simpleAjax.prototype.setNoCache=function(){
	this.o.setRequestHeader("Cache-Control","no-store, no-cache, must-revalidate");
	this.o.setRequestHeader( "If-Modified-Since", "Sat, 1 Jan 1900 00:00:00 GMT" );
	return true;
}
simpleAjax.prototype.send=function()	// sunucuya veri gönderir
{
	if(arguments.length==0) return false;	// en azından ilk parametre olan url belirtilmelidir.
	var url=arguments[0];
	var data;
	var events;
	var postMethod;
	var o=this.o;
	
	if(arguments.length>1) data=arguments[1];	 		// ikinci parametre olan veri eğer belirtilmişse, gönderilecektir.
	if(arguments.length>2) events=arguments[2]; 		// üçüncü parametre olan işlem metodları eğer belirtilmişse
	if(arguments.length>3) postMethod=arguments[3]; 	//
	else
	{
		if(data==null) postMethod='GET'; else postMethod='POST'; 
	}
	
	o.abort();
	o.onreadystatechange=function()
	{
		if(o.readyState==4)
		{
			if(o.status==200)
			{
				if(typeof(events)=='object' && events.onSuccess) return events.onSuccess(o.responseText,o);
			}
			else if(typeof(events)=='object' && events.onError) return events.onError(o);
		}
	}
	o.open(postMethod,url,this.asynchronously);
	if(this.noCache) this.setNoCache();
	if(postMethod=='POST') o.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=utf-8");
	o.send(data);
}
