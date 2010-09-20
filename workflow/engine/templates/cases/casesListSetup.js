Ext.onReady(function(){

    var myData = {
    records : availableFields
  };


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
        store            : firstGridStore,
        columns          : cols,
        stripeRows       : true, 
        title            : 'Available Fields'
    });

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


  //Simple 'border layout' panel to house both grids
  var displayPanel = new Ext.Panel({
    width        : 650,
    height       : 400,
    layout       : 'hbox',
    renderTo     : 'panel',
    defaults     : { flex : 1 }, //auto stretch
    layoutConfig : { align : 'stretch' },
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
          firstGridStore.loadData(myData);

          //purge destination grid
          secondGridStore.removeAll();
        }
      }
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
              
              if ( isBrecord && newIndex == record.get('gridIndex') ) { newIndex++; incIndexB = false; }
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
