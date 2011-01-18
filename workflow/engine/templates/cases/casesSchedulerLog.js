/*
 * @author: Qennix
 * Jan 18th, 2011
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
var infoGrid;
var viewport;
var bbarpaging;
var w;
 

Ext.onReady(function(){
    Ext.QuickTips.init();

    store = new Ext.data.GroupingStore( {
        proxy : new Ext.data.HttpProxy({
            url: 'data_casesSchedulerLog'
          }),
    	reader : new Ext.data.JsonReader( {
    		root: 'rows',
    		totalProperty: 'results',
    		fields : [
    		    {name : 'LOG_CASE_UID'},
    		    {name : 'PRO_UID'},
    		    {name : 'TAS_UID'},
    		    {name : 'USR_NAME'},
    		    {name : 'EXEC_DATE'},
    		    {name : 'EXEC_HOUR'},
    		    {name : 'RESULT'},
    		    {name : 'SCH_UID'},
    		    {name : 'WS_CREATE_CASE_STATUS'},
    		    {name : 'WS_ROUTE_CASE_STATUS'}
    		    ]
    	})
    });
    
    cmodel = new Ext.grid.ColumnModel({
        defaults: {
            width: 50,
            sortable: true
        },
        columns: [
            {id:'LOG_CASE_UID', dataIndex: 'LOG_CASE_UID', hidden:true, hideable:false},
            {header: TRANSLATIONS.ID_DATE_LABEL, dataIndex: 'EXEC_DATE', width: 30, align:'left'},
            {header: TRANSLATIONS.ID_TIME_LABEL, dataIndex: 'EXEC_HOUR', width: 30, hidden:false, align:'left'},
            {header: TRANSLATIONS.ID_USER, dataIndex: 'USR_NAME', width: 40, hidden:false, align:'left'},
            {header: TRANSLATIONS.ID_RESULT, dataIndex: 'RESULT', width: 40, hidden:false, align:'left'},
            {header: TRANSLATIONS.ID_CREATED_CASE_STATUS, dataIndex: 'WS_CREATE_CASE_STATUS', width: 80, hidden:false, align:'left'},
            {header: TRANSLATIONS.ID_ROUTED_CASE_STATUS, dataIndex: 'WS_ROUTE_CASE_STATUS', width: 80, hidden:false, align:'left'},
            {header: TRANSLATIONS.ID_VIEW, dataIndex: 'LOG_CASE_UID', width: 20, hidden:false, align:'center', renderer: view_button}
        ]
    });
    
    bbarpaging = new Ext.PagingToolbar({
    	pageSize: 20,
    	store: store
    });

    infoGrid = new Ext.grid.GridPanel({
    	region: 'center',
    	layout: 'fit',
    	id: 'infoGrid',
    	height:100,
    	autoWidth : true,
    	title : TRANSLATIONS.ID_LOG_CASE_SCHEDULER,
    	stateful : true,
    	stateId : 'grid',
    	enableColumnResize: true,
    	enableHdMenu: true,
    	frame:true,
    	iconCls:'icon-grid',
    	columnLines: false,
    	viewConfig: {
    		forceFit:true
    	},
    	store: store,
    	cm: cmodel,
    	bbar: [{xtype: 'tbfill'}, bbarpaging],
    	listeners: {
    		rowdblclick: ShowSelectedLog,
    	},
    	view: new Ext.grid.GroupingView({
    		forceFit:true,
    		groupTextTpl: '{text}'
    	})
    });

    infoGrid.store.load({params: {"function":"caseSchedulerLog"}});

    viewport = new Ext.Viewport({
    	layout: 'fit',
    	autoScroll: false,
    	items: [
    	   infoGrid
    	]
    });
});

//Do Nothing Function
DoNothing = function(){}

//Handles DoubleClick's Grid
ShowSelectedLog = function(){
	iGrid = Ext.getCmp('infoGrid');
	rowSelected = iGrid.getSelectionModel().getSelected();
	if (rowSelected){
		ViewLogScheduler(rowSelected.data.LOG_CASE_UID);
	}
}

//Renderer Button on Grid
view_button = function(val){
	var sep = "'";
	return '<input type="button" value="' + TRANSLATIONS.ID_VIEW + '" onclick="ViewLogScheduler(' + sep + val + sep + ');" />';
}

//Open Popup View Log Window
ViewLogScheduler = function(value){
	Ext.Ajax.request({
	   url: 'cases_Scheduler_Log_Detail',
 	   success: SuccessViewLog,
 	   failure: DoNothing,
 	   params: {LOG_CASE_UID: value}
 
	});
}

//Response View Handler
SuccessViewLog = function(response, opts){
	w = new Ext.Window({
		height: 320, 
		width: 600,
		resizable: false,
	    html: response.responseText,
	    autoscroll: false,
		title: TRANSLATIONS.ID_TITLE_LOG_DETAIL,
		closable: true,
		buttons: [{
			text: TRANSLATIONS.ID_CLOSE,
			handler: CloseView
		}]
	});
	w.show();
} 

//Close View Dialog
CloseView = function(){
	w.close();
}


