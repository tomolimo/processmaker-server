/*e4*/
/**
 * Implemetation layer for HTMLSuite Calendar in PMOS - Processmaker Open source
 * licence: LGPL www.gnu.org/licences
 * 
 * Author Erik Ortiz <aortiz.erik@gmail.com, erik@colosa.com>
 * @Date Oct 5th, 2009 La Paz - Bolivia
 */

var G_CALENDAR_CURRENT_OBJ = null;
var G_CALENDAR_MEM_OFFSET;

var NeyekCalendar = function(t, editable){
	
	this.target = (typeof(t) != 'undefined')? t: null;
	this.editable = (typeof(editable) != 'undefined')? "1": "0";
	
	this.calendarObjForForm = null;
	this.oCalendar = null;
	
	this.lang = null;
	this.mask = null;
	this.showTime = false;
	this.e = null;
	
	this.__init__ = function(initialDate, enableTime){
		
		var dayClickCallbackParams = Array(this.target, this.mask, this.editable);
		
		this.calendarObjForForm = new DHTMLSuite.calendar({
			minuteDropDownInterval		:10,
			numberOfRowsInHourDropDown	:5,
			callbackFunctionOnDayClick	: {"func": 'getDateFromCalendar', "params": dayClickCallbackParams},
			isDragable		:true,
			displayTimeBar	:this.showTime
		}); 
		
		//this.calendarObjForForm.setCallbackFunctionOnClose('myOtherFunction');
	
		this.oCalendar = new DHTMLSuite.calendarModel({ 
			initialYear		:initialDate.year,
			initialMonth	:initialDate.month,
			initialDay		:initialDate.day 
		});
		
	}
	
	this.picker = function(initialDate, mask, lang, beforeDate, afterDate, showTime, e){
		
		if(G_CALENDAR_CURRENT_OBJ != null) return false;
		
		if(G_CALENDAR_MEM_OFFSET == 'lock') return false;
		
		if ( typeof(mask) != 'undefined') {
			this.mask = mask;
		} else {
			this.mask = 'Y-m-d h:i';
		}
		//alert('->'+this.mask);
		if ( typeof(lang) != 'undefined') {
			this.lang = lang;
		} else {
			this.lang = 'en';
		}
		
		if ( typeof(showTime) != 'undefined') {
			if (showTime) {
				this.showTime = true;
			} else {
				this.showTime = false;
			}
		} else {
			this.showTime = false;
		}
		
		this.__init__(initialDate);
		
		if ( typeof(e) != 'undefined') {
			this.calendarObjForForm.e = e;
		}
		
		if ( typeof(beforeDate) != 'undefined' && beforeDate != '') {
			/* before date from it will be set such as invalid*/
			this.oCalendar.addInvalidDateRange(false,{
				year	: beforeDate.year, 
				month	: beforeDate.month,
				day		: beforeDate.day
			});
		} 
		
	
		if ( typeof(beforeDate) != 'undefined' && beforeDate != '') {
			/* after date from it will be set such as invalid*/
			this.oCalendar.addInvalidDateRange({
				year	: afterDate.year,
				month	: afterDate.month,
				day		: afterDate.day
			},false);
		}
		
		/* languaje set */
		this.oCalendar.setLanguageCode(this.lang);
		
		this.calendarObjForForm.setCalendarModelReference(this.oCalendar);
		
		eInput = this.$(this.target);
		eDiv = this.$(this.target+'[div]');
		
		// Position the calendar right below the form input
		if(this.editable === "1"){
			this.calendarObjForForm.setCalendarPositionByHTMLElement(eInput, 0, eInput.offsetHeight+2);
		} else {
			this.calendarObjForForm.setCalendarPositionByHTMLElement(eDiv, 0, eDiv.offsetHeight+2);
		}
		// Specify that the calendar should set it's initial date from the value of the input field.
		submask = this.mask;
		
		if (submask.search(/Y/) != -1 ) {
			submask = submask.replace("Y", 'yyyy');
		} else {
			submask = submask.replace("y", 'yy');
		}
		submask = submask.replace("m", 'mm');
		submask = submask.replace("d", 'dd');
		submask = submask.replace("h", 'hh');
		submask = submask.replace("i", 'ii');
		
		this.calendarObjForForm.setInitialDateFromInput(eInput, submask);
		
		// Adding a reference to this element so that I can pick it up in the getDateFromCalendar below(myInput is a unique key)	
		if(this.editable === "1"){
			this.calendarObjForForm.addHtmlElementReference(this.target, eInput);
		} else {
			this.calendarObjForForm.addHtmlElementReference(this.target+'[div]', eDiv);
		}
		
		if(this.calendarObjForForm.isVisible()){
			this.calendarObjForForm.hide();
			
		}else{
			// This line resets the view back to the inital display, i.e. it displays the inital month and not the month it displayed the last time it was open.
			this.calendarObjForForm.resetViewDisplayedMonth();	
			this.calendarObjForForm.display();
		}	
		
		G_CALENDAR_CURRENT_OBJ = this.calendarObjForForm;
		return false;
	}
	
	this.getDateFromCalendar = function(inputArray){
		// Get back reference to form field.
		var references = this.calendarObjForForm.getHtmlElementReferences(); 
		references.myDate.value = this.parseDateFromMask(inputArray, this.mask); //inputArray.year + '-' + inputArray.month + '-' + inputArray.day + ' ' + inputArray.hour + ':' + inputArray.minute;
		this.calendarObjForForm.hide();
		return false;
	}
	
	this.parseDateFromMask = function(inputArray, mask){
		/* inputArray is an associative array with properties
		year, month, day, hour, minute 	*/
		
		/* format mask
		 * Y 	-> 2009
		 * y	-> 09
		 * m	-> 02
		 * d	-> 01
		 * 
		 * h	-> 12
		 * i	-> 59
		 * 
		 * d/m/y -> 01/02/09
		 * d/m/Y -> 01/02/2009
		 * Y-m-d -> 2009-02-01
		 * 
		 * Y-m-d h:m -> 2009-02-01 12:59
		 * 
		 */
		
		result = mask;
		result = result.replace("Y", inputArray.year);
		
		year = new String(inputArray.year);
		result = result.replace("y", year.substr(2,3));
		result = result.replace("m", inputArray.month);
		result = result.replace("d", inputArray.day);
		result = result.replace("h", inputArray.hour);
		result = result.replace("i", inputArray.minute);
		
		return result;
		
	}
	
	this.$ = function(id){
		return document.getElementById(id);
	}
}

function getDateFromCalendar(inputArray, targetId, mask, editable){
	
	sClear = '<a onclick="clearCalendar(\''+targetId+'\');return false;" onmouseover="lockCalendar()" onmouseout="enableCalendar()" title=\''+G_STRINGS.ID_RSTDATAFIELD+'\' href="#"><img src="/images/delete-icon.png" border=0 width=12 height=12/></a>';
	
	result = mask;
	result = result.replace("Y", inputArray.year);

	year = new String(inputArray.year);
	result = result.replace("y", year.substr(2,3));
	result = result.replace("m", inputArray.month);
	result = result.replace("d", inputArray.day);
	result = result.replace("h", inputArray.hour);
	result = result.replace("i", inputArray.minute);
	
	document.getElementById(targetId).value = result;
	if(editable !== "1"){
		document.getElementById(targetId+'[div]').innerHTML = '&nbsp;'+document.getElementById(targetId).value + '&nbsp;' + sClear;
	}
	G_CALENDAR_CURRENT_OBJ.hide();
	
    if (document.getElementById(targetId).onchange){
	  try{
        document.getElementById(targetId).onchange(); 
      }catch(e){} 
    }
    
    G_CALENDAR_CURRENT_OBJ = null;
}	








/*e2*/
if(!window.DHTMLSuite)var DHTMLSuite=new Object();if(!String.trim)String.prototype.trim=function(){return this.replace(/^\s+|\s+$/,'')};var DHTMLSuite_funcs=new Object();if(!window.DHTML_SUITE_THEME)var DHTML_SUITE_THEME='blue';if(!window.DHTML_SUITE_THEME_FOLDER)var DHTML_SUITE_THEME_FOLDER='../themes/';if(!window.DHTML_SUITE_JS_FOLDER)var DHTML_SUITE_JS_FOLDER='../js/separateFiles/';var DHTMLSuite=new Object();var standardObjectsCreated=false;DHTMLSuite.eventEls=new Array();var widgetDep=new Object();widgetDep['formValidator']=['dhtmlSuite-formUtil.js'];widgetDep['paneSplitter']=['dhtmlSuite-paneSplitter.js','dhtmlSuite-paneSplitterModel.js','dhtmlSuite-dynamicContent.js','ajax.js'];widgetDep['menuBar']=['dhtmlSuite-menuBar.js','dhtmlSuite-menuItem.js','dhtmlSuite-menuModel.js'];widgetDep['windowWidget']=['dhtmlSuite-windowWidget.js','dhtmlSuite-resize.js','dhtmlSuite-dragDropSimple.js','ajax.js','dhtmlSuite-dynamicContent.js'];widgetDep['colorWidget']=['dhtmlSuite-colorWidgets.js','dhtmlSuite-colorUtil.js'];widgetDep['colorSlider']=['dhtmlSuite-colorWidgets.js','dhtmlSuite-colorUtil.js','dhtmlSuite-slider.js'];widgetDep['colorPalette']=['dhtmlSuite-colorWidgets.js','dhtmlSuite-colorUtil.js'];widgetDep['calendar']=['dhtmlSuite-calendar.js','dhtmlSuite-dragDropSimple.js'];widgetDep['dragDropTree']=['dhtmlSuite-dragDropTree.js'];widgetDep['slider']=['dhtmlSuite-slider.js'];widgetDep['dragDrop']=['dhtmlSuite-dragDrop.js'];widgetDep['imageEnlarger']=['dhtmlSuite-imageEnlarger.js','dhtmlSuite-dragDropSimple.js'];widgetDep['imageSelection']=['dhtmlSuite-imageSelection.js'];widgetDep['floatingGallery']=['dhtmlSuite-floatingGallery.js','dhtmlSuite-mediaModel.js'];widgetDep['contextMenu']=['dhtmlSuite-contextMenu.js','dhtmlSuite-menuBar.js','dhtmlSuite-menuItem.js','dhtmlSuite-menuModel.js'];widgetDep['dynamicContent']=['dhtmlSuite-dynamicContent.js','ajax.js'];widgetDep['textEdit']=['dhtmlSuite-textEdit.js','dhtmlSuite-textEditModel.js','dhtmlSuite-listModel.js'];widgetDep['listModel']=['dhtmlSuite-listModel.js'];widgetDep['resize']=['dhtmlSuite-resize.js'];widgetDep['dragDropSimple']=['dhtmlSuite-dragDropSimple.js'];widgetDep['dynamicTooltip']=['dhtmlSuite-dynamicTooltip.js','dhtmlSuite-dynamicContent.js','ajax.js'];widgetDep['modalMessage']=['dhtmlSuite-modalMessage.js','dhtmlSuite-dynamicContent.js','ajax.js'];widgetDep['tableWidget']=['dhtmlSuite-tableWidget.js','ajax.js'];widgetDep['progressBar']=['dhtmlSuite-progressBar.js'];widgetDep['tabView']=['dhtmlSuite-tabView.js','dhtmlSuite-dynamicContent.js','ajax.js'];widgetDep['infoPanel']=['dhtmlSuite-infoPanel.js','dhtmlSuite-dynamicContent.js','ajax.js'];widgetDep['form']=['dhtmlSuite-formUtil.js','dhtmlSuite-dynamicContent.js','ajax.js'];widgetDep['autoComplete']=['dhtmlSuite-autoComplete.js','ajax.js'];widgetDep['chainedSelect']=['dhtmlSuite-chainedSelect.js','ajax.js'];var depCache=new Object();DHTMLSuite.include=function(widget){if(!widgetDep[widget]){alert('Cannot find the files for widget '+widget+'. Please verify that the name is correct');return}
var files=widgetDep[widget];for(var no=0;no<files.length;no++){if(!depCache[files[no]]){document.write('<'+'script');document.write(' language="javascript"');document.write(' type="text/javascript"');document.write(' src="'+DHTML_SUITE_JS_FOLDER+files[no]+'">');document.write('</'+'script'+'>');depCache[files[no]]=true}}}
DHTMLSuite.discardElement=function(element){element=DHTMLSuite.commonObj.getEl(element);var gBin=document.getElementById('IELeakGBin');if(!gBin){gBin=document.createElement('DIV');gBin.id='IELeakGBin';gBin.style.display='none';document.body.appendChild(gBin)}
gBin.appendChild(element);gBin.innerHTML=''}
DHTMLSuite.createStandardObjects=function(){DHTMLSuite.clientInfoObj=new DHTMLSuite.clientInfo();DHTMLSuite.clientInfoObj.init();if(!DHTMLSuite.configObj){DHTMLSuite.configObj=new DHTMLSuite.config();DHTMLSuite.configObj.init()}
DHTMLSuite.commonObj=new DHTMLSuite.common();DHTMLSuite.variableStorage=new DHTMLSuite.globalVariableStorage();;DHTMLSuite.commonObj.init();DHTMLSuite.domQueryObj=new DHTMLSuite.domQuery();DHTMLSuite.commonObj.addEvent(window,'unload',function(){DHTMLSuite.commonObj.__clearMemoryGarbage()});standardObjectsCreated=true}
DHTMLSuite.config=function(){var imagePath;var cssPath;var defaultCssPath;var defaultImagePath}
DHTMLSuite.config.prototype={init:function(){this.imagePath=DHTML_SUITE_THEME_FOLDER+DHTML_SUITE_THEME+'/images/';this.cssPath=DHTML_SUITE_THEME_FOLDER+DHTML_SUITE_THEME+'/css/';this.defaultCssPath=this.cssPath;this.defaultImagePath=this.imagePath},setCssPath:function(newCssPath){this.cssPath=newCssPath},resetCssPath:function(){this.cssPath=this.defaultCssPath},resetImagePath:function(){this.imagePath=this.defaultImagePath},setImagePath:function(newImagePath){this.imagePath=newImagePath}}
DHTMLSuite.globalVariableStorage=function(){var menuBar_highlightedItems;this.menuBar_highlightedItems=new Array();var arrayDSObjects;var arrayOfDhtmlSuiteObjects;this.arrayDSObjects=new Array();this.arrayOfDhtmlSuiteObjects=this.arrayDSObjects;var ajaxObjects;this.ajaxObjects=new Array()}
DHTMLSuite.globalVariableStorage.prototype={}
DHTMLSuite.common=function(){var loadedCSSFiles;var cssCacheStatus;var eventEls;var isOkToSelect;this.okToSelect=true;this.cssCacheStatus=true;this.eventEls=new Array()}
DHTMLSuite.common.prototype={init:function(){this.loadedCSSFiles=new Array()},loadCSS:function(cssFile,prefixConfigPath){if(!prefixConfigPath&&prefixConfigPath!==false)prefixConfigPath=true;if(!this.loadedCSSFiles[cssFile]){this.loadedCSSFiles[cssFile]=true;var lt=document.createElement('LINK');if(!this.cssCacheStatus){if(cssFile.indexOf('?')>=0)cssFile=cssFile+'&';else cssFile=cssFile+'?';cssFile=cssFile+'rand='+Math.random()}
if(prefixConfigPath){lt.href=DHTMLSuite.configObj.cssPath+cssFile}else{lt.href=cssFile}
lt.rel='stylesheet';lt.media='screen';lt.type='text/css';document.getElementsByTagName('HEAD')[0].appendChild(lt)}},__setTextSelOk:function(okToSelect){this.okToSelect=okToSelect},__isTextSelOk:function(){return this.okToSelect},setCssCacheStatus:function(cssCacheStatus){this.cssCacheStatus=cssCacheStatus},getEl:function(elRef){if(typeof elRef=='string'){if(document.getElementById(elRef))return document.getElementById(elRef);if(document.forms[elRef])return document.forms[elRef];if(document[elRef])return document[elRef];if(window[elRef])return window[elRef]}
return elRef},isArray:function(el){if(el.constructor.toString().indexOf("Array")!=-1)return true;return false},getStyle:function(el,property){el=this.getEl(el);if(document.defaultView&&document.defaultView.getComputedStyle){var retVal=null;var comp=document.defaultView.getComputedStyle(el,'');if(comp){retVal=comp[property]}
return el.style[property]||retVal}
if(document.documentElement.currentStyle&&DHTMLSuite.clientInfoObj.isMSIE){var retVal=null;if(el.currentStyle)value=el.currentStyle[property];return(el.style[property]||retVal)}
return el.style[property]},

getLeftPos:function(el){
	if(document.getBoxObjectFor){
		if(el.tagName!='INPUT'&&el.tagName!='SELECT'&&el.tagName!='TEXTAREA')
			return document.getBoxObjectFor(el).x
	}
	var returnValue=el.offsetLeft;
	while((el=el.offsetParent)!=null){
		if(el.tagName!='HTML'){
			returnValue+= el.offsetLeft;
			if(document.all)returnValue+=el.clientLeft
		}
	}
	return returnValue
},

getTopPos:function(el){
	if(document.getBoxObjectFor){
		if(el.tagName!='INPUT'&&el.tagName!='SELECT'&&el.tagName!='TEXTAREA')
			return document.getBoxObjectFor(el).y
	}
	var returnValue=el.offsetTop;
	while((el=el.offsetParent)!=null){
		if(el.tagName!='HTML'){
			returnValue+=(el.offsetTop-el.scrollTop);
			if(document.all)returnValue+=el.clientTop
		}
	}
	return returnValue
},

sgetCookie:function(name){var start=document.cookie.indexOf(name+"=");var len=start+name.length+1;if((!start)&&(name!=document.cookie.substring(0,name.length)))return null;if(start==-1)return null;var end=document.cookie.indexOf(";",len);if(end==-1)end=document.cookie.length;return unescape(document.cookie.substring(len,end))},setCookie:function(name,value,expires,path,domain,secure){expires=expires*60*60*24*1000;var today=new Date();var expires_date=new Date(today.getTime()+(expires));var cookieString=name+"="+escape(value)+
((expires)?";expires="+expires_date.toGMTString():"")+
((path)?";path="+path:"")+
((domain)?";domain="+domain:"")+
((secure)?";secure":"");document.cookie=cookieString},deleteCookie:function(name,path,domain){if(this.getCookie(name))document.cookie=name+"="+
((path)?";path="+path:"")+
((domain)?";domain="+domain:"")+
";expires=Thu,01-Jan-1970 00:00:01 GMT"},cancelEvent:function(){return false},addEvent:function(obj,type,fn,suffix){if(!suffix)suffix='';if(obj.attachEvent){if(typeof DHTMLSuite_funcs[type+fn+suffix]!='function'){DHTMLSuite_funcs[type+fn+suffix]=function(){fn.apply(window.event.srcElement)};obj.attachEvent('on'+type,DHTMLSuite_funcs[type+fn+suffix])}
obj=null} else {obj.addEventListener(type,fn,false)}
this.__addEventEl(obj)},removeEvent:function(obj,type,fn,suffix){if(obj.detachEvent){obj.detachEvent('on'+type,DHTMLSuite_funcs[type+fn+suffix]);DHTMLSuite_funcs[type+fn+suffix]=null;obj=null} else {obj.removeEventListener(type,fn,false)}},__clearMemoryGarbage:function(){if(!DHTMLSuite.clientInfoObj.isMSIE)return;for(var no=0;no<DHTMLSuite.eventEls.length;no++){try{var el=DHTMLSuite.eventEls[no];el.onclick=null;el.onmousedown=null;el.onmousemove=null;el.onmouseout=null;el.onmouseover=null;el.onmouseup=null;el.onfocus=null;el.onblur=null;el.onkeydown=null;el.onkeypress=null;el.onkeyup=null;el.onselectstart=null;el.ondragstart=null;el.oncontextmenu=null;el.onscroll=null;el=null}catch(e){}}
for(var no in DHTMLSuite.variableStorage.arrayDSObjects){DHTMLSuite.variableStorage.arrayDSObjects[no]=null}
window.onbeforeunload=null;window.onunload=null;DHTMLSuite=null},__addEventEl:function(el){DHTMLSuite.eventEls[DHTMLSuite.eventEls.length]=el},getSrcElement:function(e){var el;if(e.target)el=e.target;else if(e.srcElement)el=e.srcElement;if(el.nodeType==3)
el=el.parentNode;return el},getKeyFromEvent:function(e){var code=this.getKeyCode(e);return String.fromCharCode(code)},getKeyCode:function(e){if(e.keyCode)code=e.keyCode;else if(e.which)code=e.which;return code},isObjectClicked:function(obj,e){var src=this.getSrcElement(e);var string=src.tagName+'('+src.className+')';if(src==obj)return true;while(src.parentNode&&src.tagName.toLowerCase()!='html'){src=src.parentNode;string=string+','+src.tagName+'('+src.className+')';if(src==obj)return true}
return false},getObjectByClassName:function(e,className){var src=this.getSrcElement(e);if(src.className==className)return src;while(src&&src.tagName.toLowerCase()!='html'){src=src.parentNode;if(src.className==className)return src}
return false},getObjectByAttribute:function(e,attribute){var src=this.getSrcElement(e);var att=src.getAttribute(attribute);if(!att)att=src[attribute];if(att)return src;while(src&&src.tagName.toLowerCase()!='html'){src=src.parentNode;var att=src.getAttribute('attribute');if(!att)att=src[attribute];if(att)return src}
return false},getUniqueId:function(){var no=Math.random()+'';no=no.replace('.','');var no2=Math.random()+'';no2=no2.replace('.','');return no+no2},getAssociativeArrayFromString:function(propertyString){if(!propertyString)return;var retArray=new Array();var items=propertyString.split(/,/g);for(var no=0;no<items.length;no++){var tokens=items[no].split(/:/);retArray[tokens[0]]=tokens[1]}
return retArray},correctPng:function(el){el=DHTMLSuite.commonObj.getEl(el);var img=el;var width=img.width;var height=img.height;var html='<span style="display:inline-block;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''+img.src+'\',sizingMethod=\'scale\');width:'+width+';height:'+height+'"></span>';img.outerHTML=html},__evaluateJs:function(obj){obj=this.getEl(obj);var scriptTags=obj.getElementsByTagName('SCRIPT');var string='';var jsCode='';for(var no=0;no<scriptTags.length;no++){if(scriptTags[no].src){var head=document.getElementsByTagName("head")[0];var scriptObj=document.createElement("script");scriptObj.setAttribute("type","text/javascript");scriptObj.setAttribute("src",scriptTags[no].src)}else{if(DHTMLSuite.clientInfoObj.isOpera){jsCode=jsCode+scriptTags[no].text+'\n'}
else
jsCode=jsCode+scriptTags[no].innerHTML}}
if(jsCode)this.__installScript(jsCode)},__installScript:function(script){try{if(!script)
return;if(window.execScript){window.execScript(script)
}else if(window.jQuery&&jQuery.browser.safari){window.setTimeout(script,0)}else{window.setTimeout(script,0)}}catch(e){}},__evaluateCss:function(obj){obj=this.getEl(obj);var cssTags=obj.getElementsByTagName('STYLE');var head=document.getElementsByTagName('HEAD')[0];for(var no=0;no<cssTags.length;no++){head.appendChild(cssTags[no])}}}
DHTMLSuite.clientInfo=function(){var browser;var isOpera;var isMSIE;var isOldMSIE;var isFirefox;var navigatorVersion;var isOldMSIE}
DHTMLSuite.clientInfo.prototype={init:function(){this.browser=navigator.userAgent;this.isOpera=(this.browser.toLowerCase().indexOf('opera')>=0)?true:false;this.isFirefox=(this.browser.toLowerCase().indexOf('firefox')>=0)?true:false;this.isMSIE=(this.browser.toLowerCase().indexOf('msie')>=0)?true:false;this.isOldMSIE=(this.browser.toLowerCase().match(/msie\s[0-6]/gi))?true:false;this.isSafari=(this.browser.toLowerCase().indexOf('safari')>=0)?true:false;this.navigatorVersion=navigator.appVersion.replace(/.*?MSIE\s(\d\.\d).*/g,'$1')/1;this.isOldMSIE=(this.isMSIE&&this.navigatorVersion<7)?true:false},getBrowserWidth:function(){if(self.innerWidth)return self.innerWidth;return document.documentElement.offsetWidth},getBrowserHeight:function(){if(self.innerHeight)return self.innerHeight;return document.documentElement.offsetHeight}}
DHTMLSuite.domQuery=function(){document.getElementsByClassName=this.getElementsByClassName;document.getElementsByAttribute=this.getElementsByAttribute}
DHTMLSuite.domQuery.prototype={}


/*e1*/
/*
 * Adapted By @Neyek
 * 
 **/

if(!window.DHTMLSuite)var DHTMLSuite=new Object();DHTMLSuite.calendarLanguageModel=function(languageCode){var monthArray;var monthArrayShort;var dayArray;var weekString;var todayString;var todayIsString;var timeString;this.monthArray=new Array();this.monthArrayShort=new Array();this.dayArray=new Array();if(!languageCode)languageCode='en';this.languageCode=languageCode;this.__setCalendarProperties()}

DHTMLSuite.calendarLanguageModel.prototype={
__setCalendarProperties:function(){
switch(this.languageCode){
case "fi": 
this.monthArray =['Tammikuu','Helmikuu','Maaliskuu','Huhtikuu','Toukokuu','Kes&auml;kuu','Hein&auml;kuu','Elokuu','Syyskuu','Lokakuu','Marraskuu','Joulukuu'];
this.monthArrayShort=['Tam','Hel','Maa','Huh','Tou','Kes','Hei','Elo','Syy','Lok','Mar','Jou'];
this.dayArray=['Maa','Tii','Kes','Tor','Per','Lau','Sun'];
this.weekString='Viikko';
this.todayIsString='T&auml;n&auml;&auml;n on';
this.todayString='T&auml;n&auml;&auml;n';
this.timeString='Kello';
break;
case "ge":
this.monthArray=['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'];
this.monthArrayShort=['Jan','Feb','Mar','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Dez'];
this.dayArray=['Mon','Die','Mit','Don','Fre','Sam','Son'];
this.weekString='Woche';
this.todayIsString='Heute';
this.todayString='Heute';
this.timeString='';
break;
case "no":
this.monthArray=['Januar','Februar','Mars','April','Mai','Juni','Juli','August','September','Oktober','November','Desember'];
this.monthArrayShort=['Jan','Feb','Mar','Apr','Mai','Jun','Jul','Aug','Sep','Okt','Nov','Des'];
this.dayArray=['Man','Tir','Ons','Tor','Fre','L&oslash;r','S&oslash;n'];
this.weekString='Uke';
this.todayIsString='Dagen i dag er';
this.todayString='I dag';
this.timeString='Tid';
break;
case "nl":
this.monthArray=['Januari','Februari','Maart','April','Mei','Juni','Juli','Augustus','September','Oktober','November','December'];
this.monthArrayShort=['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sep','Okt','Nov','Dec'];
this.dayArray=['Ma','Di','Wo','Do','Vr','Za','Zo'];
this.weekString='Week';
this.todayIsString='Vandaag';
this.todayString='Vandaag';
this.timeString='';
break;
case "es": 
this.monthArray=['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
this.monthArrayShort =['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
this.dayArray=['Lun','Mar','Mie','Jue','Vie','Sab','Dom'];
this.weekString='Semana';
this.todayIsString='Hoy es';
this.todayString='Hoy';
this.timeString='';
break;
case "pt-br":  
this.monthArray=['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
this.monthArrayShort=['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'];
this.dayArray=['Seg','Ter','Qua','Qui','Sex','S&aacute;b','Dom'];
this.weekString='Sem.';
this.todayIsString='Hoje &eacute;';
this.todayString='Hoje';
this.timeString='';
break;
case "fr":  
this.monthArray=['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
this.monthArrayShort=['Jan','Fev','Mar','Avr','Mai','Jun','Jul','Aou','Sep','Oct','Nov','Dec'];
this.dayArray=['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'];
this.weekString='Sem';
this.todayIsString="Aujourd'hui";
this.todayString='Aujourd';
this.timeString='';
break;
case "da": 
this.monthArray=['januar','februar','marts','april','maj','juni','juli','august','september','oktober','november','december'];
this.monthArrayShort=['jan','feb','mar','apr','maj','jun','jul','aug','sep','okt','nov','dec'];
this.dayArray=['man','tirs','ons','tors','fre','l&oslash;r','s&oslash;n'];
this.weekString='Uge';
this.todayIsString='I dag er den';
this.todayString='I dag';
this.timeString='Tid';
break;
case "it":
this.monthArray=['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'];
this.monthArrayShort=['Gen','Feb','Mar','Apr','Mag','Giu','Lugl','Ago','Set','Ott','Nov','Dic'];
this.dayArray=['Lun','Mar','Mer','Gio','Ven','Sab','Dom'];
this.weekString='Sett';
this.todayIsString='Oggi &egrave;il';
this.todayString='Oggi &egrave;il';
this.timeString='';
break;
case "sv":
this.monthArray=['Januari','Februari','Mars','April','Maj','Juni','Juli','Augusti','September','Oktober','November','December'];
this.monthArrayShort=['Jan','Feb','Mar','Apr','Maj','Jun','Jul','Aug','Sep','Okt','Nov','Dec'];
this.dayArray=['M&aring;n','Tis','Ons','Tor','Fre','L&ouml;r','S&ouml;n'];
this.weekString='Vecka';
this.todayIsString='Idag &auml;r det den';
this.todayString='Idag &auml;r det den';
this.timeString='';
break;
 
default:
this.monthArray=['January','February','March','April','May','June','July','August','September','October','November','December'];
this.monthArrayShort=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
this.dayArray=['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
this.weekString='Week';
this.todayIsString='';
this.todayString='Today';
this.timeString='Time';
break}}}
DHTMLSuite.calendarModel=function(inputArray){var initialDay;var initialMonth;var initialYear;var initialHour;var initialMinute;var displayedDay;var displayedMonth;var displayedYear;var displayedMinute;var displayedHour;var languageCode;var languageModel;var invalidDateRange;var weekStartsOnMonday;this.weekStartsOnMonday=true;this.languageCode='en';this.invalidDateRange=new Array();this.__createDefaultModel(inputArray)}
DHTMLSuite.calendarModel.prototype=
{setCallbackFunctionOnMonthChange:function(functionName){this.callbackFunctionOnMonthChange=functionName},addInvalidDateRange:function(fromDateAsArray,toDateAsArray){var index=this.invalidDateRange.length;this.invalidDateRange[index]=new Object();if(fromDateAsArray){fromDateAsArray.day=fromDateAsArray.day+'';fromDateAsArray.month=fromDateAsArray.month+'';fromDateAsArray.year=fromDateAsArray.year+'';if(!fromDateAsArray.month)fromDateAsArray.month=fromDateAsArray.month='1';if(!fromDateAsArray.day)fromDateAsArray.day=fromDateAsArray.day='1';if(fromDateAsArray.day.length==1)fromDateAsArray.day='0'+fromDateAsArray.day;if(fromDateAsArray.month.length==1)fromDateAsArray.month='0'+fromDateAsArray.month;this.invalidDateRange[index].fromDate=fromDateAsArray.year+fromDateAsArray.month+fromDateAsArray.day}else{this.invalidDateRange[index].fromDate=false}
if(toDateAsArray){toDateAsArray.day=toDateAsArray.day+'';toDateAsArray.month=toDateAsArray.month+'';toDateAsArray.year=toDateAsArray.year+'';if(!toDateAsArray.month)toDateAsArray.month=toDateAsArray.month='1';if(!toDateAsArray.day)toDateAsArray.day=toDateAsArray.day='1';if(toDateAsArray.day.length==1)toDateAsArray.day='0'+toDateAsArray.day;if(toDateAsArray.month.length==1)toDateAsArray.month='0'+toDateAsArray.month;this.invalidDateRange[index].toDate=toDateAsArray.year+toDateAsArray.month+toDateAsArray.day}else{this.invalidDateRange[index].toDate=false}},isDateWithinValidRange:function(inputDate){if(this.invalidDateRange.length==0)return true;var month=inputDate.month+'';if(month.length==1)month='0'+month;var day=inputDate.day+'';if(day.length==1)day='0'+day;var dateToCheck=inputDate.year+month+day;for(var no=0;no<this.invalidDateRange.length;no++){if(!this.invalidDateRange[no].fromDate&&this.invalidDateRange[no].toDate>=dateToCheck)return false;if(!this.invalidDateRange[no].toDate&&this.invalidDateRange[no].fromDate<=dateToCheck)return false;if(this.invalidDateRange[no].fromDate<=dateToCheck&&this.invalidDateRange[no].toDate>=dateToCheck)return false}
return true},setInitialDateFromInput:function(inputReference,format){if(inputReference.value.length>0){if(!format.match(/^[0-9]*?$/gi)){var items=inputReference.value.split(/[^0-9]/gi);var positionArray=new Object();positionArray.m=format.indexOf('mm');if(positionArray.m==-1)positionArray.m=format.indexOf('m');positionArray.d=format.indexOf('dd');if(positionArray.d==-1)positionArray.d=format.indexOf('d');positionArray.y=format.indexOf('yyyy');positionArray.h=format.indexOf('hh');positionArray.i=format.indexOf('ii');this.initialHour='00';this.initialMinute='00';var elements=['y','m','d','h','i'];var properties=['initialYear','initialMonth','initialDay','initialHour','initialMinute'];var propertyLength=[4,2,2,2,2];for(var i=0;i<elements.length;i++){if(positionArray[elements[i]]>=0){this[properties[i]]=inputReference.value.substr(positionArray[elements[i]],propertyLength[i])/1}}}else{var monthPos=format.indexOf('mm');this.initialMonth=inputReference.value.substr(monthPos,2)/1;var yearPos=format.indexOf('yyyy');this.initialYear=inputReference.value.substr(yearPos,4);var dayPos=format.indexOf('dd');tmpDay=inputReference.value.substr(dayPos,2);this.initialDay=tmpDay;var hourPos=format.indexOf('hh');if(hourPos>=0){tmpHour=inputReference.value.substr(hourPos,2);this.initialHour=tmpHour}else{this.initialHour='00'}
var minutePos=format.indexOf('ii');if(minutePos>=0){tmpMinute=inputReference.value.substr(minutePos,2);this.initialMinute=tmpMinute}else{this.initialMinute='00'}}}
this.__setDisplayedDateToInitialData()},__setDisplayedDateToInitialData:function(){this.displayedYear=this.initialYear;this.displayedMonth=this.initialMonth;this.displayedDay=this.initialDay;this.displayedHour=this.initialHour;this.displayedMinute=this.initialMinute},__calendarSortItems:function(a,b){return a/1-b/1},setWeekStartsOnMonday:function(weekStartsOnMonday){this.weekStartsOnMonday=weekStartsOnMonday},setLanguageCode:function(languageCode){this.languageModel=new DHTMLSuite.calendarLanguageModel(languageCode)},__isLeapYear:function(inputYear){if(inputYear%400==0||(inputYear%4==0&&inputYear%100!=0))return true;return false},getWeekStartsOnMonday:function(){return this.weekStartsOnMonday},__createDefaultModel:function(inputArray){var d=new Date();this.initialYear=d.getFullYear();this.initialMonth=d.getMonth()+1;this.initialDay=d.getDate();this.initialHour=d.getHours();if(inputArray){if(inputArray.initialYear)this.initialYear=inputArray.initialYear;if(inputArray.initialMonth)this.initialMonth=inputArray.initialMonth;if(inputArray.initialDay)this.initialDay=inputArray.initialDay;if(inputArray.initialHour)this.initialHour=inputArray.initialHour;if(inputArray.initialMinute)this.initialMinute=inputArray.initialMinute;if(inputArray.languageCode)this.languageCode=inputArray.languageCode}
this.displayedYear=this.initialYear;this.displayedMonth=this.initialMonth;this.displayedDay=this.initialDay;this.displayedHour=this.initialHour;this.displayedMinute=this.initialMinute;this.languageModel=new DHTMLSuite.calendarLanguageModel()},__getDisplayedDay:function(){return this.displayedDay},__getDisplayedHourWithLeadingZeros:function(){var retVal=this.__getDisplayedHour()+'';if(retVal.length==1)retVal='0'+retVal;return retVal},__getDisplayedMinuteWithLeadingZeros:function(){var retVal=this.__getDisplayedMinute()+'';if(retVal.length==1)retVal='0'+retVal;return retVal},__getDisplayedDayWithLeadingZeros:function(){var retVal=this.__getDisplayedDay()+'';if(retVal.length==1)retVal='0'+retVal;return retVal},__getDisplayedMonthNumberWithLeadingZeros:function(){var retVal=this.__getDisplayedMonthNumber()+'';if(retVal.length==1)retVal='0'+retVal;return retVal},__getDisplayedYear:function(){return this.displayedYear},__getDisplayedHour:function(){if(!this.displayedHour)this.displayedHour=0;return this.displayedHour},__getDisplayedMinute:function(){if(!this.displayedMinute)this.displayedMinute=0;return this.displayedMinute},__getDisplayedMonthNumber:function(){return this.displayedMonth},__getInitialDay:function(){return this.initialDay},__getInitialYear:function(){return this.initialYear},__getInitialMonthNumber:function(){return this.initialMonth},__getMonthNameByMonthNumber:function(monthNumber){return this.languageModel.monthArray[monthNumber-1]},__moveOneYearBack:function(){this.displayedYear--},__moveOneYearForward:function(){this.displayedYear++},__moveOneMonthBack:function(){this.displayedMonth--;if(this.displayedMonth<1){this.displayedMonth=12;this.displayedYear--}},__moveOneMonthForward:function(){this.displayedMonth++;if(this.displayedMonth>12){this.displayedMonth=1;this.displayedYear++}},__setDisplayedYear:function(year){var success=year!=this.displayedYear;this.displayedYear=year;return success
},__setDisplayedMonth:function(month){var success=month!=this.displayedMonth;this.displayedMonth=month;return success},__setDisplayedDay:function(day){this.displayedDay=day},__setDisplayedHour:function(hour){this.displayedHour=hour/1},__setDisplayedMinute:function(minute){this.displayedMinute=minute/1},__getPreviousYearAndMonthAsArray:function(){var month=this.displayedMonth-1;var year=this.displayedYear;if(month==0){month=12;year=year-1}
var retArray=[year,month];return retArray},__getNumberOfDaysInCurrentDisplayedMonth:function(){return this.__getNumberOfDaysInAMonthByMonthAndYear(this.displayedYear,this.displayedMonth)},__getNumberOfDaysInAMonthByMonthAndYear:function(year,month){var daysInMonthArray=[31,28,31,30,31,30,31,31,30,31,30,31];var daysInMonth=daysInMonthArray[month-1];if(daysInMonth==28){if(this.__isLeapYear(year))daysInMonth=29}
return daysInMonth/1},__getStringWeek:function(){return this.languageModel.weekString},__getDaysMondayToSunday:function(){return this.languageModel.dayArray},__getDaysSundayToSaturday:function(){var retArray=this.languageModel.dayArray.concat();var lastDay=new Array(retArray[retArray.length-1]);retArray.pop();return lastDay.concat(retArray)},__getWeekNumberFromDayMonthAndYear:function(year,month,day){day=day/1;year=year/1;month=month/1;if(!this.weekStartsOnMonday)return this.__getWeekNumberFromDayMonthAndYear_S(year,month,day);var a=Math.floor((14-(month))/12);var y=year+4800-a;var m=(month)+(12*a)-3;var jd=day+Math.floor(((153*m)+2)/5)+
(365*y)+Math.floor(y/4)- Math.floor(y/100)+
Math.floor(y/400)- 32045;var d4=(jd+31741-(jd%7))%146097%36524%1461;var L=Math.floor(d4/1460);var d1=((d4-L)%365)+L;NumberOfWeek=Math.floor(d1/7)+1;return NumberOfWeek},__getWeekNumberFromDayMonthAndYear_S:function(year,month,day){month--;now=Date.UTC(year,month,day+1,0,0,0);var firstDay=new Date();firstDay.setYear(year);firstDay.setMonth(0);firstDay.setDate(1);then=Date.UTC(year,0,1,0,0,0);var Compensation=firstDay.getDay();if(Compensation > 3)Compensation-= 4;else Compensation+= 3;NumberOfWeek= Math.round((((now-then)/86400000)+Compensation)/7);return NumberOfWeek},__getDayNumberFirstDayInYear:function(year){var d=new Date();d.setFullYear(year);d.setDate(1);d.setMonth(0);return d.getDay()},__getRemainingDaysInPreviousMonthAsArray:function(){var d=new Date();d.setFullYear(this.displayedYear);d.setDate(1);d.setMonth(this.displayedMonth-1);var dayStartOfMonth=d.getDay();if(this.weekStartsOnMonday){if(dayStartOfMonth==0)dayStartOfMonth=7;dayStartOfMonth--}
var previousMonthArray=this.__getPreviousYearAndMonthAsArray();var daysInPreviousMonth=this.__getNumberOfDaysInAMonthByMonthAndYear(previousMonthArray[0],previousMonthArray[1]);var returnArray=new Array();for(var no=0;no<dayStartOfMonth;no++){returnArray[returnArray.length]=daysInPreviousMonth-dayStartOfMonth+no+1}
return returnArray},__getMonthNames:function(){return this.languageModel.monthArray},__getTodayAsString:function(){return this.languageModel.todayString},__getTimeAsString:function(){return this.languageModel.timeString}}

DHTMLSuite.calendar=function(propertyArray){
	var id;
	var divElement;
	var divElContent;
	var divElHeading;
	var divElNavBar;
	var divElMonthView;
	var divElMonthNInHead;
	var divElYearInHeading;
	var divElBtnPreviousYear;
	var divElBtnNextYear;
	var divElBtnPrvMonth;
	var divElBtnNextMonth;
	var divElYearDropdown;
	var divElYearDropdownParentYears;
	var divElHourDropdownParentHours;
	var divElHourDropdown;
	var divElMinuteDropdownParent;
	var divElMinuteDropdown;
	var divElTodayInNavBar;
	var divElHrInTimeBar;
	var divElMinInTimeBar;
	var divElTimeStringInTimeBar;
	var iframeEl;
	var iframeElDropDowns;
	var calendarModelReference;
	var objectIndex;
	var targetReference;
	var layoutCSS;
	var isDragable;
	var referenceToDragDropObject;
	var scrollInYearDropDownActive;
	var scrollInHourDropDownActive;
	var scrollInMinuteDropDownActive;
	var yearDropDownOffsetInYear;
	var hourDropDownOffsetInHour;
	var minuteDropDownOffsetInHour;
	var displayCloseButton;
	var displayNavigationBar;
	var displayTodaysDateInNavigationBar;
	var displayTimeBar;
	var posRefToHtmlEl;
	var positioningOffsetXInPixels;
	var positioningOffsetYInPixels;
	var htmlElementReferences;
	var minuteDropDownInterval;
	var numberOfRowsInMinuteDropDown;
	var numberOfRowsInHourDropDown;
	var numberOfRowsInYearDropDown;
	
	this.e = null;
	this.displayTimeBar=false;
	this.minuteDropDownInterval=5;
	this.htmlElementReferences=new Object();
	this.posRefToHtmlEl=false;
	this.displayCloseButton=true;
	this.displayNavigationBar=true;
	this.displayTodaysDateInNavigationBar=true;
	this.yearDropDownOffsetInYear=0;
	this.hourDropDownOffsetInHour=0;
	this.minuteDropDownOffsetInHour=0;
	this.minuteDropDownOffsetInMinute=0;
	this.layoutCSS='calendar.css';
	this.isDragable=false;
	this.scrollInYearDropDownActive=false;
	this.scrollInHourDropDownActive=false;
	this.scrollInMinuteDropDownActive=false;
	this.numberOfRowsInMinuteDropDown=10;
	this.numberOfRowsInHourDropDown=10;
	this.numberOfRowsInYearDropDown=10;

	var callbackFunctionOnDayClick;
    var callbackFunctionOnClose;
    var callbackFunctionOnMonthChange;
    var dateOfToday;
    
    this.dateOfToday=new Date();
    try{
    	if(!standardObjectsCreated)DHTMLSuite.createStandardObjects()
    }catch(e){alert('Include the dhtmlSuite-common.js file')}

this.objectIndex=DHTMLSuite.variableStorage.arrayDSObjects.length;DHTMLSuite.variableStorage.arrayDSObjects[this.objectIndex]=this;if(propertyArray)this.__setInitialData(propertyArray)}

DHTMLSuite.calendar.prototype={
	setCallbackFunctionOnDayClick:function(functionName){
		this.callbackFunctionOnDayClick=functionName
	},setCallbackFunctionOnMonthChange:function(functionName){if(!this.calendarModelReference){this.calendarModelReference=new DHTMLSuite.calendarModel()}
this.callbackFunctionOnMonthChange=functionName},setCallbackFunctionOnClose:function(functionName){this.callbackFunctionOnClose=functionName},setCalendarModelReference:function(calendarModelReference){this.calendarModelReference=calendarModelReference},

//(eInput, 0, eInput. offsetHeight+2)
setCalendarPositionByHTMLElement:function(refToHtmlEl,offsetXInPx,offsetYInPx){
	
	refToHtmlEl = DHTMLSuite.commonObj.getEl(refToHtmlEl);
	this.posRefToHtmlEl=refToHtmlEl;
	
	if(!offsetXInPx)
		offsetXInPx=0;
	if(!offsetYInPx)
		offsetYInPx=0;
	
	//alert(offsetXInPx+' '+offsetYInPx);  //yek
	this.positioningOffsetXInPixels=offsetXInPx;
	this.positioningOffsetYInPixels=offsetYInPx
},

addHtmlElementReference:function(key,referenceToHtmlEl){
	referenceToHtmlEl=DHTMLSuite.commonObj.getEl(referenceToHtmlEl);
	if(key){
		this.htmlElementReferences[key]=referenceToHtmlEl
	}
},
	
getHtmlElementReferences:function(){return this.htmlElementReferences},setDisplayCloseButton:function(displayCloseButton){this.displayCloseButton=displayCloseButton},setTargetReference:function(targetRef){targetRef=DHTMLSuite.commonObj.getEl(targetRef);this.targetReference=targetRef},setIsDragable:function(isDragable){this.isDragable=isDragable},resetViewDisplayedMonth:function(){if(!this.divElement)return;if(!this.calendarModelReference){this.calendarModelReference=new DHTMLSuite.calendarModel()}
this.calendarModelReference.__setDisplayedDateToInitialData();this.__populateCalHeading();this.__populateMonthView()},setLayoutCss:function(nameOfCssFile){this.layoutCSS=nameOfCssFile},__init:function(){

if(!this.divElement){

/*DHTMLSuite.commonObj.loadCSS(this.layoutCSS); neyek*/

if(!this.calendarModelReference){this.calendarModelReference=new DHTMLSuite.calendarModel()}
this.__createMainHtmlEls();this.__createHeadingElements();this.__createNavigationBar();this.__populateNavigationBar();this.__populateCalHeading();this.__createCalMonthView();this.__populateMonthView();this.__createTimeBar();this.__populateTimeBar();this.__createDropDownYears();this.__populateDropDownYears();this.__positionDropDownYears();this.__createDropDownMonth();this.__populateDropDownMonths();this.__positionDropDownMonths();this.__createDropDownHours();this.__populateDropDownHours();this.__positionDropDownHours();this.__createDropDownMinutes();this.__populateDropDownMinutes();this.__positionDropDownMinutes();this.__addEvents()}else{this.divElement.style.display='block';this.__populateCalHeading();this.__populateMonthView()}
this.__resizePrimaryiframeEl()},

display:function(){
	if(!this.divElement)
		this.__init();
	this.__positionCalendar();
	this.divElement.style.display='block';
	this.__resizePrimaryiframeEl()
},
hide:function(){
	if(this.__handleCalendarCallBack('calendarClose')===false)
		return false;
	this.divElement.style.display='none';
	this.divElYearDropdown.style.display='none';
	this.divElMonthDropdown.style.display='none'
}
,isVisible:function(){if(!this.divElement)return false;return this.divElement.style.display=='block'?true:false},setInitialDateFromInput:function(inputReference,format){if(!this.calendarModelReference){this.calendarModelReference=new DHTMLSuite.calendarModel()}
this.calendarModelReference.setInitialDateFromInput(inputReference,format)},setDisplayedYear:function(year){var success=this.calendarModelReference.__setDisplayedYear(year);this.__populateCalHeading();this.__populateMonthView();if(success)this.__handleCalendarCallBack('monthChange')},setDisplayedMonth:function(month){var success=this.calendarModelReference.__setDisplayedMonth(month);this.__populateCalHeading();this.__populateMonthView();if(success)this.__handleCalendarCallBack('monthChange')},setDisplayedHour:function(hour){this.calendarModelReference.__setDisplayedHour(hour);this.__populateTimeBar()},setDisplayedMinute:function(minute){this.calendarModelReference.__setDisplayedMinute(minute);this.__populateTimeBar()},__createDropDownMonth:function(){this.divElMonthDropdown=document.createElement('DIV');this.divElMonthDropdown.style.display='none';this.divElMonthDropdown.className='DHTMLSuite_calendar_monthDropDown';document.body.appendChild(this.divElMonthDropdown)},__populateDropDownMonths:function(){this.divElMonthDropdown.innerHTML='';var ind=this.objectIndex;var months=this.calendarModelReference.__getMonthNames();for(var no=0;no<months.length;no++){var div=document.createElement('DIV');div.className='DHTMLSuite_calendar_dropDownAMonth';if((no+1)==this.calendarModelReference.__getDisplayedMonthNumber())div.className='DHTMLSuite_calendar_yearDropDownCurrentMonth';div.innerHTML=months[no];div.id='DHTMLSuite_calendarMonthPicker'+(no+1);div.onmouseover=this.__mouseoverMonthInDropDown;div.onmouseout=this.__mouseoutMonthInDropDown;div.onclick=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__setMonthFromDropdown(e)}
this.divElMonthDropdown.appendChild(div);DHTMLSuite.commonObj.__addEventEl(div)}},__createDropDownYears:function(){this.divElYearDropdown=document.createElement('DIV');this.divElYearDropdown.style.display='none';this.divElYearDropdown.className='DHTMLSuite_calendar_yearDropDown';document.body.appendChild(this.divElYearDropdown)},__createDropDownHours:function(){this.divElHourDropdown=document.createElement('DIV');this.divElHourDropdown.style.display='none';this.divElHourDropdown.className='DHTMLSuite_calendar_hourDropDown';document.body.appendChild(this.divElHourDropdown)},__createDropDownMinutes:function(){this.divElMinuteDropdown=document.createElement('DIV');this.divElMinuteDropdown.style.display='none';this.divElMinuteDropdown.className='DHTMLSuite_calendar_minuteDropDown';document.body.appendChild(this.divElMinuteDropdown)},__populateDropDownMinutes:function(){var ind=this.objectIndex;this.divElMinuteDropdown.innerHTML='';var divPrevious=document.createElement('DIV');divPrevious.className='DHTMLSuite_calendar_dropDown_arrowUp';divPrevious.onmouseover=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__mouseoverUpAndDownArrowsInDropDownMinutes(e)} ;divPrevious.onmouseout =function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__mouseoutUpAndDownArrowsInDropDownMinutes(e)} ;this.divElMinuteDropdown.appendChild(divPrevious);DHTMLSuite.commonObj.__addEventEl(divPrevious);this.divElMinuteDropdownParent=document.createElement('DIV');this.divElMinuteDropdown.appendChild(this.divElMinuteDropdownParent);this.__populateMinutesInsideDropDownMinutes(this.divElMinuteDropdownParent);var divNext=document.createElement('DIV');divNext.className='DHTMLSuite_calendar_dropDown_arrowDown';divNext.innerHTML='<span></span>';divNext.onmouseover=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__mouseoverUpAndDownArrowsInDropDownMinutes(e)} ;divNext.onmouseout =function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__mouseoutUpAndDownArrowsInDropDownMinutes(e)} ;DHTMLSuite.commonObj.__addEventEl(divNext);this.divElMinuteDropdown.appendChild(divNext);if(60/this.minuteDropDownInterval< this.numberOfRowsInMinuteDropDown){divPrevious.style.display='none';divNext.style.display='none'}},__populateMinutesInsideDropDownMinutes:function(){var ind=this.objectIndex;this.divElMinuteDropdownParent.innerHTML='';if(60/this.minuteDropDownInterval< this.numberOfRowsInMinuteDropDown){startMinute=0}else{var startMinute=Math.max(0,(this.calendarModelReference.__getDisplayedMinute()-Math.round(this.numberOfRowsInMinuteDropDown/2)));startMinute+=(this.minuteDropDownOffsetInMinute*this.minuteDropDownInterval)
if(startMinute<0){startMinute+=this.minuteDropDownInterval;this.minuteDropDownOffsetInMinute++}
if(startMinute+(this.numberOfRowsInMinuteDropDown*this.minuteDropDownInterval)>60){/*start minute in drop down+number of records shown*interval larger than 60-> adjust it*/
startMinute-=this.minuteDropDownInterval;this.minuteDropDownOffsetInMinute--}}
for(var no=startMinute;no<Math.min(60,startMinute+this.numberOfRowsInMinuteDropDown*(this.minuteDropDownInterval));no+=this.minuteDropDownInterval){var div=document.createElement('DIV');div.className='DHTMLSuite_calendar_dropDownAMinute';if(no==this.calendarModelReference.__getDisplayedMinute())div.className='DHTMLSuite_calendar_minuteDropDownCurrentMinute';var prefix="";if(no<10)prefix="0";div.innerHTML=prefix+no;div.onmouseover=this.__mouseoverMinuteInDropDown;div.onmouseout=this.__mouseoutMinuteInDropDown;div.onclick=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__setMinuteFromDropdown(e)}
this.divElMinuteDropdownParent.appendChild(div);DHTMLSuite.commonObj.__addEventEl(div)}},__populateDropDownHours:function(){var ind=this.objectIndex;this.divElHourDropdown.innerHTML='';var div=document.createElement('DIV');div.className='DHTMLSuite_calendar_dropDown_arrowUp';div.onmouseover=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__mouseoverUpAndDownArrowsInDropDownHours(e)} ;div.onmouseout =function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__mouseoutUpAndDownArrowsInDropDownHours(e)} ;this.divElHourDropdown.appendChild(div);DHTMLSuite.commonObj.__addEventEl(div);this.divElHourDropdownParentHours=document.createElement('DIV');this.divElHourDropdown.appendChild(this.divElHourDropdownParentHours);this.__populateHoursInsideDropDownHours(this.divElHourDropdownParentHours);var div=document.createElement('DIV');div.className='DHTMLSuite_calendar_dropDown_arrowDown';div.innerHTML='<span></span>';div.onmouseover=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__mouseoverUpAndDownArrowsInDropDownHours(e)} ;div.onmouseout =function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__mouseoutUpAndDownArrowsInDropDownHours(e)} ;DHTMLSuite.commonObj.__addEventEl(div);this.divElHourDropdown.appendChild(div)},__populateHoursInsideDropDownHours:function(){var ind=this.objectIndex;this.divElHourDropdownParentHours.innerHTML='';var startHour=Math.max(0,(this.calendarModelReference.__getDisplayedHour()-Math.round(this.numberOfRowsInHourDropDown/2)));startHour=Math.min(14,startHour);if((startHour+this.hourDropDownOffsetInHour+this.numberOfRowsInHourDropDown)>24){this.hourDropDownOffsetInHour=(24-startHour-this.numberOfRowsInHourDropDown)}
if((startHour+this.hourDropDownOffsetInHour)<0){this.hourDropDownOffsetInHour=startHour*-1}
startHour+=this.hourDropDownOffsetInHour;if(startHour<0)startHour=0;if(startHour>(24-this.numberOfRowsInHourDropDown))startHour=(24-this.numberOfRowsInHourDropDown);for(var no=startHour;no<startHour+this.numberOfRowsInHourDropDown;no++){var div=document.createElement('DIV');div.className='DHTMLSuite_calendar_dropDownAnHour';if(no==this.calendarModelReference.__getDisplayedHour())div.className='DHTMLSuite_calendar_hourDropDownCurrentHour';var prefix="";if(no<10)prefix="0";div.innerHTML=prefix+no;div.onmouseover=this.__mouseoverHourInDropDown;div.onmouseout=this.__mouseoutHourInDropDown;div.onclick=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__setHourFromDropdown(e)}
this.divElHourDropdownParentHours.appendChild(div);DHTMLSuite.commonObj.__addEventEl(div)}},__populateDropDownYears:function(){var ind=this.objectIndex;this.divElYearDropdown.innerHTML='';var div=document.createElement('DIV');div.className='DHTMLSuite_calendar_dropDown_arrowUp';div.onmouseover=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__mouseoverUpAndDownArrowsInDropDownYears(e)} ;div.onmouseout =function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__mouseoutUpAndDownArrowsInDropDownYears(e)} ;this.divElYearDropdown.appendChild(div);DHTMLSuite.commonObj.__addEventEl(div);this.divElYearDropdownParentYears=document.createElement('DIV');this.divElYearDropdown.appendChild(this.divElYearDropdownParentYears);this.__populateYearsInsideDropDownYears(this.divElYearDropdownParentYears);var div=document.createElement('DIV');div.className='DHTMLSuite_calendar_dropDown_arrowDown';div.innerHTML='<span></span>';div.onmouseover=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__mouseoverUpAndDownArrowsInDropDownYears(e)} ;div.onmouseout =function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__mouseoutUpAndDownArrowsInDropDownYears(e)} ;DHTMLSuite.commonObj.__addEventEl(div);this.divElYearDropdown.appendChild(div)},__populateYearsInsideDropDownYears:function(divElementToPopulate){var ind=this.objectIndex;this.divElYearDropdownParentYears.innerHTML='';var startYear=this.calendarModelReference.__getDisplayedYear()-5+this.yearDropDownOffsetInYear;for(var no=startYear;no<startYear+this.numberOfRowsInYearDropDown;no++){var div=document.createElement('DIV');div.className='DHTMLSuite_calendar_dropDownAYear';if(no==this.calendarModelReference.__getDisplayedYear())div.className='DHTMLSuite_calendar_yearDropDownCurrentYear';div.innerHTML=no;div.onmouseover=this.__mouseoverYearInDropDown;div.onmouseout=this.__mouseoutYearInDropDown;div.onclick=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__setYearFromDropdown(e)}
this.divElYearDropdownParentYears.appendChild(div);DHTMLSuite.commonObj.__addEventEl(div)}},__positionDropDownMonths:function(){this.divElMonthDropdown.style.left=DHTMLSuite.commonObj.getLeftPos(this.divElMonthNInHead)+'px';this.divElMonthDropdown.style.top=(DHTMLSuite.commonObj.getTopPos(this.divElMonthNInHead)+this.divElMonthNInHead.offsetHeight)+'px';if(this.iframeElDropDowns){var st=this.iframeElDropDowns.style;st.left=this.divElMonthDropdown.style.left;st.top=this.divElMonthDropdown.style.top;st.width=(this.divElMonthDropdown.clientWidth)+'px';st.height=this.divElMonthDropdown.clientHeight+'px';st.display=this.divElMonthDropdown.style.display}},__positionDropDownYears:function(){this.divElYearDropdown.style.left=DHTMLSuite.commonObj.getLeftPos(this.divElYearInHeading)+'px';this.divElYearDropdown.style.top=(DHTMLSuite.commonObj.getTopPos(this.divElYearInHeading)+this.divElYearInHeading.offsetHeight)+'px';if(this.iframeElDropDowns){var st=this.iframeElDropDowns.style;st.left=this.divElYearDropdown.style.left;st.top=this.divElYearDropdown.style.top;st.width=(this.divElYearDropdown.clientWidth)+'px';st.height=this.divElYearDropdown.clientHeight+'px';st.display=this.divElYearDropdown.style.display}},__positionDropDownHours:function(){this.divElHourDropdown.style.left=DHTMLSuite.commonObj.getLeftPos(this.divElHrInTimeBar)+'px';this.divElHourDropdown.style.top=(DHTMLSuite.commonObj.getTopPos(this.divElHrInTimeBar)+this.divElHrInTimeBar.offsetHeight)+'px';if(this.iframeElDropDowns){var st=this.iframeElDropDowns.style;st.left=this.divElHourDropdown.style.left;st.top=this.divElHourDropdown.style.top;st.width=(this.divElHourDropdown.clientWidth)+'px';st.height=this.divElHourDropdown.clientHeight+'px';st.display=this.divElHourDropdown.style.display}},__positionDropDownMinutes:function(){this.divElMinuteDropdown.style.left=DHTMLSuite.commonObj.getLeftPos(this.divElMinInTimeBar)+'px';this.divElMinuteDropdown.style.top=(DHTMLSuite.commonObj.getTopPos(this.divElMinInTimeBar)+this.divElMinInTimeBar.offsetHeight)+'px';if(this.iframeElDropDowns){var st=this.iframeElDropDowns.style;st.left=this.divElMinuteDropdown.style.left;st.top=this.divElMinuteDropdown.style.top;st.width=(this.divElMinuteDropdown.clientWidth)+'px';st.height=this.divElMinuteDropdown.clientHeight+'px';st.display=this.divElMinuteDropdown.style.display}},__setMonthFromDropdown:function(e){if(document.all)e=event;var src=DHTMLSuite.commonObj.getSrcElement(e);this.__showHideDropDownBoxMonth();this.setDisplayedMonth(src.id.replace(/[^0-9]/gi,''))},__setYearFromDropdown:function(e){if(document.all)e=event;var src=DHTMLSuite.commonObj.getSrcElement(e);this.__showHideDropDownBoxYear();this.setDisplayedYear(src.innerHTML)},__setHourFromDropdown:function(e){if(document.all)e=event;var src=DHTMLSuite.commonObj.getSrcElement(e);this.__showHideDropDownBoxHour();this.setDisplayedHour(src.innerHTML)},__setMinuteFromDropdown:function(e){if(document.all)e=event;var src=DHTMLSuite.commonObj.getSrcElement(e);this.__showHideDropDownBoxMinute();this.setDisplayedMinute(src.innerHTML)},__autoHideDropDownBoxes:function(e){if(document.all)e=event;var src=DHTMLSuite.commonObj.getSrcElement(e);if(src.className.indexOf('MonthAndYear')>=0||src.className.indexOf('HourAndMinute')>=0){if(DHTMLSuite.commonObj.isObjectClicked(this.divElement,e))return}
this.__showHideDropDownBoxMonth('none');this.__showHideDropDownBoxYear('none');this.__showHideDropDownBoxHour('none');this.__showHideDropDownBoxMinute('none')},__showHideDropDownBoxMonth:function(forcedDisplayAttribute){if(!forcedDisplayAttribute){this.__showHideDropDownBoxYear('none');this.__showHideDropDownBoxHour('none')}
if(forcedDisplayAttribute){this.divElMonthDropdown.style.display=forcedDisplayAttribute}else{this.divElMonthDropdown.style.display=(this.divElMonthDropdown.style.display=='block'?'none':'block')}
this.__populateDropDownMonths();this.__positionDropDownMonths()},__showHideDropDownBoxYear:function(forcedDisplayAttribute){if(!forcedDisplayAttribute){this.__showHideDropDownBoxMonth('none');this.__showHideDropDownBoxHour('none');this.__showHideDropDownBoxMinute('none')}
if(forcedDisplayAttribute){this.divElYearDropdown.style.display=forcedDisplayAttribute}else{this.divElYearDropdown.style.display=(this.divElYearDropdown.style.display=='block'?'none':'block')}
if(this.divElYearDropdown.style.display=='none'){this.yearDropDownOffsetInYear=0}else{this.__populateDropDownYears()}
this.__positionDropDownYears()},__showHideDropDownBoxHour:function(forcedDisplayAttribute){if(!forcedDisplayAttribute){this.__showHideDropDownBoxYear('none');this.__showHideDropDownBoxMonth('none');this.__showHideDropDownBoxMinute('none')}
if(forcedDisplayAttribute){this.divElHourDropdown.style.display=forcedDisplayAttribute}else{this.divElHourDropdown.style.display=(this.divElHourDropdown.style.display=='block'?'none':'block')}
if(this.divElHourDropdown.style.display=='none'){this.hourDropDownOffsetInHour=0}else{this.__populateDropDownHours()}
this.__positionDropDownHours()},__showHideDropDownBoxMinute:function(forcedDisplayAttribute){if(!forcedDisplayAttribute){this.__showHideDropDownBoxYear('none');this.__showHideDropDownBoxMonth('none');this.__showHideDropDownBoxHour('none')}
if(forcedDisplayAttribute){this.divElMinuteDropdown.style.display=forcedDisplayAttribute}else{this.divElMinuteDropdown.style.display=(this.divElMinuteDropdown.style.display=='block'?'none':'block')}
if(this.divElMinuteDropdown.style.display=='none'){this.minuteDropDownOffsetInMinute=0}else{this.__populateDropDownMinutes()}
this.__positionDropDownMinutes()},__createMainHtmlEls:function(){this.divElement=document.createElement('DIV');this.divElement.className='DHTMLSuite_calendar';this.divElContent=document.createElement('DIV');this.divElement.appendChild(this.divElContent);this.divElContent.className='DHTMLSuite_calendarContent';if(this.targetReference)this.targetReference.appendChild(this.divElement);else document.body.appendChild(this.divElement);if(this.isDragable){try{this.referenceToDragDropObject=new DHTMLSuite.dragDropSimple({elementReference: this.divElement })}catch(e){alert('Include DHTMLSuite-dragDropSimple.js for the drag feature')}}
if(DHTMLSuite.clientInfoObj.isMSIE&&DHTMLSuite.clientInfoObj.navigatorVersion<8){this.iframeEl=document.createElement('<iframe src="about:blank" frameborder="0">');this.iframeEl.className='DHTMLSuite_calendar_iframe';this.iframeEl.style.left='0px';this.iframeEl.style.top='0px';this.iframeEl.style.position='absolute';this.divElement.appendChild(this.iframeEl);this.iframeElDropDowns=document.createElement('<iframe src="about:blank" frameborder="0">');this.iframeElDropDowns.className='DHTMLSuite_calendar_iframe';this.iframeElDropDowns.style.display='none';document.body.appendChild(this.iframeElDropDowns)}},__createHeadingElements:function(){this.divElHeading=document.createElement('DIV');if(this.isDragable){this.referenceToDragDropObject.addDragHandle(this.divElHeading);this.divElHeading.style.cursor='move'}
this.divElHeading.className='DHTMLSuite_calendarHeading';this.divElContent.appendChild(this.divElHeading);this.divElHeading.style.position='relative';this.divElClose=document.createElement('DIV');this.divElClose.innerHTML='<span></span>';

this.divElClose.className='DHTMLSuite_calendarCloseButton';

this.divElHeading.appendChild(this.divElClose);

if(!this.displayCloseButton) this.divElClose.style.display='none';

this.divElHeadingTxt=document.createElement('DIV');this.divElHeadingTxt.className='DHTMLSuite_calendarHeadingTxt';if(DHTMLSuite.clientInfoObj.isMSIE){var table=document.createElement('<TABLE cellpadding="0" cellspacing="0" border="0">')}else{var table=document.createElement('TABLE');table.setAttribute('cellpadding',0);table.setAttribute('cellspacing',0);table.setAttribute('border',0)}
table.style.margin='0 auto';var tbody=document.createElement('TBODY');table.appendChild(tbody);this.divElHeadingTxt.appendChild(table);var row=tbody.insertRow(0);var cell=row.insertCell(-1);this.divElMonthNInHead=document.createElement('DIV');this.divElMonthNInHead.className='DHTMLSuite_calendarHeaderMonthAndYear';cell.appendChild(this.divElMonthNInHead);var cell=row.insertCell(-1);var span=document.createElement('SPAN');span.innerHTML=',';cell.appendChild(span);var cell=row.insertCell(-1);this.divElYearInHeading=document.createElement('DIV');this.divElYearInHeading.className='DHTMLSuite_calendarHeaderMonthAndYear';cell.appendChild(this.divElYearInHeading);this.divElHeading.appendChild(this.divElHeadingTxt)},__createNavigationBar:function(){this.divElNavBar=document.createElement('DIV');this.divElNavBar.className='DHTMLSuite_calendar_navigationBar';this.divElContent.appendChild(this.divElNavBar);this.divElBtnPreviousYear=document.createElement('DIV');this.divElBtnPreviousYear.className='DHTMLSuite_calendar_btnPreviousYear';this.divElBtnPreviousYear.innerHTML='<span></span>';this.divElNavBar.appendChild(this.divElBtnPreviousYear);this.divElBtnNextYear=document.createElement('DIV');this.divElBtnNextYear.className='DHTMLSuite_calendar_btnNextYear';this.divElBtnNextYear.innerHTML='<span></span>';this.divElNavBar.appendChild(this.divElBtnNextYear);this.divElBtnPrvMonth=document.createElement('DIV');this.divElBtnPrvMonth.className='DHTMLSuite_calendar_btnPreviousMonth';this.divElBtnPrvMonth.innerHTML='<span></span>';this.divElNavBar.appendChild(this.divElBtnPrvMonth);this.divElBtnNextMonth=document.createElement('DIV');this.divElBtnNextMonth.className='DHTMLSuite_calendar_btnNextMonth';this.divElBtnNextMonth.innerHTML='<span></span>';this.divElNavBar.appendChild(this.divElBtnNextMonth);this.divElTodayInNavBar=document.createElement('DIV');this.divElTodayInNavBar.className='DHTMLSuite_calendar_navigationBarToday';this.divElNavBar.appendChild(this.divElTodayInNavBar);if(!this.displayNavigationBar)this.divElNavBar.style.display='none';if(!this.displayTodaysDateInNavigationBar)this.divElTodayInNavBar.style.display='none'},__populateNavigationBar:function(){var ind=this.objectIndex;this.divElTodayInNavBar.innerHTML='';var span=document.createElement('SPAN');span.innerHTML=this.calendarModelReference.__getTodayAsString();span.onclick=function(){DHTMLSuite.variableStorage.arrayDSObjects[ind].__displayMonthOfToday()}
this.divElTodayInNavBar.appendChild(span);DHTMLSuite.commonObj.__addEventEl(span)},__createCalMonthView:function(){this.divElMonthView=document.createElement('DIV');this.divElMonthView.className='DHTMLSuite_calendar_monthView';this.divElContent.appendChild(this.divElMonthView)},__populateMonthView:function(){var ind=this.objectIndex;this.divElMonthView.innerHTML='';var modelRef=this.calendarModelReference;if(DHTMLSuite.clientInfoObj.isMSIE){var table=document.createElement('<TABLE cellpadding="1" cellspacing="0" border="0" width="100%">')}else{var table=document.createElement('TABLE');table.setAttribute('cellpadding',1);table.setAttribute('cellspacing',0);table.setAttribute('border',0);table.width='100%'}
var tbody=document.createElement('TBODY');table.appendChild(tbody);this.divElMonthView.appendChild(table);var row=tbody.insertRow(-1);row.className='DHTMLSuite_calendar_monthView_headerRow';var cell=row.insertCell(-1);cell.className='DHTMLSuite_calendar_monthView_firstColumn';cell.innerHTML=modelRef.__getStringWeek();if(modelRef.getWeekStartsOnMonday()){var days=modelRef.__getDaysMondayToSunday()}else{var days=modelRef.__getDaysSundayToSaturday()}
for(var no=0;no<days.length;no++){var cell=row.insertCell(-1);cell.innerHTML=days[no];cell.className='DHTMLSuite_calendar_monthView_headerCell';if(modelRef.getWeekStartsOnMonday()&&no==6){cell.className='DHTMLSuite_calendar_monthView_headerSunday'}
if(!modelRef.getWeekStartsOnMonday()&&no==0){cell.className='DHTMLSuite_calendar_monthView_headerSunday'}}
var row=tbody.insertRow(-1);var cell=row.insertCell(-1);cell.className='DHTMLSuite_calendar_monthView_firstColumn';var week=modelRef.__getWeekNumberFromDayMonthAndYear(modelRef.__getDisplayedYear(),modelRef.__getDisplayedMonthNumber(),1);cell.innerHTML=week>0?week:53;var daysRemainingInPreviousMonth=modelRef.__getRemainingDaysInPreviousMonthAsArray();for(var no=0;no<daysRemainingInPreviousMonth.length;no++){var cell=row.insertCell(-1);cell.innerHTML=daysRemainingInPreviousMonth[no];cell.className='DHTMLSuite_calendar_monthView_daysInOtherMonths'}
var daysInCurrentMonth=modelRef.__getNumberOfDaysInCurrentDisplayedMonth();var cellCounter=daysRemainingInPreviousMonth.length+1;for(var no=1;no<=daysInCurrentMonth;no++){var cell=row.insertCell(-1);cell.innerHTML=no;cell.className='DHTMLSuite_calendar_monthView_daysInThisMonth';DHTMLSuite.commonObj.__addEventEl(cell);if(cellCounter%7==0&&modelRef.getWeekStartsOnMonday()){cell.className='DHTMLSuite_calendar_monthView_sundayInThisMonth'}
if(cellCounter%7==1&&!modelRef.getWeekStartsOnMonday()){cell.className='DHTMLSuite_calendar_monthView_sundayInThisMonth'}
if(no==modelRef.__getInitialDay()&&modelRef.__getDisplayedYear()==modelRef.__getInitialYear()&&modelRef.__getDisplayedMonthNumber()==modelRef.__getInitialMonthNumber()){cell.className='DHTMLSuite_calendar_monthView_initialDate'}
if(!modelRef.isDateWithinValidRange({year:modelRef.__getDisplayedYear(),month:modelRef.__getDisplayedMonthNumber(),day:no})){cell.className='DHTMLSuite_calendar_monthView_invalidDate'}else{cell.onmousedown=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__mousedownOnDayInCalendar(e)}
cell.onmouseover=this.__mouseoverCalendarDay;cell.onmouseout=this.__mouseoutCalendarDay;DHTMLSuite.commonObj.__addEventEl(cell)}
if(no==this.dateOfToday.getDate()&&modelRef.__getDisplayedYear()==this.dateOfToday.getFullYear()&&modelRef.__getDisplayedMonthNumber()==(this.dateOfToday.getMonth()+1)){cell.className='DHTMLSuite_calendar_monthView_currentDate'}
if(cellCounter%7==0&&no<daysInCurrentMonth){var row=tbody.insertRow(-1);var cell=row.insertCell(-1)
cell.className='DHTMLSuite_calendar_monthView_firstColumn';week++
cell.innerHTML=week}
cellCounter++}
if((cellCounter-1)%7>0){var dayCounter=1;for(var no=(cellCounter-1)%7;no<7;no++){var cell=row.insertCell(-1);cell.innerHTML=dayCounter;cell.className='DHTMLSuite_calendar_monthView_daysInOtherMonths';dayCounter++}}},__createTimeBar:function(){this.divElTimeBar=document.createElement('DIV');this.divElTimeBar.className='DHTMLSuite_calendar_timeBar';this.divElContent.appendChild(this.divElTimeBar);if(DHTMLSuite.clientInfoObj.isMSIE){var table=document.createElement('<TABLE cellpadding="0" cellspacing="0" border="0">')}else{var table=document.createElement('TABLE');table.setAttribute('cellpadding',0);table.setAttribute('cellspacing',0);table.setAttribute('border',0)}
table.style.margin='0 auto';this.divElTimeBar.appendChild(table);var row=table.insertRow(0);var cell=row.insertCell(-1);this.divElHrInTimeBar=document.createElement('DIV');this.divElHrInTimeBar.className='DHTMLSuite_calendar_timeBarHourAndMinute';cell.appendChild(this.divElHrInTimeBar);var cell=row.insertCell(-1);

var span=document.createElement('SPAN');span.innerHTML=':';
span.className = 'DHTMLSuite_calendar_dropDownAYear';
cell.appendChild(span);var cell=row.insertCell(-1);this.divElMinInTimeBar=document.createElement('DIV');this.divElMinInTimeBar.className='DHTMLSuite_calendar_timeBarHourAndMinute';cell.appendChild(this.divElMinInTimeBar);this.divElTimeStringInTimeBar=document.createElement('DIV');this.divElTimeStringInTimeBar.className='DHTMLSuite_calendarTimeBarTimeString';this.divElTimeBar.appendChild(this.divElTimeStringInTimeBar);if(!this.displayTimeBar)this.divElTimeBar.style.display='none'},__populateTimeBar:function(){this.divElHrInTimeBar.innerHTML=this.calendarModelReference.__getDisplayedHourWithLeadingZeros();this.divElMinInTimeBar.innerHTML=this.calendarModelReference.__getDisplayedMinuteWithLeadingZeros();this.divElTimeStringInTimeBar.innerHTML=this.calendarModelReference.__getTimeAsString()+':'},__populateCalHeading:function(){this.divElMonthNInHead.innerHTML=this.calendarModelReference.__getMonthNameByMonthNumber(this.calendarModelReference.__getDisplayedMonthNumber());this.divElYearInHeading.innerHTML=this.calendarModelReference.__getDisplayedYear()},__mousedownOnDayInCalendar:function(e){if(document.all)e=event;var src=DHTMLSuite.commonObj.getSrcElement(e);this.calendarModelReference.__setDisplayedDay(src.innerHTML);this.__handleCalendarCallBack('dayClick')},

__handleCalendarCallBack:function(action){
		var callbackString='';
		var addparams = '';
		
		switch(action){
			case 'dayClick':
				if (this.callbackFunctionOnDayClick) {
					callbackString = this.callbackFunctionOnDayClick.func;
					if (typeof(this.callbackFunctionOnDayClick.params) != 'undefined'){
						for(i=0; i<this.callbackFunctionOnDayClick.params.length; i++){
							addparams += ',\''+this.callbackFunctionOnDayClick.params[i]+'\'';
						}
					}
				}	
			break;
			case "monthChange":
				if(this.callbackFunctionOnMonthChange)callbackString=this.callbackFunctionOnMonthChange;break;
			case "calendarClose":if(this.callbackFunctionOnClose)callbackString=this.callbackFunctionOnClose;break
		}
		if(callbackString){
			callbackString=callbackString+
		'({'
		+' year:'+this.calendarModelReference.__getDisplayedYear()
		+',month:"'+this.calendarModelReference.__getDisplayedMonthNumberWithLeadingZeros()+'"'
		+',day:"'+this.calendarModelReference.__getDisplayedDayWithLeadingZeros()+'"'
		+',hour:"'+this.calendarModelReference.__getDisplayedHourWithLeadingZeros()+'"'
		+',minute:"'+this.calendarModelReference.__getDisplayedMinuteWithLeadingZeros()+'"'
		+',calendarRef:this'
		callbackString=callbackString+'}'+addparams+')'}
		
		if(callbackString)
			return this.__evaluateCallBackString(callbackString)
}


,__evaluateCallBackString:function(callbackString){try{return eval(callbackString)}catch(e){alert('Could not excute call back function '+callbackString+'\n'+e.message)}},__displayMonthOfToday:function(){var d=new Date();var month=d.getMonth()+1;var year=d.getFullYear();this.setDisplayedYear(year);this.setDisplayedMonth(month)},__moveOneYearBack:function(){this.calendarModelReference.__moveOneYearBack();this.__populateCalHeading();this.__populateMonthView();this.__handleCalendarCallBack('monthChange')},__moveOneYearForward:function(){this.calendarModelReference.__moveOneYearForward();this.__populateCalHeading();this.__populateMonthView();this.__handleCalendarCallBack('monthChange')},__moveOneMonthBack:function(){this.calendarModelReference.__moveOneMonthBack();this.__populateCalHeading();this.__populateMonthView();this.__handleCalendarCallBack('monthChange')},__moveOneMonthForward:function(){this.calendarModelReference.__moveOneMonthForward();this.__populateCalHeading();this.__populateMonthView();this.__handleCalendarCallBack('monthChange')},__addEvents:function(){var ind=this.objectIndex;this.divElClose.onmouseover=this.__mouseoverCalendarButton;this.divElClose.onmouseout=this.__mouseoutCalendarButton;

/* close envent @neyek*/
this.divElClose.onclick=function(e){
	DHTMLSuite.variableStorage.arrayDSObjects[ind].hide()
	G_CALENDAR_CURRENT_OBJ = null;
}

DHTMLSuite.commonObj.__addEventEl(this.divElClose);this.divElBtnPreviousYear.onmouseover=this.__mouseoverCalendarButton;this.divElBtnPreviousYear.onmouseout=this.__mouseoutCalendarButton;this.divElBtnPreviousYear.onclick=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__moveOneYearBack()}
DHTMLSuite.commonObj.__addEventEl(this.divElBtnPreviousYear);this.divElBtnNextYear.onmouseover=this.__mouseoverCalendarButton;this.divElBtnNextYear.onmouseout=this.__mouseoutCalendarButton;this.divElBtnNextYear.onclick=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__moveOneYearForward()}
DHTMLSuite.commonObj.__addEventEl(this.divElBtnNextYear);this.divElBtnPrvMonth.onmouseover=this.__mouseoverCalendarButton;this.divElBtnPrvMonth.onmouseout=this.__mouseoutCalendarButton;this.divElBtnPrvMonth.onclick=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__moveOneMonthBack()}
DHTMLSuite.commonObj.__addEventEl(this.divElBtnPrvMonth);this.divElBtnNextMonth.onmouseover=this.__mouseoverCalendarButton;this.divElBtnNextMonth.onmouseout=this.__mouseoutCalendarButton;this.divElBtnNextMonth.onclick=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__moveOneMonthForward()}
DHTMLSuite.commonObj.__addEventEl(this.divElBtnNextMonth);this.divElYearInHeading.onmouseover=this.__mouseoverMonthAndYear;this.divElYearInHeading.onmouseout=this.__mouseoutMonthAndYear;this.divElYearInHeading.onclick=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__showHideDropDownBoxYear()}
DHTMLSuite.commonObj.__addEventEl(this.divElYearInHeading);this.divElMonthNInHead.onmouseover=this.__mouseoverMonthAndYear;this.divElMonthNInHead.onmouseout=this.__mouseoutMonthAndYear;this.divElMonthNInHead.onclick=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__showHideDropDownBoxMonth()}
DHTMLSuite.commonObj.__addEventEl(this.divElMonthNInHead);this.divElHrInTimeBar.onmouseover=this.__mouseoverHourAndMinute;this.divElHrInTimeBar.onmouseout=this.__mouseoutHourAndMinute;this.divElHrInTimeBar.onclick=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__showHideDropDownBoxHour()}
DHTMLSuite.commonObj.__addEventEl(this.divElHrInTimeBar);this.divElMinInTimeBar.onmouseover=this.__mouseoverHourAndMinute;this.divElMinInTimeBar.onmouseout=this.__mouseoutHourAndMinute;this.divElMinInTimeBar.onclick=function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__showHideDropDownBoxMinute()}
DHTMLSuite.commonObj.__addEventEl(this.divElMinInTimeBar);this.divElHeading.onselectstart=function(){return false};DHTMLSuite.commonObj.__addEventEl(this.divElHeading);DHTMLSuite.commonObj.addEvent(document.documentElement,'click',function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__autoHideDropDownBoxes(e)},ind+'')},__resizePrimaryiframeEl:function(){if(!this.iframeEl)return;this.iframeEl.style.width=this.divElement.clientWidth+'px';this.iframeEl.style.height=this.divElement.clientHeight+'px'},__scrollInYearDropDown:function(scrollDirection){if(!this.scrollInYearDropDownActive)return;var ind=this.objectIndex;this.yearDropDownOffsetInYear+=scrollDirection;this.__populateYearsInsideDropDownYears();setTimeout('DHTMLSuite.variableStorage.arrayDSObjects['+ind+'].__scrollInYearDropDown('+scrollDirection+')',150)},__mouseoverUpAndDownArrowsInDropDownYears:function(e){var ind=this.objectIndex;if(document.all)e=event;var src=DHTMLSuite.commonObj.getSrcElement(e);var scrollDirection=(src.className.toLowerCase().indexOf('up')>=0?-1:1);src.className=src.className+' DHTMLSuite_calendarDropDown_dropDownArrowOver';this.scrollInYearDropDownActive=true;setTimeout('DHTMLSuite.variableStorage.arrayDSObjects['+ind+'].__scrollInYearDropDown('+scrollDirection+')',100)},__mouseoutUpAndDownArrowsInDropDownYears:function(e){if(document.all)e=event;var src=DHTMLSuite.commonObj.getSrcElement(e);src.className=src.className.replace(' DHTMLSuite_calendarDropDown_dropDownArrowOver','');this.scrollInYearDropDownActive=false},__scrollInHourDropDown:function(scrollDirection){if(!this.scrollInHourDropDownActive)return;var ind=this.objectIndex;this.hourDropDownOffsetInHour+=scrollDirection;this.__populateHoursInsideDropDownHours();setTimeout('DHTMLSuite.variableStorage.arrayDSObjects['+ind+'].__scrollInHourDropDown('+scrollDirection+')',150)},__mouseoverUpAndDownArrowsInDropDownHours:function(e){var ind=this.objectIndex;if(document.all)e=event;var src=DHTMLSuite.commonObj.getSrcElement(e);var scrollDirection=(src.className.toLowerCase().indexOf('up')>=0?-1:1);src.className=src.className+' DHTMLSuite_calendarDropDown_dropDownArrowOver';this.scrollInHourDropDownActive=true;setTimeout('DHTMLSuite.variableStorage.arrayDSObjects['+ind+'].__scrollInHourDropDown('+scrollDirection+')',100)},__mouseoutUpAndDownArrowsInDropDownHours:function(e){if(document.all)e=event;var src=DHTMLSuite.commonObj.getSrcElement(e);src.className=src.className.replace(' DHTMLSuite_calendarDropDown_dropDownArrowOver','');this.scrollInHourDropDownActive=false},__scrollInMinuteDropDown:function(scrollDirection){if(!this.scrollInMinuteDropDownActive)return;var ind=this.objectIndex;this.minuteDropDownOffsetInMinute+=scrollDirection;this.__populateMinutesInsideDropDownMinutes();setTimeout('DHTMLSuite.variableStorage.arrayDSObjects['+ind+'].__scrollInMinuteDropDown('+scrollDirection+')',150)},__mouseoverUpAndDownArrowsInDropDownMinutes:function(e){var ind=this.objectIndex;if(document.all)e=event;var src=DHTMLSuite.commonObj.getSrcElement(e);var scrollDirection=(src.className.toLowerCase().indexOf('up')>=0?-1:1);src.className=src.className+' DHTMLSuite_calendarDropDown_dropDownArrowOver';this.scrollInMinuteDropDownActive=true;setTimeout('DHTMLSuite.variableStorage.arrayDSObjects['+ind+'].__scrollInMinuteDropDown('+scrollDirection+')',100)},__mouseoutUpAndDownArrowsInDropDownMinutes:function(e){if(document.all)e=event;var src=DHTMLSuite.commonObj.getSrcElement(e);src.className=src.className.replace(' DHTMLSuite_calendarDropDown_dropDownArrowOver','');this.scrollInMinuteDropDownActive=false},__mouseoverYearInDropDown:function(){this.className=this.className+' DHTMLSuite_calendar_dropdownAYearOver'},__mouseoutYearInDropDown:function(){this.className=this.className.replace(' DHTMLSuite_calendar_dropdownAYearOver','')},__mouseoverHourInDropDown:function(){this.className=this.className+' DHTMLSuite_calendar_dropdownAnHourOver'},__mouseoutHourInDropDown:function(){this.className=this.className.replace(' DHTMLSuite_calendar_dropdownAnHourOver','')},__mouseoverMinuteInDropDown:function(){this.className=this.className+' DHTMLSuite_calendar_dropdownAMinuteOver'},__mouseoutMinuteInDropDown:function(){this.className=this.className.replace(' DHTMLSuite_calendar_dropdownAMinuteOver','')},__mouseoverMonthInDropDown:function(){this.className=this.className+' DHTMLSuite_calendar_dropdownAMonthOver'},__mouseoutMonthInDropDown:function(){this.className=this.className.replace(' DHTMLSuite_calendar_dropdownAMonthOver','')},__mouseoverCalendarDay:function(){this.className=this.className+' DHTMLSuite_calendarDayOver'},__mouseoutCalendarDay:function(){this.className=this.className.replace(' DHTMLSuite_calendarDayOver','')},__mouseoverCalendarButton:function(){this.className=this.className+' DHTMLSuite_calendarButtonOver'},__mouseoutCalendarButton:function(){this.className=this.className.replace(' DHTMLSuite_calendarButtonOver','')},__mouseoverMonthAndYear:function(){this.className=this.className+' DHTMLSuite_calendarHeaderMonthAndYearOver'},__mouseoutMonthAndYear:function(){this.className=this.className.replace(' DHTMLSuite_calendarHeaderMonthAndYearOver','')},__mouseoverHourAndMinute:function(){this.className=this.className+' DHTMLSuite_calendarTimeBarHourAndMinuteOver'},

__mouseoutHourAndMinute:function(){
	this.className=this.className.replace(' DHTMLSuite_calendarTimeBarHourAndMinuteOver','')
},
/**
 * Function fixed by NEYEK,.. 
 * The original routines fails when whe have a control on the botom of screen with vertical scroll
 * Author: Erik A. Ortiz <erik@colosa.com>
 */
__positionCalendar:function(){
	if(!this.posRefToHtmlEl)return;
	if(this.divElement.parentNode!=document.body)document.body.appendChild(this.divElement);
	
	//alert(this.divElement.tagName);
	this.divElement.style.position='absolute';
	
	var x, xX, yY, y;
	if (self.pageYOffset) // all except Explorers
	{
		x = self.pageXOffset; // not used in event code
		y = self.pageYOffset; // not used in event code
	} else if(document.documentElement
	&& document.documentElement.scrollTop) // Explorer 6 Strict
	{
		x = document.documentElement.scrollLeft;
		y = document.documentElement.scrollTop;
	}
	else if (document.body) // all other Explorers
	{
		x = document.body.scrollLeft;
		y = document.body.scrollTop;
	}
	xX = (document.all) ? x + this.e.clientX : this.e.pageX;
	yY = (document.all) ? y + this.e.clientY : this.e.pageY;
	
	//alert(xX+' -+- '+yY);
	
	this.divElement.style.left= (xX)+'px'; //(DHTMLSuite.commonObj.getLeftPos(this.posRefToHtmlEl)+this.positioningOffsetXInPixels)+'px';
	//alert(this.divElement.style.left);
	this.divElement.style.top= (yY)+'px'; //(DHTMLSuite.commonObj.getTopPos(this.posRefToHtmlEl)+this.positioningOffsetYInPixels)+'px'
	//alert(this.divElement.style.top);
},
	
__setInitialData:function(props){if(props.id)this.id=props.id;if(props.targetReference)this.targetReference=props.targetReference;if(props.calendarModelReference)this.calendarModelReference=props.calendarModelReference;

if(props.callbackFunctionOnDayClick)this.callbackFunctionOnDayClick=props.callbackFunctionOnDayClick;
if(props.callbackFunctionOnMonthChange)this.callbackFunctionOnMonthChange=props.callbackFunctionOnMonthChange;if(props.callbackFunctionOnClose)this.callbackFunctionOnClose=props.callbackFunctionOnClose;if(props.displayCloseButton||props.displayCloseButton===false)this.displayCloseButton=props.displayCloseButton;if(props.displayNavigationBar||props.displayNavigationBar===false)this.displayNavigationBar=props.displayNavigationBar;if(props.displayTodaysDateInNavigationBar||props.displayTodaysDateInNavigationBar===false)this.displayTodaysDateInNavigationBar=props.displayTodaysDateInNavigationBar;if(props.minuteDropDownInterval)this.minuteDropDownInterval=props.minuteDropDownInterval;if(props.numberOfRowsInHourDropDown)this.numberOfRowsInHourDropDown=props.numberOfRowsInHourDropDown;if(props.numberOfRowsInMinuteDropDown)this.numberOfRowsInHourDropDown=props.numberOfRowsInMinuteDropDown;if(props.numberOfRowsInYearDropDown)this.numberOfRowsInYearDropDown=props.numberOfRowsInYearDropDown;if(props.isDragable||props.isDragable===false)this.isDragable=props.isDragable;if(props.displayTimeBar||props.displayTimeBar===false)this.displayTimeBar=props.displayTimeBar}}


/*e3*/
if(!window.DHTMLSuite)var DHTMLSuite=new Object();var DHTMLSuite_dragDropSimple_curZIndex=100000;var DHTMLSuite_dragDropSimple_curObjIndex=false;DHTMLSuite.dragDropSimple=function(propertyArray){var divElement;var dragTimer;var cloneNode;this.cloneNode=true;var callbackOnAfterDrag;var callbackOnBeforeDrag;var callbackOnDuringDrag;var mouse_x;var mouse_y;var positionSet;var dragHandle;var allowMoveX;var allowMoveY;var maxY;var minY;var minX;var maxX;var initialXPos;var initialYPos;this.positionSet=false;this.dragHandle=new Array();var initOffsetX;var initOffsetY;this.allowMoveX=true;this.allowMoveY=true;this.maxY=false;this.maxX=false;this.minX=false;this.minY=false;this.callbackOnAfterDrag=false;this.callbackOnBeforeDrag=false;this.dragStatus=-1;try{if(!standardObjectsCreated)DHTMLSuite.createStandardObjects()}catch(e){alert('Include the dhtmlSuite-common.js file')}
var objectIndex;this.objectIndex=DHTMLSuite.variableStorage.arrayDSObjects.length;DHTMLSuite.variableStorage.arrayDSObjects[this.objectIndex]=this;this.__setInitProps(propertyArray);this.__init()}
DHTMLSuite.dragDropSimple.prototype={__setInitProps:function(props){if(props.cloneNode===false||props.cloneNode)this.cloneNode=props.cloneNode;if(props.allowMoveX===false||props.allowMoveX)this.allowMoveX=props.allowMoveX;if(props.allowMoveY===false||props.allowMoveY)this.allowMoveY=props.allowMoveY;if(props.minY||props.minY===0)this.minY=props.minY;if(props.maxY||props.maxY===0)this.maxY=props.maxY;if(props.minX||props.minX===0)this.minX=props.minX;if(props.maxX||props.maxX===0)this.maxX=props.maxX;if(!props.initOffsetX)props.initOffsetX=0;if(!props.initOffsetY)props.initOffsetY=0;this.initOffsetX=props.initOffsetX;this.initOffsetY=props.initOffsetY;if(props.callbackOnBeforeDrag)this.callbackOnBeforeDrag=props.callbackOnBeforeDrag;if(props.callbackOnAfterDrag)this.callbackOnAfterDrag=props.callbackOnAfterDrag;if(props.callbackOnDuringDrag)this.callbackOnDuringDrag=props.callbackOnDuringDrag;props.elementReference=DHTMLSuite.commonObj.getEl(props.elementReference);this.divElement=props.elementReference;this.initialXPos=DHTMLSuite.commonObj.getLeftPos(this.divElement);this.initialYPos=DHTMLSuite.commonObj.getTopPos(this.divElement);if(props.dragHandle)this.dragHandle[this.dragHandle.length]=DHTMLSuite.commonObj.getEl(props.dragHandle)
},__init:function(){var ind=this.objectIndex;this.divElement.objectIndex=ind;this.divElement.setAttribute('objectIndex',ind);this.divElement.style.padding='0px';if(this.allowMoveX){this.divElement.style.left=(DHTMLSuite.commonObj.getLeftPos(this.divElement)+this.initOffsetX)+'px'}
if(this.allowMoveY){this.divElement.style.top=(DHTMLSuite.commonObj.getTopPos(this.divElement)+this.initOffsetY)+'px'}
this.divElement.style.position='absolute';this.divElement.style.margin='0px';if(this.divElement.style.zIndex&&this.divElement.style.zIndex/1>DHTMLSuite_dragDropSimple_curZIndex)DHTMLSuite_dragDropSimple_curZIndex=this.divElement.style.zIndex/1;DHTMLSuite_dragDropSimple_curZIndex=DHTMLSuite_dragDropSimple_curZIndex/1+1;this.divElement.style.zIndex=DHTMLSuite_dragDropSimple_curZIndex;if(this.cloneNode){var copy=this.divElement.cloneNode(true);this.divElement.parentNode.insertBefore(copy,this.divElement);copy.style.visibility='hidden';document.body.appendChild(this.divElement)}
DHTMLSuite.commonObj.addEvent(this.divElement,'mousedown',function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__initDragProcess(e)},ind);DHTMLSuite.commonObj.addEvent(document.documentElement,'mousemove',function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__moveDragableElement(e)},ind);DHTMLSuite.commonObj.addEvent(document.documentElement,'mouseup',function(e){DHTMLSuite.variableStorage.arrayDSObjects[ind].__stopDragProcess(e)},ind);if(!document.documentElement.onselectstart)document.documentElement.onselectstart=function(){return DHTMLSuite.commonObj.__isTextSelOk()}},setCallbackOnAfterDrag:function(functionName){this.callbackOnAfterDrag=functionName},setCallbackOnBeforeDrag:function(functionName){this.callbackOnBeforeDrag=functionName},addDragHandle:function(dragHandle){this.dragHandle[this.dragHandle.length]=dragHandle},__initDragProcess:function(e){if(document.all)e=event;var ind=this.objectIndex;DHTMLSuite_dragDropSimple_curObjIndex=ind;var thisObject=DHTMLSuite.variableStorage.arrayDSObjects[ind];if(!DHTMLSuite.commonObj.isObjectClicked(thisObject.divElement,e))return;if(thisObject.divElement.style.zIndex&&thisObject.divElement.style.zIndex/1>DHTMLSuite_dragDropSimple_curZIndex)DHTMLSuite_dragDropSimple_curZIndex=thisObject.divElement.style.zIndex/1;DHTMLSuite_dragDropSimple_curZIndex=DHTMLSuite_dragDropSimple_curZIndex/1+1;thisObject.divElement.style.zIndex=DHTMLSuite_dragDropSimple_curZIndex;if(thisObject.callbackOnBeforeDrag){thisObject.__handleCallback('beforeDrag',e)}
if(thisObject.dragHandle.length>0){var objectFound;for(var no=0;no<thisObject.dragHandle.length;no++){if(!objectFound)objectFound=DHTMLSuite.commonObj.isObjectClicked(thisObject.dragHandle[no],e)}
if(!objectFound)return}
DHTMLSuite.commonObj.__setTextSelOk(false);thisObject.mouse_x=e.clientX;thisObject.mouse_y=e.clientY;thisObject.el_x=thisObject.divElement.style.left.replace('px','')/1;thisObject.el_y=thisObject.divElement.style.top.replace('px','')/1;thisObject.dragTimer=0;thisObject.__waitBeforeDragProcessStarts();return false},__waitBeforeDragProcessStarts:function(){var ind=this.objectIndex;if(this.dragTimer>=0&&this.dragTimer<5){this.dragTimer++;setTimeout('DHTMLSuite.variableStorage.arrayDSObjects['+ind+'].__waitBeforeDragProcessStarts()',5)}},__moveDragableElement:function(e){if(document.all)e=event;var ind=this.objectIndex;var thisObj=DHTMLSuite.variableStorage.arrayDSObjects[ind];if(DHTMLSuite.clientInfoObj.isMSIE&&e.button!=1)return thisObj.__stopDragProcess();if(thisObj.dragTimer==5){if(thisObj.allowMoveX){var leftPos=(e.clientX-this.mouse_x+this.el_x);if(this.maxX!==false){if(leftPos+document.documentElement.scrollLeft>this.initialXPos+this.maxX){leftPos=this.initialXPos+this.maxX}}
if(this.minX!==false){if(leftPos+document.documentElement.scrollLeft<this.initialXPos+this.minX){leftPos=this.initialXPos+this.minX}}
thisObj.divElement.style.left =leftPos+'px'}
if(thisObj.allowMoveY){var topPos=(e.clientY-thisObj.mouse_y+thisObj.el_y);if(this.maxY!==false){if(topPos>this.initialYPos+this.maxY){topPos=this.initialYPos+this.maxY}}
if(this.minY!==false){if(topPos <this.initialYPos+this.minY){topPos=this.initialYPos+this.minY}}
thisObj.divElement.style.top=topPos+'px'}
if(this.callbackOnDuringDrag)this.__handleCallback('duringDrag',e)}
return false},__stopDragProcess:function(e){var ind=this.objectIndex;DHTMLSuite.commonObj.__setTextSelOk(true);var thisObj=DHTMLSuite.variableStorage.arrayDSObjects[ind];if(thisObj.dragTimer==5){thisObj.__handleCallback('afterDrag',e)}
thisObj.dragTimer=-1},__handleCallback:function(action,e){var callbackString='';switch(action){case "afterDrag":callbackString=this.callbackOnAfterDrag;break;case "beforeDrag":callbackString=this.callbackOnBeforeDrag;break;case "duringDrag":callbackString=this.callbackOnDuringDrag;break}
if(callbackString){callbackString=callbackString+'(e)';try{eval(callbackString)}catch(e){alert('Could not execute callback function('+callbackString+')after drag')}}},__setNewCurrentZIndex:function(zIndex){if(zIndex > DHTMLSuite_dragDropSimple_curZIndex){DHTMLSuite_dragDropSimple_curZIndex=zIndex/1+1}}}


