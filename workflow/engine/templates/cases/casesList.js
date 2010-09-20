Ext.onReady ( function() {
  var pageSize = 20;
  
  var cm = new Ext.grid.ColumnModel({
    // specify any defaults for each column
    defaults: {
      sortable: true // columns are not sortable by default           
    },
    columns: [
      {
        header: '#',
        dataIndex: 'APP_NUMBER',
        width: 45,
        align: 'center'
      }, {
        header: 'Case',
        dataIndex: 'APP_TITLE',
        width: 150
      }, {
        header: 'Task',
        dataIndex: 'APP_TAS_TITLE',
        width: 150
      }, {
        header: 'Process',
        dataIndex: 'APP_PRO_TITLE',
        width: 150
      }, {
        header: 'Sent by',
        dataIndex: 'APP_DEL_PREVIOUS_USER',
        width: 90,
        align:  'center'
      }, {
        header: 'Due Date',
        dataIndex: 'DEL_TASK_DUE_DATE',
        width: 110
      }, {
        header: 'Last Modify',
        dataIndex: 'APP_UPDATE_DATE',
        width: 110,
      }, {
        header: 'Priority',
        dataIndex: 'DEL_PRIORITY',
        width: 50,
      }      
      ]
    });

  // Create HttpProxy instance, all CRUD requests will be directed to your single url instead.
  var proxyCasesList = new Ext.data.HttpProxy({
    api: {
      read :   'proxyCasesList?t=new',
    }
  });

// Typical JsonReader with additional meta-data params for defining the core attributes of your json-response
var readerCasesList = new Ext.data.JsonReader({
    totalProperty: 'totalCount',
    successProperty: 'success',
    idProperty: 'index',
    root: 'data',
    messageProperty: 'message'  
  }, [
    {name: 'APP_UID'},
    {name: 'APP_NUMBER'},
    {name: 'APP_STATUS'},
    {name: 'DEL_INDEX'},
    {name: 'APP_TITLE'},
    {name: 'APP_PRO_TITLE'},
    {name: 'APP_TAS_TITLE'},
    {name: 'APP_DEL_PREVIOUS_USER'},
    {name: 'DEL_TASK_DUE_DATE'},
    {name: 'APP_UPDATE_DATE'},
    {name: 'DEL_PRIORITY'}
]);

// The new DataWriter component.
var writerCasesList = new Ext.data.JsonWriter({
    encode: true,
    writeAllFields: true
});

// Typical Store collecting the Proxy, Reader and Writer together.
var storeCases = new Ext.data.Store({
    remoteSort: true,
    proxy: proxyCasesList,
    reader: readerCasesList,
    writer: writerCasesList,  // <-- plug a DataWriter into the store just as you would a Reader
    autoSave: true // <-- false would delay executing create, update, destroy requests until specifically told to do so with some [save] buton.
});

// create the Data Store for processes
var storeProcesses = new Ext.data.JsonStore({
    root: 'data',
    totalProperty: 'totalCount',
    idProperty: 'index',
    remoteSort: true,

    fields: [
        'PRO_UID', 'APP_PRO_TITLE'
    ],
    proxy: new Ext.data.HttpProxy({
        url: 'proxyProcessList?t=new'
    })
});
storeProcesses.setDefaultSort('APP_PRO_TITLE', 'asc');


// creating the button for filters
var btnRead = new Ext.Button ({
    id: 'read',
    text: 'read',
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: false,
    pressed: false
 });
 
var btnUnread = new Ext.Button ({
    id: 'unread',
    text: 'unread',
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: false,
    pressed: false
 });
 
var btnAll = new Ext.Button ({
    id: 'all',
    text: 'all',
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: false,
    pressed: true
 });
 
// ComboBox creation
var combo = new Ext.form.ComboBox({
    store: storeProcesses,
    displayField: 'APP_PRO_TITLE',
    typeAhead: true,
    //mode: 'local',
    maxHeight: 150,
    forceSelection: true,
    triggerAction: 'all',
    emptyText: 'Select a process...',
    selectOnFocus: true,
    width: 135,
    getListParent: function() {
      return this.el.up('.x-menu');
    },
    iconCls: 'no-icon' //use iconCls if placing within menu to shift to right side of menu
});

var tb = new Ext.Toolbar({
  height: 35,
  items: [
    btnRead,
    btnUnread,
    btnAll,
    {xtype: 'tbspacer', width: 30},
    'process', 
    combo,
    '->', // begin using the right-justified button container 
    {
      xtype: 'textfield',
      name: 'search',
      emptyText: 'enter search term'
    }, {
      text: 'search'
    }, 
    '-',
    {
      xtype: 'textfield',
      width: 80,
      name: 'jump',
      emptyText: 'case id'
    }, {
      text: 'jump'
    }
    ]
});

  /**
  * buildTopToolbar
  */
var buildTopToolbar = new function() {
    return [{
      text: 'Add',
      iconCls: 'silk-add',
      handler: this.onAdd,
      scope: this
    }, '-', {
      text: 'Delete',
      iconCls: 'silk-delete',
      handler: this.onDelete,
      scope: this
    }, '-'];
  };

  // create the editor grid
  var grid = new Ext.grid.GridPanel({
    store: storeCases,
    cm: cm,
    renderTo: 'cases-grid',
    frame: false,
    autoHeight:true,
    minHeight:400,
    layout: 'fit',
    tbar: tb,
    // paging bar on the bottom
    bbar: new Ext.PagingToolbar({
      pageSize: pageSize,
      store: storeCases,
      displayInfo: true,
      displayMsg: 'Displaying items {0} - {1} of {2}',
      emptyMsg: "No items to display",
    })

    });

  
    // manually trigger the data store load
    storeCases.load({params:{start:0, limit: pageSize }});

    function createBox(t, s){
        return ['<div class="msg">',
                '<div class="x-box-tl"><div class="x-box-tr"><div class="x-box-tc"></div></div></div>',
                '<div class="x-box-ml"><div class="x-box-mr"><div class="x-box-mc"><h3>', t, '</h3>', s, '</div></div></div>',
                '<div class="x-box-bl"><div class="x-box-br"><div class="x-box-bc"></div></div></div>',
                '</div>'].join('');
    }

    function onItemToggle(item, pressed){
    	switch ( item.id ) {
    		case 'read' : 
    		  btnUnread.toggle( false, true);
    		  btnAll.toggle( false, true);
    		  break;
    		case 'unread' : 
    		  btnRead.toggle( false, true);
    		  btnAll.toggle( false, true);
    		  break;
    		case 'all' : 
    		  btnRead.toggle( false, true);
    		  btnUnread.toggle( false, true);
    		  break;
    	}
      storeCases.load({params:{start:0, limit: pageSize, filter: item.id }});
      storeProcesses.load();
/*
      title = item.text;

      var msgCt = Ext.get('msg-div');
      msgCt.alignTo(document, 't-t');
      var s = String.format.apply(String, ['Button "{0}" was toggled to {1}.', item.text, pressed]);
      var m = Ext.DomHelper.append(msgCt, {html:createBox(title, s)}, true);
      m.slideIn('t').pause(1).ghost("t", {remove:true});              
*/      
    }

});
