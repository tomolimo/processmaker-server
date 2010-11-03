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
        Ext.Msg.alert(TRANSLATIONS.LABEL_REFRESH, TRANSLATIONS.MESSAGE_REFRESH);
  }
});

//global variables
var storeCases;
var storeReassignCases;


function callbackDeleteCase (btn, text) {
	if ( btn == 'yes' ) {
    Ext.MessageBox.show({ progressText: TRANSLATIONS.ID_PROCESSING, wait:true,waitConfig: {interval:200} });
    Ext.Ajax.request({
      url: 'cases_Delete',
      success: function(response) {
      	parent.updateCasesView();
        Ext.MessageBox.hide();
        parent.updateCasesTree();
      },
      params: {APP_UID:caseIdToDelete}
    });
  }
}

function deleteCaseFunction (appid) {
	caseIdToDelete = appid;
  Ext.Msg.confirm( TRANSLATIONS.ID_CONFIRM, TRANSLATIONS.ID_MSG_CONFIRM_DELETE_CASES , callbackDeleteCase );
}

function callbackUnpauseCase (btn, text) {
	if ( btn == 'yes' ) {
    Ext.MessageBox.show({ progressText: TRANSLATIONS.ID_PROCESSING, wait:true,waitConfig: {interval:200} });
    Ext.Ajax.request({
      url: 'cases_Ajax',
      success: function(response) {
      	parent.updateCasesView();
        Ext.MessageBox.hide();
        parent.updateCasesTree();
      },
      params: {action:'unpauseCase', sApplicationUID: caseIdToUnpause, iIndex: caseIndexToUnpause}
    });
  }
}

function unpauseCaseFunction (appid, delindex) {
	caseIdToUnpause    = appid;
	caseIndexToUnpause = delindex;
  Ext.Msg.confirm( TRANSLATIONS.ID_CONFIRM, TRANSLATIONS.ID_CONFIRM_UNPAUSE_CASE , callbackUnpauseCase );
}

Ext.onReady ( function() {

  var filterProcess = '';
  var filterUser    = '';
  var caseIdToDelete = '';
  var caseIdToUnpause = '';
  var caseIndexToUnpause = '';

  function openLink(value, p, r){
    return String.format("<a class='button_pm' href='../cases/cases_Open?APP_UID={0}&DEL_INDEX={1}&content=inner'>" + TRANSLATIONS.ID_VIEW + "</a>", r.data['APP_UID'], r.data['DEL_INDEX'], r.data['APP_TITLE']);
  }

  function deleteLink(value, p, r){
    return String.format("<a class='button_pm ss_sprite ss_bullet_red' href='#' onclick='deleteCaseFunction(\"{0}\")'>" + TRANSLATIONS.ID_DELETE + "</a>", r.data['APP_UID'] );
  }

  function viewLink(value, p, r){
    return String.format("<a href='../cases/cases_Open?APP_UID={0}&DEL_INDEX={1}&content=inner'>" + TRANSLATIONS.ID_VIEW + "</a>", r.data['APP_UID'], r.data['DEL_INDEX'], r.data['APP_TITLE']);
  }

  function reassignLink(value, p, r){
    return String.format("<a href='../cases/cases_Reassign?APP_UID={0}&DEL_INDEX={1}&content=inner'>" + TRANSLATIONS.ID_REASSIGN + "</a>", r.data['APP_UID'], r.data['DEL_INDEX'], r.data['APP_TITLE']);
  }

  function unpauseLink(value, p, r){
    return String.format("<a href='#' onclick='unpauseCaseFunction(\"{0}\",\"{1}\")'>" + TRANSLATIONS.ID_UNPAUSE + "</a>", r.data['APP_UID'], r.data['DEL_INDEX'] );
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
    	var myDate = new Date( myArrayDate[0], myArrayDate[1]-1, myArrayDate[2], myArrayHour[0], myArrayHour[1], myArrayHour[2] );
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
      return String.format("<span class='row_updated'>{0}</span>", value );
  } 

  //alert(Ext.encode(columns));
  for(var i = 0, len = columns.length; i < len; i++){
    var c = columns[i];
    c.renderer = showField;
    if( c.dataIndex == 'DEL_TASK_DUE_DATE') c.renderer = dueDate;
    if( c.dataIndex == 'APP_UPDATE_DATE')   c.renderer = showDate;
    if( c.id == 'deleteLink')               c.renderer = deleteLink;
    if( c.id == 'viewLink')                 c.renderer = viewLink;
    if( c.id == 'unpauseLink')              c.renderer = unpauseLink;
    if( c.id == 'reassignLink')             c.renderer = reassignLink;
	 //Status images
    //if( c.dataIndex == 'APP_STATUS')			c.renderer = showStatusImage;
  }
  
	/*
  function showStatusImage(value,p,r){
	  if ( value == 'COMPLETED' )
      return String.format("<div class='ss_sprite ss_tick' style='display:block;padding-left:0' title='{0}'> </div>", value );
    else if ( value == 'DRAFT' )
      return String.format("<div class='ss_sprite ss_pencil' style='display:block;padding-left:0' title='{0}'> </div>", value );
    else if ( value == 'TO_DO' )
      return String.format("<div class='ss_sprite ss_page_white_edit' style='display:block;padding-left:0' title='{0}'> </div>", value );
    else if ( value == 'CANCELLED' )
      return String.format("<div class='ss_sprite ss_cancel' style='display:block;padding-left:0' title='{0}'> </div>", value );
		else return String.format("{0}", value );
  }
  */
  //adding the last column to open the cases_Open
	//columns.push ( { header: "aaaaa", width: 50, sortable: false, renderer: openLink, menuDisabled: false, id: 'openLink'});
//	columns.push ( { header: "xxxxxx", width: 50, sortable: false, renderer: openLink, menuDisabled: false, id: 'deleteLink'});

	//adding the hidden field DEL_INIT_DATE
	readerFields.push ( {name: "DEL_INIT_DATE"});
	readerFields.push ( {name: "APP_UID"});
	readerFields.push ( {name: "DEL_INDEX"});


  var cm = new Ext.grid.ColumnModel({
    defaults: {
      sortable: true // columns are sortable by default
    },
      columns: columns
    });

  var reassignCm = new Ext.grid.ColumnModel({
    defaults: {
      sortable: true // columns are sortable by default
    },
      columns: reassignColumns
    });

  var newPopUp = new Ext.Window({
              id       : Ext.id(),
              el       : 'reassign-panel',
              title    : 'Reassign All Cases by Task',
              width    : 600,
              height   : 400,
              frame    : true,
              closable: false
            //  html     : responseObject.responseText
            });

 var btnCloseReassign = new Ext.Button ({
    text: 'Close',
    //    text: TRANSLATIONS.LABEL_SELECT_ALL,
    handler: function(){
      newPopUp.hide();
    }
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

  var proxyReassignCasesList = new Ext.data.HttpProxy({
    api: {
      read :   'proxyReassignCasesList'
    }
  });

  var readerReassignCasesList = new Ext.data.JsonReader({
    totalProperty: 'totalCount',
    successProperty: 'success',
    idProperty: 'index',
    root: 'data',
    messageProperty: 'message'
    },
    reassignReaderFields
  );

  // The new DataWriter component.
  //currently we are not using this in casesList, but it is here just for complete definition
  var writerReassignCasesList = new Ext.data.JsonWriter({
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
    autoSave: true, // <-- false would delay executing create, update, destroy requests until specifically told to do so with some [save] buton.
    sortInfo:{field: 'APP_CACHE_VIEW.APP_NUMBER', direction: "DESC"}
  });

  storeReassignCases = new Ext.data.Store({
    remoteSort: true,
    proxy : proxyReassignCasesList,
    reader: readerReassignCasesList,
    writer: writerReassignCasesList,  // <-- plug a DataWriter into the store just as you would a Reader
    autoSave: true // <-- false would delay executing create, update, destroy requests until specifically told to do so with some [save] buton.
  });

  //Layout Resizing
  storeCases.on('load',function(){var viewport = Ext.getCmp("viewportcases");viewport.doLayout();})

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

  //var reassignUsersCombo = new Ext.grid.;
  /*
  var storeToReassignUsers = new Ext.data.JsonStore({
    root: 'data',
    totalProperty: 'totalCount',
    idProperty: 'index',
    remoteSort: true,
    fields: [
      'USR_UID', 'USR_USERNAME'
    ],
    data: usersObject
  });
  storeProcesses.setDefaultSort('APP_PRO_TITLE', 'asc');
  */


  // creating the button for filters
  var btnRead = new Ext.Button ({
    id: 'read',
    text: TRANSLATIONS.LABEL_OPT_READ,
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: false,
    pressed: false
  });

  var btnUnread = new Ext.Button ({
    id: 'unread',
    text: TRANSLATIONS.LABEL_OPT_UNREAD,
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: false,
    pressed: false
  });

  var btnAll = new Ext.Button ({
    id: 'all',
    text: TRANSLATIONS.LABEL_OPT_ALL,
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: false,
    pressed: true
  });

  var btnStarted = new Ext.Button ({
    id: 'started',
//    text: 'started by me',
    text: TRANSLATIONS.LABEL_OPT_STARTED,
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: true,
    pressed: false
  });

  var btnCompleted = new Ext.Button ({
    id: 'completed',
//    text: 'Completed by me',
    text: TRANSLATIONS.LABEL_OPT_COMPLETED,
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: true,
    pressed: false
  });

  // ComboBox creation processValues
  var comboProcess = new Ext.form.ComboBox({
    width         : 180,
    boxMaxWidth   : 180,
    editable      : true,
    displayField  : 'APP_PRO_TITLE',
    valueField    : 'PRO_UID',
    forceSelection: false,
    emptyText: TRANSLATIONS.LABEL_EMPTY_PROCESSES,
    selectOnFocus: true,


    typeAhead: true,
    mode: 'local',
    autocomplete: true,
    triggerAction: 'all',

    store         : new Ext.data.ArrayStore({
      fields : ['PRO_UID','APP_PRO_TITLE'],
      data   : processValues
    }),
    listeners:{
      scope: this,
      'select': function() {
        filterProcess = comboProcess.value;
        if ( action == 'search' ){
          storeCases.setBaseParam('dateFrom', dateFrom.getValue());
          storeCases.setBaseParam('dateTo', dateTo.getValue());
        }
        storeCases.setBaseParam('process', filterProcess);
        storeCases.load({params:{process: filterProcess, start : 0 , limit : pageSize}});
      }},
    iconCls: 'no-icon'  //use iconCls if placing within menu to shift to right side of menu
  });

  var comboAllUsers = new Ext.form.ComboBox({
    width         : 180,
    boxMaxWidth   : 180,
    editable      : false,
    displayField  : 'USR_FULLNAME',
    valueField    : 'USR_UID',
    //typeAhead     : true,
    mode          : 'local',
    forceSelection: true,
    triggerAction: 'all',
    //emptyText: 'Select a process...',
    emptyText: TRANSLATIONS.LABEL_EMPTY_USERS,
    selectOnFocus: true,
    //getListParent: function() {
    //  return this.el.up('.x-menu');
    //},
    store         : new Ext.data.ArrayStore({
      fields: ['USR_UID','USR_FULLNAME'],
      data  : allUsersValues
    }),
    listeners:{
      scope: this,
      'select': function() {
        filterProcess = comboAllUsers.value;

        if (filterProcess==''){
          btnSelectAll.hide();
          btnUnSelectAll.hide();
          btnReassign.hide();
        } else  {
          btnSelectAll.show();
          btnUnSelectAll.show();
          btnReassign.show();
        }
        storeCases.setBaseParam( 'user', filterProcess);
        storeCases.load({params:{user: filterProcess, start : 0 , limit : pageSize}});
      }},
    iconCls: 'no-icon'  //use iconCls if placing within menu to shift to right side of menu
  });

  var btnSelectAll = new Ext.Button ({
    text: 'Check All',
//    text: TRANSLATIONS.LABEL_SELECT_ALL,
    handler: function(){
      grid.getSelectionModel().selectAll();
    }
  });

  var btnUnSelectAll = new Ext.Button ({
    text: 'Un-Check All',
//    text: TRANSLATIONS.LABEL_UNSELECT_ALL,
    handler: function(){
      grid.getSelectionModel().clearSelections();
    }
  });

  var btnReassign = new Ext.Button ({
    text: 'Reassign',
//    text: TRANSLATIONS.LABEL_UNSELECT_ALL,
    handler: function(){
      //grid.getSelectionModel().getSelections();
        reassignGrid.getColumnModel().setHidden(0, true);
        reassignGrid.getColumnModel().setHidden(1, true);
        reassignGrid.getColumnModel().setHidden(2, true);
        reassign();
//      reassignPopup.show();
//      conn.request({
//        url: 'cases_Ajax',
//        url: 'proxyReassignCases',
//        method: 'POST',
//        params: {"APP_UIDS": 'metaID', 'FROM_USR_ID': 'field', 'action': 'reassignByUserList'},
//        success: function(responseObject) {
//          reassignPopup.html = responseObject.responseText;
//
//            var newPopUp = new Ext.Window({
//              title    : 'Static Panel',
//              width    : 600,
//              height   : 400,
//              frame    : true,
//              html     : responseObject.responseText
//            });
//            newPopUp.show();
//        },
//        failure: function() {
//            Ext.Msg.alert('Status', 'Unable to show history at this time. Please try again later.');
//        }
//      });
    }
  });

//  var conn = new Ext.data.Connection();
  var nav = new Ext.FormPanel({
					labelWidth:100,
					frame:true,
					width:300,
					collapsible:true,
					defaultType:'textfield',
					items:[{
						fieldLabel:'Reassign To',
						name:'txt_stock_in',
						allowBlank:true
					}]
				});

  var reassignPopup = new Ext.Window({
    el:'reassign-panel',
    modal:true,
    layout:'fit',
    width:300,
    height:300,
    closable:false,
    resizable:false,
    plain:true,
    items:[nav],
    buttons:[{
      text:'submit',
      handler:function(){
        Ext.Msg.alert('OK','save ?');
        Ext.Msg.prompt('Name','please enter your name: ',function(btn,text){
          if(btn=='ok') {
            alert('ok');
          }
        });
      }
    }, {
      text:'close',
      handler:function() {
        reassignPopup.hide();
      }
    }]
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
    editable      : true,
    displayField  : 'USR_FULLNAME',
    valueField    : 'USR_UID',
    mode          : 'local',
    forceSelection: false,
    emptyText: 'Select',
    selectOnFocus: true,

    typeAhead: true,
    mode: 'local',
    autocomplete: true,
    triggerAction: 'all',

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
        storeCases.load();
      }},
    iconCls: 'no-icon'  //use iconCls if placing within menu to shift to right side of menu
  });


  var textSearch = new Ext.form.TextField ({
    allowBlank: true,
    ctCls:'pm_search_text_field',
    width: 150,
    emptyText: TRANSLATIONS.LABEL_EMPTY_SEARCH,
    listeners: {
      specialkey: function(f,e){
        if (e.getKey() == e.ENTER) {
          doSearch();
        }
      }
    }
  });

  var btnSearch = new Ext.Button ({
    text: TRANSLATIONS.LABEL_SEARCH,
    handler: doSearch
  });

  var doSearch = function(){
    searchText = textSearch.getValue();
    storeCases.setBaseParam( 'search', searchText);
    storeCases.load({params:{ start : 0 , limit : pageSize }});
  }

  var resetSearchButton = {
    text:'X',
	  ctCls:'pm_search_x_button',
    handler: function(){
      textSearch.setValue('');
      doSearch();
    }
  }

  var textJump = new Ext.form.TextField ({
    allowBlank: false,
    width: 50,
    emptyText: TRANSLATIONS.ID_CASESLIST_APP_UID,
    listeners: {
      specialkey: function(f,e){
        if (e.getKey() == e.ENTER) {
          jump = textJump.getValue();
          location.href = '../cases/cases_Open?APP_NUMBER=' + jump +'&content=inner';
        }
      }
    }
  });

  var btnJump = new Ext.Button ({
    text: TRANSLATIONS.LABEL_OPT_JUMP,
    handler: function(){
      jump = textJump.getValue();
      location.href = '../cases/cases_Open?APP_NUMBER=' + jump +'&content=inner';
    }
  });


  var dateFrom = new Ext.form.DateField({
    id:'dateFrom',
    format: 'Y-m-d',
    width: 120,
    value: ''
  });

  var dateTo = new Ext.form.DateField({
    id:'dateTo',
    format: 'Y-m-d',
    width: 120,
    value: ''
  });

  var toolbarTodo = [
    TRANSLATIONS.ID_PROCESS,
    comboProcess,
    '-',
    btnRead,
    '-',
    btnUnread,
    '-',
    btnAll,
    '->', // begin using the right-justified button container
    textSearch,
    resetSearchButton,
    btnSearch,
    '-',
    textJump,
    btnJump,
    ' ',
    ' '
  ];

  var toolbarDraft = [
    TRANSLATIONS.ID_PROCESS,
    comboProcess,
    '->', // begin using the right-justified button container
    textSearch,
    resetSearchButton,
    btnSearch,
    '-',
    textJump,
    btnJump,
    ' ',
    ' '
  ];

  var toolbarToRevise = [
    TRANSLATIONS.ID_PROCESS,
    comboProcess,
    '->', // begin using the right-justified button container
    textSearch,
    resetSearchButton,
    btnSearch,
    '-',
    textJump,
    btnJump,
    ' ',
    ' '
  ];

  var toolbarToReassign = [
    'user',
    comboAllUsers,
    '-',
    TRANSLATIONS.ID_PROCESS,
    comboProcess,
    '-',
    btnSelectAll,
    '-',
    btnUnSelectAll,
    '-',
    btnReassign,
    ' ',
    ' '
  ];

  var toolbarSent = [
    TRANSLATIONS.ID_PROCESS,
    comboProcess,
    '-',
    TRANSLATIONS.ID_STATUS,
    comboStatus,
    '-',
    btnStarted,
    '-',
    btnCompleted,
    '-',
    btnAll,
    '->', // begin using the right-justified button container
    textSearch,
    resetSearchButton,
    btnSearch,
    '-',
    textJump,
    btnJump,
    ' ',
    ' '
  ];

  var toolbarSearch = [
      ' ',
      TRANSLATIONS.ID_DELEGATE_DATE_FROM,
      dateFrom,
      ' ',
      TRANSLATIONS.ID_TO,
      dateTo,
      new Ext.Button ({
        text: TRANSLATIONS.ID_FILTER_BY_DELEGATED_DATE,
        handler: function(){
          storeCases.setBaseParam('dateFrom', dateFrom.getValue());
          storeCases.setBaseParam('dateTo', dateTo.getValue());
          storeCases.load({params:{ start : 0 , limit : pageSize }});
        }
      }),
      '-'
    ];
  
  var firstToolbarSearch = new Ext.Toolbar({
    region: 'north',
    width: '100%',
    autoHeight: true,
    items: [
      ' ',
      TRANSLATIONS.ID_PROCESS,
      comboProcess,
      '-',
      TRANSLATIONS.ID_STATUS,
      comboStatus,
      '-',
      TRANSLATIONS.ID_USER,
      comboUser,
      '->',
      textSearch,
      resetSearchButton,
      btnSearch
    ]
  });

  switch (action) {
    case 'draft'      : itemToolbar = toolbarDraft; break;
    case 'sent'       : itemToolbar = toolbarSent;  break;
    case 'to_revise'  : itemToolbar = toolbarToRevise;  break;
    case 'to_reassign': itemToolbar = toolbarToReassign;  break;
    case 'search'     : itemToolbar = toolbarSearch;  break;
    default           : itemToolbar = toolbarTodo; break;
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
    autoHeight: true,
    layout: 'fit',
    viewConfig: {
      forceFit:true
    },
    listeners: {
      rowdblclick: function(grid, n,e){
        var appUid   = grid.store.data.items[n].data.APP_UID;
        var delIndex = grid.store.data.items[n].data.DEL_INDEX;
        var caseTitle = (grid.store.data.items[n].data.APP_TITLE) ? grid.store.data.items[n].data.APP_TITLE : grid.store.data.items[n].data.APP_UID;
        Ext.Msg.show({
          msg: TRANSLATIONS.LABEL_OPEN_CASE + ' ' + caseTitle,
          width:300,
          wait:true,
          waitConfig: {interval:200}
        });
        window.location = '../cases/cases_Open?APP_UID=' + appUid + '&DEL_INDEX='+delIndex+'&content=inner';
      },
      render: function(){
        //this.loadMask = new Ext.LoadMask(this.body, {msg:TRANSLATIONS.LABEL_GRID_LOADING});
        //this.ownerCt.doLayout();
      }
    },

    tbar: tb,
    // paging bar on the bottom
    bbar: new Ext.PagingToolbar({
      pageSize: pageSize,
      store: storeCases,
      displayInfo: true,
      //displayMsg: 'Displaying items {0} - {1} of {2} ' + ' &nbsp; ' ,
      displayMsg: TRANSLATIONS.LABEL_DISPLAY_ITEMS + ' &nbsp; ',
      emptyMsg: TRANSLATIONS.LABEL_DISPLAY_EMPTY
    })
  });
  
  // create reusable renderer


  // create the editor grid
  var reassignGrid = new Ext.grid.EditorGridPanel({
    id : Ext.id(),
    region: 'center',
    store: storeReassignCases,
    cm: reassignCm,
    /* renderTo: 'cases-grid', */
    autoHeight: true,
    //frame: false,
    //autoHeight:true,
    //minHeight:400,
//    layout: 'fit',
    viewConfig: {
      forceFit:true
    },
/*
    listeners: {
      rowdblclick: function(grid, n,e){
        var appUid   = grid.store.data.items[n].data.APP_UID;
        var delIndex = grid.store.data.items[n].data.DEL_INDEX;
        var caseTitle = (grid.store.data.items[n].data.APP_TITLE) ? grid.store.data.items[n].data.APP_TITLE : grid.store.data.items[n].data.APP_UID;
        //Ext.Msg.alert (TRANSLATIONS.LABEL_OPEN_CASE , caseTitle );
        Ext.Msg.show({
                msg: TRANSLATIONS.LABEL_OPEN_CASE + ' ' + caseTitle,
                width:300,
                wait:true,
                waitConfig: {interval:200}
              });
        window.location = '../cases/cases_Open?APP_UID=' + appUid + '&DEL_INDEX='+delIndex+'&content=inner';
      },
      render: function(){
        //this.loadMask = new Ext.LoadMask(this.body, {msg:TRANSLATIONS.LABEL_GRID_LOADING});
        //this.ownerCt.doLayout();
      }
  },
  */
    //tbar: tb,
    // paging bar on the bottom
    bbar: new Ext.PagingToolbar({
      pageSize: pageSize,
      store: storeReassignCases,
      displayInfo: true,
//      displayMsg: 'Displaying items {0} - {1} of {2} ' + ' &nbsp; ',
      displayMsg: TRANSLATIONS.LABEL_DISPLAY_ITEMS + ' &nbsp; ',
//      emptyMsg: "No items to display"
      emptyMsg: TRANSLATIONS.LABEL_DISPLAY_EMPTY
    })

    });


    // manually trigger the data store load
    storeCases.setBaseParam( 'action', action );
    storeCases.setBaseParam( 'start',  0 );
    storeCases.setBaseParam( 'limit',  pageSize );
    storeCases.load();
    newPopUp.add(reassignGrid);
    newPopUp.addButton(btnCloseReassign);

    //storeProcesses.load();

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


  $configViewport = {
    layout: 'border',
    autoScroll: true,
	  id:'viewportcases',
    items: [grid]
  }

  if ( action == 'search' )
    $configViewport.items.push(firstToolbarSearch);

  var viewport = new Ext.Viewport($configViewport);






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
  // hidding the buttons for the reassign
//  if (action=='to_reassign'){
//    btnSelectAll.hide();
//    btnUnSelectAll.hide();
//    btnReassign.hide();
//  }


function reassign(){
  //var rowSelected = processesGrid.getSelectionModel().getSelected();
  //var rows = grid.getSelectionModel().getSelections();
//  alert(reassignGrid.getId());
  //if( rows.length > 0 ) {
  //  var ids = '';
  //  for(i=0; i<rows.length; i++) {
  //    if(i != 0 ) ids += ',';
  //    ids += rows[i].get('APP_UID');
  //  }
    //storeReassignCases.setBaseParam( 'APP_UIDS', ids);
    storeReassignCases.setBaseParam( 'user', comboAllUsers.value);
    storeReassignCases.setBaseParam( 'process', comboProcess.value);
    storeReassignCases.load({params:{ start : 0 , limit : pageSize}});
    newPopUp.show();
//    storeReassignCases.baseParams = {APP_UIDS : ids, user : comboAllUsers.value};
//    storeReassignCases.reload();

    //window.location = 'processes_ChangeStatus?PRO_UID='+rowSelected.data.PRO_UID;
  /*} else {
     Ext.Msg.show({
      title:'',
      msg: TRANSLATIONS.ID_NO_SELECTION_WARNING,
      buttons: Ext.Msg.INFO,
      fn: function(){},
      animEl: 'elId',
      icon: Ext.MessageBox.INFO,
      buttons: Ext.MessageBox.OK
    });
  }*/
}

function getSelectionData () {
  //var rows = reassignGrid.getSelectionModel().getSelections();
  //var gridData[0]  = rows[0].get( 'APP_UID' );
  //gridData[1]      = rows[0].get( 'TAS_UID' );
  //alert (gridData[0]+gridData[1]);
  alert('none');
}

});
