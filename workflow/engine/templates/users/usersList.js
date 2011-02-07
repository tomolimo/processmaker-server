/*
 * @author: Qennix
 * Jan 24th, 2011
 */

//Keyboard Events
new Ext.KeyMap(document, [
  {
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
},
{
  key: Ext.EventObject.DELETE,
  fn: function(k,e){
  iGrid = Ext.getCmp('infoGrid');
  rowSelected = iGrid.getSelectionModel().getSelected();
  if (rowSelected){
    DeleteUserAction();
  }
  }
}
]);

var store;
var cmodel;
var infoGrid;
var viewport;
var smodel;
var newButton;
var editButton;
var deleteButton;
var summaryButton;
var groupsButton;
//var reassignButton;
var authenticationButton;
var searchButton;
var searchText;
var contextMenu;
var user_admin = '00000000000000000000000000000001';
var pageSize;
var fullNameFormat;
var dateFormat;

Ext.onReady(function(){
  Ext.QuickTips.init();
  
  fullNameFormat = CONFIG.fullNameFormat;
  dateFormat = CONFIG.dateFormat;
  pageSize = parseInt(CONFIG.pageSize);
  
  newButton = new Ext.Action({
    text: _('ID_NEW'),
     iconCls: 'button_menu_ext ss_sprite  ss_add',
     handler: NewUserAction
  });
    
  summaryButton = new Ext.Action({
    text: _('ID_SUMMARY'),
     iconCls: 'button_menu_ext ss_sprite  ss_table',
     handler: SummaryTabOpen,
    disabled: true  
  });
    
  editButton = new Ext.Action({
    text: _('ID_EDIT'),
     iconCls: 'button_menu_ext ss_sprite  ss_pencil',
     handler: EditUserAction,
     disabled: true  
  });
    
  deleteButton = new Ext.Action({
    text: _('ID_DELETE'),
     iconCls: 'button_menu_ext ss_sprite  ss_delete',
     handler: DeleteUserAction,
     disabled: true
  });
    
  groupsButton = new Ext.Action({
     text: _('ID_GROUPS'),
     iconCls: 'button_menu_ext ss_sprite ss_group_add',
     handler: UsersGroupPage,
     disabled: true
  });
    
//    reassignButton = new Ext.Action({
//      text: _('ID_REASSIGN_CASES'),
//      iconCls: 'button_menu_ext ss_sprite ss_arrow_rotate_clockwise',
//      handler: DoNothing,
//      disabled: true
//    });
    
  authenticationButton = new Ext.Action({
     text: _('ID_AUTHENTICATION'),
    iconCls: 'button_menu_ext ss_sprite ss_key',
     handler: AuthUserPage,
     disabled: true
  });
    
    
  searchButton = new Ext.Action({
    text: _('ID_SEARCH'),
    handler: DoSearch
  });
    
  contextMenu = new Ext.menu.Menu({
    items: [editButton, deleteButton,'-',groupsButton,'-',authenticationButton,'-',summaryButton]
  });
    
  searchText = new Ext.form.TextField ({
    id: 'searchTxt',
    ctCls:'pm_search_text_field',
    allowBlank: true,
    width: 150,
    emptyText: _('ID_ENTER_SEARCH_TERM'),//'enter search term',
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
    
  smodel = new Ext.grid.RowSelectionModel({
    singleSelect: true,
     listeners:{
       rowselect: function(sm){
      editButton.enable();
      deleteButton.enable();
        groupsButton.enable();
        //reassignButton.enable();
        authenticationButton.enable();
        summaryButton.enable();
      },
      rowdeselect: function(sm){
        editButton.disable();
      deleteButton.disable();
      groupsButton.disable();
      //reassignButton.disable();
      authenticationButton.disable();
      summaryButton.disable();
      }
    }
  });

  store = new Ext.data.GroupingStore( {
    proxy : new Ext.data.HttpProxy({
      url: 'users_Ajax?function=usersList'
    }),
    reader : new Ext.data.JsonReader( {
      root: 'users',
      totalProperty: 'total_users',
      fields : [
        {name : 'USR_UID'},
      {name : 'USR_USERNAME'},
      {name : 'USR_FIRSTNAME'},
      {name : 'USR_LASTNAME'},
      {name : 'USR_EMAIL'},
      {name : 'USR_ROLE'},
      {name : 'USR_DUE_DATE'},
      {name : 'DEP_TITLE'},
      {name : 'LAST_LOGIN'},
      {name : 'USR_STATUS'},
      {name : 'TOTAL_CASES'},
      {name : 'DUE_DATE_OK'}
      ]
    })
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
  
  bbarpaging = new Ext.PagingToolbar({
    pageSize: pageSize,
     store: store,
     displayInfo: true,
     displayMsg: _('ID_GRID_PAGE_DISPLAYING_USERS_MESSAGE') + '&nbsp; &nbsp; ',
     emptyMsg: _('ID_GRID_PAGE_NO_USERS_MESSAGE'),
     items: ['-',_('ID_PAGE_SIZE')+':',comboPageSize]
  });
    
  cmodel = new Ext.grid.ColumnModel({
    defaults: {
      width: 50,
      sortable: true
    },
    columns: [
      {id:'USR_UID', dataIndex: 'USR_UID', hidden:true, hideable:false},
      {header: '', dataIndex: 'USR_UID', width: 30, align:'center', sortable: false, renderer: photo_user},
      {header: _('ID_USER_NAME'), dataIndex: 'USR_USERNAME', width: 90, hidden:false, align:'left'},
      {header: _('ID_FULL_NAME'), dataIndex: 'USR_USERNAME', width: 175, align:'left', renderer: full_name},
      {header: _('ID_EMAIL'), dataIndex: 'USR_EMAIL', width: 120, hidden: true, align: 'left'},
      {header: _('ID_STATUS'), dataIndex: 'USR_STATUS', width: 50, hidden: false, align: 'center', renderer: render_status},
      {header: _('ID_ROLE'), dataIndex: 'USR_ROLE', width: 180, hidden:false, align:'left'},
      {header: _('ID_DEPARTMENT'), dataIndex: 'DEP_TITLE', width: 150, hidden:true, align:'left'},
      {header: _('ID_LAST_LOGIN'), dataIndex: 'LAST_LOGIN', width: 108, hidden:false, align:'center'},
      {header: _('ID_CASES'), dataIndex: 'TOTAL_CASES', width: 45, hidden:false, align:'right'},
      {header: _('ID_DUE_DATE'), dataIndex: 'USR_DUE_DATE', width: 108, hidden:false, align:'center', renderer: render_duedate}
    ]
  });
    
  infoGrid = new Ext.grid.GridPanel({
    region: 'center',
     layout: 'fit',
     id: 'infoGrid',
    height:100,
     autoWidth : true,
     stateful : true,
     stateId : 'grid',
     enableColumnResize: true,
     enableHdMenu: true,
     frame:false,
     iconCls:'icon-grid',
     columnLines: false,
     viewConfig: {
      forceFit:true
    },
    title : _('ID_USERS'),
    store: store,
    cm: cmodel,
    sm: smodel,
    tbar: [newButton, '-',summaryButton,'-', editButton, deleteButton,'-',groupsButton,'-',authenticationButton,  {xtype: 'tbfill'}, searchText,clearTextButton,searchButton],
    bbar: bbarpaging,
    listeners: {
      rowdblclick: SummaryTabOpen
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
    
  infoGrid.on('contextmenu', 
    function (evt) {
      evt.preventDefault();
    },
    this
  );
    
  infoGrid.addListener('rowcontextmenu',onMessageContextMenu,this);

  infoGrid.store.load();

  viewport = new Ext.Viewport({
     layout: 'fit',
     autoScroll: false,
     items: [infoGrid]
  });
});

//Function Handles Context Menu Opening
onMessageContextMenu = function (grid, rowIndex, e) {
  e.stopEvent();
  var coords = e.getXY();
  contextMenu.showAt([coords[0], coords[1]]);
};

//Do Nothing Function
DoNothing = function(){};

//Open New User Form
NewUserAction = function(){
  location.href = 'users_New';
};

//Delete User Action
DeleteUserAction = function(){
  var uid = infoGrid.getSelectionModel().getSelected();
  if (uid){
  if (uid.data.USR_UID==user_admin){
    Ext.Msg.alert(_('ID_USERS'), _('ID_CANNOT_DELETE_ADMIN_USER'));
  }else{
    viewport.getEl().mask(_('ID_PROCESSING'));
    Ext.Ajax.request({
    url: 'users_Ajax',
    params: {'function': 'canDeleteUser', uUID: uid.data.USR_UID},
    success: function(res, opt){
      viewport.getEl().unmask();
      response = Ext.util.JSON.decode(res.responseText);
      if (response.candelete){
      if (response.hashistory){
        Ext.Msg.confirm(_('ID_CONFIRM'), _('ID_USERS_DELETE_WITH_HISTORY'), 
        function(btn){
          if (btn=='yes') DeleteUser(uid.data.USR_UID);
        }
        );
      }else{
        Ext.Msg.confirm(_('ID_CONFIRM'), _('ID_MSG_CONFIRM_DELETE_USER'), 
        function(btn){
          if (btn=='yes') DeleteUser(uid.data.USR_UID);
          }
        );
        }
      }else{
      PMExt.error(_('ID_USERS'), _('ID_MSG_CANNOT_DELETE_USER'));  
      }
    },
    failure: function(r,o){
      viewport.getEl().unmask();
      DoNothing();
    }
    });
  }
  }
};

//Open User-Groups Manager
UsersGroupPage = function(value){
  rowSelected = infoGrid.getSelectionModel().getSelected();
  if (rowSelected){
  value = rowSelected.data.USR_UID;
  location.href = 'usersGroups?uUID=' + value + '&type=group';
  }
};

//Open Summary Tab
SummaryTabOpen = function(){
  rowSelected = infoGrid.getSelectionModel().getSelected();
  if (rowSelected){
  value = rowSelected.data.USR_UID;
  location.href = 'usersGroups?uUID=' + value + '&type=summary';
  }
};

//Edit User Action
EditUserAction = function(){
  var uid = infoGrid.getSelectionModel().getSelected();
  if (uid){
  location.href = 'users_Edit?USR_UID=' + uid.data.USR_UID;
  }
};

//Open Authentication-User Manager
AuthUserPage = function(value){
  rowSelected = infoGrid.getSelectionModel().getSelected();
  if (rowSelected){
  value = rowSelected.data.USR_UID;
  location.href = 'usersGroups?uUID=' + value + '&type=auth';;
  }
};

//Renderer Active/Inactive Role
photo_user = function(value){
  return '<img border="0" src="users_ViewPhotoGrid?h=' + Math.random() +'&pUID=' + value + '" width="20" />';
};

//Render Full Name
full_name = function(v,x,s){
  return parseFullName(v, s.data.USR_FIRSTNAME, s.data.USR_LASTNAME, fullNameFormat);
};

//Render Status
render_status = function(v){
  switch(v){
  case 'ACTIVE': return '<font color="green">' + _('ID_ACTIVE') + '</font>'; break;
  case 'INACTIVE': return '<font color="red">' + _('ID_INACTIVE') + '</font>';; break;
  case 'VACATION': return '<font color="blue">' + _('ID_VACATION') + '</font>';; break;
  }
};

//Render Due Date
render_duedate = function(v,x,s){
  if (s.data.DUE_DATE_OK)
  return v;  
  else
  return '<font color="red">' + v + '</font>';
};

//Load Grid By Default
GridByDefault = function(){
  searchText.reset();
  infoGrid.store.load();
};

//Do Search Function
DoSearch = function(){
  infoGrid.store.load({params: {textFilter: searchText.getValue()}});  
};

//Delete User Function
DeleteUser = function(uid){
  Ext.Ajax.request({
  url: 'users_Ajax',
  params: {'function': 'deleteUser', USR_UID: uid},
  success: function(res, opt){
    DoSearch();
    PMExt.notify(_('ID_USERS'),_('ID_USERS_SUCCESS_DELETE'));
  },
  failure: DoNothing
  });
};

//Update Page Size Configuration
UpdatePageConfig = function(pageSize){
  Ext.Ajax.request({
  url: 'users_Ajax',
  params: {'function':'updatePageSize', size: pageSize}
  });
};

//Function Parse Full Name Format
parseFullName = function(uN, fN, lN, f){
  var aux = f;
  aux = aux.replace('@userName',uN);
  aux = aux.replace('@firstName',fN);
  aux = aux.replace('@lastName',lN);
  return aux;
};