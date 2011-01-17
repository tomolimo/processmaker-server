/*
 * @author: Qennix
 * Jan 10th, 2011
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
var exportButton;
var dataButton;

var store;
var expander;
var cmodel;
var infoGrid;
var viewport;
var smodel; 

var rowsSelected;

Ext.onReady(function(){
    Ext.QuickTips.init();

    newButton = new Ext.Action({
    	text: TRANSLATIONS.ID_NEW_ADD_TABLE,
    	iconCls: 'silk-add',
    	icon: '/images/addc.png',
    	handler: NewPMTable
    });

    editButton = new Ext.Action({
    	text: TRANSLATIONS.ID_EDIT,
    	iconCls: 'button_menu_ext ss_sprite  ss_pencil',
    	//icon: '/images/addc.png',
    	handler: EditPMTable,
    	disabled: true
    });

    deleteButton = new Ext.Action({
    	text: TRANSLATIONS.ID_DELETE,
    	iconCls: 'button_menu_ext ss_sprite  ss_delete',
    	//icon: '/images/addc.png',
    	handler: DeletePMTable,
    	disabled: true
    });
    
    importButton = new Ext.Action({
    	text: TRANSLATIONS.ID_IMPORT,
    	iconCls: 'silk-add',
    	icon: '/images/import.gif',
    	handler: ImportPMTable
    });
    
    exportButton = new Ext.Action({
    	text: TRANSLATIONS.ID_EXPORT,
    	iconCls: 'silk-add',
    	icon: '/images/export.png',
    	handler: ExportPMTable,
    	disabled: true
    });
    
    dataButton = new Ext.Action({
    	text: TRANSLATIONS.ID_DATA,
    	iconCls: 'silk-add',
    	icon: '/images/cases-draft.png',
    	handler: PMTableData,
    	disabled: true
    });


    store = new Ext.data.GroupingStore( {
    	proxy : new Ext.data.HttpProxy({
    		url: 'data_additionalTablesList'
    	}),
    	reader : new Ext.data.JsonReader( {
    		root: '',
    		fields : [
    		    {name : 'ADD_TAB_UID'},
    		    {name : 'ADD_TAB_NAME'},
    		    {name : 'ADD_TAB_DESCRIPTION'}
    		    ]
    	})
    });
    
    smodel = new Ext.grid.CheckboxSelectionModel({
    	listeners:{
    		selectionchange: function(sm){
    			var count_rows = sm.getCount();
    			switch(count_rows){
    			case 0:
    				editButton.disable();
    				deleteButton.disable();
    				exportButton.disable();
    				dataButton.disable();
    				break;
    			case 1:
    				editButton.enable();
    				deleteButton.enable();
    				exportButton.enable();
    				dataButton.enable();
    				break;
    			default:
    				editButton.disable();
					deleteButton.enable();
					exportButton.enable();
					dataButton.disable();
    				break;
    			}
    		}
    	}
    });
    
    cmodel = new Ext.grid.ColumnModel({
        defaults: {
            width: 50,
            sortable: true
        },
        columns: [
            smodel,
            {id:'ADD_TAB_UID', dataIndex: 'ADD_TAB_UID', hidden:true, hideable:false},
            {header: TRANSLATIONS.ID_NAME, dataIndex: 'ADD_TAB_NAME', width: 20, align:'left'},
            {header: TRANSLATIONS.ID_DESCRIPTION, dataIndex: 'ADD_TAB_DESCRIPTION', width: 50, hidden:false, align:'left'}
        ]
    });

    infoGrid = new Ext.grid.GridPanel({
    	region: 'center',
    	layout: 'fit',
    	id: 'infoGrid',
    	height:100,
    	autoWidth : true,
    	//width: 350,
    	//autoHeight: true,
    	title : TRANSLATIONS.ID_ADDITIONAL_TABLES,
    	stateful : true,
    	stateId : 'grid',
    	enableColumnResize: true,
    	enableHdMenu: true,
    	frame:false,
    	//plugins: expander,
    	iconCls:'icon-grid',
    	//cls : 'grid_with_checkbox',
    	columnLines: false,
    	viewConfig: {
    		forceFit:true
    	},
    	store: store,
    	cm: cmodel,
    	sm: smodel,
    	tbar:[newButton,'-', editButton, deleteButton,'-', dataButton,{xtype: 'tbfill'} , importButton, exportButton],
    	listeners: {
    		rowdblclick: PMTableData,
    	},
    	view: new Ext.grid.GroupingView({
    		forceFit:true,
    		groupTextTpl: '{text}'
    	})
    });

    infoGrid.store.load({params: {"function":"additionalTablesList"}});

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

//Load New PM Table Forms
NewPMTable = function(){
	location.href = 'additionalTablesNew';
}

//Load PM Table Edition Forms
EditPMTable = function(){
	iGrid = Ext.getCmp('infoGrid');
	rowsSelected = iGrid.getSelectionModel().getSelections();
    location.href = 'additionalTablesEdit?sUID='+RetrieveRowsID(rowsSelected)+'&rand='+Math.random();
}

//Confirm PM Table Deletion Tasks
DeletePMTable = function(){
	iGrid = Ext.getCmp('infoGrid');
	rowsSelected = iGrid.getSelectionModel().getSelections();
    Ext.Msg.confirm(TRANSLATIONS.ID_CONFIRM, TRANSLATIONS.ID_CONFIRM_DELETE_PM_TABLE,
        function(btn, text){
            if (btn=="yes"){
                location.href = 'additionalTablesDelete?sUID='+RetrieveRowsID(rowsSelected)+'&rand='+Math.random();
            }
    });
}

//Load Import PM Table Form
ImportPMTable = function(){
	location.href = 'additionalTablesToImport';
}

//Load Export PM Tables Form
ExportPMTable = function(){
	iGrid = Ext.getCmp('infoGrid');
	rowsSelected = iGrid.getSelectionModel().getSelections();
	location.href = 'additionalTablesToExport?sUID='+RetrieveRowsID(rowsSelected)+'&rand='+Math.random();
}

//Load PM TAble Data
PMTableData = function(){
	iGrid = Ext.getCmp('infoGrid');
	rowsSelected = iGrid.getSelectionModel().getSelections();
    location.href = 'additionalTablesData?sUID='+RetrieveRowsID(rowsSelected)+'&rand='+Math.random();
}

//Gets UIDs from a array of rows
RetrieveRowsID = function(rows){
	var arrAux = new Array();
	for(var c=0; c<rows.length; c++){
		arrAux[c] = rows[c].get('ADD_TAB_UID');
	}
	return arrAux.join(',');
}