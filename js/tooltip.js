/**
 * Create a tooltip
 *
 * @param array || object tooltip
 * 	1) array:
 * 		[
 *			{'target':'','options':''},
 *			{'target':'','options':''},
 *			...
 * 		]
 *
 * 	2) object:
 *		{'target':'','options':''}
 */
function createTooltip(tooltip){
	// If array, call self in a loop
	if($.isArray(tooltip)){
		for(var i in tooltip)
			createTooltip(tooltip[i]);
	}
	// If not array
	else{
		// If there is target element
		if($(tooltip.target).length>0){
			// Create tooltip
			$(tooltip.target).qtip(tooltip.options);
		}
	}
}

/**
 * Hide the tooltip 
 * 	by clicking the hide link inside the tooltip
 */
function hideTooltip(elem){
	var $e=$(elem);

	// If invoked by a qtip hide link inside a tooltip
	if($e.hasClass('qtipHide')){
		var qtipId=$e.parents('.ui-tooltip').attr('id');
		$('#'+qtipId).qtip('api').hide();
		return false;
	}
}

/**
 * Customized tooltips
 */
function ToolTips(){}

/**
 * Defaults
 */
ToolTips.prototype.defs={
	tClass:'customToolTip'
}

/**
 * Show the tooltip with the type speficied
 *
 * @param string target
 * @param string type ToolTip type
 * @param string nameSpace
 */
ToolTips.prototype.show=function(target,type,nameSpace){
	// If no target like this, return
	if($(target).length==0)
		return;
	
	var tTip=null;

	// If correct namespace and type
	if(nameSpace && this.types.nameSpaced[nameSpace][type])
		tTip=this.types.nameSpaced[nameSpace][type];		
	// If correct type
	else if(type && this.types[type])
		tTip=this.types[type];
	
	if(tTip!=null)
		$(target).qtip(tTip);
}
	
/**
 * Types of tooltips
 */
ToolTips.prototype.types={
	nameSpaced:{
		test:{
			result:{
				show:{ready:true},
				hide:false,
				content:{
					title:{
						text:'Sonuç İşaretleri',
						button:true
					},
					text:
					'<ul class="questionResult">'+
						'<li><b class="c">D</b> - <span>Doğru seçilen</span></li>'+
						'<li><b class="i">Y</b> - <span>Yanlış seçilen</span></li>'+
						'<li><b class="l">Ç</b> - <span>Seçilmeyen doğru</span></li>'+
					'</ul>'
				},
				position:{
					my:'left center',
					at:'right center'
				},
				style:{
					classes:'ui-tooltip-youtube ui-tooltip-shadow'
				}
			}
		},
		word:{
			// Classes of word
			classes:{
				overwrite:false,
				show:{ready:true},
				content:{
					text:
					'<ul class="wordClasses">'+
						'<li><abbr class="v">f</abbr> - <span>Fiil</span></li>'+
						'<li><abbr class="n">i</abbr> - <span>İsim</span></li>'+
						'<li><abbr class="aj">s</abbr> - <span>Sıfat</span></li>'+
						'<li><abbr class="av">z</abbr> - <span>Zarf</span></li>'+
						'<li><abbr class="pp">e</abbr> - <span>Edat</span></li>'+
					'</ul>'
				},
				position:{
					my:'left center',
					at:'right center'
				},
				style:{
					classes:'ui-tooltip-youtube'
				}
			}
		}
	}
}

// ToolTips object
//var toolTips=new ToolTips();


