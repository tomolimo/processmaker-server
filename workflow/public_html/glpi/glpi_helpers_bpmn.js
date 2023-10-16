glpi = {
	
	// Grid helpers
   deleteGridRows: function ( gridName ) {
		var grd = getFieldById(gridName) ;
		var tot = grd.getNumberRows() ;
		for(var i = tot; i > 0; i-- ) {
			grd.deleteRow(i);
		};		
	},

	selectAll: function ( gridName, colName, select ) {
		var grd = getFieldById(gridName) ;
		var colNum = this.getColNum( gridName, colName ) ;
		for(var i=1; i<=grd.getNumberRows() && colNum>0; i++){
			//debugger ;
			//if( grd.getValue( i, colNum ) != select ) {
				grd.setValue( select, i, colNum ) ;
				$(grd.gridtable[i-1][colNum-1].el).change(); // to trigger change
			//}
		}
	},

	selectRow: function (gridName, colName, select, rowIdx) {
	   var grd = getFieldById(gridName);
	   var colNum = this.getColNum(gridName, colName);
	   grd.setValue(select, rowIdx, colNum);
	   $(grd.gridtable[rowIdx - 1][colNum - 1].el).change(); // to trigger change
	},

	selectWithCallback: function (gridName, colName, select, callback_filter) {
	   var grd = getFieldById(gridName);
	   var colNum = this.getColNum(gridName, colName);
	   for (var i = 1; i <= grd.getNumberRows() && colNum > 0; i++) {
	      //debugger ;
	      var toBeChanged = callback_filter(glpi.getGridRow(gridName, i));
	      if (toBeChanged) {// && grd.getValue(i, colNum) != select) {
	         grd.setValue(select, i, colNum);
	         $(grd.gridtable[i - 1][colNum - 1].el).change(); // to trigger change
	      }
	   }
	},
		
	getColNum: function (gridName, colName) {
		var grd = getFieldById(gridName) ;
		for(var i= 0; i < grd.columnsModel.length; i++ ){
			if( grd.columnsModel[i].columnName == colName ) {
				return i + 1 ;
			}
		}
		return false ;
	},

	getColsNums: function (gridName) {
	   var grd = getFieldById(gridName);
	   var ret = {};
	   for (var i = 0; i < grd.columnsModel.length; i++) {
	      ret[grd.columnsModel[i].columnName] = i + 1;
	   }
	   return ret;
	},

	
	jq: function( myid ) {
    return "#" + myid.replace( /(:|\.|\[|\]|,|=|@)/g, "\\$1" );
	},
	
	addGridRow:	function ( gridName, oRow ) {
		var grd = getFieldById(gridName)
		var aData = [] ;
		// debugger;
		for(var i = 0; i < grd.columnsModel.length; i++) {
			aData.push( {value: oRow[grd.columnsModel[i].columnName]} ) ;
		}
		
		grd.addRow( aData ) ;
	},
					
	find: function(elts, hookfct, thisValue){
	   var stop = false;
	   for (var i = 0; i < elts.length && !stop; i++) {
	      stop = hookfct(elts[i], thisValue, i);
	   }
		return stop;
	},

	findIndex: function (elts, hookfct, thisValue) {
	   for (var i = 0; i < elts.length; i++) {
	      if (hookfct(elts[i], thisValue, i)) {
	         return i;
	      }
	   }
	   return -1;
	},

	//asyncLoop: function (index, maxIndex, endCallback, toDoCallback) {
	//   setTimeout(function () {
	//      if (index < maxIndex) {
	//         toDoCallback(arguments);
	//         //that.addGridRow(gridName, elts[i]);
	//         index++;
	//         asyncLoop(index);
	//      } else {
	//         endCallback();
	//      }
	//   }, 0);
	//},

	setGridRowsAsync: function (gridName, elts, callback) {
	   var that = this;
	   function asyncLoop(i) {
	      setTimeout(function () {
	         if (i < elts.length) {
	            that.addGridRow(gridName, elts[i]);
	            i++;
	            asyncLoop(i);
	         } else {
	            callback();
	         }
	      }, 0);
	   }
      // start asyncLoop
	   asyncLoop(0);
	},

	setGridRows: function( gridName, elts) { 
	   //debugger;
	   for (var i = 0; i < elts.length; i++) {
	      this.addGridRow(gridName, elts[i]);
	   }
	},

	getGridRow: function( gridName, iRow ){
		var grd = getFieldById(gridName) ;
		var elt = {};
		for (var f = 0; f < grd.columnsModel.length; f++) {
			elt[grd.columnsModel[f].columnName] = grd.getValue(iRow, f + 1) ; 
		}
		return elt ;
	},

	getGridRows: function( gridName ) { 
		var grd = getFieldById(gridName) ;
		var tot = grd.getNumberRows(); 
		var elts = [] ;
		for (var i = 1; i <= tot; i++) {
			elts.push(this.getGridRow(gridName, i));
		}
		return elts;
	},			
	
	gridCountRows: function(gridName) {
		//debugger;
		return getFieldById(gridName).getNumberRows() ;
	},
	
	initGrid: function(gridName, colName) {
		//debugger ;
		var grd = getFieldById(gridName) ;
		var colNum = this.getColNum( gridName, colName) ;
		if( grd.getNumberRows() == 1 && grd.getValue( 1, colNum ) == '') {
			grd.deleteRow(1);
		};
	}, 

	// CSS and class helpers
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
   },
	
	// Dialog helpers
	// Create the dialog with "Yes" and "No" buttons:
   showConfirmDlg: function (title, content, yestext, notext, callback, position) {
      //debugger;
      return glpi.showMultiBtnDlg(title, content, [{ text: yestext, val: true }, { text: notext, val: false }], callback, position);
   },

   // Create a dialog with several buttons:
   showMultiBtnDlg: function (title, content, buttons, callback, position) {
      // buttons is an array of the form
      // [ {text: 'Yes', val:  1},
      //   {text: 'No', val: 0},
      //   {text: 'May be', val: -1}
      // ]
      var locParent = window.document;
      var parentScrollTop = $(locParent).scrollTop();

      if (!position) {
         position = {};
      }

      var locButtons = [];
      buttons.forEach(function (item, index) {
         locButtons.push({
            text: item.text,
            click: function () {
               $(this).dialog('close');
               callback(item.val);
            }
         });
      });
      var dlgContents = {
         title: title,
         modal: true,
         resizable: false,
         buttons: locButtons,
         close: function (event, ui) {
            $(locParent).find('html, body').animate({
               scrollTop: parentScrollTop
            }, 1000);
         },
         position: position,
         show: true,
         hide: true
      }

      var locDlg = $("<div></div>").html(content).dialog(dlgContents);

      $(locParent).find('html, body').animate({
         scrollTop: locDlg.offset().top
      }, 1000);

      return locDlg;
   },


	// Create the dialog with "Ok" button
   showInfoDlg: function (title, content, oktext, elementOrPosition) {
	   var locParent = window.document;
	   var parentScrollTop = $(locParent).scrollTop();

	   if (!elementOrPosition || (elementOrPosition instanceof jQuery && elementOrPosition.length == 0)) {
	      position = { my: "top", at: "bottom", of: $('html, body') };
	   } 
	   else if (elementOrPosition instanceof jQuery) {
	      position = { my: "top", at: "bottom", of: elementOrPosition };
	   } else {
	      position = elementOrPosition;
	   }


	   var dlgContents = {
		  title: title,   
		  width: 900,
		  modal: true,
		  buttons: [{
				text: oktext,
				click: function() {
					 $(this).dialog("close");
				}
		  }],
		  close: function (event, ui) {
		     $(locParent).find('html, body').animate({
		        scrollTop: parentScrollTop
		     }, 1000);
		  },
		  position: position,
		  show: true,
		  hide: true		  
	   }

	   var locDlg = $("<div></div>").html(content).dialog(dlgContents);

	   $(locParent).find('html, body').animate({
	      scrollTop: locDlg.offset().top
	   }, 1000);

	   return locDlg;
   },

   showSimpleModalAlert: function(title, content, oktext) {
     var dlgContents = {
      title: title,   
      modal: true,
      buttons: [{
         text: oktext,
         click: function() {
            $(this).dialog("close");
         }
      }],
      open: function (event, ui) {
         $('div[aria-describedby*="alertWin"]').css('top','15px');
      },    
      show: true,
         hide: true           
      }
      $("<div id=\"alertWin\"></div>").html(content).dialog(dlgContents);
   },

	
	// Translation helper
	t: function( str ){
		var trans = $('#translations option') ;
		//debugger;
		for(var i=0; i<trans.length; i++) {
			if ($(trans[i]).val() == str){
				return $(trans[i]).text() ;
			}
		};
		// not found
		return str ;
	},

   // suggest helper 
   suggestSetValueAndText : function(suggestId, value, text) {
      getFieldById(suggestId).setValue(value);
      //getFieldById(suggestId).$el.find('input').last().val(value);
      getFieldById(suggestId).$el.find('input').first().val(text);
   },
  
   suggestDynamicValue : function(suggestId, targetSuggestId){
      getFieldById(suggestId).executeSuggestQuery(function(data){
         if (data.length == 1) {
            glpi.suggestSetValueAndText(suggestId, data[0].value, data[0].text);
            if (targetSuggestId) {
               glpi.suggestSetValueAndText(targetSuggestId, data[0].value, data[0].text);
            }
         }
      });
   }

}