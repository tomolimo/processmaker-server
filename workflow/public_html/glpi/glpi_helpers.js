glpi = {
	ajax_post: function(ajax_server, parameters, callback, asynchronous){
		if(ajax_server == ''){
			return;
		}
		var objetus;
		objetus = get_xmlhttp();
		try{					
			if(typeof(asynchronous) === 'undefined'){
				asynchronous=false;
			}
			data = JSON.stringify(parameters);
			objetus.open('POST',ajax_server,asynchronous);
			
			objetus.onreadystatechange = function(){
				if(objetus.readyState == 4 && objetus.status == 200 && callback){
					callback(objetus.responseText);
				}
			}
			objetus.setRequestHeader("Content-Type","application/json; charset=utf-8");
			objetus.send(data);
			if(!asynchronous){
				if(callback)
					callback(objetus.responseText);
				return objetus.responseText;
			}
		} catch(ss){
			alert("Error: " + var_dump(ss));
		}
	},
				
	ajax_get: function(ajax_server,parameters,callback,asynchronous, user, pass){
		if(ajax_server == ''){
			return;
		}
		var objetus;
		//debugger;
		objetus=get_xmlhttp();
		try{

			if(typeof(asynchronous)==='undefined')
				asynchronous=false;
			if( parameters ) 
				questionMark=(ajax_server.split('?').length>1)?'&':'?';
			else {
				questionMark = ''; 
				parameters = '';
			}
			objetus.open('GET', ajax_server + questionMark + parameters, asynchronous);
			//objetus.setRequestHeader('Authorization', 'Basic ' + btoa(user + ":" + pass) );
			//objetus.withCredentials = true ;
			objetus.onreadystatechange=function(){
				if(objetus.readyState==4 && objetus.status==200 && callback){
					callback(objetus.responseText);
				}
			}
			objetus.send(null);
			if(!asynchronous){
				if(callback)
					callback(objetus.responseText);
				return objetus.responseText;
			}
		} catch(ss){
			alert("Error: "+var_dump(ss));
		}
	},
				
   deleteGridRows: function ( gridName ) {
		var grd = getObject( gridName );
		var i = Number_Rows_Grid(gridName, grd.aFields[0].sFieldName) ;
		for (; i > 1; i--) {
			grd.deleteGridRow(i, true);
		}
	  //debugger;
		for (i = 0; i < grd.aFields.length; i++) {
			var item = getGridField(gridName, 1, grd.aFields[i].sFieldName);
			switch( item.getAttribute('pmgridtype') ) {
				case 'checkbox' : 
					item.checked = false;
					break ;
				case 'link' :
					item.setAttribute('onclick', null);
					break;						
				default :
					item.value = '';
			}
		}
	},

	setGridRow: function( grd, iRow, oRow ){
		for (var i = 0; i < grd.aFields.length; i++) {
			this.setItemValue( getGridField(grd.sGridName, iRow, grd.aFields[i].sFieldName), oRow[grd.aFields[i].sFieldName] );
		}					  		
	},

	getGridRow: function( grd, iRow ){
		var elt = {};
		for (var f = 0; f < grd.aFields.length; f++) {
			elt[grd.aFields[f].sFieldName] = this.getItemValue( getGridField(grd.sGridName, iRow, grd.aFields[f].sFieldName) ) ;
		}
		return elt ;
	},

	addGridRow:	function ( gridName, oRow ) {
		var grd = getObject( gridName );
		var newRowNo = Number_Rows_Grid(gridName, grd.aFields[0].sFieldName) ;
		var item = getGridField(gridName, 1, grd.aFields[0].sFieldName) ;
		if( item.value != '' ) { 
			grd.addGridRow();
			newRowNo += 1 ;
		}
		this.setGridRow( grd, newRowNo, oRow);
	},
					
	find: function(elts, funcionaplicada, thisValue){
		var parar=false;
		for(var i=0;i<elts.length&&!parar;i++)
			parar=funcionaplicada(elts[i], thisValue,i);
		return parar;
	},
	
	setItemValue: function( item, value ) {
		switch( item.getAttribute('pmgridtype') ) {
			case 'checkbox' : 
				item.checked = value;
				break ;
			case 'link' :
				item.setAttribute('onclick', value);
				break;						
			default :
				item.value = value;
		}
	},
	
	getItemValue: function( item ){
		var value ;
		switch( item.getAttribute('pmgridtype') ) {
			case 'checkbox' : 
				value = item.checked;
				break ;
			case 'link' :
				value = item.getAttribute('onclick');
				break;
			default :
				value = item.value;
		}
		return value ;
	},
			
	setGridRows: function( gridName, elts ) { 
		//debugger;
		var grd = getObject( gridName );
		var tot = Number_Rows_Grid(gridName, grd.aFields[0].sFieldName);
		var eltsCount = elts.length ;
		if( eltsCount > tot ) {
			// we need to add rows in gridName
			for(var i=tot; i<eltsCount; i++){
				grd.addGridRow();
			}
		} else if( eltsCount < tot ){
			// we need to delete some grid rows
			for (var i=tot; i > eltsCount; i--) {
				grd.deleteGridRow(i, true);
			}
		}
		
		tot = eltsCount;
		for (var i = 1; i <= tot; i++) {
			this.setGridRow( grd, i, elts[i-1] ) ;
		}
	},

	getGridRows: function( gridName ) { 
		var grd = getObject( gridName );
		var tot = Number_Rows_Grid(gridName, grd.aFields[0].sFieldName);
		var elts = [] ;
		 if( getGridField(gridName, 1, grd.aFields[0].sFieldName).value != '' ){
			for (var i = 1; i <= tot; i++) {
				elts.push(this.getGridRow(grd, i));
			}
		}
		return elts;
	},			

	gridCountRows: function(gridName) {
		var grd = getObject( gridName );
		if( getGridField(gridName, 1, grd.aFields[0].sFieldName).value != '' ) {
			return Number_Rows_Grid(gridName, grd.aFields[0].sFieldName) ;
		} else {
			return 0 ;
		}
	},
		
	init: function() {
		return true;
	}, 

   getCSSRules: function (selector, sheet) {
      var results = new Array() ;
      var sheets = typeof sheet !== 'undefined' ? [sheet] : document.styleSheets;
      selector = '.' + selector;
      for (var i = 0, l = sheets.length; i < l; i++) {
         var sheet = sheets[i];
         if( !sheet.cssRules ) { continue; }
         for (var j = 0, k = sheet.cssRules.length; j < k; j++) {
            var rule = sheet.cssRules[j];
            if (rule.selectorText && rule.selectorText.split(',').indexOf(selector) !== -1) {
               results.push( rule );
            }
         }
      }
      return results;
   },

   setClassAttribute: function (className, attrName, value) {
      var rules = glpi.getCSSRules(className);
      for (var i = 0, l = rules.length; i < l; i++) {
         rules[i].style[attrName] = value;
      }
   }

}