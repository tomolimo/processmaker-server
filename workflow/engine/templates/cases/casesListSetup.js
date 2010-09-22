Ext.onReady(function(){
  //global variables for this script
  var currentAction = '';
  
  // Generic fields array to use in both store defs.
  var pmFields = [
    {name: 'name', mapping : 'name'},
    {name: 'gridIndex', mapping : 'gridIndex'},
    {name: 'fieldType', mapping : 'fieldType'},
    {name: 'label', mapping : 'label'},
    {name: 'width', mapping : 'width'},
    {name: 'align', mapping : 'align'}
  ];

  //Dropdown to select the PMTable
  var PmTableStore = new Ext.data.JsonStore({
     root            : 'data',
     url             : 'proxyPMTablesList',
     totalProperty   : 'totalCount',
     idProperty      : 'gridIndex',
     remoteSort      : false, //true,
     autoLoad        : false,
     fields          : [
       'ADD_TAB_UID', 'ADD_TAB_NAME'
     ]
  });

  // create the Data Store to list PMTables in the dropdown
  var pmTablesDropdown = new Ext.form.ComboBox ({
    width        : '180',
    xtype        : 'combo',
//    emptyText    : 'Select a PM Table...',
    emptyText    : TRANSLATIONS.LABEL_EMPTY_PMTABLE,
    store        : PmTableStore,
    displayField : 'ADD_TAB_NAME',
    valueField   : 'ADD_TAB_UID',
    triggerAction: 'all',
    listeners: {
      'select': function() {
        var tableUid  =  this.value;
        Ext.Ajax.request({
          url: 'proxyPMTablesFieldList',
          success: function(response) {
            var dataResponse = Ext.util.JSON.decode(response.responseText);
            var rec = Ext.data.Record.create(pmFields);
            //alert(firstGrid.store);
            var index;
            var record;
            var count = firstGrid.store.getTotalCount();

            // removing all the PM Table fields from the first grid
            do{
              index = firstGrid.store.find('fieldType','PM Table');
              record = firstGrid.store.getAt(index);
              if (index>=0)
                firstGrid.store.remove(record);
            }while (index>=0);

            // removing all the PM Table fields from the second grid
            do{
              index = secondGrid.store.find('fieldType','PM Table');
              record = secondGrid.store.getAt(index);
              if (index>=0)
                secondGrid.store.remove(record);
            }while (index>=0);

            for(var i = 0; i <= dataResponse.data.length-1; i++){
              var d = new rec( dataResponse.data[i] );
              firstGrid.store.add(d);
            }
            firstGrid.store.commitChanges();
          },
          failure: function(){},
          params: {xaction: 'getFieldsFromPMTable', table: tableUid }
        });

      }
    }
  });

  // create the Dropdown for rows per page
  var pmRowsPerPage = new Ext.form.ComboBox ({
    width         : 60,
    boxMaxWidth   : 70,
    editable      : false,
    triggerAction : 'all',
    mode          : 'local',    
    store        : new Ext.data.ArrayStore({
      fields: ['id'],
      data  : [[5], [6], [7], [8], [9], [10], [12], [15], [18], [20], [25], [30], [50], [100] ]
    }),
    valueField    : 'id',
    displayField  : 'id',
    triggerAction : 'all'
  });
  
  // create the Dropdown for date formats
  var pmDateFormat = new Ext.form.ComboBox ({
    width         : 80,
    boxMaxWidth   : 90,
    editable      : false,
    triggerAction : 'all',
    mode          : 'local',    
    store        : new Ext.data.ArrayStore({
      fields: ['id'],
      data  : [['M d, Y'],['M d Y'],['M d Y H:i:s'],['d M Y'],['d M Y H:i:s'],['Y-m-d'],['Y-m-d H:i:s'],['Y/m/d '],['Y/m/d H:i:s'],['D d M Y'] ]
    }),
    valueField    : 'id',
    displayField  : 'id',
    triggerAction : 'all'
  });
  
  PmTableStore.setDefaultSort('ADD_TAB_NAME', 'asc');
  PmTableStore.load();


  var remoteFieldsProxy = new Ext.data.HttpProxy({
    url : 'proxyPMTablesFieldList',
    autoSave: true,
    totalProperty: 'totalCount',
    successProperty: 'success',
    idProperty: 'gridIndex',
    root: 'data',
    messageProperty: 'message'
  });

  var readerPmFields = new Ext.data.JsonReader({
    totalProperty : 'totalCount',
    idProperty    : 'index',
    root          : 'data'
    }, pmFields
  );

  //currently we are not using this , but it is here just for complete definition
  var writerPmFields = new Ext.data.JsonWriter({
    //encode: true,
    writeAllFields: false
  });

  var remotePmFieldsStore = new Ext.data.Store({
    remoteSort : true,
    proxy      : remoteFieldsProxy,
    reader     : readerPmFields,
    writer     : writerPmFields,  // <-- plug a DataWriter into the store just as you would a Reader
    autoSave   : false // <-- false would delay executing create, update, destroy requests until specifically told to do so with some [save] buton.
  });
  
  // fields array used in first grid
  var fields = [
    {name: 'name', mapping : 'name'},
    {name: 'gridIndex', mapping : 'gridIndex'},
    {name: 'fieldType', mapping : 'fieldType'}
  ];

  // fields array used in second grid
  var fieldsSecond = [
    {name: 'name', mapping : 'name'},
    {name: 'gridIndex', mapping : 'gridIndex'},
    {name: 'fieldType', mapping : 'fieldType'},
    {name: 'label', mapping : 'label'},
    {name: 'width', mapping : 'width'},
    {name: 'align', mapping : 'align'}
  ];

  // Column Model shortcut array
  var cols = [
//    {header: "#",          width: 25,  sortable: false, dataIndex: 'gridIndex', hidden: true},
    {header: TRANSLATIONS.LABEL_HEADER_NUMBER,     width: 25,  sortable: false, dataIndex: 'gridIndex', hidden: true},
    {header: TRANSLATIONS.LABEL_HEADER_FIELD_NAME, width: 160, sortable: false, dataIndex: 'name'},
    {header: TRANSLATIONS.LABEL_HEADER_FIELD_TYPE, width: 70,  sortable: false, dataIndex: 'fieldType'}
  ];

  var labelTextField = new Ext.form.TextField ({
    allowBlank: true
  });

  var alignComboBox = new Ext.form.ComboBox ({
    editable : false,
    triggerAction: 'all',
    lazyRender:true,
    mode: 'local',
    store: new Ext.data.ArrayStore({
        id: 0,
        fields: [
            'id',
            'label'
        ],
        data: [['left', 'left'], ['center', 'center'], ['right', 'right']]
    }),
    valueField: 'id',
    displayField: 'label'
  });

  var widthTextField = new Ext.form.NumberField({
    allowBlank: false,
    allowNegative: false,
    maxValue: 800,
    minValue: 0
  });

  // Column Model shortcut array
//  var colsSecond = [
  var colsSecond = new Ext.grid.ColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: false // columns are not sortable by default           
        },
        columns: [
          {header: TRANSLATIONS.LABEL_HEADER_NUMBER,     width: 25,  dataIndex: 'gridIndex', hidden: true},
          {header: TRANSLATIONS.LABEL_HEADER_FIELD_NAME, width: 160, dataIndex: 'name'},
          {header: TRANSLATIONS.LABEL_HEADER_FIELD_TYPE, width: 70,  dataIndex: 'fieldType'},
//          {header: "Label",      width: 160, dataIndex: 'label',  editor: labelTextField },
          {header: TRANSLATIONS.LABEL_HEADER_LABEL,      width: 160, dataIndex: 'label',  editor: labelTextField },
//          {header: "Width",      width: 40,  dataIndex: 'width',  editor: widthTextField },
          {header: TRANSLATIONS.LABEL_HEADER_WIDTH,      width: 40,  dataIndex: 'width',  editor: widthTextField },
//          {header: "Align",      width: 60,  dataIndex: 'align',  editor: alignComboBox},
          {header: TRANSLATIONS.LABEL_HEADER_ALIGN,      width: 60,  dataIndex: 'align',  editor: alignComboBox},
        ]
    });
  
  // declare the source Grid
  var firstGrid = new Ext.grid.GridPanel({
    enableDragDrop   : true,
    width            : 240,
    ddGroup          : 'secondGridDDGroup',
//    ddText           : '{0} selected field{1}',
    ddText           : '{0} '+TRANSLATIONS.LABEL_SELECTED_FIELD+'{1}',
    store            : remotePmFieldsStore,
    columns          : cols,
    stripeRows       : true,
//    title            : 'Available Fields'
    title            : TRANSLATIONS.LABEL_AVAILABLE_FIELDS
  });

  var secondGridStore = new Ext.data.JsonStore({
    root            : 'data',
    totalProperty   : 'totalCount',
    fields          : fieldsSecond,
    remoteSort      : false, 
    successProperty : 'success'
  });
  
  // create the destination Grid
  var secondGrid = new Ext.grid.EditorGridPanel({
      enableDragDrop   : true,
      ddGroup          : 'firstGridDDGroup',
      selModel         : new Ext.grid.RowSelectionModel({singleSelect:true}),
      store            : secondGridStore,
      ddText           : '{0} '+TRANSLATIONS.LABEL_SELECTED_FIELD+'{1}',

      //columns          : colsSecond,
      clicksToEdit: 1,
      cm          : colsSecond,
      stripeRows       : true,
//      title            : 'Case List Fields'
      title            : TRANSLATIONS.LABEL_CASES_LIST_FIELDS
  });

 
  // Simple 'border layout' panel to house both grids

  function sendGridFieldsRequest(action) {
  	currentAction = action;
    Ext.Ajax.request({
      url: 'proxyPMTablesFieldList',
      success: function(response) {
        var dataResponse = Ext.util.JSON.decode(response.responseText);
        remotePmFieldsStore.loadData(dataResponse.first);
        secondGridStore.loadData(dataResponse.second);
        //remove APP_UID and DEL_INDEX from second grid, this is only to avoid display in this grid
        if ( secondGrid.store.data.items[1].data['name'] == 'DEL_INDEX' ) secondGrid.store.removeAt(1); //del_index
        if ( secondGrid.store.data.items[0].data['name'] == 'APP_UID'   ) secondGrid.store.removeAt(0); //app_uid
        // set also the selected table value in the comboBox element.
        pmTablesDropdown.setValue(dataResponse.PMTable);
        pmRowsPerPage.setValue(dataResponse.rowsperpage);
        pmDateFormat.setValue (dataResponse.dateformat );
      },
      failure: function(){},
      params: {xaction: 'read', action: action}
    });
  }
  
  function applyChanges() {
    var rs = firstGrid.store.data.items;
    var fv = [];
    for(var i = 0; i <= rs.length-1; i++){
      fv[i]= rs[i].data['name'];
    }
    var rs = secondGrid.store.data.items;
    var sv = [];
    for(var i = 0; i <= rs.length-1; i++){
      //sv[i]= rs[i].data['name'];
      sv[i]= rs[i].data;
    }

    Ext.Ajax.request({
      url: 'proxyPMTablesFieldList',
      success: function(response) {
        var dataResponse = Ext.util.JSON.decode(response.responseText);
        remotePmFieldsStore.loadData(dataResponse.first);
        secondGridStore.loadData(dataResponse.second);
        //remove APP_UID and DEL_INDEX from second grid, this is only to avoid display in this grid
        if ( secondGrid.store.data.items[1].data['name'] == 'DEL_INDEX' ) secondGrid.store.removeAt(1); //del_index
        if ( secondGrid.store.data.items[0].data['name'] == 'APP_UID'   ) secondGrid.store.removeAt(0); //app_uid
        pmRowsPerPage.setValue(dataResponse.rowsperpage);
        pmDateFormat.setValue (dataResponse.dateformat );

//        Ext.Msg.alert( 'info', 'saved' );
        Ext.Msg.alert( TRANSLATIONS.LABEL_INFO, TRANSLATIONS.LABEL_SAVED );
      },
      failure: function(){},
      params: {xaction: 'applyChanges', action: currentAction, first: Ext.util.JSON.encode(fv), second: Ext.util.JSON.encode(sv), pmtable: pmTablesDropdown.getValue(), rowsperpage: pmRowsPerPage.getValue(), dateformat: pmDateFormat.getValue() }
    });

  };
  
  function resetGrids() {
    Ext.Ajax.request({
      url: 'proxyPMTablesFieldList',
      success: function(response) {
        var dataResponse = Ext.util.JSON.decode(response.responseText);
        remotePmFieldsStore.loadData(dataResponse.first);
        secondGridStore.loadData(dataResponse.second);
        //remove APP_UID and DEL_INDEX from second grid, this is only to avoid display in this grid
        if ( secondGrid.store.data.items[1].data['name'] == 'DEL_INDEX' ) secondGrid.store.removeAt(1); //del_index
        if ( secondGrid.store.data.items[0].data['name'] == 'APP_UID'   ) secondGrid.store.removeAt(0); //app_uid
        pmTablesDropdown.setValue('');
        pmRowsPerPage.setValue(dataResponse.rowsperpage);
        pmDateFormat.setValue (dataResponse.dateformat );
      },
      failure: function(){},
      params: {xaction: 'reset', action: currentAction }
    });

  };
  
  var inboxPanel = new Ext.Panel({
//    title        : 'Inbox',
    title        : TRANSLATIONS.LABEL_TITLE_INBOX,
    listeners: {'activate': function() {
    	sendGridFieldsRequest('todo');
    }}  
  });

  var draftPanel = new Ext.Panel({
//    title        : 'Draft',
    title        : TRANSLATIONS.LABEL_TITLE_DRAFT,
    listeners: {'activate': function() {
    	sendGridFieldsRequest('draft');
    }}  
  });

  var participatedPanel = new Ext.Panel({
//    title        : 'Participated',
    title        : TRANSLATIONS.LABEL_TITLE_PARTICIPATED,
    listeners: {'activate': function() {
    	sendGridFieldsRequest('sent');
    }}  
  });

  var unassignedPanel = new Ext.Panel({
//    title        : 'Unassigned',
    title        : TRANSLATIONS.LABEL_TITLE_UNASSIGNED,
    listeners: {'activate': function() {
    	sendGridFieldsRequest('unassigned');
    }}  
  });

  var pausedPanel = new Ext.Panel({
//    title        : 'Paused',
    title        : TRANSLATIONS.LABEL_TITLE_PAUSED,
    listeners: {'activate': function() {
    	sendGridFieldsRequest('paused');
    }}  
  });

  var completedPanel = new Ext.Panel({
//    title        : 'Completed',
    title        : TRANSLATIONS.LABEL_TITLE_COMPLETED,
    listeners: {'activate': function() {
    	sendGridFieldsRequest('completed');
    }}  
  });

  var cancelledPanel = new Ext.Panel({
//    title        : 'Cancelled',
    title        : TRANSLATIONS.LABEL_TITLE_CANCELLED,
    listeners: {'activate': function() {
    	sendGridFieldsRequest('cancelled');
    }}  
  });

  var mainPanel = new Ext.Panel({
    title        : '',
    renderTo     : 'alt-panel',
    width        : 750,
    height       : 460,
    layout       : 'hbox',
    layoutConfig : {align : 'stretch'},
    tbar         : new Ext.Toolbar({
      items: [
//        'PM Table',
        TRANSLATIONS.LABEL_PM_TABLE,
        pmTablesDropdown,
        ' ',
//        'Rows per page',
        TRANSLATIONS.LABEL_ROWS_PER_PAGE,
        pmRowsPerPage,
        ' ',
//        'Date format',
        TRANSLATIONS.LABEL_DATE_FORMAT,
        pmDateFormat
      ]
    }),
    items        : [
      firstGrid,
      secondGrid
    ],
    bbar         : [
      '->',
      {
//        text    : 'Reset',
        text    : TRANSLATIONS.LABEL_RESET,
        handler: function(){
          resetGrids();
        }
      }, ' ',
      {
//        text    : 'Apply changes',
        text    : TRANSLATIONS.LABEL_APPLY_CHANGES,
        handler: function(){
          applyChanges();
        }
      }]

  });

var tabs = new Ext.TabPanel({
	renderTo       : 'panel',
	activeTab      : 0,
  width          : 750,
//  height         : 10,
	//deferredRender : false,
	//autoTabs       : true,
	items          : [
    inboxPanel,
    draftPanel,
    participatedPanel,
    unassignedPanel,
    pausedPanel,
    completedPanel,
    cancelledPanel
	]
});

// used to add records to the destination stores

  // Setup Drop Targets
  // This will make sure we only drop to the  view scroller element
  var firstGridDropTargetEl =  firstGrid.getView().scroller.dom;
  var firstGridDropTarget = new Ext.dd.DropTarget(firstGridDropTargetEl, {
       ddGroup    : 'firstGridDDGroup',
       notifyDrop : function(ddSource, e, data){
         var records =  ddSource.dragData.selections;
         Ext.each(records, ddSource.grid.store.remove, ddSource.grid.store);
         firstGrid.store.add(records);
         firstGrid.store.commitChanges();
         //firstGrid.store.sort('gridIndex', 'ASC');
         return true
       }
  });
  
  


  // This will make sure we only drop to the view scroller element
  var secondGridDropTargetEl = secondGrid.getView().scroller.dom;
  var secondGridDropTarget = new Ext.dd.DropTarget(secondGridDropTargetEl, {

      //ddGroup    : 'secondGridDDGroup',
      notifyDrop : function(ddSource, e, data){

        if ( ddSource.ddGroup == 'firstGridDDGroup') {
          var selectedRecord = secondGrid.getSelectionModel().getSelected();
          // to get value of a field in the record
          var valSource = selectedRecord.get('gridIndex');  

          var rowTargetId = secondGrid.getView().findRowIndex(e.getTarget());
          var recTarget = secondGrid.getStore().getAt(rowTargetId);
          var valTarget = recTarget.get('gridIndex');  

          var newIndex = 0;
          for (i=0; i< secondGrid.store.getCount(); i++ ) {
          	var record = secondGrid.getStore().getAt(i);
            if ( record.get('gridIndex') == valSource ) {
            	record.set('gridIndex',valTarget);    	
            }
            else {
              incIndexB = 1;
              isBrecord = 0;
              if ( record.get('gridIndex') == valTarget ) isBrecord = true;
              
              if ( isBrecord && newIndex == record.get('gridIndex') ) {newIndex++;incIndexB = false;}
              record.set('gridIndex', newIndex);    	
              newIndex++;
              if ( isBrecord && incIndexB ) newIndex++;
            }
          }
          secondGrid.store.sort('gridIndex', 'ASC');
          return true;
        };

        var records =  ddSource.dragData.selections;
        Ext.each(records, ddSource.grid.store.remove, ddSource.grid.store);
        secondGrid.store.add(records);
        
        //reorder fields, putting a secuencial index for all records
        for (i=0; i< secondGrid.store.getCount(); i++ ) {
        	var record = secondGrid.getStore().getAt(i);
          record.set('gridIndex', i );
        }        
        secondGrid.store.commitChanges();
//        secondGrid.store.sort('gridIndex', 'ASC');
        return true
      }
  });
  secondGridDropTarget.addToGroup('secondGridDDGroup');
  secondGridDropTarget.addToGroup('firstGridDDGroup');

});
