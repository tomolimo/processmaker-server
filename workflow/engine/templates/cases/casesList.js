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
        Ext.Msg.alert(TRANSLATIONS.ID_REFRESH_LABEL, TRANSLATIONS.ID_REFRESH_MESSAGE);
  }
});

/*** global variables **/
var storeCases;
var storeReassignCases;
var grid;
var textJump;

/** */

function openCase(){

    var rowModel = grid.getSelectionModel().getSelected();
    if(rowModel){
      var appUid   = rowModel.data.APP_UID;
      var delIndex = rowModel.data.DEL_INDEX;
      var caseTitle = (rowModel.data.APP_TITLE) ? rowModel.data.APP_TITLE : rowModel.data.APP_UID;
      
      Ext.Msg.show({
        msg: TRANSLATIONS.ID_OPEN_CASE + ' ' + caseTitle,
        width:300,
        wait:true,
        waitConfig: {interval:200}
      });
      params = '';
      switch(action){
        case 'to_revise':
          params += 'APP_UID=' + appUid;
          params += '&DEL_INDEX=' + delIndex;
          params += '&to_revise=true';
          requestFile = 'open';
          break;
        case 'sent': // = participated
          params += 'APP_UID=' + appUid;
          params += '&DEL_INDEX=' + delIndex;
          //requestFile = '../cases/cases_Open';
          requestFile = 'open';
        break;
        case 'todo':
        case 'draft':
        case 'paused':
        case 'unassigned':
        default:
          params += 'APP_UID=' + appUid;
          params += '&DEL_INDEX=' + delIndex;
          //requestFile = '../cases/cases_Open';
          requestFile = 'open';
          break;
      }

      parent._CASE_TITLE  = caseTitle;
      params += '&action=' + action;
      redirect(requestFile + '?' + params);

    } else
      msgBox('Information', TRANSLATIONS.ID_SELECT_ONE_AT_LEAST);
}

function jumpToCase(appNumber){
  params  = 'APP_NUMBER=' + appNumber;
  params += '&action=jump';
  requestFile = '../cases/cases_Open';
  redirect(requestFile + '?' + params);
}

function deleteCase() {
  var rows = grid.getSelectionModel().getSelections();
  if( rows.length > 0 ) {
    ids = Array();
    for(i=0; i<rows.length; i++)
      ids[i] = rows[i].get('APP_UID');

    APP_UIDS = ids.join(',');

    Ext.Msg.confirm(
      TRANSLATIONS.ID_CONFIRM,
      rows.length == 1? TRANSLATIONS.ID_MSG_CONFIRM_DELETE_CASE: TRANSLATIONS.ID_MSG_CONFIRM_DELETE_CASES,
      function(btn, text){
        if ( btn == 'yes' ) {
          Ext.MessageBox.show({ msg: TRANSLATIONS.ID_DELETING_ELEMENTS, wait:true,waitConfig: {interval:200} });
          Ext.Ajax.request({
            url: 'cases_Delete',
            success: function(response) {
              parent.updateCasesView();
              Ext.MessageBox.hide();
              parent.updateCasesTree();
            },
            params: {APP_UIDS:APP_UIDS}
          });
        }
      }
    );
  } else {
    Ext.Msg.show({
      title:'',
      msg: TRANSLATIONS.ID_NO_SELECTION_WARNING,
      buttons: Ext.Msg.INFO,
      fn: function(){},
      animEl: 'elId',
      icon: Ext.MessageBox.INFO,
      buttons: Ext.MessageBox.OK
    });
  }
}

function pauseCase(date){
  rowModel = grid.getSelectionModel().getSelected();
  unpauseDate = date.format('Y-m-d');

  Ext.Msg.confirm(
    TRANSLATIONS.ID_CONFIRM,
    TRANSLATIONS.ID_PAUSE_CASE_TO_DATE +' '+date.format('M j, Y')+'?',
    function(btn, text){
      if ( btn == 'yes' ) {
        Ext.MessageBox.show({ msg: TRANSLATIONS.ID_PROCESSING, wait:true,waitConfig: {interval:200} });
        Ext.Ajax.request({
          url: 'cases_Ajax',
          success: function(response) {
            parent.updateCasesView();
            parent.updateCasesTree();
            Ext.MessageBox.hide();
          },
          params: {action:'pauseCase', unpausedate:unpauseDate, APP_UID:rowModel.data.APP_UID, DEL_INDEX: rowModel.data.DEL_INDEX}
        });

      }
    }
  );
}


function cancelCase(){
  var rows = grid.getSelectionModel().getSelections();
  if( rows.length > 0 ) {
    app_uid = Array();
    del_index = Array();

    for(i=0; i<rows.length; i++){
      app_uid[i]   = rows[i].get('APP_UID');
      del_index[i] = rows[i].get('DEL_INDEX');
    }
    APP_UIDS    = app_uid.join(',');
    DEL_INDEXES = del_index.join(',');

    Ext.Msg.confirm(
      TRANSLATIONS.ID_CONFIRM,
      rows.length == 1? TRANSLATIONS.ID_MSG_CONFIRM_CANCEL_CASE: TRANSLATIONS.ID_MSG_CONFIRM_CANCEL_CASES,
      function(btn, text){
        if ( btn == 'yes' ) {
          Ext.MessageBox.show({ msg: TRANSLATIONS.ID_PROCESSING, wait:true,waitConfig: {interval:200} });
          Ext.Ajax.request({
            url: 'cases_Ajax',
            success: function(response) {
              parent.updateCasesView();
              Ext.MessageBox.hide();
              parent.updateCasesTree();
            },
            params: {action:'cancelCase', APP_UID:APP_UIDS, DEL_INDEX:DEL_INDEXES}
          });
        }
      }
    );
  } else {
    Ext.Msg.show({
      title:'',
      msg: TRANSLATIONS.ID_NO_SELECTION_WARNING,
      buttons: Ext.Msg.INFO,
      fn: function(){},
      animEl: 'elId',
      icon: Ext.MessageBox.INFO,
      buttons: Ext.MessageBox.OK
    });
  }
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

function unpauseCase() {
  rowModel = grid.getSelectionModel().getSelected();
	caseIdToUnpause    = rowModel.data.APP_UID;
	caseIndexToUnpause = rowModel.data.DEL_INDEX;

  Ext.Msg.confirm( TRANSLATIONS.ID_CONFIRM, TRANSLATIONS.ID_CONFIRM_UNPAUSE_CASE , function (btn, text) {
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
  });
}

function redirect(href){
  window.location.href = href;
}

Ext.onReady ( function() {
  var ids = '';
  var filterProcess = '';
  var filterUser    = '';
  var caseIdToDelete = '';
  var caseIdToUnpause = '';
  var caseIndexToUnpause = '';
  parent._action = action;

  function openLink(value, p, r){
    return String.format("<a class='button_pm' href='../cases/cases_Open?APP_UID={0}&DEL_INDEX={1}&content=inner'>" + TRANSLATIONS.ID_VIEW + "</a>", r.data['APP_UID'], r.data['DEL_INDEX'], r.data['APP_TITLE']);
  }

  function deleteLink(value, p, r){
    return String.format("<a class='button_pm ss_sprite ss_bullet_red' href='#' onclick='deleteCase(\"{0}\")'>" + TRANSLATIONS.ID_DELETE + "</a>", r.data['APP_UID'] );
  }

  function viewLink(value, p, r){
    return String.format("<a href='../cases/cases_Open?APP_UID={0}&DEL_INDEX={1}&content=inner'>" + TRANSLATIONS.ID_VIEW + "</a>", r.data['APP_UID'], r.data['DEL_INDEX'], r.data['APP_TITLE']);
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

  for(var i = 0, len = columns.length; i < len; i++){
    var c = columns[i];
    c.renderer = showField;
    if( c.dataIndex == 'DEL_TASK_DUE_DATE') c.renderer = dueDate;
    if( c.dataIndex == 'APP_UPDATE_DATE')   c.renderer = showDate;
    if( c.id == 'deleteLink')               c.renderer = deleteLink;
    if( c.id == 'viewLink')                 c.renderer = viewLink;
    if( c.id == 'unpauseLink')              c.renderer = unpauseLink;
  }

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
              width    : 750,
              height   : 350,
              frame    : true,
              closable: false
            });

  var btnCloseReassign = new Ext.Button ({
    text: TRANSLATIONS.ID_CLOSE,
    //    text: TRANSLATIONS.LABEL_SELECT_ALL,
    handler: function(){
      newPopUp.hide();
    }
  });

  var btnExecReassign = new Ext.Button ({
    text: TRANSLATIONS.ID_REASSIGN_ALL,
    // text: 'Reassign All',
    //    text: TRANSLATIONS.LABEL_SELECT_ALL,
    handler: function(){
      
      var rs = storeReassignCases.getModifiedRecords();
      var sv = [];
      for(var i = 0; i <= rs.length-1; i++){
        //sv[i]= rs[i].data['name'];
        sv[i]= rs[i].data;
      }
      var gridData = storeReassignCases.getModifiedRecords();
      
      Ext.Ajax.request({
        url: 'proxySaveReassignCasesList',
        success: function(response) {
          newPopUp.hide();
          storeCases.reload();
        },
        params: { APP_UIDS:ids, data:Ext.util.JSON.encode(sv), selected:false }
      });
      
      /*
      storeReassignCases.setBaseParam('selected', false);
      var result = storeReassignCases.save();
      newPopUp.hide();
      storeCases.reload();
      */
      //storeReassignCases.reload();
    }
  });
  
  var btnExecReassignSelected = new Ext.Button ({
    text: TRANSLATIONS.ID_REASSIGN,
    // text: 'Reassign',
    //    text: TRANSLATIONS.LABEL_SELECT_ALL,
    handler: function(){
      newPopUp.hide();
      var rs = storeReassignCases.getModifiedRecords();
      var sv = [];
      for(var i = 0; i <= rs.length-1; i++){
        //sv[i]= rs[i].data['name'];
        sv[i]= rs[i].data;
      }
      var gridData = storeReassignCases.getModifiedRecords();
      Ext.MessageBox.show({ msg: TRANSLATIONS.ID_PROCESSING, wait:true,waitConfig: {interval:200} });
      Ext.Ajax.request({
        url: 'proxySaveReassignCasesList',
        success: function(response) {
          Ext.MessageBox.hide();
          storeCases.reload();
          var ajaxServerResponse = Ext.util.JSON.decode(response.responseText);
          var count;
          var message = '';
          
          for (count in ajaxServerResponse) {
            if ( ajaxServerResponse[count]['TAS_TITLE'] != undefined ){
              message = message + "Task: " + ajaxServerResponse[count]['TAS_TITLE'] + " - Reassigned Cases: " + ajaxServerResponse[count]['REASSIGNED_CASES'] + "<br>" ;
            };
          }
          
          if (ajaxServerResponse['TOTAL']!=undefined&&ajaxServerResponse['TOTAL']!=-1){
            message = message + "Total Cases Reassigned: " + ajaxServerResponse['TOTAL'];
          } else {
            message = "";
          };
          
          if (message!=""){
            Ext.MessageBox.alert( 'Status Reassignment', message, '' );
          }
        },
        params: { APP_UIDS:ids, data:Ext.util.JSON.encode(sv), selected:true }
      });
      
      /*storeReassignCases.setBaseParam('selected', true);
      var result = storeReassignCases.save();
      //storeCases.load({params:{process: filterProcess, start : 0 , limit : pageSize}});
      newPopUp.hide();
      storeCases.reload();
      //storeReassignCases.reload();
      */
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
      read    : 'proxyReassignCasesList'
      //create  : 'proxySaveReassignCasesList',
      //update  : 'proxySaveReassignCasesList',
      //destroy : 'proxyReassignCasesList'
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
    remoteSort: false,
    proxy : proxyReassignCasesList,
    reader: readerReassignCasesList
    //writer: writerReassignCasesList,  // <-- plug a DataWriter into the store just as you would a Reader
    //autoSave: false // <-- false would delay executing create, update, destroy requests until specifically told to do so with some [save] buton.
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

  // creating the button for filters
  var btnRead = new Ext.Button ({
    id: 'read',
    text: TRANSLATIONS.ID_OPT_READ,
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: false,
    pressed: false
  });

  var btnUnread = new Ext.Button ({
    id: 'unread',
    text: TRANSLATIONS.ID_OPT_UNREAD,
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: false,
    pressed: false
  });

  var btnAll = new Ext.Button ({
    id: 'all',
    text: TRANSLATIONS.ID_OPT_ALL,
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: false,
    pressed: true
  });

  var btnStarted = new Ext.Button ({
    id: 'started',
//    text: 'started by me',
    text: TRANSLATIONS.ID_OPT_STARTED,
    enableToggle: true,
    toggleHandler: onItemToggle,
    allowDepress: true,
    pressed: false
  });

  var btnCompleted = new Ext.Button ({
    id: 'completed',
//    text: 'Completed by me',
    text: TRANSLATIONS.ID_OPT_COMPLETED,
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
    emptyText: TRANSLATIONS.ID_EMPTY_PROCESSES,
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
    
    emptyText: TRANSLATIONS.ID_EMPTY_USERS,
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
        }
        else  {
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
    text: TRANSLATIONS.CHECK_ALL,
    // text: 'Check All',
    // text: TRANSLATIONS.LABEL_SELECT_ALL,
    handler: function(){
      grid.getSelectionModel().selectAll();
    }
  });

  var btnUnSelectAll = new Ext.Button ({
    text: TRANSLATIONS.UNCHECK_ALL,
    // text: 'Un-Check All',
    // text: TRANSLATIONS.LABEL_UNSELECT_ALL,
    handler: function(){
      grid.getSelectionModel().clearSelections();
    }
  });

  var btnReassign = new Ext.Button ({
    text: TRANSLATIONS.ID_REASSIGN,
    // text: 'Reassign',
    // text: TRANSLATIONS.LABEL_UNSELECT_ALL,
    handler: function(){
        reassign();
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
      text: TRANSLATIONS.ID_SUBMIT,
      handler:function(){
        Ext.Msg.alert('OK','save ?');
        Ext.Msg.prompt('Name','please enter your name: ',function(btn,text){
          if(btn=='ok') {
            alert('ok');
          }
        });
      }
    }, {
      text: TRANSLATIONS.ID_CLOSE,
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
    emptyText: TRANSLATIONS.ID_SELECT,
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
    emptyText: TRANSLATIONS.ID_EMPTY_SEARCH,
    listeners: {
      specialkey: function(f,e){
        if (e.getKey() == e.ENTER) {
          doSearch();
        }
      }
    }
  });

  var btnSearch = new Ext.Button ({
    text: TRANSLATIONS.ID_SEARCH,
    handler: doSearch
  });

  function doSearch(){
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

  textJump = {
    xtype: 'numberfield',
    id   : 'textJump',
    allowBlank: true,
    width: 50,
    emptyText: TRANSLATIONS.ID_CASESLIST_APP_UID,
    listeners: {
      specialkey: function(f,e){
        if (e.getKey() == e.ENTER) {
          // defining an id and using the Ext.getCmp method improves the accesibility of Ext components
          caseNumber = parseFloat(Ext.util.Format.trim(Ext.getCmp('textJump').getValue()));
          if( caseNumber )
            jumpToCase(caseNumber);
          else
            msgBox('Input Error', 'You have set a invalid Application Number', 'error');
        }
      }
    }
  };

  var btnJump = new Ext.Button ({
    text: TRANSLATIONS.ID_OPT_JUMP,
    handler: function(){
      var caseNumber = parseFloat(Ext.util.Format.trim(Ext.getCmp('textJump').getValue()));
      if (caseNumber){
        jumpToCase(caseNumber);
      } else {
            msgBox('Input Error', 'You have set a invalid Application Number', 'error');
    }
    }
  });

  /*** menu and toolbars **/

  function onMessageContextMenu(grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    messageContextMenu.showAt([coords[0], coords[1]]);
    enableDisableMenuOption();
  }

  function enableDisableMenuOption(){
    var rows = grid.getSelectionModel().getSelections();
    switch(action){
      case 'todo':
        if( rows.length == 0 ) {
          optionMenuOpen.setDisabled(true);
          optionMenuPause.setDisabled(true);
          optionMenuReassign.setDisabled(true);
          optionMenuCancel.setDisabled(true);
        } else if( rows.length == 1 ) {
          optionMenuOpen.setDisabled(false);
          optionMenuPause.setDisabled(false);
          optionMenuReassign.setDisabled(false);
          optionMenuCancel.setDisabled(false);
        } else {
          optionMenuOpen.setDisabled(true);
          optionMenuPause.setDisabled(true);
          optionMenuReassign.setDisabled(true);
          optionMenuCancel.setDisabled(false);
        }
        break;
      case 'draft':
        if( rows.length == 0 ) {
          optionMenuOpen.setDisabled(true);
          optionMenuPause.setDisabled(true);
          optionMenuReassign.setDisabled(true);
          optionMenuDelete.setDisabled(true);
        } else if( rows.length == 1 ) {
          optionMenuOpen.setDisabled(false);
          optionMenuPause.setDisabled(false);
          optionMenuReassign.setDisabled(false);
          optionMenuDelete.setDisabled(false);
        } else {
          optionMenuOpen.setDisabled(true);
          optionMenuPause.setDisabled(true);
          optionMenuReassign.setDisabled(true);
          optionMenuDelete.setDisabled(false);
        }
        break;
    }
  }

  var menuItems;
  //alert(action);
  optionMenuOpen = new Ext.Action({
    text: TRANSLATIONS.ID_OPEN_CASE,
    iconCls: 'ICON_CASES_OPEN',
    handler: openCase
  });

  optionMenuUnpause = new Ext.Action({
    text: TRANSLATIONS.ID_UNPAUSE_CASE,
    iconCls: 'ICON_CASES_UNPAUSE',
    handler: unpauseCase
  });

  optionMenuPause = new Ext.Action({
    text: TRANSLATIONS.ID_PAUSE_CASE,
    iconCls: 'ICON_CASES_PAUSED',
    menu: new Ext.menu.DateMenu({
      //vtype: 'daterange',
      handler: function(dp, date){
        pauseCase(date);
      }
    })

  });

  //optionMenuPause.setMinValue('2010-11-04');
  optionMenuReassign = new Ext.Action({
    text: TRANSLATIONS.ID_REASSIGN,
    iconCls: 'ICON_CASES_TO_REASSIGN',
    handler: function(){}
  });
  optionMenuDelete = new Ext.Action({
    text: TRANSLATIONS.ID_DELETE,
    iconCls: 'ICON_CASES_DELETE',
    handler: deleteCase
  });
  optionMenuCancel = new Ext.Action({
    text: TRANSLATIONS.ID_CANCEL,
    iconCls: 'ICON_CASES_CANCEL',
    handler: cancelCase
  });


  switch(action){
    case 'todo':
      menuItems = [optionMenuOpen, optionMenuPause];

      if( ___p34315105.search('R') > 0 )
        menuItems.push(optionMenuReassign);
      if( ___p34315105.search('C') > 0 )
        menuItems.push(optionMenuCancel);

      break;

      case 'draft':
      menuItems = [optionMenuOpen, optionMenuPause];
      if( ___p34315105.search('R') > 0 )
        menuItems.push(optionMenuReassign);
      menuItems.push(optionMenuDelete);

      break;

    case 'paused':
      menuItems = [optionMenuUnpause];
      break;

    default:
      menuItems = []
  }

  var messageContextMenu = new Ext.menu.Menu({
    id: 'messageContextMenu',
    items: menuItems
  });

  //

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
    {
      xtype: 'tbsplit',
      text: TRANSLATIONS.ID_ACTIONS,
      menu: menuItems,
      listeners: { menushow: enableDisableMenuOption }
    },

    '-',
    btnRead,
    '-',
    btnUnread,
    '-',
    btnAll,
    '->', // begin using the right-justified button container
    TRANSLATIONS.ID_PROCESS,
    comboProcess,
    '-',
    textSearch,
    resetSearchButton,
    btnSearch,
    '-',
    textJump,
    btnJump,
    ' ',
    ' '
  ];

  var toolbarGeneral = [
    btnRead,
    '-',
    btnUnread,
    '-',
    btnAll,
    '->', // begin using the right-justified button container
    TRANSLATIONS.ID_PROCESS,
    comboProcess,
    '-',
    textSearch,
    resetSearchButton,
    btnSearch,
    '-',
    textJump,
    btnJump,
    ' ',
    ' '
  ];

  var toolbarUnassigned = [
    btnRead,
    '-',
    btnUnread,
    '-',
    btnAll,
    '->', // begin using the right-justified button container
    TRANSLATIONS.ID_PROCESS,
    comboProcess,
    '-',
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
    {
      xtype: 'tbsplit',
      text: TRANSLATIONS.ID_ACTIONS,
      menu: menuItems,
      listeners: { menushow: enableDisableMenuOption }
    },
    '->',
    TRANSLATIONS.ID_PROCESS,
    comboProcess,
    '-',
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
    '->',
    textSearch,
    resetSearchButton,
    btnSearch,
    ' ',
    ' '
  ];

  var toolbarSent = [
    btnStarted,
    '-',
    btnCompleted,
    '-',
    btnAll,
    '->', // begin using the right-justified button container
     TRANSLATIONS.ID_PROCESS,
    comboProcess,
    '-',
    TRANSLATIONS.ID_STATUS,
    comboStatus,
    '-',
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
  //alert(action);
  switch (action) {
    case 'draft'      : itemToolbar = toolbarDraft; break;
    case 'sent'       : itemToolbar = toolbarSent;  break;
    case 'to_revise'  : itemToolbar = toolbarToRevise;  break;
    case 'to_reassign': itemToolbar = toolbarToReassign; break;
    case 'search'     : itemToolbar = toolbarSearch;     break;
    case 'unassigned' : itemToolbar = toolbarUnassigned; break;
    case 'gral'       : itemToolbar = toolbarGeneral;    break;
    default           : itemToolbar = toolbarTodo; break;
  }

  var tb = new Ext.Toolbar({
    height: 33,
    items: itemToolbar
  });

  // create the editor grid
  grid = new Ext.grid.GridPanel({
    region: 'center',
    id: 'casesGrid',
    store: storeCases,
    cm: cm,
    //autoHeight: true,
    layout: 'fit',
    viewConfig: {
      forceFit:true
    },
    listeners: {
      rowdblclick: openCase,
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
      displayMsg: TRANSLATIONS.ID_DISPLAY_ITEMS + ' &nbsp; ',
      emptyMsg: TRANSLATIONS.ID_DISPLAY_EMPTY
    })
  });


  grid.on('rowcontextmenu', function (grid, rowIndex, evt) {
      var sm = grid.getSelectionModel();
      sm.selectRow(rowIndex, sm.isSelected(rowIndex));
  }, this);
  grid.on('contextmenu', function (evt) {
      evt.preventDefault();
  }, this);

  grid.addListener('rowcontextmenu', onMessageContextMenu,this);
  
  // patch in order to hide the USR_UIR and PREVIOUS_USR_UID columns 
  var userIndex     = grid.getColumnModel().findColumnIndex('USR_UID');
  if ( userIndex >= 0 ) grid.getColumnModel().setHidden(userIndex, true);
  var prevUserIndex = grid.getColumnModel().findColumnIndex('PREVIOUS_USR_UID');
  if ( prevUserIndex >= 0 ) grid.getColumnModel().setHidden(prevUserIndex, true);
  
  if (action=='to_reassign'){
    //grid.getColumnModel().setHidden(0, true);
    grid.getColumnModel().setHidden(1, true);
  }

  // create reusable renderer


  // create the editor grid
  var reassignGrid = new Ext.grid.EditorGridPanel({
    id : Ext.id(),
    region: 'center',
    store: storeReassignCases,
    cm: reassignCm,
    autoHeight: true,
    viewConfig: {
      forceFit:true
    }
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

    });


var gridForm = new Ext.FormPanel({
        id: 'reassign-form',
        frame: true,
        labelAlign: 'left',
        //title: 'Company data',
        bodyStyle:'padding:5px',
        width: 750,
        layout: 'column',    // Specifies that the items will now be arranged in columns
        items: [{
            id : 'tasksGrid',
            columnWidth: 0.60,
            layout: 'fit',
            items: {
                id: 'TasksToReassign',
                xtype: 'grid',
                ds: storeReassignCases,
                cm: reassignCm,
                sm: new Ext.grid.RowSelectionModel({
                    singleSelect: true
                    /*listeners: {
                        rowselect: function(sm, row, rec) {
                            Ext.getCmp("reassign-form").getForm().loadRecord(rec);
                        }
                    }*/
                }),
                //autoExpandColumn: 'company',
                height: 350,
                title  : 'Cases to reassign - Task List',
                border : true,
                listeners: {
                    click: function() {
                        rows = this.getSelectionModel().getSelections();
                        var application = '';
                        if( rows.length > 0 ) {
                            var ids = '';
                            for(i=0; i<rows.length; i++) {
                              // filtering duplicate tasks
                             application = rows[i].get('APP_UID');
                            }
                        }
                        comboUsersToReassign.clearValue();
                        storeUsersToReassign.removeAll();
                        storeUsersToReassign.setBaseParam('application',application);
                        //storeUsersToReassign.load();
                        //alert(record.USERS);
                    } // Allow rows to be rendered.
                }
            }
        },{
            columnWidth: 0.4,
            xtype: 'fieldset',
            labelWidth: 70,
            title:'User List',
            defaults: {width: 120, border:false},    // Default config options for child items
            defaultType: 'textfield',
            autoHeight: true,
            bodyStyle: Ext.isIE ? 'padding:0 0 5px 15px;' : 'padding:10px 15px;',
            border: false,
            style: {
                "margin-left": "10px", // when you add custom margin in IE 6...
                "margin-right": Ext.isIE6 ? (Ext.isStrict ? "-10px" : "-13px") : "0"  // you have to adjust for it somewhere else
            },
            items: comboUsersToReassign
        }]
        //renderTo: bd
    });


    // manually trigger the data store load
    storeCases.setBaseParam( 'action', action );
    storeCases.setBaseParam( 'start',  0 );
    storeCases.setBaseParam( 'limit',  pageSize );
    storeCases.load();
    //newPopUp.add(reassignGrid);
    newPopUp.add(gridForm);
    newPopUp.addButton(btnExecReassignSelected);
    //newPopUp.addButton(btnExecReassign);   
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
          btnCompleted.toggle( false, true);
          break;
        case 'completed' :
          btnAll.toggle( false, true);
          btnStarted.toggle( false, true);
          break;
        case 'all' :
          btnRead.toggle( false, true);
          btnUnread.toggle( false, true);
          btnStarted.toggle( false, true);
          btnCompleted.toggle( false, true);
          break;
      }
      if(pressed){
      storeCases.setBaseParam( 'filter', item.id );
      } else {
        storeCases.setBaseParam( 'filter', '' );
      }
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

  //routine to hide the debug panel if it is open
  if( typeof parent != 'undefined' ){
    if( parent.PANEL_EAST_OPEN ){
      parent.PANEL_EAST_OPEN = false;
      parent.Ext.getCmp('debugPanel').hide();
      parent.Ext.getCmp('debugPanel').ownerCt.doLayout();
    }
  }

  _nodeId = '';
  switch(action){
    case 'draft':
      _nodeId = "CASES_DRAFT";
      break;
    case 'sent':
      _nodeId = "CASES_SENT";
      break;
    case 'unassigned':
      _nodeId = "CASES_SELFSERVICE";
      break;
    case 'paused':
      _nodeId = "CASES_PAUSED";
      break;
    case 'todo':
      _nodeId = "CASES_INBOX";
      break;
  }

  if( _nodeId != '' ){
    treePanel1 = parent.Ext.getCmp('tree-panel')
    if(treePanel1)
      node = treePanel1.getNodeById(_nodeId);
    if(node)
      node.select();
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
  var rows  = grid.getSelectionModel().getSelections();
  storeReassignCases.rejectChanges();
  var tasks = [];
  if( rows.length > 0 ) {
    ids = '';
    for(i=0; i<rows.length; i++) {
      // filtering duplicate tasks

         if( i != 0 ) ids += ',';
         ids += rows[i].get('APP_UID') + "|" + rows[i].get('TAS_UID')+ "|" + rows[i].get('DEL_INDEX');
    }
    storeReassignCases.setBaseParam( 'APP_UIDS', ids);
    storeReassignCases.load();
    newPopUp.show();

    //grid = reassignGrid.store.data;
    //Ext.Msg.alert ( grid );
/*
    for( var i =0; i < grid.length; i++) {
      grid[i].data.APP_UID = grid[i].data.USERS[0];
    }
    */
  }
  else {
     Ext.Msg.show({
       title:'',
       msg: TRANSLATIONS.ID_NO_SELECTION_WARNING,
       buttons: Ext.Msg.INFO,
       fn: function(){},
       animEl: 'elId',
       icon: Ext.MessageBox.INFO,
       buttons: Ext.MessageBox.OK
    });
  }
}

function inArray(arr, obj) {
  for(var i=0; i<arr.length; i++) {
    if (arr[i] == obj) return true;
  }
  return false;
}


// Add the additional 'advanced' VTypes -- [Begin]
Ext.apply(Ext.form.VTypes, {
	daterange : function(val, field) {
		var date = field.parseDate(val);

		if(!date){
			return;
		}
		if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
			var start = Ext.getCmp(field.startDateField);
			start.setMaxValue(date);
			start.validate();
			this.dateRangeMax = date;
		}
		else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
			var end = Ext.getCmp(field.endDateField);
			end.setMinValue(date);
			end.validate();
			this.dateRangeMin = date;
		}
		/*
		 * Always return true since we're only using this vtype to set the
		 * min/max allowed values (these are tested for after the vtype test)
		 */
		return true;
	}
});
// Add the additional 'advanced' VTypes -- [End]


});

function msgBox(title, msg, type){
  if( typeof('type') == 'undefined' )
    type = 'info';

  switch(type){
    case 'error':
      icon = Ext.MessageBox.ERROR;
      break;
    case 'info':
    default:
      icon = Ext.MessageBox.INFO;
      break;
  }

  Ext.Msg.show({
    title: title,
    msg: msg,
    fn: function(){},
    animEl: 'elId',
    icon: icon,
    buttons: Ext.MessageBox.OK
  });
}
