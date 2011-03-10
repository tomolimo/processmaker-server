/**
 * BPMN Designer v1.1
 * @date Feb 2th, 2011
 * @author Erik A. O. <erik@colosa.com>
 */

var toolbarPanel;
var actorsPanel;
var northPanelItems;
var eastPanelTree;
var ActiveProperty;
var comboCategory;
var comboCalendar;
var comboPMVariables;
var propertiesGrid;
var propertyStore;
var usersTaskStore;
var usersTaskGrid;
var usersTaskGridContextMenu;
var usersTaskAdHocStore;
var usersTaskAdHocGrid;
var usersTaskAdHocGridContextMenu;
var mainMenu;
var tbar1;

var usersPanelStart = 0;
var usersPanelLimit = 1000;
var usersStore;
var usersGrid;
var groupsStore;
var groupsGrid;
var adHocUsersStore;
var adHocUsersGrid;
var adHocGroupsStore;
var adHocGroupsGrid;

var usersActorsWin;
var groupsActorsWin;
var adhocUsersActorsWin;
var adHocGroupsActorsWin;

var _onDropActors;
var _targetTask;

Ext.onReady(function(){
  divScroll = document.body;
    
toolbarPanel = {
  title: '&nbsp;',
  border: true,
  xtype:'buttongroup',
  defaultType: 'button',
  cls: 'btn-panel-pmtoolbar',
  columns: 1,
  defaults: {
    scale: 'small'
  },

  items : [{
      iconCls: 'button_small_ext ss_sprite ss_bpmn_task-18x18',
      id:"x-shapes-task",
      text: ' ', 
      width: 22
    },{
      iconCls: 'button_small_ext ss_sprite ss_bpmn_startevent-18x18',
      id:"x-shapes-startEvent",
      text: ' ', 
      width: 22
    },{
      iconCls: 'button_small_ext ss_sprite ss_bpmn_interevent-18x18',
      id:"x-shapes-interEvent",
      text: ' ', 
      width: 22
    },{
      iconCls: 'button_small_ext ss_sprite ss_bpmn_endevent-18x18',
      id:"x-shapes-endEvent",
      text: ' ', 
      width: 22
    },{
      iconCls: 'ss_sprite ss_bpmn_gateway-18x18',
      id:"x-shapes-gateways",
      text: ' ', 
      width: 22
    },{
      iconCls: 'ss_sprite ss_bpmn_annotation-18x18',
      id:"x-shapes-annotation",
      text: ' ', 
      width: 22
    }
  ]
};


actorsPanel = {
  title: '&nbsp;',//_('ID_ACTORS'),
  border: true,
  xtype:'buttongroup',
  defaultType: 'button',
  cls: 'btn-panel-pmtoolbar',
  columns: 1,
  defaults: {
    scale: 'small'
  },
  items : [
    {
      iconCls: 'ICON_USERS',
      id:"x-pm-users",
      text: ' ', 
      width: 22,
      handler: function(){
        usersActorsWin.show();
      }
    },{
      iconCls: 'ICON_GROUPS',
      id:"x-pm-groups",
      text: ' ', 
      width: 22,
      handler: function(){
        groupsActorsWin.show();
      }
    },{
      iconCls: 'ss_sprite ss_user_suit',
      id:"x-pm-users-adhoc",
      text: ' ', 
      width: 22,
      handler: function(){
        adHocUsersActorsWin.show();
      }
    },{
      iconCls: 'ss_sprite ss_group_suit',
      id:"x-pm-groups-adhoc",
      text: ' ', 
      width: 22,
      handler: function(){
        adHocGroupsActorsWin.show();
      }
    }
  ]
};
        

northPanelItems = [
/*
  {
    text: 'Save',
    cls: 'x-btn-text-icon',
    iconCls: 'button_menu_ext ss_sprite ss_disk',
    handler: function() {
      saveProcess();
    }
  }, {
      text:'Save as',
      iconCls: 'button_menu_ext ss_sprite ss_disk_multiple'
  }, {
    xtype: 'tbseparator'
  },
*/{
    //xtype: 'tbsplit',
    text:'Edit',
    //iconCls: '',
    menu: new Ext.menu.Menu({
      items: [
        {
          text: _('ID_SWITCH_EDITOR'),
          iconCls: 'ss_sprite ss_arrow_switch',
          handler: function() {
            if(typeof pro_uid !== 'undefined') {
              location.href = 'processes/processes_Map?PRO_UID=' +pro_uid+ '&rand=' +Math.random()
            }
          }
        }, {
          text: _('ID_SNAP_GEOMETRY'),
          checked: false, // when checked has a boolean value, it is assumed to be a CheckItem
          checkHandler: function(item, checked){
            workflow.setSnapToGeometry(checked);
          }
        }
      ]
    })
  },
  {
    //xtype: 'tbsplit',
    //iconCls: 'button_menu_ext ss_sprite ss_application',
    text: 'Process',
    menu: new Ext.menu.Menu({
      items: [{
          text    : 'Dynaform',
          iconCls: 'button_menu_ext ss_sprite ss_application_form',
          handler : function() {
            processObj.addDynaform();
          }
        }, {
          text: 'Input Document',
          iconCls: 'button_menu_ext ss_sprite ss_page_white_put',
          handler : function() {
            processObj.addInputDoc();
          }
        }, {
          text: 'Output Document',
          iconCls: 'button_menu_ext ss_sprite ss_page_white_get',

          handler : function() {
            processObj.addOutputDoc();
          }
        }, {
          text: 'Trigger',
          iconCls: 'button_menu_ext ss_sprite ss_cog',
          handler : function() {
            processObj.addTriggers();
          }
        }, {
          text: 'Report Table',
          iconCls: 'button_menu_ext ss_sprite ss_table',
          handler : function() {
            processObj.addReportTable();
          }
        }, {
          text: 'Database Connection',
          iconCls: 'button_menu_ext ss_sprite ss_database_connect',
          handler : function() {
            processObj.dbConnection();
          }
        }

      ]
    })
  
  }, 
  '-',
  {
    text:'Undo',
    iconCls: 'button_menu_ext ss_sprite ss_arrow_undo',
    handler: function() {
      workflow.getCommandStack().undo();
    }
  }, {
    text:'Redo',
    iconCls: 'button_menu_ext ss_sprite ss_arrow_redo',
    handler: function() {
     workflow.getCommandStack().redo();
    }
  },{
    //xtype: 'tbsplit',
    text:'Zoom',
    iconCls: 'button_menu_ext ss_sprite ss_zoom',
    menu: new Ext.menu.Menu({
      items: [{
          text    : '25%',
          handler: function() {
            Ext.getCmp('designerTab')._setDesignerTitle(pro_title + ' (25%)');
            workflow.zoom('25');
          }
        },{
          text    : '50%',
          handler: function() {
            Ext.getCmp('designerTab')._setDesignerTitle(pro_title + ' (50%)');
            workflow.zoom('50');
          }
        },{
          text    : '75%',
          handler: function() {
            Ext.getCmp('designerTab')._setDesignerTitle(pro_title + ' (75%)');
            workflow.zoom('75');
          }
        },{
          text    : '100%',
          handler: function() {
            Ext.getCmp('designerTab')._setDesignerTitle(pro_title + ' (100%)');
            workflow.zoom('100');
          }
        },{
          text    : '125%',
          handler: function() {
            Ext.getCmp('designerTab')._setDesignerTitle(pro_title + ' (125%)');
            workflow.zoom('125');
          }
        },{
          text    : '150%',
          handler: function() {
            Ext.getCmp('designerTab')._setDesignerTitle(pro_title + ' (150%)');
            workflow.zoom('150');
          }
        },{
          text    : '200%',
          handler: function() {
            Ext.getCmp('designerTab')._setDesignerTitle(pro_title + ' (200%)');
            workflow.zoom('200');
          }
        }
      ]
    })
  } /*,{
    xtype: 'tbseparator'
  }, {
    text: _('ID_ACTORS'),
    iconCls: 'ICON_USERS',
    handler: function(){
      usersPanel.show()
    }

  }, {
    xtype: 'tbfill'
  }, {
    text: _('ID_SWITCH_EDITOR'),
    iconCls: 'button_menu_ext ss_sprite ss_pencil',
    handler: function() {
      if(typeof pro_uid !== 'undefined') {
        location.href = 'processes/processes_Map?PRO_UID=' +pro_uid+ '&rand=' +Math.random()
      }
    }
  }*/
]



eastPanelTree = new Ext.tree.TreePanel({
  id: 'eastPanelTree',
  useArrows: false,
  autoScroll: true,
  animate: true,
  rootVisible : false,
  border: true,
  height: PMExt.getBrowser().screen.height * 0.3,
  region: 'north',
  split : true,
  collapseMode:'mini',
  loader : new Ext.tree.TreeLoader({
    preloadChildren : true,
    dataUrl : 'processProxy/getProcessDetail',
    baseParams : {
      PRO_UID: pro_uid
    }
  }),
  root: {
    nodeType : 'async',
    draggable : false,
    id : 'root',
    expanded : true
  }
});

// tree east panel selection change
eastPanelTree.getSelectionModel().on('selectionchange', function(tree, node){
  if( node.attributes.type == 'task') {
    _TAS_UID = node.attributes.id;

    Ext.getCmp('usersPanelTabs').getTabEl('usersTaskGrid').style.display = '';
    Ext.getCmp('usersPanelTabs').getTabEl('usersTaskAdHocGrid').style.display = '';
    Ext.getCmp('usersTaskGrid').store.reload({params: {tas_uid: _TAS_UID, tu_type: 1}});
    Ext.getCmp('usersTaskAdHocGrid').store.reload({params: {tas_uid: _TAS_UID, tu_type: 2}});
  } else {
    Ext.getCmp('usersPanelTabs').setActiveTab(0);
    Ext.getCmp('usersPanelTabs').getTabEl('usersTaskGrid').style.display = 'none';
    Ext.getCmp('usersPanelTabs').getTabEl('usersTaskAdHocGrid').style.display = 'none';
  }
  propertyStore.reload({params: {
    action : 'getProperties',
    UID    : node.attributes.id,
    type   : node.attributes.type
  }});
  Ext.getCmp('eastPanelCenter').setTitle(node.attributes.typeLabel+': '+node.attributes.text);
  //propertiesGrid.store.sort('name','DESC');
  propertiesGrid.setSource(propertyStore.reader.jsonData.prop);

})

ActiveProperty = new Ext.form.Checkbox({
  name        : 'active',
  fieldLabel : 'Active',
  checked    : true,
  inputValue : '1'
});

comboCategory = new Ext.form.ComboBox({
  fieldLabel    : 'Category',
  name        : 'category',
  allowBlank     : true,
  store        : new Ext.data.Store( {
    //autoLoad: true,  //autoload the data
    proxy : new Ext.data.HttpProxy( {
      url : 'processProxy/getCategoriesList',
      method : 'POST'
    }),
    baseParams : {
      action : 'getCategoriesList'
    },
    reader : new Ext.data.JsonReader( {
      //root : 'rows',
      fields : [
        {name : 'CATEGORY_UID'},
        {name : 'CATEGORY_NAME'}
      ]
    })
  }),
  valueField : 'CATEGORY_NAME',
  displayField : 'CATEGORY_NAME',
  typeAhead    : true,
  //mode         : 'local',
  triggerAction    : 'all',
  editable: true,
  forceSelection: true,
  selectOnFocus  : true
});

comboCalendar = new Ext.form.ComboBox({
  fieldLabel    : 'Calendar',
  name        : 'calendar',
  allowBlank     : true,
  store        : new Ext.data.Store( {
    //autoLoad: true,  //autoload the data
    proxy : new Ext.data.HttpProxy({ url: 'processProxy/getCaledarList'}),
    //baseParams : {action: 'getCaledarList'},
    reader : new Ext.data.JsonReader( {
      root : 'rows',
      fields : [
        {name : 'CALENDAR_UID'},
        {name : 'CALENDAR_NAME'}
      ]
    })
  }),
  valueField : 'CALENDAR_NAME',
  displayField : 'CALENDAR_NAME',
  typeAhead    : true,
  //mode        : 'local',
  triggerAction    : 'all',
  editable: true,
  forceSelection: true
});

var comboPMVariables = new Ext.form.ComboBox({
  fieldLabel    : 'Calendar',
  name        : 'calendar',
  allowBlank     : true,
  store        : new Ext.data.Store( {
    //autoLoad: false,  //autoload the data
    proxy : new Ext.data.HttpProxy({ url: 'processProxy/getPMVariables'}),
    baseParams : {PRO_UID: pro_uid},
    reader : new Ext.data.JsonReader( {
      root : 'rows',
      fields : [
        {name : 'sName'},
        {name : 'sName'}
      ]
    })
  }),
  valueField : 'sName',
  displayField : 'sName',
  typeAhead    : true,
  //mode        : 'local',
  triggerAction: 'all',
  editable: true,
  forceSelection: true
});

propertiesGrid = new Ext.grid.PropertyGrid({
  id: 'propGrid',
  title: 'Properties',
  loadMask : {msg:"Loading..."},
  autoHeight: true,
  viewConfig : {
      forceFit: true,
      scrollOffset: 2 // the grid will never have scrollbars
  },
  customEditors: {
    //'Debug' : new Ext.grid.GridEditor(ActiveProperty),
    'Category' : new Ext.grid.GridEditor(comboCategory),
    'Calendar' : new Ext.grid.GridEditor(comboCalendar),
    'Variable for case priority' : new Ext.grid.GridEditor(comboPMVariables)
  }
});

propertiesGrid.on('afteredit', function afterEdit(r) {
  var node = Ext.getCmp('eastPanelTree').getSelectionModel().getSelectedNode();
  var UID;
  var type;

  if( node ) {
    UID = node.attributes.id;
    type = node.attributes.type;
  } else {
    UID = pro_uid;
    type = 'process';
  }
  Ext.Ajax.request({
    url: 'processProxy/saveProperties',
    params: {
      UID: UID,
      type: type,
      property: r.record.data.name,
      value: r.value
    },
    success: function(response) {
      if( type == 'process' && r.record.data.name == 'Title') {
        pro_title = r.value;
        
        Ext.getCmp('designerTab')._setDesignerTitle(pro_title);
        Ext.getCmp('eastPanelTree').getNodeById(UID).setText(pro_title);
      } else if( type == 'task' && r.record.data.name == 'Title') {
        Ext.getCmp('eastPanelTree').getNodeById(UID).setText(r.value);
        //here we need to find and update the task title into task figure on designer
        //if the current selection is the same node editing the title property
        
        if( workflow.currentSelection.id == UID ) { 
          workflow.currentSelection.taskName = r.value;
        }
      }
    },
    failure: function(){
      //Ext.Msg.alert ('Failure');
    }
  });

  //r.record.commit();
}, this );


propertyStore = new Ext.data.JsonStore({
  id: 'propertyStore',
  autoLoad: true,  //autoload the data
  url: 'processProxy/getProperties',
  root: 'prop',
  fields: ['title', 'description'],
  store: new Ext.grid.PropertyStore({
    sortable: false,
    defaultSortable: false
  }),
  listeners: {
    load: {
      fn: function(store, records, options){
        //propertiesGrid.store.sort('name','DESC');
        propertiesGrid.setSource(store.reader.jsonData.prop);
      }
    }
  },
  baseParams: {
    UID    : pro_uid,
    type   : 'process'
  }
});


usersTaskStore = new Ext.data.GroupingStore( {
    autoLoad: false,
    url: 'processProxy/getActorsTask',
    reader : new Ext.data.JsonReader({
      totalProperty: 'totalCount',
      root: 'data',
      fields : [
        {name : 'USR_UID'},
        {name : 'USR_USERNAME'},
        {name : 'USR_FIRSTNAME'},
        {name : 'USR_LASTNAME'},
        {name : 'NAME'},
        {name : 'TU_RELATION'}
      ]
    }),
    baseParams: {tas_uid: '', tu_type: ''},
    groupField: 'TU_RELATION'
  });

  usersTaskGrid = new Ext.grid.GridPanel({
    id       : 'usersTaskGrid',
    title    : _('ID_ACTORS'),
    height   : 180,
    stateful : true,
    stateId  : 'usersTaskGrid',
    sortable:false,
    view: new Ext.grid.GroupingView({
      forceFit:true,
      groupTextTpl: '{[values.rs.length]} {[values.rs[0].data["TU_RELATION"] == 1 ? "Users" : "Groups"]}'
    }),
    cm : new Ext.grid.ColumnModel({
      defaults: {
        width: 300,
        sortable: true
      },
      columns : [
        {id:'USR_UID', dataIndex: 'USR_UID', hidden:true, hideable:false},
        {header: 'Assigned', id:'TU_RELATION', dataIndex: 'TU_RELATION', hidden:true, hideable:false},
        {header: 'User', dataIndex: 'USER', width: 249, renderer:function(v,p,r){
          if( r.data.TU_RELATION == '1' )
            return _FNF(r.data.USR_USERNAME, r.data.USR_FIRSTNAME, r.data.USR_LASTNAME);
          else
            return r.data.NAME;
        }}
      ]
    }),
    store: usersTaskStore,
    listeners: {
      render: function(){
        this.loadMask = new Ext.LoadMask(this.body, {msg:_('ID_LOADING')});
      }
    }/*,
    tbar:[
      '->', {
        text: _('ID_REMOVE'),
        iconCls: 'button_menu_ext ss_sprite  ss_delete',
        handler: removeUsersTask
      }
    ]*/,
    bbar: [new Ext.PagingToolbar({
      pageSize   : 10,
      store      : usersTaskStore,
      displayInfo: true,
      displayMsg : '{2} Users',
      emptyMsg   : ''
    })]
  });

    //connecting context menu  to grid
  usersTaskGrid.addListener('rowcontextmenu', function(grid, rowIndex, e){
    e.stopEvent();
    var coords = e.getXY();
    usersTaskGridContextMenu.showAt([coords[0], coords[1]]);
  });

  //by default the right click is not selecting the grid row over the mouse
  usersTaskGrid.on('rowcontextmenu', function (grid, rowIndex, evt) {
    var sm = grid.getSelectionModel();
    sm.selectRow(rowIndex, sm.isSelected(rowIndex));
  });

  //prevent default
  usersTaskGrid.on('contextmenu', function (evt) {
      evt.preventDefault();
  });

  usersTaskGridContextMenu = new Ext.menu.Menu({
    id: 'messageContextMenu',
    items: [{
        text: _('ID_REMOVE'),
        iconCls: 'button_menu_ext ss_sprite  ss_delete',
        handler: removeUsersTask
      }
    ]
  });

  //AD HOC
  usersTaskAdHocStore = new Ext.data.GroupingStore( {
    autoLoad: false,
    url: 'processProxy/getActorsTask',
    reader : new Ext.data.JsonReader({
      totalProperty: 'totalCount',
      root: 'data',
      fields : [
        {name : 'USR_UID'},
        {name : 'USR_USERNAME'},
        {name : 'USR_FIRSTNAME'},
        {name : 'USR_LASTNAME'},
        {name : 'NAME'},
        {name : 'TU_RELATION'}
      ]
    }),
    baseParams: {tas_uid: '', tu_type: ''},
    groupField: 'TU_RELATION'
  });

  usersTaskAdHocGrid = new Ext.grid.GridPanel({
    id       : 'usersTaskAdHocGrid',
    title    : _('ID_AD_HOC_ACTORS'),
    height   : 180,
    stateful : true,
    stateId  : 'usersTaskAdHocGrid',
    sortable:false,
    view: new Ext.grid.GroupingView({
      forceFit:true,
      groupTextTpl: '{[values.rs.length]} {[values.rs[0].data["TU_RELATION"] == 1 ? "Users" : "Groups"]}'
    }),
    cm : new Ext.grid.ColumnModel({
      defaults: {
        width: 300,
        sortable: true
      },
      columns : [
        {id:'USR_UID', dataIndex: 'USR_UID', hidden:true, hideable:false},
        {header: 'Assigned', id:'TU_RELATION', dataIndex: 'TU_RELATION', hidden:true, hideable:false},
        {header: 'User', dataIndex: 'USER', width: 249, renderer:function(v,p,r){
          if( r.data.TU_RELATION == '1' )
            return _FNF(r.data.USR_USERNAME, r.data.USR_FIRSTNAME, r.data.USR_LASTNAME);
          else
            return r.data.NAME;
        }}
      ]
    }),
    store: usersTaskAdHocStore,
    listeners: {
      render: function(){
        this.loadMask = new Ext.LoadMask(this.body, {msg:_('ID_LOADING')});
      }
    }/*,
    tbar:[
      '->', {
        text: _('ID_REMOVE'),
        iconCls: 'button_menu_ext ss_sprite  ss_delete',
        handler: removeUsersTask
      }
    ]*/,
    bbar: [new Ext.PagingToolbar({
      pageSize   : 10,
      store      : usersTaskStore,
      displayInfo: true,
      displayMsg : '{2} Users',
      emptyMsg   : ''
    })]
  });


    //connecting context menu  to grid
  usersTaskAdHocGrid.addListener('rowcontextmenu', function(grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    usersTaskAdHocGridContextMenu.showAt([coords[0], coords[1]]);
  },this);

  //by default the right click is not selecting the grid row over the mouse
  //we need to set this four lines
  usersTaskAdHocGrid.on('rowcontextmenu', function (grid, rowIndex, evt) {
    var sm = grid.getSelectionModel();
    sm.selectRow(rowIndex, sm.isSelected(rowIndex));
  }, this);

  //prevent default
  usersTaskGrid.on('contextmenu', function (evt) {
      evt.preventDefault();
  }, this);

  usersTaskAdHocGridContextMenu = new Ext.menu.Menu({
    id: 'messagAdHocGrideContextMenu',
    items: [{
        text: _('ID_REMOVE'),
        iconCls: 'button_menu_ext ss_sprite  ss_delete',
        handler: removeUsersAdHocTask
      }
    ]
  });

  /*** for actors ***/
  usersStore = new Ext.data.Store({
    autoLoad: false,
    proxy : new Ext.data.HttpProxy({
      url: 'processProxy/getUsers?start='+usersPanelStart+'&limit='+usersPanelLimit
    }),
    reader : new Ext.data.JsonReader( {
      totalProperty: 'totalCount',
      root: 'data',
      fields : [
        {name : 'USR_UID'},
        {name : 'USER'},
        {name : 'USR_USERNAME'},
        {name : 'USR_FIRSTNAME'},
        {name : 'USR_LASTNAME'}
      ]
    }),
    listeners: {
      load: function(){
        usersActorsWin.setTitle(_('ID_USERS_ACTORS') + ' (' +usersStore.reader.jsonData.totalCount+ ')');
      }
    }
  });

  usersGrid = new Ext.grid.GridPanel({
    id       : 'usersGrid',
    height   : 180,
    ddGroup  : 'task-assignment',
    enableDragDrop : true,
    width: 150,
    cm : new Ext.grid.ColumnModel({
      defaults: {
        width: 200,
        sortable: true
      },
      columns : [
        {header: 'USR_UID', id:'USR_UID', dataIndex: 'USR_UID', hidden:true, hideable:false},
        {header: 'User', dataIndex: 'USER', width: 249, renderer:function(v,p,r){
          return _FNF(r.data.USR_USERNAME, r.data.USR_FIRSTNAME, r.data.USR_LASTNAME);
        }}
      ]
    }),
    store: usersStore,
    loadMask: {msg:_('ID_LOADING')},
    tbar : [
      new Ext.form.TextField ({
        id    : 'usersSearchTxt',
        ctCls :'pm_search_text_field',
        allowBlank : true,
        width : 170,
        emptyText : _('ID_ENTER_SEARCH_TERM'),
        listeners : {
          specialkey: function(f,e){
            if (e.getKey() == e.ENTER)
              usersSearch();
          }
        }
      }), {
        text    :'X',
        ctCls   :'pm_search_x_button',
        handler : function(){
          usersStore.setBaseParam( 'search', '');
          usersStore.load({params:{start : 0 , limit :  usersPanelLimit}});
          Ext.getCmp('usersSearchTxt').setValue('');
        }
      }, {
        text    :TRANSLATIONS.ID_SEARCH,
        handler : usersSearch
      }
    ]
    /*,
    bbar: [new Ext.PagingToolbar({
      pageSize   : usersPanelLimit,
      store      : usersStore,
      displayInfo: true,
      displayMsg : '{2} Users',
      emptyMsg   : ''
    })]*/
  });
	
	adHocUsersStore = new Ext.data.Store({
    autoLoad: false,
    proxy : new Ext.data.HttpProxy({
      url: 'processProxy/getUsers?start='+usersPanelStart+'&limit='+usersPanelLimit
    }),
    reader : new Ext.data.JsonReader( {
      totalProperty: 'totalCount',
      root: 'data',
      fields : [
        {name : 'USR_UID'},
        {name : 'USER'},
        {name : 'USR_USERNAME'},
        {name : 'USR_FIRSTNAME'},
        {name : 'USR_LASTNAME'}
      ]
    }),
    listeners: {
      load: function(){
        adHocUsersActorsWin.setTitle(_('ID_ADHOC_USERS_ACTORS') + ' (' +adHocUsersStore.reader.jsonData.totalCount+ ')');
      }
    }
  });

  adHocUsersGrid = new Ext.grid.GridPanel({
    id       : 'adHocUsersGrid',
    height   : 180,
    ddGroup  : 'task-assignment',
    enableDragDrop : true,
    width: 150,
    cm : new Ext.grid.ColumnModel({
      defaults: {
        width: 200,
        sortable: true
      },
      columns : [
        {header: 'USR_UID', id:'USR_UID', dataIndex: 'USR_UID', hidden:true, hideable:false},
        {header: 'User', dataIndex: 'USER', width: 249, renderer:function(v,p,r){
          return _FNF(r.data.USR_USERNAME, r.data.USR_FIRSTNAME, r.data.USR_LASTNAME);
        }}
      ]
    }),
    store: adHocUsersStore,
    loadMask: {msg:_('ID_LOADING')},
    tbar : [
      new Ext.form.TextField ({
        id    : 'adHocUsersSearchTxt',
        ctCls :'pm_search_text_field',
        allowBlank : true,
        width : 170,
        emptyText : _('ID_ENTER_SEARCH_TERM'),
        listeners : {
          specialkey: function(f,e){
            if (e.getKey() == e.ENTER)
              adHocUsersSearch();
          }
        }
      }), {
        text    :'X',
        ctCls   :'pm_search_x_button',
        handler : function(){
          adHocUsersStore.setBaseParam( 'search', '');
          adHocUsersStore.load({params:{start : 0 , limit :  usersPanelLimit}});
          Ext.getCmp('adHocUsersSearchTxt').setValue('');
        }
      }, {
        text    :TRANSLATIONS.ID_SEARCH,
        handler : adHocUsersSearch
      }
    ]
    /*,
    bbar: [new Ext.PagingToolbar({
      pageSize   : usersPanelLimit,
      store      : usersStore,
      displayInfo: true,
      displayMsg : '{2} Users',
      emptyMsg   : ''
    })]*/
  });
  
  groupsStore = new Ext.data.Store( {
    autoLoad: false,
    proxy : new Ext.data.HttpProxy({
      url: 'processProxy/getGroups?start='+usersPanelStart+'&limit='+usersPanelLimit
    }),
    reader : new Ext.data.JsonReader( {
      totalProperty: 'totalCount',
      root: 'data',
      fields : [
        {name : 'GRP_UID'},
        {name : 'CON_VALUE'}
      ]
    }),
    listeners: {
      load: function(){
        groupsActorsWin.setTitle(_('ID_GROUPS_ACTORS') + ' (' +groupsStore.reader.jsonData.totalCount+ ')');
      }
    }
  });

  groupsGrid = new Ext.grid.GridPanel({
    id       : 'groupsGrid',
    ddGroup  : 'task-assignment',
    height   : 180,
    width    : 150,
    enableDragDrop : true,
    cm : new Ext.grid.ColumnModel({
      defaults : {
        width    : 250,
        sortable : true
      },
      columns: [
        {id:'GRP_UID', dataIndex: 'GRP_UID', hidden:true, hideable:false},
        {header: 'Group', dataIndex: 'CON_VALUE', width: 249}
      ]
    }),
    store : groupsStore,
    loadMask: {msg:_('ID_LOADING')},
    tbar : [
      new Ext.form.TextField ({
        id    : 'groupsSearchTxt',
        ctCls :'pm_search_text_field',
        allowBlank : true,
        width : 170,
        emptyText : _('ID_ENTER_SEARCH_TERM'),
        listeners : {
          specialkey: function(f,e){
            if (e.getKey() == e.ENTER)
              groupsSearch();
          }
        }
      }), {
        text    :'X',
        ctCls   :'pm_search_x_button',
        handler : function(){
          groupsStore.setBaseParam( 'search', '');
          groupsStore.load({params:{start : 0 , limit :  usersPanelLimit}});
          Ext.getCmp('groupsSearchTxt').setValue('');
        }
      }, {
        text    :TRANSLATIONS.ID_SEARCH,
        handler : groupsSearch
      }
    ]/*,
    bbar: [new Ext.PagingToolbar({
      pageSize   : usersPanelLimit,
      store      : groupsStore,
      displayInfo: true,
      displayMsg : '{2} Groups',
      emptyMsg   : 'No records found'
    })]*/
  });
	
	adHocGroupsStore = new Ext.data.Store( {
    autoLoad: false,
    proxy : new Ext.data.HttpProxy({
      url: 'processProxy/getGroups?start='+usersPanelStart+'&limit='+usersPanelLimit
    }),
    reader : new Ext.data.JsonReader( {
      totalProperty: 'totalCount',
      root: 'data',
      fields : [
        {name : 'GRP_UID'},
        {name : 'CON_VALUE'}
      ]
    }),
    listeners: {
      load: function(){
        adHocGroupsActorsWin.setTitle(_('ID_ADHOC_GROUPS_ACTORS') + ' (' +adHocGroupsStore.reader.jsonData.totalCount+ ')');
      }
    }
  });

  adHocGroupsGrid = new Ext.grid.GridPanel({
    id       : 'adHocGroupsGrid',
    ddGroup  : 'task-assignment',
    height   : 180,
    width    : 150,
    enableDragDrop : true,
    cm : new Ext.grid.ColumnModel({
      defaults : {
        width    : 250,
        sortable : true
      },
      columns: [
        {id:'GRP_UID', dataIndex: 'GRP_UID', hidden:true, hideable:false},
        {header: 'Group', dataIndex: 'CON_VALUE', width: 249}
      ]
    }),
    store : adHocGroupsStore,
    loadMask: {msg:_('ID_LOADING')},
    tbar : [
      new Ext.form.TextField ({
        id    : 'adHocGroupsSearchTxt',
        ctCls :'pm_search_text_field',
        allowBlank : true,
        width : 170,
        emptyText : _('ID_ENTER_SEARCH_TERM'),
        listeners : {
          specialkey: function(f,e){
            if (e.getKey() == e.ENTER)
              adHocGroupsSearch();
          }
        }
      }), {
        text    :'X',
        ctCls   :'pm_search_x_button',
        handler : function(){
          adHocGroupsStore.setBaseParam( 'search', '');
          adHocGroupsStore.load({params:{start : 0 , limit :  usersPanelLimit}});
          Ext.getCmp('adHocGroupsSearchTxt').setValue('');
        }
      }, {
        text    :TRANSLATIONS.ID_SEARCH,
        handler : adHocGroupsSearch
      }
    ]/*,
    bbar: [new Ext.PagingToolbar({
      pageSize   : usersPanelLimit,
      store      : groupsStore,
      displayInfo: true,
      displayMsg : '{2} Groups',
      emptyMsg   : 'No records found'
    })]*/
  });
  
  _onDropActors = function(ddSource, e, data) {
    
    var records = ddSource.dragData.selections;
    var uids = Array();
    _TAS_UID = _targetTask.id;
    
    if( data.grid.id == 'usersGrid' || data.grid.id == 'groupsGrid') {
      _TU_TYPE = 1;   
    } else { //some groups grid items were dropped
      _TU_TYPE = 2;
    }      
    
    Ext.each(records, function(gridRow){
      if( data.grid.id == 'usersGrid' || data.grid.id == 'adHocUsersGrid') {//some users grid items were dropped    
        _RELATION = 1;   
        uids.push(gridRow.data.USR_UID);
      } else { //some groups grid items were dropped
        _RELATION = 2;
        uids.push(gridRow.data.GRP_UID);
      }
    });

    uids = uids.join(',');

    
    Ext.getCmp('eastPanelCenter').setTitle(_('ID_TASK')+': '+_targetTask.name);
    
    Ext.Ajax.request({
      url: 'processProxy/assignActorsTask',
      success: function(response){
        var result = Ext.util.JSON.decode(response.responseText);
        if( result.success ) {
            PMExt.notify(_('ID_RESPONSABILITIES_ASSIGNMENT'), result.msg);
          
            Ext.getCmp('eastPanel').show();
            Ext.getCmp('usersPanelTabs').getTabEl('usersTaskGrid').style.display = '';
            Ext.getCmp('usersPanelTabs').getTabEl('usersTaskAdHocGrid').style.display = '';
            Ext.getCmp('eastPanelTree').getNodeById(_TAS_UID).select();
            if( _TU_TYPE == 1 ) {
              Ext.getCmp('usersPanelTabs').setActiveTab(1);
              Ext.getCmp('usersTaskGrid').store.reload({params:{tas_uid: _TAS_UID, tu_type: 1}});
            } else {
              Ext.getCmp('usersPanelTabs').setActiveTab(2);
              Ext.getCmp('usersTaskAdHocGrid').store.reload({params:{tas_uid: _TAS_UID, tu_type: 2}});
            }
          
        } else {
          PMExt.error(_('ID_ERROR'), result.msg);
        }
      },
      failure: function(){},
      params: {
        TAS_UID     : _TAS_UID,
        TU_TYPE     : _TU_TYPE,
        TU_RELATION : _RELATION,
        UIDS        : uids
      }
    });
  }


   //last
   usersActorsWin = new Ext.Window({
      layout:'fit',
      padding: '0 10 0 0',
      iconCls: 'ICON_USERS',
      width:260, x:45, y:55,
      height:PMExt.getBrowser().screen.height/2 - 20,
      closeAction:'hide',
      plain: true,
      plugins: [ new Ext.ux.WindowCascade() ],
      offset: 50,
      items: [usersGrid],
      listeners:{
        beforerender:function(){
          usersGrid.store.load();
        }
      }
    });
	 
	  adHocUsersActorsWin = new Ext.Window({
      layout:'fit',
      padding: '0 10 0 0',
      iconCls: 'ss_sprite ss_user_suit',
      width:260,
      height:PMExt.getBrowser().screen.height/2 - 20,
      closeAction:'hide',
      plain: true,
      plugins: [ new Ext.ux.WindowCascade() ],
      offset: 50,
      items: [adHocUsersGrid],
      listeners:{
        beforerender:function(){
          adHocUsersGrid.store.load();
        }
      }
    });
    
    groupsActorsWin = new Ext.Window({
      layout:'fit',
      padding: '0 10 0 0',
      iconCls: 'ICON_GROUPS',
      width:260,
      height:PMExt.getBrowser().screen.height/2 - 20,
      closeAction:'hide',
      plain: true,
      plugins: [ new Ext.ux.WindowCascade() ],
      offset: 50,
      items: [groupsGrid],
      listeners:{
        beforerender:function(){
          groupsGrid.store.load();
        }
      }
    });
		
		adHocGroupsActorsWin = new Ext.Window({
      layout:'fit',
      padding: '0 10 0 0',
      iconCls: 'ss_sprite ss_group_suit',
      width:260,
      height:PMExt.getBrowser().screen.height/2 - 20,
      closeAction:'hide',
      plain: true,
      plugins: [ new Ext.ux.WindowCascade() ],
      offset: 50,
      items: [adHocGroupsGrid],
      listeners:{
        beforerender:function(){
          adHocGroupsGrid.store.load();
        }
      }
    });

});
//end onReady


function removeUsersTask(){

    var usr_uid = Array();
    var tu_relation = Array();
    var rowsSelected = Ext.getCmp('usersTaskGrid').getSelectionModel().getSelections();

    if( rowsSelected.length == 0 ) {
      PMExt.error('', _('ID_NO_SELECTION_WARNING'));
      return false;
    }

    for(i=0; i<rowsSelected.length; i++) {
      usr_uid[i]     = rowsSelected[i].get('USR_UID');
      tu_relation[i] = rowsSelected[i].get('TU_RELATION');
    }
    usr_uid = usr_uid.join(',');
    tu_relation = tu_relation.join(',');

    //PMExt.confirm(_('ID_CONFIRM'), _('ID_REMOVE_USERS_CONFIRM'), function(){
      Ext.Ajax.request({
        url   : 'processProxy/removeActorsTask',
        method: 'POST',
        params: {
          action : 'removeUsersTask',
          USR_UID: usr_uid,
          TU_RELATION: tu_relation,
          TAS_UID: _TAS_UID,
          TU_TYPE: 1
        },
        success: function(response) {
          var result = Ext.util.JSON.decode(response.responseText);
          if( result.success ){
            Ext.getCmp('usersTaskGrid').store.reload();
          } else {
            PMExt.error(_('ID_ERROR'), result.msg);
          }
        }
      });
    //});
  }


function removeUsersAdHocTask(){

  var usr_uid = Array();
  var tu_relation = Array();
  var rowsSelected = Ext.getCmp('usersTaskAdHocGrid').getSelectionModel().getSelections();

  if( rowsSelected.length == 0 ) {
    PMExt.error('', _('ID_NO_SELECTION_WARNING'));
    return false;
  }

  for(i=0; i<rowsSelected.length; i++) {
    usr_uid[i]     = rowsSelected[i].get('USR_UID');
    tu_relation[i] = rowsSelected[i].get('TU_RELATION');
  }
  usr_uid = usr_uid.join(',');
  tu_relation = tu_relation.join(',');

  //PMExt.confirm(_('ID_CONFIRM'), _('ID_REMOVE_USERS_CONFIRM'), function(){
    Ext.Ajax.request({
      url   : 'processProxy/removeActorsTask',
      method: 'POST',
      params: {
        action : 'removeUsersTask',
        USR_UID: usr_uid,
        TU_RELATION: tu_relation,
        TAS_UID: _TAS_UID,
        TU_TYPE: 2
      },
      success: function(response) {
        var result = Ext.util.JSON.decode(response.responseText);
        if( result.success ){
          Ext.getCmp('usersTaskAdHocGrid').store.reload();
        } else {
          PMExt.error(_('ID_ERROR'), result.msg);
        }
      }
    });
  //});
}



function usersSearch()
{
  var search = Ext.getCmp('usersSearchTxt').getValue().trim();
  Ext.getCmp('usersGrid').store.setBaseParam('search', search);
  Ext.getCmp('usersGrid').store.load({params:{search: search, start : 0 , limit : usersPanelLimit }});
}

function groupsSearch()
{
  var search = Ext.getCmp('groupsSearchTxt').getValue().trim();
  Ext.getCmp('groupsGrid').store.setBaseParam('search', search);
  Ext.getCmp('groupsGrid').store.load({params:{search: search, start : 0 , limit : usersPanelLimit }});
}

function adHocUsersSearch()
{
  var search = Ext.getCmp('adHocUsersSearchTxt').getValue().trim();
  Ext.getCmp('adHocUsersGrid').store.setBaseParam('search', search);
  Ext.getCmp('adHocUsersGrid').store.load({params:{search: search, start : 0 , limit : usersPanelLimit }});
}

function adHocGroupsSearch()
{
  var search = Ext.getCmp('adHocGroupsSearchTxt').getValue().trim();
  Ext.getCmp('adHocGroupsGrid').store.setBaseParam('search', search);
  Ext.getCmp('adHocGroupsGrid').store.load({params:{search: search, start : 0 , limit : usersPanelLimit }});
}


Ext.namespace('Ext.ux.plugins');
Ext.ux.WindowCascade = Ext.extend(Object, {
    constructor: function(offset) {
    	this.offset = offset;
    },

    init: function(client) {
    	client.beforeShow = Ext.Window.prototype.beforeShow.createInterceptor(this.beforeShow);
    },

    beforeShow: function() {
    	if ((this.x == undefined) && (this.y == undefined)) {
    	    var prev;
    	    this.manager.each(function(w) {
    	        if (w == this) {
    	            if (prev) {
    	            	var o = this.offset || 20;
    	                var p = prev.getPosition();
    	                this.x = p[0] + o;
    	                this.y = p[1] + o;
    	            }
    	            return false;
    	        }
    	        if (w.isVisible()) prev = w;
    	    }, this);
    	}
    }
});
