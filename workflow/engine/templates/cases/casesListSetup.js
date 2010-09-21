Ext.onReady(function(){

  var myData = {
    records : availableFields
  };

  var remoteProxy = new Ext.data.HttpProxy({
     url : 'proxyPMTablesList'
  });

  // Generic fields array to use in both store defs.
  var pmFields = [
    {name: 'name', mapping : 'name'},
    {name: 'gridIndex', mapping : 'gridIndex'},
    {name: 'column2', mapping : 'column2'}
  ];

  var remotePmTableStore = new Ext.data.JsonStore({
     root            : 'data',
     proxy           : remoteProxy,
     totalProperty   : 'totalCount',
     idProperty      : 'index',
     remoteSort      : true,
     autoLoad        : false,
     fields          : [
       'ADD_TAB_UID','ADD_TAB_NAME'
     ]
  });
  remotePmTableStore.setDefaultSort('ADD_TAB_NAME', 'asc');

  var remoteFieldsProxy = new Ext.data.HttpProxy({
    url : 'proxyPMTablesFieldList'
  });

  var readerCasesList = new Ext.data.JsonReader({
    totalProperty : 'totalCount',
    idProperty    : 'index',
    root          : 'data'
    }, pmFields
  );

  // The new DataWriter component.
  //currently we are not using this in casesList, but it is here just for complete definition
  var writerCasesList = new Ext.data.JsonWriter({
    encode: true,
    writeAllFields: true
  });

  var remotePmFieldsStore = new Ext.data.Store({
    remoteSort : true,
    proxy      : remoteFieldsProxy,
    reader     : readerCasesList,
    writer     : writerCasesList,  // <-- plug a DataWriter into the store just as you would a Reader
    autoSave   : true // <-- false would delay executing create, update, destroy requests until specifically told to do so with some [save] buton.
  });

  // create the Data Store for processes
  var pmTablesDropdown = {
    width        : '180',
    xtype        : 'combo',
    emptyText    : 'Select a configuration...',
    store        : remotePmTableStore,
    displayField : 'ADD_TAB_NAME',
    valueField   : 'ADD_TAB_UID',

  //     displayField : 'APP_PRO_TITLE',
     //typeAhead    : true,
    triggerAction: 'all',
    listeners: {
      'select': function() {
        var tableUid  =  this.value;
        //alert (tableUid);
        remotePmFieldsStore.setBaseParam( 'tab', tableUid);
        remotePmFieldsStore.load({params:{tab: tableUid}});

//        firstGridStore.loadData(myData);
        //firstGridStore.loadData(remotePmFieldsStore);
           //purge destination grid
        secondGridStore.removeAll();
        /*firstGridStore.add(new Field({
          name: 'Ned',
          gridIndex: '3',
          column2 : '1'
        }));*/
         //firstGridStore.add (remotePmFieldsStore);
      }
    }
  }

  // Generic fields array to use in both store defs.
  var fields = [
    {name: 'name', mapping : 'name'},
    {name: 'gridIndex', mapping : 'gridIndex'},
    {name: 'column2', mapping : 'column2'}
  ];


  // create the data store
  var firstGridStore = new Ext.data.JsonStore({
    fields : fields,
    data   : myData,
    root   : 'records'
  });
 

  // Column Model shortcut array
  var cols = [
    {header: "#",          width: 25,  sortable: false, dataIndex: 'gridIndex', hidden: true},
    {header: "Field Name", width: 160, sortable: false, dataIndex: 'name'},
    {header: "Field Type", width: 80,  sortable: false, dataIndex: 'column2'}
  ];

  // declare the source Grid
    var firstGrid = new Ext.grid.GridPanel({
      enableDragDrop   : true,
      ddGroup          : 'secondGridDDGroup',
      ddText           : '{0} selected field{1}',
      store            : remotePmFieldsStore,
      columns          : cols,
      stripeRows       : true,
      title            : 'Available Fields'
    });
    remotePmFieldsStore.load();
    
    var secondGridStore = new Ext.data.JsonStore({
      fields : fields,
      root   : 'records'
    });

    // create the destination Grid
    var secondGrid = new Ext.grid.GridPanel({
        enableDragDrop   : true,
        ddGroup          : 'firstGridDDGroup',
        store            : secondGridStore,
        columns          : cols,
        stripeRows       : true,
        title            : 'Case List Fields'
    });


  // Simple 'border layout' panel to house both grids
  var filterPanel = new Ext.Panel({
    title        : 'Select Additional Fields',
    width        : 200,
    height       : 50,
    layout       : 'hbox',
    renderTo     : 'panel',
    defaults     : {flex : 1}, //auto stretch
    layoutConfig : {align : 'center'},
    items        : [
      pmTablesDropdown
    ]
  });

  var displayPanel = new Ext.Panel({
    width        : 650,
    height       : 400,
    layout       : 'hbox',
    renderTo     : 'panel',
    defaults     : {flex : 1}, //auto stretch
    layoutConfig : {align : 'stretch'},
    items        : [
      firstGrid,
      secondGrid
    ],
    bbar    : [
      '->', // Fill
      {
        text    : 'Reset both grids',
        handler : function() {
          //refresh source grid
          var tableUid  =  pmTablesDropdown.value;
          // alert (tableUid);
          remotePmFieldsStore.setBaseParam( 'tab', tableUid);
          remotePmFieldsStore.load({params:{tab: tableUid}});
          //purge destination grid
          secondGridStore.removeAll();
        }
      }
    ]
  });

  var mainPanel = new Ext.Panel({
    width        : 650,
    height       : 450,
    layout       : 'vbox',
    renderTo     : 'panel',
    defaults     : {flex : 1}, //auto stretch
    layoutConfig : {align : 'center'},
    items        : [
      filterPanel,
      displayPanel
    ]
  });

// used to add records to the destination stores
//  var blankRecord =  Ext.data.Record.create(fields);

  // Setup Drop Targets
  // This will make sure we only drop to the  view scroller element
  var firstGridDropTargetEl =  firstGrid.getView().scroller.dom;
  var firstGridDropTarget = new Ext.dd.DropTarget(firstGridDropTargetEl, {
       ddGroup    : 'firstGridDDGroup',
       notifyDrop : function(ddSource, e, data){
         var records =  ddSource.dragData.selections;
         Ext.each(records, ddSource.grid.store.remove, ddSource.grid.store);
         firstGrid.store.add(records);
         firstGrid.store.sort('gridIndex', 'ASC');
         return true
       }
  });


  // This will make sure we only drop to the view scroller element
  var secondGridDropTargetEl = secondGrid.getView().scroller.dom;
  var secondGridDropTarget = new Ext.dd.DropTarget(secondGridDropTargetEl, {
      //ddGroup    : 'secondGridDDGroup',
      notifyDrop : function(ddSource, e, data){
        if ( ddSource.ddGroup == 'firstGridDDGroup') {
          //var records =  ddSource.dragData.selections;
          //var record = secondGrid.getStore().getAt(0);
          //record.set('gridIndex', secondGrid.getStore().getCount() +1 );
          
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
          secondGrid.getStore().commitChanges();
          secondGrid.store.sort('gridIndex', 'ASC');
                    
//          Ext.Msg.alert('hover', rowTargetId + "--" + valTarget + "--" + valSource + text);

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
        secondGrid.getStore().commitChanges();
        secondGrid.store.sort('gridIndex', 'ASC');
        return true
      }
  });
  secondGridDropTarget.addToGroup('secondGridDDGroup');
  secondGridDropTarget.addToGroup('firstGridDDGroup');


});
