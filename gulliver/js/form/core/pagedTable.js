/* PACKAGE : GULLIVER FORMS
 */
function G_PagedTable( )
{
  this.id='';
  this.name='';
	this.event='';
	this.element = null;
	this.field='';
	this.ajaxUri='';
	this.currentOrder='';
	this.currentFilter='';
	this.currentPage=1;
	this.totalRows=0;
	this.rowsPerPage=25;
	this.onInsertField='';
	this.onDeleteField='';
	this.afterDeleteField='';
	this.onUpdateField='';
	this.form;
	var me = this;
  function loadTable( func, uri )  {
  	var div = document.getElementById('table[' + me.id + ']');
    var newContent=ajax_function(me.ajaxUri,func,uri);
  	if (div.outerHTML) {
  	  div.outerHTML=div.outerHTML.split(div.innerHTML).join(newContent);
  	} else {
  	  div.innerHTML=newContent;
  	}
  	var myScripts = div.getElementsByTagName('SCRIPT');
  	for(var rr=0; rr<myScripts.length ; rr++){
  	  try {
  	    if (myScripts[rr].innerHTML!=='')
  	      if (window.execScript)
  	          window.execScript( myScripts[rr].innerHTML, 'javascript' );
  	        else
  	          window.setTimeout( myScripts[rr].innerHTML, 0 );
  	  } catch (e) {
  	    alert(e.description);
  	  }
  	}
  	eval("if (loadPopupMenu_"+me.id+")loadPopupMenu_"+me.id+"();");
  	delete div;
  	delete myScripts;
  }
	this.showHideField=function(field)
	{
    uri='field='+encodeURIComponent(field);
    var ns=[],showIt=true;
    for(var i=0,j=me.shownFields.length;i<j;i++){
      if (me.shownFields[i]!==field) ns.push(me.shownFields[i]);
      else showIt=false;
    }
    if (showIt) ns.push(field);
    me.shownFields=ns;
    loadTable('showHideField',uri);
	}
	this.updateField=function(field, title, width, height)
	{
	  width = width  || 500;
	  height= height || 200;
		popupWindow(title,this.popupPage + '&field='+ encodeURIComponent(field), width, height);
		//this.form=document.getElementById('xmlPopup');
	}
	this.deleteField=function(field)
	{
  }
  this.doFilter = function ( searchForm )
  {
  	var inputs,r,uri;
  	inputs=searchForm.elements;
  	me.currentFilter='';
  	for(r=0;r<inputs.length;r++)
  	if(inputs[r].value!='')
  	{
  		if (me.currentFilter!='') me.currentFilter+='&';
  		me.currentFilter+=inputs[r].id+'='+encodeURIComponent(inputs[r].value);
  	}
  	uri='order='+encodeURIComponent(me.currentOrder)
  					+'&page='+me.currentPage;
  	if(me.currentFilter!='')
  		uri=uri		+'&filter='+encodeURIComponent(me.currentFilter);
    loadTable('paint',uri);
  	/*var ee = document.getElementById('table[' + me.id + ']');
  	var newContent=ajax_function(me.ajaxUri,'paint',uri);
  	if (ee.outerHTML) {
  	  ee.outerHTML=ee.outerHTML.split(ee.innerHTML).join(newContent);
  	} else {
  	  ee.innerHTML=newContent;
  	}
  	delete ee;
  	delete newContent;*/
  }
  this.doFastSearch = function( criteria )
  {
    uri='fastSearch='+encodeURIComponent(criteria);
  	/*var ee = document.getElementById('table[' + me.id + ']');
  	var newContent=ajax_function(me.ajaxUri,'paint',uri);
  	if (ee.outerHTML) {
  	  ee.outerHTML=ee.outerHTML.split(ee.innerHTML).join(newContent);
  	} else {
  	  ee.innerHTML=newContent;
  	}
  	delete ee;
  	delete newContent;*/
  	loadTable('paint',uri);
  }
  this.doSort = function ( fieldName , orderDirection)
  {
  	var inputs,r,uri;
  	if (orderDirection)
  	  me.currentOrder = fieldName + '=' + orderDirection;
  	else
  	  me.currentOrder = '';
  	uri='order='+encodeURIComponent(me.currentOrder)
  					+'&page='+me.currentPage;
  	if(me.currentFilter!='')
  		uri=uri		+'&filter='+encodeURIComponent(me.currentFilter);
  	loadTable('paint',uri);
  	/*var ee = document.getElementById('table[' + me.id + ']');
  	var newContent=ajax_function(me.ajaxUri,'paint',uri);
  	if (ee.outerHTML)
  	  ee.outerHTML=ee.outerHTML.split(ee.innerHTML).join(newContent);
  	else
  	  ee.innerHTML=newContent;
  	delete ee;
  	delete newContent;*/
  }
  this.refresh = function()
  {
    loadTable('paint','');
  	/*var ee = document.getElementById('table[' + me.id + ']');
  	var newContent=ajax_function(me.ajaxUri,'paint','');
  	if (ee.outerHTML)
  	  ee.outerHTML=ee.outerHTML.split(ee.innerHTML).join(newContent);
  	else
  	  ee.innerHTML=newContent;
  	delete ee;
  	delete newContent;*/
  }
  this.doGoToPage = function( nextCurrentPage )
  {
  	var inputs,r,uri;
  	me.currentPage = nextCurrentPage;
  	uri='order='+encodeURIComponent(me.currentOrder)
  					+'&page='+me.currentPage;
  	if(me.currentFilter!='')
  		uri=uri		+'&filter='+encodeURIComponent(me.currentFilter);
  	var ee = document.getElementById('table[' + me.id + ']');
  	var newContent=ajax_function(me.ajaxUri,'paint',uri);
  	if (ee.outerHTML)
  	  ee.outerHTML=ee.outerHTML.split(ee.innerHTML).join(newContent);
  	else
  	  ee.innerHTML=newContent;
  	delete ee;
  	delete newContent;
  }
  function encodeData(data)
  {
  	var enc;
  	enc='';
  	if (typeof(data)=='object')
  		for (u in data)
  		  enc+='&'+u+'='+encodeURIComponent(data[u]);
  	return encodeURIComponent(enc);
  }
}

function popup(url)
{
	var h;
	lleft=((document.body.clientWidth/2)+document.body.scrollLeft);
	ltop=((document.body.clientHeight/2)+document.body.scrollTop);

	panelPopup=leimnud.panel.create({w:popupWidth,h:popupHeight},{x:lleft,y:ltop,center:true},"popup",9,false,{
	botones:{cerrar:true},
	style:{
		panel:{
			border:"1px solid #000000",
			color:"#000000",
			backgroundColor:"#FEFEFE"
		},
				html:{
			textAlign:"left",
			padding:"5px",
			paddingTop:"12px"
		}
	}
	});

	leimnud.panel.loader.begin(panelPopup);
	uyh=new leimnud.rpc.xmlhttp({
		method    :"GET",
		url				: url,
		callback        :{
			_function	:function($)
			{
				leimnud.panel.loader.end($.arguments.obj);
				dc=$dce("div");
				leimnud.style.set(dc,{textAlign:"justify"});
				dc.innerHTML=$.request.responseText;
				leimnud.panel.html($.arguments.obj,dc);
				leimnud.panel.sombra($.arguments.obj,{sombra:{color:"#000000",opacity:30}});
			},
			arguments:{obj:panelPopup}
		}
	});
}

//global function for paged table
function setRowClass (theRow, thePointerClass)
{
    if (thePointerClass == '' || typeof(theRow.className) == 'undefined') {
        return false;
    }

    if(globalRowSelected == null || globalRowSelected.id != theRow.id){
    	globalRowSelectedClass = theRow.className;
    	theRow.className = thePointerClass;
	}
    return true;
}

var globalRowSelected = null;
var globalRowSelectedClass;

function focusRow(o, className){
	if (className == '' || typeof(o.className) == 'undefined') {
        return false;
    }
	
	/* restore its previous class at the other object*/
	if( globalRowSelected != null ){
		globalRowSelected.className = globalRowSelectedClass;
	}
	
	globalRowSelected = o;
	//globalRowSelectedClass = o.className;
	
	o.className = className;
	
    return true;
}
