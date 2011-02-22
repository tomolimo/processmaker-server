new Ext.KeyMap(document, {
  key: Ext.EventObject.F5,
  fn: function(keycode, e) {
    if (Ext.isIE)
      e.browserEvent.keyCode = 8;
  //e.stopEvent();
  }
});


var saveProcess;
var usersPanel;

Ext.onReady ( function() {
  workflow  = new MyWorkflow("paintarea");
  workflow.setEnableSmoothFigureHandling(false);
  workflow.scrollArea.width = 2000;
  //For Undo and Redo Options
  // workflow.getCommandStack().addCommandStackEventListener(new commandListener());

  if(typeof pro_uid !== 'undefined') {
    Ext.Ajax.request({
      url: 'openProcess.php?PRO_UID=' + pro_uid,
      success: function(response) {
        shapesData =  createShapes(response.responseText);
        createConnection(shapesData);
        
        /**
         * erik: Setting Drop targets from users & groups grids to assignment to tasks
         * for all existing tasks
         */
        var dropEls = Ext.get('paintarea').query('.x-task');
        for(var i = 0; i < dropEls.length; i++)
          new Ext.dd.DropTarget(dropEls[i], {ddGroup:'task-assignment', notifyDrop  : Ext.getCmp('usersPanel')._onDrop});
        
      },
      failure: function(){
        Ext.Msg.alert ('Failure');
      }
    });
  }

  

  /**********************************************************************************
  *
  * Do the Ext (Yahoo UI) Stuff
  *
  **********************************************************************************/
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
  
  var east= {
    id         : 'eastPanel',
    title      : '',
    region     : 'east',
    width      : 150,
    border     : false,
    autoScroll : true,
    collapsible :true,
    split       :true,
    collapseMode:'mini',
    hideCollapseTool: false,

    items:{
      html:'east panel'
    }
  };

  var north= {
    xtype	:	"panel",
    initialSize: 60,
    split:false,
    titlebar: false,
    collapsible: false,
    animate: false,
    region	:	"north"
  };
  
  var south= {
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
    id: 'centerRegion',
    width:100,
    height:2000,
    xtype	:	"panel",
    //autoScroll:true,
    fitToFrame:true,
    region	:	"center"
  };

  var processObj = new ProcessOptions();

  var main = new Ext.Panel({
    renderTo  : "center1",
    region    : "center",
    layout    : "border",
    autoScroll: true,
    height    : 1000,
    width     : PMExt.getBrowser().screen.width,
    //items   : [west, north, center]
    items   : [north, center]
  });
  

  var toolbarPanel = new Ext.Panel({
    layout:'table',
    defaultType: 'button',
    baseCls: 'x-plain',
    cls: 'btn-panel',
    //menu: undefined,
    split: false,

    layoutConfig: {
        columns: 7
    },
    defaults: {
      autoEl: {tag: 'h3', html: 's', style:"padding:15px 0 3px;"},
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
  });
  
  var designerToolbarHeight = 60;
  var designerToolbarWidth  = 265;
  var designerToolbar = new Ext.Window({
    id: 'designerToolbar',
    //title: 'Toolbar',
    headerAsText: true,
    width: designerToolbarWidth,
    height: designerToolbarHeight,
    x: (PMExt.getBrowser().screen.width - designerToolbarWidth) - 5,
    y: 0,
    minimizable: false,
    maximizable: false,
    closable: false,
    resizable: false,
    floating: true,
    frame:true,
    shadow:false,
    border:true,
    
    shim: true,
    plugin: new Ext.ux.WindowAlwaysOnTop,
    items: [toolbarPanel]
    /*html: '<div id="x-shapes">\n\
      <p id="x-shapes-task" class="toolbar-item"><img src= "/skins/ext/images/gray/shapes/pallete/task.png"/></p>\n\
      <p id="x-shapes-startEvent" class="toolbar-item"><img src= "/skins/ext/images/gray/shapes/pallete/startevent.png"/></p>\n\
      <p id="x-shapes-interEvent" class="toolbar-item"><img src= "/skins/ext/images/gray/shapes/pallete/interevent.png"/></p>\n\
      <p id="x-shapes-endEvent" title="" class="toolbar-item"><img src= "/skins/ext/images/gray/shapes/pallete/endevent.png"/></p>\n\
      <p id="x-shapes-gateways" title="" class="toolbar-item"><img src= "/skins/ext/images/gray/shapes/pallete/gateway.png"/><br/></p>\n\
      <p id="x-shapes-annotation" title="" class="toolbar-item"><img src= "/skins/ext/images/gray/shapes/pallete/annotation.png"/></p>\n\
      <!--<p id="x-shapes-group" title="Group"><img src= "/skins/ext/images/gray/shapes/pallete/group.png"/></p>\n\
      <p id="x-shapes-dataobject" title="Data Object" class="toolbar-item"><img src= "/skins/ext/images/gray/shapes/pallete/dataobject.png"/></p>\n\
      <p id="x-shapes-pool" title="Pool" class="toolbar-item"><img src= "/skins/ext/images/gray/shapes/pallete/pool.png"/></p>\n\
      <p id="x-shapes-lane" title="Lane" class="toolbar-item"><img src= "/skins/ext/images/gray/shapes/pallete/lane.png"/></p>\n\
      <p id="x-shapes-milestone" title="Milestone" class="toolbar-item"><img src= "/skins/ext/images/gray/shapes/pallete/milestone.png"/></p>-->\n\
    </div>'*/
  });
  designerToolbar.on('minimize',function(w){
    //console.debug('minimizing...');
    if( w.collapsed )
      designerToolbar.expand();
    else
      designerToolbar.collapse(); //collapse the window
    
  });
  
  designerToolbar.show();

  // custom variables
  designerToolbar._posRelToView = designerToolbar.getPosition(true);
  designerToolbar._scrollPosTimer = false;
  designerToolbar._moveBlocker = false;
  designerToolbar.show();
  var divScroll = document.body;

  // save relative pos to view when moving (for scrolling event below)
  // also, manually do a constrain, else the win would be lost if moved outside the view
  designerToolbar.on('move', function() {
    // lock function (because we move the window again inside)
    if (designerToolbar._moveBlocker) return;
    designerToolbar._moveBlocker = true;

    var winPos = designerToolbar.getPosition(true);
    designerToolbar._posRelToView = [winPos[0] - divScroll.scrollLeft, winPos[1] - divScroll.scrollTop];

    // manually do what constrain should do if it worked as assumed
    var layersize = [Ext.get(divScroll).getWidth(), Ext.get(divScroll).getHeight()];
    var windowsize = [designerToolbar.getSize().width, designerToolbar.getSize().height];
    // assumed width of the scrollbar (true for windows 7) plus some padding to be sure
    var scrollSize = 17 + 5;
    if (designerToolbar._posRelToView[0] < 0) { // too far left
        designerToolbar.setPosition(divScroll.scrollLeft, winPos[1]);
        designerToolbar._posRelToView = [0, designerToolbar._posRelToView[1]];
    } else if (designerToolbar._posRelToView[0] >= (layersize[0] - windowsize[0])) { // too far right
        designerToolbar.setPosition(((divScroll.scrollLeft + layersize[0]) - windowsize[0] - scrollSize), winPos[1]);
        designerToolbar._posRelToView = [(layersize[0] - windowsize[0] - scrollSize), designerToolbar._posRelToView[1]];
    }

    winPos = designerToolbar.getPosition(true); // update pos
    if (designerToolbar._posRelToView[1] < 0) { // too high up
        designerToolbar.setPosition(winPos[0], divScroll.scrollTop);
        designerToolbar._posRelToView = [designerToolbar._posRelToView[0], 0];
    } else if (designerToolbar._posRelToView[1] >= layersize[1]) { // too low
        designerToolbar.setPosition(winPos[0], ((divScroll.scrollTop + layersize[1]) - windowsize[1] - scrollSize));
        designerToolbar._posRelToView = [designerToolbar._posRelToView[0], (layersize[1] - windowsize[1] - scrollSize)];
    }

    // release function
    designerToolbar._moveBlocker = false;
  });

  ////
  usersPanelStart = 0;
  usersPanelLimit = 11;
  
  var usersStore = new Ext.data.Store( {
    autoLoad: true,
    proxy : new Ext.data.HttpProxy({
      url: '../processes/ajaxListener?action=getUsers&start='+usersPanelStart+'&limit='+usersPanelLimit
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
    })
  });
  
  var usersGrid = new Ext.grid.GridPanel({
    id: 'usersGrid',
    title : 'Users',
    stateful : true,
    stateId : 'usersGrid',
    enableColumnResize: true,
    enableHdMenu: true,
    //frame:false,
    //columnLines: true,
    ddGroup        : 'task-assignment',
    enableDragDrop: true,
    viewConfig: {
      forceFit:true
    },

    cm: new Ext.grid.ColumnModel({
      defaults: {
          width: 200,
          sortable: true
      },
      columns: [
        {header: 'USR_UID', id:'USR_UID', dataIndex: 'USR_UID', hidden:true, hideable:false},
        {header: 'USER', dataIndex: 'USER', width: 300, renderer:function(v,p,r){
          return v; //String.format("<font color='green'>{0}</font>", v);
        }}
      ]
    }),
    store: usersStore,
    listeners: {
      render: function(){
        this.loadMask = new Ext.LoadMask(this.body, {msg:_('ID_LOADING')});
      }
    },
    tbar:[
      //'->',
      new Ext.form.TextField ({
        id: 'usersSearchTxt',
        ctCls:'pm_search_text_field',
        allowBlank: true,
        width: 230,
        emptyText: _('ID_ENTER_SEARCH_TERM'),//'enter search term',
        listeners: {
          specialkey: function(f,e){
            if (e.getKey() == e.ENTER)
              usersSearch();
          }
        }
      }),{
        text:'X',
        ctCls:'pm_search_x_button',
        handler: function(){
          usersStore.setBaseParam( 'search', '');
          usersStore.load({params:{start : 0 , limit :  usersPanelLimit}});
          Ext.getCmp('usersSearchTxt').setValue('');
        }
      },{
        text:TRANSLATIONS.ID_SEARCH,
        handler: usersSearch
      }
    ],
    bbar: [new Ext.PagingToolbar({
      pageSize: usersPanelLimit,
      store: usersStore,
      displayInfo: true,
      displayMsg: '{0} - {1} of {2}',
      emptyMsg: ""
    })]
  });


  var groupsStore = new Ext.data.Store( {
    autoLoad: true,
    proxy : new Ext.data.HttpProxy({
      url: '../processes/ajaxListener?action=getGroups&start='+usersPanelStart+'&limit='+usersPanelLimit
    }),
    reader : new Ext.data.JsonReader( {
      totalProperty: 'totalCount',
      root: 'data',
      fields : [
        {name : 'GRP_UID'},
        {name : 'CON_VALUE'}
      ]
    })
  });

  var groupsGrid = new Ext.grid.GridPanel({
    id: 'groupsGrid',
    title : 'Groups',
    stateful : true,
    stateId : 'groupsGrid',
    //enableColumnResize: true,
    //enableHdMenu: true,
    frame:false,
    //columnLines: true,
    ddGroup        : 'task-assignment',
    height: 200,
    enableDragDrop: true,
    viewConfig: {
      forceFit:true
    },

    cm: new Ext.grid.ColumnModel({
      defaults: {
          width: 200,
          sortable: true
      },
      columns: [
        {header: '', id:'GRP_UID', dataIndex: 'GRP_UID', hidden:true, hideable:false},
        {header: 'Group', dataIndex: 'CON_VALUE', width: 300, renderer:function(v,p,r){
          return v; //String.format("<font color='green'>{0}</font>", v);
        }}
      ]
    }),
    store: groupsStore,
    listeners: {
      render: function(){
        this.loadMask = new Ext.LoadMask(this.body, {msg:_('ID_LOADING')});
      }
    },
    tbar:[
      //'->',
      new Ext.form.TextField ({
        id: 'groupsSearchTxt',
        ctCls:'pm_search_text_field',
        allowBlank: true,
        width: 230,
        emptyText: _('ID_ENTER_SEARCH_TERM'),//'enter search term',
        listeners: {
          specialkey: function(f,e){
            if (e.getKey() == e.ENTER)
              groupsSearch();
          }
        }
      }),{
        text:'X',
        ctCls:'pm_search_x_button',
        handler: function(){
          groupsStore.setBaseParam( 'search', '');
          groupsStore.load({params:{start : 0 , limit :  usersPanelLimit}});
          Ext.getCmp('groupsSearchTxt').setValue('');
        }
      },{
        text:TRANSLATIONS.ID_SEARCH,
        handler: groupsSearch
      }
    ],
    bbar: [new Ext.PagingToolbar({
      pageSize: usersPanelLimit,
      store: groupsStore,
      displayInfo: true,
      displayMsg: '{0} - {1} of {2}',
      emptyMsg: ""
    })]
  });
  

  usersPanel = new Ext.Window({
    id: 'usersPanel',
    title: '<span style="font-size:10px; font-weight:bold; align:center;">&nbsp;Actors</span>',
    headerAsText: true,
    collapsed : true,
    width: 302,
    height:380,
    //x: (PMExt.getBrowser().screen.width - designerToolbarWidth) - 5,
    //y: designerToolbarHeight + 2,
    x: 0,
    y: 0,
    minimizable: false,
    maximizable: false,
    closable: false,
    resizable: false,
    floating: true,
    shadow:false,
    border:false,
    //html: 'userslist'
    items:[
      new Ext.TabPanel({
        border: true, // already wrapped so don't add another border
        activeTab: 0, // second tab initially active
        tabPosition: 'top',
        //region:'north',
        split: true,
        height:350,
        items: [
          usersGrid,
          groupsGrid
        ]
      })
    ],
    tools: [
      /*{
        id:'help',
        qtip: 'Get Help',
        handler: function(event, toolEl, panel){
            // whatever
        }
      },*/ {
        id: 'toggle',
        handler: function(w) {
          if( Ext.getCmp('usersPanel').collapsed )
            Ext.getCmp('usersPanel').expand();
          else
            Ext.getCmp('usersPanel').collapse();
        }
      },{
        id: 'close',
        handler: function() {
          Ext.getCmp('usersPanel').hide()
        }
      }
    ]
  });
  
  usersPanel.on('minimize',function(w){
    if( Ext.getCmp('usersPanel').collapsed )
      Ext.getCmp('usersPanel').expand();
    else
      Ext.getCmp('usersPanel').collapse();
  });
  
  // custom variables
  usersPanel._targetTask = null;
  usersPanel._onDrop = function(ddSource, e, data) {
    alert('tas_uid: ' + Ext.getCmp('usersPanel')._targetTask);

    var records = ddSource.dragData.selections;
    Ext.each(records, function(gridRow){
      alert('usr_uid ->'+gridRow.data.USR_UID);
    });

  }
  
  usersPanel.on('beforeshow', function(e) {
    usersPanel._posRelToView = usersPanel.getPosition(true);
    usersPanel._scrollPosTimer = false;
    usersPanel._moveBlocker = false;
    usersPanel.setPosition(0, divScroll.scrollTop);
  }, this);
  

  //usersPanel.show();
  var divScroll = document.body;

  // save relative pos to view when moving (for scrolling event below)
  // also, manually do a constrain, else the win would be lost if moved outside the view
  usersPanel.on('move', function() {
    // lock function (because we move the window again inside)
    if (usersPanel._moveBlocker) return;
    usersPanel._moveBlocker = true;

    var winPos = usersPanel.getPosition(true);
    usersPanel._posRelToView = [winPos[0] - divScroll.scrollLeft, winPos[1] - divScroll.scrollTop];

    // manually do what constrain should do if it worked as assumed
    var layersize = [Ext.get(divScroll).getWidth(), Ext.get(divScroll).getHeight()];
    var windowsize = [usersPanel.getSize().width, usersPanel.getSize().height];
    // assumed width of the scrollbar (true for windows 7) plus some padding to be sure
    var scrollSize = 17 + 5;
    if (usersPanel._posRelToView[0] < 0) { // too far left
        usersPanel.setPosition(divScroll.scrollLeft, winPos[1]);
        usersPanel._posRelToView = [0, usersPanel._posRelToView[1]];
    } else if (usersPanel._posRelToView[0] >= (layersize[0] - windowsize[0])) { // too far right
        usersPanel.setPosition(((divScroll.scrollLeft + layersize[0]) - windowsize[0] - scrollSize), winPos[1]);
        usersPanel._posRelToView = [(layersize[0] - windowsize[0] - scrollSize), usersPanel._posRelToView[1]];
    }

    winPos = usersPanel.getPosition(true); // update pos
    if (usersPanel._posRelToView[1] < 0) { // too high up
        usersPanel.setPosition(winPos[0], divScroll.scrollTop);
        usersPanel._posRelToView = [usersPanel._posRelToView[0], 0];
    } else if (usersPanel._posRelToView[1] >= layersize[1]) { // too low
        usersPanel.setPosition(winPos[0], ((divScroll.scrollTop + layersize[1]) - windowsize[1] - scrollSize));
        usersPanel._posRelToView = [usersPanel._posRelToView[0], (layersize[1] - windowsize[1] - scrollSize)];
    }

    // release function
    usersPanel._moveBlocker = false;
  });

  Ext.fly(document).on("scroll", function(){
    if (designerToolbar._scrollPosTimer) {
      clearTimeout(designerToolbar._scrollPosTimer);
    }
    designerToolbar._scrollPosTimer = setTimeout(function() {
      designerToolbar.setPosition(designerToolbar._posRelToView[0] + divScroll.scrollLeft, designerToolbar._posRelToView[1] + divScroll.scrollTop);
    }, 100);

    if( usersPanel.isVisible() ) {
      if (usersPanel._scrollPosTimer) {
        clearTimeout(usersPanel._scrollPosTimer);
      }
      usersPanel._scrollPosTimer = setTimeout(function() {
        usersPanel.setPosition(usersPanel._posRelToView[0] + divScroll.scrollLeft, usersPanel._posRelToView[1] + divScroll.scrollTop);
      }, 100);
    }
  });

  new Ext.ToolTip({
      target: 'x-shapes-task',
      title: 'Task',
      trackMouse: true,
      anchor: 'top',
      html: ''
  });
  new Ext.ToolTip({
      target: 'x-shapes-startEvent',
      title: '  Start Event',
      trackMouse: true,
      anchor: 'top',
      html: ''
  });
  new Ext.ToolTip({
      target: 'x-shapes-interEvent',
      title: 'Intermediate Event',
      trackMouse: true,
      anchor: 'top',
      html: ''
  });
  new Ext.ToolTip({
      target: 'x-shapes-endEvent',
      title: 'End Event',
      trackMouse: true,
      anchor: 'top',
      html: ''
  });
  new Ext.ToolTip({
      target: 'x-shapes-gateways',
      title: 'Gateway',
      trackMouse: true,
      anchor: 'top',
      html: ''
  });
  new Ext.ToolTip({
      target: 'x-shapes-annotation',
      title: 'Annotation',
      anchor: 'left',
      trackMouse: true,
      html: ''
  });


  //Get main into workflow object
  workflow.main = main;
  //items[3]=>'center region'
  var centerRegionId = Ext.getCmp('centerRegion').body.id;
  canvas = Ext.get(centerRegionId);

  //Context Menu of ProcessMap
  ProcessMapObj = new ProcessMapContext();
  contextCanvasMenu = new Ext.menu.Menu({
      items: [{
          text: 'Edit Process',
          handler: ProcessMapObj.editProcess,
          iconCls: 'button_menu_ext ss_sprite ss_page_white_edit',
          scope: this
      }, {
          text: 'Export Process',
          handler: ProcessMapObj.exportProcess,
          iconCls: 'button_menu_ext ss_sprite ss_script_go',
          scope: this
      }, {
          text: 'Add Task',
          handler: ProcessMapObj.addTask,
          iconCls: 'button_menu_ext ss_sprite ss_layout_add',
          scope: this
      }, {
          text: 'Add Subprocess',
          handler: workflow.subProcess,
          iconCls: 'button_menu_ext ss_sprite ss_layout_link',
          scope: this
      },/* {
          text: 'Horizontal Line',
          handler: ProcessMapObj.horiLine,
          scope: this
      }, {
          text: 'Vertical Line',
          handler: ProcessMapObj.vertiLine,
          scope: this
      }, {
          text: 'Delete All Lines',
          handler: ProcessMapObj.delLines,
          scope: this
      }, */{
          text: 'Process Permission',
          iconCls: 'button_menu_ext ss_sprite ss_application_key',
          handler: ProcessMapObj.processPermission,
          scope: this
      },{
          text: 'Process Supervisor',
          iconCls: 'button_menu_ext ss_sprite ss_group',
          menu: {        // <-- submenu by nested config object
                  items: [
                      // stick any markup in a menu
                      {
                          text: 'Supervisors',
                          iconCls: 'button_menu_ext ss_sprite ss_group',
                          handler: ProcessMapObj.processSupervisors
                      },
                      {
                          text: 'DynaForm',
                          iconCls: 'button_menu_ext ss_sprite ss_application_form',
                          handler: ProcessMapObj.processDynaform
                      },
                      {
                          text: 'Input Documents',
                          iconCls: 'button_menu_ext ss_sprite ss_page_white_put',
                          handler: ProcessMapObj.processIODoc
                      }
                  ]
              }
      },{
          text: 'Case Tracker',
          iconCls: 'button_menu_ext ss_sprite ss_exclamation',

          menu: {        // <-- submenu by nested config object
                  items: [
                      // stick any markup in a menu
                      {
                          text: 'Properties',
                          iconCls: 'button_menu_ext ss_sprite ss_exclamation',
                          handler: ProcessMapObj.caseTrackerProperties,
                          scope:this
                      },
                      {
                          text: 'Objects',
                          iconCls: 'button_menu_ext ss_sprite ss_exclamation',
                          handler: ProcessMapObj.caseTrackerObjects,
                          scope:this
                      }
                  ]
              }
      }, {
          text: 'Process File Manager',
          iconCls: 'button_menu_ext ss_sprite ss_folder',
          menu: {        // <-- submenu by nested config object
                  items: [
                      // stick any markup in a menu
                      {
                          text: 'mailTemplates',
                          iconCls: 'button_menu_ext ss_sprite ss_email',
                          handler: ProcessMapObj.processFileManager
                      },
                      {
                          text: 'public',
                          iconCls: 'button_menu_ext ss_sprite ss_folder_go',
                          handler: ProcessMapObj.processFileManager
                      }
                  ]
              }
      }]
  });

  canvas.on('contextmenu', function(e) {
      e.stopEvent();
      this.workflow.contextX = e.xy[0];
      this.workflow.contextY = e.xy[1];
      var pmosExtObj = new pmosExt();
      this.contextCanvasMenu.showAt(e.getXY());
  }, this);

  canvas.on('click', function(e) {
      e.stopEvent();
      this.workflow.contextClicked = false;
      if(this.workflow.currentSelection != null)
          this.workflow.disablePorts(this.workflow.currentSelection);
      //Removes Flow menu
      this.workflow.setCurrentSelection(null);
  }, this);

  var simpleToolbar = new Ext.Toolbar('toolbar');
  simpleToolbar.addButton({
      text: 'Save',
      cls: 'x-btn-text-icon scroll-bottom'
  });
  simpleToolbar.addButton({
      text: 'Save As',
      cls: 'x-btn-text-icon scroll-bottom'
  });
  simpleToolbar.addButton({
      text: 'Undo',
      cls: 'x-btn-text-icon'
  });
  simpleToolbar.addButton({
      text: 'Redo',
      cls: 'x-btn-text-icon'
  });

  var menu = new FlowMenu(workflow);
  workflow.addSelectionListener(menu);
  workflow.scrollArea = document.getElementById(centerRegionId).parentNode;
  
  
  
  
  
  var dragsource=new Ext.dd.DragSource("x-shapes-task", {
      ddGroup:'TreeDD',
      dragData:{
          name: "bpmnTask"
      }
  });
  var dragsource=new Ext.dd.DragSource("x-shapes-startEvent", {
      ddGroup:'TreeDD',
      dragData:{
          name: "bpmnEventEmptyStart"
      }
  });
  var dragsource=new Ext.dd.DragSource("x-shapes-interEvent", {
      ddGroup:'TreeDD',
      dragData:{
          name: "bpmnEventEmptyInter"
      }
  });
  var dragsource=new Ext.dd.DragSource("x-shapes-endEvent", {
      ddGroup:'TreeDD',
      dragData:{
          name: "bpmnEventEmptyEnd"
      }
  });
  var dragsource=new Ext.dd.DragSource("x-shapes-gateways", {
      ddGroup:'TreeDD',
      dragData:{
          name: "bpmnGatewayExclusiveData"
      }
  });
  /*var dragsource=new Ext.dd.DragSource("x-shapes-dataobject", {
      ddGroup:'TreeDD',
      dragData:{
          name: "bpmnDataobject"
      }
  });
  var dragsource=new Ext.dd.DragSource("x-shapes-pool", {
      ddGroup:'TreeDD',
      dragData:{
          name: "bpmnPool"
      }
  });*/
  var dragsource=new Ext.dd.DragSource("x-shapes-annotation", {
      ddGroup:'TreeDD',
      dragData:{
          name: "bpmnAnnotation"
      }
  });


  var droptarget=new Ext.dd.DropTarget(centerRegionId,{
      ddGroup:'TreeDD'
  });

  //Creating Pool
  var oPool = new bpmnPool(workflow);
  //workflow.addFigure(oPool,100,70);
  if(workflow.taskNo == '')
      workflow.taskNo= 0; //Initializing Count for the bpmnTask
  var count = 0;
  this.taskName='';
  droptarget.notifyDrop=function(dd, e, data)
  {
      if(data.name)
      {
          var xOffset    = workflow.getAbsoluteX();
          var yOffset    = workflow.getAbsoluteY();
          if(data.name == 'bpmnTask')
          {
              workflow.boundaryEvent = false;
          }
          workflow.task_width='';
          workflow.annotationName='Annotation';
          NewShape = eval("new "+data.name+"(workflow)");
          NewShape.x = e.xy[0];
          NewShape.y = e.xy[1];
          NewShape.limitFlag == false;
          NewShape.actiontype = 'addTask';
          if(data.name == 'bpmnAnnotation'){
            NewShape.actiontype = 'addText';
            workflow.saveShape(NewShape);      //Saving task when user drags and drops it
          }
          else if(data.name == 'bpmnTask'){
            NewShape.actiontype = 'addTask';
            workflow.saveShape(NewShape);      //Saving Annotations when user drags and drops it
          }
          else if(data.name.match(/Event/)){
            NewShape.actiontype = 'addEvent';
            NewShape.mode = 'ddEvent';
            workflow.saveShape(NewShape);      //Saving Annotations when user drags and drops it
          }
          else if(data.name.match(/Gateway/)){
            NewShape.actiontype = 'addGateway';
            NewShape.mode = 'ddGateway';
            workflow.saveShape(NewShape);      //Saving Annotations when user drags and drops it
          }

          var scrollLeft = workflow.getScrollLeft();
          var scrollTop  = workflow.getScrollTop();
          workflow.addFigure(NewShape,e.xy[0]-xOffset+scrollLeft,e.xy[1]-yOffset+scrollTop);
          return true;
      }
  }


  function createConnection(shapes)
  {
      //var totaltask = shapes[0].length;                //shapes[0] is an array for all the tasks
      //var totalgateways = shapes[1].length;          //shapes[1] is an array for all the gateways
      //var totalevents = shapes[2].length;           //shapes[2] is an array for all the events
      if(typeof shapes.routes != 'undefined' && shapes.routes != ''){
      var totalroutes  = shapes.routes.length;           //shapes[3] is an array for all the routes
      for(var i=0;i<=totalroutes-1;i++){
          var sourceid = shapes.routes[i][1];      //getting source id for connection from Routes array
          var targetid = shapes.routes[i][2];      //getting target id for connection from Routes array
          //After creating all the shapes, check one by one shape id
          for(var conn =0; conn < this.workflow.figures.data.length ; conn++){
              if(typeof this.workflow.figures.data[conn] === 'object'){
                  if(sourceid == this.workflow.figures.data[conn].id){
                      sourceObj = this.workflow.figures.data[conn];
                  }
              }
          }
          for(var conn =0; conn < this.workflow.figures.data.length ; conn++){
              if(typeof this.workflow.figures.data[conn] === 'object'){
                  if(targetid == this.workflow.figures.data[conn].id ){
                      targetObj = this.workflow.figures.data[conn];
                  }
              }
          }
          //Making Connections
          var connObj = new DecoratedConnection();
          connObj.setSource(sourceObj.output1);
          connObj.setTarget(targetObj.input2);
          connObj.id = shapes.routes[i][0];
          this.workflow.addFigure(connObj);
      }
    }
 }




  /*function getUrlVars()
  {
      var vars = [], hash;
      var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

      for(var i = 0; i < hashes.length; i++)
      {
          hash = hashes[i].split('=');
          vars.push(hash[0]);
          vars[hash[0]] = hash[1];
      }
      var pro_uid = vars["PRO_UID"];
      return pro_uid;
  }*/

  function createShapes(stringData)
  {

      var responsearray = stringData.split("|");
      var jsonstring = new Array();
      var shapes = new Array();
      //var param = new Array();
      var shapeType = new Array();

      for(var i=0; i<=responsearray.length-1;i++)
      {
          jsonstring[i] = responsearray[i].split(":");
          var param = jsonstring[i][0].replace(" ","");
          shapeType[i] = param;
          switch(param)
          {
              case 'tasks':
                  shapes[param] = new Array();
                  shapes[param] = Ext.util.JSON.decode(jsonstring[i][1]);
                  break;
              case 'gateways':
                  shapes[param] = new Array();
                  shapes[param] = Ext.util.JSON.decode(jsonstring[i][1]);
                  break;
              case 'events':
                  shapes[param] = new Array();
                  shapes[param] = Ext.util.JSON.decode(jsonstring[i][1]);
                  break;
              case 'annotations':
                  shapes[param] = new Array();
                  shapes[param] = Ext.util.JSON.decode(jsonstring[i][1]);
                  break;
              case 'process':
                  shapes[param] = new Array();
                  shapes[param] = Ext.util.JSON.decode(jsonstring[i][1]);
                  break;
              case 'subprocess':
                  shapes[param] = new Array();
                  shapes[param] = Ext.util.JSON.decode(jsonstring[i][1]);
                  break;
              case 'routes':
                  shapes[param] = new Array();
                  shapes[param] = Ext.util.JSON.decode(jsonstring[i][1]);
                  break;
          }
      }
      workflow.taskNo = 0;
      //Create all shapes
      for(var j=0;j< shapeType.length;j++)
      {
          //  _4562.workflow.taskNo=0;
          
          switch(shapeType[j])
          {
              case 'tasks':
                  for(var k=0;k<shapes.tasks.length;k++){
                      var task_boundary = shapes.tasks[k][6];
                      if(task_boundary != null && task_boundary == 'TIMER' && task_boundary != '')
                          workflow.boundaryEvent = true;
                      else
                          workflow.boundaryEvent = false;

                      if(k != 0)
                          workflow.taskNo++;

                      workflow.taskName = shapes.tasks[k][1];
                      workflow.task_width = shapes.tasks[k][4];
                      workflow.task_height = shapes.tasks[k][5];
                      NewShape = eval("new bpmnTask(workflow)");
                      NewShape.x = shapes.tasks[k][2];
                      NewShape.y = shapes.tasks[k][3];
                      NewShape.taskName = shapes.tasks[k][1];
                      workflow.setBoundary(NewShape);
                      workflow.addFigure(NewShape, NewShape.x, NewShape.y);
                      NewShape.html.id = shapes.tasks[k][0];
                      NewShape.id = shapes.tasks[k][0];
                  }
                  break;
              case 'gateways':
                  for(var k=0;k<shapes.gateways.length;k++){
                      var srctype = shapes.gateways[k][1];
                      
                      NewShape = eval("new "+srctype+"(workflow)");
                      NewShape.x = shapes.gateways[k][2];
                      NewShape.y = shapes.gateways[k][3];
                     // workflow.setBoundary(NewShape);
                      workflow.addFigure(NewShape, NewShape.x, NewShape.y);
                      //Setting newshape id to the old shape id
                      NewShape.html.id = shapes.gateways[k][0];
                      NewShape.id = shapes.gateways[k][0];
                  }
                  break;
              case 'events':
                      for(var k=0;k<shapes.events.length;k++){
                          var srceventtype = shapes.events[k][1];
                          var tas_uid = shapes.events[k][4];
                          if(tas_uid != '')
                          {
                              NewShape = eval("new "+srceventtype+"(workflow)");
                              NewShape.x = shapes.events[k][2];
                              NewShape.y = shapes.events[k][3];
                              workflow.setBoundary(NewShape);
                              workflow.addFigure(NewShape, NewShape.x, NewShape.y);
                              //Setting newshape id to the old shape id
                              NewShape.html.id = shapes.events[k][0];
                              NewShape.id = shapes.events[k][0];
                          }
                          else if(tas_uid == ''){
                              NewShape = eval("new "+srceventtype+"(workflow)");
                              NewShape.x = shapes.events[k][2];
                              NewShape.y = shapes.events[k][3];
                              workflow.setBoundary(NewShape);
                              workflow.addFigure(NewShape, NewShape.x, NewShape.y);
                              //Setting newshape id to the old shape id
                              NewShape.html.id = shapes.events[k][0];
                              NewShape.id = shapes.events[k][0];
                          }
                  }
                  break;
              case 'annotations':
                  for(var k=0;k<shapes.annotations.length;k++){
                      workflow.annotationName = shapes.annotations[k][1];
                      workflow.anno_width = shapes.annotations[k][4];
                      workflow.anno_height = shapes.annotations[k][5];
                      NewShape = eval("new bpmnAnnotation(workflow)");
                      NewShape.x = shapes.annotations[k][2];
                      NewShape.y = shapes.annotations[k][3];
                      workflow.setBoundary(NewShape);
                      workflow.addFigure(NewShape, NewShape.x, NewShape.y);
                      //Setting newshape id to the old shape id
                      NewShape.html.id = shapes.annotations[k][0];
                      NewShape.id = shapes.annotations[k][0];
                  }
                  break;
          case 'subprocess':
              for(var k=0;k<shapes.subprocess.length;k++){
                  workflow.subProcessName = shapes.subprocess[k][1];
                  NewShape = eval("new bpmnSubProcess(workflow)");
                  NewShape.x = shapes.subprocess[k][2];
                  NewShape.y = shapes.subprocess[k][3];
                  workflow.setBoundary(NewShape);
                  workflow.addFigure(NewShape, NewShape.x, NewShape.y);
                  //Setting newshape id to the old shape id
                  NewShape.html.id = shapes.subprocess[k][0];
                  NewShape.id = shapes.subprocess[k][0];
              }
              break;
          }
      }
      if(typeof(workflow.taskNo) != 'undefined' && workflow.taskNo != 0)
          workflow.taskNo++;
      return shapes;
  }


  function updateConnection(connArray,oldid,newid)
  {
      for(var i=0;i< connArray.length;i++)
      {
          if(connArray[i][1] == oldid)
              connArray[i][1] = newid;
          else if(connArray[i][2] == oldid)
              connArray[i][2] = newid;
      }
      return connArray;
  }

  function debug(msg)
  {
      var console = document.getElementById("debug");
      console.innerHTML=console.innerHTML+"<br>"+msg;
  }

  saveProcess = function()
  {
      // console.dir(this.workflow);

      var tasks = new Array();
      var events = new Array();
      var gateways = new Array();
      var annotations = new Array();
      var subprocess = new Array();
      var l=0;
      var m=0;
      var n=0;
      var p=0;
      var r=0;

      for(var c = 0; c<this.workflow.figures.data.length; c++)
      {
          if(this.workflow.figures.data[c]){
              if(typeof this.workflow.figures.data[c] === "object")
              {
                  if(this.workflow.figures.data[c].type.match(/Task/))
                  {
                      tasks[l] = new Array();

                      tasks[l][0] = this.workflow.figures.data[c].id;
                      tasks[l][1] = this.workflow.figures.data[c].taskName;
                      tasks[l][2] = this.workflow.figures.data[c].x;
                      tasks[l][3] = this.workflow.figures.data[c].y;
                      tasks[l][4] = this.workflow.figures.data[c].width;
                      tasks[l][5] = this.workflow.figures.data[c].height;
                      tasks[l][6] = 'NORMAL';

                      l++;
                  }

                  if(this.workflow.figures.data[c].type.match(/Gateway/))
                  {
                      gateways[m] = new Array();

                      gateways[m][0] = this.workflow.figures.data[c].id;
                      gateways[m][1] = this.workflow.figures.data[c].type;
                      gateways[m][2] = this.workflow.figures.data[c].x;
                      gateways[m][3] = this.workflow.figures.data[c].y;
                      gateways[m][4] = this.workflow.figures.data[c].width;
                      gateways[m][5] = this.workflow.figures.data[c].height;

                      m++;
                  }

                  if(this.workflow.figures.data[c].type.match(/Event/))
                  {
                      events[n] = new Array();

                      events[n][0] = this.workflow.figures.data[c].id;
                      events[n][1] = this.workflow.figures.data[c].type;
                      events[n][2] = this.workflow.figures.data[c].x;
                      events[n][3] = this.workflow.figures.data[c].y;
                      events[n][4] = this.workflow.figures.data[c].width;
                      events[n][5] = this.workflow.figures.data[c].height;

                      n++;
                  }

                  if(this.workflow.figures.data[c].type.match(/Annotation/))
                  {
                      annotations[p] = new Array();

                      annotations[p][0] = this.workflow.figures.data[c].id;
                      annotations[p][1] = this.workflow.figures.data[c].type;
                      annotations[p][2] = this.workflow.figures.data[c].x;
                      annotations[p][3] = this.workflow.figures.data[c].y;
                      annotations[p][4] = this.workflow.figures.data[c].width;
                      annotations[p][5] = this.workflow.figures.data[c].height;
                      annotations[p][6] = this.workflow.figures.data[c].annotationName;
                      p++;
                  }

                  if(this.workflow.figures.data[c].type.match(/SubProcess/))
                  {
                      subprocess[r] = new Array();

                      subprocess[r][0] = this.workflow.figures.data[c].id;
                      subprocess[r][1] = this.workflow.figures.data[c].subProcessName;
                      subprocess[r][2] = this.workflow.figures.data[c].x;
                      subprocess[r][3] = this.workflow.figures.data[c].y;
                      subprocess[r][4] = this.workflow.figures.data[c].width;
                      subprocess[r][5] = this.workflow.figures.data[c].height;
                      subprocess[r][6] = 'SUBPROCESS';
                      r++;
                  }
              }
          }
      }

      var routes = new Array();
      routes = this.workflow.getCommonConnections(this);

      //array task ['idTask','name','pos_X','pos_y']
      var oldtasks    = [['4043621294c5bda0d9625f4067933182','Task 1','431','131'],['4131425644c5bda073ed062050942935','Task 2','360','274'],['6367816924c6cbc57f36c36034634744','Task 3','540','274']];
      //array gateways ['idGateway','type_gateway','pos_X','pos_y']
      var oldgateways = [['6934720824c5be48364b533001453464','GatewayExclusiveData','461','228']];
      //array gateways ['idEvent','type_event','pos_X','pos_y']
      var oldevents    = [['2081943344c5bdbb38a7ae9016052622','EventEmptyStart','480','95'],['5585460614c5bdbb8629170012669821','EventEmptyEnd','411','347'],['8565089054c5be1e6efeca5077280809','EventEmptyEnd','590','347']];
      //array routes ['id','from','to']
      var oldroutes    = [['2081943344c5bdbb38a7ae9016052982','2081943344c5bdbb38a7ae9016052622','4043621294c5bda0d9625f4067933182'],['4031913164c5bdbb5329a05024607071','4043621294c5bda0d9625f4067933182','6934720824c5be48364b533001453464'],['8851314534c5a6777ee2c96009360450','6934720824c5be48364b533001453464','4131425644c5bda073ed062050942935'],['6934720824c5be48364b533001453464','6934720824c5be48364b533001453464','6367816924c6cbc57f36c36034634744'],['7298598774c5bd9fa3ed1c8035004509','4131425644c5bda073ed062050942935','5585460614c5bdbb8629170012669821'],['8565089054c5be1e6efeca5077280809','6367816924c6cbc57f36c36034634744','8565089054c5be1e6efeca5077280809']];

      var allRoutes = routes
      var aTasks        =  	Ext.util.JSON.encode(tasks);
      var aGateways     = 	Ext.util.JSON.encode(gateways);
      var aEvents       = 	Ext.util.JSON.encode(events);
      var aAnnotations  = 	Ext.util.JSON.encode(annotations);
      var aRoutes       = 	Ext.util.JSON.encode(routes);
      var aSubProcess   = Ext.util.JSON.encode(subprocess);

      //var pro_uid = getUrlVars();
      var loadMask = new Ext.LoadMask(document.body, {msg:'Saving..'});
      loadMask.show();
    
      if(typeof pro_uid != 'undefined')
      {
          Ext.Ajax.request({
              url: 'saveProcess.php',
              method: 'POST',
              success: function(response) {
                var result = Ext.util.JSON.decode(response.responseText);
                loadMask.hide();
                
                if( result.success ) {
                  PMExt.notify(_('ID_PROCESS_SAVE'), result.msg);
                } else {
                  PMExt.error(_('ID_ERROR'), result.msg);
                }
              },
              failure: function(){},
              params: {
                  PRO_UID:pro_uid,
                  tasks: aTasks,
                  gateways:aGateways,
                  events:aEvents,
                  annotations:aAnnotations,
                  subprocess:aSubProcess,
                  routes:aRoutes
              }
          });
      }
      else
          Ext.Msg.alert ('Process ID Undefined');
  }

  function usersSearch()
  {
    var search = Ext.getCmp('usersSearchTxt').getValue();

    Ext.getCmp('usersGrid').store.setBaseParam('search', search);
    Ext.getCmp('usersGrid').store.load({params:{search: search, start : 0 , limit : usersPanelLimit }});
  }

  function groupsSearch()
  {
    var search = Ext.getCmp('groupsSearchTxt').getValue();

    Ext.getCmp('groupsGrid').store.setBaseParam('search', search);
    Ext.getCmp('groupsGrid').store.load({params:{search: search, start : 0 , limit : usersPanelLimit }});
  }

});

Ext.ux.WindowAlwaysOnTop = function(){
       this.init = function(win){
            win.on('deactivate', function(){
               var i=1;
               this.manager.each(function(){i++});
               this.setZIndex(this.manager.zseed + (i*10));
            })
       }
}
