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
        //document.location = document.location;
        storeCases.reload();
      }    
      else 
    Ext.Msg.alert('Refresh', 'You clicked: CTRL-F5');
  }
});

//global variables
var storeCases;

Ext.onReady ( function() {
  var filterProcess = '';

  function openLink(value, p, r){
    return String.format("<a href='../cases/cases_Open?APP_UID={0}&DEL_INDEX={1}&content=inner'>Open</a>", r.data['APP_UID'], r.data['DEL_INDEX'], r.data['APP_TITLE']);
  }

  function convertDate ( value ) {
    myDate = new Date( 1900,0,1,0,0,0);
  try{
    if(!Ext.isDate( value )){
    	var myArray = value.split(' '); 
    	var myArrayDate = myArray[0].split('-'); 
    	if ( myArray.length > 1 ) 
    	  var myArrayHour = myArray[1].split(':');
    	else
    		var myArrayHour = new Array('0','0','0'); 
    	var myDate = new Date( myArrayDate[0],myArrayDate[1],myArrayDate[2],myArrayHour[0],myArrayHour[1],myArrayHour[2]);
    }
  }
  catch(e){};
    
    return myDate;
  }   
  function showDate (value,p,r) {
    var myDate = convertDate( value );
    return String.format("{0}", myDate.dateFormat( PMDateFormat ));
  } 
  
  function dueDate(value, p, r){
    var myDate = convertDate( value );
    var myColor =  (myDate < new Date()) ? " color:red;" : 'color:green;'; 
    return String.format("<span style='{1}'>{0}</span>", myDate.dateFormat(PMDateFormat), myColor );
  }

  function showField (value,p,r) {
    if ( r.data['DEL_INIT_DATE'] )
      return String.format("{0}", value );
    else
      return String.format("<b>{0}</b>", value );
  } 
  
  for(var i = 0, len = columns.length; i < len; i++){
    var c = columns[i];
    c.renderer = showField;
    if( c.dataIndex == 'DEL_TASK_DUE_DATE') c.renderer = dueDate;
    if( c.dataIndex == 'APP_UPDATE_DATE') c.renderer = showDate;
  };

  //adding the last column to open the cases_Open
	columns.push ( { header: "", width: 50, dataIndex: 'DEL_PRIORITY', sortable: false, renderer: openLink, menuDisabled: true, id: 'openLink'});

	//adding the hidden field DEL_INIT_DATE
	readerFields.push ( { name: "DEL_INIT_DATE"});
	readerFields.push ( { name: "APP_UID"});
	readerFields.push ( { name: "DEL_INDEX"});

	
  var cm = new Ext.grid.ColumnModel({
    defaults: {
      sortable: true // columns are sortable by default           
    },
      columns: columns
    });

  // Create HttpProxy instance, all CRUD requests will be directed to single proxy url.
  var proxyCasesList = new Ext.data.HttpProxy({
    api: {
      read :   'proxyCasesList'
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
  storeCases = new Ext.data.Store({
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
 
  var btnStarted = new Ext.Button ({
    id: 'started',
    text: 'started by me',
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: true,
    pressed: false
  });
 
  var btnCompleted = new Ext.Button ({
    id: 'completed',
    text: 'Completed by me',
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: true,
    pressed: false
  });
 
  // ComboBox creation processValues
  var comboProcess = new Ext.form.ComboBox({
    width         : 180,
    boxMaxWidth   : 180,
    editable      : false,
    displayField  : 'APP_PRO_TITLE',
    valueField    : 'PRO_UID',
    //typeAhead     : true,
    mode          : 'local',
    forceSelection: true,
    triggerAction: 'all',
    //emptyText: 'Select a process...',
    selectOnFocus: true,
    //getListParent: function() {
    //  return this.el.up('.x-menu');
    //},
    store         : new Ext.data.ArrayStore({
      fields: ['PRO_UID','APP_PRO_TITLE'],
      data  : processValues
    }),
    listeners:{
      scope: this,
      'select': function() {
        filterProcess = comboProcess.value;
        storeCases.setBaseParam( 'process', filterProcess);
        storeCases.load({params:{process: filterProcess, start : 0 , limit : pageSize }});
      }},
    iconCls: 'no-icon'  //use iconCls if placing within menu to shift to right side of menu
  });
  
  // ComboBox creation
  var comboStatus = new Ext.form.ComboBox({
    width         : 90,
    boxMaxWidth   : 90,
    editable      : false,
    mode          : 'local',    
    store         : new Ext.data.ArrayStore({
      fields: ['id', 'value'],
      data  : statusValues
    }),
    valueField    : 'id',
    displayField  : 'value',
    triggerAction : 'all',
    
    //typeAhead: true,
    //forceSelection: true,
    //emptyText: 'Select a status...',
    //selectOnFocus: true,
    //getListParent: function() {
    //  return this.el.up('.x-menu');
    //},
    listeners:{
      scope: this,
      'select': function() {
        filterStatus = comboStatus.value;
        storeCases.setBaseParam( 'status', filterStatus);
        storeCases.setBaseParam( 'start', 0);
        storeCases.setBaseParam( 'limit', pageSize);
        storeCases.load();
      }},
    iconCls: 'no-icon'  //use iconCls if placing within menu to shift to right side of menu
  });

  // ComboBox creation processValues
  var comboUser = new Ext.form.ComboBox({
    width         : 160,
    boxMaxWidth   : 180,
    editable      : false,
    displayField  : 'USR_FULLNAME',
    valueField    : 'USR_UID',
    //typeAhead     : true,
    mode          : 'local',
    forceSelection: true,
    triggerAction: 'all',
    //emptyText: 'Select a process...',
    selectOnFocus: true,
    //getListParent: function() {
    //  return this.el.up('.x-menu');
    //},
    store         : new Ext.data.ArrayStore({
      fields: ['USR_UID','USR_FULLNAME'],
      data  : userValues
    }),
    listeners:{
      scope: this,
      'select': function() {
        filterUser = comboUser.value;
        storeCases.setBaseParam( 'user', filterUser);
        storeCases.setBaseParam( 'start', 0);
        storeCases.setBaseParam( 'limit', pageSize);
      }},
    iconCls: 'no-icon'  //use iconCls if placing within menu to shift to right side of menu
  });
  
  
  var textSearch = new Ext.form.TextField ({
    allowBlank: false,
    width: 90,
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
    width: 50,
    emptyText: 'case Id'
  });

  var btnJump = new Ext.Button ({
    text: 'jump',
    handler: function(){
      jump = textJump.getValue();
      location.href = '../cases/cases_Open?APP_NUMBER=' + jump +'&content=inner'; 
    }
  });
  
  var toolbarTodo = [
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
    ];

  var toolbarDraft = [
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
    ];

  var toolbarSent = [
      btnStarted,
      '-',
      btnCompleted,
      '-',
      btnAll,
      '-',
      'status', 
      comboStatus,
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
    ];

  var toolbarSearch = [
      'user',
      comboUser,
      'status', 
      comboStatus,
      '-',
      'process', 
      comboProcess,
      '-', // begin using the right-justified button container 
      textSearch,
      btnSearch,
      '-',
    ];
  if ( action == 'search' )
  var firstToolbarSearch = new Ext.Toolbar({
    renderTo: 'panel',
    width: '100%',
    height: 33,
    items: [
//      btnStarted,
      '-',
//      btnCompleted,
      '-',
//      btnAll
    ]
  });
  
  switch (action) {
    case 'draft'  : itemToolbar = toolbarDraft; break;
    case 'sent'   : itemToolbar = toolbarSent;  break;
    case 'search' : itemToolbar = toolbarSearch;  break;
    default       : itemToolbar = toolbarTodo; break;
  }
    
  var tb = new Ext.Toolbar({
    height: 33,
    items: itemToolbar
  });

  // create the editor grid
  var grid = new Ext.grid.GridPanel({
    region: 'center',
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
    listeners: {
      rowdblclick: function(grid, n,e){
      var appUid   = grid.store.data.items[n].data.APP_UID;
    	var delIndex = grid.store.data.items[n].data.DEL_INDEX;
    	var caseTitle = (grid.store.data.items[n].data.APP_TITLE) ? grid.store.data.items[n].data.APP_TITLE : grid.store.data.items[n].data.APP_UID;
    	Ext.Msg.alert ('open case' , caseTitle );
      window.location = '../cases/cases_Open?APP_UID=' + appUid + '&DEL_INDEX='+delIndex+'&content=inner';
    }
  },
    
    tbar: tb,
    // paging bar on the bottom
    bbar: new Ext.PagingToolbar({
      pageSize: pageSize,
      store: storeCases,
      displayInfo: true,
      displayMsg: 'Displaying items {0} - {1} of {2} ' + '&nbsp; &nbsp; ',
      emptyMsg: "No items to display"
    })

    });

    // manually trigger the data store load
    storeCases.setBaseParam( 'action', action );
    storeCases.setBaseParam( 'start',  0 );
    storeCases.setBaseParam( 'limit',  pageSize );
    storeCases.load();
    //storeProcesses.load();

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
        case 'started' : 
          btnAll.toggle( false, true);
          break;
        case 'completed' : 
          btnAll.toggle( false, true);
          break;
        case 'all' : 
          btnRead.toggle( false, true);
          btnUnread.toggle( false, true);
          btnStarted.toggle( false, true);
          btnCompleted.toggle( false, true);
          break;
      }
      storeCases.setBaseParam( 'filter', item.id );
      storeCases.setBaseParam( 'start',  0 );
      storeCases.setBaseParam( 'limit',  pageSize );
      storeCases.load();
      //storeProcesses.load();
    }
  
  var viewport = new Ext.Viewport({
    layout: 'border',
    autoScroll: true,
    items: [
      grid
    ]
  });
  
  if( parent.PANEL_EAST_OPEN ){
    parent.PANEL_EAST_OPEN = false;
    var debugPanel = parent.Ext.getCmp('debugPanel');      
    debugPanel.hide();
    debugPanel.ownerCt.doLayout(); 
  }
  //parent.updateCasesView();
  parent.updateCasesTree();
  comboStatus.setValue('');
  comboProcess.setValue('');
  
});
