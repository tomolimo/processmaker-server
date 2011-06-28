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

    pageSize = parseInt(CONFIG.pageSize);

    newButton = new Ext.Action({
    	text: _('ID_NEW_ADD_TABLE'),
    	iconCls: 'button_menu_ext ss_sprite ss_add',
    	handler: NewPMTable
    });

    editButton = new Ext.Action({
    	text: _('ID_EDIT'),
    	iconCls: 'button_menu_ext ss_sprite ss_pencil',
      	handler: EditPMTable,
    	disabled: true
    });

    deleteButton = new Ext.Action({
    	text: _('ID_DELETE'),
    	iconCls: 'button_menu_ext ss_sprite  ss_delete',
      	handler: DeletePMTable,
    	disabled: true
    });

    importButton = new Ext.Action({
    	text: _('ID_IMPORT'),
    	iconCls: 'silk-add',
    	icon: '/images/import.gif',
    	handler: ImportPMTable
    });

    exportButton = new Ext.Action({
    	text: _('ID_EXPORT'),
    	iconCls: 'silk-add',
    	icon: '/images/export.png',
    	handler: ExportPMTable,
    	disabled: true
    });

    dataButton = new Ext.Action({
    	text: '&nbsp;' + _('ID_DATA'),
    	iconCls: 'silk-add',
    	icon: '/images/icon-pmtables.png',
    	handler: PMTableData,
    	disabled: true
    });

    searchButton = new Ext.Action({
    	text: _('ID_SEARCH'),
    	handler: DoSearch
    });

    contextMenu = new Ext.menu.Menu({
    	items: [editButton, deleteButton,'-',dataButton,'-',exportButton]
    });

    searchText = new Ext.form.TextField ({
        id: 'searchTxt',
        ctCls:'pm_search_text_field',
        allowBlank: true,
        width: 150,
        emptyText: _('ID_ENTER_SEARCH_TERM'),
        listeners: {
          specialkey: function(f,e){
            if (e.getKey() == e.ENTER) {
            	DoSearch();
            }
          },
          focus: function(f,e) {
       	   			var row = infoGrid.getSelectionModel().getSelected();
       	   			infoGrid.getSelectionModel().deselectRow(infoGrid.getStore().indexOf(row));
          		 }
        }
    });

    clearTextButton = new Ext.Action({
    	text: 'X',
    	ctCls:'pm_search_x_button',
    	handler: GridByDefault
    });

    storePageSize = new Ext.data.SimpleStore({
        fields: ['size'],
         data: [['20'],['30'],['40'],['50'],['100']],
         autoLoad: true
      });

      comboPageSize = new Ext.form.ComboBox({
        typeAhead     : false,
        mode          : 'local',
        triggerAction : 'all',
        store: storePageSize,
        valueField: 'size',
        displayField: 'size',
        width: 50,
        editable: false,
        listeners:{
          select: function(c,d,i){
            UpdatePageConfig(d.data['size']);
            bbarpaging.pageSize = parseInt(d.data['size']);
            bbarpaging.moveFirst();
          }
        }
      });

      comboPageSize.setValue(pageSize);


    store = new Ext.data.GroupingStore( {
    	proxy : new Ext.data.HttpProxy({
    		url: 'data_additionalTablesList'
    	}),
    	reader : new Ext.data.JsonReader( {
    		root: 'tables',
    		totalProperty: 'total_tables',
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
            {header: _('ID_NAME'), dataIndex: 'ADD_TAB_NAME', width: 300, align:'left'},
            {header: _('ID_DESCRIPTION'), dataIndex: 'ADD_TAB_DESCRIPTION', width: 400, hidden:false, align:'left'}
        ]
    });

    bbarpaging = new Ext.PagingToolbar({
        pageSize: pageSize,
        store: store,
        displayInfo: true,
        displayMsg: _('ID_GRID_PAGE_DISPLAYING_PMTABLES_MESSAGE') + '&nbsp; &nbsp; ',
        emptyMsg: _('ID_GRID_PAGE_NO_PMTABLES_MESSAGE'),
        items: ['-',_('ID_PAGE_SIZE')+':',comboPageSize]
      });

    infoGrid = new Ext.grid.GridPanel({
    	region: 'center',
    	layout: 'fit',
    	id: 'infoGrid',
    	height:100,
    	autoWidth : true,
    	title : _('ID_ADDITIONAL_TABLES'),
    	stateful : true,
    	stateId : 'grid',
    	enableColumnResize: true,
    	enableHdMenu: true,
    	frame:false,
    	columnLines: false,
    	viewConfig: {
    		forceFit:true
    	},
    	store: store,
    	cm: cmodel,
    	sm: smodel,
    	tbar:[newButton, '-', editButton, deleteButton,'-', dataButton,'-' , importButton, exportButton,{xtype: 'tbfill'},searchText,clearTextButton,searchButton],
    	bbar: bbarpaging,
    	listeners: {
    		rowdblclick: PMTableData,
    		render: function(){
    			this.loadMask = new Ext.LoadMask(this.body, {msg:_('ID_LOADING_GRID')});
    		}
    	},
    	view: new Ext.grid.GroupingView({
    		forceFit:true,
    		groupTextTpl: '{text}'
    	})
    });

    infoGrid.on('rowcontextmenu',
    		function (grid, rowIndex, evt) {
        		var sm = grid.getSelectionModel();
        		sm.selectRow(rowIndex, sm.isSelected(rowIndex));
    		},
    		this
    );

    infoGrid.on('contextmenu', function(evt){evt.preventDefault();}, this);
    infoGrid.addListener('rowcontextmenu',onMessageContextMenu, this);

    infoGrid.store.load();

    viewport = new Ext.Viewport({
    	layout: 'fit',
    	autoScroll: false,
    	items: [
    	   infoGrid
    	]
    });
});

//Funtion Handles Context Menu Opening
onMessageContextMenu = function (grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    contextMenu.showAt([coords[0], coords[1]]);
};

/////JS FUNCTIONS

//Capitalize String Function
capitalize = function(s){
	s = s.toLowerCase();
	return s.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
};

//Do Nothing Function
DoNothing = function(){};

//Load New PM Table Forms
NewPMTable = function(){
	location.href = 'additionalTablesNew';
};

newReportTable = function(){
  location.href = '../reportTables/new';
};

//Load PM Table Edition Forms
EditPMTable = function(){
	iGrid = Ext.getCmp('infoGrid');
	rowsSelected = iGrid.getSelectionModel().getSelections();
    location.href = 'additionalTablesEdit?sUID='+RetrieveRowsID(rowsSelected)+'&rand='+Math.random();
};

//Confirm PM Table Deletion Tasks
DeletePMTable = function(){
	iGrid = Ext.getCmp('infoGrid');
	rowsSelected = iGrid.getSelectionModel().getSelections();
    Ext.Msg.confirm(_('ID_CONFIRM'), _('ID_CONFIRM_DELETE_PM_TABLE'),
        function(btn, text){
            if (btn=="yes"){
                location.href = 'additionalTablesDelete?sUID='+RetrieveRowsID(rowsSelected)+'&rand='+Math.random();
            }
    });
};

//Load Import PM Table Form
ImportPMTable = function(){
	location.href = 'additionalTablesToImport';
};

//Load Export PM Tables Form
ExportPMTable = function(){
	iGrid = Ext.getCmp('infoGrid');
	rowsSelected = iGrid.getSelectionModel().getSelections();
	location.href = 'additionalTablesToExport?sUID='+RetrieveRowsID(rowsSelected)+'&rand='+Math.random();
};

//Load PM TAble Data
PMTableData = function(){
	iGrid = Ext.getCmp('infoGrid');
	rowsSelected = iGrid.getSelectionModel().getSelections();
    location.href = 'additionalTablesData?sUID='+RetrieveRowsID(rowsSelected)+'&rand='+Math.random();
};

//Gets UIDs from a array of rows
RetrieveRowsID = function(rows){
	var arrAux = new Array();
	for(var c=0; c<rows.length; c++){
		arrAux[c] = rows[c].get('ADD_TAB_UID');
	}
	return arrAux.join(',');
};
//Update Page Size Configuration
UpdatePageConfig = function(pageSize){
  Ext.Ajax.request({
  url: 'additionalTablesAjax',
  params: {action:'updatePageSize', size: pageSize}
  });
};

//Do Search Function
DoSearch = function(){
   infoGrid.store.load({params: {textFilter: searchText.getValue()}});
};

//Load Grid By Default
GridByDefault = function(){
	searchText.reset();
	infoGrid.store.load();
};