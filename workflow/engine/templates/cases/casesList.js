Ext.onReady ( function() {
  var filterProcess = '';

  function openLink(value, p, r){
    return String.format("<a href='../cases/cases_Open?APP_UID={0}&DEL_INDEX={1}&content=inner'>Open</a>", r.data['APP_UID'], r.data['DEL_INDEX'], r.data['APP_TITLE']);
  }
   
  function showDate (value,p,r) {
    if(!Ext.isDate( value )){
    	var myArray = value.split(' '); 
    	var myArrayDate = myArray[0].split('-'); 
    	var myDate = new Date( myArrayDate[0],myArrayDate[1],myArrayDate[2]);
    }
    return String.format("{0}", myDate.dateFormat( 'm/d/Y'));
  } 
  
  function dueDate(value, p, r){
    if(!Ext.isDate( value )){
    	var myArray = value.split(' '); 
    	var myArrayDate = myArray[0].split('-'); 
    	var myDate = new Date( myArrayDate[0],myArrayDate[1],myArrayDate[2]);
    }
    var myColor =  (myDate < new Date()) ? " color:red;" : 'color:green;'; 
    return String.format("<span style='{1}'>{0}</span>", myDate.dateFormat( 'm/d/Y'), myColor );
  }
   
	columns.push ( { header: "", width: 50, dataIndex: 'DEL_PRIORITY', sortable: false, renderer: openLink, menuDisabled: true, id: 'openLink'});

  for(var i = 0, len = columns.length; i < len; i++){
    var c = columns[i];
      if( c.dataIndex == 'DEL_TASK_DUE_DATE') c.renderer = dueDate;
      if( c.dataIndex == 'APP_UPDATE_DATE') c.renderer = showDate;
  };

	
  var cm = new Ext.grid.ColumnModel({
    defaults: {
      sortable: true // columns are sortable by default           
    },
      columns: columns
    });

  // Create HttpProxy instance, all CRUD requests will be directed to single proxy url.
  var proxyCasesList = new Ext.data.HttpProxy({
    api: {
      read :   'proxyCasesList?t=new',
    }
  });

  // Typical JsonReader with additional meta-data params for defining the core attributes of your json-response
  // the readerFields is defined in PHP server side
  var readerCasesList = new Ext.data.JsonReader({
    totalProperty: 'totalCount',
    successProperty: 'success',
    idProperty: 'index',
    root: 'data',
    messageProperty: 'message'  
    }, 
    readerFields
  );

  // The new DataWriter component.
  //currently we are not using this in casesList, but it is here just for complete definition
  var writerCasesList = new Ext.data.JsonWriter({
    encode: true,
    writeAllFields: true
  });

  // Typical Store collecting the Proxy, Reader and Writer together.
  // This is the store for Cases List
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
  var comboProcess = new Ext.form.ComboBox({
    width: 180,
    store: storeProcesses,
    displayField: 'APP_PRO_TITLE',
    typeAhead: true,
    //mode: 'local',
    maxHeight: 200,
    forceSelection: true,
    triggerAction: 'all',
    emptyText: 'Select a process...',
    selectOnFocus: true,
    getListParent: function() {
      return this.el.up('.x-menu');
    },
    listeners:{
      scope: this,
      'select': function() {
        filterProcess = comboProcess.value;
        storeCases.setBaseParam( 'process', filterProcess);
        storeCases.load({params:{process: filterProcess, start : 0 , limit : pageSize }});
      }},
    iconCls: 'no-icon'  //use iconCls if placing within menu to shift to right side of menu
  });
  
  var textSearch = new Ext.form.TextField ({
    allowBlank: false,
    emptyText: 'enter search term'
  });

  var btnSearch = new Ext.Button ({
    text: 'search',
    handler: function(){
      searchText = textSearch.getValue();
      storeCases.setBaseParam( 'search', searchText);
      storeCases.load({params:{ start : 0 , limit : pageSize }});
    }
  });

  var textJump = new Ext.form.TextField ({
    allowBlank: false,
    emptyText: 'case Id'
  });

  var btnJump = new Ext.Button ({
    text: 'jump',
    handler: function(){
      jump = textJump.getValue();
      location.href = '../cases/cases_Open?APP_NUMBER=' + jump +'&content=inner'; 
    }
  });

  var tb = new Ext.Toolbar({
    height: 35,
    items: [
      btnRead,
      '-',
      btnUnread,
      '-',
      btnAll,
      '-',
      'process', 
      comboProcess,
      '->', // begin using the right-justified button container 
      textSearch,
      btnSearch,
      '-',
      textJump,
      btnJump,
      ' ',
      ' '
    ]
  });


  // create the editor grid
  var grid = new Ext.grid.GridPanel({
    store: storeCases,
    cm: cm,
    renderTo: 'cases-grid',
    frame: false,
    autoHeight:true,
    minHeight:400,
    layout: 'fit',
/*
    viewConfig: {
      forceFit:true
  },
*/
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
    storeProcesses.load();

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
