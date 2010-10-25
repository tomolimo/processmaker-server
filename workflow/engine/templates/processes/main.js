/*
 * @author: Erik A. Ortiz
 * Aug 20th, 2010 
 */
var processesGrid;
var store;
var comboCategory;

new Ext.KeyMap(document, {
  key: Ext.EventObject.F5,
  fn: function(keycode, e) {
      if (! e.ctrlKey) {
        if (Ext.isIE)
            e.browserEvent.keyCode = 8;
        e.stopEvent();
        document.location = document.location;
      }
      else
        Ext.Msg.alert('Refresh', 'You clicked: CTRL-F5');
  }
});


Ext.onReady(function(){
  //Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
  Ext.QuickTips.init();

  store = new Ext.data.GroupingStore( {
  //var store = new Ext.data.Store( {
    proxy : new Ext.data.HttpProxy({
      url: 'processesList'
    }),

    reader : new Ext.data.JsonReader( {
      totalProperty: 'totalCount',
      root: 'data',
      fields : [
        {name : 'PRO_DESCRIPTION'},
        {name : 'PRO_UID'},
        {name : 'PRO_CATEGORY_LABEL'},
        {name : 'PRO_TITLE'},
        {name : 'PRO_STATUS'},
        {name : 'PRO_STATUS_LABEL'},
        {name : 'PRO_CREATE_DATE'},
        {name : 'PRO_DEBUG_LABEL'},
        {name : 'PRO_CREATE_USER_LABEL'},
        {name : 'CASES_COUNT', type:'float'},
        {name : 'CASES_COUNT_DRAFT', type:'float'},
        {name : 'CASES_COUNT_TO_DO', type:'float'},
        {name : 'CASES_COUNT_COMPLETED', type:'float'},
        {name : 'CASES_COUNT_CANCELLED', type:'float'}
      ]
    }),
    sortInfo:{field: 'PRO_TITLE', direction: "ASC"},
    //groupField:'PRO_CATEGORY_LABEL'

  });
  
  
  var expander = new Ext.ux.grid.RowExpander({
    tpl : new Ext.Template(
        '<p><b>Process Description:</b> {PRO_DESCRIPTION}</p><br>'
    )
  });

  comboCategory = new Ext.form.ComboBox({
      fieldLabel : 'Categoty',
      hiddenName : 'category',
      store : new Ext.data.Store( {
        proxy : new Ext.data.HttpProxy( {
          url : 'mainAjax',
          method : 'POST'
        }),
        baseParams : {
          request : 'categoriesList'
        },
        reader : new Ext.data.JsonReader( {
          root : 'rows',
          fields : [ {
            name : 'CATEGORY_UID'
          }, {
            name : 'CATEGORY_NAME'
          } ]
        })
      }),
      valueField : 'CATEGORY_UID',
      displayField : 'CATEGORY_NAME',
      triggerAction : 'all',
      emptyText : 'Select',
      selectOnFocus : true,
      editable : true,
      width: 180,
      allowBlank : true,
      autocomplete: true,
      typeAhead: true,
      allowBlankText : 'You should to select a language from the list.',
      listeners:{
      scope: this,
      'select': function() {
        filter = comboCategory.value;
        store.setBaseParam( 'category', filter);
        store.load({params:{category: filter, start : 0 , limit : 25 }});
      }}
    })
  
  processesGrid = new Ext.grid.GridPanel( {
    region: 'center',
    layout: 'fit',
    id: 'processesGrid',
    height:500,
    //autoWidth : true,
    with:'',
    title : '',
    stateful : true,
    stateId : 'grid',
    enableColumnResize: true,
    enableHdMenu: true,
    frame:false,
    plugins: expander,
    columnLines: true,

    
    /*view: new Ext.grid.GroupingView({
        //forceFit:true,
        //groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
        groupTextTpl: '{text}'
    }),*/
    viewConfig: {
      forceFit:true
    },
    cm: new Ext.grid.ColumnModel({
      defaults: {
          width: 200,
          sortable: true
      },
      columns: [
          expander,
          {id:'PRO_UID', dataIndex: 'PRO_UID', hidden:true},
          {header: "Description", dataIndex: 'PRO_DESCRIPTION',hidden:true},
          {header: "Process Title", dataIndex: 'PRO_TITLE', width: 300},
          {header: "Categoty", dataIndex: 'PRO_CATEGORY_LABEL', width: 100, hidden:false},
          {header: "Status", dataIndex: 'PRO_STATUS', width: 50, hidden:true},
          {header: "Status", dataIndex: 'PRO_STATUS_LABEL', width: 50},
          {header: "User owner", dataIndex: 'PRO_CREATE_USER_LABEL', width: 150},
          {header: "Create date", dataIndex: 'PRO_CREATE_DATE', width: 90},
          {header: "Debug", dataIndex: 'PRO_DEBUG_LABEL', width: 50, align:'center'},
          
          {header: "Inbox", dataIndex: 'CASES_COUNT_TO_DO', width: 50, align:'right'},
          {header: "Draft", dataIndex: 'CASES_COUNT_DRAFT', width: 50, align:'right'},
          {header: "Completed", dataIndex: 'CASES_COUNT_COMPLETED', width: 70, align:'right'},
          {header: "Cancelled", dataIndex: 'CASES_COUNT_CANCELLED', width: 70, align:'right'},
          {header: "Total Cases", dataIndex: 'CASES_COUNT', width: 80,renderer:function(v){return "<b>"+v+"</b>";}, align:'right'}
      ]
    }),

    store: store,

    tbar:[
      {
        text:'New',
        iconCls: 'silk-add',
        icon: '/images/addc.png',
        handler: newProcess
      },{
        text:'Edit',
        iconCls: 'silk-add',
        icon: '/images/edit.gif',
        handler: editProcess
      },{
        text:'Status',
        id:'activator',
        icon: '',
        iconCls: 'silk-add',
        handler: activeDeactive,
        disabled:true
      },{
        text:'Delete',
        iconCls: 'silk-add',
        icon: '/images/delete-16x16.gif',
        handler:deleteProcess
      },{
        xtype: 'tbseparator'
      },{
        text:'Import',
        iconCls: 'silk-add',
        icon: '/images/import.gif',
        handler:importProcess
      },/*{
        text:'Export',
        iconCls: 'silk-add',
        icon: '/images/export.png',
      },*/{
        text:'Browse Library',
        iconCls: 'silk-add',
        icon: '/images/icon-pmwebservices.png',
        handler: browseLibrary
      },
      {
        xtype: 'tbfill'
      },{
        xtype: 'tbseparator'
      },
      'Category',
      comboCategory,{
        xtype: 'tbseparator'
      },new Ext.form.TextField ({
        id: 'searchTxt',
        allowBlank: true,
        width: 150,
        emptyText: 'enter search term',
        listeners: {
          specialkey: function(f,e){
            if (e.getKey() == e.ENTER) {
              doSearch();
            }
          }
        }
      }),{
        text:'Search',
        handler: doSearch
      },
      {
        text:'X',
        handler: function(){
          store.setBaseParam( 'category', '<reset>');
          store.setBaseParam( 'processName', '');
          store.load({params:{start : 0 , limit : '' }});
          Ext.getCmp('searchTxt').setValue('');
          comboCategory.setValue('');
          //store.reload();
        }
      }
    ],
 // paging bar on the bottom
    /*bbar: new Ext.PagingToolbar({
        pageSize: 15,
        store: store,
        displayInfo: true,
        displayMsg: 'Displaying topics {0} - {1} of {2}',
        emptyMsg: "No topics to display",
        items:[]
    }),*/
    listeners: {
      rowdblclick: editProcess,
      rowclick: function(){
        //var rowSelected = processesGrid.getSelectionModel().getSelected();
        //alert(rowSelected.PRO_UID);
        //Ext.getCmp('activator').setIcon();
      },
      render: function(){
        this.loadMask = new Ext.LoadMask(this.body, {msg:'Loading...'});
        //this.ownerCt.doLayout();
        processesGrid.getSelectionModel().on('rowselect', function(){
        var rowSelected = processesGrid.getSelectionModel().getSelected();
        //alert(rowSelected.data.PRO_STATUS);
        var activator = Ext.getCmp('activator');
        activator.setDisabled(false);
        if( rowSelected.data.PRO_STATUS == 'ACTIVE' ){
          activator.setIcon('/images/deactivate.png');
          activator.setText('Deactivate');
        } else {
          activator.setIcon('/images/activate.png');
          activator.setText('Activate');
        }
         
        });
      }
  }

  });

  processesGrid.store.load({params: {"function":"languagesList"}});

  //////////////////////store.load({params: {"function":"xml"}});

  
  //processesGrid.render('processes-panel');
  
  //processesGrid.render(document.body);
  //fp.render('form-panel');


  var viewport = new Ext.Viewport({
    layout: 'border',
    autoScroll: true,
    items: [
      processesGrid
    ]
  });
});


function newProcess(){
  window.location = 'processes_New';
}

function doSearch(){
  if(comboCategory.getValue() == '')
    store.setBaseParam( 'category', '<reset>');
  filter = Ext.getCmp('searchTxt').getValue();
  
  store.setBaseParam('processName', filter);
  store.load({params:{processName: filter, start : 0 , limit : 25 }});
}

editProcess = function(){
  var rowSelected = processesGrid.getSelectionModel().getSelected();
  if( rowSelected ) {
    location.href = 'processes_Map?PRO_UID='+rowSelected.data.PRO_UID+'&rand='+Math.random()
  } else {
     Ext.Msg.show({
      title:'',
      msg: 'Select a process from the list please',
      buttons: Ext.Msg.INFO,
      fn: function(){},
      animEl: 'elId',
      icon: Ext.MessageBox.INFO,
      buttons: Ext.MessageBox.OK
    });
  }
}

deleteProcess = function(){
  var rowSelected = processesGrid.getSelectionModel().getSelected();
  if( rowSelected ) {
    parent.dropProcess(rowSelected.data.PRO_UID);
  } else {
     Ext.Msg.show({
      title:'',
      msg: 'Select a process from the list please',
      buttons: Ext.Msg.INFO,
      fn: function(){},
      animEl: 'elId',
      icon: Ext.MessageBox.INFO,
      buttons: Ext.MessageBox.OK
    });
  }
}

importProcess = function(){
  window.location = 'processes_Import';
}

browseLibrary = function(){
  window.location = 'processes_Library';
}

function activeDeactive(){
  var rowSelected = processesGrid.getSelectionModel().getSelected();
  if( rowSelected ) {
    Ext.Ajax.request({
      url : 'processes_ChangeStatus' ,
      params : { PRO_UID : rowSelected.data.PRO_UID },
      method: 'GET',
      success: function ( result, request ) {
        //Ext.MessageBox.alert('Success', 'Data return from the server: '+ result.responseText);
        store.reload();
        var activator = Ext.getCmp('activator');
        activator.setDisabled(true);
        activator.setText('Status');
        activator.setIcon('');
      },
      failure: function ( result, request) {
        Ext.MessageBox.alert('Failed', result.responseText);
      }
    });
    
    //window.location = 'processes_ChangeStatus?PRO_UID='+rowSelected.data.PRO_UID;
  } else {
     Ext.Msg.show({
      title:'',
      msg: 'Select a process from the list please',
      buttons: Ext.Msg.INFO,
      fn: function(){},
      animEl: 'elId',
      icon: Ext.MessageBox.INFO,
      buttons: Ext.MessageBox.OK
    });
  }
}

capitalize = function(s){
  s = s.toLowerCase();
  return s.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
};