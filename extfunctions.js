getById=function(id)
{
	return document.getElementById(id);
}
getByTag=function(tag)
{
	var tag=arguments[0];
	var oList=new Array();
	if(arguments.length>1) var rootObj=arguments[1]; else rootObj=document;
	return rootObj.getElementsByTagName(tag);
}

getByClass=function()
{
	var c_name=arguments[0];
	var oList=new Array();
	if(arguments.length>1) var rootObj=arguments[1]; else rootObj=document;
	for(var i=0;i<rootObj.childNodes.length;i++)
	{
		try{
		if(rootObj.childNodes[i].className &&  rootObj.childNodes[i].className.search('(^'+c_name+'$)|(^'+c_name+' )|( '+c_name+' )|( '+c_name+'$)')!=-1)  oList.push(rootObj.childNodes[i]);
		}catch(e){}
		if(rootObj.childNodes[i].childNodes.length>0) oList=oList.concat(getByClass(c_name,rootObj.childNodes[i],'dd'));
	}
	return oList;

}
setAttributes=function(obj,attrs)
{
	for(var i=0;i<attrs.length;i++) obj.setAttribute(attrs[i][0],attrs[i][1]); 
	return true;
}
appendChilds=function(obj)
{
	if(arguments[1].length<2) return false;
	for(var i=1;i<arguments.length;i++) obj.appendChild(arguments[i]);
	return true;
}

quickCreate=function(obj)
{
	obj=document.createElement(obj);
	if(arguments.length>1)
	{
		var attrs=arguments[1];
		for(var i=0;i<attrs.length;i++) obj.setAttribute(attrs[i][0],attrs[i][1]);
	}
	return obj;
}

function removeClassName(obj,clsname)
{
	obj.className=obj.className.replace(' '+clsname,'');
	obj.className=obj.className.replace(clsname,'');
}
function getByClassName()
{
	if(arguments.length>1) return getByClass(arguments[0],arguments[1]); else return getByClass(arguments[0]);
}
function isIE()
{
	if(window.navigator.userAgent.indexOf('MSIE')>0) return true; else return false;
}

function confirmDel(a)
{
	if(confirm('Silmek istediğinize emin misiniz?'))
	{
		location.href=a.href+'&confirm=yes';
		return false;
	}
	else return false;
}

function getFormParams(f)
{
	var inputs=getByTag('input',f);
	var textareas=getByTag('textarea',f);
	var selects=getByTag('select',f);
	var params= {};
	
	for(var i=0;i<inputs.length;i++) if((inputs[i].type!='radio' || inputs[i].checked==true) && (inputs[i].type!='checkbox') ) params[inputs[i].name || inputs[i].id]=inputs[i].value;
	for(var i=0;i<textareas.length;i++) params[textareas[i].name || textareas[i].id]=textareas[i].value;
	for(var i=0;i<selects.length;i++) if(selects[i].selectedIndex>-1) params[selects[i].name || selects[i].id]=selects[i].options[selects[i].selectedIndex].value;
	return params;
}

String.prototype.pad = function(l, s, t){
	return s || (s = " "), (l -= this.length) > 0 ? (s = new Array(Math.ceil(l / s.length)
		+ 1).join(s)).substr(0, t = !t ? l : t == 1 ? 0 : Math.ceil(l / 2))
		+ this + s.substr(0, l - t) : this;
};


// convert to htmlentities
function htmlentities(msg){
	msg=msg.replace(/\&/ig,'&amp;');
	msg=msg.replace(/\</ig,'&lt;');
	msg=msg.replace(/\>/ig,'&gt;');
	msg=msg.replace(/"/ig,'&quot;');
	msg=msg.replace(/'/ig,'&#39;'); //  	&apos; (does not work in IE)
	return msg;
}
