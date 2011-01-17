/*
 * @author: Qennix
 * Jan 12th, 2011
 */

//Keyboard Events
new Ext.KeyMap(document, {
	key: Ext.EventObject.F5,
    fn: function(keycode, e) {
    	if (! e.ctrlKey) {
    		if (Ext.isIE) {
            // IE6 doesn't allow cancellation of the F5 key, so trick it into
            // thinking some other key was pressed (backspace in this case)
    			e.browserEvent.keyCode = 8;
    		}
    		e.stopEvent();
    		document.location = document.location;
    	}else{
    		Ext.Msg.alert('Refresh', 'You clicked: CTRL-F5');
    	}
    }
});

var newButton;
var editButton;
var deleteButton;
var importButton;
var backButton;

var store;
var expander;
var cmodel;
var infoGrid;
var viewport;
var smodel;

var FIELD_CM;
var FIELD_DS

Ext.onReady(function(){
	var xColumns = new Array();
	var xFields = new Array();
	
	newButton = new Ext.Action({
		text: TRANSLATIONS.ID_ADD_ROW,
		iconCls: 'silk-add',
		icon: '/images/addc.png',
		handler: NewPMTableRow
	});
	
	editButton = new Ext.Action({
		text: TRANSLATIONS.ID_EDIT,
		iconCls: 'button_menu_ext ss_sprite  ss_pencil',
		//icon: '/images/addc.png',
		handler: EditPMTableRow,
		disabled: true
	});

	deleteButton = new Ext.Action({
		text: TRANSLATIONS.ID_DELETE,
		iconCls: 'button_menu_ext ss_sprite  ss_delete',
		//icon: '/images/addc.png',
		handler: DeletePMTableRow,
		disabled: true
	});

	importButton = new Ext.Action({
		text: TRANSLATIONS.ID_IMPORT,
		iconCls: 'silk-add',
		icon: '/images/import.gif',
		handler: ImportPMTableCSV
	});
	
	backButton = new Ext.Action({
		text: TRANSLATIONS.ID_BACK,
		iconCls: 'silk-add',
		icon: '/images/cases-selfservice.png',
		handler: BackPMList
	});
	
	//This loop loads columns and fields to store and column model
	for (var c=0; c<NAMES.length; c++){
		var xLabel = VALUES[c];
		var xCol = NAMES[c];
		FIELD_CM = Ext.util.JSON.decode('{"header": "' + xLabel +'", "dataIndex": "' + xCol + '", "hidden": false, "hideable": true, "width": 40}');
		xColumns[c] = FIELD_CM;
		FIELD_DS = Ext.util.JSON.decode('{"name": "' + xCol + '"}');
		xFields[c] = FIELD_DS;
	}
	
	var idField = Ext.util.JSON.decode('{"id":"' + TABLES.PKF +'", "dataIndex": "' + TABLES.PKF +'","hidden" :true, "hideable":false}');
	
	xColumns.unshift(idField);
  
	smodel = new Ext.grid.CheckboxSelectionModel({
    	listeners:{
    		selectionchange: function(sm){
    			var count_rows = sm.getCount();
    			switch(count_rows){
    			case 0:
    				editButton.disable();
    				deleteButton.disable();
    				break;
    			case 1:
    				editButton.enable();
    				deleteButton.enable();
    				break;
    			default:
    				editButton.disable();
					deleteButton.disable();
    				break;
    			}
    		}
    	}
    });
	
	xColumns.unshift(smodel);
	
	store = new Ext.data.GroupingStore( {
		proxy : new Ext.data.HttpProxy({
			url: 'data_additionalTablesData?sUID=' + TABLES.UID
		}),
		reader : new Ext.data.JsonReader( {
			root: '',
			fields : xFields
		})
	});	
	
	cmodel = new Ext.grid.ColumnModel({
	      defaults: {
	          width: 50,
	          sortable: true
	      },
	      columns: xColumns
	});
	
    
	infoGrid = new Ext.grid.GridPanel({
		region: 'center',
		layout: 'fit',
		id: 'infoGrid',
		height:100,
		autoWidth : true,
	//width: 350,
	//autoHeight: true,
		title : TRANSLATIONS.ID_ADDITIONAL_TABLES + " : " +TABLES.TABLE_NAME, // + " (" + xColumns.length + ")",
		stateful : true,
		stateId : 'grid',
		enableColumnResize: true,
		enableHdMenu: true,
		frame:false,
		//plugins: expander,
		//cls : 'grid_with_checkbox',
		iconCls:'icon-grid',
		columnLines: false,
		viewConfig: {
			forceFit:true
		},
		store: store,
		cm: cmodel,
		sm: smodel,
		tbar:[newButton,'-',editButton, deleteButton,'-',importButton,{xtype: 'tbfill' }, backButton],
		listeners: {
			rowdblclick: EditPMTableRow
		},
		view: new Ext.grid.GroupingView({
			forceFit:true,
			groupTextTpl: '{text}'
		})
	});

	infoGrid.store.load({params: {"function":"additionalTablesData"}});

	viewport = new Ext.Viewport({
		layout: 'fit',
		autoScroll: false,
		items: [
		        infoGrid
		        ]
	});	
});



/////JS FUNCTIONS

//Capitalize String Function
capitalize = function(s){
	s = s.toLowerCase();
	return s.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
};

//Do Nothing Function
DoNothing = function(){}

//Load New PM Table Row Forms
NewPMTableRow = function(){
	location.href = 'additionalTablesDataNew?sUID=' + TABLES.UID;
}

//Load PM Table Edition Row Form
EditPMTableRow = function(){
	iGrid = Ext.getCmp('infoGrid');
	rowsSelected = iGrid.getSelectionModel().getSelections();
   	location.href = 'additionalTablesDataEdit?sUID='+TABLES.UID+'&'+TABLES.PKF+'='+RetrieveRowsID(rowsSelected);
}

//Confirm PM Table Row Deletion Tasks
DeletePMTableRow = function(){
	iGrid = Ext.getCmp('infoGrid');
	rowsSelected = iGrid.getSelectionModel().getSelections();
	Ext.Msg.confirm(TRANSLATIONS.ID_CONFIRM, TRANSLATIONS.ID_MSG_CONFIRM_DELETE_ROW,
	        function(btn, text){
	            if (btn=="yes"){
	            	location.href = 'additionalTablesDataDelete?sUID='+TABLES.UID+'&'+TABLES.PKF+'='+RetrieveRowsID(rowsSelected);
	            }
	});
}

//Load Import PM Table From CSV Source
ImportPMTableCSV = function(){
	location.href = 'additionalTablesDataImportForm?sUID=' + TABLES.UID;
}

//Load PM Table List
BackPMList = function(){
	location.href = 'additionalTablesList';
}

//Gets UIDs from a array of rows
RetrieveRowsID = function(rows){
	var arrAux = new Array();
	for(var c=0; c<rows.length; c++){
		arrAux[c] = rows[c].get(TABLES.PKF);
	}
	return arrAux.join(',');
}