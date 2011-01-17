
/*
 * @author: Qennix
 * Jan 13th, 2011
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

var store;
var cmodel;
var smodel;
var infoGrid;
var viewport

var cancelButton;
var exportButton;

var w;

Ext.onReady(function(){
	Ext.QuickTips.init();
	
    var reader = new Ext.data.ArrayReader({}, [{name: 'action'}]);
	
    var comboStore = new Ext.data.Store({
        reader: reader,
        data: Ext.grid.dummyData
    });
    
	exportButton = new Ext.Action({
		text: TRANSLATIONS.ID_EXPORT,
		iconCls: 'silk-add',
		icon: '/images/export.png',
		handler: ExportPMTables
	});
	
	cancelButton = new Ext.Action({
		text: TRANSLATIONS.ID_CANCEL,
		iconCls: 'silk-add',
		icon: '/images/cases-selfservice.png',
		handler: CancelExport
	});
	
	store = new Ext.data.GroupingStore( {
    	proxy : new Ext.data.HttpProxy({
    		url: 'data_additionalTablesExport?aUID='+EXPORT_TABLES.UID_LIST
    	}),
    	reader : new Ext.data.JsonReader( {
    		root: '',
    		fields : [
    		    {name : 'ADD_TAB_UID'},
    		    {name : 'ADD_TAB_NAME'},
    		    {name : 'ADD_TAB_DESCRIPTION'},
    		    {name : 'CH_SCHEMA'},
    		    {name : 'CH_DATA'}
    		    ]
    	})
    });
	
	var action_edit = new Ext.form.ComboBox({
		typeAhead: true,
		triggerAction: 'all',
		mode: 'local',
		store: comboStore,
		displayField: 'action',
		valueField: 'action'
	});
	
	cmodel = new Ext.grid.ColumnModel({
        defaults: {
            width: 10,
            sortable: true
        },
        columns: [
            new Ext.grid.RowNumberer(),
            //smodel,
            {id:'ADD_TAB_UID', dataIndex: 'ADD_TAB_UID', hidden:true, hideable:false},
            {header: TRANSLATIONS.ID_NAME, dataIndex: 'ADD_TAB_NAME', width: 20, align:'left'},
            {header: TRANSLATIONS.ID_DESCRIPTION, dataIndex: 'ADD_TAB_DESCRIPTION', width: 50, hidden:false, align:'left'},//,
            {header: 'SCHEMA', dataIndex: 'CH_SCHEMA', hidden: false, width: 20, editor: action_edit, align: 'center'},            
            {header: 'DATA', dataIndex: 'CH_DATA', hidden: false, width: 20, editor: action_edit, align: 'center'}
        ]
    });
	
	infoGrid = new Ext.grid.EditorGridPanel({
		store: store,
        cm: cmodel,
        width: 600,
        height: 300,
        title: TRANSLATIONS.ID_ADDITIONAL_TABLES + ': ' +TRANSLATIONS.ID_TITLE_EXPORT_TOOL,
        frame: true,
        clicksToEdit: 1,
        iconCls:'icon-grid',
        id: 'infoGrid',

    	sm: new Ext.grid.RowSelectionModel({singleSelect: false}),
    	tbar:[exportButton, {xtype: 'tbfill'} ,cancelButton],//'-', editButton, deleteButton,'-', dataButton,{xtype: 'tbfill'} , importButton, exportButton],
    	view: new Ext.grid.GroupingView({
    		forceFit:true,
    		groupTextTpl: '{text}'
    	})
    });

    infoGrid.store.load({params: {"function":"additionalTablesExport"}});
    
    viewport = new Ext.Viewport({
    	layout: 'fit',
    	autoScroll: false,
    	items: [
    	   infoGrid
    	]
    });
	
});

//Cancels Export View
CancelExport = function(){
	location.href = 'additionalTablesList';
}

//Export Schema/Data from PM Tables
ExportPMTables = function(){
	iGrid = Ext.getCmp('infoGrid');
	var storeExport = iGrid.getStore();
	var UIDs = new Array();
	var SCHs = new Array();
	var DATs = new Array();
    for (var r=0; r<storeExport.getCount(); r++){
    	row = storeExport.getAt(r);
    	UIDs[r] = row.data['ADD_TAB_UID'];
    	if (row.data['CH_SCHEMA']==TRANSLATIONS.ID_ACTION_EXPORT){
    		SCHs[r] = row.data['ADD_TAB_UID'];
    	}else{
    		SCHs[r] = 0;
    	}
    	if (row.data['CH_DATA']==TRANSLATIONS.ID_ACTION_EXPORT){
    		DATs[r] = row.data['ADD_TAB_UID'];
    	}else{
    		DATs[r] = 0;
    	}  
    }
    Ext.Ajax.request({
    	   url: 'additionalTablesAjax',
    	   success: SuccessExport,
    	   failure: DoNothing,
    	   params: { action: 'doExport',  tables: UIDs.join(','), schema: SCHs.join(','), data: DATs.join(',') }
    });
}

//Response Export Handler
SuccessExport = function(response, opts){
	w = new Ext.Window({
		height: 350, 
		width: 670,
		resizable: false,
	    html: response.responseText,
	    autoscroll: false,
		title: TRANSLATIONS.ID_TITLE_EXPORT_RESULT,
		closable: true,
		buttons: [{
			text: TRANSLATIONS.ID_CLOSE,
//			iconCls: 'silk-add',
			handler: CloseExport
		}]
	});
	w.show();
} 

//Close Export Dialog
CloseExport = function(){
	w.close();
}


//Do Nothing Function
DoNothing = function(){}

Ext.grid.dummyData = [['Export'],['Ignore']];

