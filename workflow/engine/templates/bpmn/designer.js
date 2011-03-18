/**
 * BPMN Designer v1.1
 * @date Feb 2th, 2011
 * @author Erik A. O. <erik@colosa.com>
 */

var saveProcess;
var usersPanel;
var _TAS_UID;
var _TU_TYPE;
var processObj;
var ProcessMapObj;

Ext.onReady(function(){
    //Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    //Ext.BLANK_IMAGE_URL = '/images/s.gif';
    
    var northPanel = new Ext.Toolbar({
        region: 'north',
        height: 25, // give north and south regions a height
        items: northPanelItems
    })

    var southPanel = {
        // lazily created panel (xtype:'panel' is default)
        region: 'south',
        contentEl: 'south',
        split: true,
        height: 100,
        minSize: 100,
        maxSize: 200,
        collapsible: true,
        title: 'South',
        margins: '0 0 0 0'
    }

    var eastPanel = {
        id: 'eastPanel',
        region: 'east',
        title: '&nbsp;',
        collapsible: true,
        split: true,
        width: 225, // give east and west regions a width
        minSize: 175,
        maxSize: 400,
        margins: '0 3 0 0',
        layout:'border', // specify layout manager for items
        items:            // this TabPanel is wrapped by another Panel so the title will be applied
        [
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
                title : '',
                border: true, // already wrapped so don't add another border
                activeTab   : 0, // second tab initially active
                tabPosition : 'top',
                split  : true,
                collapseMode:'mini',
                //height : 318,
                items  : [
                  propertiesGrid,
                  usersTaskGrid,
                  usersTaskAdHocGrid
                ]
              })
            ]
          }
        ]
    }
    var westPanel = {
        region: 'west',
        id: 'west-panel', // see Ext.getCmp() below
        title: '&nbsp;',
        split: false,
        width: 37,
        minSize: 20,
        maxSize: 400,
        collapsible: true,
        layout: 'table',
        layoutConfig: {columns:1},
        defaults: {frame:true},
        margins: '0 3 3 0',
        items: [
          toolbarPanel,
          actorsPanel
        ]
    }

    var centerPanel = new Ext.Panel({      
        //title: '',
        region: 'center', // a center region is ALWAYS required for border layout
        id: 'designerPanel',
        contentEl: 'center1',
        autoScroll: true,
        tbar: northPanelItems
    })

    var viewport = new Ext.Viewport({
        layout: 'border',
        items:[
            /*new Ext.Toolbar({
              region: 'north',
              height: 25, // give north and south regions a height
              items: mainMenu
            }),*/
            new Ext.TabPanel({
              id: 'mainTabPanel',
              region: 'center',
              deferredRender: false,
              activeTab: 0,     // first tab initially active
              items: [
                {
                  title:'BPMN Designer',
                  id: 'designerTab',
                  layout: 'border',
                  items:[
                    // create instance immediately
                    //northPanel,
                    //southPanel,
                    eastPanel,
                    westPanel,
                    // in this instance the TabPanel is not wrapped by another panel
                    // since no title is needed, this Panel is added directly
                    // as a Container
                    centerPanel
                  ],
                  _setDesignerTitle: function(title) {
                    title = title.length > 20 ? title.substring(0, 20) + '...' : title;
                    Ext.getCmp('designerTab').setTitle('<b>'+_('ID_PROCESSMAP_TITLE')+': </b> ' + title);
                  }
                }
              ],
              _addTab: function(option) {
                alert(option);
              },
              _addTabFrame: tabFrame = function(name, title,  url) {
                title = title.length > 20 ? title.substring(0, 20) + '...' : title;
                tabId = 'pm-tab-'+name ;
                //var uri = 'ajaxListener?action=' + name;
                var TabPanel = Ext.getCmp('mainTabPanel');
                var tab = TabPanel.getItem(tabId);

                if( tab ) {
                  TabPanel.setActiveTab(tabId);
                } else {
                  TabPanel.add({
                    xtype:'iframepanel',
                    id: tabId,
                    title: title,
                    frameConfig:{name: name + 'Frame', id: name + 'Frame'},
                    defaultSrc : url,
                    loadMask:{msg:'Loading...'},
                    bodyStyle:{height:'600px'},
                    width:'1024px',
                    closable:true,
                    autoScroll: true
                  }).show();

                  TabPanel.doLayout();
                }
              }
            })
        ]
    });

    Ext.getCmp('designerTab')._setDesignerTitle(pro_title);
    Ext.fly(document).on("scroll", function(){
      if( usersPanel.isVisible() ) {
        if (usersPanel._scrollPosTimer) {
          clearTimeout(usersPanel._scrollPosTimer);
        }
        usersPanel._scrollPosTimer = setTimeout(function() {
          usersPanel.setPosition(usersPanel._posRelToView[0] + divScroll.scrollLeft, usersPanel._posRelToView[1] + divScroll.scrollTop);
        }, 100);
      }
    });
  

    processObj = new ProcessOptions();
    ProcessMapObj = new ProcessMapContext();
    workflow  = new MyWorkflow("paintarea");
    workflow.setEnableSmoothFigureHandling(false);
    //workflow.scrollArea.width = 2000;
    var listener = new SelectionListener1(workflow);
    workflow.addSelectionListener(listener);


    if(typeof pro_uid !== 'undefined') {
      Ext.Ajax.request({
        url: 'bpmnProxy/openProcess?PRO_UID=' + pro_uid,
        success: function(response) {
          shapesData =  createShapes(response.responseText);
          createConnection(shapesData);

          /**
           * erik: Setting Drop targets from users & groups grids to assignment to tasks
           * for all existing tasks
           */
          var dropEls = Ext.get('paintarea').query('.x-task');
          for(var i = 0; i < dropEls.length; i++)
            new Ext.dd.DropTarget(dropEls[i], {ddGroup:'task-assignment', notifyDrop  : _onDropActors});

        },
        failure: function(){
          Ext.Msg.alert ('Failure');
        }
      });
    }
    


  //Get main into workflow object
  workflow.main = Ext.getCmp('centerRegion');
  //workflow.setSnapToGeometry(false);
  canvas = Ext.get('paintarea');

  contextCanvasMenu = new Ext.menu.Menu({
      items: [
/*      {
          text: 'Edit Process',
          handler: ProcessMapObj.editProcess,
          iconCls: 'button_menu_ext ss_sprite ss_page_white_edit',
          scope: this
      },
*/
      {
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
      }/*, {
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
      }, */
/*
      {
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
      }
*/
      ]
  });

  canvas.on('contextmenu', function(e) {
      e.stopEvent();
      this.workflow.contextX = e.xy[0];
      this.workflow.contextY = e.xy[1];
      var pmosExtObj = new pmosExt();
      this.contextCanvasMenu.showAt(e.getXY());
  }, this);

  /*canvas.on('click', function(e) {
      e.stopEvent();
      this.workflow.contextClicked = false;
      if(this.workflow.currentSelection != null)
          this.workflow.disablePorts(this.workflow.currentSelection);
      //Removes Flow menu
      this.workflow.setCurrentSelection(null);
  }, this);*/

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
  workflow.scrollArea = document.getElementById("center1").parentNode;

  Ext.get('x-shapes-task').child('.x-btn-mc').setStyle('text-align', 'left');
  Ext.get('x-shapes-startEvent').child('.x-btn-mc').setStyle('text-align', 'left');
  Ext.get('x-shapes-interEvent').child('.x-btn-mc').setStyle('text-align', 'left');
  Ext.get('x-shapes-endEvent').child('.x-btn-mc').setStyle('text-align', 'left');
  Ext.get('x-shapes-gateways').child('.x-btn-mc').setStyle('text-align', 'left');
  Ext.get('x-shapes-annotation').child('.x-btn-mc').setStyle('text-align', 'left');
  
  Ext.get('x-pm-users').child('.x-btn-mc').setStyle('text-align', 'left');
  Ext.get('x-pm-groups').child('.x-btn-mc').setStyle('text-align', 'left');
  Ext.get('x-pm-users-adhoc').child('.x-btn-mc').setStyle('text-align', 'left');
  Ext.get('x-pm-groups-adhoc').child('.x-btn-mc').setStyle('text-align', 'left');
  

  /**
   * Setting tooltips ti tollbar items
   */
  new Ext.ToolTip({
      target: 'x-shapes-task',
      title: 'Task',
      trackMouse: true,
      anchor: 'right',
      html: ''
  });
  new Ext.ToolTip({
      target: 'x-shapes-startEvent',
      title: 'Event',
      trackMouse: true,
      anchor: 'right',
      html: 'Start'
  });
  new Ext.ToolTip({
      target: 'x-shapes-interEvent',
      title: 'Event',
      trackMouse: true,
      anchor: 'right',
      html: 'Intermediate'
  });
  new Ext.ToolTip({
      target: 'x-shapes-endEvent',
      title: 'Event',
      trackMouse: true,
      anchor: 'right',
      html: 'End'
  });
  new Ext.ToolTip({
      target: 'x-shapes-gateways',
      title: 'Gateway',
      trackMouse: true,
      anchor: 'right',
      html: ''
  });
  new Ext.ToolTip({
      target: 'x-shapes-annotation',
      title: 'Annotation',
      anchor: 'right',
      trackMouse: true,
      html: ''
  });

  new Ext.ToolTip({
      target: 'x-pm-users',
      title: 'Actors',
      anchor: 'right',
      trackMouse: true,
      html: 'Users'
  });
  new Ext.ToolTip({
      target: 'x-pm-groups',
      title: 'Actors',
      anchor: 'right',
      trackMouse: true,
      html: 'Groups'
  });
  new Ext.ToolTip({
      target: 'x-pm-users-adhoc',
      title: 'Actors',
      anchor: 'right',
      trackMouse: true,
      html: 'Ad Hoc Users'
  });
  new Ext.ToolTip({
      target: 'x-pm-groups-adhoc',
      title: 'Actors',
      anchor: 'right',
      trackMouse: true,
      html: 'Ad Hoc Groups'
  });
  
  
  /**
   * setting drag sources for Toolbar items
   */
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

  var dragsource=new Ext.dd.DragSource("x-shapes-annotation", {
      ddGroup:'TreeDD',
      dragData:{
          name: "bpmnAnnotation"
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
  



  var droptarget=new Ext.dd.DropTarget('paintarea',{
      ddGroup:'TreeDD'
  });

  //Creating Pool
  //var oPool = new bpmnPool(workflow);
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
          var scrollLeft = workflow.getScrollLeft();
          var scrollTop  = workflow.getScrollTop();
          if(data.name == 'bpmnTask') {
              workflow.boundaryEvent = false;
          }
          if(typeof workflow.zoomfactor == 'undefined') {
              workflow.zoomfactor = 1;
          }
          workflow.task_width='';
          workflow.annotationName='Annotation';
          workflow.orgXPos = eval(e.xy[0]/workflow.zoomfactor);
          workflow.orgYPos = eval(e.xy[1]/workflow.zoomfactor);
          NewShape = eval("new "+data.name+"(workflow)");
          NewShape.x = e.xy[0];
          NewShape.y = e.xy[1];
          if(data.name == 'bpmnAnnotation') {
            NewShape.actiontype = 'addText';
            workflow.saveShape(NewShape);      //Saving task when user drags and drops it
          }
          else if(data.name == 'bpmnTask') {
            NewShape.actiontype = 'addTask';
            workflow.saveShape(NewShape);      //Saving Annotations when user drags and drops it
          }
          else if(data.name.match(/Event/)) {
            NewShape.actiontype = 'addEvent';
            NewShape.mode = 'ddEvent';
            workflow.saveShape(NewShape);      //Saving Annotations when user drags and drops it
          }
          else if(data.name.match(/Gateway/)){
            NewShape.actiontype = 'addGateway';
            NewShape.mode = 'ddGateway';
            workflow.saveShape(NewShape);      //Saving Annotations when user drags and drops it
          }
          workflow.addFigure(NewShape,e.xy[0]-xOffset+scrollLeft,e.xy[1]-yOffset+scrollTop);
          return true;
      }
  }

  function createConnection(shapes)
  {
      //var totaltask = shapes[0].length;                //shapes[0] is an array for all the tasks
      //var totalgateways = shapes[1].length;          //shapes[1] is an array for all the gateways
      //var totalevents = shapes[2].length;           //shapes[2] is an array for all the events
      if(typeof shapes.routes != 'undefined' && shapes.routes != '') {
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
          if(targetObj.type == 'bpmnAnnotation') {
            var connObj = new DottedConnection();
            connObj.setSource(sourceObj.output2);
            connObj.setTarget(targetObj.input1);
          }
          else {
            var connObj = new DecoratedConnection();
            connObj.setSource(sourceObj.output1);
            connObj.setTarget(targetObj.input2);
          }

          connObj.id = shapes.routes[i][0];
          this.workflow.addFigure(connObj);
      }
    }
 }

  function createShapes(stringData)
  {
    
      var shapes =  Ext.util.JSON.decode(stringData); //stringData.split("|");
      workflow.taskNo = 0;
      //Create all shapes

      //case 'tasks':
      for(var k=0;k<shapes.tasks.length;k++){
          var task_boundary = shapes.tasks[k][6];
          if(task_boundary != null && task_boundary == 'TIMER' && task_boundary != '')
              workflow.boundaryEvent = true;
          else
              workflow.boundaryEvent = false;

          if(k != 0)
              workflow.taskNo++;

          workflow.taskName    = shapes.tasks[k][1];
          //workaround for Old Processmap widht and height
          if(shapes.tasks[k][4] == 110) {
            workflow.task_width  = 165;
          }
          else {
            workflow.task_width  = shapes.tasks[k][4];
          }
          if(shapes.tasks[k][5] == 60) {
            workflow.task_height = 40;
          }
          else {
            workflow.task_height = shapes.tasks[k][5];
          }
          workflow.orgXPos = shapes.tasks[k][2];
          workflow.orgYPos = shapes.tasks[k][3];
          NewShape = eval("new bpmnTask(workflow)");
          NewShape.x = shapes.tasks[k][2];
          NewShape.y = shapes.tasks[k][3];
          NewShape.taskName = shapes.tasks[k][1];
          workflow.setBoundary(NewShape);
          workflow.addFigure(NewShape, NewShape.x, NewShape.y);
          NewShape.html.id = shapes.tasks[k][0];
          NewShape.id = shapes.tasks[k][0];
      }

      //case 'gateways':
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

      //case 'events':
      for(var k=0;k<shapes.events.length;k++) {
          var srceventtype = shapes.events[k][1];
          var tas_uid = shapes.events[k][4];
          if(tas_uid != '') {
              NewShape = eval("new "+srceventtype+"(workflow)");
              NewShape.x = shapes.events[k][2];
              NewShape.y = shapes.events[k][3];
              workflow.setBoundary(NewShape);
              workflow.addFigure(NewShape, NewShape.x, NewShape.y);
              //Setting newshape id to the old shape id
              NewShape.html.id = shapes.events[k][0];
              NewShape.id = shapes.events[k][0];
          }
          else if(tas_uid == '') {
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

      //case 'annotations':
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

      //case 'subprocess':
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
      var aTasks        =   Ext.util.JSON.encode(tasks);
      var aGateways     =   Ext.util.JSON.encode(gateways);
      var aEvents       =   Ext.util.JSON.encode(events);
      var aAnnotations  =   Ext.util.JSON.encode(annotations);
      var aRoutes       =   Ext.util.JSON.encode(routes);
      var aSubProcess   = Ext.util.JSON.encode(subprocess);

      //var pro_uid = getUrlVars();
      var loadMask = new Ext.LoadMask(document.body, {msg:'Saving..'});
      loadMask.show();

      if(typeof pro_uid != 'undefined')
      {
          Ext.Ajax.request({
              url: '../bpmn/saveProcess.php',
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
  
});

//erik: selection Listener
SelectionListener1 = function(_43b1){
  this.workflow = _43b1;
  this.lastSelectedItem = null;
};

SelectionListener1.prototype.type = "SelectionListener1";
SelectionListener1.prototype.onSelectionChanged = function(_43b2) {
	if( this.workflow.currentSelection )
  if (this.lastSelectedItem !== this.workflow.currentSelection.id ) {
    if( this.workflow.currentSelection.type == 'bpmnTask' || this.workflow.currentSelection.type == 'bpmnSubProcess' ) {
      this.lastSelectedItem = this.workflow.currentSelection.id;
      //console.log('selecting task: '+this.workflow.currentSelection.taskName);
      //erik: to set selected the respective node on eastTreePanel
      var currEatTreeNode = Ext.getCmp('eastPanelTree').getNodeById(this.workflow.currentSelection.id);
      if( typeof currEatTreeNode != 'undefined' )
        currEatTreeNode.select();
    }
  }
};

