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
var onDynaformsContextMenu;
var usersTaskGridContextMenu;
var usersTaskAdHocStore;
var usersTaskAdHocGrid;
var onUsersTaskAdHocGridContextMenu;
var usersTaskAdHocGridContextMenu;
var mainMenu;

Ext.onReady(function(){
  
toolbarPanel = {
  title: 'Toolbar',
  border: false,
  //iconCls: 'nav',
  layout:'table',
  defaultType: 'button',
  cls: 'btn-panel',
  layoutConfig: {
    columns: 2
  },
  defaults: {
    autoEl: {tag: 'h3', style:"padding:15px 0 3px;"},
    scale: 'large'
  },

  items : [{
      iconCls: 'button_large_ext ss_sprite ss_bpmn_task',//icon: '/skins/ext/images/gray/shapes/pallete/task.png',
      id:"x-shapes-task"
    },{
      iconCls: 'button_large_ext ss_sprite ss_bpmn_startevent',//icon: '/skins/ext/images/gray/shapes/pallete/startevent.png',
      id:"x-shapes-startEvent"
    },{
      iconCls: 'button_large_ext ss_sprite ss_bpmn_interevent',//icon: '/skins/ext/images/gray/shapes/pallete/interevent.png',
      id:"x-shapes-interEvent"
    },{
      iconCls: 'button_large_ext ss_sprite ss_bpmn_endevent',//icon: '/skins/ext/images/gray/shapes/pallete/endevent.png',
      id:"x-shapes-endEvent"
    },{
      iconCls: 'button_large_ext ss_sprite ss_bpmn_gateway',//icon: '/skins/ext/images/gray/shapes/pallete/gateway.png',
      id:"x-shapes-gateways"
    },{
      iconCls: 'button_large_ext ss_sprite ss_bpmn_annotation',//icon: '/skins/ext/images/gray/shapes/pallete/annotation.png',
      id:"x-shapes-annotation"
    }
  ]
};

actorsPanel = {
  title: 'Actors',
  html: '',
  iconCls: 'ICON_USERS',
  border: false
}
        

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
      items: [{
        text: _('ID_SWITCH_EDITOR'),
        iconCls: 'ss_sprite ss_arrow_switch',
        handler: function() {
          if(typeof pro_uid !== 'undefined') {
            location.href = 'processes/processes_Map?PRO_UID=' +pro_uid+ '&rand=' +Math.random()
          }
        }
      }]
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
  '->',
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
  usersTaskGrid.addListener('rowcontextmenu', onDynaformsContextMenu,this);

  //by default the right click is not selecting the grid row over the mouse
  //we need to set this four lines
  usersTaskGrid.on('rowcontextmenu', function (grid, rowIndex, evt) {
    var sm = grid.getSelectionModel();
    sm.selectRow(rowIndex, sm.isSelected(rowIndex));
  }, this);

  //prevent default
  usersTaskGrid.on('contextmenu', function (evt) {
      evt.preventDefault();
  }, this);

  onDynaformsContextMenu = function(grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    usersTaskGridContextMenu.showAt([coords[0], coords[1]]);
  }

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
  usersTaskAdHocGrid.addListener('rowcontextmenu', onUsersTaskAdHocGridContextMenu,this);

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

  onUsersTaskAdHocGridContextMenu = function(grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    usersTaskAdHocGridContextMenu.showAt([coords[0], coords[1]]);
  }

  usersTaskAdHocGridContextMenu = new Ext.menu.Menu({
    id: 'messagAdHocGrideContextMenu',
    items: [{
        text: _('ID_REMOVE'),
        iconCls: 'button_menu_ext ss_sprite  ss_delete',
        handler: removeUsersAdHocTask
      }
    ]
  });





})
//end onReady()


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
    if( search == '' ) {
      PMExt.info(_('ID_INFO'), _('ID_ENTER_SEARCH_TERM'));
      return;
    }
    Ext.getCmp('usersGrid').store.setBaseParam('search', search);
    Ext.getCmp('usersGrid').store.load({params:{search: search, start : 0 , limit : usersPanelLimit }});
  }

  function groupsSearch()
  {
    var search = Ext.getCmp('groupsSearchTxt').getValue().trim();
    if( search == '' ) {
      PMExt.info(_('ID_INFO'), _('ID_ENTER_SEARCH_TERM'));
      return;
    }
    Ext.getCmp('groupsGrid').store.setBaseParam('search', search);
    Ext.getCmp('groupsGrid').store.load({params:{search: search, start : 0 , limit : usersPanelLimit }});
  }