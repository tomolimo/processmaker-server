var _TAS_UID;
var processObj;
//var ProcessMapObj;

Ext.onReady ( function() {
  new Ext.KeyMap(document, {
    key: Ext.EventObject.F5,
    fn: function(keycode, e) {
      //e.stopEvent();
    }
  });

  processObj = new ProcessOptions();
  //ProcessMapObj = new ProcessMapContext();

  var west= {
    id         : 'palette',
    title      : 'Palette',
    region     : 'west',
    width      : 65,
    border     : false,
    autoScroll : true,
    collapsible :true,
    split       :true,
    collapseMode:'mini',
    hideCollapseTool: false,

    items:{
      html:''
    }
  };

  var usersTaskStore = new Ext.data.GroupingStore( {
    autoLoad: false,
    url: '../processes/ajaxListener',
    reader : new Ext.data.JsonReader({
      totalProperty: 'totalCount',
      root: 'data',
      fields : [
        {name : 'USR_UID'},
        {name : 'NAME'},
        {name : 'TU_RELATION'}
      ]
    }),
    baseParams: {
      action: 'getUsersTask',
      TAS_UID: '4619962094d5d499f746ca7075681567'
    },
    groupField: 'TU_RELATION'
  });

  var usersTaskGrid = new Ext.grid.GridPanel({
    id       : 'usersTaskGrid',
    title    : 'Users & Groups',
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
          return _FNF(r.data.USR_USERNAME, r.data.USR_FIRSTNAME, r.data.USR_LASTNAME);
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

  usersTaskGrid.on('keypress', function (e) {
    alert(e.getKey());
  }, this);
  

  function onDynaformsContextMenu(grid, rowIndex, e) {
    e.stopEvent();
    var coords = e.getXY();
    usersTaskGridContextMenu.showAt([coords[0], coords[1]]);
  }

  var usersTaskGridContextMenu = new Ext.menu.Menu({
    id: 'messageContextMenu',
    items: [{
        text: _('ID_REMOVE'),
        iconCls: 'button_menu_ext ss_sprite  ss_delete',
        handler: removeUsersTask
      }
    ]
  });

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
        url   : '../processes/ajaxListener',
        method: 'POST',
        params: {
          action : 'removeUsersTask',
          USR_UID: usr_uid,
          TU_RELATION: tu_relation,
          TAS_UID: _TAS_UID
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
 
  var eastPanelTree = new Ext.tree.TreePanel({
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
      dataUrl : '../processes/ajaxListener',
      baseParams : {
        action : 'getProcessDetail',
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
      Ext.getCmp('usersTaskGrid').store.reload({params: {action:'getUsersTask', TAS_UID: _TAS_UID}});
    } else {
      Ext.getCmp('usersPanelTabs').setActiveTab(0);
      Ext.getCmp('usersPanelTabs').getTabEl('usersTaskGrid').style.display = 'none';
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

  var ActiveProperty = new Ext.form.Checkbox({
    name        : 'active',
    fieldLabel : 'Active',
    checked    : true,
    inputValue : '1'
  });

  var comboCategory = new Ext.form.ComboBox({
    fieldLabel    : 'Category',
    name        : 'category',
    allowBlank     : true,
    store        : new Ext.data.Store( {
      //autoLoad: true,  //autoload the data
      proxy : new Ext.data.HttpProxy( {
        url : '../processes/ajaxListener',
        method : 'POST'
      }),
      baseParams : {
        action : 'getCategoriesList'
      },
      reader : new Ext.data.JsonReader( {
        root : 'rows',
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

  var comboCalendar = new Ext.form.ComboBox({
    fieldLabel    : 'Calendar',
    name        : 'calendar',
    allowBlank     : true,
    store        : new Ext.data.Store( {
      //autoLoad: true,  //autoload the data
      proxy : new Ext.data.HttpProxy({ url: '../processes/ajaxListener'}),
      baseParams : {action: 'getCaledarList'},
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
      proxy : new Ext.data.HttpProxy({ url: '../processes/ajaxListener'}),
      baseParams : {action: 'getPMVariables', PRO_UID: pro_uid},
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

  
  var propertiesGrid = new Ext.grid.PropertyGrid({
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
    
    Ext.Ajax.request({
      url: '../processes/ajaxListener',
      params: {
        action : 'saveProperties',
        UID: node.attributes.id,
        type: node.attributes.type,
        property: r.record.data.name,
        value: r.value
      },
      success: function(response) {
        //
      },
      failure: function(){
        Ext.Msg.alert ('Failure');
      }
    });
    
    r.record.commit();
  }, this );
  

  var propertyStore = new Ext.data.JsonStore({
    id: 'propertyStore',
    autoLoad: true,  //autoload the data
    url: '../processes/ajaxListener',
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
      action : 'getProperties',
      UID    : pro_uid,
      type   : 'process'
    }
  });

  var east = new Ext.Panel({
    id         : 'eastPanel',
    title      : '',
    region     : 'east',
    width      : 280,
    title      : '',
    //autoScroll : true,
    layout:'border',
    collapsible :true,
    split       :true,
    //collapseMode:'mini',
    //hideCollapseTool: false,
    items:[
      eastPanelTree, 
      {
        id: 'eastPanelCenter',
        xtype: 'panel',
        title: _('ID_PROCESS')+': '+pro_title,
        region: 'center',
        layout: 'fit',
        items:[
          new Ext.TabPanel({
            id    : 'usersPanelTabs',
            title : 'sdd',
            border: true, // already wrapped so don't add another border
            activeTab   : 0, // second tab initially active
            tabPosition : 'top',
            split  : true,
            collapseMode:'mini',
            //height : 318,
            items  : [
              propertiesGrid,
              usersTaskGrid
            ]
          })
        ]
      }
    ]
  });
  
  var north = {
    xtype	:	"panel",
    initialSize: 60,
    split:false,
    titlebar: false,
    collapsible: false,
    animate: false,
    region	:	"north"
  };
  
  var south = {
    xtype	:	"panel",
    initialSize: 120,
    height: 100,
    split:true,
    titlebar: false,
    collapsible: true,
    autoScroll:true,
    animate: true,
    region	:	"south",
    items: {
        region: 'center',
        xtype: 'tabpanel',
        items: [{
            title: 'Properties',
            html: 'Properties'
        },
        {
            title: 'Debug Console',
            html: 'Debug Console'
        }]
    }
  };

  var center= {
    region: 'center',
    width:100,
    height:2000,
    xtype	:	"iframepanel",
    title   : "BPMN Processmap - " + pro_title,

    frameConfig:{name:'designerFrame', id:'designerFrame'},
    defaultSrc : 'designer?PRO_UID=' + pro_uid,
    loadMask:{msg:'Loading...'},
    bodyStyle:{height: (PMExt.getBrowser().screen.height-55) + 'px'},
    width:'1024px'
  };

  var main = new Ext.Panel({
    renderTo  : "center1",
    region    : "center",
    layout    : "border",
    autoScroll: true,
    height    : 1000,
    width     : 1300,    
    items   : [north, center, east],
    tbar: [
      {
        text: 'Save',
        cls: 'x-btn-text-icon',
        iconCls: 'button_menu_ext ss_sprite ss_disk',
        handler: function() {
          document.getElementById('designerFrame').contentWindow.saveProcess();
        }
      }, {
          text:'Save as',
          iconCls: 'button_menu_ext ss_sprite ss_disk_multiple'
      }, {
        xtype: 'tbseparator'
      }, {
        text:'Undo',
        iconCls: 'button_menu_ext ss_sprite ss_arrow_undo',
        handler: function() {
          document.getElementById('designerFrame').contentWindow.workflow.getCommandStack().undo();
        }
      }, {
        text:'Redo',
        iconCls: 'button_menu_ext ss_sprite ss_arrow_redo',
        handler: function() {
          document.getElementById('designerFrame').contentWindow.workflow.getCommandStack().redo();
        }
      },{
        //xtype: 'tbsplit',
        text:'Zoom',
        iconCls: 'button_menu_ext ss_sprite ss_zoom',
         menu: new Ext.menu.Menu({
          items: [{
              text    : '25%',
               handler: function() {
                   document.getElementById('designerFrame').contentWindow.workflow.zoom('25');
               }
            },{
              text    : '50%',
               handler: function() {
                   document.getElementById('designerFrame').contentWindow.workflow.zoom('50');
               }
            },{
              text    : '75%',
               handler: function() {
                   document.getElementById('designerFrame').contentWindow.workflow.zoom('75');
               }
            },{
              text    : '100%',
               handler: function() {
                   document.getElementById('designerFrame').contentWindow.workflow.zoom('100');
               }
            },{
              text    : '125%',
               handler: function() {
                   document.getElementById('designerFrame').contentWindow.workflow.zoom('125');
               }
            },{
              text    : '150%',
               handler: function() {
                   document.getElementById('designerFrame').contentWindow.workflow.zoom('150');
               }
            },{
              text    : '200%',
               handler: function() {
                   document.getElementById('designerFrame').contentWindow.workflow.zoom('200');
               }
            }
          ]
        })
      }, {
        xtype: 'tbseparator'
      }, {
        //xtype: 'tbsplit',
        iconCls: 'button_menu_ext ss_sprite ss_application',
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
            },
/*
            {
              text: 'Report Table',
              iconCls: 'button_menu_ext ss_sprite ss_table',
              handler : function() {
                processObj.addReportTable();
              }
            },
            {
              text: 'Database Connection',
              iconCls: 'button_menu_ext ss_sprite ss_database_connect',
              handler : function() {
                processObj.dbConnection();
              }
            }
*/
          ]
        })

      }, {
        text: 'Actors',
        iconCls: 'ICON_USERS',
        handler: function(){
          document.getElementById('designerFrame').contentWindow.usersPanel.show()
        }

      }, {
        xtype: 'tbfill'
      }, {
        text: _('ID_SWITCH_EDITOR'),
        iconCls: 'button_menu_ext ss_sprite ss_pencil',
        handler: function() {
          if(typeof pro_uid !== 'undefined') {
            location.href = '../processes/processes_Map?PRO_UID=' +pro_uid+ '&rand=' +Math.random()
          }
        }
      }
    ]
  });


  var viewport = new Ext.Viewport({
    id:'viewPort1'
    ,layout:'border'
    ,border:false,
    items:[main]
  });
  Ext.getCmp('usersPanelTabs').getTabEl('usersTaskGrid').style.display = 'none';
  //Ext.getCmp('eastPanel').hide();
  //Ext.getCmp('eastPanel').ownerCt.doLayout();
  
});
